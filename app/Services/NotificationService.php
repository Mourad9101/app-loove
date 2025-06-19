<?php

namespace App\Services;

use App\Models\Notification;
use \Pusher\PushNotifications\PushNotifications;

class NotificationService {
    private $notificationModel;
    private $beamsClient;

    public function __construct() {
        $this->notificationModel = new Notification();
        
        // Initialisation de Pusher Beams si les credentials sont disponibles
        if (defined('PUSHER_NOTIF_INSTANCE_ID') && defined('PUSHER_NOTIF_PRIMARY_KEY')) {
            $this->beamsClient = new PushNotifications([
                "instanceId" => PUSHER_NOTIF_INSTANCE_ID,
                "secretKey" => PUSHER_NOTIF_PRIMARY_KEY,
            ]);
        }
    }

    /**
     * Crée une nouvelle notification et l'envoie via Pusher Beams
     */
    public function createAndSend($data) {
        // Création en base de données
        $notificationId = $this->notificationModel->create([
            'user_id' => $data['user_id'],
            'from_user_id' => $data['from_user_id'] ?? null,
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'link' => $data['link'] ?? null,
        ]);

        if ($notificationId && $this->beamsClient) {
            // Construction des URLs complètes
            $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];
            $iconUrl = $baseUrl . "/Evergem3/public/images/Logo Evergem.png";
            $deepLink = $baseUrl . "/Evergem3" . ($data['link'] ?? '');
            $senderImageUrl = $baseUrl . "/Evergem3/public/uploads/" . ($data['sender_image'] ?? 'default.jpg');

            // Configuration de la notification push
            $notification = [
                "fcm" => [
                    "notification" => [
                        "title" => $data['title'] ?? "Nouvelle notification",
                        "body" => $data['message'],
                        "icon" => $iconUrl,
                        "click_action" => $deepLink
                    ]
                ],
                "web" => [
                    "notification" => [
                        "title" => $data['title'] ?? "Nouvelle notification",
                        "body" => $data['message'],
                        "icon" => $iconUrl,
                        "deep_link" => $deepLink,
                        "sender_image" => $senderImageUrl
                    ]
                ],
                "data" => [
                    "sender_image" => $data['sender_image'] ?? 'default.jpg'  // On garde le nom du fichier simple pour le JS
                ]
            ];

            error_log('DEBUG NOTIF PAYLOAD: ' . print_r($notification, true));

            try {
                $this->beamsClient->publishToInterests(
                    ["user-" . $data['user_id']],
                    $notification
                );
            } catch (\Exception $e) {
                error_log("Erreur Pusher Beams: " . $e->getMessage());
            }
        }

        return $notificationId;
    }

    /**
     * Récupère les notifications d'un utilisateur
     */
    public function getForUser($userId, $limit = 20) {
        return $this->notificationModel->getForUser($userId, $limit);
    }

    /**
     * Marque des notifications comme lues
     */
    public function markAsRead($notificationIds, $userId) {
        return $this->notificationModel->markAsRead($notificationIds, $userId);
    }

    /**
     * Récupère le nombre de notifications non lues
     */
    public function getUnreadCount($userId) {
        return $this->notificationModel->getUnreadCount($userId);
    }

    /**
     * Crée une notification de match
     */
    public function createMatchNotification($userId, $matchedUserId) {
        $matchedUser = (new \App\Models\User())->findById($matchedUserId);
        if (!$matchedUser) return false;

        $matchedUserName = $matchedUser['first_name'] ?? 'Utilisateur';
        $matchedUserImage = $matchedUser['image'] ?? 'default.jpg';

        return $this->createAndSend([
            'user_id' => $userId,
            'from_user_id' => $matchedUserId,
            'title' => 'Nouveau Match !',
            'message' => "Vous avez un nouveau match avec {$matchedUserName} !",
            'type' => 'match',
            'link' => "/matches",
            'sender_image' => $matchedUserImage
        ]);
    }

    /**
     * Crée une notification de message
     */
    public function createMessageNotification($userId, $fromUserId, $messagePreview) {
        error_log('DEBUG NOTIF START: Méthode createMessageNotification appelée');
        
        $fromUser = (new \App\Models\User())->findById($fromUserId);
        if (!$fromUser) return false;

        $fromUserName = $fromUser['first_name'] ?? 'Utilisateur';
        $fromUserImage = $fromUser['image'] ?? 'default.jpg';

        // Log de debug pour vérifier l'image récupérée
        error_log('DEBUG NOTIF: fromUserId=' . $fromUserId . ' image=' . $fromUserImage);
        error_log('DEBUG NOTIF USER DATA: ' . print_r($fromUser, true));

        return $this->createAndSend([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'title' => 'Nouveau message',
            'message' => "{$fromUserName} vous a envoyé : " . substr($messagePreview, 0, 50) . "...",
            'type' => 'message',
            'link' => "/messages/{$fromUserId}",
            'sender_image' => $fromUserImage
        ]);
    }

    /**
     * Crée une notification de visite de profil
     */
    public function createProfileVisitNotification($userId, $visitorId) {
        $visitor = (new \App\Models\User())->findById($visitorId);
        if (!$visitor) return false;

        $visitorName = $visitor['first_name'] ?? 'Utilisateur';
        $visitorImage = $visitor['image'] ?? 'default.jpg';

        return $this->createAndSend([
            'user_id' => $userId,
            'from_user_id' => $visitorId,
            'title' => 'Visite de profil',
            'message' => "{$visitorName} a visité votre profil",
            'type' => 'profile_visit',
            'link' => "/profile/views",
            'sender_image' => $visitorImage
        ]);
    }


    public function cleanOldNotifications() {
        return $this->notificationModel->execute(
            "DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        );
    }
} 