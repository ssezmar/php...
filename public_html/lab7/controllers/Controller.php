<?php

class Controller {
    protected $auth;

    public function __construct() {
        $this->auth = new class {
            public function isLoggedIn() { return Auth::isLoggedIn(); }
            public function isAdmin() { return Auth::isAdmin(); }
            public function getCurrentUserId() { return Auth::getCurrentUserId(); }
            public function getCurrentUsername() { return Auth::getCurrentUsername(); }
            public function getCurrentRole() { return Auth::getCurrentRole(); }
        };
    }

    protected function view($view, $data = []) {
        extract($data);
        $auth = $this->auth;
        include "./views/{$view}.php";
    }

    protected function redirect($path) {
        header("Location: {$path}");
        exit;
    }
}
?>