<div class="home-container">
    <div class="hero-section">
        <h1>Bienvenue sur <?= APP_NAME ?></h1>
        <p class="lead"><?= $description ?></p>
        
        <div class="cta-buttons">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/register" class="btn btn-primary btn-lg">Inscrivez-vous</a>
                <a href="<?= BASE_URL ?>/login" class="btn btn-outline-primary btn-lg">Connexion</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/matches" class="btn btn-primary btn-lg">Voir mes matchs</a>
                <a href="<?= BASE_URL ?>/profile" class="btn btn-outline-primary btn-lg">Mon profil</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="features-section">
        <h2>Pourquoi choisir <?= APP_NAME ?> ?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-heart"></i>
                <h3>Matchs de qualité</h3>
                <p>Notre algorithme intelligent trouve les personnes qui vous correspondent vraiment.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Sécurité garantie</h3>
                <p>Vos données sont protégées et votre vie privée est notre priorité.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-comments"></i>
                <h3>Communication facile</h3>
                <p>Chattez en toute simplicité avec vos matchs.</p>
            </div>
        </div>
    </div>
</div>

<style>
.home-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.hero-section {
    text-align: center;
    padding: 4rem 0;
    background: linear-gradient(135deg, var(--background-color) 0%, #f0f8ff 100%);
    border-radius: 12px;
    margin-bottom: 4rem;
}

.hero-section h1 {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.tagline {
    font-size: 1.5rem;
    color: #666;
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-secondary {
    background-color: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
}

.btn-secondary:hover {
    background-color: var(--primary-color);
    color: white;
}

.features-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    padding: 2rem 0;
}

.feature {
    text-align: center;
    padding: 2rem;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.feature:hover {
    transform: translateY(-5px);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--accent-color);
    margin-bottom: 1rem;
}

.feature h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.feature p {
    color: #666;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .hero-section {
        padding: 2rem 1rem;
    }

    .hero-section h1 {
        font-size: 2rem;
    }

    .tagline {
        font-size: 1.2rem;
    }

    .features-section {
        grid-template-columns: 1fr;
    }
}
</style> 