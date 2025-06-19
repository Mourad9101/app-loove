<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/pusher-notifications.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Pusher Beams -->
        <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
        <script>
            // Vérifier si les variables sont définies avant de les utiliser
            const PUSHER_NOTIF_INSTANCE_ID = '<?= defined("PUSHER_NOTIF_INSTANCE_ID") ? PUSHER_NOTIF_INSTANCE_ID : "" ?>';
            const USER_ID = '<?= $_SESSION["user_id"] ?? "" ?>';
            
            // Ne charger beams-client.js que si l'ID d'instance est défini
            if (PUSHER_NOTIF_INSTANCE_ID) {
                const beamsScript = document.createElement('script');
                beamsScript.src = '<?= BASE_URL ?>/public/js/beams-client.js';
                beamsScript.defer = true;
                document.head.appendChild(beamsScript);
            }
        </script>
    <?php endif; ?>
    
    <style>
        /* Basic styles for the notification dropdown */
        .notification-area {
            position: relative;
            display: inline-block;
            margin-left: 15px;
        }
        .notification-toggle {
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
        }
        .notification-dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 250px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
            border-radius: 5px;
            overflow: hidden;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-dropdown-content.show {
            display: block;
        }
        .notification-header {
            background-color: #eee;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .notification-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .notification-list li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .notification-list li:last-child {
            border-bottom: none;
        }
        .notification-list li:hover {
            background-color: #f1f1f1;
        }
        .notification-list li.unread {
            background-color: #e6f7ff;
            font-weight: bold;
        }
        .notification-list li img.notification-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        .notification-footer {
            border-top: 1px solid #ddd;
            text-align: center;
        }
        .notification-footer a {
            color: #007bff;
            text-decoration: none;
        }
        @media (max-width: 576px) {
            .main-header {
                display: none !important;
            }
        }

        /* Style pour les notifications toast */
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            max-width: 350px;
        }

        .notification-toast .notification-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .notification-toast .notification-content {
            flex: 1;
        }

        .notification-toast .notification-content strong {
            display: block;
            margin-bottom: 4px;
            color: #333;
        }

        .notification-toast .notification-content p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        .debug-info {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 9999;
            opacity: 0.7;
            transition: opacity 0.3s;
        }
        .debug-info:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-container">
            <a href="<?= BASE_URL ?>/" class="logo">
                <i class="fas fa-gem"></i> EverGem
            </a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/discover"><i class="fas fa-compass"></i> Découvrir</a>
                    <a href="<?= BASE_URL ?>/matches"><i class="fas fa-heart"></i> Matchs</a>
                    <a href="<?= BASE_URL ?>/messages">
                        <i class="fas fa-comments"></i> Messages
                        <span id="unread-messages-badge" class="badge bg-danger rounded-pill" style="display:none;">0</span>
                    </a>
                    <?php if (isset($_SESSION['is_premium']) && $_SESSION['is_premium']): ?>
                        <a href="<?= BASE_URL ?>/profile/views"><i class="fas fa-eye"></i> Visiteurs</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/payment"><i class="fas fa-crown"></i> Premium</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="<?= BASE_URL ?>/admin"><i class="fas fa-shield-alt"></i> Administration</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/profile"><i class="fas fa-user"></i> Profil</a>
                <div class="notification-area">
                    <a href="#" id="notification-toggle" class="notification-toggle">
                        <i class="fas fa-bell"></i>
                        <span id="unread-notifications-badge" class="badge" style="display: none;">0</span>
                    </a>
                    <div id="notification-dropdown" class="notification-dropdown">
                        <div id="notification-list" class="notification-list">
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    <a href="<?= BASE_URL ?>/register"><i class="fas fa-user-plus"></i> Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="main-content">

    <!-- Pusher -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        const PUSHER_APP_KEY = '<?= defined('PUSHER_APP_KEY') ? PUSHER_APP_KEY : '' ?>';
        const PUSHER_APP_CLUSTER = '<?= defined('PUSHER_APP_CLUSTER') ? PUSHER_APP_CLUSTER : 'eu' ?>';
        const CURRENT_USER_ID = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null' ?>;
        const BASE_URL = '<?= BASE_URL ?>';

        // Initialisation de Pusher pour les notifications
        document.addEventListener('DOMContentLoaded', function() {
            if (PUSHER_APP_KEY && CURRENT_USER_ID) {
                const pusher = new Pusher(PUSHER_APP_KEY, {
                    cluster: PUSHER_APP_CLUSTER
                });

                const channel = pusher.subscribe('notifications-channel');
                channel.bind('new-notification', function(data) {
                    console.log('Nouvelle notification reçue:', data);
                    if (data.user_id === CURRENT_USER_ID) {
                        // Mettre à jour le badge de notification
                        const badge = document.getElementById('unread-notifications-badge');
                        if (badge) {
                            const currentCount = parseInt(badge.textContent) || 0;
                            badge.textContent = currentCount + 1;
                            badge.style.display = 'inline-block';
                        }
                    }
                });
            }
        });
    </script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/pusher-notifications.css">
    <script src="<?= BASE_URL ?>/public/js/pusher-notifications.js"></script>

    <!-- Debug info -->
    <div class="debug-info">
        BASE_URL: <?= BASE_URL ?><br>
        Session ID: <?= session_id() ?><br>
        User ID: <?= $_SESSION['user_id'] ?? 'Non connecté' ?>
    </div>

    <!-- Suppression du vieux JS notifications -->
</body>
</html> 