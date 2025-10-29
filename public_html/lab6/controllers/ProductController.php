<?php
class ProductController extends Controller {
    private $productModel;
    
    public function __construct($database) {
        parent::__construct($database);
        $this->productModel = new Product($this->db);
    }
    
    public function index() {
        $message = '';
        
        // Создание товара
        if ($_POST && isset($_POST['create'])) {
            $this->productModel->name = $_POST['name'];
            $this->productModel->price = $_POST['price'];
            $this->productModel->description = $_POST['description'];
            
            if ($this->productModel->create()) {
                $message = "Товар успешно создан!";
            } else {
                $message = "Ошибка при создании товара!";
            }
        }
        
        // Удаление товара
        if (isset($_GET['delete_id'])) {
            $this->productModel->id = $_GET['delete_id'];
            if ($this->productModel->delete()) {
                $message = "Товар успешно удален!";
            } else {
                $message = "Ошибка при удалении товара!";
            }
        }
        
        $products = $this->productModel->read();
        
        $this->view('products/index', [
            'products' => $products,
            'message' => $message
        ]);
    }
}
?>