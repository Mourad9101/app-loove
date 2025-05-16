<?php
// Mode débogage
define('DEBUG', true);

// Configuration de l'application
define('APP_NAME', 'EverGem');
define('APP_URL', '/Evergem3');
define('BASE_URL', '/Evergem3');

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