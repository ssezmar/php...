<?php
$pageTitle = "Главная - Animal";
include "includes/header.php";

// Topmenu показываем только на index.php и contact.php
if (basename($_SERVER['PHP_SELF']) === 'index.php' || basename($_SERVER['PHP_SELF']) === 'contact.php') {
    include "includes/topmenu.php";
}

include "includes/menu.php";
?>

<!-- Уникальный контент index.html -->
<div class="slider_area">
    <div class="single_slider slider_bg_1 d-flex align-items-center" style="background-color: orange;">
        <!-- ... остальной контент слайдера ... -->
    </div>
</div>

<div class="service_area">
    <!-- ... остальной уникальный контент страницы ... -->
</div>

<div class="pet_care_area">
    <!-- ... -->
</div>

<div class="adapt_area">
    <!-- ... -->
</div>

<?php include "includes/footer.php"; ?>