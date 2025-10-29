<?php
// Загрузка конфигурации
require_once 'config/database.php';

// Загрузка моделей
require_once 'models/Database.php';
require_once 'models/Product.php';
require_once 'models/Customer.php';
require_once 'models/Order.php';

// Загрузка контроллеров
require_once 'controllers/Controller.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/CustomerController.php';
require_once 'controllers/OrderController.php';

// Инициализация базы данных
$database = new Database();
$db = $database->getConnection();

// Определение страницы
$page = isset($_GET['page']) ? $_GET['page'] : 'products';

// Роутинг
switch ($page) {
    case 'products':
        $controller = new ProductController($db);
        $controller->index();
        break;
        
    case 'customers':
        $controller = new CustomerController($db);
        $controller->index();
        break;
        
    case 'orders':
        $controller = new OrderController($db);
        $controller->index();
        break;
        
    default:
        $controller = new ProductController($db);
        $controller->index();
        break;
}
?>