<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Connexion' ?> - EverGem</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/auth.css">
</head>
<body class="<?= $bodyClass ?? '' ?>">
    <?php if (empty($noLoginContainer)): ?>
        <div class="login-container">
            <?= $content ?>
        </div>
    <?php else: ?>
        <?= $content ?>
    <?php endif; ?>
</body>
</html> 