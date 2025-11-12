<?php

class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function checkPassword($username, $password) {
        $user = $this->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }

    public function createUser($username, $password, $role = 'user') {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO {$this->table} (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        return $stmt->execute();
    }

    public function register($username, $password, $customer_name) {
        // Создаем пользователя
        if (!$this->createUser($username, $password, 'user')) {
            return false;
        }

        // Создаем запись в таблице customers
        // Больше не нужен user_id, просто создаем покупателя
        $query = "INSERT INTO customers (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            die('Prepare failed: ' . $this->conn->error);
        }
        
        $stmt->bind_param("s", $customer_name);
        return $stmt->execute();
    }

    public function updateCustomer($user_id, $name, $email = '', $phone = '') {
        $query = "UPDATE customers SET name = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $phone, $user_id);
        return $stmt->execute();
    }

    public function getCustomerByUserId($user_id) {
        $query = "SELECT * FROM customers WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>