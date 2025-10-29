-- База данных: simple_shop
-- Кодировка: utf8_general_ci

DROP DATABASE IF EXISTS simple_shop;
CREATE DATABASE simple_shop CHARACTER SET utf8 COLLATE utf8_general_ci;
USE simple_shop;

-- Таблица товаров
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Таблица покупателей
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Таблица заказов
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Вставка тестовых данных
INSERT INTO products (name, price, description) VALUES
('Ноутбук HP Pavilion', 55000.00, 'Игровой ноутбук с процессором Intel Core i5'),
('Мышь беспроводная', 1500.00, 'Беспроводная оптическая мышь'),
('Клавиатура механическая', 3500.00, 'Механическая клавиатура с RGB подсветкой');

INSERT INTO customers (name, email, phone) VALUES
('Иван Иванов', 'ivan@mail.ru', '+7-999-123-45-67'),
('Мария Петрова', 'maria@yandex.ru', '+7-999-765-43-21'),
('Петр Сидоров', 'petr@gmail.com', '+7-999-555-44-33');
