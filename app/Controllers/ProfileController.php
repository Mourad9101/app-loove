<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;
use app\Models\UserMatch;

class ProfileController extends Controller {
    private $userModel;
    private $userMatchModel;

    public function __construct() {
        // Appelle la méthode d'authentification du contrôleur parent
        $this->requireAuth(); 
        
        $this->userModel = new User();
        $this->userMatchModel = new UserMatch();
    }

    public function index(int $id = null) {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        $userIdToDisplay = $id ?? $_SESSION['user_id'];

        // Si ce n'est pas mon profil
        if ($userIdToDisplay != $_SESSION['user_id']) {
            // Vérifier si on vient de la page visiteurs, qu'on est premium et que ce user a vu mon profil
            $currentUser = $this->userModel->findById($_SESSION['user_id']);
            if (
                !isset($_GET['from']) || $_GET['from'] !== 'views'
                || !($currentUser['is_premium'] ?? false)
            ) {
                $this->redirect('/profile');
                return;
            }
            // Vérifier que $userIdToDisplay a bien vu mon profil
            $profileViews = $this->userModel->getProfileViews($_SESSION['user_id']);
            $canView = false;
            foreach ($profileViews as $viewer) {
                if ($viewer['viewer_id'] == $userIdToDisplay) {
                    $canView = true;
                    break;
                }
            }
            if (!$canView) {
                $this->redirect('/profile');
                return;
            }
        }
        // Récupérer les informations de l'utilisateur à afficher
        $user = $this->userModel->findById($userIdToDisplay);
        
        if (!$user) {
            $_SESSION['error'] = "Profil non trouvé";
            $this->redirect('/profile'); // Rediriger vers son propre profil ou une page d'erreur
            return;
        }

        // Récupérer l'utilisateur courant (celui qui est connecté)
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        if (!$currentUser) {
            $this->redirect('/logout'); // Si l'utilisateur courant n'est pas trouvé, le déconnecter
            return;
        }

        if ($userIdToDisplay == $_SESSION['user_id']) {
            //Profil de l'utilisateur
            $this->render('profile/index', [
                'title' => 'Mon Profil',
                'user' => $user,
                'currentUser' => $currentUser // Passer l'utilisateur courant
            ]);
        } else {
            // C'est le profil d'un autre utilisateur
            $isMatch = $this->userMatchModel->isMatch($currentUser['id'], $user['id']);
            $this->render('profile/other_profile', [
                'title' => 'Profil de ' . htmlspecialchars($user['first_name']),
                'user' => $user,
                'currentUser' => $currentUser, // Passer l'utilisateur courant
                'isMatch' => $isMatch // Passer le statut de match
            ]);
        }
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

        // Gestion de l'upload de la photo principale
        if (isset($_FILES['main_photo']) && $_FILES['main_photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/uploads/';
            $originalName = $_FILES['main_photo']['name'];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['main_photo']['tmp_name'], $filePath)) {
                $data['image'] = $fileName;
            }
        }

        // Gestion des nouvelles photos supplémentaires
        $newAdditionalImages = [];
        if (isset($_FILES['additional_photos'])) {
            foreach ($_FILES['additional_photos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['additional_photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $originalName = $_FILES['additional_photos']['name'][$key];
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $extension;
                    $filePath = 'public/uploads/' . $fileName;
                    
                    if (move_uploaded_file($tmp_name, $filePath)) {
                        $newAdditionalImages[] = $fileName;
                    }
                }
            }
        }

        // Récupérer les images supplémentaires existantes
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        $existingImages = [];
        if (!empty($currentUser['additional_images'])) {
            $existingImages = json_decode($currentUser['additional_images'], true) ?? [];
        }

        // Supprimer les photos demandées
        if (isset($_POST['remove_photo_index'])) {
            $removeIndex = (int)$_POST['remove_photo_index'];
            if (isset($existingImages[$removeIndex])) {
                unset($existingImages[$removeIndex]);
                $existingImages = array_values($existingImages); // Réindexer le tableau
            }
        }

        // Combiner les images existantes avec les nouvelles
        $allImages = array_merge($existingImages, $newAdditionalImages);
        if (!empty($allImages)) {
            $data['additional_images'] = json_encode($allImages);
        }

        if ($this->userModel->update($_SESSION['user_id'], $data)) {
            $_SESSION['success'] = "Profil mis à jour avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
        }
        
        $this->redirect('/profile');
    }

    public function profileViews(): void
    {
        $currentUser = $this->userModel->findById($_SESSION['user_id']);

        // Vérifier si l'utilisateur est premium
        if (!$currentUser || !($currentUser['is_premium'] ?? 0)) {
            $_SESSION['error'] = "Vous devez être un utilisateur premium pour accéder à cette fonctionnalité.";
            $this->redirect('/profile');
            return;
        }

        $profileViews = $this->userModel->getProfileViews($_SESSION['user_id']);
        
        $this->render('profile/views', [
            'title' => 'Mon Profil - Visiteurs',
            'profileViews' => $profileViews
        ]);
    }
} 