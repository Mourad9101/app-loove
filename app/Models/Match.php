<?php
namespace app\Models;

use app\Core\Database;

class UserMatch {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createLike(int $userId, int $likedUserId): bool {
        try {
            $sql = "INSERT INTO matches (user_id, liked_user_id) VALUES (:user_id, :liked_user_id)";
            $this->db->query($sql, [
                'user_id' => $userId,
                'liked_user_id' => $likedUserId
            ]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function isMatch(int $userId, int $likedUserId): bool {
        $sql = "SELECT COUNT(*) as count FROM matches 
                WHERE (user_id = :user1 AND liked_user_id = :user2)
                AND EXISTS (
                    SELECT 1 FROM matches 
                    WHERE user_id = :user2 AND liked_user_id = :user1
                )";
        
        $result = $this->db->query($sql, [
            'user1' => $userId,
            'user2' => $likedUserId
        ]);
        
        return (bool)$result->fetch()['count'];
    }

    public function getUserMatches(int $userId): array {
        $sql = "SELECT u.* FROM users u
                INNER JOIN matches m1 ON u.id = m1.liked_user_id
                INNER JOIN matches m2 ON m1.user_id = m2.liked_user_id AND m1.liked_user_id = m2.user_id
                WHERE m1.user_id = :userId";
        
        $result = $this->db->query($sql, ['userId' => $userId]);
        return $result->fetchAll();
    }

    public function deleteLike(int $userId, int $likedUserId): bool {
        $sql = "DELETE FROM matches WHERE user_id = :user_id AND liked_user_id = :liked_user_id";
        try {
            $this->db->query($sql, [
                'user_id' => $userId,
                'liked_user_id' => $likedUserId
            ]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
} 