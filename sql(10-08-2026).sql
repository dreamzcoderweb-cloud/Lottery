-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 10, 2026 at 04:11 AM
-- Server version: 8.0.46-0ubuntu0.24.04.3
-- PHP Version: 8.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `user_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE `bank_accounts` (
  `bank_account_id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upi_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`bank_account_id`, `customer_id`, `bank_name`, `account_number`, `ifsc_code`, `account_holder_name`, `upi_id`, `created_at`, `updated_at`) VALUES
(2, 3, 'Axis Bank', '968668686', '12345678905', 'demo k', 'jhhhhhjjg@upi', '2026-05-21 10:18:28', '2026-05-22 07:45:07'),
(3, 1, 'state bank of india', '12365842', 'SBIN00085423', 'tharik', NULL, '2026-05-21 12:18:24', '2026-05-21 12:18:24'),
(7, 32, 'Indian Bank', '6105276218', 'IDIB000K180', 'jvignesh', '9944318752@ibl', '2026-08-06 17:26:16', '2026-08-06 18:39:22'),
(8, 27, 'INADIN OVERSEAS BANK', '004501000085241', 'IOBA0000045', 'BASHEER MOHAMED', 'basirbasir7367-3@oksbi', '2026-08-07 07:36:55', '2026-08-07 07:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `short_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `sequence` int DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `short_title`, `title`, `image`, `description`, `sequence`, `link`, `status`, `created_at`, `updated_at`) VALUES
(2, 'fgfdgfdgfdgfd', 'fdgfdgdfgfdgfdgfdg', 'banner_1782278663_banner3.png', NULL, 1, NULL, 'Active', '2026-05-30 08:20:23', '2026-06-24 05:24:23'),
(3, NULL, 'FDSFDSFDSFDSFS', 'banner_1782278655_banner2.png', '<p>DSFDSFDSFDSFDSFSDFDS</p>', NULL, NULL, 'Active', '2026-06-23 11:47:42', '2026-06-24 05:24:15'),
(4, NULL, 'ddddddddddddddddd', 'banner_1782278644_banner1.png', NULL, NULL, NULL, 'Active', '2026-06-24 04:55:36', '2026-06-24 05:24:04');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` bigint UNSIGNED NOT NULL,
  `customer_id` int NOT NULL,
  `slot_id` int NOT NULL,
  `slot_items_id` int NOT NULL,
  `title_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `digits` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','success','cancelled','settled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_time` time NOT NULL,
  `close_time` time NOT NULL,
  `is_winner` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_amount` decimal(10,2) DEFAULT '0.00',
  `first_price_flag` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_price_flag` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `third_price_flag` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `customer_id`, `slot_id`, `slot_items_id`, `title_id`, `digits`, `qty`, `amount`, `status`, `payment_status`, `booking_time`, `close_time`, `is_winner`, `win_amount`, `first_price_flag`, `second_price_flag`, `third_price_flag`, `created_at`, `updated_at`) VALUES
(1, 24, 445, 3662, '1', '0', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(2, 24, 445, 3661, '1', '0', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(3, 24, 445, 3660, '1', '0', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 50.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:04'),
(4, 24, 445, 3663, '2', '04', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 500.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(5, 24, 445, 3664, '2', '85', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(6, 24, 445, 3665, '2', '05', 1, 7.00, 'success', 'paid', '06:50:31', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(7, 24, 445, 3666, '3', '045', 1, 15.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 6000.00, 'true', NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(8, 24, 445, 3667, '3', '065', 1, 25.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 0.00, NULL, NULL, 'true', '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(9, 24, 445, 3668, '3', '055', 1, 30.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 50.00, NULL, NULL, 'true', '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(10, 24, 445, 3669, '3', '075', 1, 35.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 50.00, NULL, NULL, 'true', '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(11, 24, 445, 3670, '3', '085', 1, 60.00, 'success', 'paid', '06:50:31', '12:57:00', 'true', 100.00, NULL, NULL, 'true', '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(12, 24, 445, 3671, '4', '0065', 1, 20.00, 'success', 'paid', '06:50:31', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-06 06:50:31', '2026-08-06 07:38:05'),
(13, 25, 445, 3666, '3', '089', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(14, 25, 445, 3666, '3', '098', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(15, 25, 445, 3666, '3', '809', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(16, 25, 445, 3666, '3', '890', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(17, 25, 445, 3666, '3', '908', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(18, 25, 445, 3666, '3', '980', 1, 15.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(19, 25, 445, 3666, '3', '809', 1, 25.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(20, 25, 445, 3666, '3', '222', 1, 30.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(21, 25, 445, 3666, '3', '000', 1, 35.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(22, 25, 445, 3666, '3', '000', 1, 60.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(23, 25, 445, 3671, '4', '4444', 1, 20.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(24, 25, 445, 3671, '4', '7777', 1, 20.00, 'success', 'paid', '06:56:44', '12:57:00', NULL, 0.00, NULL, NULL, NULL, '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(25, 32, 456, 3755, '2', '75', 5, 35.00, 'success', 'paid', '05:16:17', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 05:16:17', '2026-08-07 07:37:03'),
(26, 27, 456, 3757, '3', '760', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(27, 27, 456, 3757, '3', '761', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(28, 27, 456, 3757, '3', '219', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(29, 27, 456, 3757, '3', '064', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(30, 27, 456, 3757, '3', '080', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(31, 27, 456, 3757, '3', '161', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-07 07:34:01'),
(32, 27, 456, 3757, '3', '725', 1, 25.00, 'success', 'paid', '07:13:39', '12:57:00', 'true', 0.00, NULL, NULL, NULL, '2026-08-07 07:13:39', '2026-08-08 16:10:52'),
(33, 32, 456, 3756, '3', '825', 2, 120.00, 'success', 'paid', '07:20:49', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:20:49', '2026-08-07 07:37:03'),
(34, 27, 456, 3757, '3', '692', 1, 25.00, 'success', 'paid', '07:20:53', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:20:53', '2026-08-07 07:34:02'),
(35, 27, 456, 3757, '3', '473', 1, 25.00, 'success', 'paid', '07:20:53', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:20:53', '2026-08-07 07:34:02'),
(36, 27, 456, 3757, '3', '010', 1, 25.00, 'success', 'paid', '07:20:53', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:20:53', '2026-08-07 07:34:02'),
(37, 27, 456, 3757, '3', '282', 1, 25.00, 'success', 'paid', '07:20:53', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 07:20:53', '2026-08-07 07:34:02'),
(38, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(39, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(40, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(41, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(42, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(43, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(44, 27, 457, 3769, '3', '860', 1, 25.00, 'success', 'paid', '08:27:04', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 08:27:04', '2026-08-07 09:44:06'),
(45, 27, 457, 3769, '3', '754', 1, 25.00, 'success', 'paid', '09:06:21', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 09:06:21', '2026-08-07 09:44:06'),
(46, 27, 457, 3770, '3', '461', 1, 30.00, 'success', 'paid', '09:15:53', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 09:15:53', '2026-08-07 09:44:06'),
(47, 27, 457, 3770, '3', '461', 1, 30.00, 'success', 'paid', '09:15:53', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 09:15:53', '2026-08-07 09:44:06'),
(48, 27, 457, 3770, '3', '754', 1, 30.00, 'success', 'paid', '09:15:53', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 09:15:53', '2026-08-07 09:44:06'),
(57, 1, 458, 3776, '1', '5', 2, 14.00, 'success', 'paid', '10:58:57', '17:57:00', 'true', 100.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(58, 1, 458, 3775, '1', '4', 2, 14.00, 'success', 'paid', '10:58:57', '17:57:00', 'true', 100.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(59, 1, 458, 3774, '1', '0', 2, 14.00, 'success', 'paid', '10:58:57', '17:57:00', 'true', 100.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(60, 1, 458, 3780, '3', '045', 2, 30.00, 'success', 'paid', '10:58:57', '17:57:00', 'true', 12000.00, 'true', NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(61, 1, 458, 3780, '3', '147', 2, 50.00, 'success', 'paid', '10:58:57', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(62, 1, 458, 3780, '3', '475', 2, 60.00, 'success', 'paid', '10:58:57', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(63, 1, 458, 3780, '3', '123', 2, 70.00, 'success', 'paid', '10:58:57', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(64, 1, 458, 3780, '3', '045', 2, 120.00, 'success', 'paid', '10:58:57', '17:57:00', 'true', 60000.00, 'true', NULL, NULL, '2026-08-07 10:58:57', '2026-08-07 12:27:13'),
(65, 27, 458, 3780, '3', '064', 1, 25.00, 'success', 'paid', '12:22:05', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:05', '2026-08-07 12:32:24'),
(66, 27, 458, 3780, '3', '861', 1, 25.00, 'success', 'paid', '12:22:05', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:05', '2026-08-07 12:32:24'),
(67, 27, 458, 3780, '3', '073', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(68, 27, 458, 3780, '3', '910', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(69, 27, 458, 3780, '3', '977', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(70, 27, 458, 3780, '3', '461', 2, 50.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(71, 27, 458, 3780, '3', '893', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(72, 27, 458, 3780, '3', '459', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(73, 27, 458, 3780, '3', '634', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(74, 27, 458, 3780, '3', '464', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(75, 27, 458, 3780, '3', '865', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(76, 27, 458, 3780, '3', '723', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(77, 27, 458, 3780, '3', '860', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(78, 27, 458, 3780, '3', '065', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(79, 27, 458, 3780, '3', '359', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(80, 27, 458, 3780, '3', '459', 1, 25.00, 'success', 'paid', '12:22:06', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-07 12:22:06', '2026-08-07 12:32:24'),
(81, 39, 460, 3801, '2', '89', 1, 7.00, 'success', 'paid', '06:53:20', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 06:53:20', '2026-08-08 07:32:04'),
(82, 39, 460, 3801, '2', '98', 1, 7.00, 'success', 'paid', '06:53:20', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 06:53:20', '2026-08-08 07:32:04'),
(83, 39, 460, 3804, '3', '895', 1, 15.00, 'success', 'paid', '06:53:20', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 06:53:20', '2026-08-08 07:32:04'),
(84, 39, 461, 3816, '3', '564', 1, 15.00, 'success', 'paid', '09:13:05', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:13:05', '2026-08-09 06:57:27'),
(85, 39, 461, 3816, '3', '957', 1, 15.00, 'success', 'paid', '09:13:05', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:13:05', '2026-08-09 06:57:27'),
(86, 39, 461, 3815, '2', '88', 1, 7.00, 'success', 'paid', '09:13:05', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:13:05', '2026-08-09 06:57:27'),
(87, 39, 461, 3815, '2', '84', 1, 7.00, 'success', 'paid', '09:13:05', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:13:05', '2026-08-09 06:57:27'),
(88, 39, 461, 3813, '2', '56', 1, 7.00, 'success', 'paid', '09:16:37', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:16:37', '2026-08-09 06:57:27'),
(89, 27, 461, 3812, '1', '2', 1, 7.00, 'success', 'paid', '09:25:46', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:25:46', '2026-08-08 16:10:47'),
(90, 27, 461, 3816, '3', '374', 1, 25.00, 'success', 'paid', '09:26:40', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:26:40', '2026-08-08 16:10:47'),
(91, 27, 461, 3816, '3', '257', 1, 25.00, 'success', 'paid', '09:26:40', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:26:40', '2026-08-08 16:10:47'),
(92, 27, 461, 3816, '3', '459', 1, 25.00, 'success', 'paid', '09:26:40', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:26:40', '2026-08-08 16:10:47'),
(93, 27, 461, 3816, '3', '889', 1, 25.00, 'success', 'paid', '09:26:40', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-08 09:26:40', '2026-08-08 16:10:47'),
(94, 24, 464, 3846, '1', '0', 3, 21.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(95, 24, 464, 3847, '1', '0', 3, 21.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(96, 24, 464, 3848, '1', '0', 3, 21.00, 'success', 'paid', '07:17:51', '12:57:00', 'true', 150.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(97, 24, 464, 3851, '2', '01', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(98, 24, 464, 3850, '2', '01', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(99, 24, 464, 3849, '2', '01', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(100, 24, 464, 3851, '2', '10', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'true', 500.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(101, 24, 464, 3850, '2', '10', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(102, 24, 464, 3849, '2', '10', 1, 7.00, 'success', 'paid', '07:17:51', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:17:51', '2026-08-09 07:35:08'),
(103, 39, 464, 3852, '3', '756', 1, 15.00, 'success', 'paid', '07:18:10', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:18:10', '2026-08-09 08:03:35'),
(104, 39, 464, 3849, '2', '75', 1, 7.00, 'success', 'paid', '07:18:10', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:18:10', '2026-08-09 08:03:35'),
(105, 39, 464, 3849, '2', '57', 1, 7.00, 'success', 'paid', '07:18:10', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:18:10', '2026-08-09 08:03:35'),
(106, 39, 464, 3851, '2', '56', 1, 7.00, 'success', 'paid', '07:18:10', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:18:10', '2026-08-09 08:03:35'),
(107, 39, 464, 3851, '2', '11', 1, 7.00, 'success', 'paid', '07:18:10', '12:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 07:18:10', '2026-08-09 08:03:35'),
(108, 39, 465, 3861, '2', '96', 2, 14.00, 'success', 'paid', '08:55:10', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(109, 39, 465, 3861, '2', '69', 1, 7.00, 'success', 'paid', '08:55:10', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(110, 39, 465, 3864, '3', '966', 1, 15.00, 'success', 'paid', '08:55:10', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(111, 39, 465, 3864, '3', '679', 1, 15.00, 'success', 'paid', '08:55:10', '15:03:00', 'true', 25.00, NULL, NULL, 'true', '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(112, 39, 465, 3863, '2', '79', 1, 7.00, 'success', 'paid', '08:55:10', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(113, 39, 465, 3863, '2', '97', 1, 7.00, 'success', 'paid', '08:55:10', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 08:55:10', '2026-08-09 09:40:58'),
(114, 24, 465, 3858, '1', '5', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(115, 24, 465, 3859, '1', '5', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(116, 24, 465, 3860, '1', '5', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'true', 50.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(117, 24, 465, 3858, '1', '5', 2, 14.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(118, 24, 465, 3859, '1', '5', 2, 14.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(119, 24, 465, 3860, '1', '5', 2, 14.00, 'success', 'paid', '09:19:49', '15:03:00', 'true', 100.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(120, 24, 465, 3861, '2', '56', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(121, 24, 465, 3862, '2', '56', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(122, 24, 465, 3863, '2', '56', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(123, 24, 465, 3861, '2', '65', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(124, 24, 465, 3862, '2', '65', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(125, 24, 465, 3863, '2', '65', 1, 7.00, 'success', 'paid', '09:19:49', '15:03:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 09:19:49', '2026-08-09 09:51:01'),
(126, 24, 466, 3870, '1', '5', 3, 21.00, 'success', 'paid', '12:15:01', '17:57:00', 'true', 150.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(127, 24, 466, 3871, '1', '5', 3, 21.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(128, 24, 466, 3872, '1', '5', 3, 21.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(129, 24, 466, 3873, '2', '57', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(130, 24, 466, 3874, '2', '57', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(131, 24, 466, 3875, '2', '57', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(132, 24, 466, 3875, '2', '75', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(133, 24, 466, 3874, '2', '75', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(134, 24, 466, 3873, '2', '75', 1, 7.00, 'success', 'paid', '12:15:01', '17:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 12:15:01', '2026-08-09 17:33:41'),
(135, 24, 467, 3882, '1', '1', 3, 21.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(136, 24, 467, 3883, '1', '1', 3, 21.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(137, 24, 467, 3884, '1', '1', 3, 21.00, 'success', 'paid', '14:08:51', '19:57:00', 'true', 150.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(138, 24, 467, 3885, '2', '10', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(139, 24, 467, 3886, '2', '10', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(140, 24, 467, 3887, '2', '10', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(141, 24, 467, 3887, '2', '01', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(142, 24, 467, 3886, '2', '01', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(143, 24, 467, 3885, '2', '01', 1, 7.00, 'success', 'paid', '14:08:51', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:08:51', '2026-08-09 17:33:41'),
(144, 24, 467, 3888, '3', '038', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41'),
(145, 24, 467, 3888, '3', '083', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41'),
(146, 24, 467, 3888, '3', '308', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41'),
(147, 24, 467, 3888, '3', '380', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41'),
(148, 24, 467, 3888, '3', '803', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41'),
(149, 24, 467, 3888, '3', '830', 1, 15.00, 'success', 'paid', '14:10:03', '19:57:00', 'false', 0.00, NULL, NULL, NULL, '2026-08-09 14:10:03', '2026-08-09 17:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:28:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"dashboard.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"banners.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:14:\"banners.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"banners.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"banners.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:10:\"roles.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:10:\"staff.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:12:\"staff.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:10:\"staff.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"staff.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"profile.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"profile.password\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:14:\"customers.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:10:\"slots.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"slots.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:10:\"slots.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:12:\"slots.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:14:\"customers.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:16:\"withdrawals.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:19:\"withdrawals.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:18:\"withdrawals.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:21:\"reports.winningsslots\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"recharges.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"recharges.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:16:\"recharges.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"Super Admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:8:\"mananger\";s:1:\"c\";s:3:\"web\";}}}', 1786364740),
('wallet_withdraw_otp:14', 'a:3:{s:4:\"hash\";s:60:\"$2y$12$vJujzXYvZRMXcrJj8IftDuFOKHNXmrnvCggaK80105t/gamdyoUhm\";s:7:\"sent_at\";s:25:\"2026-07-27T06:00:29+00:00\";s:8:\"attempts\";i:0;}', 1785132329);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache_locks`
--

INSERT INTO `cache_locks` (`key`, `owner`, `expiration`) VALUES
('framework/schedule-f10e64fcc77dccb937c847893284c91f2ceda6f31835', 'zBaxlkHK3N2JHIMZ', 1786304101);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by_customer_id` bigint DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `mobile`, `password`, `reference_code`, `referred_by_customer_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'tharik', '9489042085', '$2y$12$45AoeOj3srCksSNQ04gRku5l8kYf1F4MMPYtS5m1cFqfpFbFmSiJG', NULL, NULL, NULL, '2026-05-05 23:50:01', '2026-05-05 23:50:01'),
(3, 'demo', '9787698254', '$2y$12$jR9E46hKv.3JMubb0ShD9Oc7dkKIS3bMLvPTuF.ds7irK3Usp.60a', NULL, NULL, NULL, '2026-05-19 06:18:33', '2026-05-19 06:18:33'),
(24, 'Johnprabu', '8667369678', NULL, 'KL001', NULL, NULL, '2026-08-06 06:19:02', '2026-08-06 06:19:02'),
(25, 'Suresh alwin', '8508879198', NULL, 'KL002', NULL, NULL, '2026-08-06 06:54:11', '2026-08-06 06:54:11'),
(26, 'chithra', '9600356580', NULL, 'KL003', NULL, NULL, '2026-08-06 09:57:29', '2026-08-06 09:57:29'),
(27, 'BASHEER MOHAMED', '9487450556', NULL, 'KL004', NULL, NULL, '2026-08-06 10:59:44', '2026-08-06 10:59:44'),
(28, 'Bala Guru', '7094281670', NULL, 'KL005', NULL, NULL, '2026-08-06 12:13:57', '2026-08-06 12:13:57'),
(29, 'Thiyagarajan', '9843725373', NULL, 'KL006', NULL, NULL, '2026-08-06 12:16:33', '2026-08-06 12:16:33'),
(30, 'Vicky', '9715819129', NULL, 'KL007', NULL, NULL, '2026-08-06 15:55:01', '2026-08-06 15:55:01'),
(31, 'gkarthik', '9965471438', NULL, 'KL008', NULL, NULL, '2026-08-06 17:00:46', '2026-08-06 17:00:46'),
(32, 'jvignesh', '9944318752', NULL, 'KL009', NULL, NULL, '2026-08-06 17:23:16', '2026-08-06 17:23:16'),
(33, 'Venkateshan M', '9865618931', NULL, 'KL010', NULL, NULL, '2026-08-07 05:32:33', '2026-08-07 05:32:33'),
(34, 'Erixon', '8592988830', NULL, 'KL011', NULL, NULL, '2026-08-07 06:24:43', '2026-08-07 06:24:43'),
(35, 'Kari Kaalan', '9342771649', NULL, 'KL012', NULL, NULL, '2026-08-07 06:27:43', '2026-08-07 06:27:43'),
(36, 'Marimuthu Mari', '9943818649', NULL, 'KL013', NULL, NULL, '2026-08-07 09:09:02', '2026-08-07 09:09:02'),
(37, 'stalin', '9965468831', NULL, 'KL014', NULL, NULL, '2026-08-07 13:24:46', '2026-08-07 13:24:46'),
(38, 'THIYAGARAJAN', '8056499684', NULL, 'KL015', NULL, NULL, '2026-08-07 13:32:24', '2026-08-07 13:32:24'),
(39, 'Srinivasan S', '9952291198', NULL, 'KL016', NULL, NULL, '2026-08-07 13:59:16', '2026-08-07 13:59:16'),
(40, 'NAVEEN', '8012803957', NULL, 'KL017', 24, NULL, '2026-08-08 01:28:26', '2026-08-08 01:28:26'),
(41, 'Suresh Amalan', '9080482309', NULL, 'KL018', NULL, NULL, '2026-08-08 16:32:48', '2026-08-08 16:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_23_065949_create_banners_table', 2),
(5, '2025_01_24_124334_create_galleries_table', 3),
(6, '2025_01_25_051011_create_albums_table', 4),
(7, '2025_01_30_063842_create_albumimages_table', 5),
(8, '2025_01_30_103349_create_testimonials_table', 6),
(9, '2025_02_11_051902_create_services_table', 7),
(11, '2025_02_11_081407_create_blogs_table', 8),
(12, '2025_03_28_064659_create_seos_table', 9),
(13, '2025_03_28_075724_create_email_configs_table', 10),
(14, '2025_03_28_101013_create_payments_table', 11),
(15, '2025_03_29_042036_create_websettings_table', 12),
(16, '2025_04_02_060633_create_contact_enquiries_table', 13),
(17, '2025_04_02_063404_create_newletter_subscriptions_table', 14),
(18, '2026_05_06_050937_create_personal_access_tokens_table', 15),
(19, '2026_05_06_051430_create_customers_table', 15),
(20, '2026_05_12_102631_create_permission_tables', 16),
(21, '2026_05_15_050325_create_slots_table', 17),
(22, '2026_05_15_050701_create_slot_items_table', 17),
(23, '2026_05_15_123514_add_deleted_at_to_slots_table', 18),
(24, '2026_05_15_123515_add_deleted_at_to_slot_items_table', 18),
(25, '2026_05_15_124459_add_slug_to_slots_table', 19),
(26, '2026_05_16_000001_add_amounts_to_slot_items_table', 20),
(27, '2026_05_16_000002_remove_amounts_from_slots_table', 20);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.view', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(2, 'banners.view', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(3, 'banners.create', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(4, 'banners.edit', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(5, 'banners.delete', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(6, 'roles.view', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(7, 'roles.create', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(8, 'roles.edit', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(9, 'roles.delete', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(10, 'staff.view', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(11, 'staff.create', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(12, 'staff.edit', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(13, 'staff.delete', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(14, 'profile.view', 'web', '2026-05-12 05:15:59', '2026-05-12 05:15:59'),
(15, 'profile.password', 'web', '2026-05-12 05:15:59', '2026-05-12 05:15:59'),
(16, 'customers.view', 'web', '2026-05-12 22:54:13', '2026-05-12 22:54:13'),
(17, 'slots.view', 'web', '2026-05-14 23:52:27', '2026-05-14 23:52:27'),
(18, 'slots.create', 'web', '2026-05-14 23:52:27', '2026-05-14 23:52:27'),
(19, 'slots.edit', 'web', '2026-05-14 23:52:27', '2026-05-14 23:52:27'),
(20, 'slots.delete', 'web', '2026-05-14 23:52:27', '2026-05-14 23:52:27'),
(21, 'customers.show', 'web', '2026-05-25 09:38:22', '2026-05-25 09:38:22'),
(22, 'withdrawals.view', 'web', '2026-05-25 13:12:07', '2026-05-25 13:12:07'),
(23, 'withdrawals.approve', 'web', '2026-05-25 13:12:07', '2026-05-25 13:12:07'),
(24, 'withdrawals.reject', 'web', '2026-05-25 13:12:38', '2026-05-25 13:12:38'),
(25, 'reports.winningsslots', 'web', '2026-06-01 13:20:18', '2026-06-01 13:20:18'),
(26, 'recharges.view', 'web', '2026-06-17 15:51:05', '2026-06-17 15:51:05'),
(27, 'recharges.approve', 'web', '2026-06-17 15:51:05', '2026-06-17 15:51:05'),
(28, 'recharges.reject', 'web', '2026-06-17 15:51:35', '2026-06-17 15:51:35');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Customer', 1, 'customer-api', 'faa6882fc12416774d42734dbbd10a261b7c4bc3323ecbaf5fce059236306ba4', '[\"*\"]', NULL, NULL, '2026-05-05 23:50:01', '2026-05-05 23:50:01'),
(2, 'App\\Models\\Customer', 1, 'customer-api', 'f6b6b72d7b5d78060881fb021747cf057ed103bdac868fa50376c8ae930edbce', '[\"*\"]', NULL, NULL, '2026-05-05 23:54:08', '2026-05-05 23:54:08'),
(4, 'App\\Models\\Customer', 1, 'customer-api', '0c0d97afceba7522507f92d3384a84d36a1978ebe38f3d0fda711344b47d6c3c', '[\"*\"]', '2026-05-16 14:52:53', NULL, '2026-05-16 07:41:07', '2026-05-16 14:52:53'),
(5, 'App\\Models\\Customer', 1, 'customer-api', '475dfe77b2341b3c8f8ce1195b23a9a6f04d7a383723416c571f4e2cb1e0b52e', '[\"*\"]', '2026-05-16 14:53:31', NULL, '2026-05-16 14:53:16', '2026-05-16 14:53:31'),
(6, 'App\\Models\\Customer', 1, 'customer-api', '2c650b750e6089cda0acb1307447f8b7266ac8075acb2656fe7a74dd62f2b5ff', '[\"*\"]', '2026-06-22 06:18:50', NULL, '2026-05-19 05:10:41', '2026-06-22 06:18:50'),
(7, 'App\\Models\\Customer', 2, 'customer-api', '85764f9251242fa802c2f038c7b79a5c083d5011739bb2a64c7ed7e733255757', '[\"*\"]', NULL, NULL, '2026-05-19 05:22:52', '2026-05-19 05:22:52'),
(8, 'App\\Models\\Customer', 1, 'customer-api', '0c69a53a0093d8d1307016dfda60c1b334a35c368d8c4029ec69a77571c21e49', '[\"*\"]', NULL, NULL, '2026-05-19 05:24:20', '2026-05-19 05:24:20'),
(9, 'App\\Models\\Customer', 3, 'customer-api', '75e93b35c338e68dca59c1d1eeec47acbb691f6e798420edba5a9a08102a2c39', '[\"*\"]', NULL, NULL, '2026-05-19 06:18:33', '2026-05-19 06:18:33'),
(10, 'App\\Models\\Customer', 3, 'customer-api', 'a937cbd9ac7323ffb4bed07aa9a6020e31e0cf8b8e7fd03c99b6f92d2cb1446c', '[\"*\"]', '2026-05-19 10:03:58', NULL, '2026-05-19 06:18:36', '2026-05-19 10:03:58'),
(11, 'App\\Models\\Customer', 1, 'customer-api', '8334c2b6e430b78bc8cc6df34a1a3500f9aa16afdf01bf2f73076e640a8f300e', '[\"*\"]', '2026-05-25 05:53:34', NULL, '2026-05-19 08:05:13', '2026-05-25 05:53:34'),
(12, 'App\\Models\\Customer', 3, 'customer-api', 'd1a574a27ac2bb4280f7498666c02bce0480c4e41a1cf4c038253281b3ab0182', '[\"*\"]', '2026-05-20 05:48:40', NULL, '2026-05-19 10:12:18', '2026-05-20 05:48:40'),
(13, 'App\\Models\\Customer', 1, 'customer-api', '40adc00d2af9d071c45f7a179e88df89ba3c888abe4c38ec36637f794c8426c6', '[\"*\"]', '2026-05-20 04:32:39', NULL, '2026-05-19 10:14:18', '2026-05-20 04:32:39'),
(14, 'App\\Models\\Customer', 3, 'customer-api', '6f8a5eb9ea8c4c1f165079f5bcc5f902a46429104c452f9a26f36a99b15fd8c9', '[\"*\"]', '2026-05-20 05:43:25', NULL, '2026-05-19 11:37:12', '2026-05-20 05:43:25'),
(15, 'App\\Models\\Customer', 1, 'customer-api', '442ea7dc0b26998116c5f0e5319478ede331e2203cacc0106329f2f0bb646a7e', '[\"*\"]', '2026-05-20 05:37:54', NULL, '2026-05-20 04:33:50', '2026-05-20 05:37:54'),
(16, 'App\\Models\\Customer', 2, 'customer-api', '21680bb746fdb3d8805f037fa3e1ad5ca619fc8a41b320121d450a4e23ed4229', '[\"*\"]', '2026-05-22 05:10:55', NULL, '2026-05-20 04:52:10', '2026-05-22 05:10:55'),
(17, 'App\\Models\\Customer', 1, 'customer-api', '1898971057c35d4ab59ef8c9861d509ef008bfc33b46fc9a0bd89152bc12c31a', '[\"*\"]', '2026-05-21 04:31:21', NULL, '2026-05-20 05:41:05', '2026-05-21 04:31:21'),
(18, 'App\\Models\\Customer', 3, 'customer-api', 'c89e10f940d0989be3e146a5e72b59935331c22acb4308f2c118d708b1951d6d', '[\"*\"]', '2026-05-20 05:54:56', NULL, '2026-05-20 05:53:19', '2026-05-20 05:54:56'),
(19, 'App\\Models\\Customer', 3, 'customer-api', '6c3befb9954abb6e6bdf5b1ea57c35238aee5583d66e287d0df2ae8084f7a5b1', '[\"*\"]', '2026-05-20 10:43:13', NULL, '2026-05-20 05:56:39', '2026-05-20 10:43:13'),
(20, 'App\\Models\\Customer', 1, 'customer-api', 'f1d196d002372c77fb6ed0ab099a5f46b85318feb0ddb35cc26496e63f482cf5', '[\"*\"]', '2026-05-20 09:35:43', NULL, '2026-05-20 09:28:27', '2026-05-20 09:35:43'),
(21, 'App\\Models\\Customer', 2, 'customer-api', 'c4dafcfa46aa9b8f6fddf7af89e4c72428e020ac06a05c2ed3a176db43b5afaa', '[\"*\"]', '2026-05-20 10:42:56', NULL, '2026-05-20 10:42:13', '2026-05-20 10:42:56'),
(22, 'App\\Models\\Customer', 3, 'customer-api', '269e2fe92899851bd673355c19e263ea7125628f639360513f4a293f0165e0d4', '[\"*\"]', '2026-05-26 05:31:06', NULL, '2026-05-20 10:44:19', '2026-05-26 05:31:06'),
(23, 'App\\Models\\Customer', 4, 'customer-api', '3bd20e7790fbaa9ff990cbf4fe5b4aece2f00958aed28caec9ade8b03dab4522', '[\"*\"]', NULL, NULL, '2026-05-20 11:47:41', '2026-05-20 11:47:41'),
(24, 'App\\Models\\Customer', 4, 'customer-api', '94fd6b5035c439068f37a3b04fabba5d6dba3e4d5799c091a331b93205dc0bc4', '[\"*\"]', '2026-05-20 11:49:07', NULL, '2026-05-20 11:47:52', '2026-05-20 11:49:07'),
(25, 'App\\Models\\Customer', 4, 'customer-api', 'd452f41ab614911d4e4bf0fa91afe5681eab1e6f7e20beea0c2a06a95b8b9d81', '[\"*\"]', '2026-05-20 11:51:29', NULL, '2026-05-20 11:49:24', '2026-05-20 11:51:29'),
(26, 'App\\Models\\Customer', 3, 'customer-api', '67a9e2ae2e5dc932633a4526e197f03b3d3cb94f23e0a3035b8492a2215b50ca', '[\"*\"]', '2026-05-21 10:50:23', NULL, '2026-05-20 11:51:41', '2026-05-21 10:50:23'),
(27, 'App\\Models\\Customer', 1, 'customer-api', 'a26330167b0ee7a7d7f84f594c2f2c545f4b9cc94584908db6a927c3054f06da', '[\"*\"]', '2026-05-21 11:38:22', NULL, '2026-05-21 04:31:48', '2026-05-21 11:38:22'),
(28, 'App\\Models\\Customer', 1, 'customer-api', 'd3f1aeb55fd4e237fe0aa8c0c15acf3fadb77708e6601e422bc39b52bedc3a9b', '[\"*\"]', '2026-05-21 12:18:24', NULL, '2026-05-21 06:35:39', '2026-05-21 12:18:24'),
(29, 'App\\Models\\Customer', 3, 'customer-api', '1ae9846f36ff00c305b73cbfa4c93f298a676b4c79c2a42eb7cfd67971eaa223', '[\"*\"]', '2026-05-22 10:10:41', NULL, '2026-05-21 12:06:52', '2026-05-22 10:10:41'),
(31, 'App\\Models\\Customer', 1, 'customer-api', 'ec10151be86a307d231453f658263e0d422a6b438c037f5996abe8b3073c95d8', '[\"*\"]', '2026-05-22 05:14:09', NULL, '2026-05-22 05:06:59', '2026-05-22 05:14:09'),
(32, 'App\\Models\\Customer', 1, 'customer-api', 'ba5adb53446a6a5bdd0608454ae242a925066e37b3126179e8ed0ff4b36db68a', '[\"*\"]', '2026-05-25 09:44:45', NULL, '2026-05-22 05:14:40', '2026-05-25 09:44:45'),
(33, 'App\\Models\\Customer', 1, 'customer-api', '30b63f41877efd94642966337416a58c81abc6a1bb48ebb8aa036a73c2e184cd', '[\"*\"]', '2026-05-25 12:59:46', NULL, '2026-05-22 07:26:05', '2026-05-25 12:59:46'),
(34, 'App\\Models\\Customer', 5, 'customer-api', '8022a8b98818e28848e05fe28c27ddb1f1fcd3bfdcf17628ec90247aa4300e1b', '[\"*\"]', NULL, NULL, '2026-05-22 09:18:23', '2026-05-22 09:18:23'),
(35, 'App\\Models\\Customer', 5, 'customer-api', 'a374c5c83f374dddf45a4abe5c152ff7ac78ddfa04cd9331394fff0e56f7c5a9', '[\"*\"]', '2026-05-23 10:59:57', NULL, '2026-05-22 09:18:34', '2026-05-23 10:59:57'),
(36, 'App\\Models\\Customer', 5, 'customer-api', '9973f00ec24da7fa63b6c10876dd591e3ba313c3dd641e64645da017eaccc6f1', '[\"*\"]', '2026-05-22 10:52:48', NULL, '2026-05-22 10:10:56', '2026-05-22 10:52:48'),
(37, 'App\\Models\\Customer', 3, 'customer-api', '334d78fe61c9e4899c9deb01431810c0f6583825d76f378e6fa511c9044fb10f', '[\"*\"]', '2026-05-26 07:22:43', NULL, '2026-05-22 10:52:58', '2026-05-26 07:22:43'),
(38, 'App\\Models\\Customer', 2, 'customer-api', '98676c8497967e3b9f3489baab4d823ca2461da9d928cc9b6cffe8511dd2f389', '[\"*\"]', '2026-05-25 10:19:24', NULL, '2026-05-22 11:15:03', '2026-05-25 10:19:24'),
(39, 'App\\Models\\Customer', 2, 'customer-api', 'f044dcd6ec067414b6ef47fa7fd071abcd3a68ce4119ab48c82240312ebde198', '[\"*\"]', NULL, NULL, '2026-05-23 05:28:58', '2026-05-23 05:28:58'),
(40, 'App\\Models\\Customer', 5, 'customer-api', 'f61ee9e907f99fe13566b70ff1071b828aaf0afec0122483eb10242d2f4fedf1', '[\"*\"]', '2026-05-25 06:02:35', NULL, '2026-05-23 11:16:00', '2026-05-25 06:02:35'),
(41, 'App\\Models\\Customer', 6, 'customer-api', '3f445a31647f59ed6bfcf2c37fa126d68672da09e9b8e43a3e22cddbf1a21ad2', '[\"*\"]', NULL, NULL, '2026-05-25 06:03:21', '2026-05-25 06:03:21'),
(42, 'App\\Models\\Customer', 6, 'customer-api', '230b3cc1dea36c1a4ed4ec24b4cf0a57e43cdcd78540ee80791b0746e25e68e9', '[\"*\"]', '2026-05-25 06:17:09', NULL, '2026-05-25 06:03:31', '2026-05-25 06:17:09'),
(43, 'App\\Models\\Customer', 5, 'customer-api', 'e645be22633da226f5862bfac1e2e7134008a2e7f9553ddcd337e3ca1f90d0fc', '[\"*\"]', '2026-05-26 06:38:07', NULL, '2026-05-25 06:17:33', '2026-05-26 06:38:07'),
(44, 'App\\Models\\Customer', 1, 'customer-api', 'f62597b51ec9e5dc4f18f30807706b32650a25aded5d3369576885a60fcb54c1', '[\"*\"]', '2026-05-25 10:32:50', NULL, '2026-05-25 06:52:23', '2026-05-25 10:32:50'),
(45, 'App\\Models\\Customer', 3, 'customer-api', '212225d90f0e9d86a256b16ba75fc60f452ad0fc3dab8183371afbf98a84e096', '[\"*\"]', '2026-05-25 11:06:09', NULL, '2026-05-25 09:51:20', '2026-05-25 11:06:09'),
(46, 'App\\Models\\Customer', 5, 'customer-api', '4d976dc4178bc997c690ef1efe4d14c95db062fb09ec0f44c5472a1ab19f9655', '[\"*\"]', '2026-05-25 11:22:02', NULL, '2026-05-25 10:22:09', '2026-05-25 11:22:02'),
(47, 'App\\Models\\Customer', 1, 'customer-api', 'cc9639362c1adb670b8cf4e0a5df929bb6414740a09424e24c6d163e31d521fe', '[\"*\"]', '2026-05-25 11:36:38', NULL, '2026-05-25 10:35:30', '2026-05-25 11:36:38'),
(48, 'App\\Models\\Customer', 2, 'customer-api', 'f301ff07e5d8c8e70d1c10c9a9a588d2f0af8d88ace767f809ac43f49277134f', '[\"*\"]', '2026-05-26 05:31:30', NULL, '2026-05-26 05:31:12', '2026-05-26 05:31:30'),
(49, 'App\\Models\\Customer', 7, 'customer-api', 'eb3d8d1f712f40f8bc675b46710a9f1d9287c185cd8b1d3455c2b4efe4b9dc94', '[\"*\"]', '2026-05-26 05:54:20', NULL, '2026-05-26 05:53:56', '2026-05-26 05:54:20'),
(50, 'App\\Models\\Customer', 8, 'customer-api', '41e5bb52c5577e7d4f936a2bea1ff064e898367053cdea7a56f8976d5b3081cb', '[\"*\"]', '2026-05-26 06:29:27', NULL, '2026-05-26 05:56:08', '2026-05-26 06:29:27'),
(51, 'App\\Models\\Customer', 6, 'customer-api', '558103b361d41d09b35a3bb3520130ec9f69cd02fa9cacac26e41bb38170c59b', '[\"*\"]', '2026-05-26 06:39:00', NULL, '2026-05-26 06:38:23', '2026-05-26 06:39:00'),
(52, 'App\\Models\\Customer', 6, 'customer-api', '63782ac44c44280e7ecfc20a4c34eaccb925e2d13b8125e7cf208e9951c740bf', '[\"*\"]', '2026-05-26 06:39:22', NULL, '2026-05-26 06:39:14', '2026-05-26 06:39:22'),
(53, 'App\\Models\\Customer', 8, 'customer-api', '2c0d6bc6929f710379c9e8939ca1ad0d741cdbb816cbf932e42dabf19653a631', '[\"*\"]', '2026-06-12 06:34:07', NULL, '2026-05-26 06:39:33', '2026-06-12 06:34:07'),
(54, 'App\\Models\\Customer', 9, 'customer-api', 'eae3b3f339b994ecd50956a707b7652f2564a1cd561de6dfe08f17b71430f711', '[\"*\"]', '2026-05-26 06:52:40', NULL, '2026-05-26 06:50:10', '2026-05-26 06:52:40'),
(55, 'App\\Models\\Customer', 10, 'customer-api', '3f3eda7adc01c0f05929ba92f544a85dc5cc1594a28aa2ab134cd4dde43bcc94', '[\"*\"]', '2026-05-26 06:51:42', NULL, '2026-05-26 06:50:47', '2026-05-26 06:51:42'),
(56, 'App\\Models\\Customer', 5, 'customer-api', 'da7fac893e6cc26b43bd2d25cd75f323a44c75ed784accc2b8a003d9bf0498a3', '[\"*\"]', '2026-05-26 07:27:14', NULL, '2026-05-26 07:22:54', '2026-05-26 07:27:14'),
(57, 'App\\Models\\Customer', 5, 'customer-api', 'b191487838dea8543ef66d8015681d2f09b6bf53631f695813fd941c72c82b6b', '[\"*\"]', '2026-05-28 05:49:57', NULL, '2026-05-28 05:45:33', '2026-05-28 05:49:57'),
(58, 'App\\Models\\Customer', 5, 'customer-api', 'ddabc74d659a9793e72b06be4cb5e9658d6c6689ee9e32818ec5d4fcc4ac14fb', '[\"*\"]', '2026-06-17 11:53:17', NULL, '2026-05-28 05:50:15', '2026-06-17 11:53:17'),
(59, 'App\\Models\\Customer', 11, 'customer-api', '3651160306fcbe140901d30802fbed7b0ffea69a826704394d8d69835e131e25', '[\"*\"]', NULL, NULL, '2026-06-03 08:33:38', '2026-06-03 08:33:38'),
(60, 'App\\Models\\Customer', 11, 'customer-api', '5d9159388060886ef8adf451fe18084593b1e481cd7a4d0ade8d2cc095be8a70', '[\"*\"]', '2026-06-11 05:39:30', NULL, '2026-06-03 08:34:30', '2026-06-11 05:39:30'),
(61, 'App\\Models\\Customer', 12, 'customer-api', 'ff5d34d6e2f98b43c70b0e72afb71f0be606438ea3ef534fb238c9c9f15e1f51', '[\"*\"]', NULL, NULL, '2026-06-04 07:40:55', '2026-06-04 07:40:55'),
(62, 'App\\Models\\Customer', 12, 'customer-api', '51c4daf961962d91971d2f5225d3201d14c80439d802b00fa7ef5fee0798ee49', '[\"*\"]', '2026-06-22 12:03:57', NULL, '2026-06-04 07:41:04', '2026-06-22 12:03:57'),
(63, 'App\\Models\\Customer', 2, 'customer-api', '07b1f8463c79d148421f11d50c7b96baf17181e9a53a66fc7386fa3dddd94739', '[\"*\"]', '2026-06-08 10:18:00', NULL, '2026-06-08 10:17:37', '2026-06-08 10:18:00'),
(64, 'App\\Models\\Customer', 11, 'customer-api', 'ec0e6e26841973a02e1f21f007f6ddba859cc9554614f8a388b32debc439252e', '[\"*\"]', '2026-06-18 06:51:18', NULL, '2026-06-11 05:39:52', '2026-06-18 06:51:18'),
(65, 'App\\Models\\Customer', 3, 'customer-api', '25bfac74531df5c7fbc368a142702b01cc13a4d354af38f9d516dc44f3f4fe26', '[\"*\"]', '2026-06-12 08:12:48', NULL, '2026-06-12 08:12:39', '2026-06-12 08:12:48'),
(66, 'App\\Models\\Customer', 3, 'customer-api', '714f1562c5e5f12cdfdb3a82352b16ebb6fa80c3d0d340bc18323ace112c8c1e', '[\"*\"]', '2026-06-12 09:47:32', NULL, '2026-06-12 09:36:34', '2026-06-12 09:47:32'),
(67, 'App\\Models\\Customer', 3, 'customer-api', '412d37d58b053148cf0e97e9bb3fae78f2f58b650af16b123f25545351fb6dab', '[\"*\"]', '2026-06-18 07:30:40', NULL, '2026-06-12 09:58:11', '2026-06-18 07:30:40'),
(68, 'App\\Models\\Customer', 3, 'customer-api', '8101a73e0e70989297f5b01fb75a0642d6f67d1ecc4ccbb0bb9da77d3e3e7295', '[\"*\"]', '2026-06-17 11:31:30', NULL, '2026-06-17 10:44:09', '2026-06-17 11:31:30'),
(69, 'App\\Models\\Customer', 3, 'customer-api', '116eb7aecd261cdfadf0250113791fcfb45d4c58fd782d10583e02b5094b28a4', '[\"*\"]', '2026-06-19 12:09:11', NULL, '2026-06-17 11:53:27', '2026-06-19 12:09:11'),
(70, 'App\\Models\\Customer', 13, 'customer-api', '8c8512d24c34c70276fbc68e5c41914a387b40d981ee004588128286a1f46ca7', '[\"*\"]', NULL, NULL, '2026-06-18 07:41:34', '2026-06-18 07:41:34'),
(71, 'App\\Models\\Customer', 14, 'customer-api', '248d92502b7cdbf8e996b202849232804f11aabf15dcd167a1b9d18f073e1fb1', '[\"*\"]', NULL, NULL, '2026-06-18 07:48:38', '2026-06-18 07:48:38'),
(72, 'App\\Models\\Customer', 14, 'customer-api', 'd401d0f8ca93136db9204464df9b3ab4d2ea5226ed9c83ccd890ee57d374878b', '[\"*\"]', '2026-06-18 07:50:36', NULL, '2026-06-18 07:48:59', '2026-06-18 07:50:36'),
(73, 'App\\Models\\Customer', 15, 'customer-api', '0866d8f80b4c058590a3bcc465b54727cc80ab688af5be02b63fe43f16eb1216', '[\"*\"]', NULL, NULL, '2026-06-18 07:53:04', '2026-06-18 07:53:04'),
(74, 'App\\Models\\Customer', 3, 'customer-api', '87c3c19059b1d1e58d1c097dd00f55c7a023fd6059fedffb720499d8e6208cb2', '[\"*\"]', '2026-06-19 07:50:25', NULL, '2026-06-18 07:53:29', '2026-06-19 07:50:25'),
(75, 'App\\Models\\Customer', 14, 'customer-api', 'dd6938cb707178480dbeff3e4ba49e30b851d8752c639ee4d1a7d2e7cc92028f', '[\"*\"]', '2026-06-19 09:42:52', NULL, '2026-06-19 07:50:36', '2026-06-19 09:42:52'),
(76, 'App\\Models\\Customer', 1, 'customer-api', '9c0344630de385335cfda1a90985e5d8d1c4ebd790e2731081c2633bea8ee90f', '[\"*\"]', '2026-06-19 12:12:11', NULL, '2026-06-19 09:55:49', '2026-06-19 12:12:11'),
(77, 'App\\Models\\Customer', 14, 'customer-api', 'e0cc92225b3dc245c7ddc210a49dca83898be1bdf2848013dfd3e5acca7b4115', '[\"*\"]', '2026-06-22 05:08:43', NULL, '2026-06-19 12:09:21', '2026-06-22 05:08:43'),
(78, 'App\\Models\\Customer', 14, 'customer-api', '3c93fd9764ac40a546aeded3011c33202049dfc1c9471c31716e57742f995477', '[\"*\"]', '2026-06-22 05:10:26', NULL, '2026-06-22 05:09:41', '2026-06-22 05:10:26'),
(79, 'App\\Models\\Customer', 14, 'customer-api', 'cb788eda8e6d645e8238867d25c1d106b656c75b6ead69afe0f8338f07b0ed6f', '[\"*\"]', '2026-06-22 05:29:46', NULL, '2026-06-22 05:13:59', '2026-06-22 05:29:46'),
(80, 'App\\Models\\Customer', 3, 'customer-api', '2465bca6aea62b3ed2909249314873113aea33c03b8bf8dcb390490fb7bc3309', '[\"*\"]', '2026-06-22 06:16:39', NULL, '2026-06-22 05:39:19', '2026-06-22 06:16:39'),
(81, 'App\\Models\\Customer', 3, 'customer-api', '9739046798707e9e8dc6e742399a9434f74aba0c7c188423063ae4ce53c1087f', '[\"*\"]', '2026-06-22 10:35:17', NULL, '2026-06-22 06:01:59', '2026-06-22 10:35:17'),
(82, 'App\\Models\\Customer', 1, 'customer-api', '385dd45649370f37c4350b7586b625860b2c3eb7538a94929c607a57292aa102', '[\"*\"]', '2026-06-22 06:57:13', NULL, '2026-06-22 06:09:30', '2026-06-22 06:57:13'),
(83, 'App\\Models\\Customer', 14, 'customer-api', '84e0d7d76cbf080ba13ed418eeb1b618936a0cb961d14ad703f7191ce8b95e46', '[\"*\"]', '2026-06-22 07:49:24', NULL, '2026-06-22 06:16:55', '2026-06-22 07:49:24'),
(84, 'App\\Models\\Customer', 2, 'customer-api', 'c361cd02273ba4493658844d3e41f7248a88d259341eee9f5f38235dea8fa472', '[\"*\"]', '2026-06-22 06:53:27', NULL, '2026-06-22 06:53:05', '2026-06-22 06:53:27'),
(85, 'App\\Models\\Customer', 14, 'customer-api', '8c18e5e7c900545a6a4bca459af969e36f7f21102b8d219f4e51ece52f60d5d4', '[\"*\"]', '2026-06-22 10:39:44', NULL, '2026-06-22 10:38:41', '2026-06-22 10:39:44'),
(86, 'App\\Models\\Customer', 14, 'customer-api', '1bedf348f5e708ae195711457a36803a6ac9a44e885db97613939df84106d9b3', '[\"*\"]', '2026-06-23 05:58:05', NULL, '2026-06-22 10:40:29', '2026-06-23 05:58:05'),
(87, 'App\\Models\\Customer', 15, 'customer-api', '0f2326a97bf2c5a1b7f1c64664bb58282ab60bb1033b115a66fd25aee0ef330d', '[\"*\"]', '2026-07-06 11:06:44', NULL, '2026-06-22 12:03:05', '2026-07-06 11:06:44'),
(88, 'App\\Models\\Customer', 12, 'customer-api', '040a19c7e07f65c96f66757e50ff0c56217e9ac9f12de84c116b5007d98f561f', '[\"*\"]', '2026-06-22 12:04:52', NULL, '2026-06-22 12:04:12', '2026-06-22 12:04:52'),
(89, 'App\\Models\\Customer', 14, 'customer-api', '249ee49ec18cef0a7637423d91ec9f96e15a364caebe58cb9181474089047802', '[\"*\"]', '2026-06-23 10:41:44', NULL, '2026-06-23 05:58:20', '2026-06-23 10:41:44'),
(90, 'App\\Models\\Customer', 11, 'customer-api', '6c0145ac6a9f87c664d135a601688c9abd04600d87dcffaeb01964b7749ca785', '[\"*\"]', '2026-06-24 12:02:42', NULL, '2026-06-23 06:35:00', '2026-06-24 12:02:42'),
(91, 'App\\Models\\Customer', 12, 'customer-api', '7a16852f4ba2711f14237246c291cf365484c60b765429eb4b5b479e36922ca0', '[\"*\"]', '2026-06-23 10:22:55', NULL, '2026-06-23 10:20:50', '2026-06-23 10:22:55'),
(92, 'App\\Models\\Customer', 12, 'customer-api', '669bd7909f940d91a7a6691280dd8425c001fe598d4b051b19e4368c6250a3ec', '[\"*\"]', '2026-06-24 13:18:19', NULL, '2026-06-23 10:28:12', '2026-06-24 13:18:19'),
(93, 'App\\Models\\Customer', 14, 'customer-api', '30717e5245ab6b173efbb554398580a3967398f5f4848cd98f3eed24df809ea1', '[\"*\"]', '2026-06-24 04:57:04', NULL, '2026-06-23 10:42:00', '2026-06-24 04:57:04'),
(94, 'App\\Models\\Customer', 14, 'customer-api', 'ba91e730590b02b71dfc4234d83041b8eacea0cdf05bc52477417813fea083c0', '[\"*\"]', '2026-06-24 05:17:06', NULL, '2026-06-24 05:15:54', '2026-06-24 05:17:06'),
(95, 'App\\Models\\Customer', 14, 'customer-api', '146714ce37ed96042dcc594a5c7b2ba6d9f4c808ef67edd2b458be296d933429', '[\"*\"]', '2026-06-24 07:32:38', NULL, '2026-06-24 07:32:08', '2026-06-24 07:32:38'),
(96, 'App\\Models\\Customer', 14, 'customer-api', '4a1dbccfdf17753bbee54bcde9024e1745cda7eabd2d2bbc5ef778002afb4cd3', '[\"*\"]', '2026-06-24 10:23:41', NULL, '2026-06-24 09:03:57', '2026-06-24 10:23:41'),
(97, 'App\\Models\\Customer', 14, 'customer-api', '3211a5d169cbc6067859838c977771111d02fe779d615b87585ac4b59badf07c', '[\"*\"]', '2026-06-24 11:43:51', NULL, '2026-06-24 09:30:06', '2026-06-24 11:43:51'),
(98, 'App\\Models\\Customer', 14, 'customer-api', '7b25560c02dc7114e6b4bc9046f657b552f661737293db2752c1f083296dd7a7', '[\"*\"]', '2026-06-24 11:11:27', NULL, '2026-06-24 10:47:12', '2026-06-24 11:11:27'),
(99, 'App\\Models\\Customer', 16, 'customer-api', '38fcaa4b360de8475bed1b5b341acf20b3e969dbd827581ae61a1864974575a1', '[\"*\"]', NULL, NULL, '2026-06-24 11:18:32', '2026-06-24 11:18:32'),
(100, 'App\\Models\\Customer', 16, 'customer-api', '8df201e81e2a07a896b1eb4233a6c7dfdb26f9b9a9dad4cb94c9c9b3066625f3', '[\"*\"]', '2026-06-24 11:20:47', NULL, '2026-06-24 11:20:05', '2026-06-24 11:20:47'),
(101, 'App\\Models\\Customer', 14, 'customer-api', '1dd23305c0c2a694beeb04aa3449e87abe5b9d549c87c1b7db044104b6ee33f1', '[\"*\"]', '2026-06-24 11:45:48', NULL, '2026-06-24 11:21:06', '2026-06-24 11:45:48'),
(102, 'App\\Models\\Customer', 14, 'customer-api', 'f82248a20e4718eb58bf7530fbe5068ef219602369641e0b0d0b8ed426cf4cee', '[\"*\"]', '2026-07-01 11:07:07', NULL, '2026-06-24 11:47:23', '2026-07-01 11:07:07'),
(103, 'App\\Models\\Customer', 11, 'customer-api', 'a7a135275c9267ceeeefe0525e065fa6740e8ddfc85c04a345e123fbe3c5c4b4', '[\"*\"]', '2026-06-30 06:56:49', NULL, '2026-06-24 12:03:10', '2026-06-30 06:56:49'),
(104, 'App\\Models\\Customer', 1, 'customer-api', '6043ce54614c1b94a500f2d83ea5b1e361b90289340b14bfe2e9e638abb6dd56', '[\"*\"]', '2026-06-24 12:25:03', NULL, '2026-06-24 12:23:22', '2026-06-24 12:25:03'),
(105, 'App\\Models\\Customer', 12, 'customer-api', 'bd97c485db3986ad9a86c7abb3cdf515fbba928b109e8cbd1e2a347ccadb829a', '[\"*\"]', '2026-07-08 07:11:07', NULL, '2026-06-24 13:18:55', '2026-07-08 07:11:07'),
(106, 'App\\Models\\Customer', 17, 'customer-api', 'a857bf5fc585724e49c8ff266bfd833e3ded3d5aa75eccb4c9b1814f3dc30b2f', '[\"*\"]', NULL, NULL, '2026-06-25 07:02:01', '2026-06-25 07:02:01'),
(107, 'App\\Models\\Customer', 17, 'customer-api', 'f26183c96ef2f2f1abd819d012dd13651a598d93fe9760b887352ed2f6149565', '[\"*\"]', '2026-06-25 07:04:45', NULL, '2026-06-25 07:02:20', '2026-06-25 07:04:45'),
(108, 'App\\Models\\Customer', 14, 'customer-api', '7146494834a09d204a1222d963d1ef049ddf8fd925628cb3e8853dd575eec8e3', '[\"*\"]', '2026-07-27 05:41:20', NULL, '2026-06-29 11:35:34', '2026-07-27 05:41:20'),
(109, 'App\\Models\\Customer', 11, 'customer-api', '54ddd828b9a53304ce8d31e3cf99bed2e718695707d3423189752048e4396e3a', '[\"*\"]', '2026-07-02 05:42:44', NULL, '2026-06-30 06:58:24', '2026-07-02 05:42:44'),
(110, 'App\\Models\\Customer', 1, 'customer-api', 'f2352f8ac7881f0c127b493ff2afc37eb7152c20c135618dbac9afd907890454', '[\"*\"]', '2026-07-01 11:11:17', NULL, '2026-07-01 10:31:21', '2026-07-01 11:11:17'),
(111, 'App\\Models\\Customer', 1, 'customer-api', 'ae3115a4592d02f41ff7489c761398a4853d0790e63b34ab0f86ff602a7d0ada', '[\"*\"]', '2026-07-07 08:02:06', NULL, '2026-07-01 11:11:12', '2026-07-07 08:02:06'),
(112, 'App\\Models\\Customer', 18, 'customer-api', '5eb8b1b4d9c57a7cf7c8db193a463e4d93cf3aefd01c86ebc6496ea3f8e6d0af', '[\"*\"]', NULL, NULL, '2026-07-01 13:16:39', '2026-07-01 13:16:39'),
(113, 'App\\Models\\Customer', 18, 'customer-api', '505c61189b552160121980a0a02125ab0ea1d3f78da4538400431b18b64c6b14', '[\"*\"]', '2026-08-05 12:15:21', NULL, '2026-07-01 13:17:19', '2026-08-05 12:15:21'),
(114, 'App\\Models\\Customer', 19, 'customer-api', '3bbe7b9bb96a235e69059d4540795f60c003b0eea6b5a8a11ef74c669ecf2ceb', '[\"*\"]', NULL, NULL, '2026-07-01 13:54:22', '2026-07-01 13:54:22'),
(115, 'App\\Models\\Customer', 19, 'customer-api', '428971851bca33d71cb5a8fa5a158f8b70cadc24030952baeb0da49502938400', '[\"*\"]', '2026-07-01 13:58:01', NULL, '2026-07-01 13:54:45', '2026-07-01 13:58:01'),
(116, 'App\\Models\\Customer', 11, 'customer-api', 'aa96b9880f9521bac69ca598524767de613df9e21c8d7e8b3e263670bab76f4d', '[\"*\"]', '2026-07-02 09:15:13', NULL, '2026-07-02 05:43:57', '2026-07-02 09:15:13'),
(117, 'App\\Models\\Customer', 1, 'customer-api', 'fd4fd2415c4b046cbf7289deb09e864483d84383be949e99f26c15b97fb3871d', '[\"*\"]', '2026-07-02 06:13:47', NULL, '2026-07-02 05:56:39', '2026-07-02 06:13:47'),
(118, 'App\\Models\\Customer', 11, 'customer-api', 'ec858c6139c983b43c13b85507d10563ccd595dd34dac31dc55a5f39cbafc9e2', '[\"*\"]', '2026-07-08 13:06:43', NULL, '2026-07-02 09:15:36', '2026-07-08 13:06:43'),
(119, 'App\\Models\\Customer', 1, 'customer-api', '54ae3edec0cb9edfe0740e5ed1663489a6d43dcf7e83891cf3fde88bb35772e1', '[\"*\"]', '2026-07-02 18:12:17', NULL, '2026-07-02 18:08:20', '2026-07-02 18:12:17'),
(120, 'App\\Models\\Customer', 3, 'customer-api', '120f1549fd0b5b8b31a04a206db8d7331b331235e96cb499f766eac71ae56685', '[\"*\"]', '2026-07-07 08:02:42', NULL, '2026-07-07 08:02:38', '2026-07-07 08:02:42'),
(121, 'App\\Models\\Customer', 14, 'customer-api', '4a72801ec2540f09054d434530047f833bc8853c8db1c8a1336b83cecf0281dc', '[\"*\"]', '2026-07-07 08:14:45', NULL, '2026-07-07 08:03:38', '2026-07-07 08:14:45'),
(122, 'App\\Models\\Customer', 20, 'customer-api', '2c75fca7c63556266859bb2f6e5cbbee081871b331461ee4c5184f0f412282bd', '[\"*\"]', NULL, NULL, '2026-07-07 08:15:24', '2026-07-07 08:15:24'),
(123, 'App\\Models\\Customer', 20, 'customer-api', '1f2d9ae1c5cde2bd19053977ba4c416682655bafddc086a96a68ff8630ecee06', '[\"*\"]', '2026-07-07 09:31:56', NULL, '2026-07-07 08:15:45', '2026-07-07 09:31:56'),
(124, 'App\\Models\\Customer', 14, 'customer-api', 'e71adc23b60ebb77a0439d9790ff331b38e3e163e223828c897a2a66b61d24da', '[\"*\"]', '2026-08-04 05:49:13', NULL, '2026-07-07 09:33:40', '2026-08-04 05:49:13'),
(125, 'App\\Models\\Customer', 14, 'customer-api', '6d239927fb64961e669edfc5dc6ef329dbabd47948d4869650dfcd56dc6d66ca', '[\"*\"]', '2026-07-08 09:56:28', NULL, '2026-07-08 09:55:44', '2026-07-08 09:56:28'),
(126, 'App\\Models\\Customer', 21, 'customer-api', '42259d16958e04696a23604cb8d0033701373aa9e781e39d67f1f45c314da8e1', '[\"*\"]', NULL, NULL, '2026-07-08 12:11:29', '2026-07-08 12:11:29'),
(127, 'App\\Models\\Customer', 21, 'customer-api', '79a8a011889e2b752e6ae7f7fe2766aae1424b173e72d95d20aef4de5dc003d4', '[\"*\"]', '2026-08-05 10:21:19', NULL, '2026-07-08 12:11:50', '2026-08-05 10:21:19'),
(128, 'App\\Models\\Customer', 19, 'customer-api', 'db904c1b2aec94ad45cea72dd80d4b7a25d720b56cad1ab1fec6c32fee4f4e94', '[\"*\"]', '2026-08-05 13:53:52', NULL, '2026-07-08 12:40:35', '2026-08-05 13:53:52'),
(129, 'App\\Models\\Customer', 14, 'customer-api', '8784cf3b19702d1b2292187917551f0568ac9c9feda3495e0bf48adcbe03511a', '[\"*\"]', '2026-07-15 10:00:40', NULL, '2026-07-09 04:54:10', '2026-07-15 10:00:40'),
(130, 'App\\Models\\Customer', 12, 'customer-api', '1e2e5abb76da7a21c78052884ad8648b3d81463e555c2ee7c891521c4f3eaffd', '[\"*\"]', '2026-07-24 07:26:33', NULL, '2026-07-09 05:11:21', '2026-07-24 07:26:33'),
(131, 'App\\Models\\Customer', 11, 'customer-api', '721401d8651a722234070d912c461737ab9a79d0e2fd4e92aeb1d50f8085b404', '[\"*\"]', '2026-07-14 13:51:15', NULL, '2026-07-11 07:42:19', '2026-07-14 13:51:15'),
(132, 'App\\Models\\Customer', 22, 'customer-api', '5330cc893e327e814384d0d1877e020308e1daaac0b082702e500e0222744172', '[\"*\"]', NULL, NULL, '2026-07-14 13:51:55', '2026-07-14 13:51:55'),
(133, 'App\\Models\\Customer', 22, 'customer-api', 'd7419c7d21cb611defd98bb91cec552830b9417946ac2227a395ad486ed11f73', '[\"*\"]', '2026-07-14 13:52:18', NULL, '2026-07-14 13:52:12', '2026-07-14 13:52:18'),
(134, 'App\\Models\\Customer', 22, 'customer-api', 'e25fc2716d556d8e7ff2fc173936d394df9b04f14cd73f76976609af9aae8e94', '[\"*\"]', '2026-07-15 06:20:00', NULL, '2026-07-15 06:05:01', '2026-07-15 06:20:00'),
(135, 'App\\Models\\Customer', 22, 'customer-api', '07bcae6e3708632fa2bd43469ab084f31e8df815a1ae151ea896274ae78da8b0', '[\"*\"]', '2026-07-15 09:28:30', NULL, '2026-07-15 09:25:45', '2026-07-15 09:28:30'),
(136, 'App\\Models\\Customer', 22, 'customer-api', '28d2a406710ba15d7d7c4b002e08df02b3388212feca6370c4ee83396f56cf41', '[\"*\"]', '2026-08-06 04:32:06', NULL, '2026-07-15 10:30:05', '2026-08-06 04:32:06'),
(137, 'App\\Models\\Customer', 1, 'customer-api', 'f7f73f1e1c00c5e67caa9cd86b9d9676da890ae2ccd6fd374f4730baf86dfde4', '[\"*\"]', '2026-07-20 06:02:52', NULL, '2026-07-20 05:56:04', '2026-07-20 06:02:52'),
(138, 'App\\Models\\Customer', 14, 'customer-api', 'ae993ee52b944da70d6dc9d022bbe17ace4aaf69145e588e8f7306b38b9c081f', '[\"*\"]', '2026-07-20 13:14:42', NULL, '2026-07-20 05:57:07', '2026-07-20 13:14:42'),
(139, 'App\\Models\\Customer', 1, 'customer-api', 'b15ca55c1c8967c962833f1808903df3215ea66528a6fe301fbd7f962af45249', '[\"*\"]', '2026-07-20 07:31:38', NULL, '2026-07-20 06:40:39', '2026-07-20 07:31:38'),
(140, 'App\\Models\\Customer', 12, 'customer-api', '12baa6a326f5cae319a3889512e5c576f6fa9cc74d7549cf3f1483c3c532e2f4', '[\"*\"]', '2026-07-26 08:40:13', NULL, '2026-07-24 11:11:01', '2026-07-26 08:40:13'),
(141, 'App\\Models\\Customer', 23, 'customer-api', '28fa33a349a1413b7eb2aeb6eed4e1f5929564418e6527ba22c3263e1ae55cd6', '[\"*\"]', NULL, NULL, '2026-07-26 08:41:07', '2026-07-26 08:41:07'),
(142, 'App\\Models\\Customer', 23, 'customer-api', 'c9f946fb48a392291ed61df0d598ff239d947c7af2f3173ee90c759fdc7814e3', '[\"*\"]', '2026-07-31 08:51:19', NULL, '2026-07-26 08:41:36', '2026-07-31 08:51:19'),
(143, 'App\\Models\\Customer', 12, 'customer-api', '5045030ff867d6b66408534897dd9b427fe15db5f03aed1ab91f171770dfddf1', '[\"*\"]', '2026-08-06 04:32:10', NULL, '2026-07-26 09:43:48', '2026-08-06 04:32:10'),
(144, 'App\\Models\\Customer', 14, 'customer-api', 'dfe2b492ab1416c24e9511e859175fb734f00078a9709d6ba9ad1b6b8724040e', '[\"*\"]', '2026-07-30 09:38:47', NULL, '2026-07-27 05:41:45', '2026-07-30 09:38:47'),
(145, 'App\\Models\\Customer', 1, 'customer-api', '7beaf35f8cb9e1cce56184d7b5323b59f9ac0a246ac9988fe014dfb50b2ba3ae', '[\"*\"]', '2026-08-07 12:27:25', NULL, '2026-08-03 08:19:39', '2026-08-07 12:27:25'),
(146, 'App\\Models\\Customer', 24, 'customer-api', '0806c03878861b4aa739ba974e1bc1d5d194a3b366541447b8c495c4d71a79b5', '[\"*\"]', NULL, NULL, '2026-08-06 06:19:02', '2026-08-06 06:19:02'),
(147, 'App\\Models\\Customer', 24, 'customer-api', 'd819def59f05ff9731a41ce35ddcb2c4bce7566b1c73cb0eab4c4a10ea6f6e53', '[\"*\"]', '2026-08-07 07:23:35', NULL, '2026-08-06 06:19:23', '2026-08-07 07:23:35'),
(148, 'App\\Models\\Customer', 25, 'customer-api', 'a08da63be0978c5dd9b3691d23c8519d60a30b3bcdcebaf954946e906410ade1', '[\"*\"]', NULL, NULL, '2026-08-06 06:54:11', '2026-08-06 06:54:11'),
(149, 'App\\Models\\Customer', 25, 'customer-api', '1e37e8ef4f37c324df1d6c59c33337107ce325c1f707d04942362f75584184b3', '[\"*\"]', '2026-08-08 03:05:46', NULL, '2026-08-06 06:54:21', '2026-08-08 03:05:46'),
(150, 'App\\Models\\Customer', 26, 'customer-api', '9f44a08c4408907775b61778074ae30496656162d7fb975c231fb3aa092d3ebc', '[\"*\"]', NULL, NULL, '2026-08-06 09:57:29', '2026-08-06 09:57:29'),
(151, 'App\\Models\\Customer', 26, 'customer-api', '71f7edcac241d1a7d1ce0d7f4efbd8c6d866d132040c4ee75d1d9369902836f1', '[\"*\"]', '2026-08-08 09:46:21', NULL, '2026-08-06 09:58:08', '2026-08-08 09:46:21'),
(152, 'App\\Models\\Customer', 27, 'customer-api', 'af2a479ee656f57da028d291db75aa762724760e7e12225bcf9f34d566e46daf', '[\"*\"]', NULL, NULL, '2026-08-06 10:59:44', '2026-08-06 10:59:44'),
(153, 'App\\Models\\Customer', 27, 'customer-api', '7f0e0f759939aa1ce4c49552ebc3a7dddb1574481909374a6ab556277212546c', '[\"*\"]', '2026-08-08 16:10:52', NULL, '2026-08-06 10:59:57', '2026-08-08 16:10:52'),
(154, 'App\\Models\\Customer', 28, 'customer-api', 'cf0eb18c10d9a6eed7fbc35c84da14559197e98df2e80bc1763df99c3481a85b', '[\"*\"]', NULL, NULL, '2026-08-06 12:13:57', '2026-08-06 12:13:57'),
(155, 'App\\Models\\Customer', 28, 'customer-api', 'fb25d7a564302125e001780b063ea343405582ad58a1ce6cbc4abd7f6d9f5168', '[\"*\"]', '2026-08-09 09:27:10', NULL, '2026-08-06 12:14:18', '2026-08-09 09:27:10'),
(156, 'App\\Models\\Customer', 29, 'customer-api', '23439aeb3bb599787f566c5291d3b9ee9e9fc58f0785a47eb6a42aa20f1357b6', '[\"*\"]', NULL, NULL, '2026-08-06 12:16:33', '2026-08-06 12:16:33'),
(157, 'App\\Models\\Customer', 29, 'customer-api', 'b27e926fc67ec357dbdf3b52abba304774405421184fd19c34f7589d1393c5ec', '[\"*\"]', '2026-08-06 12:25:38', NULL, '2026-08-06 12:16:49', '2026-08-06 12:25:38'),
(158, 'App\\Models\\Customer', 30, 'customer-api', 'd8386383d17621430504eb678092f01c60b781d90d05673bfd44b99a18b3a694', '[\"*\"]', NULL, NULL, '2026-08-06 15:55:01', '2026-08-06 15:55:01'),
(159, 'App\\Models\\Customer', 30, 'customer-api', '30b9f7154704227ebdfcc1b688855e0ee5646b2629318b87db0f0401f421615b', '[\"*\"]', '2026-08-07 03:50:16', NULL, '2026-08-06 15:55:18', '2026-08-07 03:50:16'),
(160, 'App\\Models\\Customer', 31, 'customer-api', 'a5b470188abbe35a871999f85fc8791d1f0415f388c55043e6a88873c61f4cc4', '[\"*\"]', NULL, NULL, '2026-08-06 17:00:46', '2026-08-06 17:00:46'),
(161, 'App\\Models\\Customer', 31, 'customer-api', '4da062bcbb4d5cff0f523ddd019e463d3289b90119c59b2f26aecba8ff68539c', '[\"*\"]', '2026-08-07 16:40:34', NULL, '2026-08-06 17:01:17', '2026-08-07 16:40:34'),
(162, 'App\\Models\\Customer', 32, 'customer-api', '857743c3359317d4f2ce1801e2a6ed2a2c025c1cae327e5bec75a27bdbc529f4', '[\"*\"]', NULL, NULL, '2026-08-06 17:23:16', '2026-08-06 17:23:16'),
(163, 'App\\Models\\Customer', 32, 'customer-api', '0a9c9a634b3516ef43db5b546ec4f5e0a6451a49a7b9bac6720a7d46b4880b70', '[\"*\"]', '2026-08-09 13:35:59', NULL, '2026-08-06 17:23:35', '2026-08-09 13:35:59'),
(164, 'App\\Models\\Customer', 33, 'customer-api', '1d1c7d1d52e5de719cdea9d0e8d87e88dcd5881d8277b786188fe3e051945905', '[\"*\"]', NULL, NULL, '2026-08-07 05:32:33', '2026-08-07 05:32:33'),
(165, 'App\\Models\\Customer', 33, 'customer-api', 'f9cc86a798346495ae9e95f92c92a59badea9f71ffec2b84974b44fdf46ac3f3', '[\"*\"]', '2026-08-07 15:11:09', NULL, '2026-08-07 05:33:05', '2026-08-07 15:11:09'),
(166, 'App\\Models\\Customer', 34, 'customer-api', 'bb1f797c76fa674c6c57387b34cc426e0bf2612feef4856235d2937aeb177823', '[\"*\"]', NULL, NULL, '2026-08-07 06:24:43', '2026-08-07 06:24:43'),
(167, 'App\\Models\\Customer', 34, 'customer-api', 'd7e4cb2c087663000eee5972e871071f4c7f0090424596d671afb9b1c3246780', '[\"*\"]', '2026-08-07 06:37:45', NULL, '2026-08-07 06:24:57', '2026-08-07 06:37:45'),
(168, 'App\\Models\\Customer', 35, 'customer-api', '2dc7fde849d0ab2e513970c5ca5bee73968af380befd2c81370b9018dfeafaa2', '[\"*\"]', NULL, NULL, '2026-08-07 06:27:43', '2026-08-07 06:27:43'),
(169, 'App\\Models\\Customer', 35, 'customer-api', 'd246ffaa703e9658e83cc6109141b3e23628ff388cf753349149892f2cb49f7f', '[\"*\"]', '2026-08-08 10:32:58', NULL, '2026-08-07 06:28:26', '2026-08-08 10:32:58'),
(170, 'App\\Models\\Customer', 24, 'customer-api', '484dbd3cb6f2f5fb9b82914ed9b7c78843c4805f69b4db00f2191f9657cb6170', '[\"*\"]', '2026-08-07 08:04:17', NULL, '2026-08-07 08:03:45', '2026-08-07 08:04:17'),
(171, 'App\\Models\\Customer', 34, 'customer-api', '554445a63ab074d0656674ec2b7c6f840d6c8cab866b75b45a6d6bd4ef95e676', '[\"*\"]', '2026-08-07 08:06:30', NULL, '2026-08-07 08:05:52', '2026-08-07 08:06:30'),
(172, 'App\\Models\\Customer', 36, 'customer-api', '2c725a6b4eedd5eb895824f94f4b7991f5ac644ce9f0a68d7b3aea48b0ac3609', '[\"*\"]', NULL, NULL, '2026-08-07 09:09:02', '2026-08-07 09:09:02'),
(173, 'App\\Models\\Customer', 36, 'customer-api', '6d8cab333065169231ac89c932de5b30821a9df479abb9a452b4af4772cfcc32', '[\"*\"]', '2026-08-08 04:59:38', NULL, '2026-08-07 09:09:26', '2026-08-08 04:59:38'),
(174, 'App\\Models\\Customer', 24, 'customer-api', '296cad60b528abfb2fbff93742fbb0d806bfc8385060089a31482f3634d0de4a', '[\"*\"]', '2026-08-10 01:14:11', NULL, '2026-08-07 09:16:15', '2026-08-10 01:14:11'),
(175, 'App\\Models\\Customer', 37, 'customer-api', 'dcf3fd7bd2c99da3a89bb4f7ed5b8ed87c55cddc5428897bd091eddfd01b3d5a', '[\"*\"]', NULL, NULL, '2026-08-07 13:24:46', '2026-08-07 13:24:46'),
(176, 'App\\Models\\Customer', 37, 'customer-api', '3fade9407bbaa810119002c9575d6bc366480c967802b98de4e030c920bb0bf8', '[\"*\"]', '2026-08-08 07:45:03', NULL, '2026-08-07 13:25:07', '2026-08-08 07:45:03'),
(177, 'App\\Models\\Customer', 38, 'customer-api', 'dabf547380c1f228a7645191c20b8b275a369129ce994de1743d76951e346fe1', '[\"*\"]', NULL, NULL, '2026-08-07 13:32:24', '2026-08-07 13:32:24'),
(178, 'App\\Models\\Customer', 38, 'customer-api', '322e53d5ca693c4627d36a427bbbe096266edbdc5cdb45a4834614b447a6facb', '[\"*\"]', '2026-08-08 04:50:21', NULL, '2026-08-07 13:32:38', '2026-08-08 04:50:21'),
(179, 'App\\Models\\Customer', 39, 'customer-api', '78f5c5316a85d49acb4748c23ce95a076db668929911b25738ac47816acac657', '[\"*\"]', NULL, NULL, '2026-08-07 13:59:16', '2026-08-07 13:59:16'),
(180, 'App\\Models\\Customer', 39, 'customer-api', '03983a59197cde8cc74d724ea57206d51c39b69ba70120732ab4389958f2bcd0', '[\"*\"]', '2026-08-09 07:03:48', NULL, '2026-08-07 13:59:50', '2026-08-09 07:03:48'),
(181, 'App\\Models\\Customer', 40, 'customer-api', 'b38276b03c59f975363771dc9a8a540192dda27ea1bca1fe4c6974be04615fe4', '[\"*\"]', NULL, NULL, '2026-08-08 01:28:26', '2026-08-08 01:28:26'),
(182, 'App\\Models\\Customer', 40, 'customer-api', 'cbf0aca3899a020ffa780f0a39d0901bf1ddb7e2d4630d682be2dfbb10d57ad8', '[\"*\"]', '2026-08-08 01:32:47', NULL, '2026-08-08 01:29:26', '2026-08-08 01:32:47'),
(183, 'App\\Models\\Customer', 41, 'customer-api', 'e0c91f85610cde4b79d3417eb6277ce8afc81c3b40d5ae1e76be37de4729f1e2', '[\"*\"]', NULL, NULL, '2026-08-08 16:32:48', '2026-08-08 16:32:48'),
(184, 'App\\Models\\Customer', 41, 'customer-api', '1f6e819b714aed7b3cf8fd05e032930d69789c18cc5cb7ee30b011bfed65447a', '[\"*\"]', '2026-08-09 06:53:52', NULL, '2026-08-08 16:32:58', '2026-08-09 06:53:52'),
(185, 'App\\Models\\Customer', 39, 'customer-api', 'c0c0a2a3ce8d46262877cd12c055f74e17578ca7cfb20dacf7a4216386e5ea6c', '[\"*\"]', '2026-08-09 09:40:58', NULL, '2026-08-09 07:07:02', '2026-08-09 09:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-05-12 05:04:12', '2026-05-12 05:04:12'),
(2, 'mananger', 'web', '2026-05-12 05:17:20', '2026-05-12 05:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(14, 2),
(15, 2),
(17, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5qCYRGz8cPxHWJwggs7YP4HJyYfuAvPZP4QYOGkV', 1, '157.49.106.191', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMnNkRDVTbjBadmZnTlliUnhxUDBCcUhYOEpmekljdUFiZTRScHBCeiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vbHVja3liYWJ5LmluL3RpY2tldC9wdWJsaWMvYWRtaW4vc2xvdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkNGpxVzdqQ3ZJQm9DMnJxT1ZXMFc3dUNsaEFma2tVV215WDZadEFYYnZIN2h4RXdWdEpMaGkiO30=', 1786268530),
('fsUqr8XxxeA3kn7puJXkAqSYgy8K2L4GsyXZgOoz', 1, '152.57.95.150', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicHoxdEdUWkg5aEpnV2lZTmV6WTduR1Zoa09SRWRmc1FIczBieDBiQyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo3NToiaHR0cHM6Ly9sdWNreWJhYnkuaW4vdGlja2V0L3B1YmxpYy9hZG1pbi9yZXBvcnRzL3dpbm5pbmdzLXNsb3RzLzQ0NS90aWNrZXRzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjM6Imh0dHBzOi8vbHVja3liYWJ5LmluL3RpY2tldC9wdWJsaWMvYWRtaW4vcmVwb3J0cy93aW5uaW5ncy1zbG90cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMiQ0anFXN2pDdklCb0MycnFPVlcwVzd1Q2xoQWZra1VXbXlYNlp0QVhidkg3aHhFd1Z0SkxoaSI7fQ==', 1786334743),
('TfIjs7NtOcagFgQnlkEdtgFumAZAN636IzFtKyMY', 1, '106.51.26.247', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZXVvZHZPWjR6ZDAzcHpPZHgydFB4SjR6emFXa1lUNU1RNlVXUFVtYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHBzOi8vbHVja3liYWJ5LmluL3RpY2tldC9wdWJsaWMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkNGpxVzdqQ3ZJQm9DMnJxT1ZXMFc3dUNsaEFma2tVV215WDZadEFYYnZIN2h4RXdWdEpMaGkiO30=', 1786335014),
('uf9N1LKKID4YJVosLywL1XaEValM9zhqLGGnUMfu', 1, '157.49.107.31', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQTR4SW9kRXpTNDhWSUFmN3QwRmEwQXlMUUdXUVBkbUE5TlZqMTJ2UiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDY6Imh0dHBzOi8vbHVja3liYWJ5LmluL3RpY2tldC9wdWJsaWMvYWRtaW4vc2xvdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkNGpxVzdqQ3ZJQm9DMnJxT1ZXMFc3dUNsaEFma2tVV215WDZadEFYYnZIN2h4RXdWdEpMaGkiO30=', 1786286615);

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `slot_id` bigint UNSIGNED NOT NULL,
  `main_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `draw_date` date DEFAULT NULL,
  `draw_time` time DEFAULT NULL,
  `booking_close_time` time DEFAULT NULL,
  `short_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('Active','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slots`
--

INSERT INTO `slots` (`slot_id`, `main_title`, `draw_date`, `draw_time`, `booking_close_time`, `short_title`, `title`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(445, 'Dear 1pm', '2026-08-06', '00:00:00', '12:57:00', 'Dear 1', '1,2,3,4', 'dear-1pm', 'Active', '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(446, 'Kerala 3pm', '2026-08-06', '00:00:00', '15:03:00', 'Dear 3', '1,2,3,4', 'kerala-3pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(447, 'Dear 6 pm', '2026-08-06', '00:00:00', '17:57:00', 'Dear 6', '1,2,3,4', 'dear-6-pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(448, 'Dear 8 pm', '2026-08-06', '00:00:00', '19:57:00', 'dear 8', '1,2,3,4', 'dear-8-pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(449, 'Dear 11pm', '2026-08-06', '10:43:00', '11:00:00', 'dear 11', '1,3', 'dear-11pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(450, 'DEAR 3.30 Pm', '2026-08-06', '15:14:00', '15:25:00', 'dear', '1,3', 'dear-330-pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(451, 'Dear 3.45 pm', '2026-08-06', '15:35:00', '15:48:00', 'dear', '1,3', 'dear-345-pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(452, 'dear 4.15', '2026-08-06', '16:25:00', '16:25:00', 'fdf', '1,3', 'dear-415-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(453, 'dear 4.45 pm', '2026-08-06', '16:50:00', '16:45:00', 'fdf', '1,3', 'dear-445-pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(454, 'DEAR 5 .53PM', '2026-08-06', '17:53:00', '17:53:00', 'ds', '1,3', 'dear-5-53pm-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(455, 'dear 10', '2026-08-06', '09:34:00', '11:15:00', 'dff', '1,3', 'dear-10-2026-08-06', 'Active', '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(456, 'Dear 1pm', '2026-08-07', '00:00:00', '12:57:00', 'Dear 1', '1,2,3,4', 'dear-1pm-2', 'Active', '2026-08-06 18:35:01', '2026-08-07 07:34:30', NULL),
(457, 'Kerala 3pm', '2026-08-07', '00:00:00', '15:03:00', 'Dear 3', '1,2,3,4', 'kerala-3pm', 'Active', '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(458, 'Dear 6 pm', '2026-08-07', '00:00:00', '17:57:00', 'Dear 6', '1,2,3,4', 'dear-6-pm', 'Active', '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(459, 'Dear 8 pm', '2026-08-07', '00:00:00', '19:57:00', 'dear 8', '1,2,3,4', 'dear-8-pm', 'Active', '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(460, 'Dear 1pm', '2026-08-08', '00:00:00', '12:57:00', 'Dear 1', '1,2,3,4', 'dear-1pm-3', 'Active', '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(461, 'Kerala 3pm', '2026-08-08', '00:00:00', '15:03:00', 'Dear 3', '1,2,3,4', 'kerala-3pm-2', 'Active', '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(462, 'Dear 6 pm', '2026-08-08', '00:00:00', '17:57:00', 'Dear 6', '1,2,3,4', 'dear-6-pm-2', 'Active', '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(463, 'Dear 8 pm', '2026-08-08', '00:00:00', '19:57:00', 'dear 8', '1,2,3,4', 'dear-8-pm-2', 'Active', '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(464, 'Dear 1pm', '2026-08-09', '00:00:00', '12:57:00', 'Dear 1', '1,2,3,4', 'dear-1pm-4', 'Active', '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(465, 'Kerala 3pm', '2026-08-09', '00:00:00', '15:03:00', 'Dear 3', '1,2,3,4', 'kerala-3pm-3', 'Active', '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(466, 'Dear 6 pm', '2026-08-09', '00:00:00', '17:57:00', 'Dear 6', '1,2,3,4', 'dear-6-pm-3', 'Active', '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(467, 'Dear 8 pm', '2026-08-09', '00:00:00', '19:57:00', 'dear 8', '1,2,3,4', 'dear-8-pm-3', 'Active', '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(468, 'Dear 1pm', '2026-08-10', '00:00:00', '12:57:00', 'Dear 1', '1,2,3,4', 'dear-1pm-2026-08-10', 'Active', '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(469, 'Kerala 3pm', '2026-08-10', '00:00:00', '15:03:00', 'Dear 3', '1,2,3,4', 'kerala-3pm-2026-08-10', 'Active', '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(470, 'Dear 6 pm', '2026-08-10', '00:00:00', '17:57:00', 'Dear 6', '1,2,3,4', 'dear-6-pm-2026-08-10', 'Active', '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(471, 'Dear 8 pm', '2026-08-10', '00:00:00', '19:57:00', 'dear 8', '1,2,3,4', 'dear-8-pm-2026-08-10', 'Active', '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `slot_items`
--

CREATE TABLE `slot_items` (
  `slot_items_id` bigint UNSIGNED NOT NULL,
  `title` int DEFAULT NULL,
  `slot_id` bigint UNSIGNED NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `digit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `win_amount` decimal(10,2) DEFAULT NULL,
  `first_price` decimal(10,2) DEFAULT NULL,
  `second_price` decimal(10,2) DEFAULT NULL,
  `third_price` decimal(10,2) DEFAULT NULL,
  `ticket_amt` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slot_items`
--

INSERT INTO `slot_items` (`slot_items_id`, `title`, `slot_id`, `group_name`, `digit`, `color`, `win_amount`, `first_price`, `second_price`, `third_price`, `ticket_amt`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3660, 1, 445, 'A', '0', '#903c3c', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3661, 1, 445, 'B', '4', '#5573af', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3662, 1, 445, 'C', '5', '#4bc242', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3663, 2, 445, 'AB', '04', '#66489d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3664, 2, 445, 'AC', '05', '#328f3d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3665, 2, 445, 'BC', '45', '#c07a2a', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3666, 3, 445, 'ABC', '045', '#664f82', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3667, 3, 445, 'ABC', '045', '#6bd73c', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3668, 3, 445, 'ABC', '045', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3669, 3, 445, 'ABC', '045', '#3ea2bb', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3670, 3, 445, 'ABC', '045', '#828e25', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3671, 4, 445, 'DABC', '0045', '#e13d14', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-05 18:35:02', '2026-08-06 07:37:42', NULL),
(3672, 1, 446, 'A', '0', '#f1ed84', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3673, 1, 446, 'B', '4', '#408c6f', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3674, 1, 446, 'C', '5', '#9c4f80', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3675, 2, 446, 'AB', '04', '#e100ff', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3676, 2, 446, 'AC', '05', '#dda783', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3677, 2, 446, 'BC', '45', '#9cf896', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3678, 3, 446, 'ABC', '045', '#ff00f7', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3679, 3, 446, 'ABC', '045', '#004cff', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3680, 3, 446, 'ABC', '045', '#00fffb', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3681, 3, 446, 'ABC', '045', '#47cc3e', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3682, 3, 446, 'ABC', '045', '#731111', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3683, 4, 446, 'DABC', '0045', '#484785', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3684, 1, 447, 'A', '0', '#607ec3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3685, 1, 447, 'B', '4', '#bad963', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3686, 1, 447, 'C', '5', '#bb6130', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3687, 2, 447, 'AB', '04', '#299eae', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3688, 2, 447, 'AC', '05', '#5b4596', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3689, 2, 447, 'BC', '45', '#94478e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3690, 3, 447, 'ABC', '045', '#4a8258', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3691, 3, 447, 'ABC', '045', '#61c2bb', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3692, 3, 447, 'ABC', '045', '#a3ad48', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3693, 3, 447, 'ABC', '045', '#98528c', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3694, 3, 447, 'ABC', '045', '#3c44b4', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3695, 4, 447, 'DABC', '0045', '#9a88b4', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3696, 1, 448, 'A', '0', '#701919', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3697, 1, 448, 'B', '4', '#ecf000', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3698, 1, 448, 'C', '5', '#272cd3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3699, 2, 448, 'AB', '04', '#bc298e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3700, 2, 448, 'AC', '05', '#45bf80', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3701, 2, 448, 'BC', '45', '#c2db00', 500.00, NULL, NULL, NULL, 7.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3702, 3, 448, 'ABC', '045', '#7c2727', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3703, 3, 448, 'ABC', '045', '#1f6159', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3704, 3, 448, 'ABC', '045', '#4c00ff', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3705, 3, 448, 'ABC', '045', '#43741b', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3706, 3, 448, 'ABC', '045', '#473ec1', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3707, 4, 448, 'DABC', '0045', '#c8b746', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-05 18:35:02', '2026-08-05 18:35:02', NULL),
(3708, 1, 449, 'A', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3709, 1, 449, 'V', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3710, 1, 449, 'C', '3', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3711, 3, 449, 'ABC', '010', '#000000', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3712, 3, 449, 'ABC', '010', '#000000', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3713, 3, 449, 'ABC', '010', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 04:26:54', '2026-08-06 04:26:54'),
(3714, 1, 450, 'A', '1', '#000000', 15.00, NULL, NULL, NULL, 100.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3715, 1, 450, 'C', '2', '#000000', 15.00, NULL, NULL, NULL, 100.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3716, 1, 450, 'V', '3', '#000000', 15.00, NULL, NULL, NULL, 100.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3717, 3, 450, 'ABC', '123', '#000000', NULL, 1000.00, 50.00, 20.00, 12.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3718, 3, 450, 'ABC', '123', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3719, 3, 450, 'ABC', '123', '#000000', NULL, 1000.00, 50.00, 20.00, 17.00, '2026-08-05 18:35:02', '2026-08-06 04:27:02', '2026-08-06 04:27:02'),
(3720, 1, 451, 'A', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3721, 1, 451, 'S', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3722, 1, 451, 'D', '3', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3723, 3, 451, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3724, 3, 451, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3725, 3, 451, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 04:27:11', '2026-08-06 04:27:11'),
(3726, 1, 452, 'A', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3727, 1, 452, 'S', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3728, 1, 452, 'D', '3', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3729, 3, 452, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3730, 3, 452, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3731, 3, 452, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 04:27:20', '2026-08-06 04:27:20'),
(3732, 1, 453, 'A', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3733, 1, 453, 'S', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3734, 1, 453, 'X', '3', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3735, 3, 453, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3736, 3, 453, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3737, 3, 453, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 04:27:27', '2026-08-06 04:27:27'),
(3738, 1, 454, 'A', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3739, 1, 454, 'E', '5', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3740, 1, 454, 'R', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3741, 3, 454, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3742, 3, 454, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3743, 3, 454, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 30.00, '2026-08-05 18:35:02', '2026-08-06 04:27:34', '2026-08-06 04:27:34'),
(3744, 1, 455, 'A', '0', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3745, 1, 455, 'R', '1', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3746, 1, 455, 'S', '2', '#000000', 100.00, NULL, NULL, NULL, 10.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3747, 3, 455, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 20.00, 15.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3748, 3, 455, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 0.00, 25.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3749, 3, 455, 'ABC', '010', '#000000', NULL, 1000.00, 50.00, 200.00, 35.00, '2026-08-05 18:35:02', '2026-08-06 04:27:42', '2026-08-06 04:27:42'),
(3750, 1, 456, 'A', '4', '#903c3c', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3751, 1, 456, 'B', '0', '#5573af', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3752, 1, 456, 'C', '0', '#4bc242', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3753, 2, 456, 'AB', '40', '#66489d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3754, 2, 456, 'AC', '40', '#328f3d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3755, 2, 456, 'BC', '00', '#c07a2a', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3756, 3, 456, 'ABC', '400', '#664f82', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3757, 3, 456, 'ABC', '400', '#6bd73c', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3758, 3, 456, 'ABC', '400', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3759, 3, 456, 'ABC', '400', '#3ea2bb', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3760, 3, 456, 'ABC', '400', '#828e25', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3761, 4, 456, 'DABC', '3400', '#e13d14', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-06 18:35:01', '2026-08-07 07:34:31', NULL),
(3762, 1, 457, 'A', '8', '#f1ed84', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3763, 1, 457, 'B', '8', '#408c6f', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3764, 1, 457, 'C', '7', '#9c4f80', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3765, 2, 457, 'AB', '88', '#e100ff', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3766, 2, 457, 'AC', '87', '#dda783', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3767, 2, 457, 'BC', '87', '#9cf896', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3768, 3, 457, 'ABC', '887', '#ff00f7', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3769, 3, 457, 'ABC', '887', '#004cff', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3770, 3, 457, 'ABC', '887', '#00fffb', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3771, 3, 457, 'ABC', '887', '#47cc3e', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3772, 3, 457, 'ABC', '887', '#731111', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3773, 4, 457, 'DABC', '7887', '#484785', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-06 18:35:01', '2026-08-07 09:41:49', NULL),
(3774, 1, 458, 'A', '7', '#607ec3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3775, 1, 458, 'B', '0', '#bad963', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3776, 1, 458, 'C', '1', '#bb6130', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3777, 2, 458, 'AB', '70', '#299eae', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3778, 2, 458, 'AC', '71', '#5b4596', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3779, 2, 458, 'BC', '01', '#94478e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3780, 3, 458, 'ABC', '701', '#4a8258', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3781, 3, 458, 'ABC', '701', '#61c2bb', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3782, 3, 458, 'ABC', '701', '#a3ad48', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3783, 3, 458, 'ABC', '701', '#98528c', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3784, 3, 458, 'ABC', '701', '#3c44b4', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3785, 4, 458, 'DABC', '5701', '#9a88b4', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-06 18:35:01', '2026-08-07 12:35:02', NULL),
(3786, 1, 459, 'A', '4', '#701919', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3787, 1, 459, 'B', '9', '#ecf000', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3788, 1, 459, 'C', '9', '#272cd3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3789, 2, 459, 'AB', '49', '#bc298e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3790, 2, 459, 'AC', '49', '#45bf80', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3791, 2, 459, 'BC', '99', '#c2db00', 500.00, NULL, NULL, NULL, 7.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3792, 3, 459, 'ABC', '499', '#7c2727', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3793, 3, 459, 'ABC', '499', '#1f6159', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3794, 3, 459, 'ABC', '499', '#4c00ff', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3795, 3, 459, 'ABC', '499', '#43741b', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3796, 3, 459, 'ABC', '499', '#473ec1', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3797, 4, 459, 'DABC', '1499', '#c8b746', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-06 18:35:01', '2026-08-07 15:04:47', NULL),
(3798, 1, 460, 'A', '0', '#903c3c', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3799, 1, 460, 'B', '5', '#5573af', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3800, 1, 460, 'C', '1', '#4bc242', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3801, 2, 460, 'AB', '05', '#66489d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3802, 2, 460, 'AC', '01', '#328f3d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3803, 2, 460, 'BC', '51', '#c07a2a', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3804, 3, 460, 'ABC', '051', '#664f82', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3805, 3, 460, 'ABC', '051', '#6bd73c', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3806, 3, 460, 'ABC', '051', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3807, 3, 460, 'ABC', '051', '#3ea2bb', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3808, 3, 460, 'ABC', '051', '#828e25', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3809, 4, 460, 'DABC', '7051', '#e13d14', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-07 18:35:02', '2026-08-08 07:33:50', NULL),
(3810, 1, 461, 'A', '1', '#f1ed84', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3811, 1, 461, 'B', '9', '#408c6f', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3812, 1, 461, 'C', '9', '#9c4f80', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3813, 2, 461, 'AB', '19', '#e100ff', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3814, 2, 461, 'AC', '19', '#dda783', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3815, 2, 461, 'BC', '99', '#9cf896', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3816, 3, 461, 'ABC', '199', '#ff00f7', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3817, 3, 461, 'ABC', '199', '#004cff', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3818, 3, 461, 'ABC', '199', '#00fffb', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3819, 3, 461, 'ABC', '199', '#47cc3e', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3820, 3, 461, 'ABC', '199', '#731111', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3821, 4, 461, 'DABC', '7199', '#484785', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-07 18:35:02', '2026-08-08 09:42:30', NULL),
(3822, 1, 462, 'A', '1', '#607ec3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3823, 1, 462, 'B', '0', '#bad963', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-07 18:35:02', NULL),
(3824, 1, 462, 'C', '5', '#bb6130', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3825, 2, 462, 'AB', '10', '#299eae', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3826, 2, 462, 'AC', '15', '#5b4596', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3827, 2, 462, 'BC', '05', '#94478e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3828, 3, 462, 'ABC', '105', '#4a8258', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3829, 3, 462, 'ABC', '105', '#61c2bb', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3830, 3, 462, 'ABC', '105', '#a3ad48', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3831, 3, 462, 'ABC', '105', '#98528c', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3832, 3, 462, 'ABC', '105', '#3c44b4', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3833, 4, 462, 'DABC', '5105', '#9a88b4', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-07 18:35:02', '2026-08-08 12:35:14', NULL),
(3834, 1, 463, 'A', '7', '#701919', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3835, 1, 463, 'B', '7', '#ecf000', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3836, 1, 463, 'C', '3', '#272cd3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3837, 2, 463, 'AB', '77', '#bc298e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3838, 2, 463, 'AC', '73', '#45bf80', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3839, 2, 463, 'BC', '73', '#c2db00', 500.00, NULL, NULL, NULL, 7.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3840, 3, 463, 'ABC', '773', '#7c2727', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3841, 3, 463, 'ABC', '773', '#1f6159', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3842, 3, 463, 'ABC', '773', '#4c00ff', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3843, 3, 463, 'ABC', '773', '#43741b', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3844, 3, 463, 'ABC', '773', '#473ec1', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3845, 4, 463, 'DABC', '2773', '#c8b746', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-07 18:35:02', '2026-08-08 14:45:00', NULL),
(3846, 1, 464, 'A', '4', '#903c3c', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3847, 1, 464, 'B', '1', '#5573af', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3848, 1, 464, 'C', '0', '#4bc242', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3849, 2, 464, 'AB', '41', '#66489d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3850, 2, 464, 'AC', '40', '#328f3d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3851, 2, 464, 'BC', '10', '#c07a2a', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3852, 3, 464, 'ABC', '410', '#664f82', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3853, 3, 464, 'ABC', '410', '#6bd73c', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3854, 3, 464, 'ABC', '410', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3855, 3, 464, 'ABC', '410', '#3ea2bb', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3856, 3, 464, 'ABC', '410', '#828e25', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3857, 4, 464, 'DABC', '1410', '#e13d14', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-08 18:35:01', '2026-08-09 07:34:21', NULL),
(3858, 1, 465, 'A', '9', '#f1ed84', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3859, 1, 465, 'B', '3', '#408c6f', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3860, 1, 465, 'C', '5', '#9c4f80', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3861, 2, 465, 'AB', '93', '#e100ff', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3862, 2, 465, 'AC', '95', '#dda783', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3863, 2, 465, 'BC', '35', '#9cf896', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3864, 3, 465, 'ABC', '935', '#ff00f7', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3865, 3, 465, 'ABC', '935', '#004cff', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3866, 3, 465, 'ABC', '935', '#00fffb', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3867, 3, 465, 'ABC', '935', '#47cc3e', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3868, 3, 465, 'ABC', '935', '#731111', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3869, 4, 465, 'DABC', '6935', '#484785', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-08 18:35:01', '2026-08-09 09:42:10', NULL),
(3870, 1, 466, 'A', '5', '#607ec3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3871, 1, 466, 'B', '4', '#bad963', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3872, 1, 466, 'C', '0', '#bb6130', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3873, 2, 466, 'AB', '54', '#299eae', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3874, 2, 466, 'AC', '50', '#5b4596', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3875, 2, 466, 'BC', '40', '#94478e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3876, 3, 466, 'ABC', '540', '#4a8258', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3877, 3, 466, 'ABC', '540', '#61c2bb', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3878, 3, 466, 'ABC', '540', '#a3ad48', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3879, 3, 466, 'ABC', '540', '#98528c', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3880, 3, 466, 'ABC', '540', '#3c44b4', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3881, 4, 466, 'DABC', '6540', '#9a88b4', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-08 18:35:01', '2026-08-09 12:33:57', NULL),
(3882, 1, 467, 'A', '5', '#701919', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3883, 1, 467, 'B', '5', '#ecf000', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3884, 1, 467, 'C', '1', '#272cd3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3885, 2, 467, 'AB', '55', '#bc298e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3886, 2, 467, 'AC', '51', '#45bf80', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3887, 2, 467, 'BC', '51', '#c2db00', 500.00, NULL, NULL, NULL, 7.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3888, 3, 467, 'ABC', '551', '#7c2727', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3889, 3, 467, 'ABC', '551', '#1f6159', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3890, 3, 467, 'ABC', '551', '#4c00ff', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3891, 3, 467, 'ABC', '551', '#43741b', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3892, 3, 467, 'ABC', '551', '#473ec1', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3893, 4, 467, 'DABC', '7551', '#c8b746', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-08 18:35:01', '2026-08-09 14:43:35', NULL),
(3894, 1, 468, 'A', '4', '#903c3c', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3895, 1, 468, 'B', '1', '#5573af', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3896, 1, 468, 'C', '0', '#4bc242', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3897, 2, 468, 'AB', '41', '#66489d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3898, 2, 468, 'AC', '40', '#328f3d', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3899, 2, 468, 'BC', '10', '#c07a2a', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3900, 3, 468, 'ABC', '410', '#664f82', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3901, 3, 468, 'ABC', '410', '#6bd73c', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3902, 3, 468, 'ABC', '410', '#000000', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3903, 3, 468, 'ABC', '410', '#3ea2bb', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3904, 3, 468, 'ABC', '410', '#828e25', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3905, 4, 468, 'DABC', '1410', '#e13d14', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3906, 1, 469, 'A', '9', '#f1ed84', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3907, 1, 469, 'B', '3', '#408c6f', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3908, 1, 469, 'C', '5', '#9c4f80', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3909, 2, 469, 'AB', '93', '#e100ff', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3910, 2, 469, 'AC', '95', '#dda783', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3911, 2, 469, 'BC', '35', '#9cf896', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3912, 3, 469, 'ABC', '935', '#ff00f7', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3913, 3, 469, 'ABC', '935', '#004cff', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3914, 3, 469, 'ABC', '935', '#00fffb', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3915, 3, 469, 'ABC', '935', '#47cc3e', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3916, 3, 469, 'ABC', '935', '#731111', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3917, 4, 469, 'DABC', '6935', '#484785', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3918, 1, 470, 'A', '5', '#607ec3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3919, 1, 470, 'B', '4', '#bad963', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3920, 1, 470, 'C', '0', '#bb6130', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3921, 2, 470, 'AB', '54', '#299eae', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3922, 2, 470, 'AC', '50', '#5b4596', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3923, 2, 470, 'BC', '40', '#94478e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3924, 3, 470, 'ABC', '540', '#4a8258', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3925, 3, 470, 'ABC', '540', '#61c2bb', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3926, 3, 470, 'ABC', '540', '#a3ad48', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3927, 3, 470, 'ABC', '540', '#98528c', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3928, 3, 470, 'ABC', '540', '#3c44b4', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3929, 4, 470, 'DABC', '6540', '#9a88b4', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3930, 1, 471, 'A', '5', '#701919', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3931, 1, 471, 'B', '5', '#ecf000', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3932, 1, 471, 'C', '1', '#272cd3', 50.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3933, 2, 471, 'AB', '55', '#bc298e', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3934, 2, 471, 'AC', '51', '#45bf80', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3935, 2, 471, 'BC', '51', '#c2db00', 500.00, NULL, NULL, NULL, 7.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3936, 3, 471, 'ABC', '551', '#7c2727', NULL, 6000.00, 200.00, 25.00, 15.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3937, 3, 471, 'ABC', '551', '#1f6159', NULL, 10000.00, 1000.00, 0.00, 25.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3938, 3, 471, 'ABC', '551', '#4c00ff', NULL, 15000.00, 500.00, 50.00, 30.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3939, 3, 471, 'ABC', '551', '#43741b', NULL, 17000.00, 500.00, 50.00, 35.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3940, 3, 471, 'ABC', '551', '#473ec1', NULL, 30000.00, 1000.00, 100.00, 60.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL),
(3941, 4, 471, 'DABC', '7551', '#c8b746', 85000.00, NULL, NULL, NULL, 20.00, '2026-08-09 18:35:02', '2026-08-09 18:35:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'super_admin@lukybaby.in', NULL, '$2y$12$4jqW7jCvIBoC2rqOVW0W7uClhAfkkUWmyX6ZtAXbvH7hxEwVtJLhi', NULL, '2025-01-22 13:03:41', '2026-07-15 05:12:26'),
(2, 'manager', 'manager@gmail.com', NULL, '$2y$12$NjnfoiB2ABFSY8WOkOM5.OrcCMWF4Hho28sLODSpuF.Z7SwxrI4Wq', NULL, '2026-05-12 05:17:46', '2026-05-12 05:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_recharges`
--

CREATE TABLE `wallet_recharges` (
  `wallet_recharge_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank_acc_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_recharges`
--

INSERT INTO `wallet_recharges` (`wallet_recharge_id`, `customer_id`, `balance`, `bank_acc_id`, `created_at`, `updated_at`) VALUES
(1, 1, 72928.00, 3, '2026-05-20 09:30:32', '2026-08-07 12:27:13'),
(2, 3, 3970.00, 2, '2026-05-20 09:40:30', '2026-06-17 11:36:40'),
(14, 24, 8113.00, NULL, '2026-08-06 06:47:26', '2026-08-09 17:33:41'),
(15, 25, 220.00, NULL, '2026-08-06 06:55:08', '2026-08-06 06:56:44'),
(16, 32, 85.00, 7, '2026-08-06 17:26:16', '2026-08-07 07:20:49'),
(17, 27, 3.00, 8, '2026-08-07 07:11:26', '2026-08-08 09:26:40'),
(18, 39, 37.00, NULL, '2026-08-07 15:25:41', '2026-08-09 09:40:58');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_recharge_requests`
--

CREATE TABLE `wallet_recharge_requests` (
  `wallet_recharge_request_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_proof` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_recharge_requests`
--

INSERT INTO `wallet_recharge_requests` (`wallet_recharge_request_id`, `customer_id`, `amount`, `payment_method`, `payment_proof`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 24, 500.00, 'upi', 'payment_proof_1785998721_screenshot_1785998709961.jpg', 'approved', '500', '2026-08-06 06:45:21', '2026-08-06 06:47:40'),
(2, 24, 500.00, 'upi', 'payment_proof_1785998805_screenshot_1785998805550.jpg', 'approved', '500', '2026-08-06 06:46:45', '2026-08-06 06:47:26'),
(3, 25, 500.00, 'upi', 'payment_proof_1785999296_screenshot_1785999291538.jpg', 'approved', '500', '2026-08-06 06:54:56', '2026-08-06 06:55:08'),
(4, 32, 500.00, 'upi', 'payment_proof_1786078743_screenshot_1786078740434.jpg', 'rejected', '500', '2026-08-07 04:59:03', '2026-08-07 05:23:54'),
(5, 32, 500.00, 'upi', 'payment_proof_1786078971_screenshot_1786078970654.jpg', 'approved', '500', '2026-08-07 05:02:51', '2026-08-07 05:05:49'),
(6, 27, 175.00, 'upi', 'payment_proof_1786086624_screenshot_1786086623916.jpg', 'approved', '175', '2026-08-07 07:10:24', '2026-08-07 07:11:26'),
(7, 27, 100.00, 'upi', 'payment_proof_1786087074_screenshot_1786087071235.jpg', 'approved', '100', '2026-08-07 07:17:54', '2026-08-07 07:18:54'),
(8, 27, 200.00, 'upi', 'payment_proof_1786087546_screenshot_1786087544622.jpg', 'rejected', '200', '2026-08-07 07:25:46', '2026-08-07 09:11:43'),
(9, 27, 200.00, 'upi', 'payment_proof_1786090571_screenshot_1786090570812.jpg', 'approved', '200', '2026-08-07 08:16:11', '2026-08-07 08:17:02'),
(10, 27, 100.00, 'upi', 'payment_proof_1786093794_screenshot_1786093793290.jpg', 'approved', '100', '2026-08-07 09:09:54', '2026-08-07 09:11:18'),
(11, 27, 100.00, 'upi', 'payment_proof_1786095136_screenshot_1786095135514.jpg', 'rejected', '100', '2026-08-07 09:32:16', '2026-08-07 12:17:46'),
(12, 27, 425.00, 'upi', 'payment_proof_1786105012_screenshot_1786105011442.jpg', 'approved', '425', '2026-08-07 12:16:52', '2026-08-07 12:17:59'),
(13, 39, 100.00, 'upi', 'payment_proof_1786115110_screenshot_1786115106701.jpg', 'rejected', '100', '2026-08-07 15:05:10', '2026-08-08 01:23:50'),
(14, 39, 100.00, 'upi', 'payment_proof_1786115325_screenshot_1786115321938.jpg', 'approved', '100', '2026-08-07 15:08:45', '2026-08-07 15:25:40'),
(15, 27, 100.00, 'upi', 'payment_proof_1786181108_screenshot_1786181107669.jpg', 'approved', '100', '2026-08-08 09:25:08', '2026-08-08 09:25:22'),
(16, 39, 100.00, 'upi', 'payment_proof_1786259602_screenshot_1786259598590.jpg', 'approved', '100', '2026-08-09 07:13:22', '2026-08-09 07:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `wallet_transaction_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `type` enum('credit','debit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`wallet_transaction_id`, `customer_id`, `type`, `amount`, `payment_method`, `reference_no`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'credit', 1500.00, 'upi', NULL, 'Wallet Recharge', '2026-05-20 09:30:32', '2026-05-20 09:30:32'),
(2, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-05-20 09:40:30', '2026-05-20 09:40:30'),
(3, 3, 'credit', 500.00, 'upi', NULL, 'Wallet Recharge', '2026-05-20 09:40:48', '2026-05-20 09:40:48'),
(4, 3, 'credit', 100.00, 'bank_transfer', NULL, 'Wallet Recharge', '2026-05-20 09:48:39', '2026-05-20 09:48:39'),
(7, 1, 'debit', 36.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-21 11:57:30', '2026-05-21 11:57:30'),
(8, 3, 'debit', 60.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 04:43:00', '2026-05-22 04:43:00'),
(9, 1, 'debit', 30.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 05:15:32', '2026-05-22 05:15:32'),
(10, 3, 'debit', 140.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 06:00:52', '2026-05-22 06:00:52'),
(11, 3, 'debit', 60.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 06:31:53', '2026-05-22 06:31:53'),
(12, 3, 'debit', 110.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 07:34:32', '2026-05-22 07:34:32'),
(13, 3, 'debit', 40.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 08:07:28', '2026-05-22 08:07:28'),
(14, 3, 'debit', 20.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-22 08:22:54', '2026-05-22 08:22:54'),
(22, 1, 'debit', 70.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-25 09:44:46', '2026-05-25 09:44:46'),
(26, 1, 'debit', 75.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-05-25 10:38:07', '2026-05-25 10:38:07'),
(27, 1, 'debit', 0.00, 'slot_loss', 'LOSE-11', 'No win for this booking', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(28, 1, 'debit', 0.00, 'slot_loss', 'LOSE-12', 'No win for this booking', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(29, 1, 'debit', 0.00, 'slot_loss', 'LOSE-13', 'No win for this booking', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(30, 1, 'credit', 300.00, 'slot win', 'WIN-17', 'Slot winning amount credited', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(31, 1, 'debit', 0.00, 'slot_loss', 'LOSE-18', 'No win for this booking', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(32, 1, 'debit', 0.00, 'slot_loss', 'LOSE-19', 'No win for this booking', '2026-05-25 10:48:16', '2026-05-25 10:48:16'),
(86, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-13 07:01:14', '2026-06-13 07:01:14'),
(87, 3, 'credit', 100.00, 'bank_transfer', NULL, 'Wallet Recharge', '2026-06-13 07:01:29', '2026-06-13 07:01:29'),
(88, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-13 07:33:55', '2026-06-13 07:33:55'),
(89, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-13 07:51:21', '2026-06-13 07:51:21'),
(90, 3, 'credit', 100.00, 'bank_transfer', NULL, 'Wallet Recharge', '2026-06-13 07:53:08', '2026-06-13 07:53:08'),
(91, 3, 'credit', 100.00, 'bank_transfer', NULL, 'Wallet Recharge', '2026-06-13 07:53:11', '2026-06-13 07:53:11'),
(92, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-13 07:53:21', '2026-06-13 07:53:21'),
(93, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-16 09:40:38', '2026-06-16 09:40:38'),
(94, 3, 'credit', 100.00, 'upi', NULL, 'Wallet Recharge', '2026-06-17 10:05:37', '2026-06-17 10:05:37'),
(95, 3, 'credit', 100.00, 'upi', 'RC-1', 'Wallet Recharge', '2026-06-17 11:02:19', '2026-06-17 11:02:19'),
(96, 3, 'credit', 100.00, 'upi', 'RC-1', 'Wallet Recharge', '2026-06-17 11:31:11', '2026-06-17 11:31:11'),
(97, 3, 'credit', 1000.00, 'upi', 'RC-3', 'Wallet Recharge', '2026-06-17 11:36:40', '2026-06-17 11:36:40'),
(153, 1, 'debit', 120.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-07-01 10:32:30', '2026-07-01 10:32:30'),
(154, 1, 'debit', 228.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-07-01 10:53:39', '2026-07-01 10:53:39'),
(155, 1, 'credit', 180.00, 'slot win', 'WIN-116', 'Slot winning amount credited', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(156, 1, 'debit', 20.00, 'commission', 'COM-116', 'Commission deducted from winnings', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(157, 1, 'credit', 180.00, 'slot win', 'WIN-117', 'Slot winning amount credited', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(158, 1, 'debit', 20.00, 'commission', 'COM-117', 'Commission deducted from winnings', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(159, 1, 'debit', 0.00, 'slot_loss', 'LOSE-115', 'No win for this booking', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(160, 1, 'debit', 0.00, 'slot_loss', 'LOSE-118', 'No win for this booking', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(161, 1, 'debit', 0.00, 'slot_loss', 'LOSE-114', 'No win for this booking', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(162, 1, 'debit', 0.00, 'slot_loss', 'LOSE-119', 'No win for this booking', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(163, 1, 'credit', 900.00, 'slot win', 'WIN-120', 'Slot winning amount credited', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(164, 1, 'debit', 100.00, 'commission', 'COM-120', 'Commission deducted from winnings', '2026-07-01 11:02:43', '2026-07-01 11:02:43'),
(179, 1, 'debit', 220.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-07-02 06:01:37', '2026-07-02 06:01:37'),
(180, 1, 'credit', 180.00, 'slot win', 'WIN-111', 'Slot winning amount credited', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(181, 1, 'debit', 20.00, 'commission', 'COM-111', 'Commission deducted from winnings', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(182, 1, 'debit', 0.00, 'slot_loss', 'LOSE-112', 'No win for this booking', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(183, 1, 'debit', 0.00, 'slot_loss', 'LOSE-113', 'No win for this booking', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(184, 1, 'credit', 180.00, 'slot win', 'WIN-134', 'Slot winning amount credited', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(185, 1, 'debit', 20.00, 'commission', 'COM-134', 'Commission deducted from winnings', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(186, 1, 'credit', 180.00, 'slot win', 'WIN-133', 'Slot winning amount credited', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(187, 1, 'debit', 20.00, 'commission', 'COM-133', 'Commission deducted from winnings', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(188, 1, 'debit', 0.00, 'slot_loss', 'LOSE-132', 'No win for this booking', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(189, 1, 'debit', 0.00, 'slot_loss', 'LOSE-135', 'No win for this booking', '2026-07-02 06:13:26', '2026-07-02 06:13:26'),
(332, 1, 'debit', 167.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-07-20 06:42:31', '2026-07-20 06:42:31'),
(333, 1, 'debit', 0.00, 'slot_loss', 'LOSE-232', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(334, 1, 'debit', 0.00, 'slot_loss', 'LOSE-231', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(335, 1, 'debit', 0.00, 'slot_loss', 'LOSE-230', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(336, 1, 'debit', 0.00, 'slot_loss', 'LOSE-233', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(337, 1, 'debit', 0.00, 'slot_loss', 'LOSE-234', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(338, 1, 'debit', 0.00, 'slot_loss', 'LOSE-235', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(339, 1, 'debit', 0.00, 'slot_loss', 'LOSE-236', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(340, 1, 'debit', 0.00, 'slot_loss', 'LOSE-237', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(341, 1, 'debit', 0.00, 'slot_loss', 'LOSE-238', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(342, 1, 'debit', 0.00, 'slot_loss', 'LOSE-239', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(343, 1, 'debit', 0.00, 'slot_loss', 'LOSE-240', 'No win for this booking', '2026-07-20 07:31:16', '2026-07-20 07:31:16'),
(818, 1, 'debit', 140.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-03 08:22:57', '2026-08-03 08:22:57'),
(819, 1, 'debit', 0.00, 'slot_loss', 'LOSE-700', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(820, 1, 'debit', 0.00, 'slot_loss', 'LOSE-699', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(821, 1, 'debit', 0.00, 'slot_loss', 'LOSE-698', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(822, 1, 'debit', 0.00, 'slot_loss', 'LOSE-701', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(823, 1, 'debit', 0.00, 'slot_loss', 'LOSE-702', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(824, 1, 'credit', 6000.00, 'slot win', 'WIN-703', 'Slot winning amount credited', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(825, 1, 'debit', 0.00, 'slot_loss', 'LOSE-704', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(826, 1, 'debit', 0.00, 'slot_loss', 'LOSE-705', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(827, 1, 'debit', 0.00, 'slot_loss', 'LOSE-706', 'No win for this booking', '2026-08-03 08:35:36', '2026-08-03 08:35:36'),
(828, 1, 'debit', 44.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-03 09:33:21', '2026-08-03 09:33:21'),
(829, 1, 'credit', 50.00, 'slot win', 'WIN-707', 'Slot winning amount credited', '2026-08-03 10:00:23', '2026-08-03 10:00:23'),
(830, 1, 'credit', 50.00, 'slot win', 'WIN-708', 'Slot winning amount credited', '2026-08-03 10:00:23', '2026-08-03 10:00:23'),
(831, 1, 'credit', 20.00, 'slot win', 'WIN-709', 'Slot winning amount credited', '2026-08-03 10:00:23', '2026-08-03 10:00:23'),
(838, 1, 'debit', 100.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-03 10:42:53', '2026-08-03 10:42:53'),
(839, 1, 'credit', 1000.00, 'slot win', 'WIN-715', 'Slot winning amount credited', '2026-08-03 10:55:25', '2026-08-03 10:55:25'),
(840, 1, 'credit', 50.00, 'slot win', 'WIN-716', 'Slot winning amount credited', '2026-08-03 10:55:25', '2026-08-03 10:55:25'),
(841, 1, 'debit', 0.00, 'slot_loss', 'LOSE-717', 'No win for this booking', '2026-08-03 10:55:25', '2026-08-03 10:55:25'),
(842, 1, 'credit', 20.00, 'slot win', 'WIN-718', 'Slot winning amount credited', '2026-08-03 10:55:25', '2026-08-03 10:55:25'),
(843, 1, 'credit', 20.00, 'slot win', 'WIN-719', 'Slot winning amount credited', '2026-08-03 10:55:25', '2026-08-03 10:55:25'),
(850, 1, 'debit', 100.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-03 11:08:51', '2026-08-03 11:08:51'),
(851, 1, 'credit', 1000.00, 'slot win', 'WIN-720', 'Slot winning amount credited', '2026-08-03 11:15:24', '2026-08-03 11:15:24'),
(852, 1, 'credit', 50.00, 'slot win', 'WIN-721', 'Slot winning amount credited', '2026-08-03 11:15:24', '2026-08-03 11:15:24'),
(853, 1, 'debit', 0.00, 'slot_loss', 'LOSE-722', 'No win for this booking', '2026-08-03 11:15:24', '2026-08-03 11:15:24'),
(854, 1, 'debit', 0.00, 'slot_loss', 'LOSE-723', 'No win for this booking', '2026-08-03 11:15:24', '2026-08-03 11:15:24'),
(855, 1, 'credit', 20.00, 'slot win', 'WIN-724', 'Slot winning amount credited', '2026-08-03 11:15:24', '2026-08-03 11:15:24'),
(856, 1, 'debit', 100.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-03 11:31:19', '2026-08-03 11:31:19'),
(857, 1, 'credit', 1000.00, 'slot win', 'WIN-725', 'Slot winning amount credited', '2026-08-03 11:40:21', '2026-08-03 11:40:21'),
(858, 1, 'credit', 50.00, 'slot win', 'WIN-726', 'Slot winning amount credited', '2026-08-03 11:40:21', '2026-08-03 11:40:21'),
(859, 1, 'debit', 0.00, 'slot_loss', 'LOSE-727', 'No win for this booking', '2026-08-03 11:40:21', '2026-08-03 11:40:21'),
(860, 1, 'debit', 0.00, 'slot_loss', 'LOSE-728', 'No win for this booking', '2026-08-03 11:40:21', '2026-08-03 11:40:21'),
(861, 1, 'credit', 20.00, 'slot win', 'WIN-729', 'Slot winning amount credited', '2026-08-03 11:40:21', '2026-08-03 11:40:21'),
(862, 1, 'credit', 20.00, 'slot win', 'WIN-718', 'Slot winning amount credited (corrected result)', '2026-08-03 12:20:36', '2026-08-03 12:20:36'),
(863, 1, 'credit', 20.00, 'slot win', 'WIN-723', 'Slot winning amount credited (corrected result)', '2026-08-03 12:20:36', '2026-08-03 12:20:36'),
(864, 1, 'credit', 1000.00, 'slot win', 'WIN-725', 'Slot winning amount credited', '2026-08-03 12:23:06', '2026-08-03 12:23:06'),
(865, 1, 'credit', 50.00, 'slot win', 'WIN-726', 'Slot winning amount credited', '2026-08-03 12:23:06', '2026-08-03 12:23:06'),
(866, 1, 'debit', 0.00, 'slot_loss', 'LOSE-727', 'No win for this booking', '2026-08-03 12:23:06', '2026-08-03 12:23:06'),
(867, 1, 'credit', 20.00, 'slot win', 'WIN-728', 'Slot winning amount credited', '2026-08-03 12:23:06', '2026-08-03 12:23:06'),
(868, 1, 'credit', 20.00, 'slot win', 'WIN-729', 'Slot winning amount credited', '2026-08-03 12:23:06', '2026-08-03 12:23:06'),
(890, 1, 'credit', 1000.00, 'slot win', 'WIN-730', 'Slot winning amount credited', '2026-08-04 05:45:08', '2026-08-04 05:45:08'),
(891, 1, 'credit', 50.00, 'slot win', 'WIN-731', 'Slot winning amount credited', '2026-08-04 05:45:08', '2026-08-04 05:45:08'),
(892, 1, 'credit', 200.00, 'slot win', 'WIN-734', 'Slot winning amount credited', '2026-08-04 05:45:08', '2026-08-04 05:45:08'),
(928, 24, 'credit', 500.00, 'upi', 'RC-2', 'Wallet Recharge', '2026-08-06 06:47:26', '2026-08-06 06:47:26'),
(929, 24, 'credit', 500.00, 'upi', 'RC-1', 'Wallet Recharge', '2026-08-06 06:47:40', '2026-08-06 06:47:40'),
(930, 24, 'debit', 227.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-06 06:50:31', '2026-08-06 06:50:31'),
(931, 25, 'credit', 500.00, 'upi', 'RC-3', 'Wallet Recharge', '2026-08-06 06:55:08', '2026-08-06 06:55:08'),
(932, 25, 'debit', 280.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-06 06:56:44', '2026-08-06 06:56:44'),
(933, 24, 'credit', 50.00, 'slot win', 'WIN-3', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(934, 24, 'credit', 500.00, 'slot win', 'WIN-4', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(935, 24, 'credit', 6000.00, 'slot win', 'WIN-7', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(936, 24, 'credit', 50.00, 'slot win', 'WIN-9', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(937, 24, 'credit', 50.00, 'slot win', 'WIN-10', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(938, 24, 'credit', 100.00, 'slot win', 'WIN-11', 'Slot winning amount credited', '2026-08-06 07:38:05', '2026-08-06 07:38:05'),
(939, 32, 'credit', 500.00, 'upi', 'RC-5', 'Wallet Recharge', '2026-08-07 05:05:49', '2026-08-07 05:05:49'),
(940, 32, 'debit', 175.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 05:16:17', '2026-08-07 05:16:17'),
(941, 27, 'credit', 175.00, 'upi', 'RC-6', 'Wallet Recharge', '2026-08-07 07:11:26', '2026-08-07 07:11:26'),
(942, 27, 'debit', 175.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 07:13:39', '2026-08-07 07:13:39'),
(943, 27, 'credit', 100.00, 'upi', 'RC-7', 'Wallet Recharge', '2026-08-07 07:18:54', '2026-08-07 07:18:54'),
(944, 32, 'debit', 240.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 07:20:49', '2026-08-07 07:20:49'),
(945, 27, 'debit', 100.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 07:20:53', '2026-08-07 07:20:53'),
(946, 27, 'credit', 200.00, 'upi', 'RC-9', 'Wallet Recharge', '2026-08-07 08:17:02', '2026-08-07 08:17:02'),
(947, 27, 'debit', 175.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 08:27:04', '2026-08-07 08:27:04'),
(948, 27, 'debit', 25.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 09:06:21', '2026-08-07 09:06:21'),
(949, 27, 'credit', 100.00, 'upi', 'RC-10', 'Wallet Recharge', '2026-08-07 09:11:18', '2026-08-07 09:11:18'),
(950, 27, 'debit', 90.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 09:15:53', '2026-08-07 09:15:53'),
(952, 1, 'debit', 372.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 10:58:57', '2026-08-07 10:58:57'),
(953, 27, 'credit', 425.00, 'upi', 'RC-12', 'Wallet Recharge', '2026-08-07 12:17:59', '2026-08-07 12:17:59'),
(954, 27, 'debit', 425.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-07 12:22:05', '2026-08-07 12:22:05'),
(955, 1, 'credit', 100.00, 'slot win', 'WIN-59', 'Slot winning amount credited', '2026-08-07 12:27:13', '2026-08-07 12:27:13'),
(956, 1, 'credit', 100.00, 'slot win', 'WIN-58', 'Slot winning amount credited', '2026-08-07 12:27:13', '2026-08-07 12:27:13'),
(957, 1, 'credit', 100.00, 'slot win', 'WIN-57', 'Slot winning amount credited', '2026-08-07 12:27:13', '2026-08-07 12:27:13'),
(958, 1, 'credit', 12000.00, 'slot win', 'WIN-60', 'Slot winning amount credited', '2026-08-07 12:27:13', '2026-08-07 12:27:13'),
(959, 1, 'credit', 60000.00, 'slot win', 'WIN-64', 'Slot winning amount credited', '2026-08-07 12:27:13', '2026-08-07 12:27:13'),
(960, 39, 'credit', 100.00, 'upi', 'RC-14', 'Wallet Recharge', '2026-08-07 15:25:41', '2026-08-07 15:25:41'),
(961, 39, 'debit', 29.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-08 06:53:20', '2026-08-08 06:53:20'),
(962, 39, 'debit', 44.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-08 09:13:05', '2026-08-08 09:13:05'),
(963, 39, 'debit', 7.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-08 09:16:37', '2026-08-08 09:16:37'),
(964, 27, 'credit', 100.00, 'upi', 'RC-15', 'Wallet Recharge', '2026-08-08 09:25:22', '2026-08-08 09:25:22'),
(965, 27, 'debit', 7.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-08 09:25:46', '2026-08-08 09:25:46'),
(966, 27, 'debit', 100.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-08 09:26:40', '2026-08-08 09:26:40'),
(967, 39, 'credit', 100.00, 'upi', 'RC-16', 'Wallet Recharge', '2026-08-09 07:13:37', '2026-08-09 07:13:37'),
(968, 24, 'debit', 105.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 07:17:51', '2026-08-09 07:17:51'),
(969, 39, 'debit', 43.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 07:18:10', '2026-08-09 07:18:10'),
(970, 24, 'credit', 150.00, 'slot win', 'WIN-96', 'Slot winning amount credited', '2026-08-09 07:35:08', '2026-08-09 07:35:08'),
(971, 24, 'credit', 500.00, 'slot win', 'WIN-100', 'Slot winning amount credited', '2026-08-09 07:35:08', '2026-08-09 07:35:08'),
(972, 39, 'debit', 65.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 08:55:10', '2026-08-09 08:55:10'),
(973, 24, 'debit', 105.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 09:19:49', '2026-08-09 09:19:49'),
(974, 39, 'credit', 25.00, 'slot win', 'WIN-111', 'Slot winning amount credited', '2026-08-09 09:40:58', '2026-08-09 09:40:58'),
(975, 24, 'credit', 50.00, 'slot win', 'WIN-116', 'Slot winning amount credited', '2026-08-09 09:51:01', '2026-08-09 09:51:01'),
(976, 24, 'credit', 100.00, 'slot win', 'WIN-119', 'Slot winning amount credited', '2026-08-09 09:51:01', '2026-08-09 09:51:01'),
(977, 24, 'debit', 105.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 12:15:01', '2026-08-09 12:15:01'),
(978, 24, 'debit', 105.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 14:08:51', '2026-08-09 14:08:51'),
(979, 24, 'debit', 90.00, NULL, NULL, 'Lottery Booking Amount Deducted', '2026-08-09 14:10:03', '2026-08-09 14:10:03'),
(980, 24, 'credit', 150.00, 'slot win', 'WIN-126', 'Slot winning amount credited', '2026-08-09 17:33:41', '2026-08-09 17:33:41'),
(981, 24, 'credit', 150.00, 'slot win', 'WIN-137', 'Slot winning amount credited', '2026-08-09 17:33:41', '2026-08-09 17:33:41');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_withdrawals`
--

CREATE TABLE `wallet_withdrawals` (
  `wallet_withdrawal_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  ADD PRIMARY KEY (`bank_account_id`),
  ADD UNIQUE KEY `customer_id` (`customer_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `customer_id` (`customer_id`,`slot_id`,`slot_items_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `customers_mobile_unique` (`mobile`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`slot_id`),
  ADD KEY `draw_time` (`draw_time`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `slot_items`
--
ALTER TABLE `slot_items`
  ADD PRIMARY KEY (`slot_items_id`),
  ADD KEY `slot_items_slot_id_foreign` (`slot_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wallet_recharges`
--
ALTER TABLE `wallet_recharges`
  ADD PRIMARY KEY (`wallet_recharge_id`);

--
-- Indexes for table `wallet_recharge_requests`
--
ALTER TABLE `wallet_recharge_requests`
  ADD PRIMARY KEY (`wallet_recharge_request_id`),
  ADD KEY `wallet_recharge_requests_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`wallet_transaction_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `wallet_withdrawals`
--
ALTER TABLE `wallet_withdrawals`
  ADD PRIMARY KEY (`wallet_withdrawal_id`),
  ADD KEY `wallet_withdrawals_customer_id_status_index` (`customer_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_accounts`
--
ALTER TABLE `bank_accounts`
  MODIFY `bank_account_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `slot_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=472;

--
-- AUTO_INCREMENT for table `slot_items`
--
ALTER TABLE `slot_items`
  MODIFY `slot_items_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3942;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallet_recharges`
--
ALTER TABLE `wallet_recharges`
  MODIFY `wallet_recharge_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wallet_recharge_requests`
--
ALTER TABLE `wallet_recharge_requests`
  MODIFY `wallet_recharge_request_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `wallet_transaction_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=982;

--
-- AUTO_INCREMENT for table `wallet_withdrawals`
--
ALTER TABLE `wallet_withdrawals`
  MODIFY `wallet_withdrawal_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `slot_items`
--
ALTER TABLE `slot_items`
  ADD CONSTRAINT `slot_items_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `slots` (`slot_id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_recharge_requests`
--
ALTER TABLE `wallet_recharge_requests`
  ADD CONSTRAINT `wallet_recharge_requests_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
