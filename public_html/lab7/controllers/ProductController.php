<?php

class ProductController extends Controller {
    private $product;

    public function __construct() {
        parent::__construct();
        $db = (new Database())->connect();
        $this->product = new Product($db);
    }

    public function index() {
        // Задание 2: незарегистрированный пользователь видит товары без возможности редактировать
        $products = $this->product->getAllProducts();
        $isGuest = !Auth::isLoggedIn();
        $isAdmin = Auth::isAdmin();

        $this->view('products/index', [
            'products' => $products,
            'isGuest' => $isGuest,
            'isAdmin' => $isAdmin
        ]);
    }

    public function create() {
        Auth::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;

            if ($this->product->createProduct($name, $description, $price)) {
                $this->redirect('/index.php?controller=product&action=index');
            }
        }

        $this->view('products/form', ['product' => null]);
    }

    public function edit() {
        Auth::requireAdmin();

        $id = $_GET['id'] ?? null;
        $product = $this->product->getProductById($id);

        if (!$product) {
            $this->redirect('/index.php?controller=product&action=index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;

            if ($this->product->updateProduct($id, $name, $description, $price)) {
                $this->redirect('/index.php?controller=product&action=index');
            }
        }

        $this->view('products/form', ['product' => $product]);
    }

    public function delete() {
        Auth::requireAdmin();

        $id = $_GET['id'] ?? null;
        if ($this->product->deleteProduct($id)) {
            $this->redirect('/index.php?controller=product&action=index');
        }
    }
}
?>