<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;

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
} 