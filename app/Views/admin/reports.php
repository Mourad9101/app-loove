<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">

<div class="admin-container">
  <div class="admin-content">
    <h1 class="admin-title">ADMIN PANEL</h1>
    
    <nav class="admin-nav">
      <div class="nav-links">
        <a href="<?= BASE_URL ?>/admin">
          <i class="fas fa-users"></i>
          Utilisateurs
        </a>
        <a href="<?= BASE_URL ?>/admin/reports" class="active">
          <i class="fas fa-flag"></i>
          Signalements
        </a>
        <a href="<?= BASE_URL ?>/" target="_blank">
          <i class="fas fa-external-link-alt"></i>
          Voir le site
        </a>
      </div>
    </nav>

    <?php if (isset($stats)): ?>
      <div class="stats-grid">
        <div class="stats-card">
          <div class="stats-content">
            <div>
              <p class="stats-label">Total Signalements</p>
              <p class="stats-value"><?= $stats['total'] ?></p>
            </div>
            <div class="stats-icon warning">
              <i class="fas fa-flag"></i>
            </div>
          </div>
        </div>
        
        <div class="stats-card">
          <div class="stats-content">
            <div>
              <p class="stats-label">En Attente</p>
              <p class="stats-value"><?= $stats['pending'] ?></p>
            </div>
            <div class="stats-icon pending">
              <i class="fas fa-clock"></i>
            </div>
          </div>
        </div>
        
        <div class="stats-card">
          <div class="stats-content">
            <div>
              <p class="stats-label">Examinés</p>
              <p class="stats-value"><?= $stats['reviewed'] ?></p>
            </div>
            <div class="stats-icon info">
              <i class="fas fa-eye"></i>
            </div>
          </div>
        </div>
        
        <div class="stats-card">
          <div class="stats-content">
            <div>
              <p class="stats-label">Résolus</p>
              <p class="stats-value"><?= $stats['resolved'] ?></p>
            </div>
            <div class="stats-icon success">
              <i class="fas fa-check"></i>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    <div class="admin-panel">
      <div class="panel-header">
        <h2 class="panel-title">Gestion des Signalements</h2>
        <div class="user-count">
          <i class="fas fa-flag"></i>
          <?= count($reports ?? []) ?> signalement(s)
        </div>
      </div>
      
      <div class="table-container">
        <table class="admin-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Utilisateur signalé</th>
              <th>Signalé par</th>
              <th>Raison</th>
              <th>Description</th>
              <th>Statut</th>
              <th>Date</th>
              <th>Traité par</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($reports)): ?>
              <?php foreach ($reports as $report): ?>
                <tr>
                  <td><?= htmlspecialchars($report['id']) ?></td>
                  <td><?= htmlspecialchars($report['reported_user_name']) ?></td>
                  <td><?= htmlspecialchars($report['reporter_name']) ?></td>
                  <td><?= htmlspecialchars($report['reason']) ?></td>
                  <td>
                    <div class="description-cell" title="<?= htmlspecialchars($report['description'] ?? '') ?>">
                      <?= htmlspecialchars($report['description'] ?? '') ?>
                    </div>
                  </td>
                  <td>
                    <span class="status-badge <?= $report['status'] ?>">
                      <?php
                      switch ($report['status']) {
                          case 'pending':
                              echo 'En attente';
                              break;
                          case 'reviewed':
                              echo 'Examiné';
                              break;
                          case 'resolved':
                              echo 'Résolu';
                              break;
                          default:
                              echo 'Inconnu';
                      }
                      ?>
                    </span>
                  </td>
                  <td class="date-cell">
                    <?= date('d/m/Y H:i', strtotime($report['created_at'])) ?>
                  </td>
                  <td>
                    <?php if ($report['handled_by']): ?>
                      <div class="admin-info">
                        <span><?= htmlspecialchars($report['admin_name']) ?></span>
                        <br>
                        <small><?= date('d/m/Y H:i', strtotime($report['handled_at'])) ?></small>
                      </div>
                    <?php else: ?>
                      <span class="text-muted">Non traité</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="action-grid">
                      <button class="action-button pending" title="Marquer comme en attente"
                        onclick="updateReportStatus(<?= $report['id'] ?>, 'pending')"
                        <?= $report['status'] === 'pending' ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-clock"></i>
                      </button>
                      <button class="action-button info" title="Marquer comme examiné"
                        onclick="updateReportStatus(<?= $report['id'] ?>, 'reviewed')"
                        <?= $report['status'] === 'reviewed' ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <button class="action-button success" title="Marquer comme résolu"
                        onclick="updateReportStatus(<?= $report['id'] ?>, 'resolved')"
                        <?= $report['status'] === 'resolved' ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-check"></i>
                      </button>
                      <button class="action-button warning" title="Ajouter une note"
                        onclick="addReportNote(<?= $report['id'] ?>)">
                        <i class="fa-solid fa-note-sticky"></i>
                      </button>
                    </div>
                    <?php if ($report['admin_notes']): ?>
                      <div class="admin-notes" title="<?= htmlspecialchars($report['admin_notes']) ?>">
                        <i class="fa-solid fa-comment-dots"></i>
                      </div>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="empty-message">Aucun signalement trouvé.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
    window.BASE_URL = '<?= rtrim(BASE_URL, '/') ?>';
</script><script src="<?= BASE_URL ?>/public/js/admin.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
