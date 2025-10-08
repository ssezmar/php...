<?php
// config/database.php

// Читаем переменные окружения
$dbHost     = getenv('MYSQL_HOST')     ?: 'mysql';
$dbPort     = getenv('MYSQL_PORT')     ?: '3306';
$dbName     = getenv('DB_NAME')        ?: 'dbtest';
$dbUser     = getenv('DB_USERNAME')    ?: 'otherUser';
$dbPassword = getenv('DB_PASSWORD')    ?: 'password';

// Устанавливаем соединение
$connection = mysqli_init();
mysqli_options($connection, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
$connected = mysqli_real_connect(
    $connection,
    $dbHost,
    $dbUser,
    $dbPassword,
    $dbName,
    (int)$dbPort
);

if (!$connected) {
    die('Ошибка подключения к БД: ' . mysqli_connect_error());
}

// Кодировка UTF-8
mysqli_set_charset($connection, 'utf8mb4');
