<?php
// require_once __DIR__ . '/../config/config.php';
?>

<div class="auth-container">
    <div class="auth-content">
        <img src="<?= BASE_URL ?>/public/images/Logo Evergem.png" alt="Logo" class="logo-img">
        <h1>Welcome to EverGem</h1>
        <p class="tagline">Find your precious one</p>
        
        <div class="cta-buttons">
            <button id="openLoginModal" class="btn btn-primary btn-lg">Login</button>
            <a href="<?= BASE_URL ?>/login/google" class="btn btn-outline-primary btn-lg">Login with Google</a>
        </div>

        <!-- Modale de connexion -->
        <div id="loginModal" class="modal-overlay" style="display: none;">
            <div class="modal-content">
                <button class="modal-close" id="closeLoginModal" aria-label="Fermer">&times;</button>
                <div class="auth-box" style="margin-top:0;">
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
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
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
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
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

/* Modale premium Evergem */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100vw; height: 100vh;
    background: rgba(44, 40, 80, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    transition: background 0.2s;
}
.modal-content {
    background: #fff;
    border-radius: 1.2rem;
    box-shadow: 0 8px 32px rgba(108, 92, 231, 0.18);
    padding: 2.2rem 2rem 1.5rem 2rem;
    min-width: 340px;
    max-width: 400px;
    width: 100%;
    position: relative;
    animation: modalIn 0.25s cubic-bezier(.4,1.4,.6,1);
}
@keyframes modalIn {
    from { transform: translateY(40px) scale(0.98); opacity: 0; }
    to   { transform: none; opacity: 1; }
}
.modal-close {
    position: absolute;
    top: 18px;
    right: 18px;
    background: none;
    border: none;
    font-size: 2rem;
    color: #6c5ce7;
    cursor: pointer;
    transition: color 0.2s;
    z-index: 10;
}
.modal-close:hover {
    color: #a29bfe;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openBtn = document.getElementById('openLoginModal');
    const closeBtn = document.getElementById('closeLoginModal');
    const modal = document.getElementById('loginModal');

    if (openBtn && modal) {
        openBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            setTimeout(() => {
                const emailInput = modal.querySelector('input[type=email]');
                if(emailInput) emailInput.focus();
            }, 100);
        });
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
    // Fermer la modale si on clique en dehors du contenu
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
    // Fermer avec la touche Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            modal.style.display = 'none';
        }
    });
});
</script> 