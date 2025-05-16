<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Debug styles -->
    <style>
        .debug-info {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <!-- Debug info -->
    <div class="debug-info">
        BASE_URL: <?= BASE_URL ?><br>
        Session ID: <?= session_id() ?><br>
        User ID: <?= $_SESSION['user_id'] ?? 'Non connecté' ?>
    </div>

    <header class="main-header">
        <nav class="nav-container">
            <a href="<?= BASE_URL ?>/" class="logo">
                <i class="fas fa-gem"></i> EverGem
            </a>
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/discover"><i class="fas fa-compass"></i> Découvrir</a>
                    <a href="<?= BASE_URL ?>/matches"><i class="fas fa-heart"></i> Matchs</a>
                    <a href="<?= BASE_URL ?>/messages"><i class="fas fa-comments"></i> Messages</a>
                    <a href="<?= BASE_URL ?>/profile"><i class="fas fa-user"></i> Profil</a>
                    <a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                    <a href="<?= BASE_URL ?>/register"><i class="fas fa-user-plus"></i> Inscription</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <main class="main-content"> 