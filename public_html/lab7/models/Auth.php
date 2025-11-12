<?php

class Auth {
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        session_regenerate_id(true);
    }

    public static function logout() {
        session_destroy();
        session_start();
        $_SESSION = array();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin() {
        return self::isLoggedIn() && $_SESSION['role'] === 'admin';
    }

    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function getCurrentUsername() {
        return $_SESSION['username'] ?? null;
    }

    public static function getCurrentRole() {
        return $_SESSION['role'] ?? 'guest';
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /index.php?controller=auth&action=login');
            exit;
        }
    }

    public static function requireAdmin() {
        if (!self::isAdmin()) {
            header('Location: /index.php?controller=auth&action=access_denied');
            exit;
        }
    }
}
?>