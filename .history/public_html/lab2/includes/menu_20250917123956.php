<?php
$menu = [
    ["link" => "Home", "href" => "index.php"],
    ["link" => "About", "href" => "about.php"],
    ["link" => "Contact", "href" => "contact.php"],
    ["link" => "Pagination", "href" => "pagination.php"] // <-- добавили
];
?>

<!-- slider_area_start -->
<div class="slider_area">
    <div class="single_slider slider_bg_1 d-flex align-items-center" style="background-color: orange;">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6">
                    <div class="slider_text">
                        <h3>We Care <br> <span>Your Pets</span></h3>
                        <p>Lorem ipsum dolor sit amet, consectetur <br> adipiscing elit, sed do eiusmod.</p>
                        <a href="contact.php" class="boxed-btn4">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="dog_thumb d-none d-lg-block">
            <img src="img/ban/dog.png" alt="">
        </div>
    </div>
</div>
<!-- slider_area_end -->
<div id="sticky-header" class="main-header-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-3 col-lg-3">
                <div class="logo">
                    <a href="index.php">
                        <img src="img/logo.png" alt="">
                    </a>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9">
                <div class="main-menu  d-none d-lg-block">
                    <nav>
                        <ul id="navigation">
                            <?php foreach ($menu as $item): ?>
                                <li><a href="<?= $item['href'] ?>"><?= $item['link'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-12">
                <div class="mobile_menu d-block d-lg-none"></div>
            </div>
        </div>
    </div>
</div>
</div> <!-- .header-area -->
</header>