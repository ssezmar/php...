<!doctype html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Animal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
<div class="header-area">
<!--Start Top menu-->
<?php
// подключаем topmenu только на index.php и contact.php
$current = basename($_SERVER['SCRIPT_NAME']);
if ($current == 'index.php' || $current == 'contact.php') {
    include "topmenu.php";
}
?>
<!--End Top menu-->

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
                <?php include "menu.php"; ?>
            </div>
        </div>
    </div>
</div>
</div>
</header>
