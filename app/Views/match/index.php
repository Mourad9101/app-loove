<div class="matches-container">
    <h1>Vos Matchs</h1>

    <?php if (empty($matches)): ?>
        <div class="no-matches">
            <p>Vous n'avez pas encore de matchs.</p>
            <a href="<?= APP_URL ?>/discover" class="btn btn-primary">Découvrir des profils</a>
        </div>
    <?php else: ?>
        <div class="matches-grid">
            <?php foreach ($matches as $match): ?>
                <div class="match-card" data-user-id="<?= $match['id'] ?>">
                    <div class="match-image">
                        <img src="<?= APP_URL ?>/uploads/<?= htmlspecialchars($match['image']) ?>" 
                             alt="Photo de <?= htmlspecialchars($match['first_name']) ?>"
                             class="profile-image">
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
.matches-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.match-card {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.match-card:hover {
    transform: translateY(-5px);
}

.match-image {
    position: relative;
    padding-top: 100%;
}

.match-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.match-info {
    padding: 1rem;
    text-align: center;
}

.match-details {
    color: #666;
    margin: 0.5rem 0;
}

.match-gemstone {
    color: var(--accent-color);
    font-weight: 500;
}

.match-actions {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    border-top: 1px solid #eee;
}

.btn-unmatch {
    background-color: transparent;
    color: var(--error-color);
    padding: 0.5rem;
}

.btn-unmatch:hover {
    background-color: var(--error-color);
    color: white;
}

.no-matches {
    text-align: center;
    padding: 3rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.no-matches p {
    margin-bottom: 1.5rem;
    color: #666;
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
                const response = await fetch('<?= APP_URL ?>/unmatch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ user_id: userId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    card.style.transform = 'scale(0)';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.match-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            } catch (error) {
                showAlert('Une erreur est survenue', 'error');
            }
        });
    });
});
</script> 