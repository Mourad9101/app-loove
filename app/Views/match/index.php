<?php require_once APP_PATH . '/Views/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/match.css">

<div class="matches-container">
    <div class="page-header">
    <h1>Vos Matchs</h1>
        <p>Découvrez vos connexions mutuelles</p>
    </div>

    <?php if (empty($matches)): ?>
        <div class="no-matches">
            <div class="no-matches-content">
                <i class="fas fa-heart fa-3x"></i>
            <p>Vous n'avez pas encore de matchs.</p>
                <a href="<?= APP_URL ?>/discover" class="btn btn-primary">
                    <i class="fas fa-search"></i> Découvrir des profils
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="matches-grid">
            <?php foreach ($matches as $match): ?>
                <div class="match-card" data-user-id="<?= $match['id'] ?>">
                    <div class="match-image">
                        <?php if (!empty($match['last_message_content'])): ?>
                            <div class="new-match-badge">
                                <i class="fas fa-star"></i> Nouveau match
                            </div>
                        <?php endif; ?>
                        <img src="<?= APP_URL ?>/public/uploads/<?= htmlspecialchars($match['image']) ?>" 
                             alt="Photo de <?= htmlspecialchars($match['first_name']) ?>"
                             class="match-image-img">
                        <div class="match-image-overlay"></div>
                    </div>
                    
                    <div class="match-info">
                        <h3><?= htmlspecialchars($match['first_name']) ?></h3>
                        <p class="match-details">
                            <span class="age"><?= $match['age'] ?> ans</span>
                            <span class="city"><?= htmlspecialchars($match['city']) ?></span>
                        </p>
                        <p class="match-gemstone">
                            <i class="fas fa-gem"></i> <?= htmlspecialchars($match['gemstone']) ?>
                        </p>
                        <p class="match-date">
                            Match le <?= (new DateTime($match['match_date']))->format('d/m/Y') ?>
                        </p>
                    </div>
                    
                    <div class="match-actions">
                        <a href="<?= APP_URL ?>/messages/<?= $match['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-comment"></i> Message
                        </a>
                        <button class="btn btn-unmatch unmatch-button" data-user-id="<?= $match['id'] ?>">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    window.BASE_URL = "<?= BASE_URL ?>";
</script>
<script src="<?= BASE_URL ?>/public/js/match.js"></script> 