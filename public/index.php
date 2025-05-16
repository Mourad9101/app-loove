<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = ROOT_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Fichier non trouvé : " . $file);
    }
});

try {
    // Chargement de la configuration
    require_once ROOT_PATH . '/config/config.php';
    require_once ROOT_PATH . '/config/database.php';

    // Initialisation du routeur
    $router = new app\Core\Router();

    // Routes définies
    $router->addRoute('GET', '/', 'HomeController@index');
    $router->addRoute('GET', '/login', 'AuthController@loginForm');
    $router->addRoute('POST', '/login', 'AuthController@login');
    $router->addRoute('GET', '/register', 'AuthController@registerForm');
    $router->addRoute('POST', '/register', 'AuthController@register');
    $router->addRoute('GET', '/profile', 'ProfileController@show');
    $router->addRoute('GET', '/matches', 'MatchController@index');
    $router->addRoute('GET', '/messages', 'MessageController@index');

    // Dispatch de la requête
    $router->dispatch();
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "Une erreur est survenue : " . $e->getMessage();
} 