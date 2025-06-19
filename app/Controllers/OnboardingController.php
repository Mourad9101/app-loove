<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;

class OnboardingController extends Controller {
    private $userModel;
    private $steps = [
        'gender',
        'birth_date',
        'orientation',
        'looking_for',
        'photos',
        'interests',
        'bio',
        'location'
    ];

    public function __construct() {
        $this->userModel = new User();
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        // Si l'utilisateur a déjà complété l'onboarding, le rediriger vers son profil
        if ($this->userModel->hasCompletedOnboarding($_SESSION['user_id'])) {
            $this->redirect('/profile');
            return;
        }

        if (!isset($_SESSION['onboarding_step'])) {
            $_SESSION['onboarding_step'] = 0;
        }
    }

    public function index() {
        $currentStep = $_SESSION['onboarding_step'];
        
        if ($currentStep >= count($this->steps)) {
            $this->completeOnboarding();
            return;
        }

        $method = $this->steps[$currentStep];
        $this->$method();
    }

    private function nextStep() {
        $_SESSION['onboarding_step']++;
        
        // Si c'est la dernière étape, terminer l'onboarding
        if ($_SESSION['onboarding_step'] >= count($this->steps)) {
            $this->completeOnboarding();
            return;
        }

        $this->redirect('/onboarding');
    }

    private function photos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadDir = 'public/uploads/';
            $mainImage = null;
            $uploadedImages = [];
            
