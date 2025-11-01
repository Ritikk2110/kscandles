-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2025 at 08:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kscandles`
--

-- --------------------------------------------------------

--
-- Table structure for table `abandoned_carts`
--

CREATE TABLE `abandoned_carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cart_data` text DEFAULT NULL,
  `notified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `abandoned_carts`
--

INSERT INTO `abandoned_carts` (`id`, `user_id`, `cart_data`, `notified`, `created_at`) VALUES
(13, 3, '{\"5\":1,\"6\":5}', 0, '2025-10-31 07:06:41'),
(14, 7, '{\"5\":1,\"6\":1}', 0, '2025-11-01 05:41:12');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@kscandles.in', '$2y$10$g7sUBgYxj9nSk8Rh7vWwLeqUqFJqD2GvPRkGZzvDhbAf8iEY0tLaW'),
(2, 'adminn@kscandles.in', '$2y$10$kqRIHvVavkHSvGTK.Wc19uv4RDJkj8pRhaXaLGOpdhPEBLdaTPWMu');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` int(11) NOT NULL,
  `upi_id` varchar(100) NOT NULL,
  `qr_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `upi_id`, `qr_image`) VALUES
(1, 'kr2060398@gmail.com', '../uploads/admin_qr/Colorful Brain Digital World Technology Logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_activity`
--

CREATE TABLE `cart_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_activity`
--

INSERT INTO `cart_activity` (`id`, `user_id`, `product_id`, `quantity`, `added_at`, `notified`) VALUES
(1, 3, 6, 2, '2025-10-31 05:07:07', 0),
(2, 3, 6, 3, '2025-10-31 05:07:08', 0),
(3, 3, 6, 4, '2025-10-31 05:07:08', 0),
(4, 3, 6, 5, '2025-10-31 05:07:10', 0),
(5, 3, 6, 6, '2025-10-31 05:07:11', 0),
(6, 3, 5, 2, '2025-10-31 05:07:18', 0),
(7, 3, 6, 5, '2025-10-31 07:02:32', 0),
(8, 4, 5, 2, '2025-10-31 10:33:55', 0),
(9, 4, 5, 3, '2025-10-31 10:33:57', 0),
(10, 4, 5, 4, '2025-10-31 10:33:57', 0),
(11, 4, 5, 5, '2025-10-31 10:33:58', 0),
(12, 4, 5, 6, '2025-10-31 10:33:58', 0),
(13, 4, 5, 7, '2025-10-31 10:33:59', 0),
(14, 4, 5, 8, '2025-10-31 10:33:59', 0),
(15, 4, 5, 9, '2025-10-31 10:34:00', 0),
(16, 4, 5, 10, '2025-10-31 10:34:00', 0),
(17, 4, 5, 11, '2025-10-31 10:34:01', 0),
(18, 4, 5, 12, '2025-10-31 10:34:01', 0),
(19, 4, 5, 13, '2025-10-31 10:34:01', 0),
(20, 4, 5, 14, '2025-10-31 10:34:02', 0),
(21, 4, 5, 15, '2025-10-31 10:34:02', 0),
(22, 4, 5, 16, '2025-10-31 10:34:03', 0),
(23, 4, 5, 17, '2025-10-31 10:34:03', 0),
(24, 4, 5, 18, '2025-10-31 10:34:04', 0),
(25, 4, 5, 19, '2025-10-31 10:34:04', 0),
(26, 4, 5, 20, '2025-10-31 10:34:04', 0),
(27, 4, 5, 21, '2025-10-31 10:34:05', 0),
(28, 4, 5, 22, '2025-10-31 10:34:05', 0),
(29, 4, 5, 23, '2025-10-31 10:34:06', 0),
(30, 4, 5, 24, '2025-10-31 10:34:06', 0),
(31, 4, 5, 25, '2025-10-31 10:34:07', 0),
(32, 4, 5, 26, '2025-10-31 10:34:07', 0),
(33, 4, 5, 27, '2025-10-31 10:34:08', 0),
(34, 4, 5, 28, '2025-10-31 10:34:09', 0),
(35, 4, 5, 29, '2025-10-31 10:34:11', 0),
(36, 4, 5, 30, '2025-10-31 10:34:12', 0),
(37, 4, 5, 31, '2025-10-31 10:34:15', 0),
(38, 4, 5, 32, '2025-10-31 10:34:19', 0),
(39, 4, 5, 31, '2025-10-31 10:34:22', 0),
(40, 4, 5, 1, '2025-10-31 11:55:49', 0),
(41, 5, 5, 2, '2025-10-31 15:09:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Scented Candles'),
(2, 'Decorative Candles'),
(3, 'Gift Sets'),
(4, 'freshnerss'),
(5, 'freshnerss'),
(6, 'home decor');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT 'Contact',
  `message` text NOT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `sent_at`) VALUES
