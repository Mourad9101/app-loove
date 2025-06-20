<?php require_once APP_PATH . '/Views/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/discover.css">

<?php
// Vérification et conversion si nécessaire
if (!is_array($users)) {
    if (is_string($users)) {
        $users = json_decode($users, true);
    } else {
        $users = [];
    }
}
?>

<?php
// Définit une fonction pour obtenir la couleur d'une pierre précieuse
function getGemColor($gemstone) {
    switch ($gemstone) {
        case 'Diamond': return '#b9f2ff'; // Bleu pâle
        case 'Ruby': return '#e0115f'; // Rouge rubis
        case 'Emerald': return '#50c878'; // Vert émeraude
        case 'Sapphire': return '#0f52ba'; // Bleu saphir
        case 'Amethyst': return '#9966cc'; // Violet améthyste
        default: return '#cccccc'; // Couleur par défaut
    }
}
?>

<div class="discover-container">

    <?php if (($currentUser['is_premium'] ?? false)):?>
    <div class="advanced-filters-section card mb-4 p-2">
        <h4 class="card-title">Filtres de recherche avancée</h4>
        <form action="<?= BASE_URL ?>/discover" method="GET">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label for="min_age" class="form-label">Âge Min:</label>
                            <input type="number" class="form-control form-control-sm" id="min_age" name="min_age" value="<?= htmlspecialchars($_GET['min_age'] ?? '') ?>" min="18">
                        </div>
                        <div class="col-6">
                            <label for="max_age" class="form-label">Âge Max:</label>
                            <input type="number" class="form-control form-control-sm" id="max_age" name="max_age" value="<?= htmlspecialchars($_GET['max_age'] ?? '') ?>" min="18">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="row">
                        <div class="col-6">
                            <label for="gender" class="form-label">Genre:</label>
                            <select class="form-select form-select-sm" id="gender" name="gender">
                                <option value="">Tous</option>
                                <option value="H" <?= (($_GET['gender'] ?? '') === 'H') ? 'selected' : '' ?>>Homme</option>
                                <option value="F" <?= (($_GET['gender'] ?? '') === 'F') ? 'selected' : '' ?>>Femme</option>
                                <option value="NB" <?= (($_GET['gender'] ?? '') === 'NB') ? 'selected' : '' ?>>Non-binaire</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="gemstone" class="form-label">Pierre précieuse:</label>
                            <select class="form-select form-select-sm" id="gemstone" name="gemstone">
                                <option value="">Toutes</option>
                                <option value="Diamond" <?= (($_GET['gemstone'] ?? '') === 'Diamond') ? 'selected' : '' ?>>Diamant</option>
                                <option value="Ruby" <?= (($_GET['gemstone'] ?? '') === 'Ruby') ? 'selected' : '' ?>>Rubis</option>
                                <option value="Emerald" <?= (($_GET['gemstone'] ?? '') === 'Emerald') ? 'selected' : '' ?>>Émeraude</option>
                                <option value="Sapphire" <?= (($_GET['gemstone'] ?? '') === 'Sapphire') ? 'selected' : '' ?>>Saphir</option>
                                <option value="Amethyst" <?= (($_GET['gemstone'] ?? '') === 'Amethyst') ? 'selected' : '' ?>>Améthyste</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="radius" class="form-label">Rayon de recherche (km):</label>
                    <input type="number" class="form-control form-control-sm" id="radius" name="radius" value="<?= htmlspecialchars($_GET['radius'] ?? '') ?>" min="1" max="500">
                    <small class="form-text text-muted">Distance maximale depuis votre localisation.</small>
                </div>
            </div>
            <div class="btn-container">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Appliquer les filtres</button>
            <a href="<?= BASE_URL ?>/discover" class="btn btn-secondary btn-sm"><i class="fas fa-sync"></i> Réinitialiser</a>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="alert alert-info mt-3">
        Passez <a href="<?= BASE_URL ?>/payment" class="alert-link">Premium</a> pour débloquer les filtres de recherche avancée !
    </div>
    <?php endif; ?>

    <div class="profiles-stack" id="profiles-stack">
        <?php if (!empty($users)): ?>
        <?php foreach ($users as $index => $user): ?>
            <div class="profile-card" data-user-id="<?= htmlspecialchars($user['id'] ?? '') ?>" data-index="<?= $index ?>" style="z-index: <?= count($users) - $index ?>">
                <div class="profile-image" style="background-image: url('<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image'] ?? '') ?>')">
                    <div class="profile-gradient"></div>
                    <div class="profile-distance">
                        <i class="fas fa-location-dot"></i>
                        <?= isset($user['distance']) ? number_format($user['distance'], 1) . ' km' : '< 100 km' ?>
                    </div>
                    <div class="profile-info-bottom">
                         <div class="profile-name"><?= htmlspecialchars($user['first_name'] ?? '') ?><?= isset($user['age']) ? ', ' . htmlspecialchars($user['age']) : '' ?></div>
                         <div class="profile-location"><?= strtoupper(htmlspecialchars($user['city'] ?? '')) ?><?= isset($user['country']) ? ', ' . strtoupper(htmlspecialchars($user['country'] ?? '')) : '' ?></div>
                    </div>
                </div>
                <div class="profile-content">
                    <div class="profile-bottom-drag"></div>
                    
                    <?php /* Section About me (Bio) - Rendu déroulant */ ?>
                    <div class="profile-about-title">About me</div>
                    <div class="profile-bio"><?= htmlspecialchars($user['bio'] ?? '') ?></div>
                    
                    <?php
                    $interests = [];
                    if (isset($user['interests']) && is_string($user['interests'])) {
                        $interests = json_decode($user['interests'], true);
                        if (!is_array($interests)) $interests = [];
                    } else if (isset($user['interests']) && is_array($user['interests'])) {
                         $interests = $user['interests'];
                    }
                    ?>
                    <?php if (!empty($interests)): ?>
                        <div class="profile-interests-title">Interest</div>
                        <div class="profile-interests">
                            <?php foreach ($interests as $interest): ?>
                                <span class="interest-tag"><?= htmlspecialchars($interest) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                     <div class="profile-gemstone-bottom">
                         <i class="fas fa-gem" style="color:<?= getGemColor($user['gemstone'] ?? 'Diamond') ?>"></i>
                     </div>
                 </div>
                 <div class="profile-actions">
                     <button class="btn-action btn-pass"><i class="fas fa-times"></i></button>
                     <button class="btn-action btn-gem"><i class="fas fa-gem"></i></button>
                     <button class="btn-action btn-like"><i class="fas fa-heart"></i></button>
                     <button class="btn-action btn-report"><i class="fas fa-flag"></i></button>
                 </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <div class="no-profiles">
                <div>
                    <i class="fas fa-search-minus" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <h4>Aucun profil trouvé</h4>
                    <p>Essayez d'élargir vos critères de recherche.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php
    $isPremium = $currentUser['is_premium'] ?? false;
    $dailyMatchesCount = (int)($currentUser['daily_matches_count'] ?? 0);
    $lastMatchDate = $currentUser['last_match_date'] ? new DateTime($currentUser['last_match_date']) : null;
    $today = new DateTime();

    $matchesRemaining = 0;
    if (!$isPremium) {
        // Réinitialiser le compteur si c'est un nouveau jour
        if ($lastMatchDate && $lastMatchDate->format('Y-m-d') !== $today->format('Y-m-d')) {
            $dailyMatchesCount = 0;
            // $this->userModel->updateDailyMatchCount($currentUser['id'], 0, $today->format('Y-m-d'));
        }
        $matchesRemaining = 6 - $dailyMatchesCount;
        if ($matchesRemaining < 0) $matchesRemaining = 0; // S'assurer qu'il n'est pas négatif
    }
    ?>

    <div style="height: 80px; width: 100%;"></div>
    <div class="match-limit-info">
        <?php if ($isPremium): ?>
            <p>Vous êtes Premium ! Profitez de matchs illimités chaque jour.</p>
        <?php else: ?>
            <?php if ($matchesRemaining > 0): ?>
                <p>Il vous reste <strong><?= $matchesRemaining ?></strong> matchs gratuits aujourd'hui.</p>
                <p>Passez Premium pour des matchs illimités et plus de fonctionnalités !</p>
            <?php else: ?>
                <p>Vous avez atteint votre limite de matchs quotidiens.</p>
                <p>Passez <a href="#" class="premium-link">Premium</a> pour des matchs illimités !</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Rendre BASE_URL disponible pour le JS externe
    window.BASE_URL = "<?= BASE_URL ?>";
    // Fonction utilitaire pour la couleur de la pierre précieuse
    window.getGemColor = function(gemstone) {
        switch (gemstone) {
            case 'Diamond': return '#b9f2ff';
            case 'Ruby': return '#e0115f';
            case 'Emerald': return '#50c878';
            case 'Sapphire': return '#0f52ba';
            case 'Amethyst': return '#9966cc';
            default: return '#cccccc';
        }
    };
</script>
<script src="<?= BASE_URL ?>/public/js/discover.js"></script>
