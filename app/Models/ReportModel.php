<?php

namespace app\Models;

use app\Core\Database;
use PDO;

class ReportModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function createReport(int $reporterId, int $reportedUserId, string $reason, string $description = ''): bool
    {
        $sql = "INSERT INTO reports (reporter_id, reported_user_id, reason, description, created_at) VALUES (:reporter_id, :reported_user_id, :reason, :description, NOW())";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':reporter_id' => $reporterId,
                ':reported_user_id' => $reportedUserId,
                ':reason' => $reason,
                ':description' => $description
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création du signalement : " . $e->getMessage());
            return false;
        }
    }

    public function getAllReports(): array
    {
        $sql = "SELECT 
                    r.*,
                    CONCAT(reporter.first_name, ' ', reporter.last_name) as reporter_name,
                    reporter.email as reporter_email,
                    CONCAT(reported.first_name, ' ', reported.last_name) as reported_user_name,
                    reported.email as reported_email,
                    reported.id as reported_user_id,
                    CONCAT(admin.first_name, ' ', admin.last_name) as admin_name
                FROM reports r 
                JOIN users reporter ON r.reporter_id = reporter.id 
                JOIN users reported ON r.reported_user_id = reported.id 
                LEFT JOIN users admin ON r.handled_by = admin.id
                ORDER BY r.created_at DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération de tous les signalements : " . $e->getMessage());
            return [];
        }
    }

    public function getReportById(int $reportId)
    {
        $sql = "SELECT 
                    r.*,
                    CONCAT(reporter.first_name, ' ', reporter.last_name) as reporter_name,
                    reporter.email as reporter_email,
                    CONCAT(reported.first_name, ' ', reported.last_name) as reported_user_name,
                    reported.email as reported_email,
                    reported.id as reported_user_id
                FROM reports r 
                JOIN users reporter ON r.reporter_id = reporter.id 
                JOIN users reported ON r.reported_user_id = reported.id 
                WHERE r.id = :report_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':report_id' => $reportId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du signalement par ID : " . $e->getMessage());
            return false;
        }
    }

    public function updateReportStatus(int $reportId, ?string $status, int $adminId, ?string $notes = null): bool
    {
        // Construire la requête dynamiquement pour n'updater que les champs fournis
        $fields = [];
        $params = [
            ':report_id' => $reportId,
            ':admin_id' => $adminId
        ];

        if ($status !== null) {
            $fields[] = "status = :status";
            $params[':status'] = $status;
        }

        if ($notes !== null) {
            $fields[] = "admin_notes = :notes";
            $params[':notes'] = $notes;
        }

        // Si aucun champ à mettre à jour, retourner false
        if (empty($fields)) {
            return false;
        }

        // Toujours mettre à jour les informations de traitement
        $fields[] = "handled_by = :admin_id";
        $fields[] = "handled_at = NOW()";
        $fields[] = "updated_at = NOW()";

        $sql = "UPDATE reports SET " . implode(', ', $fields) . " WHERE id = :report_id";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut du signalement : " . $e->getMessage());
            return false;
        }
    }

    public function getReportsByStatus(string $status): array
    {
        $sql = "SELECT 
                    r.*,
                    CONCAT(reporter.first_name, ' ', reporter.last_name) as reporter_name,
                    reporter.email as reporter_email,
                    CONCAT(reported.first_name, ' ', reported.last_name) as reported_user_name,
                    reported.email as reported_email,
                    reported.id as reported_user_id
                FROM reports r 
                JOIN users reporter ON r.reporter_id = reporter.id 
                JOIN users reported ON r.reported_user_id = reported.id 
                WHERE r.status = :status
                ORDER BY r.created_at DESC";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération des signalements par statut : " . $e->getMessage());
            return [];
        }
    }

    public function getReportsCount(): array
    {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count
                FROM reports 
                GROUP BY status";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $counts = [
                'pending' => 0,
                'reviewed' => 0,
                'resolved' => 0,
                'total' => 0
            ];
            
            foreach ($results as $row) {
                $counts[$row['status']] = (int)$row['count'];
                $counts['total'] += (int)$row['count'];
            }
            
            return $counts;
        } catch (\PDOException $e) {
            error_log("Erreur lors du comptage des signalements : " . $e->getMessage());
            return [
                'pending' => 0,
                'reviewed' => 0,
                'resolved' => 0,
                'total' => 0
            ];
        }
    }
} 