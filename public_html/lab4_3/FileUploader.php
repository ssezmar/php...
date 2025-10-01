<?php
// Этот файл теперь использует новые OOP-классы для обработки загрузки файлов
// Фактическая логика перемещена в FileHandler, PhotoHandler и DocumentHandler

// Автозагрузка классов
spl_autoload_register(function ($class_name) {
    $base_dir = __DIR__ . '/';
    $file = $base_dir . $class_name . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Пример использования:
// $uploader = new PhotoHandler();
// $uploader = new DocumentHandler();