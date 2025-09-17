<?php
// имя файла с данными
$file = __DIR__ . '/data.txt';

// проверяем, существует ли файл
if (!file_exists($file)) {
    echo "<p>Файл с данными пока не создан.</p>";
    exit;
}

// читаем все строки
$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

echo "<h2>Сохранённые данные:</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>№</th><th>Данные</th></tr>";

foreach ($lines as $i => $line) {
    // экранируем вывод, чтобы не было XSS
    $safeLine = htmlspecialchars($line, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    echo "<tr><td>".($i+1)."</td><td>$safeLine</td></tr>";
}

echo "</table>";
?>
