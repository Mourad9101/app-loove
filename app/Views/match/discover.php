<?php require_once APP_PATH . '/Views/layout/header.php';
error_log("Vue discover.php - Données reçues : " . print_r($users, true)); ?>

<!-- Debug info -->
<div class="debug-info-profiles">
    Nombre de profils : <?= count($users) ?><br>
    <?php foreach ($users as $index => $user): ?>
        Profil <?= $index + 1 ?> : <?= htmlspecialchars($user['first_name']) ?> (ID: <?= $user['id'] ?>)<br>
    <?php endforeach; ?>
</div>

<div class="discover-container">
    <div class="discover-header">
        <h1>Découvrez de nouveaux profils</h1>
        <p class="discover-subtitle">Faites glisser vers la droite pour liker, vers la gauche pour passer</p>
    </div>

    <?php if (empty($users)): ?>
        <div class="no-profiles">
            <div class="no-profiles-content">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h3>Plus personne à proximité</h3>
                <p>Revenez plus tard pour découvrir de nouveaux profils !</p>
                <?php error_log("Aucun profil trouvé dans la vue discover.php"); ?>
            </div>
        </div>
    <?php else: ?>
        <?php error_log("Nombre de profils à afficher : " . count($users)); ?>
        <div class="profiles-stack">
            <?php foreach ($users as $user): ?>
                <div class="profile-card" data-user-id="<?= htmlspecialchars($user['id']) ?>">
                    <div class="profile-image" style="background-image: url('<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image']) ?>'), url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDQwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjQwMCIgaGVpZ2h0PSI0MDAiIGZpbGw9IiNFMkU4RjAiLz48cGF0aCBkPSJNMjAwIDIyMEMyNDQuMTgyIDIyMCAyODAgMTg0LjE4MiAyODAgMTQwQzI4MCA5NS44MTc1IDI0NC4xODIgNjAgMjAwIDYwQzE1NS44MTcgNjAgMTIwIDk1LjgxNzUgMTIwIDE0MEMxMjAgMTg0LjE4MiAxNTUuODE3IDIyMCAyMDAgMjIwWiIgZmlsbD0iI0E2QjBCRCIvPjxwYXRoIGQ9Ik0zMTIuNzE5IDM0MEMzMTIuNzE5IDI5Ni41ODkgMjYxLjkzMSAyNjEuNDI5IDIwMCAyNjEuNDI5QzEzOC4wNjkgMjYxLjQyOSA4Ny4yODE0IDI5Ni41ODkgODcuMjgxNCAzNDAiIHN0cm9rZT0iI0E2QjBCRCIgc3Ryb2tlLXdpZHRoPSI0MCIvPjwvc3ZnPg==')">
                        <div class="profile-gradient"></div>
                    </div>
                    
                    <div class="profile-info">
                        <h2 class="profile-name">
                            <?= htmlspecialchars($user['first_name']) ?>, <?= $user['age'] ?>
                        </h2>
                        
                        <div class="profile-details">
                            <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($user['city']) ?></p>
                            <p class="profile-gemstone">
                                <i class="fas fa-gem"></i> <?= htmlspecialchars($user['gemstone']) ?>
                            </p>
                        </div>

                        <?php if (!empty($user['bio'])): ?>
                            <p class="profile-bio"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="profile-actions">
                        <button class="btn-action btn-pass" data-user-id="<?= htmlspecialchars($user['id']) ?>">
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="btn-action btn-like" data-user-id="<?= htmlspecialchars($user['id']) ?>">
                            <i class="fas fa-gem"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Styles de débogage */
.debug-info-profiles {
    position: fixed;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 10px;
    border-radius: 5px;
    font-size: 12px;
    z-index: 9999;
}

.discover-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.discover-header {
    text-align: center;
    margin-bottom: 30px;
}

.discover-subtitle {
    color: #666;
    font-size: 0.9rem;
}

.profiles-stack {
    position: relative;
    height: 600px;
}

.profile-card {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease-out;
}

.profile-image {
    height: 70%;
    background-size: cover;
    background-position: center;
    position: relative;
}

.profile-gradient {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.8));
}

.profile-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px;
    color: white;
    z-index: 1;
}

.profile-name {
    font-size: 1.8rem;
    margin-bottom: 10px;
}

