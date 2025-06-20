document.addEventListener('DOMContentLoaded', () => {
    // Gestion des formulaires
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                showAlert('Veuillez remplir tous les champs obligatoires', 'error');
            }
        });
    });

    // Système de like/pass
    const likeButtons = document.querySelectorAll('.like-button');
    const passButtons = document.querySelectorAll('.pass-button');

    likeButtons.forEach(button => {
        button.addEventListener('click', async (e) => {
            const userId = e.target.dataset.userId;
            try {
                const response = await fetch('/like', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ liked_user_id: userId })
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.match) {
                        showAlert('Nouveau match !', 'success');
                    }
                    // Faire disparaître la carte
                    button.closest('.profile-card').style.display = 'none';
                }
            } catch (error) {
                showAlert('Une erreur est survenue', 'error');
            }
        });
    });

    passButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            // Faire disparaître la carte
            button.closest('.profile-card').style.display = 'none';
        });
    });

    // Animation des cartes de fonctionnalités
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Gestion du menu mobile
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler && navbarCollapse) {
        navbarToggler.addEventListener('click', function() {
            navbarCollapse.classList.toggle('show');
        });
    }

    // Fermer le menu mobile lors du clic sur un lien
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }
        });
    });
});

// Fonction utilitaire pour afficher des alertes
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    document.querySelector('.main-content').insertAdjacentElement('afterbegin', alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
} 