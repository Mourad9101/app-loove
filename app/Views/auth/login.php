<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<div class="auth-container">
    <div class="auth-content">
        <img src="<?= BASE_URL ?>/public/images/Logo Evergem.png" alt="Logo" class="logo-img">
        <h1>Welcome to EverGem</h1>
        <p class="tagline">Find your precious one</p>
        
        <div class="cta-buttons">
            <button id="openLoginModal" class="btn btn-primary btn-lg">Se connecter</button>
            <a href="<?= BASE_URL ?>/login/google" class="btn btn-outline-primary btn-lg">Se connecter avec Google</a>
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
            <p> Vous n'avez pas de compte ? <a href="<?= BASE_URL ?>/register">S'inscrire</a></p>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/auth.js"></script>
</body>
</html>