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

    const registerForm = document.querySelector('.register-page .auth-form');
    if (registerForm) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        registerForm.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    }

    const resetForm = document.querySelector('.reset-page .auth-form');
    if (resetForm) {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        resetForm.addEventListener('submit', function(e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas !');
            }
        });
    }

    const toastContainer = document.createElement('div');
    toastContainer.className = 'toast-container';
    document.body.appendChild(toastContainer);

    function showToast(message, type = 'error') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;

        toastContainer.appendChild(toast);

        // Animation d'apparition
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Disparition après 5 secondes
        setTimeout(() => {
            toast.classList.remove('show');
            // Supprimer l'élément du DOM après la transition
            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 5000);
    }

    // Lire les messages flash depuis les éléments cachés
    const errorToast = document.getElementById('toast-error-message');
    const successToast = document.getElementById('toast-success-message');

    if (errorToast && errorToast.dataset.message) {
        showToast(errorToast.dataset.message, 'error');
    }

    if (successToast && successToast.dataset.message) {
        showToast(successToast.dataset.message, 'success');
    }
});