<?php 
// views/products/view.php
include 'views/header.php'; 
?>

<?php if ($message): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="mb-3">
    <a href="?page=products" class="btn btn-secondary">← Назад к списку товаров</a>
</div>

<div class="row">
    <!-- Информация о товаре -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📦 Информация о товаре</h5>
            </div>
            <div class="card-body">
                <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                <h3 class="text-success"><?php echo number_format($product['price'], 2); ?> ₽</h3>

                <?php if ($rating_info['total_reviews'] > 0): ?>
                    <div class="mt-3">
                        <strong>Рейтинг:</strong> 
                        <span class="badge bg-warning text-dark">
                            ⭐ <?php echo number_format($rating_info['avg_rating'], 1); ?> / 5
                        </span>
                        <small class="text-muted">(<?php echo $rating_info['total_reviews']; ?> отзывов)</small>
                    </div>
                <?php else: ?>
                    <div class="mt-3">
                        <span class="badge bg-secondary">Отзывов пока нет</span>
                    </div>
                <?php endif; ?>

                <div class="mt-3">
                    <a href="?page=products&action=edit&id=<?php echo $product['id']; ?>" 
                       class="btn btn-warning">✏️ Редактировать товар</a>
                </div>
            </div>
        </div>

        <!-- Форма добавления отзыва -->
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">➕ Добавить отзыв</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="?page=products&action=view&id=<?php echo $product['id']; ?>">
                    <input type="hidden" name="add_review" value="1">

                    <div class="mb-3">
                        <label class="form-label">Покупатель</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Выберите покупателя</option>
                            <?php 
                            if ($customers && $customers->num_rows > 0) {
                                while ($customer = $customers->fetch_assoc()): 
                            ?>
                                <option value="<?php echo $customer['id']; ?>">
                                    <?php echo htmlspecialchars($customer['name']); ?>
                                </option>
                            <?php 
                                endwhile;
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Оценка</label>
                        <select name="rating" class="form-select" required>
                            <option value="">Выберите оценку</option>
                            <option value="5">⭐⭐⭐⭐⭐ (5 - Отлично)</option>
                            <option value="4">⭐⭐⭐⭐ (4 - Хорошо)</option>
                            <option value="3">⭐⭐⭐ (3 - Нормально)</option>
                            <option value="2">⭐⭐ (2 - Плохо)</option>
                            <option value="1">⭐ (1 - Ужасно)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Комментарий</label>
                        <textarea name="comment" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100">💾 Отправить отзыв</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Список отзывов -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">💬 Отзывы о товаре</h5>
            </div>
            <div class="card-body">
                <?php if ($reviews && $reviews->num_rows > 0): ?>
                    <?php while ($review = $reviews->fetch_assoc()): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <strong><?php echo htmlspecialchars($review['customer_name']); ?></strong>
                                        </h6>
                                        <div class="mb-2">
                                            <?php 
                                            for ($i = 0; $i < $review['rating']; $i++) {
                                                echo '⭐';
                                            }
                                            ?>
                                            <span class="text-muted">(<?php echo $review['rating']; ?>/5)</span>
                                        </div>
                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                                        <small class="text-muted">
                                            <?php echo date('d.m.Y H:i', strtotime($review['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div>
                                        <a href="?page=products&action=view&id=<?php echo $product['id']; ?>&delete_review=<?php echo $review['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Удалить этот отзыв?')">🗑️</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        <strong>ℹ️ Отзывов пока нет</strong><br>
                        Будьте первым, кто оставит отзыв о этом товаре!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'views/footer.php'; ?>