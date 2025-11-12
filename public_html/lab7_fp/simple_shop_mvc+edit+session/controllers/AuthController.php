<?php
class AuthController extends Controller {
    
    public function __construct($database, $auth) {
        parent::__construct($database, $auth);
    }
    
    public function login() {
        // Если пользователь уже авторизован, перенаправляем на главную
        if ($this->auth->isLoggedIn()) {
            $this->redirect('orders');
        }
        
        $error = '';
        
        // Обработка формы входа
        if ($_POST && isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            if ($this->auth->login($username, $password)) {
                $this->redirect('orders');
            } else {
                $error = "Неверное имя пользователя или пароль!";
            }
        }
        
        $this->view('auth/login', ['error' => $error]);
    }
    
    public function logout() {
        $this->auth->logout();
        $this->redirect('login');
    }
    
    public function access_denied() {
        $this->view('auth/access_denied');
    }
}
?>