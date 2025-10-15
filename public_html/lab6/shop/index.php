<?php
require_once 'config/database.php';
require_once 'models/Database.php';
require_once 'models/Product.php';
require_once 'models/Customer.php';
require_once 'models/Order.php';

// Инициализация базы данных
$database = new Database();
$db = $database->getConnection();

// Определение текущей страницы
$page = isset($_GET['page']) ? $_GET['page'] : 'products';

include 'views/header.php';

// Обработка AJAX запросов
/*
if (isset($_POST['ajax']) && $_POST['ajax'] == 'calculate_total') {
    $order = new Order($db);
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total = $order->calculateTotal($product_id, $quantity);
    echo json_encode(['total' => $total]);
    exit;
}
*/
// Загрузка соответствующей страницы
switch($page) {
    case 'products':
        include 'views/products/index.php';
        break;
    case 'customers':
        include 'views/customers/index.php';
        break;
    case 'orders':
        include 'views/orders/index.php';
        break;
    default:
        include 'views/products/index.php';
        break;
}

include 'views/footer.php';
?>