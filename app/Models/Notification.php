<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model {
    protected $table = 'notifications';

    public function getForUser($userId, $limit = 20) {
        $limit = (int)$limit; // Sécurise la valeur
        $sql = "SELECT n.*, u.image, u.first_name, u.last_name
                FROM notifications n 
                LEFT JOIN users u ON n.from_user_id = u.id 
                WHERE n.user_id = ? AND n.read_at IS NULL
                ORDER BY n.created_at DESC 
                LIMIT $limit";
        return $this->query($sql, [$userId]);
    }

    public function markAsRead($notificationIds, $userId) {
        if (empty($notificationIds)) return false;
        
        $placeholders = str_repeat('?,', count($notificationIds) - 1) . '?';
        $params = array_merge($notificationIds, [$userId]);
        
        $sql = "UPDATE notifications 
                SET read_at = NOW() 
                WHERE id IN ($placeholders) 
                AND user_id = ?";
        
        return $this->execute($sql, $params);
    }

    public function create($data) {
        $sql = "INSERT INTO notifications (user_id, from_user_id, message, type, link, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        return $this->execute($sql, [
            $data['user_id'],
            $data['from_user_id'] ?? null,
            $data['message'],
            $data['type'] ?? 'info',
            $data['link'] ?? null
        ]);
    }

    public function getUnreadCount($userId) {
        $sql = "SELECT COUNT(*) as count 
                FROM notifications 
                WHERE user_id = ? 
                AND read_at IS NULL";
        
        $result = $this->query($sql, [$userId]);
        return $result[0]['count'] ?? 0;
    }
} 