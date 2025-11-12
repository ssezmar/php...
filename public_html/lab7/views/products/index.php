<?php include "./views/header.php"; ?>

<div class="page-content">
    <h2>Товары</h2>

    <?php if ($isGuest): ?>
        <div class="alert alert-info">
            <p>Вы просматриваете товары как неавторизованный пользователь. <a href="/lab7/index.php?controller=auth&action=login">Войдите</a> или <a href="/lab7/index.php?controller=auth&action=register">зарегистрируйтесь</a> для работы с заказами.</p>
        </div>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
        <div class="action-buttons">
            <a href="/lab7/index.php?controller=product&action=create" class="btn btn-success">+ Добавить товар</a>
        </div>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Цена</th>
                    <?php if ($isAdmin): ?>
                        <th>Действия</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><?php echo number_format($product['price'], 2); ?> ₽</td>
                        <?php if ($isAdmin): ?>
                            <td class="actions">
                                <a href="/lab7/index.php?controller=product&action=edit&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-info">Редактировать</a>
                                <a href="/lab7/index.php?controller=product&action=delete&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Нет товаров в магазине.</p>
    <?php endif; ?>
</div>

<?php include "./views/footer.php"; ?>