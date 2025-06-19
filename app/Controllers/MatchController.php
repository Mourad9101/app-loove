<?php
namespace app\Controllers;

use app\Core\Controller;
use app\Models\UserMatch;
use app\Models\User;
use Pusher\Pusher;

class MatchController extends Controller {
    private $matchModel;
    private $userModel;
    private $pusher;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        $this->matchModel = new UserMatch();
        $this->userModel = new User();
        
        // Vérification des variables d'environnement Pusher
        $requiredEnvVars = ['PUSHER_APP_KEY', 'PUSHER_APP_SECRET', 'PUSHER_APP_ID', 'PUSHER_APP_CLUSTER'];
        $missingVars = [];
        
        foreach ($requiredEnvVars as $var) {
            if (!isset($_ENV[$var]) || empty($_ENV[$var])) {
                $missingVars[] = $var;
            }
        }
        
        if (!empty($missingVars)) {
            error_log('Variables d\'environnement Pusher manquantes : ' . implode(', ', $missingVars));
            $this->pusher = null;
        } else {
            // Initialisation de Pusher
            $this->pusher = new Pusher(
                $_ENV['PUSHER_APP_KEY'],
                $_ENV['PUSHER_APP_SECRET'],
                $_ENV['PUSHER_APP_ID'],
                [
                    'cluster' => $_ENV['PUSHER_APP_CLUSTER'],
                    'useTLS' => true
                ]
            );
        }
    }

    public function index(): void {
        $matches = $this->matchModel->getUserMatches($_SESSION['user_id']);
        $this->render('match/index', ['matches' => $matches]);
    }

    public function discover(): void {
        
        // Vérifier si l'utilisateur existe
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        
        if (!$currentUser) {
            $this->redirect('/logout');
            return;
        }

        // Vérification si l'utilisateur a complété son onboarding
        if (!$currentUser['has_completed_onboarding']) {
            $this->redirect('/onboarding');
            return;
        }

        $filters = [];
        // Appliquer les filtres avancés si l'utilisateur est premium
        if (($currentUser['is_premium'] ?? false)) {
            $filters['min_age'] = filter_input(INPUT_GET, 'min_age', FILTER_VALIDATE_INT);
            $filters['max_age'] = filter_input(INPUT_GET, 'max_age', FILTER_VALIDATE_INT);
            $filters['gender'] = filter_input(INPUT_GET, 'gender', FILTER_UNSAFE_RAW);
            $filters['gemstone'] = filter_input(INPUT_GET, 'gemstone', FILTER_UNSAFE_RAW);
            $filters['radius'] = filter_input(INPUT_GET, 'radius', FILTER_VALIDATE_FLOAT);

            // Nettoyer les filtres vides
            $filters = array_filter($filters, function($value) { return $value !== null && $value !== '' && $value !== false; });
        }

        $potentialMatches = $this->userModel->getPotentialMatches($_SESSION['user_id'], 0, 10, $filters);
        
        // Enregistrer les vues de profil pour chaque utilisateur potentiel
        foreach ($potentialMatches as $matchUser) {
            $this->userModel->recordProfileView($currentUser['id'], $matchUser['id']);
        }
        
        // Vérifier si BASE_URL est défini
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/Evergem3');
        }

        // S'assurer que potentialMatches est un tableau
        if (!is_array($potentialMatches)) {
            $potentialMatches = [];
        }
        
        $this->render('match/discover', [
            'users' => $potentialMatches,
            'BASE_URL' => BASE_URL,
            'currentUser' => $currentUser
        ]);
        
    }

    public function like(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUserId = $_SESSION['user_id'];
            $currentUser = $this->userModel->findById($currentUserId);

            if (!$currentUser) {
                $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.'], 404);
                return;
            }

            // Logique de limite de matchs pour les utilisateurs non premium
            if (!$currentUser['is_premium']) {
                $today = new \DateTime();
                $lastMatchDate = $currentUser['last_match_date'] ? new \DateTime($currentUser['last_match_date']) : null;
                $dailyMatchesCount = (int)$currentUser['daily_matches_count'];

                if ($lastMatchDate && $lastMatchDate->format('Y-m-d') !== $today->format('Y-m-d')) {
                    $dailyMatchesCount = 0;
                }

                if ($dailyMatchesCount >= 6) {
                    $this->json(['success' => false, 'error' => 'Limite de 6 matchs quotidiens atteinte. Passez Premium pour des matchs illimités !']);
                    return;
                }
            }

            $likedUserId = (int)($_POST['user_id'] ?? 0);
            
            if ($likedUserId > 0) {
                // Créer le like
                $success = $this->matchModel->createLike($currentUserId, $likedUserId);
                
                if ($success) {
                    // Pour les utilisateurs non premium, incrémenter le compteur
                    if (!$currentUser['is_premium']) {
                        $dailyMatchesCount++;
                        $this->userModel->updateDailyMatchCount($currentUserId, $dailyMatchesCount, $today->format('Y-m-d'));
                    }

                    // Vérifier si c'est un match mutuel
                    $isMatch = $this->matchModel->isMatch($currentUserId, $likedUserId);
                    
                    if ($isMatch && $this->pusher !== null) {
                        // Créer une notification via Pusher pour l'utilisateur qui a été liké
                        $this->pusher->trigger('notifications-channel', 'new-notification', [
                            'user_id' => $likedUserId,
                            'type' => 'match',
                            'message' => 'Vous avez un nouveau Match avec ' . $currentUser['first_name'] . ' !',
                            'source_user_id' => $currentUserId
                        ]);

                        // Créer une notification via Pusher pour l'utilisateur actuel
                        $this->pusher->trigger('notifications-channel', 'new-notification', [
                            'user_id' => $currentUserId,
                            'type' => 'match',
                            'message' => 'Vous avez un nouveau Match avec ' . $this->userModel->findById($likedUserId)['first_name'] . ' !',
                            'source_user_id' => $likedUserId
                        ]);
                    }

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
            $currentUserId = $_SESSION['user_id'];
            $passedUserId = (int)($_POST['user_id'] ?? 0);
            
            if ($passedUserId > 0) {
                // Enregistrer le pass en base de données
                $success = $this->userModel->createPass($currentUserId, $passedUserId);
                
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

    public function loadMoreProfiles(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $offset = (int)($_POST['offset'] ?? 0);
            $limit = (int)($_POST['limit'] ?? 10);

            $currentUserId = $_SESSION['user_id'];
            $currentUser = $this->userModel->findById($currentUserId);

            $filters = [];
            // Appliquer les filtres avancés si l'utilisateur est premium
            if (($currentUser['is_premium'] ?? false)) {
                $filters['min_age'] = filter_input(INPUT_POST, 'min_age', FILTER_VALIDATE_INT);
                $filters['max_age'] = filter_input(INPUT_POST, 'max_age', FILTER_VALIDATE_INT);
                $filters['gender'] = filter_input(INPUT_POST, 'gender', FILTER_UNSAFE_RAW);
                $filters['gemstone'] = filter_input(INPUT_POST, 'gemstone', FILTER_UNSAFE_RAW);
                $filters['radius'] = filter_input(INPUT_POST, 'radius', FILTER_VALIDATE_FLOAT);

                // Nettoyer les filtres vides
                $filters = array_filter($filters, function($value) { return $value !== null && $value !== '' && $value !== false; });
            }
            
            $potentialMatches = $this->userModel->getPotentialMatches(
                $_SESSION['user_id'],
                $offset,
                $limit,
                $filters
            );
            
            $this->json([
                'success' => true,
                'profiles' => $potentialMatches,
                'hasMore' => count($potentialMatches) === $limit
            ]);
        }
    }

    public function gem(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentUserId = $_SESSION['user_id'];
            $currentUser = $this->userModel->findById($currentUserId);

            if (!$currentUser) {
                $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.'], 404);
                return;
            }

            // Logique de limite de matchs pour les utilisateurs non premium
            if (!$currentUser['is_premium']) {
                $today = new \DateTime();
                $lastMatchDate = $currentUser['last_match_date'] ? new \DateTime($currentUser['last_match_date']) : null;
                $dailyMatchesCount = (int)$currentUser['daily_matches_count'];

                if ($lastMatchDate && $lastMatchDate->format('Y-m-d') !== $today->format('Y-m-d')) {
                    // Jour différent, réinitialiser le compteur
                    $dailyMatchesCount = 0;
                }

                if ($dailyMatchesCount >= 6) {
                    $this->json(['success' => false, 'error' => 'Limite de 6 matchs quotidiens atteinte. Passez Premium pour des matchs illimités !']);
                    return;
                }
            }

            $gemmedUserId = (int)($_POST['user_id'] ?? 0);

            if ($gemmedUserId > 0) {
                $success = $this->matchModel->createLike($currentUserId, $gemmedUserId);
                if ($success) {
                    // Pour les utilisateurs non premium, incrémenter le compteur
                    if (!$currentUser['is_premium']) {
                        $dailyMatchesCount++;
                        $this->userModel->updateDailyMatchCount($currentUserId, $dailyMatchesCount, $today->format('Y-m-d'));
                    }

                    $isMatch = $this->matchModel->isMatch($currentUserId, $gemmedUserId);
                    if (!$isMatch) {
                        $this->matchModel->createLike($gemmedUserId, $currentUserId);
                        $isMatch = true;
                        
                        if ($this->pusher !== null) {
                            // Créer une notification via Pusher pour l'utilisateur qui a été "gemmé"
                            $this->pusher->trigger('notifications-channel', 'new-notification', [
                                'user_id' => $gemmedUserId,
                                'type' => 'match',
                                'message' => 'Vous avez un nouveau Match (Gem) avec ' . $currentUser['first_name'] . ' !',
                                'source_user_id' => $currentUserId
                            ]);
                        }
                    }
                    
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
} 