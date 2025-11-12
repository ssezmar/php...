<?php

class HomeController extends Controller {
    private $product;

    public function __construct() {
        parent::__construct();
        $db = (new Database())->connect();
        $this->product = new Product($db);
    }

    public function index() {
        $products = $this->product->getAllProducts();
        $isGuest = !Auth::isLoggedIn();
        $isAdmin = Auth::isAdmin();

        $this->view('home/index', [
            'products' => $products,
            'isGuest' => $isGuest,
            'isAdmin' => $isAdmin
        ]);
    }
}
?>