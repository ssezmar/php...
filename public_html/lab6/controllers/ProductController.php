<?php
// controllers/ProductController.php
class ProductController extends Controller {
    private $productModel;
    private $reviewModel;

    public function __construct($database) {
        parent::__construct($database);
        $this->productModel = new Product($this->db);
        $this->reviewModel = new Review($this->db);
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

        // Редактирование товара
        if ($_POST && isset($_POST['update'])) {
            $this->productModel->id = $_POST['id'];
            $this->productModel->name = $_POST['name'];
            $this->productModel->price = $_POST['price'];
            $this->productModel->description = $_POST['description'];

            if ($this->productModel->update()) {
                $message = "Товар успешно обновлен!";
                $this->redirect('products');
            } else {
                $message = "Ошибка при обновлении товара!";
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

    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $product = $this->productModel->getById($id);

        if (!$product) {
            $this->redirect('products');
        }

        $this->view('products/edit', [
            'product' => $product
        ]);
    }

    // ✅ ИСПРАВЛЕНО: переименован метод на viewProduct()
    public function viewProduct() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $product = $this->productModel->getById($id);

        if (!$product) {
            $this->redirect('products');
        }

        $message = '';

        // Добавление отзыва
        if ($_POST && isset($_POST['add_review'])) {
            $this->reviewModel->product_id = $id;
            $this->reviewModel->customer_id = $_POST['customer_id'];
            $this->reviewModel->rating = $_POST['rating'];
            $this->reviewModel->comment = $_POST['comment'];

            if ($this->reviewModel->create()) {
                $message = "Отзыв успешно добавлен!";
            } else {
                $message = "Ошибка при добавлении отзыва!";
            }
        }

        // Удаление отзыва
        if (isset($_GET['delete_review'])) {
            $this->reviewModel->id = $_GET['delete_review'];
            if ($this->reviewModel->delete()) {
                $message = "Отзыв удален!";
            }
        }

        $reviews = $this->reviewModel->getByProductId($id);
        $rating_info = $this->reviewModel->getAverageRating($id);

        // Получаем список покупателей для формы отзыва
        $customerModel = new Customer($this->db);
        $customers = $customerModel->read();

        $this->view('products/view', [
            'product' => $product,
            'reviews' => $reviews,
            'rating_info' => $rating_info,
            'customers' => $customers,
            'message' => $message
        ]);
    }
}
?>