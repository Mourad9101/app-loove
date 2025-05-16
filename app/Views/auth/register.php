<div class="auth-container">
    <div class="auth-box">
        <h2>Inscription</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/register" method="POST" class="auth-form">
            <div class="form-group">
                <label for="name">Nom complet</label>
                <input type="text" id="name" name="name" class="form-control" 
                       value="<?= isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : '' ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="<?= isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : '' ?>"
                       required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" 
                       required minlength="8">
                <small class="form-text text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                       required minlength="8">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </div>

            <div class="auth-links">
                <p>Déjà inscrit ? <a href="<?= BASE_URL ?>/login">Connectez-vous</a></p>
            </div>
        </form>
    </div>
</div>

<?php
// Nettoyer les anciennes données du formulaire
unset($_SESSION['old_input']);
?>

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

.form-text {
    font-size: 0.875rem;
    margin-top: 0.25rem;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.auth-form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    form.addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas !');
        }
    });
});
</script> 