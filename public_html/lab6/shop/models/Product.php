<?php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $category;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=?, description=?, price=?, category=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $this->name = $this->conn->real_escape_string($this->name);
        $this->description = $this->conn->real_escape_string($this->description);
        $this->price = $this->conn->real_escape_string($this->price);
        $this->category = $this->conn->real_escape_string($this->category);

        $stmt->bind_param("ssds", 
            $this->name, 
            $this->description, 
            $this->price, 
            $this->category
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
                  SET name=?, description=?, price=?, category=?
                  WHERE id=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $this->name = $this->conn->real_escape_string($this->name);
        $this->description = $this->conn->real_escape_string($this->description);
        $this->price = $this->conn->real_escape_string($this->price);
        $this->category = $this->conn->real_escape_string($this->category);
        $this->id = $this->conn->real_escape_string($this->id);

        $stmt->bind_param("ssdsi", 
            $this->name, 
            $this->description, 
            $this->price, 
            $this->category,
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

        $this->id = $this->conn->real_escape_string($this->id);
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        
        $stmt->close();
        return false;
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>