<?php

namespace app\Controllers;

use app\Core\Controller;
use app\Models\ReportModel;

class ReportController extends Controller
{
    private $reportModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }
        $this->reportModel = new ReportModel();
    }

    public function create(int $reportedUserId):
        void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reporterId = $_SESSION['user_id'];
            $input = json_decode(file_get_contents('php://input'), true);
            $reason = $input['reason'] ?? '';

            if (!$reason) {
                $this->json(['success' => false, 'error' => 'La raison du signalement est requise.']);
                return;
            }

            $success = $this->reportModel->createReport($reporterId, $reportedUserId, $reason);

            if ($success) {
                $this->json(['success' => true, 'message' => 'Signalement enregistré avec succès.']);
            } else {
                $this->json(['success' => false, 'error' => 'Échec de l\'enregistrement du signalement.']);
            }
        }
    }
} 