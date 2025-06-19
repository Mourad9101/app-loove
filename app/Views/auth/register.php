<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<div class="auth-container">
    <div class="auth-box">
        <h2>Sign Up</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/register" method="POST" class="auth-form">
            <div class="form-group">
                <label for="name">Full Name</label>
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
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" 
                       required minlength="8">
                <small class="form-text text-muted">Password must be at least 8 characters long</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" 
                       required minlength="8">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </div>

            <div class="auth-links">
                <p>Already have an account? <a href="<?= BASE_URL ?>/login">Log in</a></p>
            </div>
        </form>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
}

.auth-box {
    background: #fff;
    border-radius: 1.2rem;
    box-shadow: 0 8px 32px rgba(108, 92, 231, 0.18);
    padding: 2.2rem 2rem 1.5rem 2rem;
    min-width: 340px;
    max-width: 400px;
    width: 100%;
    position: relative;
    margin-top: 0;
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
}

.auth-box h2 {
    text-align: center;
    color: #6c5ce7;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 700;
}

.auth-form .form-group {
    margin-bottom: 1.2rem;
}

.auth-form label {
    font-weight: 600;
    color: #6c5ce7;
    margin-bottom: 0.3rem;
    display: block;
}

.auth-form .form-control {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #ecebfa;
    border-radius: 8px;
    font-size: 1rem;
    margin-bottom: 0.2rem;
    transition: border 0.2s;
}
.auth-form .form-control:focus {
    border: 2px solid #6c5ce7;
    outline: none;
}
.auth-form button[type="submit"] {
    width: 100%;
    margin-top: 0.5rem;
    padding: 10px 0;
    background: linear-gradient(90deg, #6c5ce7 0%, #a29bfe 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.auth-form button[type="submit"]:hover {
    background: linear-gradient(90deg, #a29bfe 0%, #6c5ce7 100%);
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
    color: #6c5ce7;
    text-decoration: none;
    font-weight: 500;
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
            alert('Passwords do not match!');
        }
    });
});
</script>

<?php
// Nettoyer les anciennes données du formulaire
unset($_SESSION['old_input']);
?> 