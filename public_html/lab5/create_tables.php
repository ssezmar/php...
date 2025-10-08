<?php
require_once 'config/database.php';

// Создание таблицы факультетов
$sql_faculties = "CREATE TABLE IF NOT EXISTS faculties (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($connection, $sql_faculties)) {
    echo "<div class='alert alert-success'>Таблица 'faculties' создана успешно!</div>";
    
    // Добавляем тестовые факультеты
    $test_faculties = [
        'Информационные технологии',
        'Экономика и финансы',
        'Юриспруденция',
        'Медицина',
        'Строительство',
        'Дизайн'
    ];
    
    foreach ($test_faculties as $faculty) {
        $sql = "INSERT IGNORE INTO faculties (name) VALUES ('$faculty')";
        mysqli_query($connection, $sql);
    }
    echo "<div class='alert alert-info'>Добавлены тестовые факультеты</div>";
    
} else {
    echo "<div class='alert alert-danger'>Ошибка создания таблицы faculties: " . mysqli_error($connection) . "</div>";
}

// Создание таблицы студентов (обновленная)
$sql_students = "CREATE TABLE IF NOT EXISTS students (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    faculty_id INT(6) UNSIGNED,
    course INT(2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id) ON DELETE SET NULL
)";

if (mysqli_query($connection, $sql_students)) {
    echo "<div class='alert alert-success'>Таблица 'students' создана успешно!</div>";
} else {
    echo "<div class='alert alert-danger'>Ошибка создания таблицы students: " . mysqli_error($connection) . "</div>";
}

mysqli_close($connection);
?>