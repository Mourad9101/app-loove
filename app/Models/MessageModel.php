<?php

namespace app\Models;

use app\Core\Model;
use app\Core\Database;

class MessageModel extends Model
{
    protected $table = 'messages';

    public function createMessage(int $conversationId, int $senderId, int $receiverId, string $messageContent): bool
    {
        $db = Database::getInstance();
        $sql = "INSERT INTO messages (conversation_id, sender_id, receiver_id, message_content) VALUES (?, ?, ?, ?)";
        try {
            $db->executeQuery($sql, [$conversationId, $senderId, $receiverId, $messageContent]);
            return true;
        } catch (\Exception $e) {
            error_log("Erreur lors de la création du message: " . $e->getMessage());
            return false;
        }
    }

    public function getMessagesByConversationId(int $conversationId, int $lastMessageId = 0): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM messages WHERE conversation_id = ? AND id > ? ORDER BY created_at ASC";
        $stmt = $db->executeQuery($sql, [$conversationId, $lastMessageId]);
        return $stmt->fetchAll();
    }

    public function getLatestMessageId(int $conversationId): int
    {
        $db = Database::getInstance();
        $sql = "SELECT MAX(id) as max_id FROM messages WHERE conversation_id = ?";
        $stmt = $db->executeQuery($sql, [$conversationId]);
        $result = $stmt->fetch();
        return $result && $result['max_id'] ? (int)$result['max_id'] : 0;
    }
} 