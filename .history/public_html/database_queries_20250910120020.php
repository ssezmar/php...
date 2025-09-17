<?php

require 'db_config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Вставка пользователя
    $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
    $stmt->execute(['name' => 'Иван Иванов', 'email' => 'ivan@example.com']);

    // Получение всех пользователей
    $stmt = $pdo->query('SELECT * FROM users');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>