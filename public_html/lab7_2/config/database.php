<?php
// config.php

// Для Docker контейнеров - используйте имя сервиса
define('DB_HOST', 'mysql');  // ← Имя сервиса из docker-compose.yml
define('DB_NAME', 'my_shop_edit_session');
define('DB_USER', 'root');
define('DB_PASS', 'rootpassword');

// Или используйте переменные окружения
// define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
// define('DB_NAME', getenv('DB_NAME') ?: 'my_shop_edit_session');
// define('DB_USER', getenv('DB_USER') ?: 'root');
// define('DB_PASS', getenv('DB_PASS') ?: 'rootpassword');
?>
