-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 29 2024 г., 07:57
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
(2, 'riko', '356a192b7913b04c54574d18c28d46e6395428ab');

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(35, 3, 6, 'Red Running shoes ', 85, 1, 'Light_Running_Shoes_Red.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `number` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` date NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(6, 3, 'Kristaps F', '25991778', 'kristaps1@gmail.com', 'paypal', 'flat no. RIGA, Mūkusalas iela 1, RIGA, Mūkusalas iela  1, RIGA, RIGA, Latvia - 1027', 'Red Running shoes  (85 x 1) - Black Running shoes (145 x 1) - Light Blue Running Shoes (105 x 1) - ', 335, '2024-03-29', 'pending'),
(7, 3, 'Normuds A', '25666711', 'Normuds33@gmail.com', 'credit card', 'flat no. Rīga, Gasmais iela, Rīga, Gasmais iela, Rīga, Rīgas, Latvia - 1015', 'Red Running shoes  (85 x 1) - ', 85, '2024-03-29', 'pending');

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
  `image_03` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `details`, `price`, `image_01`, `image_02`, `image_03`) VALUES
(4, 'Black Running shoes', 'Running shoes cushion your stride with soft foam to keep you running in comfort. Minimalist design fits in just about anywhere your day takes you.', 145, 'Light_Running_Shoes_Black.jpg', '288496.jpg', '288499.jpg'),
(6, 'Red Running shoes ', 'Running shoes cushion your stride with soft foam to keep you running in comfort. Minimalist design fits in just about anywhere your day takes you.', 85, 'Light_Running_Shoes_Red.jpg', 'Ultraboost_Light_Running_Shoes_Red_IE3042_02_standard_hover.jpg', 'Ultraboost_Light_Running_Shoes_Red_IE3042_03_standard.jpg'),
(7, 'Light Green Running Shoes', 'Running shoes cushion your stride with soft foam to keep you running in comfort. Minimalist design fits in just about anywhere your day takes you.\r\n', 145, 'Light_Running_Shoes_Light_Green.jpg', 'm_wp_64667f983b982a624a367de6.webp', 'm_wp_64667f9a3b982a624a367dfa.webp'),
(8, 'White Running Shoes', 'Running shoes cushion your stride with soft foam to keep you running in comfort. Minimalist design fits in just about anywhere your day takes you.', 145, 'Light_Running_Shoes_White.jpg', 'LW9EPYS_061712_7.webp', 'LW9EPYS_061712_9.webp'),
(12, 'Light Blue Running Shoes', 'Running shoes cushion your stride with soft foam to keep you running in comfort. Minimalist design fits in just about anywhere your day takes you.\r\n', 105, 'Light_Running_Shoes_Light_Blue.jpg', 'Screenshot_1.jpg', 'Screenshot_2.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'riko', 'riko@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220'),
(3, 'kristaps', 'kristaps1@gmail.com', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- --------------------------------------------------------

--
-- Структура таблицы `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(7, 1, 6, 'Viscose Tricot Relaxed Pant', 85, 'FGE40-201TRI_ETERNAL_VISCOSE_TRCOT_RELAXED_PANT_BLACK-Front_900x.webp'),
(9, 1, 8, 'Wool Hat', 55, 'FGE70-089WOL-019-O_SWOOLHATBLACKWOOLMOHAIR_1_900x.webp'),
(10, 1, 7, 'Fleece Zip Hoodie', 145, 'FGE50-025FLC_ETERNAL_FLEECE_ZIP_HOODIE_BLACK-Front_900x.webp'),
(14, 3, 7, 'Light Green Running Shoes', 145, 'Light_Running_Shoes_Light_Green.jpg');

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
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
