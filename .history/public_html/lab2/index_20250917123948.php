<?php
$pageTitle = "Главная - Animal";
include "includes/header.php";

// Подключаем Topmenu только на index.php и contact.php
if (basename($_SERVER['PHP_SELF']) === 'index.php' || basename($_SERVER['PHP_SELF']) === 'contact.php') {
    include "includes/topmenu.php";
}

// Подключаем главное меню
include "includes/menu.php";
?>

<!-- service_area_start  -->
<div class="service_area">
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-lg-7 col-md-10">
                <div class="section_title text-center mb-95">
                    <h3>Services for every dog</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="single_service">
                     <div class="service_thumb service_icon_bg_1 d-flex align-items-center justify-content-center">
                         <div class="service_icon">
                             <img src="img/service/service_icon_1.png" alt="">
                         </div>
                     </div>
                     <div class="service_content text-center">
                        <h3>Pet Boarding</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
                     </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single_service active">
                     <div class="service_thumb service_icon_bg_1 d-flex align-items-center justify-content-center">
                         <div class="service_icon">
                             <img src="img/service/service_icon_2.png" alt="">
                         </div>
                     </div>
                     <div class="service_content text-center">
                        <h3>Healthy Meals</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
                     </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single_service">
                     <div class="service_thumb service_icon_bg_1 d-flex align-items-center justify-content-center">
                         <div class="service_icon">
                             <img src="img/service/service_icon_3.png" alt="">
                         </div>
                     </div>
                     <div class="service_content text-center">
                        <h3>Pet Spa</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut</p>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- service_area_end -->

<!-- pet_care_area_start  -->
<div class="pet_care_area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="pet_thumb">
                    <img src="img/about/pet_care.png" alt="">
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1 col-md-6">
                <div class="pet_info">
                    <div class="section_title">
                        <h3><span>We care your pet </span> <br>
                            As you care</h3>
                        <p>Lorem ipsum dolor sit , consectetur adipiscing elit, sed do <br> iusmod tempor incididunt ut labore et dolore magna aliqua. <br> Quis ipsum suspendisse ultrices gravida. Risus commodo <br>
                            viverra maecenas accumsan.</p>
                        <a href="about.php" class="boxed-btn3">About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- pet_care_area_end  -->

<!-- adapt_area_start  -->
<div class="adapt_area">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-5">
                <div class="adapt_help">
                    <div class="section_title">
                        <h3><span>We need your</span>
                            help Adopt Us</h3>
                        <p>Lorem ipsum dolor sit , consectetur adipiscing elit, sed do iusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices.</p>
                        <a href="contact.php" class="boxed-btn3">Contact Us</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="adapt_about">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <div class="single_adapt text-center">
                                <img src="img/adapt_icon/1.png" alt="">
                                <div class="adapt_content">
                                    <h3 class="counter">452</h3>
                                    <p>Pets Available</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single_adapt text-center">
                                <img src="img/adapt_icon/3.png" alt="">
                                <div class="adapt_content">
                                    <h3><span class="counter">52</span>+</h3>
                                    <p>Pets Available</p>
                                </div>
                            </div>
                            <div class="single_adapt text-center">
                                <img src="img/adapt_icon/2.png" alt="">
                                <div class="adapt_content">
                                    <h3><span class="counter">52</span>+</h3>
                                    <p>Pets Available</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- adapt_area_end  -->

<?php include "includes/footer.php"; ?>