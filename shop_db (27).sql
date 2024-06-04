-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Июн 04 2024 г., 06:51
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`) VALUES
(1, 'admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2'),
(2, 'riko', '356a192b7913b04c54574d18c28d46e6395428ab'),
(3, 'aaaa', '011c945f30ce2cbafc452f39840f025693339c42');

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `quantity` int(10) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `size`) VALUES
(2, 4, 19, 1, '43'),
(4, 1003, 18, 1, '44'),
(6, 1003, 6, 1, 'default'),
(13, 3, 15, 1, '40');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `method`, `address`, `total_price`, `placed_on`, `payment_status`) VALUES
(17, 3, 'cash on delivery', 'Gaismas iela 4Stūnīši, Olaines pagasts, Olaines novads, LV-2127', 176, '2024-06-03', 'completed'),
(18, 3, 'cash on delivery', 'Gaismas iela 4Stūnīši, Olaines pagasts, Olaines novads, LV-2127', 151, '2024-06-04', 'completed'),
(19, 3, 'cash on delivery', 'Gaismas iela 4Stūnīši, Olaines pagasts, Olaines novads, LV-2127', 151, '2024-06-04', 'pending');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int(100) NOT NULL,
  `order_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `size`) VALUES
(29, 17, 14, 1, 130, '45'),
(30, 17, 14, 1, 130, '43'),
(31, 17, 14, 1, 130, '48'),
(32, 18, 15, 1, 210, '42'),
(33, 19, 15, 1, 210, '44');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(10) NOT NULL,
  `image_01` varchar(100) NOT NULL,
  `image_02` varchar(100) NOT NULL,
  `image_03` varchar(100) NOT NULL,
  `sizes` varchar(50) NOT NULL DEFAULT '',
  `discount` decimal(5,2) DEFAULT NULL,
  `model` varchar(50) NOT NULL,
  `outer_material` varchar(50) NOT NULL,
  `inner_material` varchar(50) NOT NULL,
  `outsole` varchar(50) NOT NULL,
  `color` varchar(30) NOT NULL,
  `designer` varchar(255) NOT NULL,
  `article_number` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`, `sizes`, `discount`, `model`, `outer_material`, `inner_material`, `outsole`, `color`, `designer`, `article_number`) VALUES
(14, 'running shoes', 'The GEL-KINSEI™ Blast shoes are designed for distance runners seeking a smooth stride. They keep your mind and body energized to achieve your training goals. An arrangement of GEL® technology and FF BLAST™ cushioning create a soft yet responsive step. After easing into the landing phase, the shoe propels you forward with a smooth transition. ', 130, 'image_32613394_30_620x757_0.webp', 'image_32613394_30_620x757_1.webp', 'image_32613394_30_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 55.00, 'GT Xpress 2', 'textile', 'textile', 'synthetic', 'Blue', 'Asics', '1011A997'),
(15, 'running shoes ', 'The GEL-KINSEI™ Blast shoes are designed for distance runners seeking a smooth stride. They keep your mind and body energized to achieve your training goals. An arrangement of GEL® technology and FF BLAST™ cushioning create a soft yet responsive step. After easing into the landing phase, the shoe propels you forward with a smooth transition. ', 210, 'image_32798289_10_620x757_0.jpg', 'image_32798289_10_620x757_1.webp', 'image_32798289_10_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 28.00, 'GEL-KINSEI BLAST', 'textile ', 'textile ', 'synthetic', 'Black', 'Asics', '1011B203'),
(16, 'Asics', 'The GEL-KINSEI™ Blast shoes are designed for distance runners seeking a smooth stride. They keep your mind and body energized to achieve your training goals. An arrangement of GEL® technology and FF BLAST™ cushioning create a soft yet responsive step. After easing into the landing phase, the shoe propels you forward with a smooth transition. ', 160, 'image_32798294_63_620x757_0.jpg', 'image_32798294_63_620x757_1.jpg', 'image_32798294_63_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 33.00, 'GEL-KINSEI BLAST', 'synthetic ', 'textile', 'rubber', 'Yellow', 'Asics', '1011B330'),
(17, 'running shoes', 'Twelve bigger, wider Cloud elements provide a larger sureface area underfoot for added stability while Zero-Gravity Foam offers cushioning yet keeps weight low. The Cloud elements are configured specifically to reduce inward rotation when landing. ', 170, 'image_33004662_36_620x757_0.webp', 'image_33004662_36_620x757_1.webp', 'image_33004662_36_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 41.00, 'Cloudflyer 4 ', 'synthetic', 'textile', 'synthetic', 'Blue', 'On', ' 71.98675'),
(18, 'running shoes', 'Twelve bigger, wider Cloud elements provide a larger sureface area underfoot for added stability while Zero-Gravity Foam offers cushioning yet keeps weight low. The Cloud elements are configured specifically to reduce inward rotation when landing.\r\n', 166, 'image_33004592_10_620x757_0.jpg', 'image_33004592_10_620x757_1.webp', 'image_33004592_10_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 33.00, 'Cloudflyer 4 ', 'synthetic', ' textile', 'synthetic', 'Black', 'On ', ' 41.99585'),
(19, ' running shoes', 'Twelve bigger, wider Cloud elements provide a larger sureface area underfoot for added stability while Zero-Gravity Foam offers cushioning yet keeps weight low. The Cloud elements are configured specifically to reduce inward rotation when landing.', 159, 'image_32995128_64_620x757_0.webp', 'image_32995128_64_620x757_1.jpg', 'image_32995128_64_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 28.00, 'CLOUDFLYER 4 ', 'synthetic', 'textile', 'synthetic', 'Orange', 'On', '61.99585'),
(20, 'running shoes', 'Twelve bigger, wider Cloud elements provide a larger sureface area underfoot for added stability while Zero-Gravity Foam offers cushioning yet keeps weight low. The Cloud elements are configured specifically to reduce inward rotation when landing.', 144, 'image_32995129_20_620x757_0.jpg', 'image_32995129_20_620x757_1.webp', 'image_32995129_20_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 12.00, ' Cloud X 3 AD', 'synthetic', 'textile', 'synthetic', 'Grey', 'On', '51.95585'),
(21, 'running shoes', 'Twelve bigger, wider Cloud elements provide a larger sureface area underfoot for added stability while Zero-Gravity Foam offers cushioning yet keeps weight low. The Cloud elements are configured specifically to reduce inward rotation when landing.', 122, 'image_32995032_20_620x757_0.jpg', 'image_32995032_20_620x757_1.jpg', 'image_32995032_20_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 15.00, ' Cloudnova', 'synthetic', ' textile', 'synthetic', 'White', 'On ', '33.99585'),
(22, 'running shoes', 'Stylish as ever, comfortable when the rubber meets the road and performance-driven for your desired pace, it&#39;s an evolution of a fan favourite that offers a soft, smooth ride.', 160, 'image_32893284_10_620x757_0.webp', 'image_32893284_10_620x757_1.webp', 'image_32893284_10_620x757_3.jpg', '39,40,41,42,43,44,45,46,47,48', 50.00, 'React Infinity Run FK 3', 'textile', 'textile', 'synthetic', 'Black', 'Nike ', '11155.21'),
(23, 'running shoes', 'Stylish as ever, comfortable when the rubber meets the road and performance-driven for your desired pace, an evolution of a fan favourite that offers a', 169, 'image_32926804_10_620x757_0.jpg', 'image_32926804_10_620x757_1.webp', 'image_32926804_10_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 45.00, 'REACT INFINITY RUN', 'textile', 'textile', 'synthetic', 'Black', 'Nike', '125555.12'),
(24, 'running shoes', 'Stylish as ever, comfortable when the rubber meets the road and performance-driven for your desired pace, an evolution of a fan favourite that offers.', 122, 'image_32885568_25_620x757_0.webp', 'image_32885568_25_620x757_1.jpg', 'image_32885568_25_620x757_3.jpg', '39,40,41,42,43,44,45,46,47,48', 32.00, 'Air Zoom Pegasus 40', ' textile', 'synthetic', 'synthetic', ' Ecru', 'Nike ', '3333.21'),
(25, 'running shoes ', 'Stylish as ever, comfortable when the rubber meets the road and performance-driven for your desired pace, an evolution of a fan favourite that offers.', 119, 'image_32021615_30_620x757_0.webp', 'image_32021615_30_620x757_1.webp', 'image_32021615_30_620x757_3.webp', '39,40,41,42,43,44,45,46,47,48', 15.00, 'AIR ZOOM PEGASUS 37', 'textile', 'textile', 'synthetic', 'Blue', 'Nike ', '33231.55'),
(26, 'running shoes', 'Lace up and take your run beyond the tarmac. These adidas Terrex trail running shoes are built for comfort and durability in rough terrain. ', 140, 'image_40004624_000008174_620x757_0.jpg', 'image_40004624_000008174_620x757_1.jpg', 'image_40004624_000008174_620x757_3.jpg', '39,40,41,42,43,44,45,46,47,48', 38.00, 'Terrex', 'textile', 'textile', 'synthetic', 'Mint', 'adidas ', '222.321'),
(27, 'running shoes', 'Lace up and take your run beyond the tarmac. These adidas Terrex trail running shoes are built for comfort and durability in rough terrain.', 220, 'image_32896626_40_620x757_0.jpg', 'image_32896626_40_620x757_1.jpg', 'image_32896626_40_620x757_3.jpg', '39,40,41,42,43,44,45,46,47,48', 40.00, 'Performance ', 'textile', 'synthetic', 'synthetic', 'Red', 'adidas ', '5555.771'),
(28, 'running shoes', 'Built for reliability over the long haul and sustained comfort over all distances, the 860 is a true go-to shoe. The Fresh Foam X 860v14 combines innovative Stability Plane technology with the pinnacle underfoot cushioning experience of Fresh Foam X.', 180, 'image_32745997_20_620x757_0.jpg', 'image_32745997_20_620x757_1.jpg', 'image_32745997_20_620x757_2.jpg', '39,40,41,42,43,44,45,46,47,48', 25.00, ' 880 v12', 'textile', 'textile', 'synthetic', 'White', 'New Balance ', '444.312');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `address`, `phone`) VALUES
(1, 'Artur', 'King', 'kingart4ur@GMAIL.COM', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', NULL, NULL),
(3, 'Kristaps', 'Fedosejevs', 'kristaps1@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Gaismas iela 4Stūnīši, Olaines pagasts, Olaines novads, LV-2127', '25991778'),
(4, 'Riko', 'Labs', 'RIKO1@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Latgales iela 240Latgales priekšpilsēta, Rīga, LV-1063', '25556678'),
(1001, 'riko', '', 'riko@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', NULL, NULL),
(1002, 'test3', 'test', 'tes3t@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', NULL, NULL),
(1003, 'test4', 'test', 'test4@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `product_id` int(100) NOT NULL,
  `size` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `size`) VALUES
(28, 3, 14, 'default'),
(29, 3, 15, 'default'),
(30, 3, 15, '41');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_ibfk_1` (`user_id`),
  ADD KEY `wishlist_ibfk_2` (`product_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- AUTO_INCREMENT для таблицы `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения внешнего ключа таблицы `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
