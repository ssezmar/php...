<nav class="nav">
  <a class="nav-link" href="add_subject.php">Добавить предмет</a>
  <a class="nav-link" href="enroll.php">Записать на предмет</a>
  <a class="nav-link" href="list_by_subject.php">Список по предмету</a>
  <a class="nav-link" href="load_students.php">Загрузить списком</a>

</nav>

<?php
require_once __DIR__ . '/config/database.php';
$selected = null;
$students = [];

// загружаем предметы
$all = mysqli_query($connection, "SELECT id,name FROM subjects ORDER BY name");

if (!empty($_GET['subject_id'])) {
  $selected = (int)$_GET['subject_id'];
  $stmt = $connection->prepare(
    "SELECT s.name, s.email FROM students s
     JOIN student_subjects ss ON ss.student_id=s.id
     WHERE ss.subject_id=? ORDER BY s.name"
  );
  $stmt->bind_param('i',$selected);
  $stmt->execute();
  $res = $stmt->get_result();
  $students = $res->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><title>Студенты по предмету</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="p-4">
  <h1>Студенты, изучающие предмет</h1>
  <form class="mb-3">
    <select name="subject_id" class="form-select" onchange="this.form.submit()">
      <option value="">— Выберите предмет —</option>
      <?php while($r=mysqli_fetch_assoc($all)): ?>
        <option value="<?=$r['id']?>"
          <?= $selected===$r['id']?'selected':''?>>
          <?=htmlspecialchars($r['name'])?>
        </option>
      <?php endwhile;?>
    </select>
  </form>
  <?php if($selected): ?>
    <?php if($students): ?>
      <table class="table">
        <tr><th>Имя</th><th>Email</th></tr>
        <?php foreach($students as $st): ?>
          <tr>
            <td><?=htmlspecialchars($st['name'])?></td>
            <td><?=htmlspecialchars($st['email'])?></td>
          </tr>
        <?php endforeach;?>
      </table>
    <?php else: ?>
      <div class="alert alert-warning">Нет записанных студентов</div>
    <?php endif; ?>
  <?php endif; ?>
</body></html>
