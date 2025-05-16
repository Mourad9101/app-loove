<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\UserMatch;
use app\Models\User;

class MatchController extends Controller {
    private $matchModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            error_log("Utilisateur non connecté - Redirection vers login");
            $this->redirect('/login');
        }
        error_log("MatchController construit pour l'utilisateur ID: " . $_SESSION['user_id']);
        $this->matchModel = new UserMatch();
        $this->userModel = new User();
    }

    public function index(): void {
        $matches = $this->matchModel->getUserMatches($_SESSION['user_id']);
        $this->render('match/index', ['matches' => $matches]);
    }

    public function discover(): void {
        error_log("=== DÉBUT MÉTHODE DISCOVER ===");
        error_log("Session ID : " . session_id());
        error_log("Variables de session : " . print_r($_SESSION, true));
        
        // Vérifier si l'utilisateur existe
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        error_log("Données de l'utilisateur courant : " . print_r($currentUser, true));
        
        if (!$currentUser) {
            error_log("Utilisateur non trouvé dans la base de données");
            $this->redirect('/logout');
            return;
        }

        // Vérifier si l'utilisateur a complété son onboarding
        if (!$currentUser['has_completed_onboarding']) {
            error_log("L'utilisateur n'a pas complété son onboarding");
            $this->redirect('/onboarding');
            return;
        }

        $potentialMatches = $this->userModel->getPotentialMatches($_SESSION['user_id']);
        error_log("Profils récupérés dans discover : " . print_r($potentialMatches, true));
        error_log("Nombre de profils trouvés : " . count($potentialMatches));

        // Vérifier si BASE_URL est défini
        if (!defined('BASE_URL')) {
            error_log("ATTENTION: BASE_URL n'est pas défini!");
            define('BASE_URL', '/Evergem3');
        }
        error_log("BASE_URL : " . BASE_URL);

        // Debug des variables disponibles
        error_log("Variables disponibles pour la vue : " . print_r([
            'users' => $potentialMatches,
            'BASE_URL' => BASE_URL,
            'currentUser' => $currentUser
        ], true));
        
        $this->render('match/discover', [
            'users' => $potentialMatches,
            'BASE_URL' => BASE_URL,
            'currentUser' => $currentUser
        ]);
        
        error_log("=== FIN MÉTHODE DISCOVER ===");
    }

    public function like(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $likedUserId = (int)($_POST['user_id'] ?? 0);
            error_log("Tentative de like - User ID: " . $_SESSION['user_id'] . " like User ID: " . $likedUserId);
            
            if ($likedUserId > 0) {
                $success = $this->matchModel->createLike($_SESSION['user_id'], $likedUserId);
                
                if ($success) {
                    $isMatch = $this->matchModel->isMatch($_SESSION['user_id'], $likedUserId);
                    $this->json([
                        'success' => true,
                        'match' => $isMatch
                    ]);
                    return;
                }
            }
            
            $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue'
            ]);
        }
    }

    public function pass(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['user_id'] ?? 0);
            error_log("Tentative de pass - User ID: " . $_SESSION['user_id'] . " pass User ID: " . $userId);
            
            if ($userId > 0) {
                $this->json(['success' => true]);
                return;
            }
            
            $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue'
            ]);
        }
    }

    public function unmatch(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $unmatchUserId = (int)($_POST['user_id'] ?? 0);
            
            if ($unmatchUserId > 0) {
                $success = $this->matchModel->deleteLike($_SESSION['user_id'], $unmatchUserId);
                
                if ($success) {
                    $this->json(['success' => true]);
                    return;
                }
            }
            
            $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue'
            ]);
        }
    }
} 