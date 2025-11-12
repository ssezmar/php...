<?php

class AuthController extends Controller {
    private $user;

    public function __construct() {
        parent::__construct();
        $db = (new Database())->connect();
        $this->user = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->user->checkPassword($username, $password);
            if ($user) {
                Auth::login($user);
                $this->redirect('/index.php?controller=home&action=index');
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        }

        $this->view('auth/login', ['error' => $error ?? null]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $customer_name = $_POST['customer_name'] ?? '';

            if ($this->user->getUserByUsername($username)) {
                $error = 'Пользователь с таким именем уже существует';
            } else {
                if ($this->user->register($username, $password, $customer_name)) {
                    $user = $this->user->checkPassword($username, $password);
                    Auth::login($user);
                    $this->redirect('/index.php?controller=home&action=index');
                } else {
                    $error = 'Ошибка при регистрации';
                }
            }
        }

        $this->view('auth/register', ['error' => $error ?? null]);
    }

    public function logout() {
        Auth::logout();
        $this->redirect('/index.php');
    }

    public function access_denied() {
        $this->view('auth/access_denied');
    }
}
?>