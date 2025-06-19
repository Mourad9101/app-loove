<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <h1><?= $title ?? 'Visiteurs de Profil' ?></h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($profileViews)): ?>
        <div class="row">
            <?php foreach ($profileViews as $viewer): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($viewer['image'] ?? 'default.jpg') ?>" class="card-img-top" alt="Photo de profil">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($viewer['first_name'] ?? '') ?> <?= htmlspecialchars($viewer['last_name'] ?? '') ?></h5>
                            <p class="card-text"><small class="text-muted">Vu le: <?= date('d/m/Y à H:i', strtotime($viewer['view_date'])) ?></small></p>
                            <a href="<?= BASE_URL ?>/profile/<?= htmlspecialchars($viewer['viewer_id']) ?>?from=views" class="btn btn-primary btn-sm">Voir le profil</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info" role="alert">
            Personne n'a encore consulté votre profil, ou vous n'êtes pas premium.
        </div>
    <?php endif; ?>
</div>