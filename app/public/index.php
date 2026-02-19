<?php

$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'home':
        require_once '../controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;

    default:
        echo "404 - Page non trouvée";
        break;
}
