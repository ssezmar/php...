<?php
require_once 'PhotoHandler.php';
require_once 'FileUtils.php'; // утилита для пагинации и прочее

include "includes/header.php";
include "includes/topmenu.php";
include "includes/menu.php";


// Инициализация обработчика фото
$uploader = new PhotoHandler('uploads/photos/', 'files_data.json');

// Сообщения для пользователя
$msg = null;
$msgType = 'success';

// Загрузка файла
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doupload'])) {
    try {
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

// Получение только фото
$photos = $uploader->getFilesByType('photo');

// Сортировка по дате
usort($photos, function($a, $b) {
    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
});

// Пагинация
$perPage = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = FileUtils::paginateArray($photos, $page, $perPage);
$pagePhotos = $pagination['items'];
$totalPages = $pagination['total_pages'];
$page = $pagination['current_page'];

// Функция для генерации URL страницы
function page_url($p) {
    $qs = $_GET;
    $qs['page'] = $p;
    return basename($_SERVER['PHP_SELF']) . '?' . http_build_query($qs);
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Фотоальбом</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Фотоальбом</h1>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo htmlspecialchars($msgType); ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <!-- Форма загрузки -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <input type="file" name="file" class="form-control-file" required>
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="text" name="filename" class="form-control" placeholder="Комментарий" required>
                </div>
                <button type="submit" name="doupload" class="btn btn-primary mb-2">Загрузить</button>
            </form>
        </div>
    </div>

    <!-- Вывод фото -->
    <?php if (empty($photos)): ?>
        <p class="text-muted">Нет загруженных фото.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($pagePhotos as $photo): ?>
                <?php echo $uploader->generateCard($photo); ?>
            <?php endforeach; ?>
        </div>

        <!-- Пагинация -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo page_url(max(1, $page - 1)); ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo page_url($i); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo page_url(min($totalPages, $page + 1)); ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<?php 
    include "includes/footer.php";
?>
</body>
</html>