-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 10:40 AM
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
-- Database: `bsu_lab_assets`
--

-- --------------------------------------------------------

--
-- Table structure for table `lab_admin_notifications`
--

CREATE TABLE `lab_admin_notifications` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_admin_notifications`
--

INSERT INTO `lab_admin_notifications` (`id`, `reservation_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 'New Requisition (LAB-20260423-001): Student Justin Bieber requested a requisition for 2026-04-24 at .', 0, '2026-04-23 14:10:20'),
(2, 2, 'New Requisition (LAB-20260428-001): Student Geo Mar C. De Guzman requested a requisition for 2026-04-28 at .', 0, '2026-04-28 08:48:27'),
(3, 3, 'New Requisition (LAB-20260428-002): Student Greg Tomco requested a requisition for 2026-04-28 at .', 0, '2026-04-28 09:59:36'),
(4, 4, 'New Requisition (LAB-20260428-003): Student Jerome Cabral requested a requisition for 2026-04-28 at .', 0, '2026-04-28 11:24:55'),
(5, 5, 'New Requisition (LAB-20260429-001): Student Gomari C. De Guzman requested a requisition for 2026-04-29 at .', 0, '2026-04-29 10:56:29'),
(6, 6, 'New Requisition (LAB-20260504-001): Student Michael Jackson requested a requisition for 2026-05-04 at .', 0, '2026-05-04 16:13:16'),
(7, 7, 'New Requisition (LAB-20260505-001): Student Geo Mar C. De Guzman requested a requisition for 2026-05-05 at .', 0, '2026-05-05 10:59:27'),
(8, 8, 'New Requisition (LAB-20260507-001): Student Juan Dela Cruz requested a requisition for 2026-05-07 at .', 0, '2026-05-07 08:37:16'),
(9, 9, 'New Requisition (LAB-20260507-002): Student Michael Jordan requested a requisition for 2026-05-08 at .', 0, '2026-05-07 08:38:33');

-- --------------------------------------------------------

--
-- Table structure for table `lab_admin_users`
--

CREATE TABLE `lab_admin_users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_admin_users`
--

