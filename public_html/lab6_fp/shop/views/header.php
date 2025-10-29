<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин - MySQLi + php ооп + частичная mvc парадигма</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <strong>Магазин </strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page == 'products') ? 'active' : ''; ?>" 
                           href="index.php?page=products">Товары</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page == 'customers') ? 'active' : ''; ?>" 
                           href="index.php?page=customers">Покупатели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($page == 'orders') ? 'active' : ''; ?>" 
                           href="index.php?page=orders">Заказы</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Проект (MySQLi + php ооп + частичная mvc- архитектура)
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">