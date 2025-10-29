<?php
$customer = new Customer($db);

// Создание покупателя
if ($_POST && isset($_POST['create'])) {
    $customer->first_name = $_POST['first_name'];
    $customer->last_name = $_POST['last_name'];
    $customer->email = $_POST['email'];
    $customer->phone = $_POST['phone'];
    $customer->address = $_POST['address'];
    
    if ($customer->create()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Покупатель успешно создан!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при создании покупателя!</div>";
    }
}

// Удаление покупателя
if (isset($_GET['delete_id'])) {
    $customer->id = $_GET['delete_id'];
    if ($customer->delete()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                Покупатель успешно удален!
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при удалении покупателя!</div>";
    }
}

$result = $customer->read();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Добавить покупателя</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" name="first_name" class="form-control" required maxlength="100">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Фамилия</label>
                            <input type="text" name="last_name" class="form-control" required maxlength="100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" maxlength="20">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Адрес</label>
                        <textarea name="address" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" name="create" class="btn btn-primary w-100">Добавить покупателя</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Список покупателей</h4>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Фамилия</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Адрес</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td>
                                    <a href="?page=customers&delete_id=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Вы уверены, что хотите удалить этого покупателя?')">
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
                    <h5>Покупатели не найдены</h5>
                    <p class="mb-0">Добавьте первого покупателя используя форму слева.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>