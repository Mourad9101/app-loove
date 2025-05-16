<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;

class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
    }

    public function index() {
        // Récupérer les informations de l'utilisateur
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['error'] = "Erreur lors de la récupération du profil";
            $this->redirect('/login');
            return;
        }

        $this->render('profile/index', [
            'title' => 'Mon Profil',
            'user' => $user
        ]);
    }

    public function edit() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        $this->render('profile/edit', [
            'title' => 'Modifier mon profil',
            'user' => $user
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
            return;
        }

        // Traiter la mise à jour du profil
        $data = [
            'first_name' => trim($_POST['first_name']),
            'last_name' => trim($_POST['last_name']),
            'city' => trim($_POST['city']),
            'bio' => trim($_POST['bio']),
            'age' => (int)$_POST['age'],
            'gender' => $_POST['gender'],
            'gemstone' => $_POST['gemstone']
        ];

        if ($this->userModel->update($_SESSION['user_id'], $data)) {
            $_SESSION['success'] = "Profil mis à jour avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Traiter la mise à jour du profil
            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'city' => trim($_POST['city']),
                'bio' => trim($_POST['bio']),
                'age' => (int)$_POST['age'],
                'gender' => $_POST['gender'],
                'gemstone' => $_POST['gemstone']
            ];

            if ($this->userModel->update($_SESSION['user_id'], $data)) {
                $_SESSION['success'] = "Profil mis à jour avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
            }
            $this->redirect('/profile');
            return;
        }

        $user = $this->userModel->findById($_SESSION['user_id']);
        $this->render('profile/edit', [
            'title' => 'Modifier mon profil',
            'user' => $user
        ]);
    }
} 