<?php
include "includes/header.php";
include "includes/menu.php";

$imgdir = __DIR__ . '/images';
$imgWebDir = 'images';
$metaFile = $imgdir . '/comments.json';
$maxWidth = 2000;
$maxHeight = 2000;
$maxFileSize = 4 * 1024 * 1024;
$allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif'];

$perPage = 3;

if (!is_dir($imgdir)) {
    mkdir($imgdir, 0755, true);
}

$meta = [];
if (file_exists($metaFile)) {
    $txt = file_get_contents($metaFile);
    $meta = json_decode($txt, true) ?: [];
}

function human_filesize($bytes, $dec = 2) {
    if ($bytes <= 0) return '0 B';
    $sizes = ['B','KB','MB'];
    $factor = floor((strlen((string)$bytes) - 1) / 3);
    return sprintf("%.{$dec}f", $bytes / pow(1024, $factor)) . ' ' . $sizes[$factor];
}

$message = null;
$messageType = 'success';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doupload'])) {
    if (!isset($_FILES['file'])) {
        $message = 'Файл не передан.';
        $messageType = 'danger';
    } else {
        $file = $_FILES['file'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = 'Ошибка при загрузке файла. Код ошибки: ' . $file['error'];
            $messageType = 'danger';
        } else {
            if ($file['size'] > $maxFileSize) {
                $message = 'Файл по размеру превышает допустимый лимит (' . human_filesize($maxFileSize) . ').';
                $messageType = 'danger';
            } else {
                $info = @getimagesize($file['tmp_name']);
                if ($info === false) {
                    $message = 'Файл не является изображением.';
                    $messageType = 'danger';
                } else {
                    $width = $info[0];
                    $height = $info[1];
                    if ($width > $maxWidth || $height > $maxHeight) {
                        $message = "Разрешение изображения {$width}x{$height} превышает допустимое ({$maxWidth}x{$maxHeight}).";
                        $messageType = 'danger';
                    } else {
                        $mime = $info['mime'] ?? mime_content_type($file['tmp_name']);
                        if (!array_key_exists($mime, $allowedMime)) {
                            $message = 'Недопустимый тип изображения. Разрешены: JPG, PNG, GIF.';
                            $messageType = 'danger';
                        } else {
                            $ext = $allowedMime[$mime];
                            $newName = time() . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
                            $destPath = $imgdir . '/' . $newName;
                            if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                                $message = 'Не удалось сохранить загруженный файл на сервер.';
                                $messageType = 'danger';
                            } else {
                                $comment = trim($_POST['filename'] ?? '');
                                $meta[$newName] = [
                                    'comment' => $comment,
                                    'time' => time(),
                                    'size' => filesize($destPath),
                                    'width' => $width,
                                    'height' => $height
                                ];
                                file_put_contents($metaFile, json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
                                $message = 'Фото успешно загружено.';
                                $messageType = 'success';
                            }
                        }
                    }
                }
            }
        }
    }
}

$photos = [];
$dh = opendir($imgdir);
if ($dh) {
    while (($entry = readdir($dh)) !== false) {
        if ($entry === '.' || $entry === '..') continue;
        if (!preg_match('/\.(jpe?g|png|gif)$/i', $entry)) continue;
        $path = $imgdir . '/' . $entry;
        if (!is_file($path)) continue;
        $ft = filemtime($path);
        $sz = filesize($path);
        $info = @getimagesize($path);
        $w = $info ? $info[0] : null;
        $h = $info ? $info[1] : null;
        $photos[] = [
            'file' => $entry,
            'url' => $imgWebDir . '/' . $entry,
            'time' => $ft,
            'size' => $sz,
            'width' => $w,
            'height' => $h,
            'comment' => isset($meta[$entry]) ? $meta[$entry]['comment'] : ''
        ];
    }
    closedir($dh);

    usort($photos, function($a, $b) {
        return $b['time'] <=> $a['time'];
    });
}

$total = count($photos);
$totalPages = ($total === 0) ? 1 : (int)ceil($total / $perPage);
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;
$pagePhotos = array_slice($photos, $offset, $perPage);

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
  <title>Фотоальбом — AniPat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    .card-img-top { max-height: 250px; object-fit: cover; }
  </style>
</head>
<body>

<div class="container mt-4">
  <h1 class="mb-3">Фотоальбом</h1>

  <?php if ($message): ?>
    <div class="alert alert-<?php echo ($messageType); ?>">
      <?php echo ($message); ?>
    </div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <form action="photos.php" method="post" enctype="multipart/form-data" class="form-inline">
        <div class="form-group mb-2 mr-2">
          <label class="sr-only" for="file">Файл</label>
          <input type="file" class="form-control-file" id="file" name="file" required>
        </div>
        <div class="form-group mb-2 mr-2" style="min-width:300px;">
          <label class="sr-only" for="filename">Комментарий</label>
          <input type="text" class="form-control" id="filename" name="filename" placeholder="Комментарий к фото" required>
        </div>
        <button type="submit" name="doupload" class="btn btn-primary mb-2">Закачать новую фотографию</button>
      </form>
      <small class="form-text text-muted">Макс. размер файла: <?php echo human_filesize($maxFileSize); ?>. Макс. разрешение: <?php echo $maxWidth . 'x' . $maxHeight; ?>.</small>
    </div>
  </div>

  <div class="row">
    <?php if (empty($photos)): ?>
      <div class="col-12"><p class="text-muted">Пока нет загруженных фотографий.</p></div>
    <?php endif; ?>

    <?php foreach ($pagePhotos as $p): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="<?php echo htmlspecialchars($p['url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['comment'] ?: $p['file']); ?>">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($p['comment'] ?: 'Без комментария'); ?></h5>
            <p class="card-text mb-1"><strong>Имя файла:</strong> <?php echo htmlspecialchars($p['file']); ?></p>
            <p class="card-text mb-1"><strong>Размер:</strong> <?php echo human_filesize($p['size']); ?></p>
            <p class="card-text mb-1"><strong>Размеры:</strong> <?php echo ($p['width'] && $p['height']) ? ($p['width'].'x'.$p['height']) : '—'; ?></p>
            <p class="card-text text-muted mt-auto"><small>Добавлено: <?php echo date("d.m.Y H:i:s", $p['time']); ?></small></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php if ($totalPages > 1): ?>
    <nav aria-label="Фотоальбом - пагинация">
      <ul class="pagination justify-content-center">
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
          <a class="page-link" href="<?php echo htmlspecialchars(page_url(max(1, $page-1))); ?>" aria-label="Previous">&laquo;</a>
        </li>

        <?php
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        if ($start > 1) {
            echo '<li class="page-item"><a class="page-link" href="'.htmlspecialchars(page_url(1)).'">1</a></li>';
            if ($start > 2) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
        for ($i = $start; $i <= $end; $i++): ?>
          <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
            <a class="page-link" href="<?php echo htmlspecialchars(page_url($i)); ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor;
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
            echo '<li class="page-item"><a class="page-link" href="'.htmlspecialchars(page_url($totalPages)).'">'.$totalPages.'</a></li>';
        }
        ?>

        <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
          <a class="page-link" href="<?php echo htmlspecialchars(page_url(min($totalPages, $page+1))); ?>" aria-label="Next">&raquo;</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>

</div>

<!-- Bootstrap JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>