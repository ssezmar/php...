<?php
class Customer {
    private $conn;
    private $table_name = "customers";

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $address;
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
                  SET first_name=?, last_name=?, email=?, phone=?, address=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $this->first_name = $this->conn->real_escape_string($this->first_name);
        $this->last_name = $this->conn->real_escape_string($this->last_name);
        $this->email = $this->conn->real_escape_string($this->email);
        $this->phone = $this->conn->real_escape_string($this->phone);
        $this->address = $this->conn->real_escape_string($this->address);

        $stmt->bind_param("sssss", 
            $this->first_name, 
            $this->last_name, 
            $this->email, 
            $this->phone, 
            $this->address
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
                  SET first_name=?, last_name=?, email=?, phone=?, address=?
                  WHERE id=?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sssssi", 
            $this->first_name, 
            $this->last_name, 
            $this->email, 
            $this->phone, 
            $this->address,
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