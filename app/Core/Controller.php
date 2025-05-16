<?php
namespace app\Core;

class Controller {
    protected function render($view, $data = []) {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Démarrer la mise en tampon
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

        // Inclure le layout si existant
        $layoutPath = APP_PATH . '/Views/layout/main.php';
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
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
} 