<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<!-- Tailwind CSS CDN + FontAwesome -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<style>
.main-header, .main-header a, .main-header span, .main-header .logo {
    font-family: 'Poppins', sans-serif !important;
    color: #222 !important;
}

.status-badge {
    min-width: 80px;
    display: inline-block;
    text-align: center;
}
.action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
    justify-items: center;
    align-items: center;
}
</style>

<div class="min-h-screen bg-gradient-to-br from-teal-50 via-cyan-50 to-blue-100 py-10">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-4xl font-extrabold text-center text-cyan-700 mb-10 tracking-tight font-sans">Admin Evergem</h1>
    <?php if (isset($revenue)): ?>
      <div class="flex justify-end mb-6">
        <div class="bg-white rounded-2xl shadow-md px-8 py-6 flex flex-col items-center min-w-[220px]">
          <div class="flex items-center gap-2 mb-2">
            <i class="fa-solid fa-coins text-2xl text-yellow-400"></i>
            <span class="text-lg font-semibold text-gray-700">Revenus Premium</span>
          </div>
          <div class="text-3xl font-extrabold text-green-600"><?= number_format($revenue, 2) ?> €</div>
          <div class="text-xs text-gray-400 mt-1">Total généré</div>
        </div>
      </div>
    <?php endif; ?>
    <div class="bg-white/90 rounded-3xl shadow-xl p-8">
      <h2 class="text-2xl font-bold text-cyan-700 mb-6">Gestion des Utilisateurs</h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800 font-semibold">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-4 p-3 rounded bg-rose-100 text-rose-800 font-semibold">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm font-medium">
        <thead>
            <tr class="bg-cyan-100 text-cyan-700">
              <th class="py-3 px-4 rounded-tl-2xl">ID</th>
              <th class="py-3 px-4">Photo</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Prénom</th>
              <th class="py-3 px-4">Nom</th>
              <th class="py-3 px-4">Statut</th>
              <th class="py-3 px-4">Premium</th>
              <th class="py-3 px-4">Admin</th>
              <th class="py-3 px-4 rounded-tr-2xl">Actions</th>
            </tr>
        </thead>
          <tbody class="bg-white">
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                <tr class="border-b last:border-0 hover:bg-cyan-50 transition">
                  <td class="py-3 px-4 text-gray-700 font-semibold"><?= htmlspecialchars($user['id']) ?></td>
                  <td class="py-3 px-4">
                    <div class="flex items-center justify-center">
                      <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image'] ?? 'default.jpg') ?>"
                           alt="Photo de profil"
                           class="w-12 h-12 rounded-full object-cover border-2 border-cyan-200 shadow-sm bg-white"
                           onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                    </div>
                  </td>
                  <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($user['email']) ?></td>
                  <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($user['first_name']) ?></td>
                  <td class="py-3 px-4 text-gray-700"><?= htmlspecialchars($user['last_name']) ?></td>
                  <td class="py-3 px-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold status-badge
                      <?= ($user['is_active'] ?? 0) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                                <?= ($user['is_active'] ?? 0) ? 'Actif' : 'Désactivé' ?>
                            </span>
                        </td>
                  <td class="py-3 px-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                      <?= ($user['is_premium'] ?? 0) ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-700' ?>">
                                <?= ($user['is_premium'] ?? 0) ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                  <td class="py-3 px-4 text-center">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                      <?= ($user['is_admin'] ?? 0) ? 'bg-cyan-100 text-cyan-700' : 'bg-gray-200 text-gray-700' ?>">
                                <?= ($user['is_admin'] ?? 0) ? 'Oui' : 'Non' ?>
                            </span>
                        </td>
                  <td class="py-3 px-4 text-center">
                    <div class="action-grid">
                      <button class="p-2 rounded-full border-2 border-yellow-400 bg-white text-yellow-500 hover:bg-yellow-400 shadow transition" title="Désactiver/Activer"
                        onclick="toggleUserStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_active'] ?? 0)) ?>)">
                        <i class="fa-solid fa-user-check"></i>
                            </button>
                      <button class="p-2 rounded-full border-2 border-green-500 bg-white text-green-500 hover:bg-green-500 shadow transition" title="Promouvoir Premium"
                        onclick="togglePremiumStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_premium'] ?? 0)) ?>)">
                        <i class="fa-solid fa-gem"></i>
                            </button>
                      <button class="p-2 rounded-full border-2 border-blue-500 bg-white text-blue-500 hover:bg-blue-500 shadow transition" title="Promouvoir Admin"
                        onclick="toggleAdminStatus(<?= $user['id'] ?>, <?= json_encode((bool)($user['is_admin'] ?? 0)) ?>)" <?= ($user['id'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                        <i class="fa-solid fa-user-shield"></i>
                            </button>
                      <button class="p-2 rounded-full border-2 border-red-500 bg-white text-red-500 hover:bg-red-500 shadow transition" title="Supprimer"
                        onclick="deleteUser(<?= $user['id'] ?>)">
                        <i class="fa-solid fa-trash"></i>
                            </button>
                    </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                <td colspan="9" class="text-center py-8 text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
      </div>
    </div>
  </div>
</div>

<script>
    window.BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>/public/js/admin.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
