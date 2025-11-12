-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 26 2025 г., 21:14
-- Версия сервера: 10.4.10-MariaDB
-- Версия PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `my_shop+edit+session`
--

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT '\'\'',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'Иван Иванович Иванов', 'ivan@mail.ru', '+7-999-123-45-67', '', '2025-10-13 07:23:50'),
(2, 'Мария Петрова', 'maria@yandex.ru', '+7-999-765-43-21', '', '2025-10-13 07:23:50'),
(3, 'Петр Сидоров', 'petr@gmail.com', '+7-999-555-44-33', '', '2025-10-13 07:23:50'),
(4, 'Петров Петр Петрович', '234@sdd.ru', '+73454657', '', '2025-10-13 07:28:47'),
(5, 'Покупатель Tyny', '16635253@gmail.com', '', '', '2025-10-26 20:56:07');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `product_id`, `quantity`, `total`, `order_date`) VALUES
(1, 1, 4, 2, '72000.00', '2025-10-13 07:29:17'),
(2, 3, 1, 1, '55000.00', '2025-10-13 07:31:00'),
(3, 4, 2, 2, '3000.00', '2025-10-26 16:10:26'),
(4, 1, 2, 23, '34500.00', '2025-10-26 16:17:01'),
(7, 1, 1, 1, '55000.00', '2025-10-26 18:29:47'),
(6, 1, 3, 1, '3500.00', '2025-10-26 16:48:45'),
(8, NULL, 4, 1, '40000.00', '2025-10-26 19:38:17'),
(9, 4, 4, 1, '40000.00', '2025-10-26 20:49:46');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `created_at`) VALUES
(1, 'Ноутбук HP Pavilion', '55000.00', 'Игровой ноутбук с процессором Intel Core i5', '2025-10-13 07:23:50'),
(2, 'Мышь беспроводная', '1500.00', 'Беспроводная оптическая мышь', '2025-10-13 07:23:50'),
(3, 'Клавиатура механическая', '3500.00', 'Механическая клавиатура с RGB подсветкой', '2025-10-13 07:23:50'),
(4, 'Смарт-часы Apple Watch ', '40000.00', 'Смарт-часы ', '2025-10-13 07:28:19');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `customer_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `customer_id`, `created_at`) VALUES
(1, 'admin', 'admin@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, '2025-10-26 14:11:02'),
(2, 'user1', 'ivan@mail.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, '2025-10-26 14:11:02'),
(3, 'user2', 'maria@yandex.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 2, '2025-10-26 14:11:02'),
(4, 'user3', '123@gmail.com', '$2y$10$a4ovzAQMayvXAwsM7Kk90unkMaqXpe1a0OGKtTD8Uw1bicLFU35ja', 'user', 3, '2025-10-26 19:34:17'),
(5, 'user4', 'g72227111@gmail.com', '$2y$10$5s3xcdBUq9Ai5G5cb7u1/O3T2IWabULi8XDJigAIwmOyvcb92Qg.a', 'user', NULL, '2025-10-26 19:36:09'),
(6, 'sgu', '234@sdd.ru', '$2y$10$rfH9VE.AZc8sWoRHIRtGz.TYBnW030JTdIyJkikrSrJtFFdiIoccO', 'user', 4, '2025-10-26 20:31:08'),
(7, 'tyny', '16635253@gmail.com', '$2y$10$2nSL7gxUnTO8.0m5eUsLPurGq63PaB7ZeZnrcIADl8FHLoMNkQY9a', 'user', 5, '2025-10-26 20:56:07');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
