<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;
use Google\Client;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function loginForm() {
        return $this->render('auth/login', [
            'title' => 'Connexion - ' . APP_NAME
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires";
            $this->redirect('/login');
            return;
        }

        $loggedUser = $this->userModel->authenticate($email, $password);

        if ($loggedUser) {
            $_SESSION['user_id'] = $loggedUser['id'];
            $_SESSION['user_email'] = $loggedUser['email'];
            $_SESSION['is_admin'] = $loggedUser['is_admin'] ?? false;
            $_SESSION['is_premium'] = $loggedUser['is_premium'] ?? false;
            $_SESSION['success'] = "Connexion réussie !";
            
            // Vérifier si l'onboarding est complété
            if (!$this->userModel->hasCompletedOnboarding($loggedUser['id'])) {
                $this->redirect('/onboarding');
            } else {
                $this->redirect('/profile');
            }
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect";
            $this->redirect('/login');
        }
    }

    public function registerForm() {
        return $this->render('auth/register', [
            'title' => 'Inscription - ' . APP_NAME
        ]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            $_SESSION['error'] = "Tous les champs sont obligatoires";
            $_SESSION['old_input'] = ['email' => $email, 'name' => $name];
            $this->redirect('/register');
            return;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas";
            $_SESSION['old_input'] = ['email' => $email, 'name' => $name];
            $this->redirect('/register');
            return;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères";
            $_SESSION['old_input'] = ['email' => $email, 'name' => $name];
            $this->redirect('/register');
            return;
        }
        
        // Vérifier si l'email existe déjà
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = "Cet email est déjà utilisé";
            $_SESSION['old_input'] = ['email' => $email, 'name' => $name];
            $this->redirect('/register');
            return;
        }

        // Créer l'utilisateur
        $userId = $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        if ($userId) {
            // Connecter automatiquement l'utilisateur
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_email'] = $email;
            $_SESSION['success'] = "Inscription réussie ! Complétez votre profil pour commencer.";
            
            // Rediriger vers l'onboarding
            $this->redirect('/onboarding');
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
            $_SESSION['old_input'] = ['email' => $email, 'name' => $name];
            $this->redirect('/register');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }

    public function googleLogin() {
        $client = new Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');

        $authUrl = $client->createAuthUrl();
        $this->redirect($authUrl);
    }

    public function googleCallback() {
        if (!isset($_GET['code'])) {
            $_SESSION['error'] = "Erreur de connexion Google : Aucun code d'autorisation.";
            $this->redirect('/login');
            return;
        }

        $client = new Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        
        try {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            $client->setAccessToken($token);

            $oauth2 = new \Google_Service_Oauth2($client);
            $google_user_info = $oauth2->userinfo->get();

            $email = $google_user_info->email;
            $name = $google_user_info->name;
            $google_id = $google_user_info->id;

            $user = $this->userModel->findByEmail($email);

            if ($user) {
                // L'utilisateur existe, connectez-le
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = $user['is_admin'] ?? false;
                $_SESSION['is_premium'] = $user['is_premium'] ?? false;
                $_SESSION['success'] = "Connexion Google réussie !";

                // Vérifier si l'onboarding est complété
                if (!$this->userModel->hasCompletedOnboarding($user['id'])) {
                    $this->redirect('/onboarding');
                } else {
                    $this->redirect('/profile');
                }
            } else {
                // L'utilisateur n'existe pas, créez un nouveau compte
                $userId = $this->userModel->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => null,
                    'google_id' => $google_id
                ]);

                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['success'] = "Inscription Google réussie ! Complétez votre profil pour commencer.";
                    $this->redirect('/onboarding');
                } else {
                    $_SESSION['error'] = "Erreur lors de l'inscription avec Google.";
                    $this->redirect('/login');
                }
            }

        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur d'authentification Google : " . $e->getMessage();
            error_log("Google Auth Error: " . $e->getMessage());
            $this->redirect('/login');
        }
    }
} 