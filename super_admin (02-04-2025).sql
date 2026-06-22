-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2025 at 11:56 AM
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
-- Database: `super_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `albumimages`
--

CREATE TABLE `albumimages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `album_id` int(10) UNSIGNED NOT NULL,
  `image` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `albumimages`
--

INSERT INTO `albumimages` (`id`, `album_id`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, '[\"albumimage_1738226760_default.png\",\"albumimage_1738226760_sailogo.jpg\",\"albumimage_1738226860_1732003658_673c474a18d95.jpg\",\"albumimage_1738226860_1732083420_673d7edc55b25.png\",\"albumimage_1738231050_1732088659_673d935325026.png\"]', '2025-01-30 03:15:15', '2025-01-30 04:27:30'),
(2, 2, NULL, '2025-01-30 04:28:12', '2025-01-30 04:28:12'),
(3, 3, NULL, '2025-01-30 04:28:13', '2025-01-30 04:28:13'),
(4, 4, '[]', '2025-01-30 04:32:17', '2025-04-01 23:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `title`, `image`, `created_at`, `updated_at`) VALUES
(1, 'album one', 'album_1738226714_default.png', '2025-01-30 03:15:14', '2025-01-30 03:15:14'),
(4, 'album', 'album_1738231337_sailogo.jpg', '2025-01-30 04:32:17', '2025-01-30 04:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `short_title` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sequence` int(11) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `short_title`, `title`, `image`, `description`, `sequence`, `link`, `status`, `created_at`, `updated_at`) VALUES
(1, 'short title', 'title', 'banner_1737702186_default.png', NULL, 1, NULL, 'Active', '2025-01-24 01:33:06', '2025-02-10 05:14:14'),
(2, 'title', 'title', 'banner_1737704602_1732003658_673c474a1a61e.jpg', NULL, 2, NULL, 'Active', '2025-01-24 02:13:22', '2025-01-24 05:51:12'),
(3, 'short title', 'test', 'banner_1737712870_1732092394_673da1ea9c117.jpg', NULL, 3, NULL, 'Inactive', '2025-01-24 04:31:10', '2025-01-24 04:34:30'),
(5, 'short title', 'title', 'banner_1737702186_default.png', NULL, 1, NULL, 'Inactive', '2025-01-24 01:33:06', '2025-01-24 06:07:29'),
(6, 'title', 'title', 'banner_1737704602_1732003658_673c474a1a61e.jpg', NULL, 2, NULL, 'Active', '2025-01-24 02:13:22', '2025-01-24 05:51:12'),
(7, 'short title', 'test', 'banner_1737712870_1732092394_673da1ea9c117.jpg', NULL, 3, NULL, 'Inactive', '2025-01-24 04:31:10', '2025-01-24 04:34:30'),
(8, 'short title', 'title', 'banner_1737702186_default.png', NULL, 1, NULL, 'Inactive', '2025-01-24 01:33:06', '2025-01-24 06:07:29'),
(9, 'title', 'title', 'banner_1737704602_1732003658_673c474a1a61e.jpg', NULL, 2, NULL, 'Active', '2025-01-24 02:13:22', '2025-01-24 05:51:12'),
(10, 'short title', 'test', 'banner_1737712870_1732092394_673da1ea9c117.jpg', NULL, 3, NULL, 'Inactive', '2025-01-24 04:31:10', '2025-01-24 04:34:30'),
(11, 'short title', 'title', 'banner_1737702186_default.png', NULL, 1, NULL, 'Inactive', '2025-01-24 01:33:06', '2025-01-24 06:07:29'),
(12, 'title', 'title', 'banner_1737704602_1732003658_673c474a1a61e.jpg', NULL, 2, NULL, 'Active', '2025-01-24 02:13:22', '2025-01-24 05:51:12'),
(13, 'short title', 'test ukkhjkhkjhkjkh', 'banner_1737712870_1732092394_673da1ea9c117.jpg', NULL, 3, NULL, 'Active', '2025-01-24 04:31:10', '2025-04-01 23:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `publish_date` date NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `is_latest` varchar(255) NOT NULL DEFAULT 'N',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `short_description`, `description`, `image`, `publish_date`, `status`, `is_latest`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', '<p>testtest</p>', 'blog_1739347631_default.png', '2025-02-20', 'Active', 'N', '2025-02-12 02:37:11', '2025-04-01 06:57:35');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_enquiries`
--

CREATE TABLE `contact_enquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_enquiries`
--

INSERT INTO `contact_enquiries` (`id`, `name`, `mobile_number`, `email`, `subject`, `created_at`, `updated_at`) VALUES
(1, 'riyaz', '9853254232', 'test@gmail.com', NULL, '2025-04-02 06:13:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_configs`
--

CREATE TABLE `email_configs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `protocol` varchar(255) NOT NULL,
  `mailtype` varchar(255) NOT NULL,
  `smtp_host` varchar(255) NOT NULL,
  `smtp_port` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `password` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_configs`
--

INSERT INTO `email_configs` (`id`, `protocol`, `mailtype`, `smtp_host`, `smtp_port`, `sender_email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'smtp', '1', 'smtp.gmail.com', '465', 'aradhanab2017@gmail.com', 'eyJpdiI6InpRYTk5by9ZWlJBY2l5RGpYWUY0cXc9PSIsInZhbHVlIjoiZmVEUXMvV0dHTkQxTkNRNkJ2N1MxQ3FJQUpYazBLVmZpUGwzTGg3WndZbz0iLCJtYWMiOiI4NjNmNjM2MzI4Y2M5ZjZmZmQ4YjE3YzM4Yzc4MzhmYWQxNjA4MzhiZDcxM2IwNTc2NDY2MDFlYzg4M2U5YWI4IiwidGFnIjoiIn0=', NULL, '2025-04-02 04:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'gallery_1738147127_default.png', 'Active', '2025-01-29 05:08:47', '2025-04-01 23:57:37');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
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
(17, '2025_04_02_063404_create_newletter_subscriptions_table', 14);

-- --------------------------------------------------------

--
-- Table structure for table `newletter_subscriptions`
--

CREATE TABLE `newletter_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `newletter_subscriptions`
--

INSERT INTO `newletter_subscriptions` (`id`, `name`, `email`, `message`, `created_at`, `updated_at`) VALUES
(2, 'test123', 'test123@gmail.com', NULL, '2025-04-02 06:37:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `agent` varchar(255) NOT NULL,
  `merchant_id` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `status` enum('Test','Live') NOT NULL DEFAULT 'Test',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `agent`, `merchant_id`, `api_key`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Razorpay', 'rzp_test_2hEj93EL0gFSAp', 'EpfQnpGGOd71Ub44IUcXaVgv', 'Live', NULL, '2025-03-28 05:17:01');

-- --------------------------------------------------------

--
-- Table structure for table `seos`
--

CREATE TABLE `seos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_link` varchar(255) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seos`
--

INSERT INTO `seos` (`id`, `page_name`, `page_link`, `meta_title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', 'welcome to website', 'welcome to website', '2025-03-28 01:58:13', '2025-03-28 01:58:13'),
(2, 'Contact Us', 'contact-us', 'welcome to website development', 'welcome to website development welcome to website development', '2025-03-28 01:59:39', '2025-03-28 02:13:42');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'title', '<p>description</p>', 'service_1739255323_default.png', 'Active', '2025-02-11 00:58:43', '2025-04-02 00:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4tr6wpSeBLZeQwm0odVVDxGUVzEmvhQVFMbQlSPB', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicVJjVXFLd3ZBQXNGNHlVazVnQ0htbVZLZHA5WDd2MThDQWpOYWMwTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbWFpbF9jb25maWd1cmF0aW9uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czoyMDA6ImV5SnBkaUk2SW14elNFbHVOazVMUlVWeFEwNU1lVXBWUVhSblJuYzlQU0lzSW5aaGJIVmxJam9pUkZaQmNIaFZPRzFDYVdWTlpXVkZRM0kxZG5aQ1p6MDlJaXdpYldGaklqb2lZVFU0WVdGa01qUmlNell5TkRBM01ETTJOV00wWVRCbU5qRXlOelExTVdJMk9HTTNaak15TXpkaE0yUmlaV1l6TXpBeFpXSTBZVEpsT0RreVpXRmpPU0lzSW5SaFp5STZJaUo5Ijt9', 1743587737);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` tinyint(4) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `description`, `image`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(4, 'ram', 'test', 'testtest', 'testimonial_1739170197_default.png', 5, 'Active', '2025-02-10 01:19:57', '2025-02-10 05:54:22'),
(5, 'test', 'test', 'description', 'testimonial_1739190586_sailogo.jpg', 5, 'Active', '2025-02-10 06:59:46', '2025-02-10 06:59:46'),
(6, 'test', 'test', 'description', 'testimonial_1739190586_sailogo.jpg', 4, 'Inactive', '2025-02-10 06:59:46', '2025-02-10 08:33:01'),
(8, 'FDFDSFDSF', NULL, 'SFAFFDSFDSF', 'testimonial_1743506277_testimonial_1739190586_sailogo (2) - Copy.jpg', 4, 'Active', '2025-04-01 05:47:57', '2025-04-01 05:54:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` longtext NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, 'eyJpdiI6ImxzSEluNk5LRUVxQ05MeUpVQXRnRnc9PSIsInZhbHVlIjoiRFZBcHhVOG1CaWVNZWVFQ3I1dnZCZz09IiwibWFjIjoiYTU4YWFkMjRiMzYyNDA3MDM2NWM0YTBmNjEyNzQ1MWI2OGM3ZjMyMzdhM2RiZWYzMzAxZWI0YTJlODkyZWFjOSIsInRhZyI6IiJ9', NULL, '2025-01-22 13:03:41', '2025-03-31 23:50:02');

-- --------------------------------------------------------

--
-- Table structure for table `websettings`
--

CREATE TABLE `websettings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `hotline_number` varchar(255) NOT NULL,
  `sales_email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `fav_icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `websettings`
--

INSERT INTO `websettings` (`id`, `contact_person`, `contact_email`, `contact_number`, `hotline_number`, `sales_email`, `address`, `logo`, `fav_icon`, `created_at`, `updated_at`) VALUES
(1, 'Super admin', 'superadmin@gmail.com', '9851238521', '9851238521', NULL, 'no 2, abc street, new york.', 'logo.png', 'fav_icon.png', NULL, '2025-03-29 02:46:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albumimages`
--
ALTER TABLE `albumimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `albumimages_album_id_index` (`album_id`);

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_configs`
--
ALTER TABLE `email_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newletter_subscriptions`
--
ALTER TABLE `newletter_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seos`
--
ALTER TABLE `seos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `websettings`
--
ALTER TABLE `websettings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albumimages`
--
ALTER TABLE `albumimages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_enquiries`
--
ALTER TABLE `contact_enquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `email_configs`
--
ALTER TABLE `email_configs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `newletter_subscriptions`
--
ALTER TABLE `newletter_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seos`
--
ALTER TABLE `seos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `websettings`
--
ALTER TABLE `websettings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
