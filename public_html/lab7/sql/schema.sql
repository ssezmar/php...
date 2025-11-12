-- Создание БД
CREATE DATABASE IF NOT EXISTS simple_shop;
USE simple_shop;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица покупателей
CREATE TABLE IF NOT EXISTS customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица товаров
CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Таблица заказов
CREATE TABLE IF NOT EXISTS orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Вставка тестовых пользователей
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$QAQD5f5pZlUhTvGFCqN0wuU8NfuEaC97HQnc0RZ1YUmeFgTCQs7gC', 'admin'),
('user1', '$2y$10$QAQD5f5pZlUhTvGFCqN0wuU8NfuEaC97HQnc0RZ1YUmeFgTCQs7gC', 'user');

-- Вставка тестовых покупателей
INSERT INTO customers (name, email, phone) VALUES 
('Администратор', 'admin@example.com', '123-456-7890'),
('Иван Пользователь', 'user1@example.com', '098-765-4321');

-- Вставка тестовых товаров
INSERT INTO products (name, description, price) VALUES 
('Ноутбук', 'Мощный ноутбук для работы и развлечений', 50000.00),
('Мобильный телефон', 'Смартфон с отличной камерой', 30000.00),
('Планшет', 'Портативный планшет для чтения', 20000.00),
('Наушники', 'Беспроводные наушники', 5000.00),
('Смарт-часы', 'Спортивные смарт-часы', 8000.00);

-- Вставка тестовых заказов
INSERT INTO orders (customer_id, product_id, quantity, total_price) VALUES 
(1, 1, 1, 50000.00),
(1, 2, 2, 60000.00),
(2, 3, 1, 20000.00),
(2, 4, 2, 10000.00);
