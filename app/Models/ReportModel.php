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

    public function createReport(int $reporterId, int $reportedUserId, string $reason): bool
    {
        $sql = "INSERT INTO reports (reporter_id, reported_user_id, reason, created_at) VALUES (:reporter_id, :reported_user_id, :reason, NOW())";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':reporter_id' => $reporterId,
                ':reported_user_id' => $reportedUserId,
                ':reason' => $reason
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la création du signalement : " . $e->getMessage());
            return false;
        }
    }

    public function getAllReports(): array
    {
        $sql = "SELECT r.*, reporter.email as reporter_email, reported.email as reported_email, reported.first_name as reported_first_name, reported.last_name as reported_last_name FROM reports r JOIN users reporter ON r.reporter_id = reporter.id JOIN users reported ON r.reported_user_id = reported.id ORDER BY r.created_at DESC";
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
        $sql = "SELECT r.*, reporter.email as reporter_email, reported.email as reported_email, reported.first_name as reported_first_name, reported.last_name as reported_last_name FROM reports r JOIN users reporter ON r.reporter_id = reporter.id JOIN users reported ON r.reported_user_id = reported.id WHERE r.id = :report_id";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':report_id' => $reportId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la récupération du signalement par ID : " . $e->getMessage());
            return false;
        }
    }

    public function updateReportStatus(int $reportId, string $status): bool
    {
        $sql = "UPDATE reports SET status = :status, updated_at = NOW() WHERE id = :report_id";
        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':report_id' => $reportId
            ]);
        } catch (\PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut du signalement : " . $e->getMessage());
            return false;
        }
    }
} 