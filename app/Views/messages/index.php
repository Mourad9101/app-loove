<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <h1>Vos Conversations</h1>
    <div class="list-group">
        <?php if (!empty($matches)): ?>
            <?php foreach ($matches as $match): ?>
                <a href="<?= BASE_URL ?>/messages/<?= htmlspecialchars($match['id']) ?>" class="list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <?php if (isset($match['image']) && !empty($match['image'])): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($match['image']) ?>" alt="Profil" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?= BASE_URL ?>/public/images/Logo Evergem.png" alt="Profil" class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                            <?php endif; ?>
                            <div>
                                <h5 class="mb-1">
                                    <?= htmlspecialchars($match['first_name']) ?>
                                    <?php if (isset($match['is_read']) && $match['is_read'] == 0 && isset($currentUser['id']) && $match['id'] != $currentUser['id']): ?>
                                        <span class="badge bg-primary rounded-pill ms-2">Nouveau</span>
                                    <?php endif; ?>
                                </h5>
                                <?php if (isset($match['last_message_content'])): ?>
                                    <p class="mb-1 text-muted"><?= htmlspecialchars($match['last_message_content']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <small class="text-muted">
                            <?= isset($match['last_message_date']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($match['last_message_date']))) : (isset($match['created_at']) ? htmlspecialchars(date('d/m/Y H:i', strtotime($match['created_at']))) : 'Aucune') ?>
                        </small>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item">
                <p class="mb-0">Vous n'avez pas encore de conversations.</p>
                <a href="<?= BASE_URL ?>/discover" class="btn btn-primary mt-3">Découvrir des profils</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.list-group-item {
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(108, 92, 231, 0.05);
}

.text-muted {
    color: #6c757d !important;
}
</style>
