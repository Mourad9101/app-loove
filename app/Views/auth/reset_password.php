<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<div class="auth-container">
    <div class="auth-box">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="margin-bottom:1rem; color: #d63031; background: #ffeaea; border: 1px solid #fab1a0; padding: 10px 15px; border-radius: 6px; text-align:center;">
                <?= $error; ?>
            </div>
        <?php endif; ?>
        <h2>Réinitialiser le mot de passe</h2>
        <p class="info-text">Choisis un nouveau mot de passe pour ton compte.</p>
        <form action="<?= BASE_URL ?>/reset-password?token=<?= htmlspecialchars($_GET['token'] ?? '') ?>&email=<?= urlencode($_GET['email'] ?? '') ?>" method="POST" class="auth-form">
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary w-100">Réinitialiser</button>
        </form>
        <div class="auth-links">
            <a href="<?= BASE_URL ?>/login">Retour à la connexion</a>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>/public/js/auth.js"></script> 