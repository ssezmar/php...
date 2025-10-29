<?php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $customer_id;
    public $product_id;
    public $quantity;
    public $total_amount;
    public $order_date;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT 
                    o.id, 
                    o.quantity, 
                    o.total_amount, 
                    o.order_date, 
                    o.status,
                    c.first_name, 
                    c.last_name,
                    p.name as product_name,
                    p.price as product_price
                  FROM " . $this->table_name . " o
                  LEFT JOIN customers c ON o.customer_id = c.id
                  LEFT JOIN products p ON o.product_id = p.id
                  ORDER BY o.order_date DESC";
        
        $result = $this->conn->query($query);
        return $result;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET customer_id=?, product_id=?, quantity=?, total_amount=?, status=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $this->customer_id = $this->conn->real_escape_string($this->customer_id);
        $this->product_id = $this->conn->real_escape_string($this->product_id);
        $this->quantity = $this->conn->real_escape_string($this->quantity);
        $this->total_amount = $this->conn->real_escape_string($this->total_amount);
        $this->status = $this->conn->real_escape_string($this->status);

        $stmt->bind_param("iiids", 
            $this->customer_id, 
            $this->product_id, 
            $this->quantity, 
            $this->total_amount, 
            $this->status
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET customer_id=?, product_id=?, quantity=?, total_amount=?, status=?
                  WHERE id=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iiidsi", 
            $this->customer_id, 
            $this->product_id, 
            $this->quantity, 
            $this->total_amount, 
            $this->status,
            $this->id
        );

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
        return false;
    }

    public function getCustomers() {
        $query = "SELECT id, first_name, last_name FROM customers";
        $result = $this->conn->query($query);
        return $result;
    }

    public function getProducts() {
        $query = "SELECT id, name, price FROM products";
        $result = $this->conn->query($query);
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function calculateTotal($product_id, $quantity) {
        $query = "SELECT price FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if ($product) {
            return $product['price'] * $quantity;
        }
        
        return 0;
    }
}
?>