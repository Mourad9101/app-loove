<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <h1>Gestion des Signalements</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Signalé par</th>
                <th>Utilisateur signalé</th>
                <th>Raison</th>
                <th>Statut</th>
                <th>Date du signalement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reports)): ?>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?= htmlspecialchars($report['id']) ?></td>
                        <td><?= htmlspecialchars($report['reporter_email']) ?></td>
                        <td><?= htmlspecialchars($report['reported_email']) ?> (<?= htmlspecialchars($report['reported_first_name']) ?>)</td>
                        <td><?= htmlspecialchars($report['reason']) ?></td>
                        <td>
                            <span class="badge bg-<?= $report['status'] === 'pending' ? 'warning' : ($report['status'] === 'reviewed' ? 'info' : 'success') ?>">
                                <?= htmlspecialchars($report['status']) ?>
                            </span>
                        </td>
                        <td><?= (new DateTime($report['created_at']))->format('d/m/Y H:i') ?></td>
                        <td>
                            <?php if ($report['status'] === 'pending'): ?>
                                <button 
                                    class="btn btn-sm btn-info"
                                    onclick="updateReportStatus(<?= $report['id'] ?>, 'reviewed')"
                                >
                                    Marquer comme vu
                                </button>
                            <?php elseif ($report['status'] === 'reviewed'): ?>
                                <button 
                                    class="btn btn-sm btn-success"
                                    onclick="updateReportStatus(<?= $report['id'] ?>, 'resolved')"
                                >
                                    Marquer comme résolu
                                </button>
                            <?php endif; ?>
                            <button 
                                class="btn btn-sm btn-danger"
                                onclick="confirmDeleteUser(<?= $report['reported_user_id'] ?>, '<?= htmlspecialchars($report['reported_email']) ?>')"
                            >
                                Supprimer l'utilisateur signalé
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">Aucun signalement trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function updateReportStatus(reportId, newStatus) {
    const actionText = newStatus === 'reviewed' ? 'marquer comme vu' : 'marquer comme résolu';
    if (confirm(`Êtes-vous sûr de vouloir ${actionText} ce signalement ?`)) {
        fetch(`<?= BASE_URL ?>/admin/report/${reportId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Signalement ${actionText} avec succès.`);
                location.reload();
            } else {
                alert(data.error || 'Une erreur est survenue lors de la mise à jour du statut du signalement.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }
}

function confirmDeleteUser(userId, userEmail) {
    if (confirm(`Êtes-vous sûr de vouloir SUPPRIMER DÉFINITIVEMENT l'utilisateur ${userEmail} (ID: ${userId}) ? Cette action est irréversible et supprimera toutes les données de l'utilisateur.`)) {
        fetch(`<?= BASE_URL ?>/admin/user/${userId}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Utilisateur ${userEmail} supprimé avec succès.`);
                location.reload();
            } else {
                alert(data.error || 'Une erreur est survenue lors de la suppression de l'utilisateur.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }
}
</script>

<?php require_once APP_PATH . '/Views/layout/footer.php'; ?> 