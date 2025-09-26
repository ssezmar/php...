<?php
include "includes/header.php";
include "includes/menu.php";
$dir = __DIR__ . '/documents';        // физический путь для хранения файлов
$webDir = 'documents';                // путь для URL <a href="documents/..."
$metaFile = $dir . '/meta.json';      // файл с метаданными
$maxFileSize = 10 * 1024 * 1024;      // 10 MB
$allowedExt = ['pdf','doc','docx','xls','xlsx'];
$allowedMime = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

$perPage = 1;

if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

$meta = [];
if (file_exists($metaFile)) {
    $json = file_get_contents($metaFile);
    $meta = json_decode($json, true) ?: [];
}

function human_filesize($bytes, $dec = 2) {
    $sizes = ['B','KB','MB'];
    if ($bytes == 0) return '0 B';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
}

$msg = null;
$msgType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    if (!isset($_FILES['file'])) {
        $msg = "Файл не выбран.";
        $msgType = 'danger';
    } else {
        $f = $_FILES['file'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $msg = "Ошибка загрузки файла (код: {$f['error']}).";
            $msgType = 'danger';
        } elseif ($f['size'] > $maxFileSize) {
            $msg = "Файл превышает допустимый размер " . human_filesize($maxFileSize) . ".";
            $msgType = 'danger';
        } else {
            $origName = $f['name'];
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt)) {
                $msg = "Недопустимое расширение. Разрешены: " . implode(', ', $allowedExt) . ".";
                $msgType = 'danger';
            } else {
                // Проверка MIME через finfo
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($f['tmp_name']);
                if (!in_array($mime, $allowedMime)) {
                    $msg = "Недопустимый тип файла (MIME: {$mime}).";
                    $msgType = 'danger';
                } else {
                    // Генерируем уникальное имя для хранения
                    $storedName = time() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
                    $dest = $dir . '/' . $storedName;
                    if (!move_uploaded_file($f['tmp_name'], $dest)) {
                        $msg = "Не удалось сохранить файл на сервере.";
                        $msgType = 'danger';
                    } else {
                        $title = trim($_POST['title'] ?? '');
                        if ($title === '') $title = $origName;
                        $entry = [
                            'title' => $title,
                            'original_name' => $origName,
                            'stored_name' => $storedName,
                            'size' => filesize($dest),
                            'mime' => $mime,
                            'time' => time()
                        ];
                        $meta[$storedName] = $entry;
                        // сохраняем JSON (блокировка)
                        file_put_contents($metaFile, json_encode($meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
                        $msg = "Файл успешно загружен.";
                        $msgType = 'success';
                    }
                }
            }
        }
    }
}

$docs = array_values($meta);
usort($docs, function($a, $b){ return $b['time'] - $a['time']; });

// Пагинация: вычислим текущую страницу и страницы в цел
$total = count($docs);
$totalPages = ($total === 0) ? 1 : (int)ceil($total / $perPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;
$pageDocs = array_slice($docs, $offset, $perPage);

// Вспомогательная функция для генерации URL с сохранением других GET-параметров
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
  <title>Документы — AniPat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h1 class="mb-4">Документы</h1>

  <?php if ($msg): ?>
    <div class="alert alert-<?php echo ($msgType); ?>"><?php echo ($msg); ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <form action="documents.php" method="post" enctype="multipart/form-data" class="form-inline">
        <div class="form-group mb-2 mr-2">
          <label class="sr-only" for="file">Файл</label>
          <input type="file" class="form-control-file" id="file" name="file" required>
        </div>
        <div class="form-group mb-2 mr-2">
          <label class="sr-only" for="title">Краткое название</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Краткое название" required>
        </div>
        <button type="submit" name="upload" class="btn btn-primary mb-2">Загрузить</button>
      </form>
      <small class="form-text text-muted">Разрешённые форматы: .doc, .docx, .xls, .xlsx, .pdf. Макс. размер: <?php echo human_filesize($maxFileSize); ?>.</small>
    </div>
  </div>

  <?php if (empty($docs)): ?>
    <p class="text-muted">Пока нет загруженных документов.</p>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-sm table-bordered">
        <thead class="thead-light">
          <tr>
            <th>Название</th>
            <th>Оригинальное имя</th>
            <th>Размер</th>
            <th>Дата загрузки</th>
            <th>Действие</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pageDocs as $d): ?>
            <tr>
              <td><?php echo ($d['title']); ?></td>
              <td><?php echo ($d['original_name']); ?></td>
              <td><?php echo human_filesize($d['size']); ?></td>
              <td><?php echo date("d.m.Y H:i:s", $d['time']); ?></td>
              <td>
                <a href="<?php echo ($webDir . '/' . $d['stored_name']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Просмотреть / Скачать</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
      <nav aria-label="Documents pagination">
        <ul class="pagination">
          <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo (page_url(max(1, $page-1))); ?>" aria-label="Previous">
              &laquo;
            </a>
          </li>

          <?php
          $start = max(1, $page - 2);
          $end = min($totalPages, $page + 2);
          if ($start > 1) {
              echo '<li class="page-item"><a class="page-link" href="'.(page_url(1)).'">1</a></li>';
              if ($start > 2) {
                  echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
              }
          }
          for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
              <a class="page-link" href="<?php echo (page_url($i)); ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor;
          if ($end < $totalPages) {
              if ($end < $totalPages - 1) {
                  echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
              }
              echo '<li class="page-item"><a class="page-link" href="'.(page_url($totalPages)).'">'. $totalPages .'</a></li>';
          }
          ?>

          <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo (page_url(min($totalPages, $page+1))); ?>" aria-label="Next">
              &raquo;
            </a>
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