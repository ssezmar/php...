<?php
$order = new Order($db);

// Создание заказа
if ($_POST && isset($_POST['create'])) {
    $order->customer_id = $_POST['customer_id'];
    $order->product_id = $_POST['product_id'];
    $order->quantity = $_POST['quantity'];
    $order->total_amount = $_POST['total_amount'];
    $order->status = $_POST['status'];
    
    if ($order->create()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Заказ успешно создан!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при создании заказа!</div>";
    }
}

// Удаление заказа
if (isset($_GET['delete_id'])) {
    $order->id = $_GET['delete_id'];
    if ($order->delete()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Заказ успешно удален!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при удалении заказа!</div>";
    }
}

$orders = $order->read();
$customers = $order->getCustomers();
$products = $order->getProducts();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Создать заказ</h4>
            </div>
            <div class="card-body">
                <form method="POST" id="orderForm">
                    <div class="mb-3">
                        <label class="form-label">Покупатель</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Выберите покупателя</option>
                            <?php while ($customer = $customers->fetch_assoc()): ?>
                            <option value="<?php echo $customer['id']; ?>">
                                <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Товар</label>
                        <select name="product_id" id="product_id" class="form-select" required onchange="calculateTotal()">
                            <option value="">Выберите товар</option>
                            <?php while ($product = $products->fetch_assoc()): ?>
                            <option value="<?php echo $product['id']; ?>" data-price="<?php echo $product['price']; ?>">
                                <?php echo htmlspecialchars($product['name'] . ' - ' . $product['price'] . ' руб.'); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Количество</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" 
                               min="1" value="1" required oninput="calculateTotal()">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Общая сумма (руб.)</label>
                        <input type="number" step="0.01" name="total_amount" id="total_amount" 
                               class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Статус</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">В обработке</option>
                            <option value="completed">Завершен</option>
                            <option value="cancelled">Отменен</option>
                        </select>
                    </div>
                    <button type="submit" name="create" class="btn btn-primary w-100">Создать заказ</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Список заказов</h4>
            </div>
            <div class="card-body">
                <?php if ($orders->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Покупатель</th>
                                <th>Товар</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                                <th>Дата</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $row['quantity']; ?> шт.</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <?php echo number_format($row['total_amount'], 2); ?> руб.
                                    </span>
                                </td>
                                <td><?php echo date('d.m.Y H:i', strtotime($row['order_date'])); ?></td>
                                <td>
                                    <?php
                                    $status_badges = [
                                        'pending' => 'bg-warning',
                                        'completed' => 'bg-success', 
                                        'cancelled' => 'bg-danger'
                                    ];
                                    $status_text = [
                                        'pending' => 'В обработке',
                                        'completed' => 'Завершен',
                                        'cancelled' => 'Отменен'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $status_badges[$row['status']]; ?>">
                                        <?php echo $status_text[$row['status']]; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?page=orders&delete_id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">
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
                    <h5>Заказы не найдены</h5>
                    <p class="mb-0">Создайте первый заказ используя форму слева.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>