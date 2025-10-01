<?php
require_once 'DocumentHandler.php';
require_once 'FileUtils.php';

include "includes/header.php";
include "includes/topmenu.php";
include "includes/menu.php";


// Инициализация
$uploader = new DocumentHandler('uploads/documents/', 'files_data.json');
$maxFileSize = FileUtils::human_filesize($uploader->maxSize ?? 20971520);

$msg = null;
$msgType = 'success';

// Загрузка
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    try {
        $uploader->uploadFile($_FILES['file'], $_POST['title']);
        $msg = "Файл успешно загружен.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

// Удаление
if (isset($_GET['delete'])) {
    try {
        $uploader->deleteFile($_GET['delete']);
        $msg = "Файл удалён.";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        $msgType = 'danger';
    }
}

// Получение только документов
$docs = $uploader->getFilesByType('document');

// Сортировка по дате
usort($docs, function($a, $b) {
    return strtotime($b['upload_date']) - strtotime($a['upload_date']);
});

// Пагинация
$perPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = FileUtils::paginateArray($docs, $page, $perPage);
$pageDocs = $pagination['items'];
$totalPages = $pagination['total_pages'];
$page = $pagination['current_page'];

// Для ссылок страниц
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
    <title>Документы</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Документы</h1>

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
                    <input type="file" class="form-control-file" name="file" required>
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control" name="title" placeholder="Комментарий" required>
                </div>
                <button type="submit" name="upload" class="btn btn-primary mb-2">Загрузить</button>
            </form>
            <small class="form-text text-muted">
                Разрешённые форматы: <?php echo implode(', ', $uploader->allowedTypes ?? []); ?>.<br>
                Макс. размер: <?php echo htmlspecialchars($maxFileSize); ?>.
            </small>
        </div>
    </div>

    <!-- Список документов -->
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
                        <a class="page-link" href="<?php echo page_url(max(1, $page - 1)); ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo page_url($i); ?>"><?php echo $i; ?></a>
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
<?php include "includes/footer.php"; ?>
</body>
</html>