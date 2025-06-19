<div class="nav-item">
    <a href="#" id="notification-toggle" class="nav-link position-relative">
        <i class="fas fa-bell"></i>
        <span id="unread-notifications-badge" class="badge" style="display: none;">0</span>
    </a>
    <div id="notification-dropdown" class="notification-dropdown">
        <div id="notification-list" class="notification-list">
            <!-- Les notifications seront chargées ici via Pusher -->
        </div>
    </div>
</div>

<!-- À la fin du fichier, avant la fermeture de body -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/notifications.css">
<script src="<?= BASE_URL ?>/public/js/notifications.js"></script> 