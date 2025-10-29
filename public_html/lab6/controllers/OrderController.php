<?php
class OrderController extends Controller {
    private $orderModel;
    
    public function __construct($database) {
        parent::__construct($database);
        $this->orderModel = new Order($this->db);
    }
    
    public function index() {
        $message = '';
        
        // Создание заказа
        if ($_POST && isset($_POST['create'])) {
            $this->orderModel->customer_id = $_POST['customer_id'];
            $this->orderModel->product_id = $_POST['product_id'];
            $this->orderModel->quantity = $_POST['quantity'];
            
            if ($this->orderModel->create()) {
                $message = "Заказ успешно создан!";
            } else {
                $message = "Ошибка при создании заказа!";
            }
        }
        
        // Удаление заказа
        if (isset($_GET['delete_id'])) {
            $this->orderModel->id = $_GET['delete_id'];
            if ($this->orderModel->delete()) {
                $message = "Заказ успешно удален!";
            } else {
                $message = "Ошибка при удалении заказа!";
            }
        }
        
        $orders = $this->orderModel->read();
        $customers = $this->orderModel->getCustomers();
        $products = $this->orderModel->getProducts();
        
        $this->view('orders/index', [
            'orders' => $orders,
            'customers' => $customers,
            'products' => $products,
            'message' => $message
        ]);
    }
}
?>