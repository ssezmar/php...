<?php include "./views/header.php"; ?>

<div class="alert-container">
    <div class="alert alert-error">
        <h2>Доступ запрещен</h2>
        <p>У вас нет прав для доступа к этой странице.</p>
        <a href="/lab7/index.php?controller=home&action=index" class="btn btn-primary">Вернуться на главную</a>
    </div>
</div>

<?php include "./views/footer.php"; ?>