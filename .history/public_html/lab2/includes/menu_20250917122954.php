<?php
$menu = [
    ["link" => "home", "href" => "index.php"],
    ["link" => "about", "href" => "about.php"],
    ["link" => "services", "href" => "services.php"],
    ["link" => "contact", "href" => "contact.php"],
    ["link" => "pagination", "href" => "pagination.php"] // доп. задание
];
?>
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