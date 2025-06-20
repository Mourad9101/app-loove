<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-5">
                <h1 class="display-4 text-primary">Passez Premium</h1>
                <p class="lead">Débloquez toutes les fonctionnalités d'EverGem</p>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if ($user['is_premium']): ?>
                <!-- Utilisateur déjà Premium -->
                <div class="card premium-status-card">
                    <div class="card-body text-center">
                        <div class="premium-icon mb-3">
                            <i class="fas fa-crown fa-3x text-warning"></i>
                        </div>
                        <h3 class="card-title text-primary">Vous êtes déjà Premium !</h3>
                        <p class="card-text">Profitez de tous vos avantages Premium.</p>
                        
                        <?php if ($subscription): ?>
                            <div class="subscription-info mt-4">
                                <p><strong>Statut :</strong> 
                                    <span class="badge badge-success">Actif</span>
                                </p>
                                <p><strong>Prix :</strong> <?= number_format($subscription['amount'], 2) ?>€/mois</p>
                                <p><strong>Début :</strong> <?= date('d/m/Y', strtotime($subscription['created_at'])) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-danger mt-3" onclick="cancelSubscription()">
                            <i class="fas fa-times"></i> Annuler l'abonnement
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <!-- Plans d'abonnement -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card plan-card">
                            <div class="card-body text-center">
                                <h4 class="card-title">Gratuit</h4>
                                <div class="price">
                                    <span class="currency">€</span>
                                    <span class="amount">0</span>
                                    <span class="period">/mois</span>
                                </div>
                                <ul class="features-list">
                                    <li><i class="fas fa-check text-success"></i> 6 matchs par jour</li>
                                    <li><i class="fas fa-check text-success"></i> Profil de base</li>
                                    <li><i class="fas fa-check text-success"></i> Messagerie</li>
                                    <li><i class="fas fa-times text-muted"></i> Matchs illimités</li>
                                    <li><i class="fas fa-times text-muted"></i> Fonctionnalités avancées</li>
                                </ul>
                                <div class="current-plan-badge">
                                    <span class="badge badge-secondary">Plan actuel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-4">
                        <div class="card plan-card premium-plan">
                            <div class="card-body text-center">
                                <div class="premium-badge-plan">
                                    <i class="fas fa-crown"></i> Premium
                                </div>
                                <h4 class="card-title">Premium</h4>
                                <div class="price">
                                    <span class="currency">€</span>
                                    <span class="amount">9.99</span>
                                    <span class="period">/mois</span>
                                </div>
                                <ul class="features-list">
                                    <li><i class="fas fa-check text-success"></i> Matchs illimités</li>
                                    <li><i class="fas fa-check text-success"></i> Profil Premium</li>
                                    <li><i class="fas fa-check text-success"></i> Messagerie avancée</li>
                                    <li><i class="fas fa-check text-success"></i> Fonctionnalités exclusives</li>
                                    <li><i class="fas fa-check text-success"></i> Support prioritaire</li>
                                </ul>
                                <button class="btn btn-primary btn-lg btn-block" onclick="startCheckout()">
                                    <i class="fas fa-credit-card"></i> Commencer l'abonnement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations de sécurité -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-shield-alt text-success"></i> Paiement sécurisé
                        </h5>
                        <p class="card-text">
                            Vos informations de paiement sont protégées par Stripe, leader mondial du paiement en ligne. 
                            Vous pouvez annuler votre abonnement à tout moment.
                        </p>
                        <div class="security-badges">
                            <i class="fab fa-cc-visa fa-2x text-primary"></i>
                            <i class="fab fa-cc-mastercard fa-2x text-primary"></i>
                            <i class="fab fa-cc-paypal fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('<?= STRIPE_PUBLIC_KEY ?>');

function startCheckout() {
    // Afficher un loader
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation du paiement...';
    button.disabled = true;

    fetch('<?= BASE_URL ?>/payment/createCheckoutSession', {
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
        fetch('<?= BASE_URL ?>/payment/cancelSubscription', {
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
</script>

