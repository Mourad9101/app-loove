<?php
namespace app\Core;

class Controller {
    protected function render($view, $data = [], $layout = 'layout/main.php') {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Démarrage de la mise en tampon
        ob_start();

        // Inclure la vue
        $viewPath = APP_PATH . '/Views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("View file not found: {$viewPath}");
        }

        // Récupérer le contenu et nettoyer le tampon
        $content = ob_get_clean();

        // Inclure le layout passé en paramètre
        $layoutPath = APP_PATH . '/Views/' . $layout;
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }

    protected function redirect($url) {
        header('Location: ' . APP_URL . $url);
        exit();
    }

    protected function json($data) {
        // Désactiver l'affichage des erreurs pour les requêtes JSON
        ini_set('display_errors', 0);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            $this->redirect('/login');
        }
    }

    public function isAjax() {
        return (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        );
    }

    protected function jsonResponse($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
} 