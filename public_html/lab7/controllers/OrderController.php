<?php

class OrderController extends Controller {
    private $order;
    private $product;
    private $customer;

    public function __construct() {
        parent::__construct();
        $db = (new Database())->connect();
        $this->order = new Order($db);
        $this->product = new Product($db);
        $this->customer = new Customer($db);
    }

    public function index() {
        Auth::requireLogin();

        // Задание 1: Список заказов с разными полями для админа и пользователя
        $orders = $this->order->getAllOrders($this->auth);
        $isAdmin = Auth::isAdmin();

        $this->view('orders/index', [
            'orders' => $orders,
            'isAdmin' => $isAdmin
        ]);
    }

    public function create() {
        Auth::requireLogin();

        $isAdmin = Auth::isAdmin();
        $products = $this->product->getAllProducts();
        $customers = $isAdmin ? $this->customer->getAllCustomers() : [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_id = $_POST['customer_id'] ?? null;
            $product_id = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if (!$customer_id || !$product_id) {
                $error = 'Все поля обязательны';
            } else {
                $product = $this->product->getProductById($product_id);
                $total_price = $product['price'] * $quantity;

                if ($this->order->createOrder($customer_id, $product_id, $quantity, $total_price)) {
                    $this->redirect('/index.php?controller=order&action=index');
                }
            }
        }

        $this->view('orders/form', [
            'order' => null,
            'products' => $products,
            'customers' => $customers,
            'isAdmin' => $isAdmin,
            'error' => $error ?? null
        ]);
    }

    public function edit() {
        Auth::requireLogin();

        $id = $_GET['id'] ?? null;
        $order = $this->order->getOrderById($id, $this->auth);

        if (!$order) {
            $this->redirect('/index.php?controller=order&action=index');
        }

        $isAdmin = Auth::isAdmin();
        $products = $this->product->getAllProducts();
        $customers = $isAdmin ? $this->customer->getAllCustomers() : [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            if (!$product_id) {
                $error = 'Все поля обязательны';
            } else {
                $product = $this->product->getProductById($product_id);
                $total_price = $product['price'] * $quantity;

                if ($this->order->updateOrder($id, $product_id, $quantity, $total_price, $this->auth)) {
                    $this->redirect('/index.php?controller=order&action=index');
                }
            }
        }

        $this->view('orders/form', [
            'order' => $order,
            'products' => $products,
            'customers' => $customers,
            'isAdmin' => $isAdmin,
            'error' => $error ?? null
        ]);
    }

    public function delete() {
        Auth::requireLogin();

        $id = $_GET['id'] ?? null;
        if ($this->order->deleteOrder($id, $this->auth)) {
            $this->redirect('/index.php?controller=order&action=index');
        }
    }
}
?>