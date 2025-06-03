<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EverGem - Trouvez votre pierre précieuse</title>
    
    <!-- Polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/variables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--background-color);
            color: var(--text-primary);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .app-container {
            max-width: 100%;
            min-height: 100vh;
            margin: 0 auto;
            position: relative;
        }

        @media (min-width: 768px) {
            .app-container {
                max-width: 440px;
                box-shadow: var(--shadow-lg);
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?= $content ?>
    </div>
</body>
</html> 