(1, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloooo', '2025-10-30 14:24:29'),
(2, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloooo', '2025-10-30 14:45:16'),
(3, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloooo', '2025-10-30 14:51:15'),
(4, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloooo', '2025-10-30 14:51:40'),
(5, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'ritikkk', '2025-10-30 14:56:22'),
(6, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'ritikkk', '2025-10-30 14:56:47'),
(7, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'ritikkk', '2025-10-30 14:56:54'),
(8, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'hello', '2025-10-30 14:57:09'),
(9, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'hello', '2025-10-30 15:03:13'),
(10, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'hellooo', '2025-10-30 15:03:34'),
(11, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'hellooo', '2025-10-30 15:04:34'),
(12, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloo', '2025-10-30 15:04:50'),
(13, 'Ritik kumar', 'kr2060398@gmail.com', NULL, 'Contact', 'helloo', '2025-10-30 15:09:43'),
(21, 'Ritik kumar', 'kr2060398@gmail.com', '06388110321', 'Custom Order', 'dcfdf', '2025-10-31 14:04:57'),
(22, 'Sam', 'ritik.p2110@gmail.com', '3214565121', 'General Inquiry', 'scdvd', '2025-11-01 05:07:44');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `payment_status` varchar(50) DEFAULT 'Pending',
  `payment_proof` varchar(255) DEFAULT NULL,
  `utr_number` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` datetime DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(15) DEFAULT NULL,
  `landmark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `address`, `total`, `status`, `payment_status`, `payment_proof`, `utr_number`, `payment_method`, `created_at`, `order_date`, `phone`, `city`, `state`, `pincode`, `landmark`) VALUES
(2, 3, NULL, NULL, NULL, 2892.00, 'pending', 'Pending', NULL, NULL, 'COD', '2025-10-30 12:53:57', '2025-10-30 18:23:57', NULL, NULL, NULL, NULL, NULL),
(3, 3, 'Ritik kumar', 'ritik@kumar.com', '0', 200.00, 'Processing', 'Paid', 'uploads/payments/WhatsApp Image 2025-10-09 at 19.54.15_2e5991dc.jpg', '25569856265958', 'Manual', '2025-10-30 19:39:18', '2025-10-31 01:09:18', NULL, NULL, NULL, NULL, NULL),
(4, 3, 'Ritik kumar', 'ritik@kumar.com', '0', 324.00, NULL, 'pending', NULL, NULL, 'Manual', '2025-10-30 19:56:19', '2025-10-31 01:26:19', NULL, NULL, NULL, NULL, NULL),
(5, 3, 'shya,mmm', 'ritik@kumar.com', '0', 125.00, NULL, 'Pending', 'uploads/payments/1761855820_picture.png', '2658954885', 'Manual', '2025-10-30 20:20:51', '2025-10-31 01:50:51', NULL, NULL, NULL, NULL, NULL),
(6, 3, 'Rohannn', 'ritik@kumar.com', '0', 2598.00, NULL, 'Pending', 'uploads/payments/1761855892_—Pngtree—floral vintage retro golden corner_8539576.png', '35656595965', 'Manual', '2025-10-30 20:24:23', '2025-10-31 01:54:23', NULL, NULL, NULL, NULL, NULL),
(7, 3, 'Ritik kumar', 'ritik@kumar.com', '0', 2548.00, NULL, 'Pending', 'uploads/payments/1761856044_png-transparent-golden-ornamental-corner-thumbnail.png', '32256523263', 'Manual', '2025-10-30 20:27:06', '2025-10-31 01:57:06', NULL, NULL, NULL, NULL, NULL),
(8, 3, 'Ritik kumar', 'ritik@kumar.com', '0', 324.00, NULL, 'Pending', 'uploads/payments/1761857489_ritik_avtar.jpg', '25569856265958', 'Manual', '2025-10-30 20:51:15', '2025-10-31 02:21:15', NULL, NULL, NULL, NULL, NULL),
(9, 3, 'Ritik kumar', 'ritik@kumar.com', '0', 200.00, 'Shipped', 'Paid', 'uploads/payments/1761887295_Gradient Colorful Minimalist Coming  Soon Banner.png', '25569856265958', 'Manual', '2025-10-31 05:07:43', '2025-10-31 10:37:43', NULL, NULL, NULL, NULL, NULL),
(10, 4, 'Ritik kumar', 'smart@things.com', '0', 775.00, 'pending', 'Pending', 'uploads/payments/1761911307_default-avatar.png', '2658954885', 'Manual', '2025-10-31 11:00:51', '2025-10-31 16:30:51', NULL, NULL, NULL, NULL, NULL),
(11, 4, 'Ritik kumar', 'smart@things.com', '0', 25.00, 'pending', 'pending', NULL, NULL, 'Manual', '2025-10-31 11:56:37', '2025-10-31 17:26:37', NULL, NULL, NULL, NULL, NULL),
(12, 4, 'Ritik kumar', 'smart@things.com', 'jnjhj', 2548.00, 'Pending', 'Pending', NULL, NULL, 'Manual', '2025-10-31 12:14:08', '2025-10-31 17:44:08', NULL, 'Lucknow', 'Uttar Pradesh', '227202', NULL),
(13, 4, 'Ritik kumar', 'smart@things.com', 'Nyay vviharrr', 2573.00, 'Delivered', 'Failed', 'uploads/payments/1761919108_ana.jpg', '2582654588555255', 'Manual', '2025-10-31 13:56:46', '2025-10-31 19:26:46', NULL, 'Lucknow', 'Uttar Pradesh', '227202', NULL),
(14, 5, 'Ritik kumar', 'yash@gmail.com', 'Nyay vihar colony sitapur road lucknow\r\nHazrat Ganj', 75.00, 'Pending', 'Pending', NULL, NULL, 'Manual', '2025-10-31 15:14:34', '2025-10-31 20:44:34', NULL, 'oraii', 'Uttar Pradesh', '227202', NULL),
(15, 5, 'Ritik kumar', 'yash@gmail.com', 'Nyay vihar colony sitapur road lucknow\r\nHazrat Ganj', 50.00, 'Pending', 'Pending', 'uploads/payments/1761923828_WhatsApp Image 2025-10-08 at 00.27.12_58a7bd45.jpg', '52645666', 'Manual', '2025-10-31 15:16:52', '2025-10-31 20:46:52', NULL, 'Select', 'Uttar Pradesh', '227202', NULL),
(16, 5, 'Ritik kumar', 'yash@gmail.com', 'Nyay vihar colony sitapur road lucknow\r\nHazrat Ganj', 149.00, 'Pending', 'Pending', 'uploads/payments/1761925019_default-avatar.png', '5465545456', 'Manual', '2025-10-31 15:36:09', '2025-10-31 21:06:09', NULL, 'Lucknow', 'Uttar Pradesh', '227202', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `product_name`) VALUES
(1, 2, 2, 3, 399.00, 'Vanilla Dream Candle'),
(2, 2, 3, 4, 299.00, 'Decorative Pillar Candle'),
(3, 2, 1, 1, 499.00, 'Lavender Bliss Candle'),
(4, 3, 5, 8, 25.00, 'candle fragg'),
(5, 4, 5, 1, 25.00, 'candle fragg'),
(6, 4, 3, 1, 299.00, 'Decorative Pillar Candle'),
(7, 5, 5, 5, 25.00, 'candle fragg'),
(8, 6, 5, 2, 25.00, 'candle fragg'),
(9, 6, 4, 1, 2548.00, 'Lost and Found Information System'),
(10, 7, 4, 1, 2548.00, 'Lost and Found Information System'),
(11, 8, 5, 1, 25.00, 'candle fragg'),
(12, 8, 3, 1, 299.00, 'Decorative Pillar Candle'),
(13, 9, 5, 2, 25.00, 'candle fragg'),
(14, 9, 6, 6, 25.00, 'luminous'),
(15, 10, 5, 31, 25.00, 'candle fragg'),
(16, 11, 5, 1, 25.00, 'candle fragg'),
(17, 12, 4, 1, 2548.00, 'Lost and Found Information System'),
(18, 13, 4, 1, 2548.00, 'Lost and Found Information System'),
(19, 13, 6, 1, 25.00, 'luminous'),
(20, 14, 5, 2, 25.00, 'candle fragg'),
(21, 14, 6, 1, 25.00, 'luminous'),
(22, 15, 6, 1, 25.00, 'luminous'),
(23, 15, 5, 1, 25.00, 'candle fragg'),
(24, 16, 5, 2, 25.00, 'candle fragg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `avg_rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `avg_rating`, `total_reviews`, `stock`, `description`, `image`, `created_at`) VALUES
(1, 'Lavender Bliss Candle', 1, 499.00, 0.00, 0, 49, 'A soothing lavender-scented candle for relaxation.', 'candle1.jpeg', '2025-10-30 08:50:25'),
(2, 'Vanilla Dream Candle', 1, 399.00, 0.00, 0, 67, 'A sweet and creamy vanilla fragrance for calm evenings.', 'candle2.jpeg', '2025-10-30 08:50:25'),
(3, 'Decorative Pillar Candle', 2, 299.00, 3.75, 4, 24, 'Beautiful pillar candle perfect for home décor.', 'candle3.jpeg', '2025-10-30 08:50:25'),
(4, 'Lost and Found Information System', 3, 258.00, 3.00, 1, 19996, 'looks good', '1761922216_442488f1ab07.jpeg', '2025-10-30 14:42:09'),
(5, 'candle fragg', 4, 25.00, 0.00, 0, 164, 'fragxnxxxx', '1761845142_ebbc2b359f10.jpeg', '2025-10-30 17:05:55'),
(6, 'luminous', 6, 25.00, 0.00, 0, 1991, 'a little fragrence xyzzz', '1761845097_9eb1a240db9d.jpeg', '2025-10-30 17:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `name`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 1, NULL, 5, 'Amazing fragrance and long-lasting burn!', '2025-10-30 08:50:26'),
(2, 3, NULL, 'Ritik kumar', 5, 'yess finee', '2025-10-30 14:10:11'),
(3, 3, NULL, 'Ritik kumar', 5, 'yess finee', '2025-10-30 14:11:39'),
(4, 4, NULL, 'Ritik kumar', 3, 'goooddd', '2025-10-30 14:42:50'),
(5, 3, NULL, 'Ritik kumar', 4, 'product is very nice surely you can buy it', '2025-10-30 16:26:26'),
(6, 3, NULL, 'Ritik kumar', 1, 'nooo', '2025-10-30 16:26:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `phone`, `created_at`) VALUES
(1, 'Ritik Kumar', 'ritik@example.com', '$2y$10$g7sUBgYxj9nSk8Rh7vWwLeqUqFJqD2GvPRkGZzvDhbAf8iEY0tLaW', 'Lucknow, India', '9876543210', '2025-10-30 08:50:25'),
(2, 'Admin', 'admin@kscandles.in', '$2y$10$g7sUBgYxj9nSk8Rh7vWwLeqUqFJqD2GvPRkGZzvDhbAf8iEY0tLaW', 'Server Office', '9999999999', '2025-10-30 08:50:25'),
(3, 'Ritik Kumarrr', 'ritik@kumar.com', '$2y$10$n2qPpNTprfLtqxw75FQb7e6KHhNd9iUhmtPtPAnwlqVRMKIzPBqXS', NULL, NULL, '2025-10-30 12:41:19'),
(4, 'Smart Things Finder System', 'smart@things.com', '$2y$10$GAEZKJN4oHVGvyMV.cU0uOtiMaEpj6eAlZd9FePk4qFGA7x6jKLCS', NULL, NULL, '2025-10-31 08:07:15'),
(5, 'yash', 'yash@gmail.com', '$2y$10$BdPji8a4O0e3OGGRY1y7ku3sJzJRwBwCp551y5Rt5LIDdRbP6fTD.', NULL, NULL, '2025-10-31 15:08:27'),
(7, 'Sam', 'ritik@kumarr.com', '$2y$10$F.oZ5DJRbOwhad.fmvV4Xe4WmP/Q7h04uTj0LC0Q9cfHOW6TLWD.O', 'yess', '3214565121', '2025-10-31 22:05:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abandoned_carts`
--
ALTER TABLE `abandoned_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `cart_activity`
--
ALTER TABLE `cart_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abandoned_carts`
--
ALTER TABLE `abandoned_carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_activity`
--
ALTER TABLE `cart_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_activity`
--
ALTER TABLE `cart_activity`
  ADD CONSTRAINT `cart_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_activity_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
