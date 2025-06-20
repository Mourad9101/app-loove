<?php
namespace app\Controllers;

use app\Core\Controller;

class HomeController extends Controller {
    public function index() {
        return $this->render('home/index', [
            'title' => 'Accueil - ' . APP_NAME,
            'description' => 'Bienvenue sur ' . APP_NAME . ', trouvez l\'amour qui vous correspond !'
        ]);
    }
} 