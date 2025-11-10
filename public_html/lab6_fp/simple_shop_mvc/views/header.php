<?php
// views/header.php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интернет-магазин - MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="?page=products">🛒 Интернет-магазин</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] == 'products') ? 'active' : ''; ?>" 
                           href="?page=products">📦 Товары</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'customers') ? 'active' : ''; ?>" 
                           href="?page=customers">👥 Покупатели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] == 'orders') ? 'active' : ''; ?>" 
                           href="?page=orders">📋 Заказы</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">