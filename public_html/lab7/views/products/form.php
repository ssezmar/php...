<?php include "./views/header.php"; ?>

<div class="form-container">
    <h2><?php echo isset($product) ? 'Редактировать товар' : 'Добавить товар'; ?></h2>

    <form method="POST" class="form">
        <div class="form-group">
            <label for="name">Название</label>
            <input type="text" id="name" name="name" value="<?php echo isset($product) ? htmlspecialchars($product['name']) : ''; ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" name="description" rows="4"><?php echo isset($product) ? htmlspecialchars($product['description']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Цена (₽)</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo isset($product) ? htmlspecialchars($product['price']) : ''; ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/lab7/index.php?controller=product&action=index" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<?php include "./views/footer.php"; ?>