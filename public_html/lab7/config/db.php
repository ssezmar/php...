<?php

class Database {
    private $host = 'mysql';      // ← ЗАМЕНИ 'localhost' на имя сервиса из docker-compose
    private $db_name = 'simple_shop';
    private $user = 'root';
    private $password = 'rootpassword';   // ← ЗАМЕНИ на свой пароль из docker-compose
    private $conn;

    public function connect() {
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );

        if ($this->conn->connect_error) {
            die('Connection Error: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
        return $this->conn;
    }
}
?>
