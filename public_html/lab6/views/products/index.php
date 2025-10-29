<?php 
// views/products/index.php
include 'views/header.php'; 
?>

<?php if ($message): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📦 Список товаров</h5>
            </div>
            <div class="card-body">
                <?php if ($products && $products->num_rows > 0): ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Описание</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($product = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo number_format($product['price'], 2); ?> ₽</td>
                                <td><?php echo htmlspecialchars($product['description']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="?page=products&action=edit&id=<?php echo $product['id']; ?>" 
                                           class="btn btn-warning">✏️ Изменить</a>
                                        <a href="?page=products&delete_id=<?php echo $product['id']; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Удалить этот товар?')">🗑️ Удалить</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Товары не найдены!</strong><br>
                        Возможно таблица пуста или возникла ошибка при подключении к БД.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">➕ Добавить товар</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=products">
                    <input type="hidden" name="create" value="1">

                    <div class="mb-3">
                        <label class="form-label">Название товара</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Цена (руб.)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">💾 Создать товар</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>