.profile-details {
    display: flex;
    gap: 15px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.profile-bio {
    font-size: 0.9rem;
    margin-bottom: 20px;
    opacity: 0.9;
}

.profile-actions {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 0 20px;
}

.btn-action {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-pass {
    background-color: #fff;
    color: #dc3545;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-like {
    background-color: #fff;
    color: #4299e1;
    box-shadow: 0 2px 8px rgba(66, 153, 225, 0.3);
}

.btn-action:hover {
    transform: scale(1.1);
}

.btn-pass:hover {
    background-color: #dc3545;
    color: white;
}

.btn-like:hover {
    background-color: #4299e1;
    color: #fff;
}

.no-profiles {
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    background: #f8f9fa;
    border-radius: 15px;
}

.no-profiles-content {
    color: #6c757d;
}

.no-profiles-content i {
    margin-bottom: 15px;
}

@keyframes swipeLeft {
    to {
        transform: translateX(-150%) rotate(-30deg);
        opacity: 0;
    }
}

@keyframes swipeRight {
    to {
        transform: translateX(150%) rotate(30deg);
        opacity: 0;
    }
}

.swiping-left {
    animation: swipeLeft 0.5s ease-out forwards;
}

.swiping-right {
    animation: swipeRight 0.5s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script chargé');
    const cards = document.querySelectorAll('.profile-card');
    console.log('Nombre de cartes trouvées:', cards.length);
    
    let currentCardIndex = cards.length - 1;

    cards.forEach((card, index) => {
        console.log('Configuration de la carte:', index);
        card.style.zIndex = cards.length - index;
        
        let startX = 0;
        let currentX = 0;
        let isDragging = false;

        card.addEventListener('mousedown', startDragging);
        card.addEventListener('touchstart', e => {
            startDragging(e.touches[0]);
        });

        function startDragging(e) {
            isDragging = true;
            startX = e.clientX;
            card.style.transition = 'none';
        }

        document.addEventListener('mousemove', drag);
        document.addEventListener('touchmove', e => {
            if (isDragging) e.preventDefault();
            drag(e.touches[0]);
        });

        function drag(e) {
            if (!isDragging) return;
            
            currentX = e.clientX - startX;
            const rotation = currentX / 10;
            
            card.style.transform = `translateX(${currentX}px) rotate(${rotation}deg)`;
            
            const likeButton = card.querySelector('.btn-like');
            const passButton = card.querySelector('.btn-pass');
            
            if (currentX > 0) {
                likeButton.style.transform = `scale(${1 + currentX/500})`;
                passButton.style.transform = 'scale(1)';
            } else if (currentX < 0) {
                passButton.style.transform = `scale(${1 - currentX/500})`;
                likeButton.style.transform = 'scale(1)';
            }
        }

        document.addEventListener('mouseup', endDragging);
        document.addEventListener('touchend', endDragging);

        function endDragging() {
            if (!isDragging) return;
            isDragging = false;
            
            const threshold = 100;
            
            if (currentX > threshold) {
                like(card);
            } else if (currentX < -threshold) {
                pass(card);
            } else {
                card.style.transition = 'transform 0.3s ease';
                card.style.transform = '';
                
                const likeButton = card.querySelector('.btn-like');
                const passButton = card.querySelector('.btn-pass');
                likeButton.style.transform = '';
                passButton.style.transform = '';
            }
        }
    });

    document.querySelectorAll('.btn-like').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.profile-card');
            like(card);
        });
    });

    document.querySelectorAll('.btn-pass').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.profile-card');
            pass(card);
        });
    });

    function like(card) {
        const userId = card.dataset.userId;
        card.classList.add('swiping-right');
        
        fetch(`${BASE_URL}/matches/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.match) {
                showMatchNotification();
            }
        });

        removeCard(card);
    }

    function pass(card) {
        const userId = card.dataset.userId;
        card.classList.add('swiping-left');
        
        fetch(`${BASE_URL}/matches/pass`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${userId}`
        });

        removeCard(card);
    }

    function removeCard(card) {
        card.addEventListener('animationend', () => {
            card.remove();
            currentCardIndex--;
            
            if (currentCardIndex < 0) {
                document.querySelector('.profiles-stack').innerHTML = `
                    <div class="no-profiles">
                        <div class="no-profiles-content">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <h3>Plus personne à proximité</h3>
                            <p>Revenez plus tard pour découvrir de nouveaux profils !</p>
                        </div>
                    </div>
                `;
            }
        });
    }

    function showMatchNotification() {
        // Créer une notification de match
        const notification = document.createElement('div');
        notification.className = 'match-notification';
        notification.innerHTML = `
            <div class="match-content">
                <i class="fas fa-heart fa-3x mb-3"></i>
                <h3>C'est un match !</h3>
                <p>Vous pouvez maintenant commencer à discuter</p>
                <button class="btn btn-primary mt-3" onclick="window.location.href='${BASE_URL}/matches'">
                    Voir mes matchs
                </button>
            </div>
        `;
        document.body.appendChild(notification);

        // Ajouter le style pour la notification
        const style = document.createElement('style');
        style.textContent = `
            .match-notification {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
                animation: fadeIn 0.3s ease;
            }

            .match-content {
                background: white;
                padding: 40px;
                border-radius: 15px;
                text-align: center;
                animation: scaleIn 0.3s ease;
            }

            .match-content i {
                color: #e74c3c;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes scaleIn {
                from { transform: scale(0.8); }
                to { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);

        // Fermer la notification au clic en dehors
        notification.addEventListener('click', (e) => {
            if (e.target === notification) {
                notification.remove();
            }
        });
    }
});
</script> 