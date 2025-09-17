<?php
$pageTitle = "Pagination - Animal";

// Подключения
include "includes/header.php";
include "includes/menu.php";
include "function.php";

// Данные (можно заменить на реальные из БД или массив животных)
$data = [
    "бобик","Карпухин Глеб","Собака3","Собака4","Собака5",
    "Собака6","Собака7","Собака8","Собака9","Собака10",
    "Собака11","Собака12","Собака13","Собака14","Собака15"
];

$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$perPage = 5;

// Получаем данные для текущей страницы
$result = paginateArray($data, $currentPage, $perPage);

// Вывод элементов
echo "<div class='container'>";
echo "<h2 class='text-center mt-4'>Список собак</h2>";

if (!empty($result['data'])) {
    echo "<ul>";
    foreach ($result['data'] as $item) {
        echo "<li>{$item}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Нет данных.</p>";
}

// Навигация по страницам
if (!empty($result) && $result['pages_total'] > 1) {
    echo '<div class="pagination">';
    for ($i = 1; $i <= $result['pages_total']; $i++) {
        $class = ($i === $currentPage) ? 'active' : '';
        echo '<a href="?page=' . $i . '" class="' . $class . '">' . $i . '</a>';
    }
    echo '</div>';
}

echo "</div>";

include "includes/footer.php";
?>