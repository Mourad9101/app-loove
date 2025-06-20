<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">

<div class="admin-container">
    <div class="admin-content">
      <h1 class="admin-title">ADMIN PANEL</h1>
        <nav class="admin-nav">
          <div class="nav-links">
            <a href="<?= BASE_URL ?>/admin" class="active">
              <i class="fas fa-users"></i>
              Utilisateurs
            </a>
            <a href="<?= BASE_URL ?>/admin/reports">
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
          <div class="stats-container mb-4">
            <div class="stat-row">
              <div class="stat-card">
                <h5>Total Utilisateurs</h5>
                <p><?= $stats['total_users'] ?></p>
              </div>
              <div class="stat-card">
                <h5>Utilisateurs Actifs</h5>
                <p><?= $stats['active_users'] ?></p>
              </div>
              <div class="stat-card">
                <h5>Utilisateurs Premium</h5>
                <p><?= $stats['premium_users'] ?></p>
              </div>
              <div class="stat-card">
                <h5>Administrateurs</h5>
                <p><?= $stats['admin_users'] ?></p>
              </div>
              <div class="stat-card">
                <h5>Revenus Totaux</h5>
                <p><?= number_format($stats['revenue'], 2) ?> €</p>
              </div>
            </div>
          </div>

          <div class="subscription-stats mb-4">
            <div class="charts-row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Statuts des Abonnements</h5>
                    <canvas id="statusChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Revenus Mensuels</h5>
                    <canvas id="revenueChart"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Répartition des Utilisateurs</h5>
                    <canvas id="planChart" data-stats='<?= json_encode([
                        'premium' => $stats['premium_users'] ?? 0,
                        'standard' => $stats['standard_users'] ?? 0
                    ]) ?>'></canvas>
                  </div>
                </div>
              </div>
        </div>
      </div>
    <?php endif; ?>
        
        <div class="admin-panel">
          <div class="panel-header">
            <h2 class="panel-title">Gestion des Utilisateurs</h2>
            <div class="user-count">
              <i class="fas fa-users"></i>
              <?= count($users ?? []) ?> utilisateur(s)
            </div>
          </div>
          
    <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

          <div class="table-container">
            <table class="admin-table">
        <thead>
                <tr>
                  <th>ID</th>
                  <th>Photo</th>
                  <th>Email</th>
                  <th>Prénom</th>
                  <th>Nom</th>
                  <th>Statut</th>
                  <th>Premium</th>
                  <th>Admin</th>
                  <th>Actions</th>
            </tr>
        </thead>
              <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?= htmlspecialchars($user['id']) ?></td>
                      <td>
                        <div class="user-photo">
                      <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image'] ?? 'default.jpg') ?>"
                           alt="Photo de profil"
                           onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                    </div>
                  </td>
                      <td><?= htmlspecialchars($user['email']) ?></td>
                      <td><?= htmlspecialchars($user['first_name']) ?></td>
                      <td><?= htmlspecialchars($user['last_name']) ?></td>
                      <td>
                        <span class="status-badge <?= ($user['is_active'] ?? 0) ? 'active' : 'inactive' ?>">
                                <?= ($user['is_active'] ?? 0) ? 'Actif' : 'Désactivé' ?>
                            </span>
                        </td>
                      <td>
                        <span class="status-badge <?= ($user['is_premium'] ?? 0) ? 'premium' : 'not-premium' ?>">
                                <?= ($user['is_premium'] ?? 0) ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                      <td>
                        <span class="status-badge <?= ($user['is_admin'] ?? 0) ? 'admin' : 'not-admin' ?>">
                                <?= ($user['is_admin'] ?? 0) ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                      <td>
                    <div class="action-grid">
                          <button class="action-button status" title="Désactiver/Activer"
                        onclick="toggleUserStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_active'] ?? 0)) ?>)">
                        <i class="fa-solid fa-user-check"></i>
                            </button>
                          <button class="action-button premium" title="Promouvoir Premium"
                        onclick="togglePremiumStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_premium'] ?? 0)) ?>)">
                        <i class="fa-solid fa-gem"></i>
                            </button>
                          <button class="action-button admin" title="Promouvoir Admin"
                            onclick="toggleAdminStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_admin'] ?? 0)) ?>)"
                            <?= ($user['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-user-shield"></i>
                            </button>
                          <button class="action-button delete" title="Supprimer"
                            onclick="deleteUser(<?= $user['id'] ?>)"
                            <?= ($user['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-trash"></i>
                            </button>
                    </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="empty-message">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
      </div>
    </div>
  </div>
</div>

<script>
    const BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="<?= BASE_URL ?>/public/js/admin.js"></script>

</body>
</html>