<?php include "./views/header.php"; ?>

<div class="form-container">
    <h2>Регистрация</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <div class="form-group">
            <label for="username">Имя пользователя</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="customer_name">Имя покупателя</label>
            <input type="text" id="customer_name" name="customer_name" required>
        </div>

        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>

    <p>Уже есть аккаунт? <a href="/lab7/index.php?controller=auth&action=login">Войти</a></p>
</div>

<?php include "./views/footer.php"; ?>