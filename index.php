<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chargement de la configuration avant tout
require_once __DIR__ . '/config/config.php';

// Configuration des sessions avant de les démarrer
ini_set('session.cookie_lifetime', 3600);
ini_set('session.gc_maxlifetime', 3600);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $file = ROOT_PATH . '/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    } else {
        error_log("Fichier non trouvé : " . $file);
        if (DEBUG) {
            echo "Fichier non trouvé : " . $file . "<br>";
        }
    }
});

try {
    // Initialisation du routeur
    $router = new app\Core\Router();

    // Routes définies
    $router->addRoute('GET', BASE_URL . '/', 'HomeController@index');
    
    // Routes d'authentification
    $router->addRoute('GET', BASE_URL . '/login', 'AuthController@loginForm');
    $router->addRoute('POST', BASE_URL . '/login', 'AuthController@login');
    $router->addRoute('GET', BASE_URL . '/register', 'AuthController@registerForm');
    $router->addRoute('POST', BASE_URL . '/register', 'AuthController@register');
    $router->addRoute('GET', BASE_URL . '/logout', 'AuthController@logout');
    
    // Routes de profil
    $router->addRoute('GET', BASE_URL . '/profile', 'ProfileController@index');
    $router->addRoute('GET', BASE_URL . '/profile/edit', 'ProfileController@edit');
    $router->addRoute('POST', BASE_URL . '/profile/edit', 'ProfileController@update');
    
    // Routes d'onboarding - Nouvelle version
    $router->addRoute('GET', BASE_URL . '/onboarding', 'OnboardingController@index');
    $router->addRoute('POST', BASE_URL . '/onboarding', 'OnboardingController@index');
    
    // Routes de l'application principale
    $router->addRoute('GET', BASE_URL . '/matches', 'MatchController@index');
    $router->addRoute('GET', BASE_URL . '/discover', 'MatchController@discover');
    $router->addRoute('POST', BASE_URL . '/matches/like', 'MatchController@like');
    $router->addRoute('POST', BASE_URL . '/matches/pass', 'MatchController@pass');
    $router->addRoute('GET', BASE_URL . '/messages', 'MessageController@index');
    $router->addRoute('GET', BASE_URL . '/messages/{id}', 'MessageController@show');
    $router->addRoute('POST', BASE_URL . '/messages/send', 'MessageController@send');

    // Dispatch de la requête
    $router->dispatch();
} catch (Exception $e) {
    error_log($e->getMessage());
    if (DEBUG) {
        echo "Une erreur est survenue : " . $e->getMessage() . "<br>";
        echo "Trace : <pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        include APP_PATH . '/Views/errors/500.php';
    }
} 