<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\NotificationService;

class NotificationsController extends Controller {
    private $notificationService;

    public function __construct() {
        try {
            $this->notificationService = new NotificationService();
        } catch (\Exception $e) {
            error_log("Erreur d'initialisation du NotificationService: " . $e->getMessage());
            // Affiche l'erreur dans la réponse JSON pour le debug
            $this->jsonResponse([
                'error' => "Erreur d'initialisation du NotificationService",
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function get() {
        if (!$this->isAjax()) {
            $this->redirect('/');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->jsonResponse(['error' => 'Non autorisé'], 401);
            return;
        }

        try {
            if (!$this->notificationService) {
                throw new \Exception('Service de notification non disponible');
            }
            
            $notifications = $this->notificationService->getForUser($userId);
            $this->jsonResponse(['notifications' => $notifications]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Erreur lors de la récupération des notifications',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead() {
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->jsonResponse(['error' => 'Non autorisé'], 401);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $notificationIds = $data['ids'] ?? [];

        if (empty($notificationIds)) {
            $this->jsonResponse(['error' => 'Aucun ID de notification fourni'], 400);
            return;
        }

        try {
            if (!$this->notificationService) {
                throw new \Exception('Service de notification non disponible');
            }
            
            $success = $this->notificationService->markAsRead($notificationIds, $userId);
            $this->jsonResponse(['success' => $success]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Erreur lors du marquage des notifications'], 500);
        }
    }

    public function getUnreadCount() {
        if (!$this->isAjax()) {
            $this->redirect('/');
            return;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $this->jsonResponse(['error' => 'Non autorisé'], 401);
            return;
        }

        try {
            if (!$this->notificationService) {
                throw new \Exception('Service de notification non disponible');
            }
            
            $count = $this->notificationService->getUnreadCount($userId);
            $this->jsonResponse(['count' => $count]);
        } catch (\Exception $e) {
            $this->jsonResponse([
                'error' => 'Erreur lors de la récupération du nombre de notifications',
                'details' => $e->getMessage()
            ], 500);
        }
    }
} 