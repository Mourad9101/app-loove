<div class="auth-container">
    <div class="auth-box">
        <h2>Créer un compte</h2>
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
                <label for="email">Adresse email</label>
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
                <button type="submit" class="btn btn-primary w-100">Créer mon compte</button>
            </div>
            <div class="auth-links">
                <p>Vous avez déjà un compte ? <a href="<?= BASE_URL ?>/login">Se connecter</a></p>
            </div>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?> 