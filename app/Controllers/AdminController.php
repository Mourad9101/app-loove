<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Models\User;
use app\Models\ReportModel;

class AdminController extends Controller
{
    private $userModel;
    private $reportModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $this->userModel = new User();
        $this->reportModel = new ReportModel();

        // Vérification du rôle d'administrateur
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        if (!$currentUser || !$currentUser['is_admin']) {
            // Rediriger vers la page d'accueil ou une page d'erreur d'accès
            $this->redirect('/'); // Ou une page spécifique comme '/unauthorized'
            return;
        }
    }

    public function index(): void
    {
        // Récupérer tous les utilisateurs
        $users = $this->userModel->getAllUsers();
        $subscriptionModel = new \app\Models\Subscription();
        $revenue = $subscriptionModel->getTotalRevenue();
        $this->render('admin/users', ['users' => $users, 'revenue' => $revenue]);
    }

    public function toggleUserStatus(int $userId):
        void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->userModel->findById($userId);
            if ($user) {
                $newStatus = !$user['is_active'];
                $success = $this->userModel->setUserStatus($userId, $newStatus);
                if ($success) {
                    $this->json(['success' => true, 'new_status' => $newStatus]);
                } else {
                    $this->json(['success' => false, 'error' => 'Échec de la mise à jour du statut.']);
                }
            } else {
                $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.']);
            }
        }
    }

    public function deleteUser(int $userId):
        void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->userModel->deleteUser($userId);
            if ($success) {
                $this->json(['success' => true]);
            } else {
                $this->json(['success' => false, 'error' => 'Échec de la suppression de l\'utilisateur.']);
            }
        }
    }

    public function toggleAdminStatus(int $userId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("toggleAdminStatus : Début de la requête POST pour userId: " . $userId);
            $input = json_decode(file_get_contents('php://input'), true);
            $isAdmin = $input['is_admin'] ?? null;

            error_log("toggleAdminStatus : is_admin reçu : " . var_export($isAdmin, true));

            if (is_null($isAdmin) || !is_bool($isAdmin)) {
                error_log("toggleAdminStatus : Statut d\'administrateur invalide.");
                $this->json(['success' => false, 'error' => 'Statut d\'administrateur invalide.']);
                return;
            }

            // Empêcher un administrateur de se rétrograder lui-même
            if ($userId == $_SESSION['user_id'] && !$isAdmin) {
                error_log("toggleAdminStatus : Tentative de rétrogradation de soi-même.");
                $this->json(['success' => false, 'error' => 'Vous ne pouvez pas vous rétrograder vous-même.']);
                return;
            }

            $user = $this->userModel->findById($userId);
            if ($user) {
                error_log("toggleAdminStatus : Utilisateur trouvé. Ancien statut admin: " . ($user['is_admin'] ? 'Oui' : 'Non'));
                $success = $this->userModel->setIsAdmin($userId, $isAdmin);
                if ($success) {
                    error_log("toggleAdminStatus : Statut admin mis à jour avec succès pour userId: " . $userId . " vers: " . ($isAdmin ? 'Oui' : 'Non'));
                    $this->json(['success' => true, 'new_status' => $isAdmin]);
                } else {
                    error_log("toggleAdminStatus : Échec de la mise à jour du statut d\'administrateur pour userId: " . $userId);
                    $this->json(['success' => false, 'error' => 'Échec de la mise à jour du statut d\'administrateur.']);
                }
            } else {
                error_log("toggleAdminStatus : Utilisateur non trouvé pour userId: " . $userId);
                $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.']);
            }
        }
    }

    public function reports(): void
    {
        $reports = $this->reportModel->getAllReports();
        $this->render('admin/reports', ['reports' => $reports]);
    }

    public function updateReportStatus(int $reportId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $status = $input['status'] ?? '';

            if (!in_array($status, ['pending', 'reviewed', 'resolved'])) {
                $this->json(['success' => false, 'error' => 'Statut de signalement invalide.']);
                return;
            }

            $success = $this->reportModel->updateReportStatus($reportId, $status);
            if ($success) {
                $this->json(['success' => true, 'new_status' => $status]);
            } else {
                $this->json(['success' => false, 'error' => 'Échec de la mise à jour du statut du signalement.']);
            }
        }
    }

    public function toggleUserPremiumStatus(int $userId): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("toggleUserPremiumStatus : Début de la requête POST pour userId: " . $userId);
            $input = json_decode(file_get_contents('php://input'), true);
            $isPremium = $input['is_premium'] ?? null;

            error_log("toggleUserPremiumStatus : is_premium reçu : " . var_export($isPremium, true));

            if (is_null($isPremium) || !is_bool($isPremium)) {
                error_log("toggleUserPremiumStatus : Statut premium invalide.");
                $this->json(['success' => false, 'error' => 'Statut premium invalide.']);
                return;
            }

            $user = $this->userModel->findById($userId);
            if ($user) {
                error_log("toggleUserPremiumStatus : Utilisateur trouvé. Ancien statut premium: " . ($user['is_premium'] ? 'Oui' : 'Non'));
                $success = $this->userModel->setIsPremium($userId, $isPremium);
                if ($success) {
                    error_log("toggleUserPremiumStatus : Statut premium mis à jour avec succès pour userId: " . $userId . " vers: " . ($isPremium ? 'Oui' : 'Non'));
                    $this->json(['success' => true, 'new_status' => $isPremium]);
                } else {
                    error_log("toggleUserPremiumStatus : Échec de la mise à jour du statut premium pour userId: " . $userId);
                    $this->json(['success' => false, 'error' => 'Échec de la mise à jour du statut premium.']);
                }
            } else {
                error_log("toggleUserPremiumStatus : Utilisateur non trouvé pour userId: " . $userId);
                $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.']);
            }
        }
    }
} 