INSERT INTO `lab_admin_users` (`id`, `name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Administrator', 'admin', 'admin@lams.edu', '$2y$10$RGRC79mJ.q.w7qDGe715T.fVPOxD2/bCJngSBsd.cOT.qr.MNCOhK', '2026-03-30 03:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `lab_categories`
--

CREATE TABLE `lab_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_categories`
--

INSERT INTO `lab_categories` (`id`, `name`, `description`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Hot Kitchen Tools', NULL, 1, 1, '2026-03-25 07:54:54'),
(2, 'Cold Kitchen Tools', NULL, 2, 1, '2026-03-25 07:54:54'),
(3, 'Food & Beverage Service', NULL, 3, 1, '2026-03-25 07:54:54'),
(4, 'Linens', NULL, 4, 1, '2026-03-25 07:54:54'),
(5, 'Laundry Tools & Linens', NULL, 5, 1, '2026-03-25 07:54:54');

-- --------------------------------------------------------

--
-- Table structure for table `lab_items`
--

CREATE TABLE `lab_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `unit` varchar(50) DEFAULT 'piece',
  `total_quantity` int(11) NOT NULL DEFAULT 0,
  `available_quantity` int(11) NOT NULL DEFAULT 0,
  `min_threshold` int(11) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `acquisition_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_items`
--

INSERT INTO `lab_items` (`id`, `category_id`, `item_name`, `unit`, `total_quantity`, `available_quantity`, `min_threshold`, `image_path`, `description`, `acquisition_date`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Ice Cream Scooper (2\')', 'piece', 2, 2, 0, 'item_1774928270_489.png', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-23 06:06:15'),
(3, 1, 'Ice Cream Scooper (6\')', 'piece', 1, 1, 0, 'item_1774926208_323.jpg', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-23 06:06:15'),
(4, 1, 'Ice Cream Scooper (8\')', 'piece', 4, 4, 0, 'item_1774926217_700.jpg', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-23 06:06:15'),
(5, 1, 'Air fryer', 'piece', 1, 1, 0, 'item_1774924235_363.jpg', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-05-04 00:45:55'),
(6, 1, 'Blender', 'piece', 1, 0, 0, 'item_1774928610_404.png', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-05-07 00:47:17'),
(7, 1, 'Can opener', 'piece', 1, 1, 0, 'item_1774928704_539.png', NULL, NULL, 1, '2026-03-25 07:55:14', '2026-05-05 00:14:10'),
(8, 1, 'Casserole (Big)', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(9, 1, 'CASSEROLE (31\")', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(10, 1, 'CASSEROLE (34\")', 'piece', 8, 8, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(11, 1, 'CASSEROLE (36\")', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(12, 1, 'Chinese Wok Set', 'set', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 02:19:48'),
(13, 1, 'Chopping board', 'piece', 15, 15, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(14, 1, 'CONE STRAINER (L) 18CM', 'piece', 8, 8, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(15, 1, 'CONE STRAINER (S) 16CM', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(16, 1, 'Corer', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(17, 1, 'Colander (S)', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(18, 1, 'Colander (B)', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(19, 1, 'Deep Fryer (Single)', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(20, 1, 'Deep Fryer (Double)', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(21, 1, 'Digital weighing scale', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(22, 1, 'Electric oven', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(23, 1, 'Electric weighing scale', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(24, 1, 'Fish poucher', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(25, 1, 'Food processor', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(26, 1, 'Food tray', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(27, 1, 'Grater', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(28, 1, 'Hasaan', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(29, 1, 'Heavy duty stove', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(30, 1, 'Knives', 'piece', 25, 25, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-29 01:09:34'),
(31, 1, 'Ladle stainless', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-29 01:09:34'),
(32, 1, 'Mandolino (Cutter)', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(33, 1, 'Measuring cups', 'set', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(34, 1, 'Meat grinder', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(35, 1, 'Meat slicer', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(36, 1, 'Non-stick pan', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(37, 1, 'Oven toaster', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(38, 1, 'Paellera', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(39, 1, 'Pan (L)', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(40, 1, 'Pan (M)', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(41, 1, 'Pan (S)', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(42, 1, 'Pasta maker', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(43, 1, 'Peeler', 'piece', 0, 0, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(44, 1, 'Piping tips set', 'set', 6, 6, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(45, 1, 'Pizza cutter', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(46, 1, 'Pocket thermometer', 'piece', 0, 0, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(47, 1, 'Pressure cooker', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(48, 1, 'Rolling pin', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 00:36:29'),
(49, 1, 'Scissors', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(50, 1, 'Sifters', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(51, 1, 'Soup Ladle', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(52, 1, 'Spatula stainless', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(53, 1, 'Stainless Cookware set', 'set', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(54, 1, 'Stainless steel rolling pan', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(55, 1, 'Stand mixer', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(56, 1, 'Steak hammer', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(57, 1, 'Strainer', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(58, 1, 'Turner', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(59, 1, 'WIRE WHISK (10\")', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(60, 1, 'WIRE WHISK (11\")', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(61, 1, 'WIRE WHISK (12\")', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(62, 1, 'WIRE WHISK (14\")', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(63, 1, 'Wok (Big)', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(64, 1, 'Wooden spoon', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(65, 1, 'Working table', 'piece', 6, 6, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(66, 1, 'Sink', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(67, 1, '2 Door Chiller and Freezer', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 02:19:48'),
(68, 1, 'Industrial Oven', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(69, 1, 'Gas range with Oven', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(70, 1, 'Cabinet wood Drawer 2 Door', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 01:32:01'),
(71, 3, 'Ashtray', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-05-04 01:34:43'),
(72, 3, 'Bar stirrer', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(73, 3, 'Beer Mug', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 00:30:04'),
(74, 3, 'Beer tower', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(75, 3, 'Bowl', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(76, 3, 'Bread knife', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 00:30:04'),
(77, 3, 'Butter knife', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(78, 3, 'Center piece', 'piece', 8, 8, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(79, 3, 'Chaffing Dish (Double)', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(80, 3, 'Chaffing dish (Single)', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(81, 3, 'Chaffing Dish (Roll-up)', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(82, 3, 'Clear Bowl Big', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(83, 3, 'Cocktail fork', 'piece', 22, 22, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(84, 3, 'Coffee cup', 'piece', 157, 157, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(85, 3, 'Coffee grinder', 'piece', 0, 0, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(86, 3, 'Coffee Maker', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(87, 3, 'Coffee pitcher', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(88, 3, 'Cooler', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(89, 3, 'Dessert fork', 'piece', 70, 70, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(90, 3, 'Dessert spoon', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(91, 3, 'Dinner Fork', 'piece', 77, 77, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(92, 3, 'Dinner knife', 'piece', 142, 142, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(93, 3, 'Dinner Plate 10.5\'', 'piece', 60, 60, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-30 01:32:23'),
(94, 3, 'Dinner Plate 10\'', 'piece', 57, 57, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(95, 3, 'Dinner Plate 6.5\'', 'piece', 41, 41, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(96, 3, 'Dinner Plate 6\'', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(97, 3, 'Dinner Plate 7\'', 'piece', 41, 41, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(98, 3, 'Dinner Plate 9.5\'', 'piece', 105, 105, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(99, 3, 'Dinner Plate 9\'', 'piece', 20, 20, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(100, 3, 'Dinner spoon', 'piece', 20, 20, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(101, 3, 'Electric kettle', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(102, 3, 'Fish Fork', 'piece', 40, 40, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(103, 3, 'Fish knives', 'piece', 22, 22, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(104, 3, 'Fork steak', 'piece', 20, 20, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(105, 3, 'Goblet', 'piece', 57, 57, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(106, 3, 'Halo halo glass', 'piece', 8, 8, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(107, 3, 'Highball', 'piece', 207, 207, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(108, 3, 'Ice bucket', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(109, 3, 'Ice tong', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(110, 3, 'Monkey dish', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(111, 3, 'Oval Plate', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(112, 3, 'Oval Plate with design', 'piece', 6, 6, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(113, 3, 'Perculator', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(114, 3, 'Pilsner', 'piece', 36, 36, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(115, 3, 'Rock glass', 'piece', 19, 19, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(116, 3, 'Salad Plate', 'piece', 42, 42, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(117, 3, 'Sauce boat', 'piece', 12, 12, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(118, 3, 'Saucer', 'piece', 161, 161, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(119, 3, 'Serving fork', 'piece', 9, 9, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(120, 3, 'Serving spoon', 'piece', 19, 19, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(121, 3, 'Shot glass', 'piece', 39, 39, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(122, 3, 'Show plate', 'piece', 22, 22, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(123, 3, 'Soup bowl ceramics', 'piece', 65, 65, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(124, 3, 'Soup bowl with design', 'piece', 30, 30, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(125, 3, 'Soup tourine', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(126, 3, 'Soy dish', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(127, 3, 'Steak knives', 'piece', 23, 23, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(128, 3, 'Teacup', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(129, 3, 'Teacup clear', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(130, 3, 'Teacup pitcher', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(131, 3, 'Tea Saucer', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(132, 3, 'Teaspoon', 'piece', 50, 50, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 01:49:21'),
(133, 3, 'Tongs', 'piece', 9, 9, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(134, 3, 'Water pitcher', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(135, 3, 'Wine glass', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(136, 4, 'Table Napkin Red', 'piece', 119, 119, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(137, 4, 'Table Napkin Beige', 'piece', 105, 105, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(138, 4, 'Table Napkin Brown', 'piece', 55, 55, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(139, 4, 'Table Napkin Grey', 'piece', 64, 64, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(140, 4, 'Table Napkin Orange', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(141, 4, 'Table Napkin Red Velvet', 'piece', 4, 4, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(142, 4, 'Table Cloth Skirted', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(143, 4, 'Top Cloth Rectangular Green', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(144, 4, 'Top Cloth Rectangular Yellow', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(145, 4, 'Top Cloth White', 'piece', 7, 7, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(146, 4, 'Underliner White', 'piece', 9, 9, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(147, 4, 'Table Napkin Yellow', 'piece', 39, 39, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(148, 5, 'Flat sheet Single', 'piece', 24, 24, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(149, 5, 'Flat sheet Double', 'piece', 24, 24, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(150, 5, 'Bed linen Single', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(151, 5, 'Bed linen Double', 'piece', 20, 20, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(152, 5, 'Pillow', 'piece', 129, 129, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(153, 5, 'Duvet filler with blue print', 'piece', 54, 54, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(154, 5, 'Duvet Filler White', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(155, 5, 'Duvet Cover White', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(156, 5, 'Pillow', 'piece', 129, 129, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(157, 5, 'Mattress protector', 'piece', 5, 5, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(158, 5, 'Blanket', 'piece', 3, 3, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(159, 5, 'Robes', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(160, 5, 'Bath towel', 'piece', 50, 50, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-05-05 03:00:49'),
(161, 5, 'Hand towel', 'piece', 10, 10, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(162, 5, 'Iron', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(163, 5, 'Steam Iron', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(164, 5, 'Iron Board', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-25 07:55:14'),
(165, 5, 'Automatic Washing machine', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(166, 5, 'Manual Washing machine', 'piece', 2, 2, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-03-30 02:28:56'),
(167, 2, 'Working Table', 'piece', 6, 6, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-20 06:58:41'),
(168, 2, 'Freezer', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 00:30:04'),
(169, 2, 'Refrigerator', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 00:30:04'),
(170, 2, 'Chiller', 'piece', 1, 1, 0, NULL, NULL, NULL, 1, '2026-03-25 07:55:14', '2026-04-28 00:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `lab_item_logs`
--

CREATE TABLE `lab_item_logs` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `change_type` varchar(20) DEFAULT NULL,
  `quantity_change` int(11) NOT NULL,
  `previous_quantity` int(11) NOT NULL,
  `new_quantity` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `is_disposal` tinyint(1) DEFAULT 0,
  `disposal_reason` varchar(255) DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_item_logs`
--

INSERT INTO `lab_item_logs` (`id`, `item_id`, `change_type`, `quantity_change`, `previous_quantity`, `new_quantity`, `remarks`, `is_disposal`, `disposal_reason`, `performed_by`, `created_at`) VALUES
(1, 5, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(2, 6, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(3, 67, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(4, 73, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(5, 76, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(6, 168, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(7, 169, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(8, 170, '-', 1, 0, 0, 'Cooldown expired for LAB-20260423-001 (auto-returned +1)', 0, NULL, 1, '2026-04-28 00:30:04'),
(9, 5, '-', 1, 0, 0, 'Requisition LAB-20260428-001 completed (returned +1)', 0, NULL, 1, '2026-04-28 01:32:01'),
(10, 6, '-', 1, 0, 0, 'Requisition LAB-20260428-001 completed (returned +1)', 0, NULL, 1, '2026-04-28 01:32:01'),
(11, 12, '-', 2, 0, 0, 'Requisition LAB-20260428-001 completed (returned +2)', 0, NULL, 1, '2026-04-28 01:32:01'),
(12, 67, '-', 1, 0, 0, 'Requisition LAB-20260428-001 completed (returned +1)', 0, NULL, 1, '2026-04-28 01:32:01'),
(13, 70, '-', 1, 0, 0, 'Requisition LAB-20260428-001 completed (returned +1)', 0, NULL, 1, '2026-04-28 01:32:01'),
(14, 5, '-', 1, 0, 0, 'Requisition LAB-20260428-002 completed (returned +1)', 0, NULL, 1, '2026-04-28 02:19:48'),
(15, 6, '-', 1, 0, 0, 'Requisition LAB-20260428-002 completed (returned +1)', 0, NULL, 1, '2026-04-28 02:19:48'),
(16, 12, '-', 3, 0, 0, 'Requisition LAB-20260428-002 completed (returned +3)', 0, NULL, 1, '2026-04-28 02:19:48'),
(17, 67, '-', 1, 0, 0, 'Requisition LAB-20260428-002 completed (returned +1)', 0, NULL, 1, '2026-04-28 02:19:48'),
(18, 30, '-', 7, 0, 0, 'Auto-restored items from LAB-20260428-003 (3h limit reached)', 0, NULL, 0, '2026-04-29 01:09:34'),
(19, 31, '-', 4, 0, 0, 'Auto-restored items from LAB-20260428-003 (3h limit reached)', 0, NULL, 0, '2026-04-29 01:09:34'),
(20, 5, '+', 1, 0, 0, 'new delivery', 0, '', 1, '2026-04-29 02:55:26'),
(21, 5, '-', 2, 0, 0, 'Cooldown expired for LAB-20260429-001 (auto-returned +2)', 0, NULL, 1, '2026-04-29 23:59:29'),
(22, 6, '-', 2, 0, 0, 'Cooldown expired for LAB-20260429-001 (auto-returned +2)', 0, NULL, 1, '2026-04-29 23:59:29'),
(23, 5, '-', 2, 0, 0, 'Cooldown expired for LAB-20260429-001 (auto-returned +2)', 0, NULL, 1, '2026-04-30 00:24:23'),
(24, 6, '-', 2, 0, 0, 'Cooldown expired for LAB-20260429-001 (auto-returned +2)', 0, NULL, 1, '2026-04-30 00:24:23'),
(25, 5, '-', 2, 0, 0, 'System correction: Fixed available quantity discrepancy (was 4, should be 2)', 0, NULL, 0, '2026-04-30 00:40:58'),
(26, 6, '-', 2, 0, 0, 'System correction: Fixed available quantity discrepancy (was 4, should be 2)', 0, NULL, 0, '2026-04-30 00:40:58'),
(27, 93, '-', 1, 0, 0, 'nawala', 0, '', 1, '2026-04-30 01:32:23'),
(28, 5, '-', 1, 0, 0, 'nawala', 0, '', 1, '2026-05-04 00:19:25'),
(29, 5, '-', 1, 0, 0, 'nawala', 0, '', 1, '2026-05-04 00:19:26'),
(30, 5, '+', 2, 0, 0, 'new delivery', 0, '', 1, '2026-05-04 00:45:43'),
(31, 5, '-', 1, 0, 0, 'nawala', 0, '', 1, '2026-05-04 00:45:55'),
(32, 71, '-', 3, 0, 0, 'nabasag', 0, '', 1, '2026-05-04 01:34:43'),
(33, 6, '-', 1, 0, 0, 'nasira ang isa', 0, '', 1, '2026-05-04 05:51:00'),
(34, 6, '-', 1, 0, 0, 'nasira ang isa', 0, '', 1, '2026-05-04 05:51:11'),
(35, 6, '+', 1, 0, 0, 'Newly Acquired', 0, '', 1, '2026-05-04 05:51:27'),
(36, 6, '-', 1, 0, 0, 'nasira', 0, '', 1, '2026-05-04 05:51:54'),
(37, 6, '+', 1, 0, 0, 'Bagong Delivered', 0, '', 1, '2026-05-04 05:52:10'),
(38, 7, '+', 1, 0, 0, 'New Delivered (Acquired: 2026-05-05)', 0, '', 1, '2026-05-05 00:13:16'),
(39, 7, '-', 1, 0, 0, 'nawala ang isa', 0, '', 1, '2026-05-05 00:14:10'),
(40, 160, '+', 10, 0, 0, 'Newly Acquired (Acquired: 2026-05-05)', 0, '', 1, '2026-05-05 02:58:08'),
(41, 160, '+', 25, 0, 0, 'Requisition LAB-20260505-001 completed (returned +25)', 0, NULL, 1, '2026-05-05 03:00:17'),
(42, 160, '-', 25, 0, 0, 'tinesting ko lang kanina mag add', 0, '', 1, '2026-05-05 03:00:49');

-- --------------------------------------------------------

--
-- Table structure for table `lab_reservations`
--

CREATE TABLE `lab_reservations` (
  `id` int(11) NOT NULL,
  `reservation_no` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_contact` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `course_section` varchar(100) NOT NULL,
  `station` varchar(100) DEFAULT NULL,
  `batch` int(11) DEFAULT NULL,
  `reservation_time` time NOT NULL,
  `reservation_end_time` varchar(50) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `status` enum('pending','approved','ongoing','completed','cancelled','denied') NOT NULL DEFAULT 'pending',
  `admin_remarks` text DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `cooldown_until` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stock_restored` tinyint(1) DEFAULT 0,
  `ongoing_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_reservations`
--

INSERT INTO `lab_reservations` (`id`, `reservation_no`, `user_id`, `user_name`, `user_email`, `user_contact`, `subject`, `course_section`, `station`, `batch`, `reservation_time`, `reservation_end_time`, `reservation_date`, `status`, `admin_remarks`, `approved_at`, `completed_at`, `cooldown_until`, `created_at`, `updated_at`, `stock_restored`, `ongoing_at`) VALUES
(1, 'LAB-20260423-001', NULL, 'Justin Bieber', 'geomarc789@gmail.com', '09087547440', 'Hm 110', 'Bshm 1101', '12', 2, '07:00:00', '10:00', '2026-04-24', 'completed', NULL, NULL, NULL, '2026-04-23 17:44:09', '2026-04-23 06:10:20', '2026-04-28 00:33:21', 1, '2026-04-23 14:44:09'),
(2, 'LAB-20260428-001', NULL, 'Geo Mar C. De Guzman', 'geomarc789@gmail.com', '09087547440', 'Hm 110', 'Bshm - 1201', '12', 2, '07:00:00', '10:00', '2026-04-28', 'completed', NULL, NULL, NULL, '2026-04-28 12:08:53', '2026-04-28 00:48:27', '2026-04-28 01:32:01', 1, '2026-04-28 09:08:53'),
(3, 'LAB-20260428-002', NULL, 'Greg Tomco', 'geomarc789@gmail.com', '09087547440', 'Hm 110', 'Bshm - 1301', '12', 2, '07:30:00', '10:30', '2026-04-28', 'completed', NULL, NULL, NULL, '2026-04-28 13:19:45', '2026-04-28 01:59:36', '2026-04-28 02:19:48', 1, '2026-04-28 10:19:45'),
(4, 'LAB-20260428-003', NULL, 'Jerome Cabral', 'markjeromecabral@gmail.com', '09087547440', 'Hm 110', 'Bshm 3201', '12', 2, '11:30:00', '14:30', '2026-04-28', 'completed', NULL, NULL, NULL, '2026-04-28 14:25:23', '2026-04-28 03:24:55', '2026-04-29 01:28:55', 1, '2026-04-28 11:25:23'),
(5, 'LAB-20260429-001', NULL, 'Gomari C. De Guzman', 'geomarc789@gmail.com', '09087547440', 'Hm 110', 'Bshm - 1201', '12', 2, '08:00:00', '11:00', '2026-04-29', 'completed', NULL, NULL, NULL, '2026-04-30 11:24:23', '2026-04-29 02:56:29', '2026-04-30 00:24:26', 1, '2026-04-30 08:24:23'),
(6, 'LAB-20260504-001', NULL, 'Michael Jackson', 'geomarc789@gmail.com', '09087547440', 'Hm 110', 'Bshm -3101', '2', 2, '14:00:00', '16:00', '2026-05-04', 'denied', NULL, NULL, NULL, NULL, '2026-05-04 08:13:16', '2026-05-06 00:04:35', 0, NULL),
(7, 'LAB-20260505-001', NULL, 'Geo Mar C. De Guzman', 'geomarc789@gmail.com', '09087547440', 'Hm 12', 'Bshm 2102', '2', 3, '11:00:00', '14:00', '2026-05-05', 'completed', NULL, NULL, NULL, '2026-05-05 14:00:13', '2026-05-05 02:59:27', '2026-05-05 03:00:17', 1, '2026-05-05 11:00:13'),
(8, 'LAB-20260507-001', NULL, 'Juan Dela Cruz', 'JuanDelaCruz@gmail.com', '09876543211', 'Hm 210', 'Bshm 2102', '2', 2, '09:00:00', '12:00', '2026-05-07', 'pending', NULL, NULL, NULL, NULL, '2026-05-07 00:37:16', '2026-05-07 00:37:16', 0, NULL),
(9, 'LAB-20260507-002', NULL, 'Michael Jordan', 'geomarc789@gmail.com', '09087547440', '2', 'Bshm 1101', '2', 2, '10:30:00', '13:30', '2026-05-08', 'ongoing', NULL, NULL, NULL, '2026-05-07 11:49:41', '2026-05-07 00:38:33', '2026-05-07 00:49:41', 0, '2026-05-07 08:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `lab_reservation_items`
--

CREATE TABLE `lab_reservation_items` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `requested_quantity` int(11) NOT NULL,
  `approved_quantity` int(11) DEFAULT NULL,
  `status` enum('pending','approved','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_reservation_items`
--

INSERT INTO `lab_reservation_items` (`id`, `reservation_id`, `item_id`, `requested_quantity`, `approved_quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(2, 1, 6, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(3, 1, 67, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(4, 1, 73, 2, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(5, 1, 76, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(6, 1, 168, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(7, 1, 169, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(8, 1, 170, 1, 1, 'pending', '2026-04-23 06:10:20', '2026-04-23 06:18:44'),
(9, 2, 5, 1, 1, 'pending', '2026-04-28 00:48:27', '2026-04-28 00:51:57'),
(10, 2, 6, 1, 1, 'pending', '2026-04-28 00:48:27', '2026-04-28 00:51:57'),
(11, 2, 12, 2, 2, 'pending', '2026-04-28 00:48:27', '2026-04-28 00:51:57'),
(12, 2, 67, 1, 1, 'pending', '2026-04-28 00:48:27', '2026-04-28 00:51:57'),
(13, 2, 70, 1, 1, 'pending', '2026-04-28 00:48:27', '2026-04-28 00:51:57'),
(14, 3, 5, 1, 1, 'pending', '2026-04-28 01:59:36', '2026-04-28 01:59:59'),
(15, 3, 6, 1, 1, 'pending', '2026-04-28 01:59:36', '2026-04-28 01:59:59'),
(16, 3, 12, 3, 3, 'pending', '2026-04-28 01:59:36', '2026-04-28 01:59:59'),
(17, 3, 67, 1, 1, 'pending', '2026-04-28 01:59:36', '2026-04-28 01:59:59'),
(18, 4, 30, 10, 7, 'pending', '2026-04-28 03:24:55', '2026-04-28 03:25:16'),
(19, 4, 31, 5, 4, 'pending', '2026-04-28 03:24:55', '2026-04-28 03:25:16'),
(20, 5, 5, 2, 2, 'pending', '2026-04-29 02:56:29', '2026-04-29 02:56:37'),
(21, 5, 6, 2, 2, 'pending', '2026-04-29 02:56:29', '2026-04-29 02:56:37'),
(22, 6, 16, 5, NULL, 'pending', '2026-05-04 08:13:16', '2026-05-04 08:13:16'),
(23, 6, 21, 1, NULL, 'pending', '2026-05-04 08:13:16', '2026-05-04 08:13:16'),
(24, 7, 160, 25, 25, 'pending', '2026-05-05 02:59:27', '2026-05-05 02:59:38'),
(25, 8, 5, 1, NULL, 'pending', '2026-05-07 00:37:16', '2026-05-07 00:37:16'),
(26, 9, 6, 1, 1, 'pending', '2026-05-07 00:38:33', '2026-05-07 00:47:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lab_admin_notifications`
--
ALTER TABLE `lab_admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `lab_admin_users`
--
ALTER TABLE `lab_admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `lab_categories`
--
ALTER TABLE `lab_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_items`
--
ALTER TABLE `lab_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `lab_item_logs`
--
ALTER TABLE `lab_item_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `lab_reservations`
--
ALTER TABLE `lab_reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_no` (`reservation_no`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status` (`status`),
  ADD KEY `reservation_date` (`reservation_date`);

--
-- Indexes for table `lab_reservation_items`
--
ALTER TABLE `lab_reservation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lab_admin_notifications`
--
ALTER TABLE `lab_admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lab_admin_users`
--
ALTER TABLE `lab_admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_categories`
--
ALTER TABLE `lab_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lab_items`
--
ALTER TABLE `lab_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `lab_item_logs`
--
ALTER TABLE `lab_item_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `lab_reservations`
--
ALTER TABLE `lab_reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lab_reservation_items`
--
ALTER TABLE `lab_reservation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_items`
--
ALTER TABLE `lab_items`
  ADD CONSTRAINT `lab_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `lab_categories` (`id`);

--
-- Constraints for table `lab_item_logs`
--
ALTER TABLE `lab_item_logs`
  ADD CONSTRAINT `lab_item_logs_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `lab_items` (`id`);

--
-- Constraints for table `lab_reservation_items`
--
ALTER TABLE `lab_reservation_items`
  ADD CONSTRAINT `lab_reservation_items_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `lab_reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_reservation_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `lab_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
