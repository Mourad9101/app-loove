<?php

// Ce fichier sert à définir des constantes globales et à charger la configuration
// à partir des variables d'environnement (.env).

// IMPORTANT : Ce fichier ne doit contenir AUCUNE information sensible.
// Mode débogage
define('DEBUG', true);

// Configuration de l'application
define('APP_NAME', 'EverGem');
define('APP_URL', '/Evergem3'); // Pour les chemins internes de l'application
define('BASE_URL', '/Evergem3'); // URL de base pour les chemins relatifs
define('NGROK_URL', 'https://d9a4-2a01-e0a-348-7e0-91d7-fda3-15ec-d9e5.ngrok-free.app/Evergem3');

// Configuration Stripe (chargée depuis .env)
define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY'] ?? '');
define('STRIPE_PUBLIC_KEY', $_ENV['STRIPE_PUBLIC_KEY'] ?? '');
define('STRIPE_WEBHOOK_SECRET', $_ENV['STRIPE_WEBHOOK_SECRET'] ?? '');

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'evergem');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Chemins de l'application
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('UPLOAD_PATH', ROOT_PATH . '/upload');

// Configuration du fuseau horaire
date_default_timezone_set('Europe/Paris');

// Configuration des erreurs en développement
if (DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Constantes de l'application
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Configuration Pusher Beams
define('PUSHER_NOTIF_INSTANCE_ID', $_ENV['PUSHER_NOTIF_INSTANCE_ID'] ?? '');
define('PUSHER_NOTIF_PRIMARY_KEY', $_ENV['PUSHER_NOTIF_PRIMARY_KEY'] ?? '');

// New constant
define('APP_FULL_URL', $_ENV['APP_FULL_URL'] ?? '');