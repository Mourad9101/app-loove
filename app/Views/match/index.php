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
                        <?php if (!$match['has_messaged']): ?>
                            <div class="new-match-badge">
                                <i class="fas fa-star"></i> Nouveau match
                            </div>
                        <?php endif; ?>
                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($match['image']) ?>" 
                             alt="Photo de <?= htmlspecialchars($match['first_name']) ?>"
                             class="profile-image">
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

<style>
/* Styles spécifiques à la page des matchs */
.matches-container {
    background-color: transparent;
}

.page-header h1 {
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

.page-header p {
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
}

.match-card {
    position: relative;
    border: none;
    background: var(--card-background);
    transition: all 0.3s ease;
}

.match-image {
    position: relative;
    padding-top: 100%;
    overflow: hidden;
}

.match-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.match-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 50%, rgba(0, 0, 0, 0.1));
    pointer-events: none;
}

.match-card:hover .match-image img {
    transform: scale(1.05);
}

.new-match-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--primary-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.8rem;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

.no-matches {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    background: var(--card-background);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
}

.no-matches-content {
    text-align: center;
    padding: 2rem;
}

.no-matches-content i {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.no-matches-content p {
    font-size: 1.1rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

.match-info h3 {
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

.match-info p {
    font-family: 'Poppins', sans-serif; /* Assure la police Poppins */
}

@media (max-width: 768px) {
    .matches-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        padding: 0 1rem;
    }
}

@media (max-width: 480px) {
    .matches-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Gérer le unmatch
    document.querySelectorAll('.unmatch-button').forEach(button => {
        button.addEventListener('click', async () => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce match ?')) {
                return;
            }

            const userId = button.dataset.userId;
            const card = button.closest('.match-card');
            
            try {
                const response = await fetch('<?= BASE_URL ?>/matches/unmatch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.match-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert('Une erreur est survenue');
                }
            } catch (error) {
                console.error('Erreur lors du unmatch:', error);
                alert('Une erreur est survenue');
            }
        });
    });
});
</script> 