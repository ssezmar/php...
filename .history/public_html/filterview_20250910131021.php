<?php
$file = __DIR__ . '/data.csv';

// Если файл не существует
if (!file_exists($file)) {
    echo "<p>Файл с данными пока не создан.</p>";
    exit;
}

// Инициализация переменных
$searchValue = '';
$searchBy = 'name';
$results = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchBy = $_POST['search_by'] ?? 'name';
    $searchValue = trim($_POST['search_value'] ?? '');

    if ($searchValue !== '') {
        $fp = fopen($file, 'r');

        while (($row = fgetcsv($fp)) !== false) {
            if ($searchBy === 'name' && stripos($row[1], $searchValue) !== false) {
                $results[] = $row;
            }
            if ($searchBy === 'email' && stripos($row[3], $searchValue) !== false) {
                $results[] = $row;
            }
        }

        fclose($fp);
    }
}
?>

<h2>Поиск по имени или e-mail</h2>
<form action="" method="post">
    <label for="search_by">Критерий:</label>
    <select name="search_by" id="search_by">
        <option value="name" <?= $searchBy === 'name' ? 'selected' : '' ?>>Имя</option>
        <option value="email" <?= $searchBy === 'email' ? 'selected' : '' ?>>E-mail</option>
    </select>

    <label for="search_value">Значение:</label>
    <input type="text" name="search_value" id="search_value" value="<?= htmlspecialchars($searchValue) ?>" required>

    <button type="submit">Поиск</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($searchValue === '') {
        echo "<p>Введите значение для поиска.</p>";
    } elseif (empty($results)) {
        echo "<p>Совпадений не найдено.</p>";
    } else {
        echo "<h3>Результаты поиска:</h3>";
        foreach ($results as $row) {
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
    }
}
?>
