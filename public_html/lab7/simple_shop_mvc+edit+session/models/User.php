<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $role;
    public $customer_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Авторизация пользователя
    public function login($username, $password) {
        $query = "SELECT id, username, email, password_hash, role, customer_id 
                  FROM " . $this->table_name . " 
                  WHERE username = ? OR email = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            }
        }

        return false;
    }

    // Получить пользователя по ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Получить всех пользователей (для админа)
    public function getAll() {
        $query = "SELECT u.*, c.name as customer_name 
                  FROM " . $this->table_name . " u
                  LEFT JOIN customers c ON u.customer_id = c.id
                  ORDER BY u.created_at DESC";
        $result = $this->conn->query($query);
        return $result;
    }
}
?>