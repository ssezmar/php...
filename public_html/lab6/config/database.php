<?php
// config/database.php
define('DB_HOST', 'mysql');          // потому что контейнер mysql называется именно так!
define('DB_USER', 'otherUser');      // имя пользователя MySQL (как в docker-compose)
define('DB_PASS', 'password');       // пароль MySQL (как в docker-compose)
define('DB_NAME', 'dbtest');         // имя БД (как в docker-compose)
define('DB_CHARSET', 'utf8');
?>