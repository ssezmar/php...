<?php include "./views/header.php"; ?>

<div class="form-container">
    <h2><?php echo isset($order) ? 'Редактировать заказ' : 'Добавить заказ'; ?></h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="form">
        <!-- Задание 1: поле "Выберите пользователя" только для администратора -->
        <?php if ($isAdmin): ?>
            <div class="form-group">
                <label for="customer_id">Выберите пользователя</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">-- Выберите пользователя --</option>
                    <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo $customer['id']; ?>" <?php echo isset($order) && $order['customer_id'] == $customer['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($customer['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <!-- Для обычного пользователя customer_id скрыт и берется из сессии -->
            <input type="hidden" name="customer_id" value="<?php echo Auth::getCurrentUserId(); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="product_id">Товар</label>
            <select id="product_id" name="product_id" required>
                <option value="">-- Выберите товар --</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo $product['id']; ?>" <?php echo isset($order) && $order['product_id'] == $product['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($product['name']); ?> (<?php echo number_format($product['price'], 2); ?> ₽)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Количество</label>
            <input type="number" id="quantity" name="quantity" min="1" value="<?php echo isset($order) ? htmlspecialchars($order['quantity']) : '1'; ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="/lab7/index.php?controller=order&action=index" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
</div>

<?php include "./views/footer.php"; ?>