<?php
// models/Order.php
class Order {
    private $conn;
    private $table_name = "orders";

    public $id;
    public $customer_id;
    public $product_id;
    public $quantity;
    public $total;
    public $order_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Получить все заказы с информацией о покупателях и товарах
    public function read() {
        $query = "SELECT o.*, c.name as customer_name, p.name as product_name, p.price
                  FROM " . $this->table_name . " o
                  LEFT JOIN customers c ON o.customer_id = c.id
                  LEFT JOIN products p ON o.product_id = p.id
                  ORDER BY o.order_date DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    // Получить заказ по ID
    public function getById($id) {
        $query = "SELECT o.*, c.name as customer_name, p.name as product_name 
                  FROM " . $this->table_name . " o
                  LEFT JOIN customers c ON o.customer_id = c.id
                  LEFT JOIN products p ON o.product_id = p.id
                  WHERE o.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Создать заказ
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (customer_id, product_id, quantity, total) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiid", $this->customer_id, $this->product_id, $this->quantity, $this->total);
        return $stmt->execute();
    }

    // Обновить заказ
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET customer_id=?, product_id=?, quantity=?, total=? 
                  WHERE id=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiidi", $this->customer_id, $this->product_id, $this->quantity, $this->total, $this->id);
        return $stmt->execute();
    }

    // Удалить заказ
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>