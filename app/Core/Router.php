<?php
namespace app\Core;

class Router {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        error_log("DEBUG Router: Requête pour URI originale: " . $requestUri . " avec méthode: " . $requestMethod);

        foreach ($this->routes as $route) {
            error_log("DEBUG Router: Tentative de correspondance avec la route: " . $route['method'] . " " . $route['path']);
            $params = [];
            if ($route['method'] === $requestMethod && $this->matchPath($route['path'], $requestUri, $params)) {
                error_log("DEBUG Router: Route correspondante trouvée: " . $route['path']);
                return $this->executeHandler($route['handler'], $params);
            }
        }
        $this->notFound();
    }

    private function matchPath($routePath, $requestUri, &$params) {
        // Nettoyer les slashes en début et fin
        $routePath = trim($routePath, '/');
        $requestUri = trim($requestUri, '/');

        error_log("DEBUG Router Match: Route Path: " . $routePath . ", Request URI: " . $requestUri);

        // Si les chemins sont identiques, pas besoin de vérifier les paramètres
        if ($routePath === $requestUri) {
            error_log("DEBUG Router Match: Correspondance exacte trouvée.");
            return true;
        }

        // Convertir les chemins en tableaux
        $routeParts = explode('/', $routePath);
        $requestParts = explode('/', $requestUri);

        // Si le nombre de segments est différent, ce n'est pas une correspondance
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }

        // Vérifier chaque segment
        for ($i = 0; $i < count($routeParts); $i++) {
            $routePart = $routeParts[$i];
            $requestPart = $requestParts[$i];

            // Vérifier si c'est un paramètre dynamique
            if (preg_match('/^{([^}]+)}$/', $routePart, $matches)) {
                // Stocker le paramètre
                $params[$matches[1]] = $requestPart;
                continue;
            }

            // Si ce n'est pas un paramètre, les segments doivent correspondre exactement
            if ($routePart !== $requestPart) {
                return false;
            }
        }

        return true;
    }

    private function executeHandler($handler, $params = []) {
        list($controller, $method) = explode('@', $handler);
        $controllerClass = "app\\Controllers\\{$controller}";

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();

        error_log("DEBUG Router: Attempting to call method '{$method}' on controller '{$controllerClass}'");

        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in controller {$controllerClass}");
        }

        // Passer les paramètres à la méthode
        return $controller->$method(...array_values($params));
    }

    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        if (file_exists(APP_PATH . '/Views/errors/404.php')) {
            include APP_PATH . '/Views/errors/404.php';
        } else {
            echo "Page not found";
        }
        exit();
    }
} 