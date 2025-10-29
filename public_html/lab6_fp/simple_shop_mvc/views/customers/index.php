<?php 
// views/customers/index.php
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
                <h5 class="mb-0">👥 Список покупателей</h5>
            </div>
            <div class="card-body">
                <?php if ($customers && $customers->num_rows > 0): ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($customer = $customers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $customer['id']; ?></td>
                                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="?page=customers&action=edit&id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-warning">✏️ Изменить</a>
                                        <a href="?page=customers&delete_id=<?php echo $customer['id']; ?>" 
                                           class="btn btn-danger" 
                                           onclick="return confirm('Удалить этого покупателя?')">🗑️ Удалить</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <strong>⚠️ Покупатели не найдены!</strong><br>
                        Возможно таблица пуста или возникла ошибка при подключении к БД.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">➕ Добавить покупателя</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=customers">
                    <input type="hidden" name="create" value="1">

                    <div class="mb-3">
                        <label class="form-label">Имя</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success w-100">💾 Создать покупателя</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>