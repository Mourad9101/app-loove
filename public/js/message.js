const pusher = new Pusher(window.PUSHER_KEY, {
    cluster: window.PUSHER_CLUSTER
});
const channel = pusher.subscribe('chat-channel');
const chatBox = document.getElementById('chat-box');
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-message-button');
const currentUserId = window.currentUserId;
const recipientId = window.recipientId;
const BASE_URL = window.BASE_URL;

channel.bind('new-message', function(data) {
    if (data.sender_id == currentUserId) return;
    if (data.receiver_id == currentUserId || data.sender_id == currentUserId) {
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
        if (data.status === 'success') {
            messageInput.value = '';
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message-bubble sent';
            messageDiv.innerHTML = `
                <p>${messageContent}</p>
                <span class="timestamp">${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</span>
            `;
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        } else {
            alert('Impossible d\'envoyer le message: ' + data.message);
        }
    } catch (error) {
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
