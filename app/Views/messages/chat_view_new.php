<?php require_once APP_PATH . '/Views/layout/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/message.css">

<div class="message-page-container">
    <div class="message-header">
        <a href="<?= BASE_URL ?>/messages" class="back-button"><i class="fas fa-chevron-left"></i></a>
        <div class="header-profile-info">
            <div class="profile-avatar-small" style="background-image: url('<?= BASE_URL ?>/public/uploads/<?= htmlspecialchars($recipientUser['image'] ?? 'default.jpg') ?>');"></div>
            <span><?= htmlspecialchars($recipientUser['first_name'] ?? 'Utilisateur') ?></span>
        </div>
    </div>

    <div class="messages-container" id="chat-box">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message-bubble <?= ($message['sender_id'] == $currentUser['id']) ? 'sent' : 'received' ?>">
                    <p><?= htmlspecialchars($message['message_content']) ?></p>
                    <span class="timestamp">
                        <?= date('H:i', strtotime($message['created_at'])) ?>
                        <?php if ($message['sender_id'] == $currentUser['id']): ?>
                            <span class="double-checks" style="margin-left: 4px; vertical-align: middle;">
                                <?php if ($message['is_read'] == 1): ?>
                                    <i class="fa-solid fa-check-double" style="color: #fff; font-size: 1.1em;"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-check-double" style="color: #bbb; font-size: 1.1em;"></i>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-messages">Dites bonjour à <?= htmlspecialchars($recipientUser['first_name'] ?? 'Utilisateur') ?> !</p>
        <?php endif; ?>
    </div>

    <div class="message-input-bar">
        <button class="icon-button attachment-button"><i class="fas fa-paperclip"></i></button>
        <textarea id="message-input" placeholder="Écrire un message..."></textarea>
        <button class="icon-button send-button" id="send-message-button"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<!-- Ajout du script Pusher -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    window.BASE_URL = '<?= BASE_URL ?>';
    window.currentUserId = <?= $currentUser['id'] ?>;
    window.recipientId = <?= $recipientUser['id'] ?>;
    window.PUSHER_KEY = '<?= $_ENV['PUSHER_KEY'] ?? '' ?>';
    window.PUSHER_CLUSTER = '<?= $_ENV['PUSHER_CLUSTER'] ?? 'eu' ?>';
</script>
<script src="<?= BASE_URL ?>/public/js/message.js"></script>
