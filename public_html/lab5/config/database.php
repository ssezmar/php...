<?php
// Настройки подключения к базе данных
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'students_db');

// Создаем подключение
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Проверяем подключение
if (!$connection) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Устанавливаем кодировку
mysqli_set_charset($connection, "utf8");
?>