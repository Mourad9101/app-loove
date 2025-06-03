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

<!-- Debug info -->
<div class="debug-info-profiles">
    Nombre de profils : <?= count($users) ?><br>
    <?php foreach ($users as $index => $user): ?>
        Profil <?= $index + 1 ?> : <?= htmlspecialchars($user['first_name']) ?> (ID: <?= $user['id'] ?>)<br>
    <?php endforeach; ?>
</div>

<div class="discover-container">
    <div class="profiles-stack" id="profiles-stack">
        <?php foreach ($users as $index => $user): ?>
            <div class="profile-card" data-user-id="<?= htmlspecialchars($user['id'] ?? '') ?>" data-index="<?= $index ?>" style="z-index: <?= count($users) - $index ?>">
                <div class="profile-image" style="background-image: url('<?= BASE_URL ?>/uploads/<?= htmlspecialchars($user['image'] ?? '') ?>')">
                    <div class="profile-gradient"></div>
                    <div class="profile-distance">
                        <i class="fas fa-location-dot"></i>
                        <?= isset($user['distance']) ? number_format($user['distance'], 1) . ' km' : '< 100 km' ?>
                    </div>
                    <div class="profile-header-overlay">
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
                 </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script discover.php démarré');
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

        // Optionnel: Gérer l'opacité ou un indicateur de swipe ici si désiré
    }

    function handleEnd() {
        if (!isDragging) return;
        
        const card = document.querySelector('.profile-card[data-index="0"]');
        if (!card) return;
        
        isDragging = false;
        const threshold = window.innerWidth * 0.25; // Seuil de swipe ajusté
        
        if (Math.abs(currentX) > threshold) {
            const isLike = currentX > 0;
            handleSwipe(card, isLike);
        } else {
            // Revenir à la position initiale
            card.style.transform = '';
            card.style.transition = 'transform 0.3s ease';
        }
        
        currentX = 0;
    }

    function handleSwipe(card, isLike) {
        const userId = card.dataset.userId;
        const endpoint = isLike ? 'like' : 'pass';
        
        card.style.transition = 'transform 0.5s ease, opacity 0.5s ease';
        card.style.transform = `translateX(${isLike ? '150%' : '-150%'}) rotate(${isLike ? '15deg' : '-15deg'})`;
        card.style.opacity = 0;

        // Envoyer la requête au backend
        fetch(`<?= BASE_URL ?>/matches/${endpoint}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.match) {
                // Gérer le cas d'un match
                console.log('Match trouvé !', data.match);
                // Vous pourriez appeler ici une fonction pour afficher une modale de match, etc.
                showMatchNotification(); // Appel à la fonction existante
            }
        })
        .catch(error => {
            console.error(`Erreur lors de l'action ${endpoint} pour l'utilisateur ${userId}:`, error);
            // Gérer l'erreur (ex: réafficher la carte ou montrer un message d'erreur)
        });
        
        // Supprimer la carte après l'animation
        setTimeout(() => {
            card.remove();
            updateCardsIndex();
        }, 500); // Correspond à la durée de la transition
    }

    function updateCardsIndex() {
        document.querySelectorAll('.profile-card').forEach((card, index) => {
            card.dataset.index = index;
            card.style.zIndex = document.querySelectorAll('.profile-card').length - index;
            // Optionnel: Ajouter d'autres styles d'empilement ici (ex: scale, légère translation)
        });
         // Optionnel: Afficher un message si plus de cartes
         if (document.querySelectorAll('.profile-card').length === 0) {
             const discoverContainer = document.querySelector('.discover-container');
             if (discoverContainer) {
                 discoverContainer.innerHTML = `<div class="no-profiles">
    <i class="fas fa-box-open"></i>
    <p>Aucun nouveau profil pour l'instant.<br>Revenez plus tard !</p>
</div>`;
             }
         }
    }

    // Événements tactiles (pour mobile)
    stack.addEventListener('touchstart', handleStart, { passive: false });
    stack.addEventListener('touchmove', handleMove, { passive: false });
    stack.addEventListener('touchend', handleEnd);

    // Événements souris (pour desktop)
    stack.addEventListener('mousedown', handleStart);
    document.addEventListener('mousemove', handleMove);
    document.addEventListener('mouseup', handleEnd);

    // Gestion des clics sur les boutons d'action
    document.addEventListener('click', function(e) {
        // Utiliser event delegation pour les boutons
        const button = e.target.closest('.btn-action');
        if (!button) return; // Si le clic n'était pas sur un bouton d'action ou son enfant
        
        const card = button.closest('.profile-card');
        // S'assurer que le bouton cliqué est sur la carte du dessus
        if (!card || card.dataset.index !== '0') return;
        
        e.preventDefault(); // Empêcher le comportement par défaut (ex: soumission de formulaire si les boutons étaient dans un form)
        e.stopPropagation(); // Empêcher l'événement de remonter à la pile et de déclencher un drag/swipe
        
        const isLike = button.classList.contains('btn-like');
        handleSwipe(card, isLike);
    });

    // Gérer le déroulement de la bio
    document.querySelectorAll('.profile-about-title').forEach(title => {
        title.addEventListener('click', function() {
            const aboutSection = this.closest('.profile-about-section');
            if (aboutSection) {
                aboutSection.classList.toggle('open');
            }
        });
    });

    // Fonction existante pour montrer une notification de match (peut être améliorée)
    function showMatchNotification() {
        console.log('Match!');
        // TODO: Implémenter une animation ou modale de match ici
    }
});
</script>

<?php require_once APP_PATH . '/Views/layout/footer.php'; ?> 