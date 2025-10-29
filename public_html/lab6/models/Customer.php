<?php
// models/Customer.php
class Customer {
    private $conn;
    private $table_name = "customers";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Получить всех покупателей
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    // Получить покупателя по ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Создать покупателя
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, phone) 
                  VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $this->name, $this->email, $this->phone);
        return $stmt->execute();
    }

    // Обновить покупателя
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=?, email=?, phone=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $this->name, $this->email, $this->phone, $this->id);
        return $stmt->execute();
    }

    // Удалить покупателя
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>