<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Connexion' ?> - EverGem</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(108, 92, 231, 0.10);
            padding: 2.5rem 2rem 2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 340px;
            max-width: 400px;
            width: 100%;
        }
        .logo-img {
            width: 80px;
            margin-bottom: 0.5rem;
        }
        h1 {
            font-size: 2.3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            color: #222;
            text-align: center;
        }
        .tagline {
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 2rem;
            text-align: center;
        }
        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
            margin-bottom: 2rem;
        }
        .btn-lg {
            padding: 1rem 1.5rem;
            font-size: 1.2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            width: 100%;
        }
        .btn-primary {
            background: linear-gradient(90deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border: none;
            box-shadow: 0 2px 8px #a29bfe33;
        }
        .btn-primary:hover {
            opacity: 0.92;
        }
        .btn-outline-primary {
            background-color: #ecebfa;
            color: #6c5ce7;
            border: none;
        }
        .btn-outline-primary:hover {
            background-color: #d6d4fa;
        }
        .auth-box {
            display: block;
            width: 100%;
            margin-top: 2rem;
            padding: 24px 18px 18px 18px;
            border: none;
            border-radius: 1rem;
            background-color: #f8f9fa;
            box-shadow: 0 2px 8px rgba(108, 92, 231, 0.08);
            text-align: left;
        }
        .auth-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #222;
            font-size: 1.5rem;
            font-weight: 700;
        }
        .auth-box .form-group {
            margin-bottom: 1.2rem;
        }
        .auth-box label {
            font-weight: 600;
            color: #6c5ce7;
            margin-bottom: 0.3rem;
        }
        .auth-box input[type="email"],
        .auth-box input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #ecebfa;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 0.2rem;
            transition: border 0.2s;
        }
        .auth-box input[type="email"]:focus,
        .auth-box input[type="password"]:focus {
            border: 2px solid #6c5ce7;
            outline: none;
        }
        .auth-box button[type="submit"] {
            width: 100%;
            margin-top: 0.5rem;
            padding: 10px 0;
            background: linear-gradient(90deg, #6c5ce7 0%, #a29bfe 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .auth-box button[type="submit"]:hover {
            background: linear-gradient(90deg, #a29bfe 0%, #6c5ce7 100%);
        }
        .auth-links {
            text-align: center;
            margin-top: 1.2rem;
        }
        .auth-links a {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .signup-link {
            margin-top: 1.5rem;
            text-align: center;
        }
        .signup-link a {
            color: #6c5ce7;
            text-decoration: none;
            font-weight: 600;
        }
        .signup-link a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .login-container {
                padding: 1.2rem 0.5rem;
                min-width: unset;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <?= $content ?>
    </div>
</body>
</html> 