-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 11 2025 г., 18:23
-- Версия сервера: 8.0.30
-- Версия PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Aquaria`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'Аквариумные рыбки', 'fish.png'),
(2, 'Аквариумы и тумбы', 'aquarium.png'),
(3, 'Корм для рыбок', 'food.png'),
(4, 'Живые растения', 'plants.png'),
(5, 'Оборудование', 'equipment.png'),
(6, 'Автокормушка', 'feeder.png'),
(7, 'Природные камни', 'stones.png');

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `added_at`) VALUES
(1, 3, 18, '2025-05-15 16:44:56'),
(6, 3, 13, '2025-05-17 12:03:26');

-- --------------------------------------------------------

--
-- Структура таблицы `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `phone`, `submitted_at`) VALUES
(1, 'Артур', '+7 (999) 999-99-99', '2025-05-06 10:52:39');

-- --------------------------------------------------------

--
-- Структура таблицы `loyalty_cards`
--

CREATE TABLE `loyalty_cards` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `card_number` varchar(20) NOT NULL,
  `balance` decimal(10,2) DEFAULT '0.00',
  `first_purchase` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `discount_rate` decimal(3,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `loyalty_cards`
--

INSERT INTO `loyalty_cards` (`id`, `user_id`, `name`, `card_number`, `balance`, `first_purchase`, `created_at`, `discount_rate`) VALUES
(2, 1, 'Иван Иванов', '1234567890123456', '0.00', 0, '2025-05-20 00:03:56', '0.15'),
(3, 9, 'Artur', 'AQUA-A069D953', '200.00', 0, '2025-06-08 15:17:24', '0.15'),
(4, 15, 'Karapet', 'AQUA-A4C198A1', '200.00', 0, '2025-06-08 20:12:49', '0.15');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
  `final_price` decimal(10,2) NOT NULL,
  `delivery_address` text NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('собирается','в пути','доставлен','отменён') NOT NULL DEFAULT 'собирается'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `discount`, `final_price`, `delivery_address`, `payment_method`, `created_at`, `status`) VALUES
(1, 4, '400.00', '0.00', '950.00', 'ул. Тестовая, д. 1', 'card', '2025-05-14 14:40:05', 'в пути'),
(2, 4, '550.00', '0.00', '950.00', 'ул. Тестовая, д. 1', 'card', '2025-05-14 14:40:05', 'собирается'),
(3, 4, '9600.00', '0.00', '9600.00', 'Москва, д15 к5 подъезд 1', 'card', '2025-05-14 16:00:28', 'собирается'),
(4, 4, '3200.00', '0.00', '3200.00', 'Москва', 'card', '2025-05-14 16:03:39', 'доставлен'),
(5, 3, '21950.00', '0.00', '21950.00', 'Moscov', 'card', '2025-05-15 13:08:17', 'собирается'),
(6, 9, '1600.00', '0.00', '1600.00', 'xzczx', 'card_on_delivery', '2025-06-08 15:05:54', 'собирается'),
(7, 15, '1035.00', '0.00', '1035.00', 'czxczxczx', 'cash', '2025-06-08 17:03:33', 'собирается'),
(8, 15, '1200.00', '0.00', '1200.00', 'Пожалуйста, заполните все поля корректно.', 'card_on_delivery', '2025-06-08 17:27:51', 'собирается'),
(9, 9, '21950.00', '0.00', '21950.00', 'Russian Federation', 'card_on_delivery', '2025-06-09 16:21:40', 'собирается'),
(10, 9, '2200.00', '0.00', '2200.00', 'Октябрьский проспект д 64 кв 11', 'cash', '2025-06-09 16:32:47', 'собирается'),
(11, 9, '109750.00', '0.00', '109750.00', 'Moscow', 'cash', '2025-06-09 16:37:27', 'собирается'),
(12, 9, '110050.00', '0.00', '110050.00', 'Volgograd', 'card_on_delivery', '2025-06-09 16:39:48', 'собирается'),
(13, 9, '4900.00', '0.00', '4900.00', 'dfvdf', 'cash', '2025-06-10 12:40:56', 'собирается');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 3, 22, 3, '3200.00'),
(2, 4, 22, 1, '3200.00'),
(3, 5, 6, 1, '21950.00'),
(4, 6, 1, 4, '400.00'),
(5, 7, 15, 3, '345.00'),
(6, 8, 1, 3, '400.00'),
(7, 9, 6, 1, '21950.00'),
(8, 10, 2, 4, '550.00'),
(9, 13, 18, 1, '4900.00');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` varchar(77) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category_id`) VALUES
(1, 'Лялиус-неоновый', '400.00', 'Размер в продаже, см: 4,0+/-', 'fish-1.png', 1),
(2, 'Радужница неоновая', '550.00', 'Размер в продаже, см: 3,5+/-', 'fish-2.png', 1),
(3, 'Меланотения Прекрасная', '600.00', 'Размер в продаже, см: 4,0+/-\r\n', 'fish-3.png', 1),
(4, 'Псевдомугил Гертруды', '300.00', 'Размер в продаже, см: 5,0+/-', 'fish-4.png', 1),
(5, 'Аквариум AQUAEL SHRIMP', '19900.00', 'Компактный аквариум для креветок.', 'akv-1.png', 2),
(6, 'AQUAEL Aквариум AQUA4', '21950.00', 'Аквариум для новичков и профессионалов.', 'akv-2.png', 2),
(7, 'AQUAEL SHRIMP SET', '15400.00', 'Набор для содержания креветок.', 'akv-3.png', 2),
(8, 'PRIME Аквариум 15л', '7790.00', 'Аквариум на 15 литров, для дома и офиса.', 'akv-4.png', 2),
(9, 'Витамины для рыб', '450.00', 'Витамины для поддержания иммунитета рыб.', 'korm-1.png', 3),
(10, 'Корм для петушков', '115.00', 'Специальный корм для петушков.', 'korm-2.png', 3),
(11, 'Корм йена', '400.00', 'Корм для тропических рыб.', 'korm-3.png', 3),
(12, 'Tetra Micro Pellets', '3450.00', 'Мелкий корм для мелких рыб.', 'korm-4.png', 3),
(13, 'Альтернантера Бетзикиана', '225.00', 'Растение с бордовыми листьями.', 'flow-1.png', 4),
(14, 'Альтернантера Рейнека', '300.00', 'Узколистное растение для аквариума.', 'flow-2.png', 4),
(15, 'Альтернантера Кардинальская', '345.00', 'Яркое растение для заднего плана.', 'flow-3.png', 4),
(16, 'Несея Педицеллата', '240.00', 'Растение для оформления переднего плана.', 'flow-4.png', 4),
(17, 'EHEIM ECCO PRO', '22300.00', 'Надежный внешний фильтр.', 'oborud-1.png', 5),
(18, 'Внешний фильтр', '4900.00', 'Фильтр для кристально чистой воды.', 'oborud-2.png', 5),
(19, 'Помпа течения', '650.00', 'Помпа для усиления течения.', 'oborud-3.png', 5),
(20, 'Помпа течения 3,5 Вт', '1350.00', 'Компактная помпа 3,5 Вт.', 'oborud-4.png', 5),
(21, 'Автоматическая кормушка', '2100.00', 'Устройство для автоматической кормёжки.', 'autokorm-1.png', 6),
(22, 'Автокормушка', '3200.00', 'Простая автокормушка с таймером.', 'autokorm-2.png', 6),
(23, 'EHEIM Автокормушка', '9450.00', 'Автоматическая кормушка EHEIM.', 'autokorm-3.png', 6),
(24, 'Boyu ZW-82 Автокормушка', '1950.00', 'Надёжная кормушка ZW-82.', 'autokorm-4.png', 6),
(25, 'GLOXY Камень натуральный', '1450.00', 'Натуральный камень для декора.', 'stone-1.png', 7),
(26, 'PRIME Декорация природная', '450.00', 'Декор из природного камня.', 'stone-2.png', 7),
(27, 'Камень декоративный', '400.00', 'Искусственный декоративный камень.', 'stone-3.png', 7),
(28, 'PRIME Декорация природная', '400.00', 'Камень для оформления аквариума.', 'stone-4.png', 7);

