<?php require_once APP_PATH . '/Views/layout/header.php';

// Débogage détaillé
error_log("Type de \$users : " . gettype($users));
error_log("Contenu de \$users : " . print_r($users, true));

// Vérification et conversion si nécessaire
if (!is_array($users)) {
    if (is_string($users)) {
        $users = json_decode($users, true);
    } else {
        $users = [];
    }
}

// Vérification après conversion
error_log("Type de \$users après conversion : " . gettype($users));
error_log("Contenu de \$users après conversion : " . print_r($users, true)); ?>

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

<style>
.profile-bio {
    margin-bottom: 1.5rem;
    word-break: break-word;
}
.profiles-stack {
    margin-bottom: 5rem !important;
}
.match-limit-info {
    margin-top: 4rem !important;
    text-align: center;
}
</style>

<div class="discover-container">

    <?php if (($currentUser['is_premium'] ?? false)): // Afficher les filtres si l'utilisateur est premium ?>
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
            $dailyMatchesCount = 0; // Réinitialiser pour le nouveau jour
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
document.addEventListener('DOMContentLoaded', function() {
    const stack = document.getElementById('profiles-stack');
    let isDragging = false;
    let startX = 0;
    let currentX = 0;

    function handleStart(e) {
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        isDragging = true;
        startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        card.style.transition = 'none';
    }

    function handleMove(e) {
        if (!isDragging) return;
        
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        e.preventDefault();
        const clientX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
        currentX = clientX - startX;
        
        const rotate = currentX * 0.05; // Rotation légèrement réduite pour un effet plus doux
        card.style.transform = `translateX(${currentX}px) rotate(${rotate}deg)`;

    }

    function handleEnd() {
        if (!isDragging) return;
        
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        isDragging = false;
        const threshold = window.innerWidth * 0.25; // Seuil de swipe ajusté
        
        if (Math.abs(currentX) > threshold) {
            const isLike = currentX > 0;
            handleSwipe(card, isLike ? 'like' : 'pass');
        } else {
            // Revenir à la position initiale
            card.style.transform = '';
            card.style.transition = 'transform 0.3s ease';
        }
        
        currentX = 0;
    }

    function handleSwipe(card, actionType) {
        console.log('handleSwipe appelé avec actionType:', actionType);
        const userId = card.dataset.userId;
        console.log('userId:', userId);
        let endpoint = '';

        switch (actionType) {
            case 'pass':
                endpoint = 'pass';
                break;
            case 'like':
                endpoint = 'like';
                break;
            case 'gem':
                endpoint = 'gem';
                break;
            default:
                console.log('Action type invalide:', actionType);
                return;
        }
        
        console.log('Endpoint:', endpoint);
        console.log('URL de la requête:', `<?= BASE_URL ?>/matches/${endpoint}`);
        
        card.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
        
        let transformX = '0';
        if (actionType === 'like' || actionType === 'gem') {
            transformX = '1000px';
        } else if (actionType === 'pass') {
            transformX = '-1000px';
        }
        
        card.style.transform = `translateX(${transformX}) rotate(${currentX * 0.05}deg)`;

        // Mettre à jour l'index de la carte après l'animation
        setTimeout(() => {
            card.remove();
            // Réindexer les cartes restantes pour s'assurer que le z-index est correct
            document.querySelectorAll('.profile-card').forEach((remainingCard, idx) => {
                remainingCard.dataset.index = idx;
                remainingCard.style.zIndex = stack.children.length - idx;
            });
            loadMoreProfiles(); // Tenter de charger plus de profils si nécessaire
        }, 500); // Temps correspondant à la transition CSS

        // Envoyer la requête au serveur
        fetch(`<?= BASE_URL ?>/matches/${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            console.log('Réponse du serveur pour', actionType, ':', data);
            if (data.success) {
                if (data.match) {
                    alert('C\'est un Match !'); // Afficher une alerte pour un match
                }
                // La logique de suppression de la carte est gérée par handleSwipe
            } else {
                // Afficher un message d'erreur si la limite est atteinte
                if (data.error && data.error.includes('Limite')) {
                    alert(data.error);
                } else {
                    alert('Une erreur est survenue lors de l\'action.');
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }

    // Ajout des gestionnaires d'événements pour les boutons
    document.querySelectorAll('.btn-pass').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'pass');
        });
    });

    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'like');
        });
    });

    document.querySelectorAll('.btn-gem').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            handleSwipe(card, 'gem');
        });
    });

    document.querySelectorAll('.btn-report').forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.profile-card');
            const userId = card.dataset.userId;
            reportUser(userId);
        });
    });

    // Pour la biographie: rendre déroulable
    const bioElements = document.querySelectorAll('.profile-bio');
    bioElements.forEach(bio => {
        if (bio.scrollHeight > bio.clientHeight) {
            bio.classList.add('scrollable');
        }
    });

    let isLoadingMore = false;
    function loadMoreProfiles() {
        // Empêcher les requêtes multiples
        if (isLoadingMore) return;

        const currentProfilesCount = document.querySelectorAll('.profile-card').length;
        
        // Récupérer les valeurs des filtres du formulaire
        const minAge = document.getElementById('min_age') ? document.getElementById('min_age').value : '';
        const maxAge = document.getElementById('max_age') ? document.getElementById('max_age').value : '';
        const gender = document.getElementById('gender') ? document.getElementById('gender').value : '';
        const gemstone = document.getElementById('gemstone') ? document.getElementById('gemstone').value : '';
        const radius = document.getElementById('radius') ? document.getElementById('radius').value : '';

        let queryString = `offset=${currentProfilesCount}&limit=10`;
        if (minAge) queryString += `&min_age=${minAge}`;
        if (maxAge) queryString += `&max_age=${maxAge}`;
        if (gender) queryString += `&gender=${gender}`;
        if (gemstone) queryString += `&gemstone=${gemstone}`;
        if (radius) queryString += `&radius=${radius}`;

        // Charger plus de profils si moins de X profils restants et pas déjà en cours de chargement
        if (currentProfilesCount < 3) {
            isLoadingMore = true;
            fetch(`<?= BASE_URL ?>/matches/load-more?${queryString}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: ``
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.profiles.length > 0) {
                    data.profiles.forEach(newProfile => {
                        appendProfileCard(newProfile);
                    });
                } else if (!data.hasMore) {
                    console.log('Plus de profils à charger.');
                }
            })
            .catch(error => {
                console.error('Erreur de chargement des profils:', error);
            })
            .finally(() => {
                isLoadingMore = false;
            });
        }
    }

    function appendProfileCard(user) {
        const newIndex = stack.children.length;
        const newCard = document.createElement('div');
        newCard.classList.add('profile-card');
        newCard.dataset.userId = user.id;
        newCard.dataset.index = newIndex;
        newCard.style.zIndex = stack.children.length - newIndex;

        // Utilisez l'URL de base pour les images
        const imageUrl = `<?= BASE_URL ?>/public/uploads/${user.image || 'default.jpg'}`;
        const gemColor = getGemColor(user.gemstone || 'Diamond');

        newCard.innerHTML = `
            <div class="profile-image" style="background-image: url('${imageUrl}')">
                <div class="profile-gradient"></div>
                <div class="profile-distance">
                    <i class="fas fa-location-dot"></i>
                    ${user.distance !== undefined ? user.distance.toFixed(1) + ' km' : '< 100 km'}
                </div>
                <div class="profile-info-bottom">
                    <div class="profile-name">${user.first_name || ''}${user.age !== undefined ? ', ' + user.age : ''}</div>
                    <div class="profile-location">${(user.city || '').toUpperCase()}${(user.country !== undefined && user.country !== null) ? ', ' + user.country.toUpperCase() : ''}</div>
                </div>
            </div>
            <div class="profile-content">
                <div class="profile-bottom-drag"></div>
                <div class="profile-about-title">About me</div>
                <div class="profile-bio">${user.bio || ''}</div>
                ${user.interests && user.interests.length > 0 ? `
                    <div class="profile-interests-title">Interest</div>
                    <div class="profile-interests">
                        ${user.interests.map(interest => `<span class="interest-tag">${interest}</span>`).join('')}
                    </div>
                ` : ''}
                <div class="profile-gemstone-bottom">
                    <i class="fas fa-gem" style="color:${gemColor}"></i>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn-action btn-pass"><i class="fas fa-times"></i></button>
                <button class="btn-action btn-gem"><i class="fas fa-gem"></i></button>
                <button class="btn-action btn-like"><i class="fas fa-heart"></i></button>
                <button class="btn-action btn-report"><i class="fas fa-flag"></i></button>
            </div>
        `;

        stack.appendChild(newCard);
        addCardEventListeners(newCard);
    }

    // Fonction pour ajouter les écouteurs d'événements aux cartes (initiales et nouvelles)
    function addCardEventListeners(cardElement) {
        cardElement.querySelector('.btn-pass').addEventListener('click', function() {
            handleSwipe(cardElement, 'pass');
        });
        cardElement.querySelector('.btn-like').addEventListener('click', function() {
            handleSwipe(cardElement, 'like');
        });
        cardElement.querySelector('.btn-gem').addEventListener('click', function() {
            handleSwipe(cardElement, 'gem');
        });
        cardElement.querySelector('.btn-report').addEventListener('click', function() {
            const userId = cardElement.dataset.userId;
            reportUser(userId);
        });

        // Rendre la bio déroulable
        const bio = cardElement.querySelector('.profile-bio');
        if (bio && bio.scrollHeight > bio.clientHeight) {
            bio.classList.add('scrollable');
        }
    }

    // Appliquer les écouteurs aux cartes existantes au chargement initial
    document.querySelectorAll('.profile-card').forEach(card => {
        addCardEventListeners(card);
    });

    // Gestion des événements pour le swipe
    stack.addEventListener('mousedown', handleStart);
    stack.addEventListener('touchstart', handleStart);
    stack.addEventListener('mousemove', handleMove);
    stack.addEventListener('touchmove', handleMove);
    stack.addEventListener('mouseup', handleEnd);
    stack.addEventListener('touchend', handleEnd);
    stack.addEventListener('mouseleave', handleEnd); // Gérer le cas où la souris sort de la zone

    // Initialiser le chargement de plus de profils si nécessaire
    loadMoreProfiles();

    // Fonction pour signaler un utilisateur (utilisée par le bouton report)
    function reportUser(reportedUserId) {
        const reason = prompt('Quelle est la raison du signalement ?');
        if (reason !== null && reason.trim() !== '') {
            fetch(`<?= BASE_URL ?>/report/${reportedUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Utilisateur signalé avec succès. Merci de votre aide !');
                } else {
                    alert(data.error || 'Une erreur est survenue lors du signalement.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur de communication avec le serveur.');
            });
        }
    }
});
</script>
