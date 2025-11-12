<?php

class Order {
    private $conn;
    private $table = 'orders';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllOrders($auth = null) {
        // Администратор видит все заказы
        $query = "SELECT o.*, c.name as customer_name 
                 FROM {$this->table} o 
                 LEFT JOIN customers c ON o.customer_id = c.id 
                 ORDER BY o.id DESC";
        
        $result = $this->conn->query($query);
        if (!$result) {
            die('Query Error: ' . $this->conn->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderById($id, $auth = null) {
        $query = "SELECT o.*, c.name as customer_name 
                 FROM {$this->table} o 
                 LEFT JOIN customers c ON o.customer_id = c.id 
                 WHERE o.id = ?";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die('Prepare Error: ' . $this->conn->error);
        }
        
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function createOrder($customer_id, $product_id, $quantity, $total_price) {
        $query = "INSERT INTO {$this->table} (customer_id, product_id, quantity, total_price) 
                 VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            die('Prepare Error: ' . $this->conn->error);
        }
        
        $stmt->bind_param("iidi", $customer_id, $product_id, $quantity, $total_price);
        return $stmt->execute();
    }

    public function updateOrder($id, $product_id, $quantity, $total_price, $auth = null) {
        $query = "UPDATE {$this->table} SET product_id = ?, quantity = ?, total_price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            die('Prepare Error: ' . $this->conn->error);
        }
        
        $stmt->bind_param("iidi", $product_id, $quantity, $total_price, $id);
        return $stmt->execute();
    }

    public function deleteOrder($id, $auth = null) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            die('Prepare Error: ' . $this->conn->error);
        }
        
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>