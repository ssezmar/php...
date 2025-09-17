<?php
$file = __DIR__ . '/data.csv';

if (!file_exists($file)) {
    echo "<p>Файл с данными пока не создан.</p>";
    exit;
}

$fp = fopen($file, 'r');

echo "<h2>Сохранённые данные:</h2>";

while (($row = fgetcsv($fp)) !== false) {
    echo "<div style='margin-bottom:15px; padding:10px; border:1px solid #ccc;'>";
    echo "<strong>Дата:</strong> " . htmlspecialchars($row[0]) . "<br>";
    echo "<strong>Имя:</strong> " . htmlspecialchars($row[1]) . "<br>";
    echo "<strong>Возраст:</strong> " . htmlspecialchars($row[2]) . "<br>";
    echo "<strong>E-mail:</strong> " . htmlspecialchars($row[3]) . "<br>";
    echo "<strong>Телефон:</strong> " . htmlspecialchars($row[4]) . "<br>";
    echo "<strong>Увлечения:</strong> " . htmlspecialchars($row[5]) . "<br>";
    echo "<strong>Курсы:</strong> " . htmlspecialchars($row[6]) . "<br>";
    echo "<strong>Книги:</strong> " . htmlspecialchars($row[7]) . "<br>";
    echo "<strong>Видео:</strong> " . htmlspecialchars($row[8]) . "<br>";
    echo "<strong>Предпочтение:</strong> " . htmlspecialchars($row[9]) . "<br>";
    echo "</div>";
}

fclose($fp);
?>
