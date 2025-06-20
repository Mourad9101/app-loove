const stripe = Stripe(window.STRIPE_PUBLIC_KEY);

function startCheckout() {
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation du paiement...';
    button.disabled = true;

    fetch(window.BASE_URL + '/payment/createCheckoutSession', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.sessionId) {
            return stripe.redirectToCheckout({
                sessionId: data.sessionId
            });
        } else {
            throw new Error(data.error || 'Erreur lors de la création de la session');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la préparation du paiement. Veuillez réessayer.');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function cancelSubscription() {
    if (confirm('Êtes-vous sûr de vouloir annuler votre abonnement Premium ?')) {
        fetch(window.BASE_URL + '/payment/cancelSubscription', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Abonnement annulé avec succès');
                location.reload();
            } else {
                alert(data.error || 'Erreur lors de l\'annulation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'annulation de l\'abonnement');
        });
    }
}
