<?php
$product = new Product($db);

// Создание товара
if ($_POST && isset($_POST['create'])) {
    $product->name = $_POST['name'];
    $product->description = $_POST['description'];
    $product->price = $_POST['price'];
    $product->category = $_POST['category'];
    
    if ($product->create()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Товар успешно создан!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при создании товара!</div>";
    }
}

// Удаление товара
if (isset($_GET['delete_id'])) {
    $product->id = $_GET['delete_id'];
    if ($product->delete()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Товар успешно удален!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при удалении товара!</div>";
    }
}

$result = $product->read();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Добавить товар</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Название товара</label>
                        <input type="text" name="name" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Цена (руб.)</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Категория</label>
                        <input type="text" name="category" class="form-control" maxlength="100">
                    </div>
                    <button type="submit" name="create" class="btn btn-primary w-100">Добавить товар</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Список товаров</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Описание</th>
                                <th>Цена</th>
                                <th>Категория</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <span class="badge bg-success">
                                        <?php echo number_format($row['price'], 2); ?> руб.
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo htmlspecialchars($row['category']); ?></span>
                                </td>
                                <td>
                                    <a href="?page=products&delete_id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">
                                        Удалить
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info text-center">
                    <h5>Товары не найдены</h5>
                    <p class="mb-0">Добавьте первый товар используя форму слева.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>