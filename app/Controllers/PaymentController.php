<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\Subscription;
use app\Models\User;

class PaymentController extends Controller {
    private $subscriptionModel;
    private $userModel;
    private $stripe;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        
        $this->subscriptionModel = new Subscription();
        $this->userModel = new User();
        
        // Initialiser Stripe
         require_once 'vendor/autoload.php';
         $this->stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
    }

    public function index(): void {
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        $subscription = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
        
        $this->render('payment/index', [
            'user' => $currentUser,
            'subscription' => $subscription
        ]);
    }

    public function createCheckoutSession(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        try {
            // Vérification que Stripe est initialisé
            if (!$this->stripe) {
                 require_once 'vendor/autoload.php';
                 $this->stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
            }
            
            // Récupérer la clé publique et les informations de prix depuis le fichier de configuration
            $stripeConfig = require_once ROOT_PATH . '/config/stripe.php';
            $premiumPriceId = $stripeConfig['price_id'];
            error_log("Stripe Price ID sent: " . $premiumPriceId);
            $productName = $stripeConfig['products']['premium']['name'];
            $productDescription = $stripeConfig['products']['premium']['description'];

            // Créer une session de paiement Stripe
            $checkoutSession = $this->stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $premiumPriceId, // Utilisation de l'ID de prix
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => NGROK_URL . '/payment/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => NGROK_URL . '/payment/cancel',
                'customer_email' => $this->userModel->findById($_SESSION['user_id'])['email'],
                'metadata' => [
                    'user_id' => $_SESSION['user_id']
                ],
                'automatic_tax' => ['enabled' => true],
            ]);

            $this->json(['sessionId' => $checkoutSession->id]);
        } catch (\Exception $e) {
            error_log("Erreur Stripe : " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            $this->json(['error' => 'Erreur lors de la création de la session de paiement'], 500);
        }
    }

    public function success(): void {
        $sessionId = $_GET['session_id'] ?? null;
        
        if (!$sessionId) {
            $this->redirect('/payment');
            return;
        }

        try {
            // Assurez-vous que Stripe est initialisé
            if (!$this->stripe) {
                require_once 'vendor/autoload.php';
                $this->stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
            }

            // Récupérer les détails de la session
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                // Récupérer l'ID de l'utilisateur à partir des métadonnées
                $userId = $session->metadata->user_id ?? $_SESSION['user_id'];

                // Créer l'abonnement dans notre base de données
                $subscription = $this->subscriptionModel->createSubscription(
                    (int)$userId,
                    $session->subscription,
                    'premium',
                    9.99
                );
                
                if ($subscription) {
                    $_SESSION['success'] = 'Félicitations ! Vous êtes maintenant Premium !';
                    $this->redirect('/profile');
                    return;
                }
            }
            
            $_SESSION['error'] = 'Erreur lors de l\'activation de votre abonnement';
            $this->redirect('/payment');
        } catch (\Exception $e) {
            error_log("Erreur lors du traitement du succès : " . $e->getMessage());
            $_SESSION['error'] = 'Erreur lors du traitement du paiement';
            $this->redirect('/payment');
        }
    }

    public function cancel(): void {
        $_SESSION['error'] = 'Paiement annulé. Vous pouvez réessayer à tout moment.';
        $this->redirect('/payment');
    }

    public function cancelSubscription(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        try {
            // Assurez-vous que Stripe est initialisé
            if (!$this->stripe) {
                require_once 'vendor/autoload.php';
                $this->stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);
            }

            $subscription = $this->subscriptionModel->getActiveSubscription($_SESSION['user_id']);
            
            if (!$subscription) {
                $this->json(['error' => 'Aucun abonnement actif trouvé'], 404);
                return;
            }

            // Annuler l'abonnement sur Stripe
            $this->stripe->subscriptions->cancel($subscription['stripe_subscription_id']);
            
            // Annuler dans notre base de données
            $success = $this->subscriptionModel->cancelSubscription($_SESSION['user_id']);
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Abonnement annulé avec succès']);
            } else {
                $this->json(['error' => 'Erreur lors de l\'annulation'], 500);
            }
        } catch (\Exception $e) {
            error_log("Erreur lors de l'annulation : " . $e->getMessage());
            $this->json(['error' => 'Erreur lors de l\'annulation de l\'abonnement'], 500);
        }
    }

    public function webhook(): void {
        $payload = file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
        }

        http_response_code(200);
    }

    private function handleSubscriptionCreated($subscription) {
        $userId = $subscription->metadata->user_id ?? null;
        if ($userId) {
            $this->subscriptionModel->createSubscription(
                (int)$userId,
                $subscription->id,
                'premium',
                $subscription->plan->amount / 100 // Convertir les centimes en euros
            );
        }
    }

    private function handleSubscriptionUpdated($subscription) {
        $status = $subscription->status;
        $this->subscriptionModel->updateSubscriptionStatus($subscription->id, $status);
    }

    private function handleSubscriptionDeleted($subscription) {
        $this->subscriptionModel->updateSubscriptionStatus($subscription->id, 'cancelled');
    }

    private function handlePaymentFailed($invoice) {
        // Marquer l'abonnement comme en retard de paiement
        $this->subscriptionModel->updateSubscriptionStatus($invoice->subscription, 'past_due');
    }

    private function handlePaymentSucceeded($invoice) {
        // Mettre à jour l'abonnement comme actif si le paiement a réussi
        $this->subscriptionModel->updateSubscriptionStatus($invoice->subscription, 'active');
    }
} 