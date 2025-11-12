<?php
session_start();

// Подключаем классы
require_once './config/db.php';
require_once './models/User.php';
require_once './models/Auth.php';
require_once './models/Product.php';
require_once './models/Order.php';
require_once './models/Customer.php';
require_once './controllers/Controller.php';
require_once './controllers/AuthController.php';
require_once './controllers/ProductController.php';
require_once './controllers/OrderController.php';
require_once './controllers/HomeController.php';

// Получаем параметры маршрутизации
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Маршрутизация
try {
    $controllerClass = ucfirst($controller) . 'Controller';

    if (class_exists($controllerClass)) {
        $controllerInstance = new $controllerClass();

        if (method_exists($controllerInstance, $action)) {
            $controllerInstance->$action();
        } else {
            die("Действие $action не найдено в контроллере $controllerClass");
        }
    } else {
        die("Контроллер $controllerClass не найден");
    }
} catch (Exception $e) {
    die("Ошибка: " . htmlspecialchars($e->getMessage()));
}
?>