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
            error_log("=== Création d'un like de $userId vers $likedUserId ===");
            
            // Vérifier si le like existe déjà
            $checkSql = "SELECT COUNT(*) as count FROM matches 
                        WHERE user_id = :user_id AND liked_user_id = :liked_user_id";
            
            $stmt = $this->db->prepare($checkSql);
            $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
            
            if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                error_log("Le like existe déjà");
                return true;
            }
            
            $sql = "INSERT INTO matches (user_id, liked_user_id, created_at) 
                    VALUES (:user_id, :liked_user_id, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
            
            error_log("Résultat de la création du like : " . ($success ? "Succès" : "Échec"));
            return $success;
        } catch (\PDOException $e) {
            error_log("Erreur dans createLike : " . $e->getMessage());
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
            error_log("=== Vérification de match entre $userId et $likedUserId ===");
            
            $sql = "SELECT COUNT(*) as count 
                    FROM matches m1 
                    INNER JOIN matches m2 ON m1.user_id = m2.liked_user_id 
                        AND m1.liked_user_id = m2.user_id 
                    WHERE m1.user_id = :user_id 
                        AND m1.liked_user_id = :liked_user_id";
            
            error_log("Requête SQL : " . $sql);
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':liked_user_id' => $likedUserId
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $isMatch = $result['count'] > 0;
            
            error_log("Résultat du match : " . ($isMatch ? "OUI" : "NON"));
            return $isMatch;
        } catch (\PDOException $e) {
            error_log("Erreur dans isMatch : " . $e->getMessage());
            return false;
        }
    }

    public function getUserMatches($userId) {
        try {
            $sql = "SELECT 
                        u.*,
                        m1.created_at as match_date,
                        msg.last_message_content,
                        msg.last_message_date
                    FROM users u
                    INNER JOIN matches m1 ON (m1.liked_user_id = u.id AND m1.user_id = ?)
                    INNER JOIN matches m2 ON (m2.user_id = m1.liked_user_id AND m2.liked_user_id = m1.user_id)
                    LEFT JOIN (
                        SELECT 
                            CASE 
                                WHEN sender_id = ? THEN receiver_id
                                ELSE sender_id
                            END as other_user_id,
                            MAX(created_at) as last_message_date,
                            SUBSTRING_INDEX(GROUP_CONCAT(message_content ORDER BY created_at DESC), ',', 1) as last_message_content
                        FROM messages
                        WHERE sender_id = ? OR receiver_id = ?
                        GROUP BY other_user_id
                    ) msg ON msg.other_user_id = u.id
                    WHERE m1.user_id = ?
                    ORDER BY msg.last_message_date DESC, m1.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $userId, $userId, $userId, $userId]);
            $matches = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($matches as &$match) {
                unset($match['password']);
            }
            return $matches;
        } catch (\Exception $e) {
            error_log("Erreur dans getUserMatches : " . $e->getMessage());
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