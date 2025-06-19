<?php require_once APP_PATH . '/Views/layout/header.php'; ?>

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

    // Configuration de Pusher
    const pusher = new Pusher('<?= $_ENV['PUSHER_KEY'] ?? '' ?>', {
        cluster: '<?= $_ENV['PUSHER_CLUSTER'] ?? 'eu' ?>'
    });

    // S'abonner au canal de chat
    const channel = pusher.subscribe('chat-channel');
    
    // Écouter les nouveaux messages
    channel.bind('new-message', function(data) {
        // Ne pas afficher le message via Pusher si c'est l'expéditeur (déjà affiché localement)
        if (data.sender_id == currentUserId) return;

        // Vérifier si le message est pour cette conversation
        if (data.receiver_id == currentUserId || data.sender_id == currentUserId) {
            const chatBox = document.getElementById('chat-box');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-bubble received';
            messageDiv.innerHTML = `
                <p>${data.message}</p>
                <span class="timestamp">${new Date(data.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
            `;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });

    // Code pour l'envoi de messages
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-message-button');
    const currentUserId = <?= $currentUser['id'] ?>;
    const recipientId = <?= $recipientUser['id'] ?>;

    async function sendMessage() {
        const messageContent = messageInput.value.trim();
        if (!messageContent) return;

        try {
            const formData = new FormData();
            formData.append('conversation_id', recipientId);
            formData.append('sender_id', currentUserId);
            formData.append('receiver_id', recipientId);
            formData.append('message_content', messageContent);

            const response = await fetch(BASE_URL + '/messages/send', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();
            console.log('Réponse du serveur:', data); // Debug

            if (data.status === 'success') {
                messageInput.value = '';
                // Ajoute le message immédiatement côté expéditeur
                const chatBox = document.getElementById('chat-box');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message-bubble sent';
                messageDiv.innerHTML = `
                    <p>${messageContent}</p>
                    <span class="timestamp">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
                `;
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            } else {
                console.error('Erreur lors de l\'envoi du message:', data.message);
                alert('Impossible d\'envoyer le message: ' + data.message);
            }
        } catch (error) {
            console.error('Erreur réseau ou serveur lors de l\'envoi du message:', error);
            alert('Erreur lors de l\'envoi du message.');
        }
    }

    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
</script>

<style>
.message-page-container {
    display: flex;
    flex-direction: column;
    height: 100vh;
    background-color: #f5f5f5;
}

.message-header {
    display: flex;
    align-items: center;
    padding: 1rem;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.back-button {
    margin-right: 1rem;
    color: #666;
    text-decoration: none;
}

.header-profile-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profile-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-size: cover;
    background-position: center;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.message-bubble {
    max-width: 70%;
    padding: 0.8rem;
    border-radius: 1rem;
    position: relative;
}

.message-bubble.sent {
    align-self: flex-end;
    background-color:  #6c5ce7;
    color: white;
}

.message-bubble.received {
    align-self: flex-start;
    background-color: white;
    color: #333;
}

.timestamp {
    font-size: 0.7rem;
    opacity: 0.7;
    margin-top: 0.3rem;
    display: block;
}

.message-input-bar {
    display: flex;
    align-items: center;
    padding: 1rem;
    background-color: white;
    box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
    gap: 0.5rem;
}

.icon-button {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 0.5rem;
}

#message-input {
    flex: 1;
    border: 1px solid #ddd;
    border-radius: 1.5rem;
    padding: 0.8rem 1rem;
    resize: none;
    height: 40px;
    max-height: 120px;
    outline: none;
}

.send-button {
    color: #0084ff;
}

.no-messages {
    text-align: center;
    color: #666;
    margin: 2rem 0;
}
</style>
