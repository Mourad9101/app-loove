<div class="container mt-5">
    <h1>Conversation avec l'utilisateur ID: <?php echo htmlspecialchars($conversationId); ?></h1>
    <div id="chat-box" class="card p-3" style="height: 400px; overflow-y: scroll;">
    </div>
    <div class="input-group mb-3 mt-3">
        <input type="text" id="message-input" class="form-control" placeholder="Tapez votre message...">
        <button class="btn btn-primary" id="send-button">Envoyer</button>
    </div>
</div>

<script>
    // Récupérer BASE_URL du PHP pour les requêtes AJAX
    const BASE_URL = <?php echo json_encode(BASE_URL); ?>;
    const conversationId = <?php echo json_encode($conversationId); ?>;
    
    const currentUserId = 1; // ID de l'utilisateur connecté
    const otherUserId = 2;   // ID de l'autre utilisateur dans la conversation

    let lastMessageId = 0; // Pour suivre le dernier message affiché

    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chat-box');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');

        // Fonction pour ajouter un message à la boîte de chat
        function addMessageToChatBox(senderId, messageContent) {
            const messageElement = document.createElement('p');
            const senderName = (senderId === currentUserId) ? 'Moi' : 'Autre utilisateur';
            messageElement.innerHTML = `<strong>${senderName}:</strong> ${messageContent}`;
            chatBox.appendChild(messageElement);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Fonction pour envoyer un message via AJAX
        async function sendMessage() {
            const message = messageInput.value.trim();
            if (message) {
                const formData = new FormData();
                formData.append('conversation_id', conversationId);
                formData.append('sender_id', currentUserId);
                formData.append('receiver_id', otherUserId);
                formData.append('message_content', message);

                try {
                    const response = await fetch(`${BASE_URL}/messages/send`, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    if (data.status === 'success') {
                        messageInput.value = '';
                        // Après l'envoi, récupérer les messages pour s'assurer que le message envoyé est affiché
                        fetchNewMessages(); 
                    } else {
                        console.error('Erreur lors de l\'envoi du message:', data.message);
                    }
                } catch (error) {
                    console.error('Erreur réseau ou serveur lors de l\'envoi du message:', error);
                }
            }
        }

        // Fonction pour récupérer les nouveaux messages via polling
        async function fetchNewMessages() {
            try {
                const response = await fetch(`${BASE_URL}/messages/getNewMessages?conversation_id=${conversationId}&last_message_id=${lastMessageId}`);
                const data = await response.json();

                if (data.status === 'success' && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        addMessageToChatBox(parseInt(msg.sender_id), msg.message_content);
                    });
                    // Mettre à jour le dernier ID de message pour le prochain polling
                    lastMessageId = data.latest_message_id; 
                }
            } catch (error) {
                console.error('Erreur lors de la récupération des messages:', error);
            }
        }

        sendButton.addEventListener('click', sendMessage);

        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        // Initialiser le chat en récupérant les messages existants
        fetchNewMessages();

        // Démarrer le polling toutes les 2 secondes (tu peux ajuster ce délai)
        setInterval(fetchNewMessages, 2000); 
    });
</script> 