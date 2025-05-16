<div class="auth-container">
    <div class="auth-box">
        <h2>Connexion</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/login" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </div>

            <div class="auth-links">
                <p>Pas encore de compte ? <a href="<?= BASE_URL ?>/register">Inscrivez-vous</a></p>
                <a href="<?= BASE_URL ?>/forgot-password">Mot de passe oublié ?</a>
            </div>
        </form>
    </div>
</div>

<style>
.auth-container {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.auth-box {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 400px;
}

.auth-box h2 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 2rem;
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.auth-form label {
    display: block;
    margin-bottom: 0.5rem;
    color: var(--text-color);
}

.auth-form .form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    transition: border-color 0.3s ease;
}

.auth-form .form-control:focus {
    outline: none;
    border-color: var(--primary-color);
}

.auth-links {
    text-align: center;
    margin-top: 1.5rem;
}

.auth-links a {
    color: var(--primary-color);
    text-decoration: none;
}

.auth-links a:hover {
    text-decoration: underline;
}
</style> 