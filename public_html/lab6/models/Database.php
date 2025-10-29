<?php
// models/Database.php
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli(
                $this->host,
                $this->username,
                $this->password,
                $this->db_name
            );
            $this->conn->set_charset($this->charset);

            if ($this->conn->connect_error) {
                die("Ошибка подключения: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Ошибка подключения к БД: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>