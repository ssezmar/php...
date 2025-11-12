<?php

class Customer {
    private $conn;
    private $table = 'customers';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCustomerById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllCustomers() {
        $query = "SELECT * FROM {$this->table}";
        return $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
    }
}
?>