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
            // Vérification si les variables sont définies avant de les utiliser
            const PUSHER_NOTIF_INSTANCE_ID = '<?= defined("PUSHER_NOTIF_INSTANCE_ID") ? PUSHER_NOTIF_INSTANCE_ID : "" ?>';
            const USER_ID = '<?= $_SESSION["user_id"] ?? "" ?>';
            
            // Charger beams-client.js que si l'ID d'instance est défini
            if (PUSHER_NOTIF_INSTANCE_ID) {
                const beamsScript = document.createElement('script');
                beamsScript.src = '<?= BASE_URL ?>/public/js/beams-client.js';
                beamsScript.defer = true;
                document.head.appendChild(beamsScript);
            }
        </script>
    <?php endif; ?>
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
                            <div id="notification-list" class="notification-list"></div>
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
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
            </div>
        <?php endif; ?>

    <!-- Pusher -->
    <div id="pusher-notifications-container"></div>
    <script>
        window.PUSHER_APP_KEY = '<?= defined('PUSHER_APP_KEY') ? PUSHER_APP_KEY : '' ?>';
        window.PUSHER_APP_CLUSTER = '<?= defined('PUSHER_APP_CLUSTER') ? PUSHER_APP_CLUSTER : 'eu' ?>';
        window.CURRENT_USER_ID = <?= isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null' ?>;
        window.BASE_URL = '<?= BASE_URL ?>';
    </script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="<?= BASE_URL ?>/public/js/pusher-notifications.js"></script>

    <!-- Debug info -->
    <div class="debug-info">
        BASE_URL: <?= BASE_URL ?><br>
        Session ID: <?= session_id() ?><br>
        User ID: <?= $_SESSION['user_id'] ?? 'Non connecté' ?>
    </div>
</body>
</html> 