-- --------------------------------------------------------

--
-- Структура таблицы `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','in_progress','resolved') DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `support_requests`
--

INSERT INTO `support_requests` (`id`, `user_id`, `message`, `created_at`, `status`) VALUES
(1, 9, 'ПРИВЕТ ВСЕ БОМБА', '2025-06-08 16:07:25', 'new'),
(2, 16, 'ОЧЕНЬ КРУТО', '2025-06-08 19:41:44', 'new');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `profile_image` varchar(255) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `profile_image`, `token`, `is_confirmed`) VALUES
(1, 'Art', 'gigant@mail.ru', '123456', 'user', '2025-04-09 12:33:26', NULL, NULL, 0),
(3, 'Archee', 'asem2005@mail.ru', '$2y$10$xNXiBHBqQMUpmMMer9MNiuBpCSciU0bpcZ.TOhXwJvHsdcocSdG1y', 'user', '2025-05-13 16:52:59', 'uploads/photo_2025-03-21_12-23-48.jpg', NULL, 1),
(4, 'Paxan', 'paxan-777@mail.ru', '$2y$10$dZpwdbZKb0QfVV0GaVI3o.jJc6FU1sK3nhJVTn68h6uNhJpC93rN2', 'user', '2025-05-14 17:33:41', 'uploads/photo_2025-03-21_12-24-54.jpg', NULL, 0),
(9, 'Artur', '', '$2y$10$zQxTAYGVnl6on4FVQtdQuevP3p26OZb0BRgFpwZoVbYJtzvqMM5UG', 'admin', '2025-05-26 20:35:31', NULL, '554c8d85a044e1c9765070b5f238cc2e', 1),
(10, 'Vacok', 'krasava@mail.ru', '$2y$10$RjGNhix7vMD0OeBUVZC5eu.d9r07rQQSbYmXlyu6oxZUSuxxWJGxK', 'user', '2025-06-08 19:14:52', NULL, '961cf4770c576576099a403f97206fb3', 0),
(15, 'Karapet', 'karapet200420@mail.ru', '$2y$10$UFT9LnfEQjPX.K/3BNH5KOpRIRfap/QrD84ILZVC8ZO5g3JxgAtD2', 'user', '2025-06-08 19:41:24', NULL, NULL, 1),
(16, 'Aftandir', 'akopanartur228@gmail.com', '$2y$10$0QsS/liFxvt7iRFzkf9AkeC58JEMl9BAeXo6Ryv/NTWwb5S0Uerj.', 'user', '2025-06-08 22:39:28', NULL, NULL, 1),
(19, 'Akopyan', 'xurshudyan-95@mail.ru', '$2y$10$SaLf/3VRwlri9ccfyHr4reBK5v62ciym0fDNR/SHrPY0KlJLz1FCO', 'user', '2025-06-10 14:25:46', NULL, NULL, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `loyalty_cards`
--
ALTER TABLE `loyalty_cards`
  ADD CONSTRAINT `loyalty_cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `support_requests`
--
ALTER TABLE `support_requests`
  ADD CONSTRAINT `support_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
