<?php 
// views/orders/edit.php
include 'views/header.php'; 
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">✏️ Редактировать заказ</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=orders">
                    <input type="hidden" name="update" value="1">
                    <input type="hidden" name="id" value="<?php echo $order['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">Покупатель</label>
                        <select name="customer_id" class="form-select" required>
                            <?php 
                            $customers->data_seek(0);
                            while ($customer = $customers->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $customer['id']; ?>" 
                                    <?php echo ($customer['id'] == $order['customer_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($customer['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Товар</label>
                        <select name="product_id" class="form-select" id="product_select" required>
                            <?php 
                            $products->data_seek(0);
                            while ($product = $products->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $product['id']; ?>" 
                                        data-price="<?php echo $product['price']; ?>"
                                        <?php echo ($product['id'] == $order['product_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($product['name']); ?> - 
                                    <?php echo number_format($product['price'], 2); ?> ₽
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Количество</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" 
                               min="1" value="<?php echo $order['quantity']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Итоговая сумма (руб.)</label>
                        <input type="number" step="0.01" name="total" id="total" 
                               class="form-control" value="<?php echo $order['total']; ?>" required>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">💾 Сохранить изменения</button>
                        <a href="?page=orders" class="btn btn-secondary">❌ Отмена</a>
                    </div>
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