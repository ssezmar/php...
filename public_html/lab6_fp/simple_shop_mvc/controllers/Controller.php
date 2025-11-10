<?php
// controllers/Controller.php
class Controller {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Загрузка представления
    protected function view($view, $data = []) {
        extract($data);
        require_once 'views/' . $view . '.php';
    }

    // Редирект
    protected function redirect($page) {
        header("Location: ?page=" . $page);
        exit();
    }
}
?>