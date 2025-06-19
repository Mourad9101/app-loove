<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Models\MessageModel;
use app\Models\UserMatch;
use app\Models\User;
use Pusher\Pusher;
use App\Services\NotificationService;

class MessagesController extends Controller {
    private $messageModel;
    private $userMatchModel;
    private $userModel;
    private $pusher;

    public function __construct() {
        error_log("DEBUG: CONSTRUCTEUR MessagesController appelé.");
        error_log("DEBUG: Session content at MessagesController constructor: " . print_r($_SESSION, true));
        $this->requireAuth(); 
        $this->messageModel = new MessageModel();
        $this->userMatchModel = new UserMatch();
        $this->userModel = new User();

        // Initialisation de Pusher
        $pusherConfig = require __DIR__ . '/../../config/pusher.php';
        $this->pusher = new Pusher(
            $pusherConfig['key'],
            $pusherConfig['secret'],
            $pusherConfig['app_id'],
            [
                'cluster' => $pusherConfig['cluster'],
                'useTLS' => $pusherConfig['useTLS']
            ]
        );
    }

    public function index() {
        $currentUserId = $_SESSION['user_id'];
        $matches = $this->userMatchModel->getUserMatches($currentUserId);

        $this->render('messages/index', [
            'matches' => $matches,
            'currentUser' => $this->userModel->findById($currentUserId)
        ]);
    }

    public function show($recipientId) {
        $currentUserId = $_SESSION['user_id'];
        $recipientId = (int)$recipientId;

        $currentUser = $this->userModel->findById($currentUserId);
        $recipientUser = $this->userModel->findById($recipientId);

        if (!$currentUser || !$recipientUser) {
            $this->redirect('/messages');
            return;
        }
        
        if (!$this->userMatchModel->isMatch($currentUserId, $recipientId)) {
            $this->redirect('/messages');
            return;
        }
        
        // Marquer les messages reçus comme lus AVANT de récupérer les messages
        $db = \app\Core\Database::getInstance();
        $db->executeQuery(
            "UPDATE messages SET is_read = 1, read_at = NOW() WHERE sender_id = ? AND receiver_id = ? AND is_read = 0",
            [$recipientId, $currentUserId]
        );

        // Récupérer tous les messages entre les deux utilisateurs (dans les deux sens)
        $sql = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC";
        $stmt = $db->executeQuery($sql, [$currentUserId, $recipientId, $recipientId, $currentUserId]);
        $messages = $stmt->fetchAll();

        $this->render('messages/chat_view_new', [
            'currentUser' => $currentUser,
            'recipientUser' => $recipientUser,
            'messages' => $messages,
            'conversationId' => $recipientId
        ]);
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['conversation_id'], $_POST['sender_id'], $_POST['receiver_id'], $_POST['message_content'])) {
            $conversationId = (int)$_POST['conversation_id'];
            $senderId = (int)$_POST['sender_id'];
            $receiverId = (int)$_POST['receiver_id'];
            $messageContent = htmlspecialchars(trim($_POST['message_content']));

            // Vérification de l'utilisateur qui envoie le message
            $senderUser = $this->userModel->findById($senderId);
            if (!$senderUser) {
                $this->json(['status' => 'error', 'message' => 'Expéditeur non trouvé.'], 404);
                return;
            }

            // Si l'expéditeur n'est pas premium, vérifier s'il y a un match mutuel
            if (!($senderUser['is_premium'] ?? false)) {
                if (!$this->userMatchModel->isMatch($senderId, $receiverId)) {
                    $this->json(['status' => 'error', 'message' => 'Vous devez matcher pour envoyer un message.'], 403);
                    return;
                }
            }

            if ($this->messageModel->createMessage($conversationId, $senderId, $receiverId, $messageContent)) {
                // Envoyer l'événement Pusher pour le message
                $data = [
                    'message' => $messageContent,
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'sender_name' => $senderUser['first_name'] ?? 'Quelqu\'un',
                    'sender_image' => $senderUser['image'] ?? 'default.jpg',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->pusher->trigger('chat-channel', 'new-message', $data);

                // Envoyer l'événement Pusher pour la notification
                $notificationData = [
                    'user_id' => $receiverId,
                    'type' => 'message',
                    'message' => $senderUser['first_name'] . ' vous a envoyé un nouveau message.',
                    'source_user_id' => $senderId,
                    'sender_name' => $senderUser['first_name'],
                    'sender_image' => $senderUser['image'] ?? 'default.jpg',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->pusher->trigger('notifications-channel', 'new-notification', $notificationData);

                // Ajout : notification push Beams
                $notificationService = new NotificationService();
                $notificationService->createMessageNotification($receiverId, $senderId, $messageContent);

                $this->json(['status' => 'success', 'message' => 'Message envoyé']);
            } else {
                $this->json(['status' => 'error', 'message' => 'Erreur lors de l\'envoi du message'], 500);
            }
        } else {
            $this->json(['status' => 'error', 'message' => 'Données invalides'], 400);
        }
    }

    public function getNewMessages() {
        if (isset($_GET['conversation_id'], $_GET['last_message_id'])) {
            $conversationId = (int)$_GET['conversation_id'];
            $lastMessageId = (int)$_GET['last_message_id'];

            $messageModel = new MessageModel();
            $newMessages = $messageModel->getMessagesByConversationId($conversationId, $lastMessageId);
            
            // Récupérer le dernier ID de message pour le prochain polling
            $latestMessageId = $messageModel->getLatestMessageId($conversationId);

            $this->json(['status' => 'success', 'messages' => $newMessages, 'latest_message_id' => $latestMessageId]);
        } else {
            $this->json(['status' => 'error', 'message' => 'Paramètres manquants'], 400);
        }
    }
} 