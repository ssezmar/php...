<?php
// models/Product.php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $price;
    public $description;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Получить все товары
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);

        // ВАЖНО: Проверка на ошибку выполнения запроса
        if ($result === false) {
            echo "Ошибка SQL в Product::read(): " . $this->conn->error . "<br>";
            echo "Запрос: " . $query . "<br>";
            return false;
        }

        return $result;
    }

    // Получить товар по ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
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

    // Создать товар
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, price, description) 
                  VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("sds", $this->name, $this->price, $this->description);
        return $stmt->execute();
    }

    // Обновить товар
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=?, price=?, description=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            echo "Ошибка подготовки запроса: " . $this->conn->error;
            return false;
        }

        $stmt->bind_param("sdsi", $this->name, $this->price, $this->description, $this->id);
        return $stmt->execute();
    }

    // Удалить товар
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
}
?>