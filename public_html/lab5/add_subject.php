<nav class="nav">
  <a class="nav-link" href="add_subject.php">Добавить предмет</a>
  <a class="nav-link" href="enroll.php">Записать на предмет</a>
  <a class="nav-link" href="list_by_subject.php">Список по предмету</a>
  <a class="nav-link" href="load_students.php">Загрузить списком</a>

</nav>

<?php
require_once __DIR__ . '/config/database.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['name'])) {
    $stmt = $connection->prepare("INSERT IGNORE INTO subjects (name) VALUES (?)");
    $stmt->bind_param('s', $_POST['name']);
    if ($stmt->execute()) {
        $msg = 'Предмет добавлен';
    }
    $stmt->close();
}

// Получаем все предметы
$res = mysqli_query($connection, "SELECT * FROM subjects ORDER BY name");
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Добавить предмет</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <?php if($msg): ?>
    <div class="alert alert-info"><?=htmlspecialchars($msg)?></div>
  <?php endif; ?>
  <h1>Добавить предмет</h1>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Название</label>
      <input name="name" class="form-control" required>
    </div>
    <button class="btn btn-primary">Сохранить</button>
  </form>
  <hr>
  <h2>Список предметов</h2>
  <ul>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
      <li><?=htmlspecialchars($row['name'])?></li>
    <?php endwhile; ?>
  </ul>
</body>
</html>
