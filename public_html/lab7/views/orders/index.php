<?php include "./views/header.php"; ?>

<div class="page-content">
    <h2>Заказы</h2>

    <div class="action-buttons">
        <a href="/lab7/index.php?controller=order&action=create" class="btn btn-success">+ Добавить заказ</a>
    </div>

    <?php if (!empty($orders)): ?>
        <table class="table">
            <thead>
                <tr>
                    <!-- Задание 1: поля ID и Покупатель только для администратора -->
                    <?php if ($isAdmin): ?>
                        <th>ID</th>
                        <th>Покупатель</th>
                    <?php endif; ?>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Итого (₽)</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <!-- Задание 1: скрываем ID и Покупателя для пользователей -->
                        <?php if ($isAdmin): ?>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?></td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($order['product_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo number_format($order['total_price'], 2); ?></td>
                        <td class="actions">
                            <a href="/lab7/index.php?controller=order&action=edit&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">Редактировать</a>
                            <a href="/lab7/index.php?controller=order&action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?');">Удалить</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Нет заказов.</p>
    <?php endif; ?>
</div>

<?php include "./views/footer.php"; ?>