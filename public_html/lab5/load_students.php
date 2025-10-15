<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

$msg = '';
// Жестко заданный текстовый список
$bulkData = <<<TXT
Иванов Алексей;ivanov@university.ru;+79991234567;Группа A
Петрова Мария;petrova@university.ru;+79997654321;Группа B
Сидоров Дмитрий;sidorov@university.ru;;Группа A
TXT;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $students = parseStudents($bulkData);

    // Подготовка запроса
    $sql = "INSERT IGNORE INTO students (name, email, phone, group_name) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die('Prepare error: ' . $connection->error);
    }

    // Привязка и выполнение
    $stmt->bind_param('ssss', $name, $email, $phone, $group);
    foreach ($students as $st) {
        $name  = $st['name'];
        $email = $st['email'];
        $phone = $st['phone'];
        $group = $st['group_name'];
        if (!$stmt->execute()) {
            die('Execute error: ' . $stmt->error);
        }
    }
    $stmt->close();
    $msg = 'Список студентов загружен';
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Загрузить студентов списком</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <?php if ($msg): ?>
    <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <h1>Массовая загрузка студентов</h1>
  <form method="post">
    <button class="btn btn-primary">Загрузить списком</button>
  </form>
</body>
</html>
