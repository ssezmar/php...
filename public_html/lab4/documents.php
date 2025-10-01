<?php
include "includes/header.php";
include "includes/menu.php";
require_once 'FileUploader.php'; // подключаем классы

$uploader = new DocumentUploader('uploads/documents/', 'files_data.json');
$maxFileSize = human_filesize($uploader->maxSize ?? 20971520); // 20МБ

// Сообщения для пользователя
$msg = null;
$msgType = 'success';

// Загрузка файла
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    try {
        $uploader->uploadFile($_FILES['file'], $_POST['title']);
        $msg = "Файл успешно загружен.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

// Удаление файла
if (isset($_GET['delete'])) {
    try {
        $uploader->deleteFile($_GET['delete']);
        $msg = "Файл удалён.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

$docs = array_filter($uploader->getAllFiles(), function($f) {
    return $f['class_type'] === 'DocumentUploader';
});

usort($docs, function($a, $b) {
    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
});
// Пагинация
$perPage = 5;
$total = count($docs);
$totalPages = ($total === 0) ? 1 : (int)ceil($total / $perPage);
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$pageDocs = array_slice($docs, $offset, $perPage);

// Функция для красоты размера файла
function human_filesize($bytes, $dec = 2) {
    $sizes = ['B','KB','MB','GB'];
    if ($bytes == 0) return '0 B';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
}

// Вспомогательная функция для URL
function page_url($p) {
    $qs = $_GET;
    $qs['page'] = $p;
    $script = basename($_SERVER['PHP_SELF']);
    return $script . '?' . http_build_query($qs);
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <title>Документы</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h1 class="mb-4">Документы</h1>

  <?php if ($msg): ?>
    <div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="form-inline">
        <div class="form-group mb-2 mr-2">
          <input type="file" class="form-control-file" name="file" required>
        </div>
        <div class="form-group mb-2 mr-2">
          <input type="text" class="form-control" name="title" placeholder="Комментарий" required>
        </div>
        <button type="submit" name="upload" class="btn btn-primary mb-2">Загрузить</button>
      </form>
      <small class="form-text text-muted">
        Разрешённые форматы: <?php echo implode(', ', $uploader->allowedTypes ?? []); ?>.
        Макс. размер: <?php echo $maxFileSize; ?>.
      </small>
    </div>
  </div>

  <?php if (empty($docs)): ?>
    <p class="text-muted">Нет загруженных документов.</p>
  <?php else: ?>
    <div class="row">
      <?php foreach ($pageDocs as $file): ?>
        <?php echo $uploader->generateCard($file); ?>
      <?php endforeach; ?>
    </div>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
      <nav aria-label="Documents pagination">
        <ul class="pagination">
          <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo page_url(max(1, $page-1)); ?>">&laquo;</a>
          </li>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
              <a class="page-link" href="<?php echo page_url($i); ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?php echo page_url(min($totalPages, $page+1)); ?>">&raquo;</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>