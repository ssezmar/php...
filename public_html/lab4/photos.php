<?php
include "includes/header.php";
include "includes/menu.php";
require_once 'FileUploader.php';

// Создаём объект для работы с фото
$uploader = new PhotoUploader('uploads/photos/', 'files_data.json');

// Сообщения
$msg = null;
$msgType = 'success';

// Загрузка файла
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doupload'])) {
    try {
        // В uploadFile второй аргумент — комментарий к фото
        $uploader->uploadFile($_FILES['file'], $_POST['filename']);
        $msg = "Фото успешно загружено.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

// Удаление файла
if (isset($_GET['delete'])) {
    try {
        $uploader->deleteFile($_GET['delete']);
        $msg = "Фото удалено.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

$photos = array_filter($uploader->getAllFiles(), function($f) {
    return $f['class_type'] === 'PhotoUploader';
});

// Сортируем по дате загрузки
usort($photos, function($a, $b) {
    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
});

// Пагинация
$perPage = 3;
$total = count($photos);
$totalPages = max(1, ceil($total / $perPage));
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$pagePhotos = array_slice($photos, $offset, $perPage);

// Для показа макс. размера в удобном виде
function human_filesize($bytes, $dec = 2) {
    $sizes = ['B','KB','MB'];
    if ($bytes == 0) return '0 B';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
}

// Генерация URL для пагинации
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
  <title>Фотоальбом</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
  <h1 class="mb-3">Фотоальбом</h1>

  <?php if ($msg): ?>
    <div class="alert alert-<?php echo $msgType; ?>">
      <?php echo htmlspecialchars($msg); ?>
    </div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <form method="post" enctype="multipart/form-data" class="form-inline">
        <div class="form-group mb-2 mr-2">
          <input type="file" class="form-control-file" name="file" required>
        </div>
        <div class="form-group mb-2 mr-2" style="min-width:300px;">
          <input type="text" class="form-control" name="filename" placeholder="Комментарий к фото" required>
        </div>
        <button type="submit" name="doupload" class="btn btn-primary mb-2">Закачать фото</button>
      </form>
      <small class="form-text text-muted">
        Разрешённые форматы: <?php echo implode(', ', $uploader->allowedTypes ?? []); ?>.
        Макс. размер файла: <?php echo human_filesize($uploader->maxSize ?? 10485760); ?>.
      </small>
    </div>
  </div>

  <div class="row">
    <?php if (empty($photos)): ?>
      <div class="col-12"><p class="text-muted">Пока нет загруженных фотографий.</p></div>
    <?php endif; ?>

    <?php foreach ($pagePhotos as $file): ?>
      <?php echo $uploader->generateCard($file); ?>
    <?php endforeach; ?>
  </div>

  <?php if ($totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo htmlspecialchars(page_url(max(1, $page-1))); ?>">&laquo;</a>
        </li>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
            <a class="page-link" href="<?php echo htmlspecialchars(page_url($i)); ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo htmlspecialchars(page_url(min($totalPages, $page+1))); ?>">&raquo;</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>