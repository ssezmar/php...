<?php
// models/Review.php
class Review {
    private $conn;
    private $table_name = "reviews";

    public $id;
    public $product_id;
    public $customer_id;
    public $rating;
    public $comment;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Получить все отзывы для конкретного товара
    public function getByProductId($product_id) {
        $query = "SELECT r.*, c.name as customer_name 
                  FROM " . $this->table_name . " r
                  LEFT JOIN customers c ON r.customer_id = c.id
                  WHERE r.product_id = ?
                  ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }

    // Получить все отзывы
    public function read() {
        $query = "SELECT r.*, c.name as customer_name, p.name as product_name
                  FROM " . $this->table_name . " r
                  LEFT JOIN customers c ON r.customer_id = c.id
                  LEFT JOIN products p ON r.product_id = p.id
                  ORDER BY r.created_at DESC";
        $result = $this->conn->query($query);

        if ($result === false) {
            echo "Ошибка SQL в Review::read(): " . $this->conn->error . "<br>";
            return false;
        }

        return $result;
    }

    // Получить отзыв по ID
    public function getById($id) {
        $query = "SELECT r.*, c.name as customer_name, p.name as product_name
                  FROM " . $this->table_name . " r
                  LEFT JOIN customers c ON r.customer_id = c.id
                  LEFT JOIN products p ON r.product_id = p.id
                  WHERE r.id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Создать отзыв
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (product_id, customer_id, rating, comment) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("iiis", $this->product_id, $this->customer_id, $this->rating, $this->comment);
        return $stmt->execute();
    }

    // Обновить отзыв
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET product_id=?, customer_id=?, rating=?, comment=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("iiisi", $this->product_id, $this->customer_id, $this->rating, $this->comment, $this->id);
        return $stmt->execute();
    }

    // Удалить отзыв
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }

    // Получить средний рейтинг товара
    public function getAverageRating($product_id) {
        $query = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
                  FROM " . $this->table_name . "
                  WHERE product_id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            return ['avg_rating' => 0, 'total_reviews' => 0];
        }

        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>