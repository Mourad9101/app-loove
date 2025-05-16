<?php
namespace app\Models;

use app\Core\Database;
use PDO;

class UserMatch {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createLike($userId, $likedUserId) {
        try {
            $sql = "INSERT INTO matches (user_id, liked_user_id, created_at) 
                    VALUES (:user_id, :liked_user_id, NOW())";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création du like : " . $e->getMessage());
            return false;
        }
    }

    public function deleteLike($userId, $likedUserId) {
        try {
            $sql = "DELETE FROM matches 
                    WHERE user_id = :user_id 
                    AND liked_user_id = :liked_user_id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la suppression du like : " . $e->getMessage());
            return false;
        }
    }

    public function isMatch($userId, $likedUserId) {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM matches m1 
                    INNER JOIN matches m2 ON m1.user_id = m2.liked_user_id 
                    AND m1.liked_user_id = m2.user_id 
                    WHERE m1.user_id = :user_id 
                    AND m1.liked_user_id = :liked_user_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la vérification du match : " . $e->getMessage());
            return false;
        }
    }

    public function getUserMatches($userId) {
        try {
            $sql = "SELECT DISTINCT u.* 
                    FROM users u 
                    INNER JOIN matches m1 ON u.id = m1.liked_user_id 
                    INNER JOIN matches m2 ON m1.user_id = m2.liked_user_id 
                    AND m1.liked_user_id = m2.user_id 
                    WHERE m1.user_id = :user_id 
                    ORDER BY m1.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Retirer les mots de passe des résultats
            foreach ($matches as &$match) {
                unset($match['password']);
            }
            
            return $matches;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des matchs : " . $e->getMessage());
            return [];
        }
    }

    public function getLikedUsers($userId) {
        try {
            $sql = "SELECT liked_user_id 
                    FROM matches 
                    WHERE user_id = :user_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des likes : " . $e->getMessage());
            return [];
        }
    }
} 