<?php
$pageTitle = "Contact - Barbers"; // Заголовок, будет подставлен в <title>
include "includes/header.php";

// Показываем topmenu только на index.php и contact.php
if (basename($_SERVER['PHP_SELF']) === 'index.php' || basename($_SERVER['PHP_SELF']) === 'contact.php') {
    include "includes/topmenu.php";
}

// Подключаем меню
include "includes/menu.php";
?>

<!-- bradcam_area_start -->
<div class="bradcam_area breadcam_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bradcam_text text-center">
                    <h3>contact</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- bradcam_area_end -->

<?php include "includes/footer.php"; ?>