<?php
$pageTitle = "Главная - Animal";
include "includes/header.php";

// Top menu только на index.php и contact.php
if (basename($_SERVER['PHP_SELF']) === 'index.php' || basename($_SERVER['PHP_SELF']) === 'contact.php') {
    include "includes/topmenu.php";
}

// Меню делаем через массив
include "includes/menu.php";
?>

<!-- Здесь начинается уникальный контент для главной страницы -->
<div class="slider_area">
    ... твой блок слайдера и остальной контент index.html ...
</div>

<?php
include "includes/footer.php";
?>