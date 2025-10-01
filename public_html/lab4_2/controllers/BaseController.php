<?php
require_once '../models/FileModel.php';

class BaseController {
    protected $model;
    protected $viewPath = '../views/';
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    protected function render($view, $data = []) {
        extract($data);
        require $this->viewPath . $view . '.php';
    }
    
    protected function handleError($message) {
        http_response_code(400);
        $this->render('error', ['message' => $message]);
        exit;
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}