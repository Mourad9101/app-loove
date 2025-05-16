<div class="error-container">
    <div class="error-content">
        <h1>Oups ! Une erreur est survenue</h1>
        <p>Nous sommes désolés, mais quelque chose s'est mal passé. Notre équipe a été notifiée et travaille à résoudre le problème.</p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>

<style>
.error-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem;
}

.error-content {
    max-width: 600px;
}

.error-content h1 {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 2.5rem;
}

.error-content p {
    color: #666;
    margin-bottom: 2rem;
    font-size: 1.1rem;
}
</style> 