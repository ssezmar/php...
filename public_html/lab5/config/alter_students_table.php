<?php
// lab5/config/alter_students_table.php

require_once __DIR__ . '/database.php';

// Проверяем, есть ли уже колонка group_name
$result = mysqli_query($connection, "
    SELECT COLUMN_NAME
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'students'
      AND COLUMN_NAME = 'group_name'
");

if (mysqli_num_rows($result) === 0) {
    // Колонки нет — добавляем
    $sql = "
        ALTER TABLE students
        ADD COLUMN group_name VARCHAR(50) DEFAULT NULL
    ";
    if (mysqli_query($connection, $sql)) {
        echo "Поле group_name успешно добавлено в таблицу students.\n";
    } else {
        echo "Ошибка при добавлении поля group_name: " . mysqli_error($connection) . "\n";
    }
} else {
    echo "Поле group_name уже существует в таблице students.\n";
}

mysqli_close($connection);
