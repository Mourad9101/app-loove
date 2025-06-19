class PusherNotifications {
    constructor() {
        console.log('Initialisation des notifications...');
        this.notifications = [];
        this.unreadCount = 0;
        this.notificationList = document.getElementById('notification-list');
        this.notificationBadge = document.getElementById('unread-notifications-badge');
        this.notificationDropdown = document.getElementById('notification-dropdown');
        this.notificationToggle = document.getElementById('notification-toggle');
        
        console.log('Éléments trouvés:', {
            list: this.notificationList,
            badge: this.notificationBadge,
            dropdown: this.notificationDropdown,
            toggle: this.notificationToggle
        });
        
        this.loadUnreadCount();
        this.initializePusher();
        this.setupEventListeners();
        this.setupDropdownHeader();
    }

    loadUnreadCount() {
        fetch(`${BASE_URL}/notifications/unread-count`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.count !== undefined) {
                    this.unreadCount = data.count;
                    this.updateUnreadCount();
                }
            })
            .catch(error => console.error('Erreur lors de la récupération du nombre de notifications non lues:', error));
    }

    initializePusher() {
        // Initialiser Pusher
        const pusher = new Pusher(PUSHER_APP_KEY, {
            cluster: PUSHER_APP_CLUSTER
        });

        // S'abonner au canal de notifications
        const channel = pusher.subscribe('notifications-channel');

        // Écouter les nouvelles notifications
        channel.bind('new-notification', (data) => {
            if (data.user_id === CURRENT_USER_ID) {
                this.addNotification(data);
                this.updateUnreadCount();
                this.showNotificationToast(data);
            }
        });
    }

    setupEventListeners() {
        // Gérer le clic sur le bouton de notifications
        this.notificationToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.notificationDropdown.classList.toggle('show');
            
            // Si le dropdown est ouvert, charger les notifications et marquer comme lues
            if (this.notificationDropdown.classList.contains('show')) {
                this.loadNotifications();
                this.markNotificationsAsRead();
            }
        });

        // Fermer le dropdown quand on clique en dehors
        document.addEventListener('click', (e) => {
            if (!this.notificationDropdown.contains(e.target) && !this.notificationToggle.contains(e.target)) {
                this.notificationDropdown.classList.remove('show');
            }
        });
    }

    setupDropdownHeader() {
        // Ajouter le header au dropdown
        const header = document.createElement('div');
        header.className = 'notification-header';
        header.textContent = 'Notifications';
        this.notificationList.insertBefore(header, this.notificationList.firstChild);
    }

    addNotification(notification) {
        // Ajouter la notification au début de la liste
        this.notifications.unshift(notification);
        
        // Récupérer l'image de l'expéditeur avec une meilleure gestion du fallback
        let senderImage = 'default.jpg';
        if (notification.data?.sender_image && notification.data.sender_image !== 'default.jpg') {
            senderImage = notification.data.sender_image;
        } else if (notification.sender_image && notification.sender_image !== 'default.jpg') {
            senderImage = notification.sender_image;
        } else if (notification.image && notification.image !== 'default.jpg') {
            senderImage = notification.image;
        }
        
        // Créer l'élément HTML de la notification
        const notificationElement = document.createElement('div');
        notificationElement.className = 'notification-item unread';
        notificationElement.dataset.id = notification.id;
        notificationElement.innerHTML = `
            <img src="${BASE_URL}/public/uploads/${senderImage}" alt="Avatar" class="notification-avatar" onerror="this.src='${BASE_URL}/public/images/Logo Evergem.png'">
            <div class="notification-content">
                <p>${notification.message}</p>
                <small class="notification-time">À l'instant</small>
                <div class="notification-actions">
                    <button class="notification-action-btn mark-as-read" data-id="${notification.id}">
                        <i class="fas fa-check"></i> Marquer comme lu
                    </button>
                </div>
            </div>
        `;

        // Ajouter la notification à la liste
        this.notificationList.appendChild(notificationElement);

        // Mettre à jour le compteur
        this.unreadCount++;
        this.updateUnreadCount();

        // Ajouter les écouteurs d'événements
        this.addNotificationEventListeners(notificationElement);

        // Rediriger vers la page des messages
        window.location.href = `${BASE_URL}/messages`;
    }

    updateUnreadCount() {
        if (this.unreadCount > 0) {
            this.notificationBadge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            this.notificationBadge.style.display = 'flex';
        } else {
            this.notificationBadge.style.display = 'none';
        }
    }

    markAsRead(notificationId, element) {
        // Marquer la notification comme lue visuellement
        element.classList.remove('unread');
        
        // Mettre à jour l'état local
        const index = this.notifications.findIndex(n => n.id === notificationId);
        if (index !== -1) {
            this.notifications[index].read_at = new Date().toISOString();
        }
        
        // Mettre à jour le compteur
        this.unreadCount = Math.max(0, this.unreadCount - 1);
        this.updateUnreadCount();

        // Supprimer l'élément de la liste
        element.remove();

        // Si c'était la dernière notification, afficher le message "Aucune nouvelle notification"
        if (this.unreadCount === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'empty-notifications';
            emptyMessage.textContent = 'Aucune nouvelle notification';
            this.notificationList.appendChild(emptyMessage);
        }

        // Envoyer la requête au serveur
        fetch(`${BASE_URL}/notifications/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: [notificationId] })
        }).catch(error => console.error('Erreur lors du marquage de la notification comme lue:', error));
    }

    showNotificationToast(notification) {
        // Récupérer l'image de l'expéditeur depuis le bon endroit dans l'objet notification
        const senderImage = notification.data?.sender_image || notification.sender_image || 'default.jpg';
        
        // Créer le toast
        const toast = document.createElement('div');
        toast.className = 'notification-toast';
        toast.innerHTML = `
            <div class="toast-content">
                <img src="${BASE_URL}/public/uploads/${senderImage}" alt="Avatar" class="toast-avatar">
                <div class="toast-message">
                    <p>${notification.message}</p>
                    <div class="toast-time">À l'instant</div>
                </div>
            </div>
            <button class="close-toast">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Ajouter le toast au conteneur
        const toastContainer = document.getElementById('toast-container') || this.createToastContainer();
        toastContainer.appendChild(toast);

        // Ajouter l'événement de fermeture
        toast.querySelector('.close-toast').addEventListener('click', () => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 300);
        });

        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
        return container;
    }

    markNotificationsAsRead() {
        const unreadNotifications = this.notificationList.querySelectorAll('.notification-item.unread');
        if (unreadNotifications.length === 0) return;

        const notificationIds = Array.from(unreadNotifications).map(item => item.dataset.id);
        
        fetch(`${BASE_URL}/notifications/mark-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: notificationIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                unreadNotifications.forEach(notification => {
                    notification.classList.remove('unread');
                });
                this.updateUnreadCount();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    loadNotifications() {
        fetch(`${BASE_URL}/notifications/get`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.notifications) {
                    // Nettoyer la liste sauf le header
                    const header = this.notificationList.querySelector('.notification-header');
                    this.notificationList.innerHTML = '';
                    if (header) this.notificationList.appendChild(header);
                    
                    // Filtrer pour n'afficher que les notifications non lues
                    const unreadNotifications = data.notifications.filter(notif => !notif.read_at);
                    
                    if (unreadNotifications.length === 0) {
                        const emptyMessage = document.createElement('div');
                        emptyMessage.className = 'empty-notifications';
                        emptyMessage.textContent = 'Aucune nouvelle notification';
                        this.notificationList.appendChild(emptyMessage);
                    } else {
                        unreadNotifications.forEach(notification => {
                            this.addNotification(notification);
                        });
                    }
                    
                    // Mettre à jour le compteur
                    this.unreadCount = unreadNotifications.length;
                    this.updateUnreadCount();
                }
            })
            .catch(error => console.error('Erreur lors du chargement des notifications:', error));
    }
}

// Initialiser les notifications quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé, initialisation des notifications...');
    new PusherNotifications();
}); 