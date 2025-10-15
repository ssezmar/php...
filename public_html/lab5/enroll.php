<nav class="nav">
  <a class="nav-link" href="add_subject.php">Добавить предмет</a>
  <a class="nav-link" href="enroll.php">Записать на предмет</a>
  <a class="nav-link" href="list_by_subject.php">Список по предмету</a>
  <a class="nav-link" href="load_students.php">Загрузить списком</a>

</nav>

<?php
require_once __DIR__ . '/config/database.php';
$msg='';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $student = (int)$_POST['student_id'];
  $subject = (int)$_POST['subject_id'];
  $stmt = $connection->prepare(
    "INSERT IGNORE INTO student_subjects (student_id, subject_id) VALUES (?,?)"
  );
  $stmt->bind_param('ii',$student,$subject);
  if ($stmt->execute()) $msg='Студент записан';
  $stmt->close();
}

// Загрузка списков
$students = mysqli_query($connection, "SELECT id,name FROM students ORDER BY name");
$subjects = mysqli_query($connection, "SELECT id,name FROM subjects ORDER BY name");
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><title>Запись на предмет</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4">
  <?php if($msg): ?><div class="alert alert-success"><?=htmlspecialchars($msg)?></div><?php endif; ?>
  <h1>Записать студента на предмет</h1>
  <form method="post">
    <div class="mb-3">
      <label>Студент</label>
      <select name="student_id" class="form-select" required>
        <option value="">— Выберите —</option>
        <?php while($s=mysqli_fetch_assoc($students)): ?>
          <option value="<?=$s['id']?>"><?=htmlspecialchars($s['name'])?></option>
        <?php endwhile;?>
      </select>
    </div>
    <div class="mb-3">
      <label>Предмет</label>
      <select name="subject_id" class="form-select" required>
        <option value="">— Выберите —</option>
        <?php while($su=mysqli_fetch_assoc($subjects)): ?>
          <option value="<?=$su['id']?>"><?=htmlspecialchars($su['name'])?></option>
        <?php endwhile;?>
      </select>
    </div>
    <button class="btn btn-primary">Записать</button>
  </form>
</body></html>
