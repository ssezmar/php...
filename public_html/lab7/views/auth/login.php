<?php include "./views/header.php"; ?>

<div class="form-container">
    <h2>Вход в систему</h2>

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

        <button type="submit" class="btn btn-primary">Вход</button>
    </form>

    <p>Нет аккаунта? <a href="/lab7/index.php?controller=auth&action=register">Зарегистрируйтесь</a></p>

    <div class="demo-info">
        <h3>Демо аккаунты:</h3>
        <ul>
            <li>Администратор: admin / 123456</li>
            <li>Пользователь: user1 / 123456</li>
        </ul>
    </div>
</div>

<?php include "./views/footer.php"; ?>