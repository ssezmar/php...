<?php

class Product {
    private $conn;
    private $table = 'products';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllProducts() {
        $query = "SELECT * FROM {$this->table}";
        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createProduct($name, $description, $price) {
        $query = "INSERT INTO {$this->table} (name, description, price) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssd", $name, $description, $price);
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $description, $price) {
        $query = "UPDATE {$this->table} SET name = ?, description = ?, price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdi", $name, $description, $price, $id);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>