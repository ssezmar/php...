<?php 
// views/orders/index.php
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
                <h5 class="mb-0">📋 Список заказов</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Покупатель</th>
                            <th>Товар</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo number_format($order['total'], 2); ?> ₽</td>
                            <td><?php echo date('d.m.Y H:i', strtotime($order['order_date'])); ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="?page=orders&action=edit&id=<?php echo $order['id']; ?>" 
                                       class="btn btn-warning">✏️ Изменить</a>
                                    <a href="?page=orders&delete_id=<?php echo $order['id']; ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Удалить этот заказ?')">🗑️ Удалить</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">➕ Добавить заказ</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=orders">
                    <input type="hidden" name="create" value="1">

                    <div class="mb-3">
                        <label class="form-label">Покупатель</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Выберите покупателя</option>
                            <?php 
                            $customers->data_seek(0);
                            while ($customer = $customers->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $customer['id']; ?>">
                                    <?php echo htmlspecialchars($customer['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Товар</label>
                        <select name="product_id" class="form-select" id="product_select" required>
                            <option value="">Выберите товар</option>
                            <?php 
                            $products->data_seek(0);
                            while ($product = $products->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $product['id']; ?>" 
                                        data-price="<?php echo $product['price']; ?>">
                                    <?php echo htmlspecialchars($product['name']); ?> - 
                                    <?php echo number_format($product['price'], 2); ?> ₽
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Количество</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" 
                               min="1" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Итоговая сумма (руб.)</label>
                        <input type="number" step="0.01" name="total" id="total" 
                               class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">💾 Создать заказ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Автоматический расчет суммы заказа
document.getElementById('product_select').addEventListener('change', calculateTotal);
document.getElementById('quantity').addEventListener('input', calculateTotal);

function calculateTotal() {
    const select = document.getElementById('product_select');
    const quantity = document.getElementById('quantity').value;
    const option = select.options[select.selectedIndex];
    const price = option.getAttribute('data-price');

    if (price && quantity) {
        const total = (parseFloat(price) * parseInt(quantity)).toFixed(2);
        document.getElementById('total').value = total;
    }
}
</script>

<?php include 'views/footer.php'; ?>