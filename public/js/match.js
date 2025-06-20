document.addEventListener('DOMContentLoaded', () => {
    // Gérer le unmatch
    document.querySelectorAll('.unmatch-button').forEach(button => {
        button.addEventListener('click', async () => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce match ?')) {
                return;
            }

            const userId = button.dataset.userId;
            const card = button.closest('.match-card');
            
            try {
                const response = await fetch(`${BASE_URL}/matches/unmatch`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `user_id=${userId}`
                });
                
                const data = await response.json();
                
                if (data.success) {
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.match-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert('Une erreur est survenue');
                }
            } catch (error) {
                console.error('Erreur lors du unmatch:', error);
                alert('Une erreur est survenue');
            }
        });
    });
}); 