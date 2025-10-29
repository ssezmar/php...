-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 13 2025 г., 09:08
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
-- База данных: `shop_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `customers`
--

DROP TABLE IF EXISTS `customers`;
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `address`, `created_at`) VALUES
(1, 'Иван', 'Петров', 'ivan@example.com', '+79161234567', 'Москва, ул. Ленина, д. 1', '2025-10-12 13:57:26'),
(2, 'Мария', 'Сидорова', 'maria@example.com', '+79167654321', 'Санкт-Петербург, Невский пр., д. 100', '2025-10-12 13:57:26'),
(3, 'Сидор', 'Сидоров', '234@sdd.ru', '3454657', '', '2025-10-12 13:58:20');

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
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `product_id`, `quantity`, `total_amount`, `order_date`, `status`) VALUES
(1, 1, 1, 1, '89999.00', '2025-10-12 13:57:27', 'completed'),
(2, 2, 2, 2, '149998.00', '2025-10-12 13:57:27', 'pending'),
(3, 3, 2, 2, '149998.00', '2025-10-12 13:59:19', 'cancelled'),
(4, 3, 5, 3, '108000.00', '2025-10-12 14:15:56', 'pending'),
(6, 1, 2, 3, '224997.00', '2025-10-13 07:45:25', 'pending');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `created_at`) VALUES
(1, 'iPhone 14', 'Смартфон Apple iPhone 14', '89999.00', 'Смартфоны', '2025-10-12 13:55:40'),
(2, 'Samsung Galaxy S23', 'Смартфон Samsung Galaxy S23', '74999.00', 'Смартфоны', '2025-10-12 13:55:40'),
(3, 'MacBook Pro', 'Ноутбук Apple MacBook Pro 16\"', '199999.00', 'Ноутбуки', '2025-10-12 13:55:40'),
(4, 'Xiaomi 15T 256 ГБ', 'Смартфон Xiaomi 15T 256 ГБ Золотистый', '50000.00', 'Смартфоны', '2025-10-12 14:08:55'),
(5, 'Смарт-часы Apple Watch ', 'Смарт-часы Apple Watch Series 10 46mm', '36000.00', 'Смарт-часы', '2025-10-12 14:15:19');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
