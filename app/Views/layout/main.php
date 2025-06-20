<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : APP_NAME ?></title>
    
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">

    <!-- Variables CSS personnalisées -->
    <style>
        :root {
            --primary-color: #6c5ce7;
            --accent-color: #a29bfe;
            --background-color: #ffffff;
            --text-color: #2d3436;
            --border-radius: 8px;
        }

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
            padding: 0.5rem 1rem;
        }
        .notification-dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 250px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1000;
            right: 0;
            border-radius: 5px;
            overflow: hidden;
            max-height: 400px;
            overflow-y: auto;
            top: 100%;
        }
        .notification-dropdown-content.show {
            display: block !important;
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

        .navbar-nav .notification-area .nav-link {
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
    <!-- Contenu principal -->
    <main class="main-content">
        <?= isset($content) ? $content : '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Pusher Beams -->
    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
    <script>
        const beamsClient = new PusherPushNotifications.Client({
            instanceId: '<?= PUSHER_NOTIF_INSTANCE_ID ?>',
        });

        beamsClient.start()
            .then(() => beamsClient.addDeviceInterest('hello'))
            .then(() => console.log('Successfully registered and subscribed!'))
            .catch(console.error);
    </script>
</body>
</html> 