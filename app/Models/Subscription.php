<?php
namespace app\Models;

use app\Core\Database;
use PDO;

class Subscription {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createSubscription($userId, $stripeSubscriptionId, $planType = 'premium', $amount = 9.99) {
        $sql = "INSERT INTO subscriptions (
                    user_id,
                    stripe_subscription_id,
                    plan_type,
                    amount,
                    status,
                    created_at,
                    updated_at
                ) VALUES (
                    :user_id,
                    :stripe_subscription_id,
                    :plan_type,
                    :amount,
                    'active',
                    NOW(),
                    NOW()
                )";
        
        try {
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':user_id' => $userId,
                ':stripe_subscription_id' => $stripeSubscriptionId,
                ':plan_type' => $planType,
                ':amount' => $amount
            ]);
            
            if ($success) {
                // Mettre à jour le statut Premium de l'utilisateur
                $this->updateUserPremiumStatus($userId, true);
                return $this->db->lastInsertId();
            }
            
            return false;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création de l'abonnement : " . $e->getMessage());
            return false;
        }
    }

    public function cancelSubscription($userId) {
        $sql = "UPDATE subscriptions SET 
                    status = 'cancelled',
                    updated_at = NOW()
                WHERE user_id = :user_id AND status = 'active'";
        
        try {
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([':user_id' => $userId]);
            
            if ($success) {
                // Retirer le statut Premium de l'utilisateur
                $this->updateUserPremiumStatus($userId, false);
                return true;
            }
            
            return false;
        } catch (\PDOException $e) {
            error_log("Erreur lors de l'annulation de l'abonnement : " . $e->getMessage());
            return false;
        }
    }

    public function getUserSubscription($userId) {
        $sql = "SELECT * FROM subscriptions 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'abonnement : " . $e->getMessage());
            return false;
        }
    }

    public function updateSubscriptionStatus($stripeSubscriptionId, $status) {
        $sql = "UPDATE subscriptions SET 
                    status = :status,
                    updated_at = NOW()
                WHERE stripe_subscription_id = :stripe_subscription_id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                ':status' => $status,
                ':stripe_subscription_id' => $stripeSubscriptionId
            ]);
            
            if ($success) {
                // Mettre à jour le statut Premium de l'utilisateur selon le statut de l'abonnement
                $subscription = $this->getSubscriptionByStripeId($stripeSubscriptionId);
                if ($subscription) {
                    $isPremium = ($status === 'active');
                    $this->updateUserPremiumStatus($subscription['user_id'], $isPremium);
                }
                return true;
            }
            
            return false;
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
            return false;
        }
    }

    public function getSubscriptionByStripeId($stripeSubscriptionId) {
        $sql = "SELECT * FROM subscriptions WHERE stripe_subscription_id = :stripe_subscription_id";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':stripe_subscription_id' => $stripeSubscriptionId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'abonnement : " . $e->getMessage());
            return false;
        }
    }

    private function updateUserPremiumStatus($userId, $isPremium) {
        $sql = "UPDATE users SET 
                    is_premium = :is_premium,
                    updated_at = NOW()
                WHERE id = :user_id";
        
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':is_premium' => $isPremium ? 1 : 0,
                ':user_id' => $userId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut Premium : " . $e->getMessage());
            return false;
        }
    }

    public function getActiveSubscription($userId) {
        $sql = "SELECT * FROM subscriptions 
                WHERE user_id = :user_id AND status = 'active'
                ORDER BY created_at DESC 
                LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de l'abonnement actif : " . $e->getMessage());
            return false;
        }
    }

    public function getTotalRevenue() {
        $sql = "SELECT SUM(amount) as total FROM subscriptions WHERE status = 'active'";
        $stmt = $this->db->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }
} 