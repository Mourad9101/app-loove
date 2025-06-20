<?php

// Activer la mise en mémoire tampon de sortie dès le début
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger les variables d'environnement depuis le fichier .env
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Chargement de la configuration avant tout
require_once __DIR__ . '/config/config.php';

// Configuration des sessions avant de les démarrer
ini_set('session.cookie_lifetime', 3600);
ini_set('session.gc_maxlifetime', 3600);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = ROOT_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Fichier non trouvé : " . $file);
    }
});

try {
    // Initialisation du routeur
    $router = new app\Core\Router();

    // Routes définies
    $router->addRoute('GET', BASE_URL . '/', 'HomeController@index');
    
    // Routes d'authentification
    $router->addRoute('GET', BASE_URL . '/login', 'AuthController@loginForm');
    $router->addRoute('POST', BASE_URL . '/login', 'AuthController@login');
    $router->addRoute('GET', BASE_URL . '/register', 'AuthController@registerForm');
    $router->addRoute('POST', BASE_URL . '/register', 'AuthController@register');
    $router->addRoute('GET', BASE_URL . '/logout', 'AuthController@logout');
    $router->addRoute('GET', BASE_URL . '/forgot-password', 'AuthController@forgotPasswordForm');
    $router->addRoute('POST', BASE_URL . '/forgot-password', 'AuthController@forgotPassword');
    $router->addRoute('GET', BASE_URL . '/reset-password', 'AuthController@resetPasswordForm');
    $router->addRoute('POST', BASE_URL . '/reset-password', 'AuthController@resetPassword');
    
    // Routes de profil
    $router->addRoute('GET', BASE_URL . '/profile', 'ProfileController@index');
    $router->addRoute('GET', BASE_URL . '/profile/edit', 'ProfileController@edit');
    $router->addRoute('POST', BASE_URL . '/profile/edit', 'ProfileController@update');
    $router->addRoute('GET', BASE_URL . '/profile/views', 'ProfileController@profileViews');
    $router->addRoute('GET', BASE_URL . '/profile/{id}', 'ProfileController@index');
    
    // Routes d'onboarding - Nouvelle version
    $router->addRoute('GET', BASE_URL . '/onboarding', 'OnboardingController@index');
    $router->addRoute('POST', BASE_URL . '/onboarding', 'OnboardingController@index');
    
    // Routes de l'application principale
    $router->addRoute('GET', BASE_URL . '/matches', 'MatchController@index');
    $router->addRoute('GET', BASE_URL . '/discover', 'MatchController@discover');
    $router->addRoute('POST', BASE_URL . '/matches/like', 'MatchController@like');
    $router->addRoute('POST', BASE_URL . '/matches/pass', 'MatchController@pass');
    $router->addRoute('POST', BASE_URL . '/matches/gem', 'MatchController@gem');
    $router->addRoute('POST', BASE_URL . '/matches/load-more', 'MatchController@loadMoreProfiles');
    $router->addRoute('POST', BASE_URL . '/matches/unmatch', 'MatchController@unmatch');
    $router->addRoute('GET', BASE_URL . '/messages', 'MessagesController@index');
    $router->addRoute('GET', BASE_URL . '/messages/getNewMessages', 'MessagesController@getNewMessages');
    $router->addRoute('GET', BASE_URL . '/messages/{id}', 'MessagesController@show');
    $router->addRoute('POST', BASE_URL . '/messages/send', 'MessagesController@sendMessage');

    // Routes de paiement et abonnements
    $router->addRoute('GET', BASE_URL . '/payment', 'PaymentController@index');
    $router->addRoute('POST', BASE_URL . '/payment/createCheckoutSession', 'PaymentController@createCheckoutSession');
    $router->addRoute('GET', BASE_URL . '/payment/success', 'PaymentController@success');
    $router->addRoute('GET', BASE_URL . '/payment/cancel', 'PaymentController@cancel');
    $router->addRoute('POST', BASE_URL . '/payment/cancelSubscription', 'PaymentController@cancelSubscription');
    $router->addRoute('POST', BASE_URL . '/payment/webhook', 'PaymentController@webhook');

    // Routes d'administration
    $router->addRoute('GET', BASE_URL . '/admin', 'AdminController@index');
    $router->addRoute('GET', BASE_URL . '/admin/dashboard', 'AdminController@dashboard');
    $router->addRoute('POST', BASE_URL . '/admin/user/{id}/toggle-status', 'AdminController@toggleUserStatus');
    $router->addRoute('POST', BASE_URL . '/admin/user/{id}/toggle-premium-status', 'AdminController@toggleUserPremiumStatus');
    $router->addRoute('POST', BASE_URL . '/admin/user/{id}/toggle-admin-status', 'AdminController@toggleAdminStatus');
    $router->addRoute('POST', BASE_URL . '/admin/user/{id}/delete', 'AdminController@deleteUser');

    // Routes de gestion des signalements
    $router->addRoute('GET', BASE_URL . '/admin/reports', 'AdminController@reports');
    $router->addRoute('POST', BASE_URL . '/admin/report/{id}/update-status', 'AdminController@updateReportStatus');

    // Route pour les statistiques d'abonnement
    $router->addRoute('GET', BASE_URL . '/admin/subscription-stats', 'AdminController@getSubscriptionStats');

    // Route de signalement des utilisateurs
    $router->addRoute('POST', BASE_URL . '/report/{id}', 'ReportController@create');

    // Routes pour les notifications
    $router->addRoute('GET', BASE_URL . '/notifications/get', 'NotificationsController@get');
    $router->addRoute('POST', BASE_URL . '/notifications/mark-as-read', 'NotificationsController@markAsRead');
    $router->addRoute('GET', BASE_URL . '/notifications/test', 'NotificationsController@test');
    $router->addRoute('GET', BASE_URL . '/notifications/unread-count', 'NotificationsController@getUnreadCount');

    // Dispatch de la requête
    $router->dispatch();
} catch (Exception $e) {
    error_log($e->getMessage());

    // Détecter si la requête est une requête AJAX
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        // Pour les requêtes AJAX, retourner une réponse JSON d'erreur
        header('Content-Type: application/json');
        http_response_code(500); // Erreur interne du serveur
        echo json_encode([
            'status' => 'error',
            'message' => 'Une erreur est survenue lors du traitement de votre requête.',
            'debug' => DEBUG ? $e->getMessage() . "\n" . $e->getTraceAsString() : null
        ]);
    } else {
        // Pour les requêtes normales, afficher la page d'erreur ou la trace complète
        if (DEBUG) {
            echo "Une erreur est survenue : " . $e->getMessage() . "<br>";
            echo "Trace : <pre>" . $e->getTraceAsString() . "</pre>";
        } else {
            include APP_PATH . '/Views/errors/500.php';
        }
    }
    exit();
} finally {
    // À la fin de chaque requête, si la mise en mémoire tampon de sortie est active
    // et qu'il y a du contenu, vider et désactiver sans l'envoyer si la requête est AJAX
    // et que nous sommes censés envoyer du JSON.
    if (ob_get_length() > 0 && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        ob_end_clean(); // Supprime le contenu du tampon sans l'envoyer
    } else if (ob_get_length() > 0) {
        ob_end_flush(); // Envoie le contenu du tampon et le désactive
    }
} 