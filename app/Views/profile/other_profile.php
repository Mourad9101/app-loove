<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <!-- Image principale -->
                <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($user['image'] ?? 'default.jpg') ?>" 
                     class="card-img-top" 
                     alt="Photo de profil"
                     onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                
                <!-- Images supplémentaires -->
                <?php if (!empty($user['additional_images'])): ?>
                    <?php 
                    $additionalImages = json_decode($user['additional_images'], true);
                    if (is_array($additionalImages) && !empty($additionalImages)):
                    ?>
                        <div class="additional-images mt-2">
                            <div class="row">
                                <?php foreach ($additionalImages as $index => $image): ?>
                                    <div class="col-4 mb-2">
                                        <img src="<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($image) ?>" 
                                             class="img-fluid rounded" 
                                             alt="Photo supplémentaire <?= $index + 1 ?>"
                                             style="width: 100%; height: 80px; object-fit: cover;"
                                             onerror="this.src='<?= BASE_URL ?>/public/uploads/default.jpg'">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title">
                        <?= htmlspecialchars($user['first_name'] ?? '') ?> <?= htmlspecialchars($user['last_name'] ?? '') ?>
                        <?php if ($user['is_premium'] ?? false): ?>
                            <span class="premium-badge">
                                <i class="fas fa-crown"></i> Premium
                            </span>
                        <?php endif; ?>
                    </h5>
                    <p class="card-text">
                        <i class="fas fa-gem"></i> <?= htmlspecialchars($user['gemstone'] ?? '') ?>
                    </p>
                </div>
            </div>
            <?php if (($currentUser['is_premium'] ?? false) || ($isMatch ?? false)): ?>
            <div class="mt-3">
                <button class="btn btn-success btn-block" onclick="sendMessage(<?= htmlspecialchars($user['id']) ?>)">
                    <i class="fas fa-comment"></i> Envoyer un message
                </button>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">À propos de <?= htmlspecialchars($user['first_name'] ?? '') ?></h4>
                    <hr>
                    <p><?= nl2br(htmlspecialchars($user['bio'] ?? 'Pas de biographie.')) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendMessage(recipientId) {
    const messageContent = prompt("Écrivez votre message à " + recipientId + ":");
    if (messageContent !== null && messageContent.trim() !== '') {
        fetch(`<?= BASE_URL ?>/messages/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `conversation_id=${recipientId}&sender_id=<?= $_SESSION['user_id'] ?>&receiver_id=${recipientId}&message_content=${encodeURIComponent(messageContent)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Message envoyé avec succès !');
                // Rediriger vers la conversation ou actualiser
                window.location.href = `<?= BASE_URL ?>/messages/${recipientId}`;
            } else {
                alert(data.message || 'Erreur lors de l\'envoi du message.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur de communication avec le serveur.');
        });
    }
}
</script>
