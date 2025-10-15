<?php
require_once __DIR__ . '/database.php';

// 1. Таблица предметов
$sql1 = "CREATE TABLE IF NOT EXISTS subjects (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($connection, $sql1) or die('Error creating subjects: '.mysqli_error($connection));

// 2. Связующая таблица many-to-many
$sql2 = "CREATE TABLE IF NOT EXISTS student_subjects (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT(6) UNSIGNED NOT NULL,
    subject_id INT(6) UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY us (student_id, subject_id),
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
)";
mysqli_query($connection, $sql2) or die('Error creating student_subjects: '.mysqli_error($connection));

echo "Subjects tables created.\n";