            // Vérifier si le dossier existe, sinon le créer
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
                error_log("Création du dossier uploads : " . $uploadDir);
            }
            
            if (isset($_FILES['photos'])) {
                error_log("Fichiers reçus : " . print_r($_FILES['photos'], true));
                
                foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                        $originalName = $_FILES['photos']['name'][$key];
                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                        $fileName = uniqid() . '.' . $extension;
                        $filePath = $uploadDir . $fileName;
                        
                        // Vérifier le type de fichier
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        $fileType = $_FILES['photos']['type'][$key];
                        
                        error_log("Traitement du fichier : " . $originalName);
                        error_log("Type de fichier : " . $fileType);
                        
                        if (!in_array($fileType, $allowedTypes)) {
                            error_log("Type de fichier non autorisé : " . $fileType);
                            continue;
                        }
                        
                        if (move_uploaded_file($tmp_name, $filePath)) {
                            error_log("Fichier déplacé avec succès vers : " . $filePath);
                            $uploadedImages[] = $fileName;
                            if (!$mainImage) {
                                $mainImage = $fileName;
                            }
                            
                            // Définir les permissions du fichier
                            chmod($filePath, 0644);
                        } else {
                            error_log("Erreur lors du déplacement du fichier : " . error_get_last()['message']);
                        }
                    } else {
                        error_log("Erreur lors de l'upload : " . $_FILES['photos']['error'][$key]);
                    }
                }
            }
            
            // Si aucune image n'a été uploadée, utiliser l'image par défaut
            if (empty($uploadedImages)) {
                $mainImage = 'default.jpg';
                error_log("Aucune image uploadée, utilisation de l'image par défaut");
            }
            
            // Mettre à jour les données de l'utilisateur
            $_SESSION['onboarding_data']['image'] = $mainImage;
            $_SESSION['onboarding_data']['additional_images'] = $uploadedImages;
            
            error_log("Images enregistrées dans la session : " . print_r([
                'main' => $mainImage,
                'additional' => $uploadedImages
            ], true));
            
            $this->nextStep();
        }

        $this->render('onboarding/photos', [
            'title' => 'Ajoutez vos plus belles photos',
            'progress' => ($this->getCurrentProgress()),
            'description' => 'Ajoutez au moins 2 photos pour continuer'
        ]);
    }

    private function gender() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['gender'] = $_POST['gender'] ?? null;
            $this->nextStep();
        }

        $this->render('onboarding/gender', [
            'title' => 'Je suis...',
            'progress' => ($this->getCurrentProgress()),
            'options' => [
                'H' => 'Un homme',
                'F' => 'Une femme',
                'NB' => 'Non-binaire',
                'other' => 'Autre'
            ]
        ]);
    }

    private function birth_date() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['birth_date'] = $_POST['birth_date'] ?? null;
            $this->nextStep();
        }

        $this->render('onboarding/birth_date', [
            'title' => 'Quelle est votre date de naissance ?',
            'progress' => ($this->getCurrentProgress())
        ]);
    }

    private function orientation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['orientation'] = $_POST['orientation'] ?? null;
            $this->nextStep();
        }

        $this->render('onboarding/orientation', [
            'title' => 'Votre orientation',
            'progress' => ($this->getCurrentProgress()),
            'options' => [
                'heterosexual' => 'Hétérosexuel(le)',
                'homosexual' => 'Homosexuel(le)',
                'bisexual' => 'Bisexuel(le)',
                'pansexual' => 'Pansexuel(le)',
                'asexual' => 'Asexuel(le)'
            ]
        ]);
    }

    private function looking_for() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['looking_for'] = $_POST['looking_for'] ?? null;
            $this->nextStep();
        }

        $this->render('onboarding/looking_for', [
            'title' => 'Je recherche...',
            'progress' => ($this->getCurrentProgress()),
            'options' => [
                'relationship' => 'Une relation sérieuse',
                'friendship' => 'De l\'amitié',
                'casual' => 'Des rencontres occasionnelles'
            ]
        ]);
    }

    private function interests() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['interests'] = $_POST['interests'] ?? [];
            $this->nextStep();
        }

        $this->render('onboarding/interests', [
            'title' => 'Vos centres d\'intérêt',
            'progress' => ($this->getCurrentProgress()),
            'interests' => [
                'sport' => 'Sport',
                'music' => 'Musique',
                'cinema' => 'Cinéma',
                'reading' => 'Lecture',
                'travel' => 'Voyage',
                'cooking' => 'Cuisine',
                'art' => 'Art',
                'gaming' => 'Jeux vidéo',
                'technology' => 'Technologie',
                'nature' => 'Nature'
            ]
        ]);
    }

    private function bio() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['onboarding_data']['bio'] = $_POST['bio'] ?? '';
            $this->nextStep();
        }

        $this->render('onboarding/bio', [
            'title' => 'Parlez-nous de vous',
            'progress' => ($this->getCurrentProgress()),
            'description' => 'Une courte description qui vous représente'
        ]);
    }

    private function location() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données de la ville
            $city = $_POST['city'] ?? '';
            $latitude = $_POST['latitude'] ?? '';
            $longitude = $_POST['longitude'] ?? '';

            if (empty($city) || empty($latitude) || empty($longitude)) {
                $_SESSION['error'] = "Veuillez sélectionner une ville dans la liste";
                $this->redirect('/onboarding');
                return;
            }

            $_SESSION['onboarding_data']['city'] = $city;
            $_SESSION['onboarding_data']['latitude'] = $latitude;
            $_SESSION['onboarding_data']['longitude'] = $longitude;
            $this->nextStep();
        }

        $this->render('onboarding/city', [
            'title' => 'Où habitez-vous ?',
            'progress' => ($this->getCurrentProgress()),
            'description' => 'Trouvez des personnes près de chez vous'
        ]);
    }

    private function getCurrentProgress() {
        return [
            'current' => $_SESSION['onboarding_step'] + 1,
            'total' => count($this->steps)
        ];
    }

    private function completeOnboarding() {
        error_log("Début de completeOnboarding");
        error_log("Données d'onboarding: " . print_r($_SESSION['onboarding_data'], true));
        
        if ($this->userModel->updateOnboardingStep($_SESSION['user_id'], $_SESSION['onboarding_data'])) {
            error_log("Mise à jour réussie, nettoyage des données de session");
            
            // Nettoyer les données d'onboarding de la session
            unset($_SESSION['onboarding_step']);
            unset($_SESSION['onboarding_data']);
            
            $_SESSION['success'] = "Profil complété avec succès !";
            
            error_log("Redirection vers le profil");
            header('Location: ' . BASE_URL . '/profile');
            exit;
        } else {
            error_log("Erreur lors de la mise à jour des données d'onboarding");
            $_SESSION['error'] = "Une erreur est survenue lors de la sauvegarde de votre profil";
            header('Location: ' . BASE_URL . '/onboarding');
            exit;
        }
    }
} 