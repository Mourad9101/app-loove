<div class="auth-container forgot-page">
    <div class="auth-box">
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success" style="margin-bottom:1rem; color: #27ae60; background: #eafaf1; border: 1px solid #b2f2d7; padding: 10px 15px; border-radius: 6px; text-align:center;">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger" style="margin-bottom:1rem; color: #d63031; background: #ffeaea; border: 1px solid #fab1a0; padding: 10px 15px; border-radius: 6px; text-align:center;">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <h2>Mot de passe oublié ?</h2>
        <p class="info-text">Saisis ton adresse email pour recevoir un lien de réinitialisation.</p>
        <form action="<?= BASE_URL ?>/forgot-password" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" id="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100">Envoyer le lien</button>
        </form>
        <div class="auth-links">
            <a href="<?= BASE_URL ?>/login">Retour à la connexion</a>
        </div>
    </div>
</div>

<head>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

</head>