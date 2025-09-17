<?php
$pageTitle = "About Us - Animal";

// Общая шапка сайта (head, open <body>, открытие header)
include "includes/header.php";

// Topmenu по условию включается только на главной и странице контактов, поэтому здесь его НЕ подключаем:
// if (basename($_SERVER['PHP_SELF']) === 'index.php' || basename($_SERVER['PHP_SELF']) === 'contact.php') {
//     include "includes/topmenu.php";
// }

// Подключаем главное меню (из массива)
include "includes/menu.php";
?>

<!-- bradcam_area_start -->
<div class="bradcam_area breadcam_bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bradcam_text text-center">
                    <h3>About Us</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- bradcam_area_end -->

<?php 
// Общий подвал сайта (footer, скрипты)
include "includes/footer.php"; 
?>