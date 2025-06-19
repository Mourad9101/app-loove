<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

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

<style>
body {
    background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
}
.matches-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 24px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 8px 32px rgba(127,90,240,0.08);
}
.page-header h1 {
    font-size: 2.2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #22223b;
}
.page-header p {
    color: #7f5af0;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}
.matches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
    gap: 2rem;
}
.match-card {
    background: #f4f4fb;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(127,90,240,0.07);
    overflow: hidden;
    transition: transform 0.18s, box-shadow 0.18s;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem 1rem;
    position: relative;
}
.match-card:hover {
    transform: translateY(-6px) scale(1.03);
    box-shadow: 0 8px 32px rgba(127,90,240,0.13);
}
.match-image {
    position: relative;
    margin-bottom: 1rem;
}
.match-image-img {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #7f5af0;
    display: block;
}
.new-match-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #7f5af0;
    color: #fff;
    font-size: 0.85rem;
    padding: 3px 10px;
    border-radius: 12px;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 0.3rem;
    box-shadow: 0 2px 8px rgba(127,90,240,0.13);
}
.match-info h3 {
    font-size: 1.3rem;
    margin: 0 0 0.3rem 0;
    color: #22223b;
    text-align: center;
}
.match-details {
    color: #555;
    font-size: 1rem;
    margin-bottom: 0.3rem;
    text-align: center;
}
.match-gemstone {
    font-size: 1.1rem;
    color: #7f5af0;
    margin-bottom: 0.3rem;
    text-align: center;
}
.match-date {
    font-size: 0.95rem;
    color: #888;
    margin-bottom: 1rem;
    text-align: center;
}
.match-actions {
    display: flex;
    gap: 0.7rem;
    margin-top: 0.5rem;
    justify-content: center;
}
.btn.btn-primary {
    background: #7f5af0;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1.2rem;
    font-size: 1rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background 0.18s;
}
.btn.btn-primary:hover {
    background: #6246ea;
}
.btn.btn-unmatch {
    background: #fff;
    color: #e63946;
    border: 1px solid #e63946;
    border-radius: 8px;
    padding: 0.5rem 0.7rem;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.18s, color 0.18s;
    display: flex;
    align-items: center;
}
.btn.btn-unmatch:hover {
    background: #e63946;
    color: #fff;
}
.no-matches {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}
.no-matches-content {
    text-align: center;
    color: #7f5af0;
}
.no-matches-content .btn {
    margin-top: 1.2rem;
}
</style> 