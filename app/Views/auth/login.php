<?php
// require_once __DIR__ . '/../config/config.php';
?>

<div class="auth-container">
    <div class="logo">
        <img src="<?= BASE_URL ?>/public/images/Logo Evergem.png" alt="Logo" class="logo-img">
    </div>
    <div class="auth-content">
        <h1>Welcome to EverGem</h1>
        <p class="tagline">Find your precious one</p>
        
        <div class="cta-buttons">
            <button id="toggleLoginForm" class="btn btn-primary btn-lg">Login</button>
            <a href="<?= BASE_URL ?>/login/google" class="btn btn-outline-primary btn-lg">Login with Google</a>
        </div>

        <div id="classicLoginForm" class="auth-box" style="display: none;">
            <h2>Connexion</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <form action="<?= BASE_URL ?>/login" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </form>
            <div class="auth-links">
                <a href="<?= BASE_URL ?>/forgot-password">Mot de passe oublié?</a>
            </div>
        </div>
        
        <div class="signup-link">
            <p>Don't have an account? <a href="<?= BASE_URL ?>/register">Sign Up</a></p>
        </div>
    </div>

</div>

<style>
.logo-img {
  width: auto;
  height: 350px;
  display: block;
  margin: 2px auto 20px auto;
  object-fit: contain;
}

.auth-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    text-align: center;
}

.auth-content {
    max-width: 400px;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 2rem;
}

.auth-container h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 0.5rem;
}

.tagline {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
    margin-bottom: 2rem;
}

.btn-lg {
    padding: 1rem 1.5rem;
    font-size: 1.2rem;
    border-radius: 50px;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(to right, #5667f0, #7a86ff);
    color: white;
    border: none;
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-outline-primary {
    background-color: #eee;
    color: #333;
    border: none;
}

.btn-outline-primary:hover {
    background-color: #ddd;
}

.signup-link {
    margin-top: 1rem;
}

.signup-link a {
    color: var(--primary-color);
    text-decoration: none;
}

.signup-link a:hover {
    text-decoration: underline;
}

/* Styles pour le formulaire visible par défaut */
.auth-box {
    display: block;
    max-width: 400px;
    width: 100%;
    margin-top: 2rem; 
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.auth-box h2 {
    text-align: center; 
    margin-bottom: 1.5rem;
}

.auth-box .form-group {
    margin-bottom: 1rem;
}

.auth-box .auth-links {
    text-align: center;
    margin-top: 1.5rem;
}

</style> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleLoginForm');
    const loginForm = document.getElementById('classicLoginForm');

    if (toggleButton && loginForm) {
        toggleButton.addEventListener('click', function() {
            if (loginForm.style.display === 'none' || loginForm.style.display === '') {
                loginForm.style.display = 'block';
                toggleButton.textContent = 'Login';
            } else {
                loginForm.style.display = 'none';
                toggleButton.textContent = 'Login';
            }
        });
    }
});
</script> 