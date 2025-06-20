<?php

namespace app\Models;

use app\Core\Database;
use PDO;

class Message
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Envoie un nouveau message.
     * @param int $senderId L'ID de l'expéditeur.
     * @param int $recipientId L'ID du destinataire.
     * @param string $content Le contenu du message.
     * @return int|bool L'ID du message inséré ou false en cas d'échec.
     */
    public function createMessage(int $senderId, int $recipientId, string $content)
    {
        $sql = "INSERT INTO messages (sender_id, recipient_id, message_content) VALUES (:sender_id, :recipient_id, :message_content)";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
            $stmt->bindParam(':message_content', $content, PDO::PARAM_STR);
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'envoi du message : " . $e->getMessage());
            return false;
        }
    }
    
    public function getConversationMessages(int $user1Id, int $user2Id, int $offset = 0, int $limit = 50)
    {
        $sql = "SELECT * FROM messages 
                WHERE (sender_id = :user1_id AND recipient_id = :user2_id) 
                OR (sender_id = :user2_id AND recipient_id = :user1_id) 
                ORDER BY timestamp ASC 
                LIMIT :limit OFFSET :offset";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user1_id', $user1Id, PDO::PARAM_INT);
            $stmt->bindParam(':user2_id', $user2Id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des messages : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marque les messages comme lus.
     * @param int $recipientId L'ID de l'utilisateur qui a reçu et lu les messages.
     * @param int $senderId L'ID de l'expéditeur des messages.
     * @return bool True en cas de succès, false sinon.
     */
    public function markMessagesAsRead(int $recipientId, int $senderId)
    {
        $sql = "UPDATE messages SET is_read = TRUE 
                WHERE sender_id = :sender_id AND recipient_id = :recipient_id AND is_read = FALSE";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindParam(':recipient_id', $recipientId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            error_log("Erreur lors du marquage des messages comme lus : " . $e->getMessage());
            return false;
        }
    }
} 