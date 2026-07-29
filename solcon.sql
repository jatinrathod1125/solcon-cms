-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 29, 2026 at 02:05 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `solcon`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=447 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `module`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, 'USER_CREATED', 'User MANAGER (manager@solcon.com) created with role and department assignments.', 'User Management', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 10:48:34', '2026-07-12 10:48:34'),
(2, 2, 'USER_CREATED', 'User Adhesive (adhesive@solcon.com) created with role and department assignments.', 'User Management', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 10:49:59', '2026-07-12 10:49:59'),
(3, 2, 'USER_CREATED', 'User Grout (grout@solcon.com) created with role and department assignments.', 'User Management', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 10:50:44', '2026-07-12 10:50:44'),
(4, 2, 'USER_CREATED', 'User Epoxy (epoxy@solcon.com) created with role and department assignments.', 'User Management', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 10:51:07', '2026-07-12 10:51:07'),
(5, 2, 'RAW_MATERIALS_IMPORTED', 'Imported raw materials inventory successfully from CSV containing 6 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:05:03', '2026-07-12 11:05:03'),
(6, 2, 'RAW_MATERIALS_IMPORTED', 'Imported raw materials inventory successfully from CSV containing 20 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:26:25', '2026-07-12 11:26:25'),
(7, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 1.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:28:52', '2026-07-12 11:28:52'),
(8, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 2.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:29:49', '2026-07-12 11:29:49'),
(9, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 3.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:30:57', '2026-07-12 11:30:57'),
(10, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 7.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:33:30', '2026-07-12 11:33:30'),
(11, 2, 'FORMULA_UPDATED', 'Updated formula version #1 for grade ID 7.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(12, 2, 'FORMULA_UPDATED', 'Created new Grout formula version #1 for color ID 5.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-12 12:28:06', '2026-07-12 12:28:06'),
(13, 2, 'RAW_MATERIALS_IMPORTED', 'Imported raw materials inventory successfully from CSV containing 9 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(14, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260713-001 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:09:20', '2026-07-13 04:09:20'),
(15, 2, 'MARKETING_ORDER_STATUS_CHANGED', 'Marketing order MKT-20260713-001 status changed from pending to in_progress', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:09:42', '2026-07-13 04:09:42'),
(16, 2, 'BATCH_CREATED', 'Production batch #ADH-20260713-0001 started on machine ID 4 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:10:52', '2026-07-13 04:10:52'),
(17, 2, 'BATCH_COMPLETED', 'Production batch #ADH-20260713-0001 completed. Output: 98 bags (1960 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(18, 2, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260713-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(19, 2, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260713-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(20, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (increase) of 2 units for product: N/A (20KG). Reason: opening stock.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:20:09', '2026-07-13 04:20:09'),
(21, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 100 units for product: N/A (20KG). Reason: Marketing Order MKT-20260713-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:20:29', '2026-07-13 04:20:29'),
(22, 2, 'MARKETING_ORDER_COMPLETED', 'Marketing order MKT-20260713-001 completed. Finished goods deducted for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:20:29', '2026-07-13 04:20:29'),
(23, 2, 'BATCH_CREATED', 'Production batch #ADH-20260713-0002 started on machine ID 4 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:28:25', '2026-07-13 04:28:25'),
(24, 2, 'BATCH_COUPON_UPDATED', 'Coupon updated for production batch #ADH-20260713-0002. New Coupon: RS-10 Solcon', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:28:32', '2026-07-13 04:28:32'),
(25, 2, 'BATCH_COMPLETED', 'Production batch #ADH-20260713-0002 completed. Output: 100 bags (2000 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(26, 2, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260713-0002.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(27, 2, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260713-0002.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(28, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260713-002 created for party: Xyz Compnay', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:45:23', '2026-07-13 04:45:23'),
(29, 2, 'MARKETING_ORDER_STATUS_CHANGED', 'Marketing order MKT-20260713-002 status changed from pending to in_progress', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:48:19', '2026-07-13 04:48:19'),
(30, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 10 units for product: F-107 (RS-10 Solcon) (20KG). Reason: Marketing Order MKT-20260713-002.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:48:53', '2026-07-13 04:48:53'),
(31, 2, 'MARKETING_ORDER_COMPLETED', 'Marketing order MKT-20260713-002 completed. Finished goods deducted for party: Xyz Compnay', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:48:53', '2026-07-13 04:48:53'),
(32, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260713-003 created for party: Xyz Compnay', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:49:17', '2026-07-13 04:49:17'),
(33, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 10 units for product: F-107 (RS-10 Solcon) (20KG). Reason: Marketing Order MKT-20260713-003.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:56:47', '2026-07-13 04:56:47'),
(34, 2, 'MARKETING_ORDER_COMPLETED', 'Marketing order MKT-20260713-003 completed. Finished goods deducted for party: Xyz Compnay', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:56:47', '2026-07-13 04:56:47'),
(35, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260713-004 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 04:57:17', '2026-07-13 04:57:17'),
(36, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260713-005 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:46:33', '2026-07-13 05:46:33'),
(37, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 10 units for product: F-107 (RS-10 Solcon) (20KG). Reason: Marketing Order MKT-20260713-005.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:47:03', '2026-07-13 05:47:03'),
(38, 2, 'MARKETING_ORDER_COMPLETED', 'Marketing order MKT-20260713-005 completed. Finished goods deducted for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:47:03', '2026-07-13 05:47:03'),
(39, 2, 'FORMULA_UPDATED', 'Created new Grout formula version #1 for color ID 1.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(40, 2, 'FORMULA_UPDATED', 'Created new Grout formula version #1 for color ID 3.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:53:00', '2026-07-13 05:53:00'),
(41, 2, 'FORMULA_UPDATED', 'Updated Grout formula version #1 for color ID 3.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(42, 2, 'FORMULA_UPDATED', 'Created new Grout formula version #1 for color ID 5.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(43, 2, 'PDF_DOWNLOADED', 'WhatsApp landscape production report PDF downloaded for range 2026-07-13 to 2026-07-13.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 06:27:38', '2026-07-13 06:27:38'),
(44, 2, 'FORMULA_UPDATED', 'Created new Grout formula version #1 for color ID 7.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(45, 2, 'RAW_MATERIALS_IMPORTED', 'Imported raw materials inventory successfully from CSV containing 12 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 08:38:47', '2026-07-13 08:38:47'),
(46, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 10 units of 700gm Black Filler Pouch (Assembly Component). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 08:55:15', '2026-07-13 08:55:15'),
(47, 2, 'BATCH_CREATED', 'Production batch #ADH-20260713-0003 started on machine ID 4 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 09:24:02', '2026-07-13 09:24:02'),
(48, 2, 'BATCH_COMPLETED', 'Production batch #ADH-20260713-0003 completed. Output: 100 bags (2000 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 09:24:18', '2026-07-13 09:24:18'),
(49, 2, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260713-0003.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 09:24:18', '2026-07-13 09:24:18'),
(50, 2, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260713-0003.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 09:24:18', '2026-07-13 09:24:18'),
(51, 2, 'RAW_MATERIALS_IMPORTED', 'Imported raw materials inventory successfully from CSV containing 29 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 10:21:52', '2026-07-13 10:21:52'),
(52, 2, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0001 started on machine M-01 (Color: White).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:02:48', '2026-07-13 11:02:48'),
(53, 2, 'TIMER_STARTED', '5-Minute dry mix timer started for Grout batch #GRT-20260713-0001 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:02:51', '2026-07-13 11:02:51'),
(54, 2, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0001. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:03:56', '2026-07-13 11:03:56'),
(55, 2, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:03:59', '2026-07-13 11:03:59'),
(56, 2, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0001 completed. Output: 36 bags (900 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(57, 2, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(58, 2, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(59, 5, 'BATCH_CREATED', 'Grout production batch #G1426 started on machine M-04 (Color: Black).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 11:55:27', '2026-07-13 11:55:27'),
(60, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #G1426 on machine M-04.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 11:55:31', '2026-07-13 11:55:31'),
(61, 5, 'BATCH_CREATED', 'Grout production batch #G1426R started on machine M-05 (Color: Red).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 11:56:54', '2026-07-13 11:56:54'),
(62, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #G1426R on machine M-05.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 11:56:56', '2026-07-13 11:56:56'),
(63, 2, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0004 started on machine M-01 (Color: White).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:37:12', '2026-07-13 15:37:12'),
(64, 2, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0004 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:37:15', '2026-07-13 15:37:15'),
(65, 2, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0004. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:38:21', '2026-07-13 15:38:21'),
(66, 2, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0004.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:38:25', '2026-07-13 15:38:25'),
(67, 2, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0004 completed. Output: 36 bags (900 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(68, 2, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0004.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(69, 2, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0004.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(70, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0005 started on machine M-01 (Color: White).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 15:41:30', '2026-07-13 15:41:30'),
(71, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0005 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 15:41:34', '2026-07-13 15:41:34'),
(72, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0005. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:13:49', '2026-07-13 16:13:49'),
(73, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0005.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:14:32', '2026-07-13 16:14:32'),
(74, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0005 completed. Output: 40 bags (1000 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(75, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0005.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(76, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0005.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(77, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0006 started on machine M-01 (Color: Ivory).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:16:57', '2026-07-13 16:16:57'),
(78, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0006 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:17:14', '2026-07-13 16:17:14'),
(79, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0006. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:46:22', '2026-07-13 16:46:22'),
(80, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0006.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:46:38', '2026-07-13 16:46:38'),
(81, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0006 completed. Output: 36 bags (900 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(82, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0006.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(83, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0006.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(84, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0007 started on machine M-01 (Color: Ivory).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:47:29', '2026-07-13 16:47:29'),
(85, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0007 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:47:48', '2026-07-13 16:47:48'),
(86, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0007. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:54:51', '2026-07-13 16:54:51'),
(87, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0007.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:55:06', '2026-07-13 16:55:06'),
(88, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0007 completed. Output: 38 bags (950 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(89, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0007.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(90, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0007.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(91, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0008 started on machine M-01 (Color: Ivory).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:56:03', '2026-07-13 16:56:03'),
(92, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0008 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 16:56:16', '2026-07-13 16:56:16'),
(93, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0008. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:23:37', '2026-07-13 17:23:37'),
(94, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0008.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:24:40', '2026-07-13 17:24:40'),
(95, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0008 completed. Output: 39 bags (975 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(96, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0008.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(97, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0008.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(98, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0009 started on machine M-01 (Color: Ivory).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:42:39', '2026-07-13 17:42:39'),
(99, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0009 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 17:42:52', '2026-07-13 17:42:52'),
(100, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0009. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:07:58', '2026-07-13 18:07:58'),
(101, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0009.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:08:17', '2026-07-13 18:08:17'),
(102, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0009 completed. Output: 39 bags (975 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(103, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0009.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(104, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0009.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(105, 5, 'BATCH_CREATED', 'Grout production batch #GRT-20260713-0010 started on machine M-01 (Color: White).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:09:15', '2026-07-13 18:09:15'),
(106, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #GRT-20260713-0010 on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-13 18:09:32', '2026-07-13 18:09:32'),
(107, 5, 'MIXING_COMPLETED', 'Mixing completed for Grout batch #GRT-20260713-0010. Status: Ready For Packing.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:01:45', '2026-07-14 03:01:45'),
(108, 5, 'PACKING_STARTED', 'Packing started for Grout batch #GRT-20260713-0010.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:02:18', '2026-07-14 03:02:18'),
(109, 5, 'BATCH_COMPLETED', 'Grout production batch #GRT-20260713-0010 completed. Output: 38 bags (950 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(110, 5, 'STOCK_DEDUCTED', 'Stock deducted for Grout production batch #GRT-20260713-0010.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(111, 5, 'LEDGER_CREATED', 'Stock ledger entries created for Grout production batch #GRT-20260713-0010.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(112, 5, 'BATCH_CREATED', 'Grout production batch #G1426G started on machine M-01 (Color: Ivory).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:30:35', '2026-07-14 03:30:35'),
(113, 5, 'TIMER_STARTED', '59-Minute dry mix timer started for Grout batch #G1426G on machine M-01.', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 03:30:50', '2026-07-14 03:30:50'),
(114, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0001 started on machine ID 5 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:53:15', '2026-07-14 06:53:15'),
(115, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0001 completed. Output: 97 bags (1940 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(116, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(117, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(118, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0002 started on machine ID 5 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:53:57', '2026-07-14 06:53:57'),
(119, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0002 completed. Output: 107 bags (2140 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(120, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0002.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(121, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0002.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(122, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0003 started on machine ID 5 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:15', '2026-07-14 06:54:15'),
(123, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0003 completed. Output: 104 bags (2080 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(124, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0003.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(125, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0003.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(126, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0004 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:40', '2026-07-14 06:54:40'),
(127, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0004 completed. Output: 101 bags (2020 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(128, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0004.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(129, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0004.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(130, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0005 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:01', '2026-07-14 06:55:01'),
(131, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0005 completed. Output: 106 bags (2120 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(132, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0005.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(133, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0005.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(134, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0006 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:21', '2026-07-14 06:55:21'),
(135, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0006 completed. Output: 106 bags (2120 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(136, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0006.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(137, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0006.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(138, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0007 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:42', '2026-07-14 06:55:42'),
(139, 4, 'BATCH_COUPON_UPDATED', 'Coupon updated for production batch #ADH-20260714-0007. New Coupon: RS-40 Solcon', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:48', '2026-07-14 06:55:48'),
(140, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0007 completed. Output: 108 bags (2160 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(141, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0007.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(142, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0007.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(143, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0008 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:12', '2026-07-14 06:56:12'),
(144, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0008 completed. Output: 108 bags (2160 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:19', '2026-07-14 06:56:19'),
(145, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0008.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:19', '2026-07-14 06:56:19'),
(146, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0008.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:19', '2026-07-14 06:56:19'),
(147, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0009 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:36', '2026-07-14 06:56:36'),
(148, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0009 completed. Output: 106 bags (2120 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(149, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0009.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(150, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0009.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(151, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0010 started on machine ID 5 (Grade: F-107).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:56:56', '2026-07-14 06:56:56'),
(152, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0010 completed. Output: 107 bags (2140 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(153, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0010.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(154, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0010.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(155, 4, 'PDF_DOWNLOADED', 'WhatsApp landscape production report PDF downloaded for range 2026-07-14 to 2026-07-14.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 06:58:19', '2026-07-14 06:58:19'),
(156, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0011 started on machine ID 6 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:11', '2026-07-14 07:00:11'),
(157, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0011 completed. Output: 162 bags (3240 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:17', '2026-07-14 07:00:17'),
(158, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0011.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:17', '2026-07-14 07:00:17'),
(159, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0011.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:17', '2026-07-14 07:00:17'),
(160, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0012 started on machine ID 6 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:27', '2026-07-14 07:00:27'),
(161, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0012 completed. Output: 102 bags (2040 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(162, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0012.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(163, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0012.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(164, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0013 started on machine ID 6 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:46', '2026-07-14 07:00:46'),
(165, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0013 completed. Output: 102 bags (2040 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(166, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0013.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(167, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0013.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(168, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0014 started on machine ID 6 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:08', '2026-07-14 07:01:08'),
(169, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0014 completed. Output: 103 bags (2060 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(170, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0014.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(171, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0014.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:17', '2026-07-14 07:01:17'),
(172, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0015 started on machine ID 6 (Grade: F-121).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:38', '2026-07-14 07:01:38'),
(173, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0015 completed. Output: 115 bags (2300 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:47', '2026-07-14 07:01:47');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `module`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(174, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0015.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(175, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0015.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(176, 4, 'BATCH_CREATED', 'Production batch #ADH-20260714-0016 started on machine ID 4 (Grade: F-121).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:01:57', '2026-07-14 07:01:57'),
(177, 4, 'BATCH_COMPLETED', 'Production batch #ADH-20260714-0016 completed. Output: 112 bags (2240 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(178, 4, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260714-0016.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(179, 4, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260714-0016.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(180, 4, 'PDF_DOWNLOADED', 'WhatsApp landscape production report PDF downloaded for range 2026-07-14 to 2026-07-14.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:08:53', '2026-07-14 07:08:53'),
(181, 2, 'PDF_DOWNLOADED', 'Production report PDF downloaded for date range 2026-07-14 to 2026-07-14 (Dept: all).', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:09:26', '2026-07-14 07:09:26'),
(182, 4, 'PDF_DOWNLOADED', 'Production report PDF downloaded for date range 2026-07-14 to 2026-07-14 (Dept: all).', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:09:37', '2026-07-14 07:09:37'),
(183, 4, 'PDF_DOWNLOADED', 'Production report PDF downloaded for date range 2026-07-14 to 2026-07-14 (Dept: all).', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 07:16:10', '2026-07-14 07:16:10'),
(184, 1, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260714-001 created for party: xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 11:51:25', '2026-07-14 11:51:25'),
(185, 1, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260714-002 created for party: Xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Mobile Safari/537.36', '2026-07-14 11:59:48', '2026-07-14 11:59:48'),
(186, 2, 'MARKETING_ORDER_STATUS_CHANGED', 'Marketing order MKT-20260714-002 status changed from pending to in_progress', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-14 12:00:52', '2026-07-14 12:00:52'),
(187, 1, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260721-001 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 14:40:05', '2026-07-21 14:40:05'),
(188, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260721-001 approved for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 14:41:27', '2026-07-21 14:41:27'),
(189, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260721-001 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 15:10:47', '2026-07-21 15:10:47'),
(190, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260721-001 approved for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 15:11:29', '2026-07-21 15:11:29'),
(191, 1, 'DISPATCH_CREATED', 'Dispatch DISP-20260721-001 created for party: jatin bhai', 'System', '127.0.0.1', 'Symfony', '2026-07-21 16:35:53', '2026-07-21 16:35:53'),
(192, 2, 'DISPATCH_LOADING_STARTED', 'Loading started for dispatch DISP-20260721-001', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:37:59', '2026-07-21 16:37:59'),
(193, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 100 units for product: F-101 (No Coupon) (20KG). Reason: Dispatch DISP-20260721-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:38:12', '2026-07-21 16:38:12'),
(194, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 10 units for product: 1KG BUCKET (White) (1KG). Reason: Dispatch DISP-20260721-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:38:12', '2026-07-21 16:38:12'),
(195, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 5 units for product: 5KG BUCKET (White) (5KG). Reason: Dispatch DISP-20260721-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:38:13', '2026-07-21 16:38:13'),
(196, 2, 'DISPATCH_COMPLETED', 'Dispatch DISP-20260721-001 completed. Finished Goods stock deducted for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:38:13', '2026-07-21 16:38:13'),
(197, 1, 'DISPATCH_CREATED', 'Dispatch DISP-20260721-002 created for party: jatin bhai', 'System', '127.0.0.1', 'Symfony', '2026-07-21 16:43:37', '2026-07-21 16:43:37'),
(198, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260721-001 created for party: xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:52:49', '2026-07-21 16:52:49'),
(199, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260721-001 approved for party: xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:53:06', '2026-07-21 16:53:06'),
(200, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260721-001 created for party: xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:54:05', '2026-07-21 16:54:05'),
(201, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260721-001 created for party: xyz', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 16:56:51', '2026-07-21 16:56:51'),
(202, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260721-001 release status changed to: Hold', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 17:00:48', '2026-07-21 17:00:48'),
(203, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260721-001 release status changed to: Released', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 17:01:11', '2026-07-21 17:01:11'),
(204, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260721-001 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-21 17:06:17', '2026-07-21 17:06:17'),
(205, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-001 created for party: ABC MARBLE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:22:25', '2026-07-22 04:22:25'),
(206, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-002 created for party: OM SAI RAM GRAYNIGHT STONE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:25:07', '2026-07-22 04:25:07'),
(207, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260722-002 approved for party: OM SAI RAM GRAYNIGHT STONE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:26:01', '2026-07-22 04:26:01'),
(208, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-002 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:26:25', '2026-07-22 04:26:25'),
(209, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260722-001 created for party: OM SAI RAM GRAYNIGHT STONE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:36:12', '2026-07-22 04:36:12'),
(210, 2, 'DISPATCH_UPDATED', 'Dispatch DISP-20260722-001 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:37:24', '2026-07-22 04:37:24'),
(211, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260722-001 release status changed to: Released', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:40:55', '2026-07-22 04:40:55'),
(212, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260722-001 release status changed to: Hold', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:41:52', '2026-07-22 04:41:52'),
(213, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260722-001 release status changed to: Released', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:42:26', '2026-07-22 04:42:26'),
(214, 7, 'DISPATCH_LOADING_STARTED', 'Loading started for dispatch DISP-20260722-001', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:43:09', '2026-07-22 04:43:09'),
(215, 2, 'FINISHED_GOODS_EXPORTED', 'Finished goods inventory CSV exported containing 135 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 04:43:58', '2026-07-22 04:43:58'),
(216, 2, 'MAINTENANCE_UNLOCK_FAILED', 'Failed maintenance unlock attempt with incorrect password from IP: 127.0.0.1.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 10:38:04', '2026-07-22 10:38:04'),
(217, 2, 'MAINTENANCE_UNLOCK_FAILED', 'Failed maintenance unlock attempt with incorrect password from IP: 127.0.0.1.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 10:38:09', '2026-07-22 10:38:09'),
(218, 1, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-003 created for party: Test Highlight Party', 'System', '127.0.0.1', 'Symfony', '2026-07-22 10:47:16', '2026-07-22 10:47:16'),
(219, 1, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-003 updated', 'System', '127.0.0.1', 'Symfony', '2026-07-22 10:47:16', '2026-07-22 10:47:16'),
(220, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-002 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 10:50:17', '2026-07-22 10:50:17'),
(221, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260721-001 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 10:53:36', '2026-07-22 10:53:36'),
(222, 1, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-003 created for party: Specific Product Test Party', 'System', '127.0.0.1', 'Symfony', '2026-07-22 10:55:15', '2026-07-22 10:55:15'),
(223, 1, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-003 updated', 'System', '127.0.0.1', 'Symfony', '2026-07-22 10:55:15', '2026-07-22 10:55:15'),
(224, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-001 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 10:55:47', '2026-07-22 10:55:47'),
(225, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 350000 for raw material ID 12. Remarks: new', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:21:59', '2026-07-22 11:21:59'),
(226, 2, 'BATCH_CREATED', 'Production batch #ADH-20260722-0001 started on machine ID 4 (Grade: F-101).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:24:19', '2026-07-22 11:24:19'),
(227, 2, 'BATCH_COMPLETED', 'Production batch #ADH-20260722-0001 completed. Output: 98 bags (1960 KG).', 'Production', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(228, 2, 'STOCK_DEDUCTED', 'Stock deducted for production batch #ADH-20260722-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(229, 2, 'LEDGER_CREATED', 'Stock ledger entries created for production batch #ADH-20260722-0001.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(230, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1000 for raw material ID 35. Remarks: sbc', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:28:52', '2026-07-22 11:28:52'),
(231, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 500 units of 100 GM HARDNER BOTTLE FINISH (Assembly Component). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:38:20', '2026-07-22 11:38:20'),
(232, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 100 units of 200GM RESIN BOTTLE FINISH (Assembly Component). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:41:05', '2026-07-22 11:41:05'),
(233, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 50000 for raw material ID 39. Remarks: NEW', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:46:45', '2026-07-22 11:46:45'),
(234, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 100 units of 700gm Black Filler Pouch (Assembly Component). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:47:04', '2026-07-22 11:47:04'),
(235, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 100 units of 100 GM HARDNER BOTTLE FINISH (Assembly Component). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 11:48:11', '2026-07-22 11:48:11'),
(236, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-003 created for party: SUD', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 12:02:43', '2026-07-22 12:02:43'),
(237, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260722-004 created for party: ABC MARBLE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 12:03:48', '2026-07-22 12:03:48'),
(238, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order MKT-20260722-003 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 12:03:57', '2026-07-22 12:03:57'),
(239, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260722-004 approved for party: ABC MARBLE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 12:05:30', '2026-07-22 12:05:30'),
(240, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260722-002 created for party: ABC MARBLE', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 12:23:01', '2026-07-22 12:23:01'),
(241, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260722-004 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-22 16:57:28', '2026-07-22 16:57:28'),
(242, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 12 for raw material ID 38.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 06:20:44', '2026-07-23 06:20:44'),
(243, 2, 'DISPATCH_RELEASE_TOGGLED', 'Dispatch DISP-20260722-002 release status changed to: Released', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 07:03:13', '2026-07-23 07:03:13'),
(244, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 500 for packing material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:32:34', '2026-07-23 08:32:34'),
(245, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 500 for packing material ID 46.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:32:49', '2026-07-23 08:32:49'),
(246, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 10 units of Clip Box 2MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 08:33:21', '2026-07-23 08:33:21'),
(247, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260723-001 created for party: Digitek', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 10:25:58', '2026-07-23 10:25:58'),
(248, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260723-001 approved for party: Digitek', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 10:26:13', '2026-07-23 10:26:13'),
(249, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260723-001 created for party: Digitek', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 10:26:52', '2026-07-23 10:26:52'),
(250, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 100 units for product: F-101 (No Coupon) (20KG). Reason: Dispatch DISP-20260723-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 10:26:53', '2026-07-23 10:26:53'),
(251, 2, 'DISPATCH_COMPLETED', 'Dispatch DISP-20260723-001 completed. Finished Goods stock deducted for party: Digitek', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 10:26:53', '2026-07-23 10:26:53'),
(252, NULL, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 5 units for product: F-101 (No Coupon) (20 KG). Reason: Dispatch DISP-TEST-448.', 'System', '127.0.0.1', 'Symfony', '2026-07-23 10:57:10', '2026-07-23 10:57:10'),
(253, 1, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 5 units for product: F-101 (No Coupon) (20 KG). Reason: Dispatch DISP-TEST-183.', 'System', '127.0.0.1', 'Symfony', '2026-07-23 10:57:16', '2026-07-23 10:57:16'),
(254, 1, 'DISPATCH_COMPLETED', 'Dispatch DISP-TEST-183 completed. Finished Goods stock deducted for party: Test Party', 'System', '127.0.0.1', 'Symfony', '2026-07-23 10:57:16', '2026-07-23 10:57:16'),
(255, 2, 'FINISHED_GOODS_EXPORTED', 'Finished goods inventory CSV exported containing 1178 records.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 11:04:54', '2026-07-23 11:04:54'),
(256, 2, 'FINISHED_GOOD_CREATED', 'Finished Good \'N/A\' (0.100) created manually by admin with 1000 units.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-23 15:46:56', '2026-07-23 15:46:56'),
(257, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-794 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:13', '2026-07-24 03:55:13'),
(258, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-794 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:19', '2026-07-24 03:55:19'),
(259, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-794 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:24', '2026-07-24 03:55:24'),
(260, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-794 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:29', '2026-07-24 03:55:29'),
(261, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-794 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:35', '2026-07-24 03:55:35'),
(262, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order ORD-TEST-178 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 03:55:43', '2026-07-24 03:55:43'),
(263, 2, 'FINISHED_GOOD_CREATED', 'Finished Good \'Plastic Trowel\' (0.50) created manually by admin with 1000 units.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:00:50', '2026-07-24 04:00:50'),
(264, 2, 'FINISHED_GOOD_CREATED', 'Finished Good \'Steel Trowvel\' (0.100) created manually by admin with 500 units.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:51:07', '2026-07-24 04:51:07'),
(265, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260724-001 created for party: Supreme', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:52:05', '2026-07-24 04:52:05'),
(266, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260724-001 approved for party: Supreme', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:52:15', '2026-07-24 04:52:15'),
(267, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260724-001 created for party: Supreme', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:52:39', '2026-07-24 04:52:39'),
(268, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 100 units for product: F-101 (20KG). Reason: Dispatch DISP-20260724-001.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:52:40', '2026-07-24 04:52:40'),
(269, 2, 'DISPATCH_COMPLETED', 'Dispatch DISP-20260724-001 completed. Finished Goods stock deducted for party: Supreme', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 04:52:40', '2026-07-24 04:52:40'),
(270, 2, 'FINISHED_GOOD_CREATED', 'Finished Good \'PLIER\' (1) created manually by admin with 1 units.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 05:52:07', '2026-07-24 05:52:07'),
(271, 2, 'FINISHED_GOOD_CREATED', 'Finished Good \'VACUUM\' (1) created manually by admin with 1 units.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 05:52:49', '2026-07-24 05:52:49'),
(272, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (increase) of 1000 units for product: VACUUM (1). Reason: UPDATE.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 05:53:37', '2026-07-24 05:53:37'),
(273, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (increase) of 49 units for product: PLIER (1). Reason: UPDATE.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 05:54:00', '2026-07-24 05:54:00'),
(274, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (decrease) of 1 units for product: VACUUM (1). Reason: REMOVE.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:37:58', '2026-07-24 10:37:58'),
(275, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1000 for packing material ID 10.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:45:11', '2026-07-24 10:45:11'),
(276, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1000 for packing material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:45:37', '2026-07-24 10:45:37'),
(277, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of CLIP 2MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:45:56', '2026-07-24 10:45:56'),
(278, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 10 units of SPACER 2MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:48:10', '2026-07-24 10:48:10'),
(279, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260724-002 created for party: SPIDERMAN', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:48:52', '2026-07-24 10:48:52'),
(280, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260724-002 approved for party: SPIDERMAN', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-24 10:49:01', '2026-07-24 10:49:01'),
(281, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260725-001 created for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 04:18:18', '2026-07-25 04:18:18'),
(282, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260725-001 approved for party: jatin bhai', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 04:18:35', '2026-07-25 04:18:35'),
(283, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 50 units of TROWEL (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 04:21:56', '2026-07-25 04:21:56'),
(284, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260725-001 created for party: Madhuram Marble', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:37:41', '2026-07-25 11:37:41'),
(285, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260725-001 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:43:31', '2026-07-25 11:43:31'),
(286, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260725-001 approved for party: Madhuram Marble', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:43:55', '2026-07-25 11:43:55'),
(287, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1000 for packing material ID 10.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:54:30', '2026-07-25 11:54:30'),
(288, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 2MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:54:49', '2026-07-25 11:54:49'),
(289, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for packing material ID 11.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:55:11', '2026-07-25 11:55:11'),
(290, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 20000 for packing material ID 12.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:55:23', '2026-07-25 11:55:23'),
(291, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for packing material ID 13.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:55:37', '2026-07-25 11:55:37'),
(292, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for packing material ID 10.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:56:45', '2026-07-25 11:56:45'),
(293, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 2MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(294, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 3MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(295, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 4MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(296, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 5MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(297, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of SPACER 6MM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:57:26', '2026-07-25 11:57:26'),
(298, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for packing material ID 17.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 11:59:40', '2026-07-25 11:59:40'),
(299, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 10 units of JACK LEVELLING (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(300, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of WEDGE (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(301, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 100 units of PLIER (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 12:06:29', '2026-07-25 12:06:29'),
(302, 2, 'DISPATCH_CREATED', 'Dispatch DISP-20260725-001 created for party: Madhuram Marble', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-25 12:08:33', '2026-07-25 12:08:33'),
(303, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 594864.85 for raw material ID 12.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:33:20', '2026-07-26 05:33:20'),
(304, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 47727.81 for raw material ID 13.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:34:42', '2026-07-26 05:34:42'),
(305, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 4472.3 for raw material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:38:25', '2026-07-26 05:38:25'),
(306, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -3150.2217 for raw material ID 18.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:40:07', '2026-07-26 05:40:07'),
(307, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -2042.4432 for raw material ID 17.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:41:46', '2026-07-26 05:41:46'),
(308, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 546 for raw material ID 19.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 05:43:48', '2026-07-26 05:43:48'),
(309, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 18073 for packing material ID 1.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 06:54:40', '2026-07-26 06:54:40'),
(310, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 59842 for packing material ID 2.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 06:55:45', '2026-07-26 06:55:45'),
(311, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -4500 for packing material ID 4.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 06:57:30', '2026-07-26 06:57:30'),
(312, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -3273 for packing material ID 3.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 06:58:14', '2026-07-26 06:58:14'),
(313, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -5500 for packing material ID 5.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-26 06:58:46', '2026-07-26 06:58:46'),
(314, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -16250.0023 for raw material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 04:18:52', '2026-07-28 04:18:52'),
(315, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1625 for raw material ID 45.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:42:02', '2026-07-28 04:42:02'),
(316, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 375 for raw material ID 77.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:42:51', '2026-07-28 04:42:51'),
(317, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 175 for raw material ID 67.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:43:16', '2026-07-28 04:43:16'),
(318, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 175 for raw material ID 67.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:51:07', '2026-07-28 04:51:07'),
(319, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1625 for raw material ID 44.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:51:55', '2026-07-28 04:51:55'),
(320, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -10 for raw material ID 67.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:52:36', '2026-07-28 04:52:36'),
(321, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 24860 for raw material ID 12.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 04:53:25', '2026-07-28 04:53:25'),
(322, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -1215 for raw material ID 67.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:55:27', '2026-07-28 04:55:27'),
(323, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -860 for raw material ID 44.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:56:48', '2026-07-28 04:56:48'),
(324, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 70.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:57:17', '2026-07-28 04:57:17'),
(325, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 51.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:57:35', '2026-07-28 04:57:35'),
(326, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -750 for raw material ID 66.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:58:13', '2026-07-28 04:58:13'),
(327, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 53.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:58:54', '2026-07-28 04:58:54'),
(328, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -425 for raw material ID 52.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 04:59:46', '2026-07-28 04:59:46'),
(329, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 50.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:00:27', '2026-07-28 05:00:27'),
(330, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 56.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:02:31', '2026-07-28 05:02:31'),
(331, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -750 for raw material ID 47.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:04:23', '2026-07-28 05:04:23'),
(332, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 71.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:05:15', '2026-07-28 05:05:15'),
(333, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -900 for raw material ID 49.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:05:44', '2026-07-28 05:05:44'),
(334, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -975 for raw material ID 72.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:06:12', '2026-07-28 05:06:12'),
(335, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -575 for raw material ID 68.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:06:53', '2026-07-28 05:06:53'),
(336, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -975 for raw material ID 57.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:07:17', '2026-07-28 05:07:17'),
(337, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 54.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:07:43', '2026-07-28 05:07:43'),
(338, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 62.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:08:02', '2026-07-28 05:08:02'),
(339, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -925 for raw material ID 48.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:08:28', '2026-07-28 05:08:28'),
(340, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -900 for raw material ID 63.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:09:19', '2026-07-28 05:09:19'),
(341, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -900 for raw material ID 65.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:09:36', '2026-07-28 05:09:36'),
(342, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 58.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:09:50', '2026-07-28 05:09:50'),
(343, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -850 for raw material ID 60.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:10:22', '2026-07-28 05:10:22'),
(344, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 73.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:10:51', '2026-07-28 05:10:51'),
(345, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -750 for raw material ID 61.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:11:13', '2026-07-28 05:11:13'),
(346, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -800 for raw material ID 59.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:11:38', '2026-07-28 05:11:38'),
(347, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 55.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:12:01', '2026-07-28 05:12:01'),
(348, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -750 for raw material ID 64.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:12:20', '2026-07-28 05:12:20'),
(349, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -950 for raw material ID 74.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:12:36', '2026-07-28 05:12:36'),
(350, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -625 for raw material ID 46.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:13:19', '2026-07-28 05:13:19'),
(351, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -350 for raw material ID 81.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:20:33', '2026-07-28 05:20:33');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `module`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(352, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 320 for raw material ID 80.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 05:21:43', '2026-07-28 05:21:43'),
(353, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 75 for raw material ID 86.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 05:32:49', '2026-07-28 05:32:49'),
(354, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 4.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 06:32:09', '2026-07-28 06:32:09'),
(355, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for raw material ID 79.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:38:30', '2026-07-28 06:38:30'),
(356, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2000 for raw material ID 82.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:39:45', '2026-07-28 06:39:45'),
(357, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 200 for raw material ID 85.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:40:19', '2026-07-28 06:40:19'),
(358, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -4 for raw material ID 86.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:40:55', '2026-07-28 06:40:55'),
(359, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 4050 for raw material ID 33.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:41:51', '2026-07-28 06:41:51'),
(360, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 230 for raw material ID 34.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:42:40', '2026-07-28 06:42:40'),
(361, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 59850 for raw material ID 40.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:44:50', '2026-07-28 06:44:50'),
(362, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 900 for raw material ID 83.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:46:21', '2026-07-28 06:46:21'),
(363, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2450 for raw material ID 84.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:47:01', '2026-07-28 06:47:01'),
(364, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1550 for packing material ID 21.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:47:37', '2026-07-28 06:47:37'),
(365, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 238 for packing material ID 24.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:48:09', '2026-07-28 06:48:09'),
(366, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -5800 for packing material ID 19.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:48:54', '2026-07-28 06:48:54'),
(367, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 0 for packing material ID 22.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:49:07', '2026-07-28 06:49:07'),
(368, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -50 for packing material ID 22.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:49:25', '2026-07-28 06:49:25'),
(369, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 30 for packing material ID 23.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:49:40', '2026-07-28 06:49:40'),
(370, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -9620 for packing material ID 20.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:50:12', '2026-07-28 06:50:12'),
(371, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 8 for packing material ID 51.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:50:30', '2026-07-28 06:50:30'),
(372, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 40 for packing material ID 46.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:51:15', '2026-07-28 06:51:15'),
(373, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1350 for packing material ID 54.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:52:15', '2026-07-28 06:52:15'),
(374, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -400 for packing material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:52:50', '2026-07-28 06:52:50'),
(375, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -25 for packing material ID 14.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:53:04', '2026-07-28 06:53:04'),
(376, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 200 for packing material ID 15.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:53:46', '2026-07-28 06:53:46'),
(377, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 450 for packing material ID 16.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:54:15', '2026-07-28 06:54:15'),
(378, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 300 for packing material ID 43.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:55:03', '2026-07-28 06:55:03'),
(379, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 450 for packing material ID 44.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 06:56:02', '2026-07-28 06:56:02'),
(380, 2, 'FORMULA_UPDATED', 'Updated formula version #1 for grade ID 1.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(381, 2, 'FORMULA_UPDATED', 'Updated formula version #1 for grade ID 2.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(382, 2, 'FORMULA_UPDATED', 'Updated formula version #1 for grade ID 3.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(383, 2, 'FORMULA_UPDATED', 'Updated formula version #1 for grade ID 4.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(384, 2, 'FORMULA_UPDATED', 'Created new formula version #1 for grade ID 5.', 'Formula', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(385, 2, 'MARKETING_ORDER_CANCELLED', 'Marketing order MKT-20260725-001 cancelled. Reason: Cancelled by user', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:36:16', '2026-07-28 11:36:16'),
(386, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-001 created for party: Roman', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:39:36', '2026-07-28 11:39:36'),
(387, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-002 created for party: National Tiles', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:40:23', '2026-07-28 11:40:23'),
(388, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-003 created for party: Khushi Traders', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:43:03', '2026-07-28 11:43:03'),
(389, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-004 created for party: Advgith Enterprise', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:45:18', '2026-07-28 11:45:18'),
(390, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-005 created for party: Shree Krishna', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:56:23', '2026-07-28 11:56:23'),
(391, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-006 created for party: Aniket', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:57:00', '2026-07-28 11:57:00'),
(392, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-007 created for party: Rajasthan Marble', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 11:59:28', '2026-07-28 11:59:28'),
(393, 2, 'MARKETING_ORDER_CREATED', 'Marketing order MKT-20260728-008 created for party: Vipul Sanitary', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:00:20', '2026-07-28 12:00:20'),
(394, 2, 'MARKETING_ORDER_UPDATED', 'Marketing order MKT-20260728-003 updated', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:02:49', '2026-07-28 12:02:49'),
(395, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-001 approved for party: Roman', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:03:58', '2026-07-28 12:03:58'),
(396, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-006 approved for party: Aniket', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:04:06', '2026-07-28 12:04:06'),
(397, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-008 approved for party: Vipul Sanitary', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:04:12', '2026-07-28 12:04:12'),
(398, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (increase) of 4000 units for product: F-107 (20KG). Reason: opening stock.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:06:38', '2026-07-28 12:06:38'),
(399, 2, 'FINISHED_GOODS_ADJUSTED', 'Manual stock adjustment (increase) of 30 units for product: F-107 (RS-10 Solcon) (20KG). Reason: new.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:08:03', '2026-07-28 12:08:03'),
(400, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-002 approved for party: National Tiles', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:14:32', '2026-07-28 12:14:32'),
(401, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-003 approved for party: Khushi Traders', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:15:06', '2026-07-28 12:15:06'),
(402, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 11050 for packing material ID 55.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:18:33', '2026-07-28 12:18:33'),
(403, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 200 for raw material ID 86.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:19:20', '2026-07-28 12:19:20'),
(404, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 141 for raw material ID 85.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:20:49', '2026-07-28 12:20:49'),
(405, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 190 for packing material ID 39.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:23:31', '2026-07-28 12:23:31'),
(406, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 965 for packing material ID 56.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:25:45', '2026-07-28 12:25:45'),
(407, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -1930 for packing material ID 56.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:26:34', '2026-07-28 12:26:34'),
(408, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 180 for packing material ID 47.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:27:36', '2026-07-28 12:27:36'),
(409, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 25 for packing material ID 61.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:30:19', '2026-07-28 12:30:19'),
(410, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -1900 for packing material ID 61.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:30:55', '2026-07-28 12:30:55'),
(411, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 200 for packing material ID 27.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:31:27', '2026-07-28 12:31:27'),
(412, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 230 for packing material ID 28.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:32:05', '2026-07-28 12:32:05'),
(413, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 225 for packing material ID 52.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:32:51', '2026-07-28 12:32:51'),
(414, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 380 for packing material ID 45.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:33:30', '2026-07-28 12:33:30'),
(415, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1200 for packing material ID 48.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:33:48', '2026-07-28 12:33:48'),
(416, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 150 for packing material ID 50.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:34:06', '2026-07-28 12:34:06'),
(417, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1200 for packing material ID 49.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:34:29', '2026-07-28 12:34:29'),
(418, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 900 for packing material ID 10.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:35:06', '2026-07-28 12:35:06'),
(419, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -1500 for packing material ID 10.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:35:41', '2026-07-28 12:35:41'),
(420, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1500 for packing material ID 11.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:36:24', '2026-07-28 12:36:24'),
(421, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -16200 for packing material ID 12.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:37:23', '2026-07-28 12:37:23'),
(422, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -400 for packing material ID 13.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:37:50', '2026-07-28 12:37:50'),
(423, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 9000 for packing material ID 53.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:38:17', '2026-07-28 12:38:17'),
(424, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 60000 for packing material ID 18.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:38:40', '2026-07-28 12:38:40'),
(425, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 400 for packing material ID 41.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:38:44', '2026-07-28 12:38:44'),
(426, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of Jari Powder - Copper (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(427, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of Jari Powder - Gold (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(428, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of Jari Powder - Red (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(429, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 20 units of Jari Powder - Silver (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(430, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 310 for packing material ID 42.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:39:44', '2026-07-28 12:39:44'),
(431, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1100 for packing material ID 25.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:40:16', '2026-07-28 12:40:16'),
(432, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 280 for packing material ID 26.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:40:46', '2026-07-28 12:40:46'),
(433, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 1475 for packing material ID 17.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:41:28', '2026-07-28 12:41:28'),
(434, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 2950 for packing material ID 17.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:42:13', '2026-07-28 12:42:13'),
(435, 6, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of -5900 for packing material ID 17.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', '2026-07-28 12:42:38', '2026-07-28 12:42:38'),
(436, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 5000 for packing material ID 22.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:52:31', '2026-07-28 12:52:31'),
(437, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 5000 for packing material ID 30.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:52:45', '2026-07-28 12:52:45'),
(438, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 50000 for packing material ID 35.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:54:15', '2026-07-28 12:54:15'),
(439, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 15 units of Grout Admix 200GM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-28 12:54:33', '2026-07-28 12:54:33'),
(440, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 1 units of Grout Admix 200GM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 04:27:25', '2026-07-29 04:27:25'),
(441, 2, 'STOCK_ADJUSTMENT', 'Manual stock adjustment of 5000 for packing material ID 34.', 'Stock', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:00:10', '2026-07-29 14:00:10'),
(442, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 10 units of Tiles Cleaner 5-LTR (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:01:26', '2026-07-29 14:01:26'),
(443, 2, 'EPOXY_COMPONENT_PREPARED', 'Prepared 7 units of VACUUM (Direct Finished Product). Ingredients deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:02:23', '2026-07-29 14:02:23'),
(444, 2, 'MARKETING_ORDER_APPROVED', 'Marketing order MKT-20260728-004 approved for party: Advgith Enterprise', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:02:39', '2026-07-29 14:02:39'),
(445, 2, 'EPOXY_ASSEMBLED', 'Manually assembled 18 units of Epoxy Product: RESIN KIT 0.3KG. Stock deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:04:34', '2026-07-29 14:04:34'),
(446, 2, 'EPOXY_ASSEMBLED', 'Manually assembled 4 units of Epoxy Product: RESIN KIT 1.5KG. Stock deducted.', 'System', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-29 14:04:43', '2026-07-29 14:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `bag_sizes`
--

DROP TABLE IF EXISTS `bag_sizes`;
CREATE TABLE IF NOT EXISTS `bag_sizes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(8,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bag_sizes`
--

INSERT INTO `bag_sizes` (`id`, `name`, `value`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '20KG', 20.00, NULL, 1, '2026-07-12 10:47:23', '2026-07-12 10:47:23'),
(2, '25KG', 25.00, NULL, 1, '2026-07-12 10:47:34', '2026-07-12 10:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-app_settings_cache', 'a:3:{s:16:\"maintenance_mode\";s:7:\"disable\";s:27:\"maintenance_unlock_password\";s:60:\"$2y$12$v53qIAvf/BpnDdaK.Uj7OORUC7d3ZmaoAiMaZEUGBQg0VSuGXMhkW\";s:20:\"maintenance_password\";s:60:\"$2y$12$YVm3y.eS3Z5a1V6AubSUvuoKtVHVypkmze65fMRNiXcXgU1SLJbSC\";}', 2100339252),
('laravel-cache-active_department_ids', 'a:3:{i:0;i:3;i:1;i:1;i:2;i:2;}', 1785336463),
('laravel-cache-firebase_oauth_token', 's:1024:\"ya29.c.c0AZ4bNpZ9PsMfoMPyzxNev5j7CcPqYhAq5KOsJOw0oWzKNntPf1Ph9g58Svb9UzytfyzQoRUWUmo4844fHlSmC0wDYJ6g02F54wFFRW4FCO7d7YUb2D6ILFykaNvsUv3uRPIYVGIy8fbsPFW0IGzqd69LDzsSGoXFoGrF2MID1E73THgw6AYieZUtR6rsGewdrfwtVxdW94cn-tKmvAl18du9253PMRld_WTBgZd_JcLJE1ow9cM6wCpcZfgweeHd8MllI56BOIL41vVzg1HkXx1wnpBpLlnG2DJgfMiI_g_rhfUgtgMpT9SDtSqtvxj-5iT8ZWwqBqkfeh9X-EWl2Yxe7uva6awWn0LujuVPeo_7bkkbs0mRC4xgwgL387D6jbdOObSo-q8ijxl9BaQ1sJmBOStmbYIM015zRIvnr-JZYF05ZYyWdgrzk_Y7YgZjIWgI0i79roa19bRIf6fpz8sWbXhYRliUwX6V_cwSYi_aiftbdqk73wyeiiXjfFoXZf5wdkpZfmU3ja2ravZ1lp2BWM3FB95R5WBd4bsZOIpiMS_99iUpjfM4yJ5re7pV540VeyhIFSxZwv6XoUFqXqo8QmR5IR_-fnuycqMOmv3a1eVaq1Yz_n7ZQqx05XMXxvqtXldx8I1dBRdnfo3p82y3UiQwrx7uwxn4meWs51fs-pnJubqol37S6vO8FdZjka8ZhnwqZpabjinvwVrrVUSZUpmcSljm4zeqMgJObiqpkORWSe7c-BQzr_QSB8s9a8aceX9a6w0x9Jmab3Udus_qzj2Yf3SOs7hSmV-1smwtbJWufri0Y4fWYZdt0QWVv4-B1XU1Rr0RhOfcbWaJoq1pfJBgv6cp4o9lfX7hUr7gcssr2RSF3-qigm04uaFnfI33UmnS-MjBt1kJfvMzWiyj9QXX1BVYdifyvv9UR4Vbc0adOjhBg-c86hBllVXMbWc5cy6lgUqrpaa8YjX85psZpo6fQtRiV6rskpJ_uxyIn2rJsp5stgs\";', 1785337061),
('laravel-cache-user_department_ids_4', 'a:1:{i:0;i:2;}', 1784985042),
('laravel-cache-user_departments_4', 'a:1:{i:0;i:2;}', 1784985096),
('laravel-cache-user_departments_6', 'a:1:{i:0;i:3;}', 1785244503),
('laravel-cache-user_department_ids_6', 'a:1:{i:0;i:3;}', 1785244503);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
CREATE TABLE IF NOT EXISTS `colors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `packing_size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_cement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `colors_code_unique` (`code`),
  KEY `colors_department_id_foreign` (`department_id`),
  KEY `colors_created_by_foreign` (`created_by`),
  KEY `colors_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `department_id`, `name`, `code`, `packing_size`, `default_cement`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'White', 'WHT', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(2, 1, 'White 500GM', 'WHT-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(3, 1, 'Ivory', 'IVY', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(4, 1, 'Ivory 500GM', 'IVY-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(5, 1, 'Black', 'BLK', '1 KG', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(6, 1, 'Black 500GM', 'BLK-500', '500 GM', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(7, 1, 'Gray', 'GRY', '1 KG', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(8, 1, 'Gray 500GM', 'GRY-500', '500 GM', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(9, 1, 'Alpine Blue', 'ABL', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(10, 1, 'Alpine Blue 500GM', 'ABL-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(11, 1, 'Light Blue', 'LBL', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(12, 1, 'Light Blue 500GM', 'LBL-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(13, 1, 'Red', 'RED', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(14, 1, 'Red 500GM', 'RED-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(15, 1, 'Magenta', 'MAG', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(16, 1, 'Magenta 500GM', 'MAG-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(17, 1, 'Terracotta', 'TER', '1 KG', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(18, 1, 'Terracotta 500GM', 'TER-500', '500 GM', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(19, 1, 'Wooden', 'WOD', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(20, 1, 'Wooden 500GM', 'WOD-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(21, 1, 'Bottle Green', 'BGR', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(22, 1, 'Bottle Green 500GM', 'BGR-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(23, 1, 'Pink', 'Pnk', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:34:04'),
(24, 1, 'Pink 500GM', 'Pnk-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:34:27'),
(25, 1, 'Orange', 'ORG', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(26, 1, 'Orange 500GM', 'ORG-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(27, 1, 'Coffee Brown', 'CBR', '1 KG', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(28, 1, 'Coffee Brown 500GM', 'CBR-500', '500 GM', 'Gray Cement', 1, NULL, 2, 2, '2026-07-12 12:25:59', '2026-07-12 12:25:59'),
(29, 1, 'Jesalmer', 'JSL', '1 KG', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:34:53', '2026-07-12 12:35:02'),
(30, 1, 'Jesalmer 500', 'JSL-500', '500 GM', 'White Cement', 1, NULL, 2, 2, '2026-07-12 12:35:20', '2026-07-12 12:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Grout Department', 'GRT', 'Production department for Grout products', 1, '2026-07-12 10:41:53', '2026-07-12 10:46:09'),
(2, 'Tile Adheshive Department', 'Tad', NULL, 1, '2026-07-12 10:45:34', '2026-07-12 10:45:34'),
(3, 'Epoxy Department', 'EPX', NULL, 1, '2026-07-12 10:45:49', '2026-07-13 08:53:02');

-- --------------------------------------------------------

--
-- Table structure for table `dispatches`
--

DROP TABLE IF EXISTS `dispatches`;
CREATE TABLE IF NOT EXISTS `dispatches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dispatch_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dispatch_type` enum('factory_pickup','crossing_delivery') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'factory_pickup',
  `party_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_address` text COLLATE utf8mb4_unicode_ci,
  `google_map_url` text COLLATE utf8mb4_unicode_ci,
  `vehicle_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_arrival_at` datetime DEFAULT NULL,
  `payment_required` tinyint(1) NOT NULL DEFAULT '0',
  `is_released` tinyint(1) NOT NULL DEFAULT '0',
  `released_by` bigint UNSIGNED DEFAULT NULL,
  `released_at` timestamp NULL DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'planned',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `loaded_by` bigint UNSIGNED DEFAULT NULL,
  `loaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dispatches_dispatch_number_unique` (`dispatch_number`),
  KEY `dispatches_released_by_foreign` (`released_by`),
  KEY `dispatches_created_by_foreign` (`created_by`),
  KEY `dispatches_loaded_by_foreign` (`loaded_by`),
  KEY `dispatches_status_dispatch_type_index` (`status`,`dispatch_type`),
  KEY `dispatches_party_name_index` (`party_name`),
  KEY `dispatches_is_released_index` (`is_released`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_items`
--

DROP TABLE IF EXISTS `dispatch_items`;
CREATE TABLE IF NOT EXISTS `dispatch_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dispatch_id` bigint UNSIGNED NOT NULL,
  `marketing_order_id` bigint UNSIGNED DEFAULT NULL,
  `marketing_order_item_id` bigint UNSIGNED DEFAULT NULL,
  `department_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_id` bigint UNSIGNED DEFAULT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_product_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_component_id` bigint UNSIGNED DEFAULT NULL,
  `quantity_bags` int NOT NULL,
  `quantity_kg` decimal(10,2) DEFAULT NULL,
  `packing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatch_items_marketing_order_item_id_foreign` (`marketing_order_item_id`),
  KEY `dispatch_items_grade_id_foreign` (`grade_id`),
  KEY `dispatch_items_color_id_foreign` (`color_id`),
  KEY `dispatch_items_epoxy_product_id_foreign` (`epoxy_product_id`),
  KEY `dispatch_items_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
  KEY `dispatch_items_epoxy_component_id_foreign` (`epoxy_component_id`),
  KEY `dispatch_items_coupon_raw_material_id_foreign` (`coupon_raw_material_id`),
  KEY `dispatch_items_dispatch_id_index` (`dispatch_id`),
  KEY `dispatch_items_marketing_order_id_index` (`marketing_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_loading_logs`
--

DROP TABLE IF EXISTS `dispatch_loading_logs`;
CREATE TABLE IF NOT EXISTS `dispatch_loading_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dispatch_id` bigint UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatch_loading_logs_dispatch_id_foreign` (`dispatch_id`),
  KEY `dispatch_loading_logs_user_id_foreign` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_status_history`
--

DROP TABLE IF EXISTS `dispatch_status_history`;
CREATE TABLE IF NOT EXISTS `dispatch_status_history` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `dispatch_id` bigint UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dispatch_status_history_dispatch_id_foreign` (`dispatch_id`),
  KEY `dispatch_status_history_changed_by_foreign` (`changed_by`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dispatch_status_history`
--

INSERT INTO `dispatch_status_history` (`id`, `dispatch_id`, `status`, `changed_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 'planned', 2, 'Dispatch created by Marketing', '2026-07-25 12:08:32', '2026-07-25 12:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_assemblies`
--

DROP TABLE IF EXISTS `epoxy_assemblies`;
CREATE TABLE IF NOT EXISTS `epoxy_assemblies` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_product_id` bigint UNSIGNED NOT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `formula_snapshot` json NOT NULL,
  `quantity` int NOT NULL,
  `operator_id` bigint UNSIGNED NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoxy_assemblies_epoxy_product_id_foreign` (`epoxy_product_id`),
  KEY `epoxy_assemblies_color_id_foreign` (`color_id`),
  KEY `epoxy_assemblies_operator_id_foreign` (`operator_id`),
  KEY `epoxy_assemblies_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_assemblies`
--

INSERT INTO `epoxy_assemblies` (`id`, `epoxy_product_id`, `color_id`, `epoxy_filler_color_id`, `formula_snapshot`, `quantity`, `operator_id`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL, '[{\"quantity\": 1.8, \"unit_code\": \"KG\", \"material_type\": \"liquid\", \"raw_material_id\": 79, \"is_dynamic_color\": false, \"raw_material_code\": \"EPX-BLT-01\", \"raw_material_name\": \"100 GM HARDNER BOTTLE\"}, {\"quantity\": 3.6, \"unit_code\": \"KG\", \"material_type\": \"liquid\", \"raw_material_id\": 80, \"is_dynamic_color\": false, \"raw_material_code\": \"r-01\", \"raw_material_name\": \"Resin\"}]', 18, 2, NULL, '2026-07-29 14:04:34', '2026-07-29 14:04:34'),
(2, 24, NULL, NULL, '[{\"quantity\": 4, \"unit_code\": \"PCS\", \"material_type\": \"Bottle\", \"raw_material_id\": 79, \"is_dynamic_color\": false, \"raw_material_code\": \"EPX-BLT-01\", \"raw_material_name\": \"100 GM HARDNER BOTTLE\"}]', 4, 2, NULL, '2026-07-29 14:04:43', '2026-07-29 14:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_components`
--

DROP TABLE IF EXISTS `epoxy_components`;
CREATE TABLE IF NOT EXISTS `epoxy_components` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requires_color` tinyint(1) NOT NULL DEFAULT '0',
  `template_material_id` bigint UNSIGNED DEFAULT NULL,
  `bulk_material_id` bigint UNSIGNED DEFAULT NULL,
  `bulk_qty_per_unit` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `packaging_material_id` bigint UNSIGNED DEFAULT NULL,
  `packaging_qty_per_unit` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purpose` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Assembly Component',
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `parent_component_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `epoxy_components_code_unique` (`code`),
  KEY `epoxy_components_template_material_id_foreign` (`template_material_id`),
  KEY `epoxy_components_bulk_material_id_foreign` (`bulk_material_id`),
  KEY `epoxy_components_packaging_material_id_foreign` (`packaging_material_id`),
  KEY `epoxy_components_unit_id_foreign` (`unit_id`),
  KEY `epoxy_components_raw_material_id_foreign` (`raw_material_id`),
  KEY `epoxy_components_parent_component_id_foreign` (`parent_component_id`),
  KEY `epoxy_components_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_components`
--

INSERT INTO `epoxy_components` (`id`, `name`, `code`, `requires_color`, `template_material_id`, `bulk_material_id`, `bulk_qty_per_unit`, `packaging_material_id`, `packaging_qty_per_unit`, `created_at`, `updated_at`, `category`, `purpose`, `unit_id`, `is_active`, `description`, `raw_material_id`, `parent_component_id`, `epoxy_filler_color_id`) VALUES
(1, '700gm Black Filler Pouch', 'EPX-BLK', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 08:34:26', '2026-07-13 08:54:24', 'Pouch', 'Assembly Component', 3, 1, NULL, 45, NULL, 1),
(2, '700gm White Filler Pouch', 'EPX-WHT', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:55:58', '2026-07-13 10:55:58', 'Pouch', 'Assembly Component', 3, 1, NULL, 77, NULL, 26),
(3, '700gm Mocha Filler Pouch', 'EPX-MOC', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 2),
(4, '700gm Sterling Silver Filler Pouch', 'EPX-STS', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 3),
(5, '700gm Hemp Filler Pouch', 'EPX-HEM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 4),
(6, '700gm Marble Beige Filler Pouch', 'EPX-MBG', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 5),
(7, '700gm Sauterne Filler Pouch', 'EPX-SAU', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 6),
(8, '700gm Smoke Grey Filler Pouch', 'EPX-SMG', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 7),
(9, '700gm Silver Shadow Filler Pouch', 'EPX-SSH', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 8),
(10, '700gm Slate Grey Filler Pouch', 'EPX-SLG', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 9),
(11, '700gm Natural Grey Filler Pouch', 'EPX-NGY', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 10),
(12, '700gm Platinum Filler Pouch', 'EPX-PLT', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 11),
(13, '700gm Terracotta Filler Pouch', 'EPX-TER', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 12),
(14, '700gm Satillo Filler Pouch', 'EPX-SAT', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 13),
(15, '700gm Cadmium Red Filler Pouch', 'EPX-CDR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 14),
(16, '700gm Orange Filler Pouch', 'EPX-ORG', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 15),
(17, '700gm Light Grey Filler Pouch', 'EPX-LGY', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 16),
(18, '700gm Inca Gold Filler Pouch', 'EPX-IGD', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 17),
(19, '700gm Blue Filler Pouch', 'EPX-BLU', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 18),
(20, '700gm Ivy Filler Pouch', 'EPX-IVY', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 19),
(21, '700gm Light Green Filler Pouch', 'EPX-LGN', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 20),
(22, '700gm Sky Blue Filler Pouch', 'EPX-SKB', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 21),
(23, '700gm Violet Filler Pouch', 'EPX-VIO', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 22),
(24, '700gm Buff Filler Pouch', 'EPX-BUF', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 23),
(25, '700gm Coffee Brown Filler Pouch', 'EPX-CBR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 24),
(26, '700gm Chocolate Brown Filler Pouch', 'EPX-CHB', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 25),
(27, '700gm Ivory Filler Pouch', 'EPX-IVR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 27),
(28, '700gm Parchment Filler Pouch', 'EPX-PAR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 28),
(29, '700gm Jaisalmer Filler Pouch', 'EPX-JAI', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 29),
(30, '700gm Dusty Rose Filler Pouch', 'EPX-DTR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-13 10:58:25', '2026-07-13 10:58:25', 'Pouch', 'Assembly Component', 3, 1, NULL, NULL, NULL, 30),
(31, 'Jari Powder - Silver', 'EPX-JARI-SLV', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Powder', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(32, 'Jari Powder - Copper', 'EPX-JARI-CPR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Powder', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(33, 'Jari Powder - Gold', 'EPX-JARI-GLD', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Powder', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(34, 'Jari Powder - Red', 'EPX-JARI-RED', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Powder', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(35, 'SB+ 1 KG', 'EPX-SBP-1', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-21 14:07:20', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(36, 'SB+ 5 KG', 'EPX-SBP-5', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-24 04:03:47', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(37, 'SB+ 20 KG', 'EPX-SBP-20', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-24 04:04:01', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(38, 'SB++ 1 KG', 'EPX-SBPP-1', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-24 04:04:30', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(39, 'SB++ 5 KG', 'EPX-SBPP-5', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-24 04:06:18', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(40, 'SB++ 20 KG', 'EPX-SBPP-20', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-24 04:06:42', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(41, 'SK+ 1 LTR', 'EPX-SKP-1', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(42, 'SK+ 5 LTR', 'EPX-SKP-5', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(43, 'SK+ 20 LTR', 'EPX-SKP-20', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-17 16:18:31', '2026-07-17 16:18:31', 'Liquid', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(44, '100 GM HARDNER BOTTLE', 'EPX-BLT-01', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-22 11:33:42', '2026-07-24 04:07:02', 'Bottle', 'Assembly Component', 3, 1, NULL, 79, NULL, NULL),
(45, '200GM RESIN BOTTLE', 'F-011', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-22 11:36:55', '2026-07-24 04:07:10', 'Bottle', 'Assembly Component', 3, 1, NULL, 82, NULL, NULL),
(46, 'CLIP 2MM', '2MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 08:24:43', '2026-07-24 04:01:57', 'Packet', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(47, 'CLIP 3MM', '3MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 09:01:34', '2026-07-24 04:02:23', 'Packet', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(48, 'CLIP 4MM', '4MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 09:01:34', '2026-07-24 04:02:12', 'Packet', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(50, 'SPACER 2MM', 'EPX-SP-2MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 10:55:07', '2026-07-24 04:02:41', 'Packet', 'Direct Finished Product', 1, 1, NULL, NULL, NULL, NULL),
(51, 'SPACER 3MM', 'EPX-SP-3MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 10:55:07', '2026-07-24 04:02:54', 'Packet', 'Direct Finished Product', 1, 1, NULL, NULL, NULL, NULL),
(52, 'SPACER 4MM', 'EPX-SP-4MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 10:55:07', '2026-07-24 04:03:04', 'Packet', 'Direct Finished Product', 1, 1, NULL, NULL, NULL, NULL),
(53, 'SPACER 5MM', 'EPX-SP-5MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-23 10:55:07', '2026-07-24 04:03:19', 'Packet', 'Direct Finished Product', 1, 1, NULL, NULL, NULL, NULL),
(60, 'JACK LEVELLING', 'JL-01', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 05:58:24', '2026-07-24 05:58:24', 'Packet', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(61, 'PLASTIC BOX', 'PB-01', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 06:03:59', '2026-07-24 06:03:59', 'Other', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(62, 'SPACER 6MM', 'EPX-SP-6MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(63, 'CLIP 2MM', 'EPX-CLIP-2MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(64, 'CLIP 3MM', 'EPX-CLIP-3MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(65, 'CLIP 4MM', 'EPX-CLIP-4MM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(66, 'WEDGE', 'EPX-WEDGE', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(67, 'JACK LEVELLING', 'EPX-JL', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(68, 'TROWEL', 'EPX-TROWEL', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(69, 'PLIER', 'EPX-PLIER', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(70, 'VACUUM', 'EPX-VAC', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-24 12:07:24', '2026-07-24 12:07:24', 'Box', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(71, '500 GM HARDNER BOTTLE', 'HRD-500', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-28 05:29:07', '2026-07-28 05:29:07', 'Bottle', 'Assembly Component', 3, 1, NULL, 85, NULL, NULL),
(72, '1 KG RESIN BOTTLE', 'REN-1K', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-28 05:29:47', '2026-07-28 05:29:47', 'Bottle', 'Assembly Component', 3, 1, NULL, 86, NULL, NULL),
(73, 'Grout Admix 200GM', 'EPX-GA-200GM', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-28 12:46:53', '2026-07-28 12:49:05', 'Bottle', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL),
(74, 'Tiles Cleaner 1-LTR', 'EPX-TC-1LTR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-28 12:46:53', '2026-07-28 12:46:53', 'Box', 'Assembly Component', 3, 1, NULL, NULL, NULL, NULL),
(75, 'Tiles Cleaner 5-LTR', 'EPX-TC-5LTR', 0, NULL, NULL, 0.0000, NULL, 0.0000, '2026-07-28 12:46:53', '2026-07-29 14:01:14', 'Bottle', 'Direct Finished Product', 3, 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_component_formulas`
--

DROP TABLE IF EXISTS `epoxy_component_formulas`;
CREATE TABLE IF NOT EXISTS `epoxy_component_formulas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_component_id` bigint UNSIGNED NOT NULL,
  `version` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoxy_component_formulas_epoxy_component_id_foreign` (`epoxy_component_id`),
  KEY `epoxy_component_formulas_created_by_foreign` (`created_by`),
  KEY `epoxy_component_formulas_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_component_formulas`
--

INSERT INTO `epoxy_component_formulas` (`id`, `epoxy_component_id`, `version`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, NULL, 2, 2, '2026-07-13 08:46:58', '2026-07-28 05:51:55'),
(2, 44, 1, 1, NULL, 2, 2, '2026-07-22 11:36:01', '2026-07-22 11:36:10'),
(3, 45, 1, 1, NULL, 2, NULL, '2026-07-22 11:37:34', '2026-07-22 11:37:34'),
(4, 46, 1, 1, NULL, 2, NULL, '2026-07-23 08:30:33', '2026-07-23 08:30:33'),
(5, 47, 1, 1, NULL, 2, NULL, '2026-07-23 09:03:03', '2026-07-23 09:03:03'),
(6, 48, 1, 1, NULL, 2, NULL, '2026-07-23 09:06:03', '2026-07-23 09:06:03'),
(7, 35, 1, 1, 'Formula for SB+ 1 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(8, 37, 1, 1, 'Formula for SB+ 20 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(9, 36, 1, 1, 'Formula for SB+ 5 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(10, 38, 1, 1, 'Formula for SB++ 1 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(11, 40, 1, 1, 'Formula for SB++ 20 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(12, 39, 1, 1, 'Formula for SB++ 5 KG', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(13, 41, 1, 1, 'Formula for SK+ 1 LTR', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(14, 43, 1, 1, 'Formula for SK+ 20 LTR', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(15, 42, 1, 1, 'Formula for SK+ 5 LTR', 1, NULL, '2026-07-23 10:55:26', '2026-07-23 10:55:26'),
(16, 60, 1, 1, NULL, 2, 2, '2026-07-24 07:04:04', '2026-07-24 07:04:13'),
(17, 50, 1, 1, NULL, 2, NULL, '2026-07-24 10:47:42', '2026-07-24 10:47:42'),
(18, 68, 1, 1, NULL, 2, NULL, '2026-07-25 04:21:33', '2026-07-25 04:21:33'),
(19, 51, 1, 1, NULL, 2, NULL, '2026-07-25 11:49:14', '2026-07-25 11:49:14'),
(20, 52, 1, 1, NULL, 2, NULL, '2026-07-25 11:49:53', '2026-07-25 11:49:53'),
(21, 53, 1, 1, NULL, 2, NULL, '2026-07-25 11:50:47', '2026-07-25 11:50:47'),
(22, 62, 1, 1, NULL, 2, NULL, '2026-07-25 11:53:05', '2026-07-25 11:53:05'),
(23, 66, 1, 1, NULL, 2, NULL, '2026-07-25 12:01:06', '2026-07-25 12:01:06'),
(24, 69, 1, 1, NULL, 2, NULL, '2026-07-25 12:06:06', '2026-07-25 12:06:06'),
(25, 72, 1, 1, NULL, 2, NULL, '2026-07-28 05:30:49', '2026-07-28 05:30:49'),
(26, 71, 1, 1, NULL, 2, NULL, '2026-07-28 05:32:06', '2026-07-28 05:32:06'),
(27, 19, 1, 1, NULL, 2, NULL, '2026-07-28 05:52:39', '2026-07-28 05:52:39'),
(28, 24, 1, 1, NULL, 2, NULL, '2026-07-28 05:54:35', '2026-07-28 05:54:35'),
(29, 15, 1, 1, NULL, 2, NULL, '2026-07-28 05:56:14', '2026-07-28 05:56:14'),
(30, 26, 1, 1, NULL, 2, NULL, '2026-07-28 05:57:17', '2026-07-28 05:57:17'),
(31, 25, 1, 1, NULL, 2, NULL, '2026-07-28 05:58:21', '2026-07-28 05:58:21'),
(32, 30, 1, 1, NULL, 2, NULL, '2026-07-28 06:02:06', '2026-07-28 06:02:06'),
(33, 5, 1, 1, NULL, 2, NULL, '2026-07-28 06:02:54', '2026-07-28 06:02:54'),
(34, 18, 1, 1, NULL, 2, NULL, '2026-07-28 06:03:28', '2026-07-28 06:03:28'),
(35, 27, 1, 1, NULL, 2, NULL, '2026-07-28 06:04:05', '2026-07-28 06:04:05'),
(36, 20, 1, 1, NULL, 2, NULL, '2026-07-28 06:04:45', '2026-07-28 06:04:45'),
(37, 29, 1, 1, NULL, 2, NULL, '2026-07-28 06:05:31', '2026-07-28 06:05:31'),
(38, 21, 1, 1, NULL, 2, NULL, '2026-07-28 06:06:12', '2026-07-28 06:06:12'),
(39, 17, 1, 1, NULL, 2, NULL, '2026-07-28 06:07:01', '2026-07-28 06:07:01'),
(40, 6, 1, 1, NULL, 2, NULL, '2026-07-28 06:07:31', '2026-07-28 06:07:31'),
(41, 3, 1, 1, NULL, 2, NULL, '2026-07-28 06:08:10', '2026-07-28 06:08:10'),
(42, 11, 1, 1, NULL, 2, NULL, '2026-07-28 06:08:44', '2026-07-28 06:08:44'),
(43, 16, 1, 1, NULL, 2, NULL, '2026-07-28 06:09:41', '2026-07-28 06:09:41'),
(44, 28, 1, 1, NULL, 2, NULL, '2026-07-28 06:10:23', '2026-07-28 06:10:23'),
(45, 12, 1, 1, NULL, 2, NULL, '2026-07-28 06:11:46', '2026-07-28 06:11:46'),
(46, 14, 1, 1, NULL, 2, NULL, '2026-07-28 06:13:34', '2026-07-28 06:13:34'),
(47, 7, 1, 1, NULL, 2, NULL, '2026-07-28 06:14:17', '2026-07-28 06:14:17'),
(48, 9, 1, 1, NULL, 2, NULL, '2026-07-28 06:15:45', '2026-07-28 06:15:45'),
(49, 22, 1, 1, NULL, 2, NULL, '2026-07-28 06:16:29', '2026-07-28 06:16:29'),
(50, 10, 1, 1, NULL, 2, NULL, '2026-07-28 06:17:15', '2026-07-28 06:17:15'),
(51, 8, 1, 1, NULL, 2, NULL, '2026-07-28 06:18:47', '2026-07-28 06:18:47'),
(52, 4, 1, 1, NULL, 2, NULL, '2026-07-28 06:19:48', '2026-07-28 06:19:48'),
(53, 13, 1, 1, NULL, 2, NULL, '2026-07-28 06:20:39', '2026-07-28 06:20:39'),
(56, 2, 1, 1, NULL, 2, NULL, '2026-07-28 06:24:35', '2026-07-28 06:24:35'),
(55, 23, 1, 1, NULL, 2, NULL, '2026-07-28 06:23:22', '2026-07-28 06:23:22'),
(57, 70, 1, 1, NULL, 2, NULL, '2026-07-28 12:18:54', '2026-07-28 12:18:54'),
(58, 32, 1, 1, NULL, 2, NULL, '2026-07-28 12:30:33', '2026-07-28 12:30:33'),
(59, 33, 1, 1, NULL, 2, NULL, '2026-07-28 12:31:18', '2026-07-28 12:31:18'),
(60, 34, 1, 1, NULL, 2, NULL, '2026-07-28 12:31:50', '2026-07-28 12:31:50'),
(61, 31, 1, 1, NULL, 2, NULL, '2026-07-28 12:32:48', '2026-07-28 12:32:48'),
(62, 73, 1, 1, NULL, 2, NULL, '2026-07-28 12:50:47', '2026-07-28 12:50:47'),
(63, 75, 1, 1, NULL, 2, NULL, '2026-07-29 13:59:23', '2026-07-29 13:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_component_formula_items`
--

DROP TABLE IF EXISTS `epoxy_component_formula_items`;
CREATE TABLE IF NOT EXISTS `epoxy_component_formula_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_component_formula_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `packing_material_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoxy_component_formula_items_epoxy_component_formula_id_foreign` (`epoxy_component_formula_id`),
  KEY `epoxy_component_formula_items_raw_material_id_foreign` (`raw_material_id`),
  KEY `epoxy_component_formula_items_unit_id_foreign` (`unit_id`),
  KEY `epoxy_component_formula_items_packing_material_id_foreign` (`packing_material_id`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_component_formula_items`
--

INSERT INTO `epoxy_component_formula_items` (`id`, `epoxy_component_formula_id`, `raw_material_id`, `packing_material_id`, `quantity`, `unit_id`, `created_at`, `updated_at`) VALUES
(64, 1, NULL, 9, 1.0000, 3, '2026-07-28 05:51:55', '2026-07-28 05:51:55'),
(63, 1, 44, NULL, 0.7000, 1, '2026-07-28 05:51:55', '2026-07-28 05:51:55'),
(12, 2, 35, NULL, 1.0000, 3, '2026-07-22 11:38:01', '2026-07-22 11:38:01'),
(11, 2, 81, NULL, 0.1000, 1, '2026-07-22 11:38:01', '2026-07-22 11:38:01'),
(7, 3, 80, NULL, 0.2000, 1, '2026-07-22 11:37:34', '2026-07-22 11:37:34'),
(8, 3, 36, NULL, 1.0000, 3, '2026-07-22 11:37:34', '2026-07-22 11:37:34'),
(13, 4, NULL, 46, 1.0000, 3, '2026-07-23 08:30:33', '2026-07-23 08:30:33'),
(14, 4, NULL, 14, 25.0000, 3, '2026-07-23 08:30:33', '2026-07-23 08:30:33'),
(15, 5, NULL, 46, 1.0000, 3, '2026-07-23 09:03:03', '2026-07-23 09:03:03'),
(16, 5, NULL, 15, 25.0000, 3, '2026-07-23 09:03:03', '2026-07-23 09:03:03'),
(17, 6, NULL, 46, 1.0000, 3, '2026-07-23 09:06:03', '2026-07-23 09:06:03'),
(18, 6, NULL, 16, 25.0000, 3, '2026-07-23 09:06:03', '2026-07-23 09:06:03'),
(28, 7, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(29, 8, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(30, 9, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(31, 10, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(32, 11, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(33, 12, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(34, 13, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(35, 14, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(36, 15, 80, NULL, 1.0000, 1, '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(42, 16, NULL, 58, 50.0000, 3, '2026-07-24 07:04:13', '2026-07-24 07:04:13'),
(41, 16, NULL, 56, 1.0000, 3, '2026-07-24 07:04:13', '2026-07-24 07:04:13'),
(40, 16, NULL, 57, 1.0000, 3, '2026-07-24 07:04:13', '2026-07-24 07:04:13'),
(43, 17, NULL, 10, 50.0000, 3, '2026-07-24 10:47:42', '2026-07-24 10:47:42'),
(44, 17, NULL, 46, 1.0000, 1, '2026-07-24 10:47:42', '2026-07-24 10:47:42'),
(45, 18, NULL, 59, 1.0000, 3, '2026-07-25 04:21:33', '2026-07-25 04:21:33'),
(46, 19, NULL, 11, 50.0000, 3, '2026-07-25 11:49:14', '2026-07-25 11:49:14'),
(47, 19, NULL, 46, 1.0000, 1, '2026-07-25 11:49:14', '2026-07-25 11:49:14'),
(48, 20, NULL, 12, 50.0000, 3, '2026-07-25 11:49:53', '2026-07-25 11:49:53'),
(49, 20, NULL, 46, 1.0000, 1, '2026-07-25 11:49:53', '2026-07-25 11:49:53'),
(50, 21, NULL, 13, 50.0000, 3, '2026-07-25 11:50:47', '2026-07-25 11:50:47'),
(51, 21, NULL, 46, 1.0000, 3, '2026-07-25 11:50:47', '2026-07-25 11:50:47'),
(52, 22, NULL, 60, 50.0000, 3, '2026-07-25 11:53:05', '2026-07-25 11:53:05'),
(53, 22, NULL, 46, 1.0000, 3, '2026-07-25 11:53:05', '2026-07-25 11:53:05'),
(54, 23, NULL, 17, 25.0000, 3, '2026-07-25 12:01:06', '2026-07-25 12:01:06'),
(55, 23, NULL, 46, 1.0000, 3, '2026-07-25 12:01:06', '2026-07-25 12:01:06'),
(56, 24, NULL, 61, 1.0000, 3, '2026-07-25 12:06:06', '2026-07-25 12:06:06'),
(57, 25, 80, NULL, 1.0000, 1, '2026-07-28 05:30:49', '2026-07-28 05:30:49'),
(58, 25, NULL, 24, 1.0000, 3, '2026-07-28 05:30:49', '2026-07-28 05:30:49'),
(59, 25, NULL, 32, 1.0000, 1, '2026-07-28 05:30:49', '2026-07-28 05:30:49'),
(60, 26, 81, NULL, 0.5000, 1, '2026-07-28 05:32:06', '2026-07-28 05:32:06'),
(61, 26, NULL, 23, 1.0000, 3, '2026-07-28 05:32:06', '2026-07-28 05:32:06'),
(62, 26, NULL, 31, 1.0000, 3, '2026-07-28 05:32:06', '2026-07-28 05:32:06'),
(65, 27, 70, NULL, 0.7000, 1, '2026-07-28 05:52:39', '2026-07-28 05:52:39'),
(66, 27, NULL, 9, 1.0000, 3, '2026-07-28 05:52:39', '2026-07-28 05:52:39'),
(67, 28, 51, NULL, 0.7000, 1, '2026-07-28 05:54:35', '2026-07-28 05:54:35'),
(68, 28, NULL, 9, 1.0000, 3, '2026-07-28 05:54:35', '2026-07-28 05:54:35'),
(69, 29, 66, NULL, 0.7000, 1, '2026-07-28 05:56:14', '2026-07-28 05:56:14'),
(70, 29, NULL, 9, 1.0000, 3, '2026-07-28 05:56:14', '2026-07-28 05:56:14'),
(71, 30, 53, NULL, 0.7000, 1, '2026-07-28 05:57:17', '2026-07-28 05:57:17'),
(72, 30, NULL, 9, 1.0000, 3, '2026-07-28 05:57:17', '2026-07-28 05:57:17'),
(73, 31, 52, NULL, 0.7000, 1, '2026-07-28 05:58:21', '2026-07-28 05:58:21'),
(74, 31, NULL, 9, 1.0000, 3, '2026-07-28 05:58:21', '2026-07-28 05:58:21'),
(75, 32, 50, NULL, 0.7000, 1, '2026-07-28 06:02:06', '2026-07-28 06:02:06'),
(76, 32, NULL, 9, 1.0000, 3, '2026-07-28 06:02:06', '2026-07-28 06:02:06'),
(77, 33, 56, NULL, 0.7000, 1, '2026-07-28 06:02:54', '2026-07-28 06:02:54'),
(78, 33, NULL, 9, 1.0000, 3, '2026-07-28 06:02:54', '2026-07-28 06:02:54'),
(79, 34, 69, NULL, 0.7000, 1, '2026-07-28 06:03:28', '2026-07-28 06:03:28'),
(80, 34, NULL, 9, 1.0000, 3, '2026-07-28 06:03:28', '2026-07-28 06:03:28'),
(81, 35, 47, NULL, 0.7000, 1, '2026-07-28 06:04:05', '2026-07-28 06:04:05'),
(82, 35, NULL, 9, 1.0000, 3, '2026-07-28 06:04:05', '2026-07-28 06:04:05'),
(83, 36, 71, NULL, 0.7000, 1, '2026-07-28 06:04:45', '2026-07-28 06:04:45'),
(84, 36, NULL, 9, 1.0000, 3, '2026-07-28 06:04:45', '2026-07-28 06:04:45'),
(85, 37, 49, NULL, 0.7000, 1, '2026-07-28 06:05:31', '2026-07-28 06:05:31'),
(86, 37, NULL, 9, 1.0000, 3, '2026-07-28 06:05:31', '2026-07-28 06:05:31'),
(87, 38, 72, NULL, 0.7000, 1, '2026-07-28 06:06:12', '2026-07-28 06:06:12'),
(88, 38, NULL, 9, 1.0000, 3, '2026-07-28 06:06:12', '2026-07-28 06:06:12'),
(89, 39, 68, NULL, 0.7000, 1, '2026-07-28 06:07:01', '2026-07-28 06:07:01'),
(90, 39, NULL, 9, 1.0000, 3, '2026-07-28 06:07:01', '2026-07-28 06:07:01'),
(91, 40, 57, NULL, 0.7000, 1, '2026-07-28 06:07:31', '2026-07-28 06:07:31'),
(92, 40, NULL, 9, 1.0000, 3, '2026-07-28 06:07:31', '2026-07-28 06:07:31'),
(93, 41, 54, NULL, 0.7000, 1, '2026-07-28 06:08:10', '2026-07-28 06:08:10'),
(94, 41, NULL, 9, 1.0000, 3, '2026-07-28 06:08:10', '2026-07-28 06:08:10'),
(95, 42, 62, NULL, 0.7000, 1, '2026-07-28 06:08:44', '2026-07-28 06:08:44'),
(96, 42, NULL, 9, 1.0000, 3, '2026-07-28 06:08:44', '2026-07-28 06:08:44'),
(97, 43, 67, NULL, 0.7000, 1, '2026-07-28 06:09:41', '2026-07-28 06:09:41'),
(98, 43, NULL, 9, 1.0000, 3, '2026-07-28 06:09:41', '2026-07-28 06:09:41'),
(99, 44, 48, NULL, 0.7000, 1, '2026-07-28 06:10:23', '2026-07-28 06:10:23'),
(100, 44, NULL, 9, 1.0000, 3, '2026-07-28 06:10:23', '2026-07-28 06:10:23'),
(101, 45, 63, NULL, 0.7000, 1, '2026-07-28 06:11:46', '2026-07-28 06:11:46'),
(102, 45, NULL, 9, 1.0000, 3, '2026-07-28 06:11:46', '2026-07-28 06:11:46'),
(103, 46, 65, NULL, 0.7000, 1, '2026-07-28 06:13:34', '2026-07-28 06:13:34'),
(104, 46, NULL, 9, 1.0000, 3, '2026-07-28 06:13:34', '2026-07-28 06:13:34'),
(105, 47, 58, NULL, 0.7000, 1, '2026-07-28 06:14:17', '2026-07-28 06:14:17'),
(106, 47, NULL, 9, 1.0000, 3, '2026-07-28 06:14:17', '2026-07-28 06:14:17'),
(107, 48, 60, NULL, 0.7000, 1, '2026-07-28 06:15:45', '2026-07-28 06:15:45'),
(108, 48, NULL, 9, 1.0000, 3, '2026-07-28 06:15:45', '2026-07-28 06:15:45'),
(109, 49, 73, NULL, 0.7000, 1, '2026-07-28 06:16:29', '2026-07-28 06:16:29'),
(110, 49, NULL, 9, 1.0000, 3, '2026-07-28 06:16:29', '2026-07-28 06:16:29'),
(111, 50, 61, NULL, 0.7000, 1, '2026-07-28 06:17:15', '2026-07-28 06:17:15'),
(112, 50, NULL, 9, 1.0000, 3, '2026-07-28 06:17:15', '2026-07-28 06:17:15'),
(113, 51, 59, NULL, 0.7000, 1, '2026-07-28 06:18:47', '2026-07-28 06:18:47'),
(114, 51, NULL, 9, 1.0000, 3, '2026-07-28 06:18:47', '2026-07-28 06:18:47'),
(115, 52, 55, NULL, 0.7000, 1, '2026-07-28 06:19:48', '2026-07-28 06:19:48'),
(116, 52, NULL, 9, 1.0000, 3, '2026-07-28 06:19:48', '2026-07-28 06:19:48'),
(117, 53, 64, NULL, 0.7000, 1, '2026-07-28 06:20:39', '2026-07-28 06:20:39'),
(118, 53, NULL, 9, 1.0000, 3, '2026-07-28 06:20:39', '2026-07-28 06:20:39'),
(119, 54, 74, NULL, 0.7000, 1, '2026-07-28 06:21:59', '2026-07-28 06:21:59'),
(120, 54, NULL, 9, 1.0000, 3, '2026-07-28 06:21:59', '2026-07-28 06:21:59'),
(121, 55, 74, NULL, 0.7000, 1, '2026-07-28 06:23:22', '2026-07-28 06:23:22'),
(122, 55, NULL, 9, 1.0000, 3, '2026-07-28 06:23:22', '2026-07-28 06:23:22'),
(123, 56, 46, NULL, 0.7000, 1, '2026-07-28 06:24:35', '2026-07-28 06:24:35'),
(124, 56, NULL, 9, 1.0000, 3, '2026-07-28 06:24:35', '2026-07-28 06:24:35'),
(125, 57, NULL, 62, 1.0000, 3, '2026-07-28 12:18:54', '2026-07-28 12:18:54'),
(126, 58, 90, NULL, 1.0000, 1, '2026-07-28 12:30:33', '2026-07-28 12:30:33'),
(127, 58, NULL, 18, 20.0000, 3, '2026-07-28 12:30:33', '2026-07-28 12:30:33'),
(128, 58, NULL, 47, 1.0000, 3, '2026-07-28 12:30:33', '2026-07-28 12:30:33'),
(129, 59, 88, NULL, 1.0000, 1, '2026-07-28 12:31:18', '2026-07-28 12:31:18'),
(130, 59, NULL, 47, 1.0000, 3, '2026-07-28 12:31:18', '2026-07-28 12:31:18'),
(131, 59, NULL, 18, 20.0000, 3, '2026-07-28 12:31:18', '2026-07-28 12:31:18'),
(132, 60, 89, NULL, 1.0000, 1, '2026-07-28 12:31:50', '2026-07-28 12:31:50'),
(133, 60, NULL, 47, 1.0000, 3, '2026-07-28 12:31:50', '2026-07-28 12:31:50'),
(134, 60, NULL, 18, 20.0000, 3, '2026-07-28 12:31:50', '2026-07-28 12:31:50'),
(135, 61, 87, NULL, 1.0000, 1, '2026-07-28 12:32:48', '2026-07-28 12:32:48'),
(136, 61, NULL, 47, 1.0000, 3, '2026-07-28 12:32:48', '2026-07-28 12:32:48'),
(137, 61, NULL, 18, 20.0000, 3, '2026-07-28 12:32:48', '2026-07-28 12:32:48'),
(138, 62, NULL, 22, 35.0000, 3, '2026-07-28 12:50:47', '2026-07-28 12:50:47'),
(139, 62, NULL, 35, 35.0000, 3, '2026-07-28 12:50:47', '2026-07-28 12:50:47'),
(140, 62, NULL, 39, 1.0000, 3, '2026-07-28 12:50:47', '2026-07-28 12:50:47'),
(141, 63, NULL, 42, 1.0000, 3, '2026-07-29 13:59:23', '2026-07-29 13:59:23'),
(142, 63, NULL, 34, 4.0000, 3, '2026-07-29 13:59:23', '2026-07-29 13:59:23');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_component_mappings`
--

DROP TABLE IF EXISTS `epoxy_component_mappings`;
CREATE TABLE IF NOT EXISTS `epoxy_component_mappings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_component_id` bigint UNSIGNED NOT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comp_color_mapping_unique` (`epoxy_component_id`,`epoxy_filler_color_id`),
  KEY `epoxy_component_mappings_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
  KEY `epoxy_component_mappings_raw_material_id_foreign` (`raw_material_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_component_preparations`
--

DROP TABLE IF EXISTS `epoxy_component_preparations`;
CREATE TABLE IF NOT EXISTS `epoxy_component_preparations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_component_id` bigint UNSIGNED NOT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int NOT NULL,
  `operator_id` bigint UNSIGNED NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoxy_component_preparations_epoxy_component_id_foreign` (`epoxy_component_id`),
  KEY `epoxy_component_preparations_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
  KEY `epoxy_component_preparations_operator_id_foreign` (`operator_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_component_preparations`
--

INSERT INTO `epoxy_component_preparations` (`id`, `epoxy_component_id`, `epoxy_filler_color_id`, `quantity`, `operator_id`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 2, NULL, '2026-07-13 08:55:15', '2026-07-13 08:55:15'),
(2, 44, NULL, 500, 2, NULL, '2026-07-22 11:38:20', '2026-07-22 11:38:20'),
(3, 45, NULL, 100, 2, NULL, '2026-07-22 11:41:05', '2026-07-22 11:41:05'),
(4, 1, 1, 100, 2, NULL, '2026-07-22 11:47:04', '2026-07-22 11:47:04'),
(5, 44, NULL, 100, 2, NULL, '2026-07-22 11:48:11', '2026-07-22 11:48:11'),
(6, 46, NULL, 10, 2, NULL, '2026-07-23 08:33:21', '2026-07-23 08:33:21'),
(7, 46, NULL, 20, 2, NULL, '2026-07-24 10:45:56', '2026-07-24 10:45:56'),
(8, 50, NULL, 10, 2, NULL, '2026-07-24 10:48:10', '2026-07-24 10:48:10'),
(9, 68, NULL, 50, 2, NULL, '2026-07-25 04:21:56', '2026-07-25 04:21:56'),
(10, 50, NULL, 20, 2, NULL, '2026-07-25 11:54:49', '2026-07-25 11:54:49'),
(11, 50, NULL, 20, 2, NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(12, 51, NULL, 20, 2, NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(13, 52, NULL, 20, 2, NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(14, 53, NULL, 20, 2, NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(15, 62, NULL, 20, 2, NULL, '2026-07-25 11:57:26', '2026-07-25 11:57:26'),
(16, 60, NULL, 10, 2, NULL, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(17, 66, NULL, 20, 2, NULL, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(18, 69, NULL, 100, 2, NULL, '2026-07-25 12:06:29', '2026-07-25 12:06:29'),
(19, 32, NULL, 20, 2, NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(20, 33, NULL, 20, 2, NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(21, 34, NULL, 20, 2, NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(22, 31, NULL, 20, 2, NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(23, 73, NULL, 15, 2, NULL, '2026-07-28 12:54:33', '2026-07-28 12:54:33'),
(24, 73, NULL, 1, 2, NULL, '2026-07-29 04:27:25', '2026-07-29 04:27:25'),
(25, 75, NULL, 10, 2, NULL, '2026-07-29 14:01:26', '2026-07-29 14:01:26'),
(26, 70, NULL, 7, 2, NULL, '2026-07-29 14:02:23', '2026-07-29 14:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_filler_colors`
--

DROP TABLE IF EXISTS `epoxy_filler_colors`;
CREATE TABLE IF NOT EXISTS `epoxy_filler_colors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `epoxy_filler_colors_code_unique` (`code`),
  KEY `epoxy_filler_colors_created_by_foreign` (`created_by`),
  KEY `epoxy_filler_colors_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_filler_colors`
--

INSERT INTO `epoxy_filler_colors` (`id`, `name`, `code`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'White', '101', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(2, 'Ivory', '102', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(3, 'Parchment', '103', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(4, 'Jaisalmer', '104', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(5, 'Dusty Rose', '105', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(6, 'Buff', '106', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(7, 'Coffee Brown', '107', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(8, 'Chocolate Brown', '108', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(9, 'Black', '109', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(10, 'Mocha', '110', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(11, 'Sterling Silver', '111', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(12, 'Hemp', '112', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(13, 'Marble Beige', '113', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(14, 'Sauterne', '114', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(15, 'Smoke Grey', '115', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(16, 'Silver Shadow', '116', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(17, 'Slate Grey', '117', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(18, 'Natural Grey', '118', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(19, 'Platinum', '119', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(20, 'Terracotta', '120', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(21, 'Saltillo', '121', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(22, 'Cadmium Red', '122', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(23, 'Orange', '123', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(24, 'Light Grey', '124', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(25, 'Inca Gold', '125', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(26, 'Blue', '126', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(27, 'Ivy', '127', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(28, 'Light Green', '128', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(29, 'Sky Blue', '129', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35'),
(30, 'Violet', '130', 1, NULL, 2, 2, '2026-07-15 08:36:35', '2026-07-15 08:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_formulas`
--

DROP TABLE IF EXISTS `epoxy_formulas`;
CREATE TABLE IF NOT EXISTS `epoxy_formulas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_product_id` bigint UNSIGNED NOT NULL,
  `version` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `epoxy_formulas_epoxy_product_id_version_unique` (`epoxy_product_id`,`version`),
  KEY `epoxy_formulas_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_formulas`
--

INSERT INTO `epoxy_formulas` (`id`, `epoxy_product_id`, `version`, `is_active`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 'Resin Kit Formula (Resin + Hardener, No Filler Pouch)', 1, '2026-07-23 10:55:07', '2026-07-23 10:55:26'),
(4, 24, 1, 1, NULL, 2, '2026-07-29 14:04:02', '2026-07-29 14:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_formula_items`
--

DROP TABLE IF EXISTS `epoxy_formula_items`;
CREATE TABLE IF NOT EXISTS `epoxy_formula_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `epoxy_formula_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(10,4) NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `is_dynamic_color` tinyint(1) NOT NULL DEFAULT '0',
  `material_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `epoxy_formula_items_epoxy_formula_id_foreign` (`epoxy_formula_id`),
  KEY `epoxy_formula_items_raw_material_id_foreign` (`raw_material_id`),
  KEY `epoxy_formula_items_unit_id_foreign` (`unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_formula_items`
--

INSERT INTO `epoxy_formula_items` (`id`, `epoxy_formula_id`, `raw_material_id`, `quantity`, `unit_id`, `is_dynamic_color`, `material_type`, `created_at`, `updated_at`) VALUES
(10, 1, 79, 0.1000, 1, 0, 'liquid', '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(9, 1, 80, 0.2000, 1, 0, 'liquid', '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(11, 2, 80, 1.0000, 1, 0, 'liquid', '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(12, 3, 80, 0.2000, 1, 0, 'liquid', '2026-07-23 11:03:21', '2026-07-23 11:03:21'),
(13, 4, 79, 1.0000, 3, 0, 'Bottle', '2026-07-29 14:04:02', '2026-07-29 14:04:02');

-- --------------------------------------------------------

--
-- Table structure for table `epoxy_products`
--

DROP TABLE IF EXISTS `epoxy_products`;
CREATE TABLE IF NOT EXISTS `epoxy_products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requires_color` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `epoxy_products_code_unique` (`code`),
  KEY `epoxy_products_created_by_foreign` (`created_by`),
  KEY `epoxy_products_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `epoxy_products`
--

INSERT INTO `epoxy_products` (`id`, `name`, `code`, `requires_color`, `is_active`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1KG BUCKET', '1B', 1, 1, NULL, 2, NULL, '2026-07-13 10:34:09', '2026-07-13 10:34:09'),
(2, '5KG BUCKET', '5B', 1, 1, NULL, 2, NULL, '2026-07-13 10:34:28', '2026-07-13 10:34:28'),
(3, 'RESIN KIT 0.3KG', 'RK', 0, 1, NULL, 2, 2, '2026-07-13 10:34:37', '2026-07-24 11:25:23'),
(24, 'RESIN KIT 1.5KG', 'RK1', 0, 1, NULL, 2, NULL, '2026-07-29 14:03:20', '2026-07-29 14:03:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_failed_at_index` (`failed_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finished_goods`
--

DROP TABLE IF EXISTS `finished_goods`;
CREATE TABLE IF NOT EXISTS `finished_goods` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` bigint UNSIGNED NOT NULL,
  `grade_id` bigint UNSIGNED DEFAULT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_product_id` bigint UNSIGNED DEFAULT NULL,
  `packing` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_bags` int NOT NULL DEFAULT '0',
  `available_weight` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `minimum_stock` int NOT NULL DEFAULT '20',
  `last_production_date` datetime DEFAULT NULL,
  `status` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remarks` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `epoxy_component_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_raw_material_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finished_goods_department_id_foreign` (`department_id`),
  KEY `finished_goods_color_id_foreign` (`color_id`),
  KEY `finished_goods_epoxy_product_id_foreign` (`epoxy_product_id`),
  KEY `finished_goods_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
  KEY `finished_goods_epoxy_component_id_foreign` (`epoxy_component_id`),
  KEY `finished_goods_coupon_raw_material_id_foreign` (`coupon_raw_material_id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `finished_goods`
--

INSERT INTO `finished_goods` (`id`, `department_id`, `grade_id`, `color_id`, `epoxy_filler_color_id`, `epoxy_product_id`, `packing`, `available_bags`, `available_weight`, `minimum_stock`, `last_production_date`, `status`, `remarks`, `created_at`, `updated_at`, `epoxy_component_id`, `coupon_raw_material_id`) VALUES
(1, 2, 1, NULL, NULL, NULL, '20KG', 675, 13500.0000, 20, '2026-07-22 16:54:51', 'active', 'Dispatched to Supreme via Dispatch #DISP-20260724-001', '2026-07-12 22:41:06', '2026-07-24 04:52:40', NULL, NULL),
(2, 2, 2, NULL, NULL, NULL, '20KG', 100, 2000.0000, 20, '2026-07-13 09:58:46', 'active', 'Auto-deducted for order MKT-20260713-005, Party: jatin bhai', '2026-07-12 22:58:46', '2026-07-28 12:08:03', NULL, 7),
(3, 1, NULL, 1, NULL, NULL, '1 KG', 150, 3750.0000, 20, '2026-07-14 08:32:37', 'active', NULL, '2026-07-13 05:34:08', '2026-07-13 21:32:37', NULL, NULL),
(4, 1, NULL, 3, NULL, NULL, '1 KG', 152, 3800.0000, 20, '2026-07-13 23:38:35', 'active', NULL, '2026-07-13 11:16:53', '2026-07-13 12:38:35', NULL, NULL),
(5, 2, 2, NULL, NULL, NULL, '20KG', 4420, 88400.0000, 20, '2026-07-14 12:27:08', 'active', NULL, '2026-07-14 01:24:51', '2026-07-28 12:06:38', NULL, NULL),
(6, 2, 2, NULL, NULL, NULL, '20KG', 322, 6440.0000, 20, '2026-07-14 12:26:45', 'active', NULL, '2026-07-14 01:25:58', '2026-07-14 01:26:45', NULL, 10),
(7, 2, 3, NULL, NULL, NULL, '20KG', 227, 4540.0000, 20, '2026-07-14 12:32:07', 'active', NULL, '2026-07-14 01:31:47', '2026-07-14 01:32:07', NULL, NULL),
(42, 3, NULL, NULL, 1, 1, '1KG', 90, 90.0000, 10, NULL, 'active', 'Dispatched to jatin bhai via Dispatch #DISP-20260721-001', '2026-07-17 11:54:00', '2026-07-21 11:08:12', NULL, NULL),
(43, 3, NULL, NULL, 1, 2, '5KG', 95, 475.0000, 10, NULL, 'active', 'Dispatched to jatin bhai via Dispatch #DISP-20260721-001', '2026-07-17 11:54:00', '2026-07-21 11:08:12', NULL, NULL),
(44, 3, NULL, NULL, 2, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(45, 3, NULL, NULL, 2, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(46, 3, NULL, NULL, 3, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(47, 3, NULL, NULL, 3, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(48, 3, NULL, NULL, 4, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(49, 3, NULL, NULL, 4, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(50, 3, NULL, NULL, 5, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(51, 3, NULL, NULL, 5, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(52, 3, NULL, NULL, 6, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(53, 3, NULL, NULL, 6, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(54, 3, NULL, NULL, 7, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(55, 3, NULL, NULL, 7, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(56, 3, NULL, NULL, 8, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(57, 3, NULL, NULL, 8, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(58, 3, NULL, NULL, 9, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(59, 3, NULL, NULL, 9, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(60, 3, NULL, NULL, 10, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(61, 3, NULL, NULL, 10, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(62, 3, NULL, NULL, 11, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(63, 3, NULL, NULL, 11, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(64, 3, NULL, NULL, 12, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(65, 3, NULL, NULL, 12, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(66, 3, NULL, NULL, 13, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(67, 3, NULL, NULL, 13, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(68, 3, NULL, NULL, 14, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(69, 3, NULL, NULL, 14, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(70, 3, NULL, NULL, 15, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(71, 3, NULL, NULL, 15, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(72, 3, NULL, NULL, 16, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(73, 3, NULL, NULL, 16, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(74, 3, NULL, NULL, 17, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(75, 3, NULL, NULL, 17, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(76, 3, NULL, NULL, 18, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(77, 3, NULL, NULL, 18, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(78, 3, NULL, NULL, 19, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(79, 3, NULL, NULL, 19, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(80, 3, NULL, NULL, 20, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(81, 3, NULL, NULL, 20, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(82, 3, NULL, NULL, 21, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(83, 3, NULL, NULL, 21, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(84, 3, NULL, NULL, 22, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(85, 3, NULL, NULL, 22, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(86, 3, NULL, NULL, 23, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(87, 3, NULL, NULL, 23, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(88, 3, NULL, NULL, 24, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(89, 3, NULL, NULL, 24, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(90, 3, NULL, NULL, 25, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(91, 3, NULL, NULL, 25, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(92, 3, NULL, NULL, 26, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(93, 3, NULL, NULL, 26, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(94, 3, NULL, NULL, 27, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(95, 3, NULL, NULL, 27, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(96, 3, NULL, NULL, 28, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(97, 3, NULL, NULL, 28, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(98, 3, NULL, NULL, 29, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(99, 3, NULL, NULL, 29, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(100, 3, NULL, NULL, 30, 1, '1KG', 100, 100.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(101, 3, NULL, NULL, 30, 2, '5KG', 100, 500.0000, 10, NULL, 'active', NULL, '2026-07-17 11:54:00', '2026-07-17 11:54:00', NULL, NULL),
(142, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-24 16:15:56', 'Active', NULL, '2026-07-24 10:45:56', '2026-07-24 10:45:56', 46, NULL),
(143, 3, NULL, NULL, NULL, NULL, 'Box', 30, 30.0000, 0, '2026-07-25 17:27:25', 'Active', NULL, '2026-07-24 10:48:10', '2026-07-25 11:57:25', 50, NULL),
(144, 3, NULL, NULL, NULL, NULL, 'Box', 50, 50.0000, 0, '2026-07-25 09:51:56', 'Active', NULL, '2026-07-25 04:21:56', '2026-07-25 04:21:56', 68, NULL),
(145, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-25 17:27:25', 'Active', NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25', 51, NULL),
(146, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-25 17:27:25', 'Active', NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25', 52, NULL),
(147, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-25 17:27:25', 'Active', NULL, '2026-07-25 11:57:25', '2026-07-25 11:57:25', 53, NULL),
(148, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-25 17:27:26', 'Active', NULL, '2026-07-25 11:57:26', '2026-07-25 11:57:26', 62, NULL),
(149, 3, NULL, NULL, NULL, NULL, 'Box', 10, 10.0000, 0, '2026-07-25 17:33:16', 'Active', NULL, '2026-07-25 12:03:16', '2026-07-25 12:03:16', 60, NULL),
(150, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-25 17:33:16', 'Active', NULL, '2026-07-25 12:03:16', '2026-07-25 12:03:16', 66, NULL),
(151, 3, NULL, NULL, NULL, NULL, 'Box', 100, 100.0000, 0, '2026-07-25 17:36:29', 'Active', NULL, '2026-07-25 12:06:29', '2026-07-25 12:06:29', 69, NULL),
(152, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-28 18:09:36', 'Active', NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36', 32, NULL),
(153, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-28 18:09:36', 'Active', NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36', 33, NULL),
(154, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-28 18:09:36', 'Active', NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36', 34, NULL),
(155, 3, NULL, NULL, NULL, NULL, 'Box', 20, 20.0000, 0, '2026-07-28 18:09:36', 'Active', NULL, '2026-07-28 12:39:36', '2026-07-28 12:39:36', 31, NULL),
(156, 3, NULL, NULL, NULL, NULL, 'Box', 16, 16.0000, 0, '2026-07-29 09:57:25', 'Active', NULL, '2026-07-28 12:54:33', '2026-07-29 04:27:25', 73, NULL),
(157, 3, NULL, NULL, NULL, NULL, '5-LTR', 10, 10.0000, 0, '2026-07-29 19:31:26', 'Active', NULL, '2026-07-29 14:01:26', '2026-07-29 14:01:26', 75, NULL),
(158, 3, NULL, NULL, NULL, NULL, '1 Unit', 7, 7.0000, 0, '2026-07-29 19:32:23', 'Active', NULL, '2026-07-29 14:02:23', '2026-07-29 14:02:23', 70, NULL),
(159, 3, NULL, NULL, NULL, 3, '0.3KG', 18, 18.0000, 20, '2026-07-29 19:34:34', 'low_stock', NULL, '2026-07-29 14:04:34', '2026-07-29 14:04:34', NULL, NULL),
(160, 3, NULL, NULL, NULL, 24, '1.5KG', 4, 4.0000, 20, '2026-07-29 19:34:43', 'low_stock', NULL, '2026-07-29 14:04:43', '2026-07-29 14:04:43', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `formulas`
--

DROP TABLE IF EXISTS `formulas`;
CREATE TABLE IF NOT EXISTS `formulas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `grade_id` bigint UNSIGNED NOT NULL,
  `version` int NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `formulas_grade_id_version_unique` (`grade_id`,`version`),
  KEY `formulas_created_by_foreign` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulas`
--

INSERT INTO `formulas` (`id`, `grade_id`, `version`, `remarks`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 1, 2, '2026-07-12 11:28:52', '2026-07-12 11:28:52'),
(2, 2, 1, NULL, 1, 2, '2026-07-12 11:29:49', '2026-07-12 11:29:49'),
(3, 3, 1, NULL, 1, 2, '2026-07-12 11:30:57', '2026-07-12 11:30:57'),
(4, 7, 1, NULL, 1, 2, '2026-07-12 11:33:30', '2026-07-12 11:33:30'),
(5, 4, 1, NULL, 1, 2, '2026-07-28 06:32:08', '2026-07-28 06:32:08'),
(6, 5, 1, NULL, 1, 2, '2026-07-28 11:36:04', '2026-07-28 11:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `formula_items`
--

DROP TABLE IF EXISTS `formula_items`;
CREATE TABLE IF NOT EXISTS `formula_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `formula_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `item_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'raw',
  `packing_material_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `consumption_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'formula',
  `consumption_per_unit` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `sequence` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `formula_items_formula_id_foreign` (`formula_id`),
  KEY `formula_items_raw_material_id_foreign` (`raw_material_id`),
  KEY `formula_items_unit_id_foreign` (`unit_id`),
  KEY `formula_items_packing_material_id_foreign` (`packing_material_id`)
) ENGINE=MyISAM AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formula_items`
--

INSERT INTO `formula_items` (`id`, `formula_id`, `raw_material_id`, `item_type`, `packing_material_id`, `quantity`, `unit_id`, `consumption_method`, `consumption_per_unit`, `sequence`, `created_at`, `updated_at`) VALUES
(44, 1, 17, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 5, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(43, 1, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(42, 1, 13, 'raw', NULL, 500.0000, 1, 'formula', 1.0000, 3, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(41, 1, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(40, 1, NULL, 'packing', 1, 1.0000, 3, 'output', 1.0000, 1, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(50, 2, 17, 'raw', NULL, 10.0000, 1, 'formula', 1.0000, 5, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(49, 2, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(48, 2, 13, 'raw', NULL, 600.0000, 1, 'formula', 1.0000, 3, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(47, 2, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(46, 2, NULL, 'packing', 2, 1.0000, 3, 'output', 1.0000, 1, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(57, 3, 18, 'raw', NULL, 6.0000, 1, 'formula', 1.0000, 6, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(56, 3, 17, 'raw', NULL, 28.0000, 1, 'formula', 1.0000, 5, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(55, 3, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(54, 3, 13, 'raw', NULL, 700.0000, 1, 'formula', 1.0000, 3, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(53, 3, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(52, 3, NULL, 'packing', 3, 1.0000, 3, 'output', 1.0000, 1, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(33, 4, 18, 'raw', NULL, 7.0000, 1, 'formula', 1.0000, 7, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(32, 4, 20, 'raw', NULL, 36.0000, 1, 'formula', 1.0000, 6, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(31, 4, 17, 'raw', NULL, 60.0000, 1, 'formula', 1.0000, 5, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(30, 4, 15, 'raw', NULL, 200.0000, 1, 'formula', 1.0000, 4, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(29, 4, 13, 'raw', NULL, 700.0000, 1, 'formula', 1.0000, 3, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(28, 4, 12, 'raw', NULL, 1280.0000, 1, 'formula', 1.0000, 2, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(27, 4, 6, 'raw', NULL, 1.0000, 3, 'output', 1.0000, 1, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(34, 4, 21, 'raw', NULL, 1.2000, 1, 'formula', 1.0000, 8, '2026-07-12 11:35:16', '2026-07-12 11:35:16'),
(63, 5, 15, 'raw', NULL, 50.0000, 1, 'formula', 1.0000, 5, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(62, 5, 18, 'raw', NULL, 1.0000, 1, 'formula', 1.0000, 4, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(61, 5, 17, 'raw', NULL, 3.5000, 1, 'formula', 1.0000, 3, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(60, 5, 14, 'raw', NULL, 150.0000, 1, 'formula', 1.0000, 2, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(59, 5, 12, 'raw', NULL, 320.0000, 1, 'formula', 1.0000, 1, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(45, 1, 18, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 6, '2026-07-28 11:28:17', '2026-07-28 11:28:17'),
(51, 2, 18, 'raw', NULL, 4.0000, 1, 'formula', 1.0000, 6, '2026-07-28 11:28:31', '2026-07-28 11:28:31'),
(58, 3, 19, 'raw', NULL, 2.0000, 1, 'formula', 1.0000, 7, '2026-07-28 11:28:56', '2026-07-28 11:28:56'),
(64, 5, NULL, 'packing', 4, 1.0000, 3, 'output', 1.0000, 6, '2026-07-28 11:34:30', '2026-07-28 11:34:30'),
(65, 6, 12, 'raw', NULL, 320.0000, 1, 'formula', 1.0000, 1, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(66, 6, 14, 'raw', NULL, 175.0000, 1, 'formula', 1.0000, 2, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(67, 6, 17, 'raw', NULL, 7.0000, 1, 'formula', 1.0000, 3, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(68, 6, 18, 'raw', NULL, 1.5000, 1, 'formula', 1.0000, 4, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(69, 6, 15, 'raw', NULL, 50.0000, 1, 'formula', 1.0000, 5, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(70, 6, 19, 'raw', NULL, 0.5000, 1, 'formula', 1.0000, 6, '2026-07-28 11:36:04', '2026-07-28 11:36:04'),
(71, 6, NULL, 'packing', 5, 1.0000, 3, 'output', 1.0000, 7, '2026-07-28 11:36:04', '2026-07-28 11:36:04');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bag_size_id` bigint UNSIGNED NOT NULL,
  `output_unit_id` bigint UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grades_name_unique` (`name`),
  UNIQUE KEY `grades_code_unique` (`code`),
  KEY `grades_department_id_foreign` (`department_id`),
  KEY `grades_bag_size_id_foreign` (`bag_size_id`),
  KEY `grades_output_unit_id_foreign` (`output_unit_id`),
  KEY `grades_created_by_foreign` (`created_by`),
  KEY `grades_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `department_id`, `name`, `code`, `bag_size_id`, `output_unit_id`, `description`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'F-101', 'F-101', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:54:33', '2026-07-12 10:54:33'),
(2, 2, 'F-107', 'F-107', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:54:48', '2026-07-12 10:54:48'),
(3, 2, 'F-121', 'F-121', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:55:01', '2026-07-12 10:55:01'),
(4, 2, 'F-115 (White)', 'F-115', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:55:40', '2026-07-12 10:56:18'),
(5, 2, 'F-133 (White)', 'F-133', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:56:02', '2026-07-12 10:56:02'),
(6, 2, 'F-147 (White)', 'F-147', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:56:46', '2026-07-12 10:56:46'),
(7, 2, 'F-147 (Gray)', 'F-147G', 1, 1, NULL, 1, 2, 2, '2026-07-12 10:57:18', '2026-07-12 10:57:18');

-- --------------------------------------------------------

--
-- Table structure for table `grout_formulas`
--

DROP TABLE IF EXISTS `grout_formulas`;
CREATE TABLE IF NOT EXISTS `grout_formulas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `color_id` bigint UNSIGNED NOT NULL,
  `version` int NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` bigint UNSIGNED NOT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grout_formulas_color_id_version_unique` (`color_id`,`version`),
  KEY `grout_formulas_created_by_foreign` (`created_by`),
  KEY `grout_formulas_updated_by_foreign` (`updated_by`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grout_formulas`
--

INSERT INTO `grout_formulas` (`id`, `color_id`, `version`, `remarks`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 1, 1, NULL, 1, 2, NULL, '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(3, 3, 1, NULL, 1, 2, 2, '2026-07-13 05:53:00', '2026-07-13 05:54:04'),
(4, 5, 1, NULL, 1, 2, NULL, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(5, 7, 1, NULL, 1, 2, NULL, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(6, 9, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(7, 10, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(8, 11, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(9, 12, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(10, 13, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(11, 14, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(12, 15, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(13, 16, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(14, 17, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(15, 18, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(16, 19, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(17, 20, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(18, 21, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(19, 22, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(20, 23, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(21, 24, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(22, 25, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(23, 26, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(24, 27, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(25, 28, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(26, 29, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(27, 30, 1, NULL, 1, 2, NULL, '2026-07-13 07:03:50', '2026-07-13 07:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `grout_formula_items`
--

DROP TABLE IF EXISTS `grout_formula_items`;
CREATE TABLE IF NOT EXISTS `grout_formula_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `grout_formula_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(10,4) NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `mix_stage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grout_formula_items_grout_formula_id_foreign` (`grout_formula_id`),
  KEY `grout_formula_items_raw_material_id_foreign` (`raw_material_id`),
  KEY `grout_formula_items_unit_id_foreign` (`unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grout_formula_items`
--

INSERT INTO `grout_formula_items` (`id`, `grout_formula_id`, `raw_material_id`, `quantity`, `unit_id`, `mix_stage`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-12 12:28:06', '2026-07-12 12:28:06'),
(2, 1, 17, 2.0000, 1, 'Stage 2', 1, '2026-07-12 12:28:06', '2026-07-12 12:28:06'),
(3, 1, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-12 12:28:06', '2026-07-12 12:28:06'),
(4, 2, 16, 700.0000, 1, 'Stage 1', 0, '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(5, 2, 14, 250.0000, 1, 'Stage 2', 1, '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(6, 2, 17, 4.0000, 1, 'Stage 1', 2, '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(7, 2, 18, 2.0000, 1, 'Stage 1', 3, '2026-07-13 05:52:16', '2026-07-13 05:52:16'),
(15, 3, 18, 2.0000, 1, 'Stage 1', 3, '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(14, 3, 17, 4.0000, 1, 'Stage 1', 2, '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(13, 3, 14, 250.0000, 1, 'Stage 2', 1, '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(12, 3, 16, 700.0000, 1, 'Stage 1', 0, '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(16, 3, 28, 2.0000, 1, 'Stage 1', 4, '2026-07-13 05:54:04', '2026-07-13 05:54:04'),
(17, 4, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(18, 4, 17, 2.0000, 1, 'Stage 1', 1, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(19, 4, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(20, 4, 22, 17.0000, 1, 'Stage 1', 3, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(21, 4, 13, 125.0000, 1, 'Stage 2', 4, '2026-07-13 06:09:02', '2026-07-13 06:09:02'),
(22, 5, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(23, 5, 17, 2.0000, 1, 'Stage 1', 1, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(24, 5, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(25, 5, 22, 0.3000, 1, 'Stage 1', 3, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(26, 5, 25, 0.5000, 1, 'Stage 1', 4, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(27, 5, 13, 150.0000, 1, 'Stage 2', 5, '2026-07-13 06:46:06', '2026-07-13 06:46:06'),
(28, 6, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(29, 6, 17, 2.0000, 1, 'Stage 1', 1, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(30, 6, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(31, 6, 30, 2.0000, 1, 'Stage 1', 3, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(32, 6, 25, 3.3000, 1, 'Stage 1', 4, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(33, 6, 14, 100.0000, 1, 'Stage 2', 5, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(34, 7, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(35, 7, 17, 2.0000, 1, 'Stage 1', 1, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(36, 7, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(37, 7, 30, 2.0000, 1, 'Stage 1', 3, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(38, 7, 25, 3.3000, 1, 'Stage 1', 4, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(39, 7, 14, 100.0000, 1, 'Stage 2', 5, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(40, 8, 16, 225.0000, 1, 'Stage 1', 0, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(41, 8, 17, 2.0000, 1, 'Stage 1', 1, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(42, 8, 18, 0.6000, 1, 'Stage 1', 2, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(43, 8, 25, 3.3000, 1, 'Stage 1', 3, '2026-07-13 07:03:50', '2026-07-13 07:03:50'),
(44, 8, 14, 100.0000, 1, 'Stage 2', 4, '2026-07-13 07:03:50', '2026-07-13 07:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `grout_production_batches`
--

DROP TABLE IF EXISTS `grout_production_batches`;
CREATE TABLE IF NOT EXISTS `grout_production_batches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_id` bigint UNSIGNED NOT NULL,
  `color_id` bigint UNSIGNED NOT NULL,
  `grout_formula_id` bigint UNSIGNED NOT NULL,
  `formula_snapshot` json NOT NULL,
  `operator_id` bigint UNSIGNED NOT NULL,
  `status` enum('Waiting','Stage 1 Mixing','Timer Running','Waiting Cement','Stage 2 Mixing','Ready For Packing','Packing','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Waiting',
  `start_time` datetime DEFAULT NULL,
  `timer_start_time` datetime DEFAULT NULL,
  `timer_end_time` datetime DEFAULT NULL,
  `stage1_start_time` datetime DEFAULT NULL,
  `stage1_end_time` datetime DEFAULT NULL,
  `stage2_start_time` datetime DEFAULT NULL,
  `stage2_end_time` datetime DEFAULT NULL,
  `packing_start_time` datetime DEFAULT NULL,
  `packing_end_time` datetime DEFAULT NULL,
  `finished_bags` int DEFAULT NULL,
  `total_weight_kg` decimal(12,4) DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `timer_skipped` tinyint(1) NOT NULL DEFAULT '0',
  `skipped_by_id` bigint UNSIGNED DEFAULT NULL,
  `skip_reason` text COLLATE utf8mb4_unicode_ci,
  `skipped_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grout_production_batches_batch_no_unique` (`batch_no`),
  KEY `grout_production_batches_machine_id_foreign` (`machine_id`),
  KEY `grout_production_batches_color_id_foreign` (`color_id`),
  KEY `grout_production_batches_grout_formula_id_foreign` (`grout_formula_id`),
  KEY `grout_production_batches_operator_id_foreign` (`operator_id`),
  KEY `grout_production_batches_status_index` (`status`),
  KEY `grout_production_batches_skipped_by_id_foreign` (`skipped_by_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grout_production_batches`
--

INSERT INTO `grout_production_batches` (`id`, `batch_no`, `machine_id`, `color_id`, `grout_formula_id`, `formula_snapshot`, `operator_id`, `status`, `start_time`, `timer_start_time`, `timer_end_time`, `stage1_start_time`, `stage1_end_time`, `stage2_start_time`, `stage2_end_time`, `packing_start_time`, `packing_end_time`, `finished_bags`, `total_weight_kg`, `remarks`, `created_at`, `updated_at`, `timer_skipped`, `skipped_by_id`, `skip_reason`, `skipped_at`) VALUES
(1, 'GRT-20260713-0001', 1, 1, 2, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}]', 2, 'Completed', '2026-07-13 16:32:48', '2026-07-13 16:32:51', '2026-07-13 16:33:51', '2026-07-13 16:32:48', '2026-07-13 16:33:56', NULL, NULL, '2026-07-13 16:33:59', '2026-07-13 16:34:08', 36, 900.0000, NULL, '2026-07-13 11:02:48', '2026-07-13 11:04:08', 0, NULL, NULL, NULL),
(2, 'G1426', 2, 5, 4, '[{\"unit_id\": 1, \"quantity\": 225, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 0.6, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 17, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 22, \"raw_material_code\": \"PMT-01\", \"raw_material_name\": \"Prigment Color Black\"}, {\"unit_id\": 1, \"quantity\": 125, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\"}]', 5, 'Timer Running', '2026-07-13 17:25:27', '2026-07-13 17:25:31', '2026-07-13 18:24:31', '2026-07-13 17:25:27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-13 11:55:27', '2026-07-13 11:55:31', 0, NULL, NULL, NULL),
(3, 'G1426R', 3, 13, 10, '[]', 5, 'Timer Running', '2026-07-13 17:26:54', '2026-07-13 17:26:56', '2026-07-13 18:25:56', '2026-07-13 17:26:54', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-13 11:56:54', '2026-07-13 11:56:56', 0, NULL, NULL, NULL),
(4, 'GRT-20260713-0004', 1, 1, 2, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}]', 2, 'Completed', '2026-07-13 21:07:12', '2026-07-13 21:07:15', '2026-07-13 21:08:15', '2026-07-13 21:07:12', '2026-07-13 21:08:21', NULL, NULL, '2026-07-13 21:08:25', '2026-07-13 21:08:39', 36, 900.0000, NULL, '2026-07-13 15:37:12', '2026-07-13 15:38:39', 0, NULL, NULL, NULL),
(5, 'GRT-20260713-0005', 1, 1, 2, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}]', 5, 'Completed', '2026-07-13 21:11:30', '2026-07-13 21:11:34', '2026-07-13 21:12:34', '2026-07-13 21:11:30', '2026-07-13 21:43:49', NULL, NULL, '2026-07-13 21:44:32', '2026-07-13 21:45:52', 40, 1000.0000, NULL, '2026-07-13 15:41:30', '2026-07-13 16:15:52', 0, NULL, NULL, NULL),
(6, 'GRT-20260713-0006', 1, 3, 3, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 28, \"raw_material_code\": \"PMT-07\", \"raw_material_name\": \"Prigment Color Yellow\"}]', 5, 'Completed', '2026-07-13 21:46:57', '2026-07-13 21:47:14', '2026-07-13 21:48:14', '2026-07-13 21:46:57', '2026-07-13 22:16:22', NULL, NULL, '2026-07-13 22:16:38', '2026-07-13 22:16:53', 36, 900.0000, NULL, '2026-07-13 16:16:57', '2026-07-13 16:46:53', 0, NULL, NULL, NULL),
(7, 'GRT-20260713-0007', 1, 3, 3, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 28, \"raw_material_code\": \"PMT-07\", \"raw_material_name\": \"Prigment Color Yellow\"}]', 5, 'Completed', '2026-07-13 22:17:29', '2026-07-13 22:17:48', '2026-07-13 22:18:48', '2026-07-13 22:17:29', '2026-07-13 22:24:51', NULL, NULL, '2026-07-13 22:25:06', '2026-07-13 22:25:21', 38, 950.0000, NULL, '2026-07-13 16:47:29', '2026-07-13 16:55:21', 0, NULL, NULL, NULL),
(8, 'GRT-20260713-0008', 1, 3, 3, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 28, \"raw_material_code\": \"PMT-07\", \"raw_material_name\": \"Prigment Color Yellow\"}]', 5, 'Completed', '2026-07-13 22:26:03', '2026-07-13 22:26:16', '2026-07-13 22:27:16', '2026-07-13 22:26:03', '2026-07-13 22:53:37', NULL, NULL, '2026-07-13 22:54:40', '2026-07-13 22:54:59', 39, 975.0000, NULL, '2026-07-13 16:56:03', '2026-07-13 17:24:59', 0, NULL, NULL, NULL),
(9, 'GRT-20260713-0009', 1, 3, 3, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 28, \"raw_material_code\": \"PMT-07\", \"raw_material_name\": \"Prigment Color Yellow\"}]', 5, 'Completed', '2026-07-13 23:12:39', '2026-07-13 23:12:52', '2026-07-13 23:13:52', '2026-07-13 23:12:39', '2026-07-13 23:37:58', NULL, NULL, '2026-07-13 23:38:17', '2026-07-13 23:38:35', 39, 975.0000, NULL, '2026-07-13 17:42:39', '2026-07-13 18:08:35', 0, NULL, NULL, NULL),
(10, 'GRT-20260713-0010', 1, 1, 2, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}]', 5, 'Completed', '2026-07-13 23:39:15', '2026-07-13 23:39:32', '2026-07-13 23:40:32', '2026-07-13 23:39:15', '2026-07-14 08:31:45', NULL, NULL, '2026-07-14 08:32:18', '2026-07-14 08:32:37', 38, 950.0000, NULL, '2026-07-13 18:09:15', '2026-07-14 03:02:37', 0, NULL, NULL, NULL),
(11, 'G1426G', 1, 3, 3, '[{\"unit_id\": 1, \"quantity\": 700, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 16, \"raw_material_code\": \"DL\", \"raw_material_name\": \"Dolomite\"}, {\"unit_id\": 1, \"quantity\": 250, \"mix_stage\": \"Stage 2\", \"unit_code\": \"KG\", \"raw_material_id\": 14, \"raw_material_code\": \"WHT-01\", \"raw_material_name\": \"White Cement\"}, {\"unit_id\": 1, \"quantity\": 4, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\"}, {\"unit_id\": 1, \"quantity\": 2, \"mix_stage\": \"Stage 1\", \"unit_code\": \"KG\", \"raw_material_id\": 28, \"raw_material_code\": \"PMT-07\", \"raw_material_name\": \"Prigment Color Yellow\"}]', 5, 'Timer Running', '2026-07-14 09:00:35', '2026-07-14 09:00:50', '2026-07-14 09:59:50', '2026-07-14 09:00:35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-07-14 03:30:35', '2026-07-14 03:30:50', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

DROP TABLE IF EXISTS `machines`;
CREATE TABLE IF NOT EXISTS `machines` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `department_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `machines_code_unique` (`code`),
  KEY `machines_department_id_foreign` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `department_id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Automatic Packing Machine M-01', 'M-01', 'Automatic packing machine for White & Ivory grouts (500 GM & 1 KG pouches)', 1, '2026-07-12 10:41:53', '2026-07-12 10:41:53'),
(2, 1, 'Manual Mixing Machine M-04', 'M-04', 'Manual mixer for colored grouts with 1-hour dry mix timers', 1, '2026-07-12 10:41:53', '2026-07-12 10:41:53'),
(3, 1, 'Manual Mixing Machine M-05', 'M-05', 'Manual mixer for colored grouts with 1-hour dry mix timers', 1, '2026-07-12 10:41:53', '2026-07-12 10:41:53'),
(4, 2, 'M-07', 'M-07', NULL, 1, '2026-07-12 10:46:29', '2026-07-12 10:46:29'),
(5, 2, 'M-08', 'M-08', NULL, 1, '2026-07-12 10:46:41', '2026-07-12 10:46:41'),
(6, 2, 'M-09', 'M-09', NULL, 1, '2026-07-12 10:46:54', '2026-07-12 10:46:54'),
(7, 2, 'Pan-Mixer', 'M-02', NULL, 1, '2026-07-12 10:53:37', '2026-07-12 10:53:37'),
(8, 2, 'Pan-Mixer', 'M-03', NULL, 1, '2026-07-12 10:53:52', '2026-07-12 10:53:52');

-- --------------------------------------------------------

--
-- Table structure for table `marketing_orders`
--

DROP TABLE IF EXISTS `marketing_orders`;
CREATE TABLE IF NOT EXISTS `marketing_orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `party_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_date` date NOT NULL,
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `availability` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unknown',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancel_reason` text COLLATE utf8mb4_unicode_ci,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marketing_orders_order_number_unique` (`order_number`),
  KEY `marketing_orders_created_by_foreign` (`created_by`),
  KEY `marketing_orders_approved_by_foreign` (`approved_by`),
  KEY `marketing_orders_status_sort_order_index` (`status`,`sort_order`),
  KEY `marketing_orders_party_name_index` (`party_name`),
  KEY `marketing_orders_order_date_index` (`order_date`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_orders`
--

INSERT INTO `marketing_orders` (`id`, `order_number`, `party_name`, `city`, `coupon`, `vehicle_number`, `order_date`, `priority`, `status`, `is_edited`, `availability`, `remarks`, `created_by`, `approved_by`, `approved_at`, `completed_at`, `cancelled_at`, `cancel_reason`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'MKT-20260728-001', 'Roman', 'Morbi', NULL, NULL, '2026-07-28', 'urgent', 'in_progress', 0, 'available', NULL, 2, 2, '2026-07-28 12:03:58', NULL, NULL, NULL, 1, '2026-07-28 11:39:36', '2026-07-28 12:03:58'),
(2, 'MKT-20260728-002', 'National Tiles', 'Bhabhra', NULL, NULL, '2026-07-28', 'high', 'in_progress', 0, 'available', NULL, 2, 2, '2026-07-28 12:14:32', NULL, NULL, NULL, 4, '2026-07-28 11:40:23', '2026-07-28 12:14:32'),
(3, 'MKT-20260728-003', 'Khushi Traders', 'Bhabhra', NULL, NULL, '2026-07-28', 'high', 'in_progress', 1, 'available', 'cetra 50gm - 1 box\ntrowel steel - 5\npu adheshive - 2', 2, 2, '2026-07-28 12:15:06', NULL, NULL, NULL, 5, '2026-07-28 11:43:03', '2026-07-29 14:02:34'),
(4, 'MKT-20260728-004', 'Advgith Enterprise', 'Mancherial', NULL, NULL, '2026-07-28', 'medium', 'in_progress', 0, 'partial', NULL, 2, 2, '2026-07-29 14:02:39', NULL, NULL, NULL, 6, '2026-07-28 11:45:18', '2026-07-29 14:02:39'),
(5, 'MKT-20260728-005', 'Shree Krishna', 'Renwal', NULL, NULL, '2026-07-28', 'medium', 'pending', 0, 'partial', NULL, 2, NULL, NULL, NULL, NULL, NULL, 5, '2026-07-28 11:56:23', '2026-07-28 11:56:23'),
(6, 'MKT-20260728-006', 'Aniket', 'Sampat', NULL, NULL, '2026-07-28', 'medium', 'in_progress', 0, 'available', NULL, 2, 2, '2026-07-28 12:04:06', NULL, NULL, NULL, 2, '2026-07-28 11:57:00', '2026-07-28 12:04:06'),
(7, 'MKT-20260728-007', 'Rajasthan Marble', 'Betiah', NULL, NULL, '2026-07-28', 'medium', 'pending', 0, 'partial', 'cetra 20gm -8 box', 2, NULL, NULL, NULL, NULL, NULL, 7, '2026-07-28 11:59:28', '2026-07-28 11:59:28'),
(8, 'MKT-20260728-008', 'Vipul Sanitary', 'Gondal', NULL, NULL, '2026-07-28', 'medium', 'in_progress', 0, 'available', NULL, 2, 2, '2026-07-28 12:04:12', NULL, NULL, NULL, 3, '2026-07-28 12:00:20', '2026-07-28 12:04:12');

-- --------------------------------------------------------

--
-- Table structure for table `marketing_order_items`
--

DROP TABLE IF EXISTS `marketing_order_items`;
CREATE TABLE IF NOT EXISTS `marketing_order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `marketing_order_id` bigint UNSIGNED NOT NULL,
  `department_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_id` bigint UNSIGNED DEFAULT NULL,
  `color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_product_id` bigint UNSIGNED DEFAULT NULL,
  `quantity_bags` int NOT NULL,
  `quantity_kg` decimal(10,2) DEFAULT NULL,
  `packing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_quantity` int DEFAULT NULL,
  `is_product_available` tinyint(1) NOT NULL DEFAULT '0',
  `is_coupon_available` tinyint(1) DEFAULT NULL,
  `item_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_edited` tinyint(1) NOT NULL DEFAULT '0',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_component_id` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketing_order_items_grade_id_foreign` (`grade_id`),
  KEY `marketing_order_items_color_id_foreign` (`color_id`),
  KEY `marketing_order_items_epoxy_product_id_foreign` (`epoxy_product_id`),
  KEY `marketing_order_items_coupon_raw_material_id_foreign` (`coupon_raw_material_id`),
  KEY `marketing_order_items_marketing_order_id_index` (`marketing_order_id`),
  KEY `marketing_order_items_department_code_index` (`department_code`),
  KEY `fk_mkt_items_epoxy_filler_color` (`epoxy_filler_color_id`),
  KEY `fk_mkt_items_epoxy_component` (`epoxy_component_id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_order_items`
--

INSERT INTO `marketing_order_items` (`id`, `marketing_order_id`, `department_code`, `grade_id`, `color_id`, `epoxy_product_id`, `quantity_bags`, `quantity_kg`, `packing`, `coupon_raw_material_id`, `coupon_quantity`, `is_product_available`, `is_coupon_available`, `item_status`, `is_edited`, `remarks`, `created_at`, `updated_at`, `epoxy_filler_color_id`, `epoxy_component_id`) VALUES
(1, 1, 'TAD', 1, NULL, NULL, 134, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:39:36', '2026-07-28 11:39:36', NULL, NULL),
(2, 1, 'EPX', NULL, NULL, 1, 18, NULL, '1KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:39:36', '2026-07-28 11:39:36', 9, NULL),
(3, 2, 'TAD', 2, NULL, NULL, 500, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:40:23', '2026-07-28 12:14:14', NULL, NULL),
(48, 3, 'EPX', NULL, NULL, NULL, 4, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 46),
(47, 3, 'EPX', NULL, NULL, NULL, 4, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 46),
(46, 3, 'EPX', NULL, NULL, NULL, 4, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 46),
(45, 3, 'EPX', NULL, NULL, 2, 4, NULL, '5KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-28 12:02:49', 9, NULL),
(44, 3, 'EPX', NULL, NULL, NULL, 1, NULL, 'Pckt', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 33),
(43, 3, 'EPX', NULL, NULL, NULL, 1, NULL, 'Pckt', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 32),
(42, 3, 'EPX', NULL, NULL, NULL, 1, NULL, 'Pckt', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 31),
(41, 3, 'EPX', NULL, NULL, 21, 1, NULL, '200GM', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, NULL),
(40, 3, 'EPX', NULL, NULL, 20, 1, NULL, '5-LTR', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, NULL),
(39, 3, 'TAD', 2, NULL, NULL, 160, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 1, NULL, '2026-07-28 12:02:49', '2026-07-28 12:02:49', NULL, NULL),
(38, 3, 'TAD', 1, NULL, NULL, 60, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-28 12:02:49', NULL, NULL),
(16, 4, 'TAD', 2, NULL, NULL, 200, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', NULL, NULL),
(17, 4, 'EPX', NULL, NULL, 21, 1, NULL, '200GM', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-29 14:02:34', NULL, NULL),
(18, 4, 'GRT', NULL, 1, NULL, 4, NULL, '1 KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', NULL, NULL),
(19, 4, 'EPX', NULL, NULL, 3, 18, NULL, '0.3KG', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', NULL, NULL),
(20, 4, 'EPX', NULL, NULL, 24, 4, NULL, '1.5KG', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', NULL, NULL),
(21, 4, 'EPX', NULL, NULL, NULL, 1, NULL, 'Pckt', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-29 14:02:34', NULL, 31),
(22, 4, 'EPX', NULL, NULL, 2, 1, NULL, '5KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', 22, NULL),
(23, 4, 'EPX', NULL, NULL, 2, 7, NULL, '5KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:45:18', '2026-07-28 11:45:18', 24, NULL),
(24, 5, 'TAD', 1, NULL, NULL, 240, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:56:23', '2026-07-28 11:56:23', NULL, NULL),
(25, 5, 'TAD', 4, NULL, NULL, 200, NULL, '20KG', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:56:23', '2026-07-28 11:56:23', NULL, NULL),
(26, 5, 'EPX', NULL, NULL, 20, 2, NULL, '1-LTR', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:56:23', '2026-07-28 11:56:23', NULL, NULL),
(27, 5, 'EPX', NULL, NULL, 20, 2, NULL, '5-LTR', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:56:23', '2026-07-29 14:02:34', NULL, NULL),
(28, 5, 'EPX', NULL, NULL, NULL, 2, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:56:23', '2026-07-29 14:02:34', NULL, 51),
(29, 6, 'TAD', 2, NULL, NULL, 80, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:57:00', '2026-07-28 11:57:00', NULL, NULL),
(30, 7, 'GRT', NULL, 5, NULL, 1, NULL, '1 KG', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', NULL, NULL),
(31, 7, 'GRT', NULL, 27, NULL, 2, NULL, '1 KG', NULL, NULL, 0, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', NULL, NULL),
(32, 7, 'GRT', NULL, 3, NULL, 4, NULL, '1 KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', NULL, NULL),
(33, 7, 'GRT', NULL, 1, NULL, 10, NULL, '1 KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', NULL, NULL),
(34, 7, 'EPX', NULL, NULL, 1, 18, NULL, '1KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', 9, NULL),
(35, 7, 'EPX', NULL, NULL, 1, 18, NULL, '1KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', 25, NULL),
(36, 7, 'EPX', NULL, NULL, 2, 4, NULL, '5KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 11:59:28', '2026-07-28 11:59:28', 25, NULL),
(37, 8, 'TAD', 2, NULL, NULL, 150, NULL, '20KG', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:00:20', '2026-07-28 12:00:20', NULL, NULL),
(49, 3, 'EPX', NULL, NULL, NULL, 1, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 66),
(50, 3, 'EPX', NULL, NULL, NULL, 20, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 68),
(51, 3, 'EPX', NULL, NULL, NULL, 5, NULL, 'Box', NULL, NULL, 1, NULL, 'pending', 0, NULL, '2026-07-28 12:02:49', '2026-07-29 14:02:34', NULL, 70);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_27_000000_create_roles_and_permissions_tables', 1),
(5, '2026_06_27_000001_create_departments_table', 1),
(6, '2026_06_27_000002_create_machines_table', 1),
(7, '2026_06_27_000003_create_units_table', 1),
(8, '2026_06_27_000004_create_bag_sizes_table', 1),
(9, '2026_06_27_000005_create_raw_materials_table', 1),
(10, '2026_06_27_000006_create_grades_table', 1),
(11, '2026_06_27_000007_create_formulas_table', 1),
(12, '2026_06_27_000008_create_formula_items_table', 1),
(13, '2026_06_27_000009_create_production_batches_table', 1),
(14, '2026_06_27_000010_create_stock_ledgers_table', 1),
(15, '2026_06_27_000011_create_stock_adjustments_table', 1),
(16, '2026_06_27_000012_create_activity_logs_table', 1),
(17, '2026_06_28_000000_add_formula_snapshot_to_production_batches_table', 1),
(51, '2026_06_28_000002_create_settings_table', 4),
(19, '2026_06_28_000004_add_profile_fields_to_users_table', 1),
(20, '2026_06_28_000005_add_module_to_activity_logs_table', 1),
(21, '2026_06_29_000000_create_user_departments_table', 1),
(22, '2026_06_29_000001_create_colors_table', 1),
(23, '2026_06_29_000002_create_grout_formulas_table', 1),
(24, '2026_06_29_000003_create_grout_formula_items_table', 1),
(25, '2026_06_29_000004_create_grout_production_batches_table', 1),
(26, '2026_06_29_000005_add_grout_batch_id_to_stock_ledgers_table', 1),
(27, '2026_06_30_000000_add_skip_timer_fields_to_grout_production_batches_table', 1),
(28, '2026_07_01_000000_create_epoxy_products_table', 1),
(29, '2026_07_01_000001_create_epoxy_formulas_table', 1),
(30, '2026_07_01_000002_create_epoxy_formula_items_table', 1),
(31, '2026_07_01_000003_create_epoxy_assemblies_table', 1),
(32, '2026_07_01_000004_add_epoxy_assembly_id_to_stock_ledgers_table', 1),
(33, '2026_07_02_000001_create_finished_goods_table', 1),
(34, '2026_07_05_000000_add_consumption_method_to_formula_items_table', 1),
(35, '2026_07_05_000001_remove_dual_color_from_colors_table', 1),
(36, '2026_07_06_000000_create_epoxy_filler_colors_table', 1),
(37, '2026_07_06_000001_create_epoxy_components_table', 1),
(38, '2026_07_06_000002_add_epoxy_color_to_tables', 1),
(39, '2026_07_06_000003_upgrade_epoxy_components_table', 1),
(40, '2026_07_06_000004_create_user_devices_table', 1),
(41, '2026_07_06_000005_create_notifications_table', 1),
(42, '2026_07_09_000000_change_production_batches_status_to_string', 1),
(43, '2026_07_09_000001_create_todos_table', 1),
(44, '2026_07_10_160000_add_is_coupon_to_raw_materials_table', 1),
(45, '2026_07_10_160001_create_marketing_orders_table', 1),
(46, '2026_07_10_160002_create_marketing_order_items_table', 1),
(47, '2026_07_10_160003_seed_marketing_role', 1),
(48, '2026_07_10_160004_seed_all_coupons', 1),
(49, '2026_07_13_000000_add_coupon_raw_material_id_to_finished_goods_table', 2),
(50, '2026_07_14_000000_create_super_admin_role_and_maintenance_settings', 3),
(52, '2026_07_14_000000_seed_maintenance_settings', 4),
(53, '2026_07_21_000000_add_city_and_delivery_date_to_marketing_orders_table', 5),
(54, '2026_07_21_000001_update_marketing_orders_table_remove_delivery_date_add_coupon', 6),
(55, '2026_07_21_180000_create_dispatch_management_system_tables', 7),
(56, '2026_07_21_190000_remove_payment_fields_from_dispatches_table', 8),
(57, '2026_07_22_190000_add_is_edited_to_marketing_orders_and_items_tables', 9),
(58, '2026_07_22_200000_remove_driver_name_from_dispatches_table', 10),
(59, '2026_07_22_210000_make_remarks_nullable_in_stock_adjustments_table', 11),
(60, '2026_07_23_000001_create_packing_material_categories_table', 11),
(61, '2026_07_23_000002_create_packing_materials_table', 11),
(62, '2026_07_23_000003_add_packing_material_id_to_stock_tables', 11),
(63, '2026_07_23_000004_migrate_packing_materials_data', 11),
(64, '2026_07_23_000005_add_packing_material_id_to_epoxy_component_formula_items_table', 12),
(65, '2026_07_28_000001_add_packing_material_id_to_formula_items_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_department_id_foreign` (`department_id`),
  KEY `notifications_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `title`, `body`, `type`, `department_id`, `user_id`, `status`, `sent_at`, `read_at`, `payload`, `created_at`, `updated_at`) VALUES
(1, 'Mixing Complete', 'Machine M-05 mixing completed.\nBatch: G1426R\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 06:29 PM', 'grout_mixing_complete', 1, 2, 'failed', '2026-07-13 12:59:20', '2026-07-13 15:42:41', '{\"batch_id\": 3, \"batch_no\": \"G1426R\", \"click_action\": \"https://post-heroes-textbooks-clearance.trycloudflare.com/grout-production/3/running\", \"machine_code\": \"M-05\"}', '2026-07-13 12:59:20', '2026-07-13 15:42:41'),
(2, 'Mixing Complete', 'Machine M-05 mixing completed.\nBatch: G1426R\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 06:29 PM', 'grout_mixing_complete', 1, 3, 'failed', '2026-07-13 12:59:21', NULL, '{\"batch_id\": 3, \"batch_no\": \"G1426R\", \"click_action\": \"https://post-heroes-textbooks-clearance.trycloudflare.com/grout-production/3/running\", \"machine_code\": \"M-05\"}', '2026-07-13 12:59:21', '2026-07-13 12:59:21'),
(3, 'Mixing Complete', 'Machine M-05 mixing completed.\nBatch: G1426R\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 06:29 PM', 'grout_mixing_complete', 1, 5, 'failed', '2026-07-13 12:59:21', '2026-07-13 15:43:07', '{\"batch_id\": 3, \"batch_no\": \"G1426R\", \"click_action\": \"https://post-heroes-textbooks-clearance.trycloudflare.com/grout-production/3/running\", \"machine_code\": \"M-05\"}', '2026-07-13 12:59:21', '2026-07-13 15:43:07'),
(4, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0005\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:12 PM', 'grout_mixing_complete', 1, 2, 'failed', '2026-07-13 15:42:58', '2026-07-13 15:51:35', '{\"batch_id\": 5, \"batch_no\": \"GRT-20260713-0005\", \"click_action\": \"https://transparent-years-planning-nylon.trycloudflare.com/grout-production/5/running\", \"machine_code\": \"M-01\"}', '2026-07-13 15:42:58', '2026-07-13 15:51:35'),
(5, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0005\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:12 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 15:42:58', NULL, '{\"batch_id\": 5, \"batch_no\": \"GRT-20260713-0005\", \"click_action\": \"https://transparent-years-planning-nylon.trycloudflare.com/grout-production/5/running\", \"machine_code\": \"M-01\"}', '2026-07-13 15:42:58', '2026-07-13 15:42:58'),
(6, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0005\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:12 PM', 'grout_mixing_complete', 1, 5, 'failed', '2026-07-13 15:42:58', '2026-07-13 15:43:07', '{\"batch_id\": 5, \"batch_no\": \"GRT-20260713-0005\", \"click_action\": \"https://transparent-years-planning-nylon.trycloudflare.com/grout-production/5/running\", \"machine_code\": \"M-01\"}', '2026-07-13 15:42:58', '2026-07-13 15:43:07'),
(7, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0006\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:49 PM', 'grout_mixing_complete', 1, 2, 'failed', '2026-07-13 16:19:32', '2026-07-14 07:02:31', '{\"batch_id\": 6, \"batch_no\": \"GRT-20260713-0006\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/6/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:19:32', '2026-07-14 07:02:31'),
(8, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0006\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:49 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 16:19:32', NULL, '{\"batch_id\": 6, \"batch_no\": \"GRT-20260713-0006\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/6/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:19:32', '2026-07-13 16:19:32'),
(9, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0006\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 09:49 PM', 'grout_mixing_complete', 1, 5, 'failed', '2026-07-13 16:19:32', '2026-07-13 16:19:58', '{\"batch_id\": 6, \"batch_no\": \"GRT-20260713-0006\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/6/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:19:32', '2026-07-13 16:19:58'),
(10, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0007\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:20 PM', 'grout_mixing_complete', 1, 2, 'no_device_registered', '2026-07-13 16:50:15', '2026-07-14 07:02:31', '{\"batch_id\": 7, \"batch_no\": \"GRT-20260713-0007\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/7/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:50:15', '2026-07-14 07:02:31'),
(11, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0007\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:20 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 16:50:15', NULL, '{\"batch_id\": 7, \"batch_no\": \"GRT-20260713-0007\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/7/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:50:15', '2026-07-13 16:50:15'),
(12, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0007\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:20 PM', 'grout_mixing_complete', 1, 5, 'failed', '2026-07-13 16:50:19', '2026-07-13 17:00:13', '{\"batch_id\": 7, \"batch_no\": \"GRT-20260713-0007\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/7/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:50:19', '2026-07-13 17:00:13'),
(13, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0008\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:28 PM', 'grout_mixing_complete', 1, 2, 'no_device_registered', '2026-07-13 16:58:31', '2026-07-14 07:02:31', '{\"batch_id\": 8, \"batch_no\": \"GRT-20260713-0008\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/8/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:58:31', '2026-07-14 07:02:31'),
(14, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0008\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:28 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 16:58:31', NULL, '{\"batch_id\": 8, \"batch_no\": \"GRT-20260713-0008\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/8/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:58:31', '2026-07-13 16:58:31'),
(15, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0008\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:28 PM', 'grout_mixing_complete', 1, 5, 'sent', '2026-07-13 16:58:31', '2026-07-13 17:00:13', '{\"batch_id\": 8, \"batch_no\": \"GRT-20260713-0008\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/8/running\", \"machine_code\": \"M-01\"}', '2026-07-13 16:58:31', '2026-07-13 17:00:13'),
(16, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0009\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:13 PM', 'grout_mixing_complete', 1, 2, 'sent', '2026-07-13 17:43:53', '2026-07-14 07:02:31', '{\"batch_id\": 9, \"batch_no\": \"GRT-20260713-0009\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/9/running\", \"machine_code\": \"M-01\"}', '2026-07-13 17:43:53', '2026-07-14 07:02:31'),
(17, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0009\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:13 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 17:43:53', NULL, '{\"batch_id\": 9, \"batch_no\": \"GRT-20260713-0009\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/9/running\", \"machine_code\": \"M-01\"}', '2026-07-13 17:43:53', '2026-07-13 17:43:53'),
(18, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0009\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:13 PM', 'grout_mixing_complete', 1, 5, 'sent', '2026-07-13 17:43:53', '2026-07-13 17:46:30', '{\"batch_id\": 9, \"batch_no\": \"GRT-20260713-0009\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/9/running\", \"machine_code\": \"M-01\"}', '2026-07-13 17:43:53', '2026-07-13 17:46:30'),
(19, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0010\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:41 PM', 'grout_mixing_complete', 1, 2, 'sent', '2026-07-13 18:11:04', '2026-07-14 07:02:31', '{\"batch_id\": 10, \"batch_no\": \"GRT-20260713-0010\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/10/running\", \"machine_code\": \"M-01\"}', '2026-07-13 18:11:04', '2026-07-14 07:02:31'),
(20, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0010\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:41 PM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-13 18:11:04', NULL, '{\"batch_id\": 10, \"batch_no\": \"GRT-20260713-0010\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/10/running\", \"machine_code\": \"M-01\"}', '2026-07-13 18:11:04', '2026-07-13 18:11:04'),
(21, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: GRT-20260713-0010\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 11:41 PM', 'grout_mixing_complete', 1, 5, 'sent', '2026-07-13 18:11:05', '2026-07-14 04:22:46', '{\"batch_id\": 10, \"batch_no\": \"GRT-20260713-0010\", \"click_action\": \"https://solconerpdemo.loca.lt/grout-production/10/running\", \"machine_code\": \"M-01\"}', '2026-07-13 18:11:05', '2026-07-14 04:22:46'),
(22, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: G1426G\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:00 AM', 'grout_mixing_complete', 1, 2, 'sent', '2026-07-14 04:30:30', '2026-07-14 07:02:31', '{\"batch_id\": 11, \"batch_no\": \"G1426G\", \"click_action\": \"https://granny-totals-determine-says.trycloudflare.com/grout-production/11/running\", \"machine_code\": \"M-01\"}', '2026-07-14 04:30:30', '2026-07-14 07:02:31'),
(23, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: G1426G\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:00 AM', 'grout_mixing_complete', 1, 3, 'no_device_registered', '2026-07-14 04:30:30', NULL, '{\"batch_id\": 11, \"batch_no\": \"G1426G\", \"click_action\": \"https://granny-totals-determine-says.trycloudflare.com/grout-production/11/running\", \"machine_code\": \"M-01\"}', '2026-07-14 04:30:30', '2026-07-14 04:30:30'),
(24, 'Mixing Complete', 'Machine M-01 mixing completed.\nBatch: G1426G\nDepartment: Grout\nSupervisor: Grout\nCurrent Time: 10:00 AM', 'grout_mixing_complete', 1, 5, 'sent', '2026-07-14 04:30:32', '2026-07-14 04:50:55', '{\"batch_id\": 11, \"batch_no\": \"G1426G\", \"click_action\": \"https://granny-totals-determine-says.trycloudflare.com/grout-production/11/running\", \"machine_code\": \"M-01\"}', '2026-07-14 04:30:32', '2026-07-14 04:50:55'),
(25, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-21 14:41:27', '2026-07-22 14:25:55', '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 14:41:27', '2026-07-22 14:25:55'),
(26, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-21 14:41:30', '2026-07-21 14:47:36', '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 14:41:30', '2026-07-21 14:47:36'),
(27, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-21 14:41:30', '2026-07-28 04:36:50', '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 14:41:30', '2026-07-28 04:36:50'),
(28, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-21 15:11:29', '2026-07-22 14:25:55', '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 15:11:29', '2026-07-22 14:25:55'),
(29, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-21 15:11:30', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 15:11:30', '2026-07-21 15:11:30'),
(30, 'New Order Approved: MKT-20260721-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-21 15:11:30', '2026-07-28 04:36:50', '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 15:11:30', '2026-07-28 04:36:50'),
(31, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 2, 'sent', '2026-07-21 16:35:55', '2026-07-21 17:01:52', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:35:55', '2026-07-21 17:01:52'),
(32, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-21 16:35:55', NULL, '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:35:55', '2026-07-21 16:35:55'),
(33, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-21 16:35:55', '2026-07-21 17:06:57', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:35:55', '2026-07-21 17:06:57'),
(34, 'New Dispatch Created: DISP-20260721-002', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 2, 'sent', '2026-07-21 16:43:38', '2026-07-21 17:01:52', '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-21 16:43:38', '2026-07-21 17:01:52'),
(35, 'New Dispatch Created: DISP-20260721-002', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-21 16:43:38', NULL, '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-21 16:43:38', '2026-07-21 16:43:38'),
(36, 'New Dispatch Created: DISP-20260721-002', 'New Factory Pickup created for jatin bhai. Vehicle: GJ-01-AB-9999. Expected: TBD', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-21 16:43:38', '2026-07-21 17:06:57', '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-21 16:43:38', '2026-07-21 17:06:57'),
(37, 'New Order Approved: MKT-20260721-001', 'Order for xyz (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-21 16:53:06', '2026-07-22 14:25:55', '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 16:53:06', '2026-07-22 14:25:55'),
(38, 'New Order Approved: MKT-20260721-001', 'Order for xyz (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-21 16:53:07', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 16:53:07', '2026-07-21 16:53:07'),
(39, 'New Order Approved: MKT-20260721-001', 'Order for xyz (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-21 16:53:07', '2026-07-28 04:36:50', '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-21 16:53:07', '2026-07-28 04:36:50'),
(40, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:23 PM', 'dispatch_created', NULL, 2, 'sent', '2026-07-21 16:54:06', '2026-07-21 17:01:52', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:54:06', '2026-07-21 17:01:52'),
(41, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:23 PM', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-21 16:54:06', NULL, '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:54:06', '2026-07-21 16:54:06'),
(42, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:23 PM', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-21 16:54:06', '2026-07-21 17:06:57', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:54:06', '2026-07-21 17:06:57'),
(43, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:26 PM', 'dispatch_created', NULL, 2, 'sent', '2026-07-21 16:56:52', '2026-07-21 17:01:52', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:56:52', '2026-07-21 17:01:52'),
(44, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:26 PM', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-21 16:56:52', NULL, '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:56:52', '2026-07-21 16:56:52'),
(45, 'New Dispatch Created: DISP-20260721-001', 'New Factory Pickup created for xyz. Vehicle: GJ-36-12-4031. Expected: 21 Jul 2026 10:26 PM', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-21 16:56:52', '2026-07-21 17:06:57', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-21 16:56:52', '2026-07-21 17:06:57'),
(46, 'New Order Approved: MKT-20260722-002', 'Order for OM SAI RAM GRAYNIGHT STONE (CHHINDWARA) has been approved. Priority: High', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-22 04:26:01', '2026-07-22 14:25:55', '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 04:26:01', '2026-07-22 14:25:55'),
(47, 'New Order Approved: MKT-20260722-002', 'Order for OM SAI RAM GRAYNIGHT STONE (CHHINDWARA) has been approved. Priority: High', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-22 04:26:02', NULL, '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 04:26:02', '2026-07-22 04:26:02'),
(48, 'New Order Approved: MKT-20260722-002', 'Order for OM SAI RAM GRAYNIGHT STONE (CHHINDWARA) has been approved. Priority: High', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-22 04:26:02', '2026-07-28 04:36:50', '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 04:26:02', '2026-07-28 04:36:50'),
(49, 'New Dispatch Created: DISP-20260722-001', 'New Factory Pickup created for OM SAI RAM GRAYNIGHT STONE. Vehicle: RJ09GD3666. Expected: 22 Jul 2026 09:58 AM', 'dispatch_created', NULL, 2, 'sent', '2026-07-22 04:36:12', '2026-07-22 04:54:57', '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-22 04:36:12', '2026-07-22 04:54:57'),
(50, 'New Dispatch Created: DISP-20260722-001', 'New Factory Pickup created for OM SAI RAM GRAYNIGHT STONE. Vehicle: RJ09GD3666. Expected: 22 Jul 2026 09:58 AM', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-22 04:36:12', NULL, '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-22 04:36:12', '2026-07-22 04:36:12'),
(51, 'New Dispatch Created: DISP-20260722-001', 'New Factory Pickup created for OM SAI RAM GRAYNIGHT STONE. Vehicle: RJ09GD3666. Expected: 22 Jul 2026 09:58 AM', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-22 04:36:12', NULL, '{\"click_url\": \"/dispatch/2\", \"dispatch_id\": 2}', '2026-07-22 04:36:12', '2026-07-22 04:36:12'),
(52, 'New Order Approved: MKT-20260722-004', 'Order for ABC MARBLE (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-22 12:05:30', '2026-07-22 14:25:55', '{\"order_id\": 7, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 12:05:30', '2026-07-22 14:25:55'),
(53, 'New Order Approved: MKT-20260722-004', 'Order for ABC MARBLE (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-22 12:05:34', NULL, '{\"order_id\": 7, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 12:05:34', '2026-07-22 12:05:34'),
(54, 'New Order Approved: MKT-20260722-004', 'Order for ABC MARBLE (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-22 12:05:34', '2026-07-28 04:36:50', '{\"order_id\": 7, \"click_url\": \"/supervisor/orders\"}', '2026-07-22 12:05:34', '2026-07-28 04:36:50'),
(55, 'New Dispatch Created: DISP-20260722-002', 'New Crossing Delivery created for ABC MARBLE. Vehicle: GJ-36-12-4031. Expected: 22 Jul 2026 05:44 PM', 'dispatch_created', NULL, 2, 'sent', '2026-07-22 12:23:02', '2026-07-22 16:33:22', '{\"click_url\": \"/dispatch/3\", \"dispatch_id\": 3}', '2026-07-22 12:23:02', '2026-07-22 16:33:22'),
(56, 'New Dispatch Created: DISP-20260722-002', 'New Crossing Delivery created for ABC MARBLE. Vehicle: GJ-36-12-4031. Expected: 22 Jul 2026 05:44 PM', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-22 12:23:02', NULL, '{\"click_url\": \"/dispatch/3\", \"dispatch_id\": 3}', '2026-07-22 12:23:02', '2026-07-22 12:23:02'),
(57, 'New Dispatch Created: DISP-20260722-002', 'New Crossing Delivery created for ABC MARBLE. Vehicle: GJ-36-12-4031. Expected: 22 Jul 2026 05:44 PM', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-22 12:23:02', NULL, '{\"click_url\": \"/dispatch/3\", \"dispatch_id\": 3}', '2026-07-22 12:23:02', '2026-07-22 12:23:02'),
(58, 'New Order Approved: MKT-20260723-001', 'Order for Digitek (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-23 10:26:13', '2026-07-23 10:34:38', '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-23 10:26:13', '2026-07-23 10:34:38'),
(59, 'New Order Approved: MKT-20260723-001', 'Order for Digitek (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-23 10:26:16', NULL, '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-23 10:26:16', '2026-07-23 10:26:16'),
(60, 'New Order Approved: MKT-20260723-001', 'Order for Digitek (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-23 10:26:16', '2026-07-28 04:36:50', '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-23 10:26:16', '2026-07-28 04:36:50'),
(61, 'New Dispatch Created: DISP-20260723-001', 'New Factory Pickup created for Digitek. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 2, 'sent', '2026-07-23 10:26:53', '2026-07-23 12:00:51', '{\"click_url\": \"/dispatch/4\", \"dispatch_id\": 4}', '2026-07-23 10:26:53', '2026-07-23 12:00:51'),
(62, 'New Dispatch Created: DISP-20260723-001', 'New Factory Pickup created for Digitek. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-23 10:26:53', NULL, '{\"click_url\": \"/dispatch/4\", \"dispatch_id\": 4}', '2026-07-23 10:26:53', '2026-07-23 10:26:53'),
(63, 'New Dispatch Created: DISP-20260723-001', 'New Factory Pickup created for Digitek. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-23 10:26:53', NULL, '{\"click_url\": \"/dispatch/4\", \"dispatch_id\": 4}', '2026-07-23 10:26:53', '2026-07-23 10:26:53'),
(64, 'New Order Approved: MKT-20260724-001', 'Order for Supreme (Rajkot) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-24 04:52:15', NULL, '{\"order_id\": 14, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 04:52:15', '2026-07-24 04:52:15'),
(65, 'New Order Approved: MKT-20260724-001', 'Order for Supreme (Rajkot) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-24 04:52:17', NULL, '{\"order_id\": 14, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 04:52:17', '2026-07-24 04:52:17'),
(66, 'New Order Approved: MKT-20260724-001', 'Order for Supreme (Rajkot) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-24 04:52:17', '2026-07-28 04:36:50', '{\"order_id\": 14, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 04:52:17', '2026-07-28 04:36:50'),
(67, 'New Dispatch Created: DISP-20260724-001', 'New Factory Pickup created for Supreme. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 2, 'sent', '2026-07-24 04:52:39', '2026-07-24 04:53:42', '{\"click_url\": \"/dispatch/8\", \"dispatch_id\": 8}', '2026-07-24 04:52:39', '2026-07-24 04:53:42'),
(68, 'New Dispatch Created: DISP-20260724-001', 'New Factory Pickup created for Supreme. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-24 04:52:39', NULL, '{\"click_url\": \"/dispatch/8\", \"dispatch_id\": 8}', '2026-07-24 04:52:39', '2026-07-24 04:52:39'),
(69, 'New Dispatch Created: DISP-20260724-001', 'New Factory Pickup created for Supreme. Vehicle: Factory Pickup / Direct. Expected: TBD', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-24 04:52:39', NULL, '{\"click_url\": \"/dispatch/8\", \"dispatch_id\": 8}', '2026-07-24 04:52:39', '2026-07-24 04:52:39'),
(70, 'New Order Approved: MKT-20260724-002', 'Order for SPIDERMAN (UK) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-24 10:49:01', NULL, '{\"order_id\": 15, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 10:49:01', '2026-07-24 10:49:01'),
(71, 'New Order Approved: MKT-20260724-002', 'Order for SPIDERMAN (UK) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-24 10:49:02', NULL, '{\"order_id\": 15, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 10:49:02', '2026-07-24 10:49:02'),
(72, 'New Order Approved: MKT-20260724-002', 'Order for SPIDERMAN (UK) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-24 10:49:02', '2026-07-28 04:36:50', '{\"order_id\": 15, \"click_url\": \"/supervisor/orders\"}', '2026-07-24 10:49:02', '2026-07-28 04:36:50'),
(73, 'New Order Approved: MKT-20260725-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-25 04:18:35', NULL, '{\"order_id\": 16, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 04:18:35', '2026-07-25 04:18:35'),
(74, 'New Order Approved: MKT-20260725-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-25 04:18:38', NULL, '{\"order_id\": 16, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 04:18:38', '2026-07-25 04:18:38'),
(75, 'New Order Approved: MKT-20260725-001', 'Order for jatin bhai (Morbi) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-25 04:18:38', '2026-07-28 04:36:50', '{\"order_id\": 16, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 04:18:38', '2026-07-28 04:36:50'),
(76, 'New Order Approved: MKT-20260725-001', 'Order for Madhuram Marble (MORBI) has been approved. Priority: High', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-25 11:43:55', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 11:43:55', '2026-07-25 11:43:55'),
(77, 'New Order Approved: MKT-20260725-001', 'Order for Madhuram Marble (MORBI) has been approved. Priority: High', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-25 11:43:57', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 11:43:57', '2026-07-25 11:43:57'),
(78, 'New Order Approved: MKT-20260725-001', 'Order for Madhuram Marble (MORBI) has been approved. Priority: High', 'marketing_order_approved', NULL, 6, 'no_device_registered', '2026-07-25 11:43:57', '2026-07-28 04:36:50', '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-25 11:43:57', '2026-07-28 04:36:50'),
(79, 'New Dispatch Created: DISP-20260725-001', 'New Factory Pickup created for Madhuram Marble. Vehicle: GJ-36-12-4031. Expected: 25 Jul 2026 05:38 PM', 'dispatch_created', NULL, 2, 'sent', '2026-07-25 12:08:34', '2026-07-28 04:48:08', '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-25 12:08:34', '2026-07-28 04:48:08'),
(80, 'New Dispatch Created: DISP-20260725-001', 'New Factory Pickup created for Madhuram Marble. Vehicle: GJ-36-12-4031. Expected: 25 Jul 2026 05:38 PM', 'dispatch_created', NULL, 3, 'no_device_registered', '2026-07-25 12:08:34', NULL, '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-25 12:08:34', '2026-07-25 12:08:34'),
(81, 'New Dispatch Created: DISP-20260725-001', 'New Factory Pickup created for Madhuram Marble. Vehicle: GJ-36-12-4031. Expected: 25 Jul 2026 05:38 PM', 'dispatch_created', NULL, 7, 'no_device_registered', '2026-07-25 12:08:34', NULL, '{\"click_url\": \"/dispatch/1\", \"dispatch_id\": 1}', '2026-07-25 12:08:34', '2026-07-25 12:08:34'),
(82, 'New Task Assigned', 'Admin has assigned you: Tommorow Planing', 'todo_assigned', 2, 4, 'no_device_registered', '2026-07-25 12:10:42', NULL, '{\"click_action\": \"http://127.0.0.1:8080/supervisor/dashboard\"}', '2026-07-25 12:10:42', '2026-07-25 12:10:42'),
(83, 'New Order Approved: MKT-20260728-001', 'Order for Roman (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-28 12:03:58', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:03:58', '2026-07-28 12:03:58'),
(84, 'New Order Approved: MKT-20260728-001', 'Order for Roman (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-28 12:04:00', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:00', '2026-07-28 12:04:00'),
(85, 'New Order Approved: MKT-20260728-001', 'Order for Roman (Morbi) has been approved. Priority: Urgent', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-28 12:04:01', NULL, '{\"order_id\": 1, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:01', '2026-07-28 12:04:01'),
(86, 'New Order Approved: MKT-20260728-006', 'Order for Aniket (Sampat) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-28 12:04:06', NULL, '{\"order_id\": 6, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:06', '2026-07-28 12:04:06'),
(87, 'New Order Approved: MKT-20260728-006', 'Order for Aniket (Sampat) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-28 12:04:07', NULL, '{\"order_id\": 6, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:07', '2026-07-28 12:04:07'),
(88, 'New Order Approved: MKT-20260728-006', 'Order for Aniket (Sampat) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-28 12:04:07', NULL, '{\"order_id\": 6, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:07', '2026-07-28 12:04:07'),
(89, 'New Order Approved: MKT-20260728-008', 'Order for Vipul Sanitary (Gondal) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-28 12:04:12', NULL, '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:12', '2026-07-28 12:04:12'),
(90, 'New Order Approved: MKT-20260728-008', 'Order for Vipul Sanitary (Gondal) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-28 12:04:12', NULL, '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:12', '2026-07-28 12:04:12'),
(91, 'New Order Approved: MKT-20260728-008', 'Order for Vipul Sanitary (Gondal) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-28 12:04:13', NULL, '{\"order_id\": 8, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:04:13', '2026-07-28 12:04:13'),
(92, 'New Order Approved: MKT-20260728-002', 'Order for National Tiles (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-28 12:14:32', NULL, '{\"order_id\": 2, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:14:32', '2026-07-28 12:14:32'),
(93, 'New Order Approved: MKT-20260728-002', 'Order for National Tiles (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-28 12:14:33', NULL, '{\"order_id\": 2, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:14:33', '2026-07-28 12:14:33'),
(94, 'New Order Approved: MKT-20260728-002', 'Order for National Tiles (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-28 12:14:33', NULL, '{\"order_id\": 2, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:14:33', '2026-07-28 12:14:33'),
(95, 'New Order Approved: MKT-20260728-003', 'Order for Khushi Traders (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-28 12:15:06', NULL, '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:15:06', '2026-07-28 12:15:06'),
(96, 'New Order Approved: MKT-20260728-003', 'Order for Khushi Traders (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-28 12:15:06', NULL, '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:15:06', '2026-07-28 12:15:06'),
(97, 'New Order Approved: MKT-20260728-003', 'Order for Khushi Traders (Bhabhra) has been approved. Priority: High', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-28 12:15:07', NULL, '{\"order_id\": 3, \"click_url\": \"/supervisor/orders\"}', '2026-07-28 12:15:07', '2026-07-28 12:15:07'),
(98, 'New Order Approved: MKT-20260728-004', 'Order for Advgith Enterprise (Mancherial) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 4, 'no_device_registered', '2026-07-29 14:02:39', NULL, '{\"order_id\": 4, \"click_url\": \"/supervisor/orders\"}', '2026-07-29 14:02:39', '2026-07-29 14:02:39'),
(99, 'New Order Approved: MKT-20260728-004', 'Order for Advgith Enterprise (Mancherial) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 5, 'sent', '2026-07-29 14:02:42', NULL, '{\"order_id\": 4, \"click_url\": \"/supervisor/orders\"}', '2026-07-29 14:02:42', '2026-07-29 14:02:42'),
(100, 'New Order Approved: MKT-20260728-004', 'Order for Advgith Enterprise (Mancherial) has been approved. Priority: Medium', 'marketing_order_approved', NULL, 6, 'sent', '2026-07-29 14:02:43', NULL, '{\"order_id\": 4, \"click_url\": \"/supervisor/orders\"}', '2026-07-29 14:02:43', '2026-07-29 14:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `packing_materials`
--

DROP TABLE IF EXISTS `packing_materials`;
CREATE TABLE IF NOT EXISTS `packing_materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `minimum_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `opening_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `current_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `packing_materials_category_id_foreign` (`category_id`),
  KEY `packing_materials_unit_id_foreign` (`unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packing_materials`
--

INSERT INTO `packing_materials` (`id`, `category_id`, `name`, `code`, `size`, `unit_id`, `minimum_stock`, `opening_stock`, `current_stock`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'F101 Bag', 'BAG-F101', NULL, 3, 2000.0000, 12000.0000, 29000.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-26 06:54:40'),
(2, 1, 'F107 Bag', 'BAG-F107', NULL, 3, 2000.0000, 12000.0000, 71000.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-26 06:55:45'),
(3, 1, 'F121 Bag', 'BAG-F121', NULL, 3, 2000.0000, 12000.0000, 8500.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-26 06:58:14'),
(4, 1, 'F115 Bag', 'BAG-F115', NULL, 3, 2000.0000, 12000.0000, 7500.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-26 06:57:30'),
(5, 1, 'F133 Bag', 'BAG-F133', NULL, 3, 2000.0000, 12000.0000, 6500.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-26 06:58:46'),
(6, 1, 'B01 Bag', 'BAG-B01', NULL, 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(7, 2, 'Grout 1Kg Pouch', 'PCH-GRT-1KG', '1Kg', 3, 5000.0000, 20000.0000, 20000.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(8, 2, 'Grout 500gm Pouch', 'PCH-GRT-500G', '500gm', 3, 5000.0000, 20000.0000, 20000.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(9, 2, 'Filler Pouch', 'PCH-FLR', NULL, 3, 50.0000, 150.0000, 49950.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(10, 2, 'Spacer Pouch 2mm', 'PCH-SPC-2MM', '2mm', 3, 0.0000, 0.0000, 900.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-28 12:35:41'),
(11, 2, 'Spacer Pouch 3mm', 'PCH-SPC-3MM', '3mm', 3, 0.0000, 0.0000, 2500.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-28 12:36:24'),
(12, 2, 'Spacer Pouch 4mm', 'PCH-SPC-4MM', '4mm', 3, 0.0000, 0.0000, 2800.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-28 12:37:23'),
(13, 2, 'Spacer Pouch 5mm', 'PCH-SPC-5MM', '5mm', 3, 0.0000, 0.0000, 600.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-28 12:37:50'),
(14, 2, 'Clip Pouch 2mm', 'PCH-CLP-2MM', '2mm', 3, 0.0000, 0.0000, 325.0000, '', 'active', '2026-07-23 07:07:43', '2026-07-28 06:53:04'),
(15, 2, 'Clip Pouch 3mm', 'PCH-CLP-3MM', '3mm', 3, 0.0000, 0.0000, 200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:53:46'),
(16, 2, 'Clip Pouch 4mm', 'PCH-CLP-4MM', '4mm', 3, 0.0000, 0.0000, 450.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:54:15'),
(17, 2, 'Wedge Pouch', 'PCH-WDG', NULL, 3, 0.0000, 0.0000, 25.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:42:38'),
(18, 2, 'Jari Pouch', 'PCH-JRI', NULL, 3, 0.0000, 0.0000, 58400.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:39:36'),
(19, 3, '1Kg Bucket', 'BKT-1KG', '1Kg', 3, 2000.0000, 10000.0000, 4200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:48:54'),
(20, 3, '5Kg Bucket', 'BKT-5KG', '5Kg', 3, 2000.0000, 10000.0000, 380.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:50:12'),
(21, 4, '100gm Bottle', 'BTL-100G', '100gm', 3, 50.0000, 150.0000, 2100.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:47:37'),
(22, 4, '200gm Bottle', 'BTL-200G', '200gm', 3, 50.0000, 1000.0000, 4440.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-29 04:27:25'),
(23, 4, '500gm Bottle', 'BTL-500G', '500gm', 3, 50.0000, 150.0000, 180.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:49:40'),
(24, 4, '1Kg Bottle', 'BTL-1KG', '1Kg', 3, 50.0000, 150.0000, 400.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:48:09'),
(25, 4, 'Tile Power 1L Bottle', 'BTL-TP-1L', '1L', 3, 0.0000, 0.0000, 1100.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:40:16'),
(26, 4, 'Tile Power 5L Bottle', 'BTL-TP-5L', '5L', 3, 0.0000, 0.0000, 280.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:40:46'),
(27, 4, 'SBR 1L Bottle', 'BTL-SBR-1L', '1L', 3, 0.0000, 0.0000, 200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:31:27'),
(28, 4, 'SBR 5L Bottle', 'BTL-SBR-5L', '5L', 3, 0.0000, 0.0000, 230.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:32:05'),
(29, 5, '100gm Sticker', 'STK-100G', '100gm', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(30, 5, '200gm Sticker', 'STK-200G', '200gm', 3, 0.0000, 0.0000, 5000.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:52:45'),
(31, 5, '500gm Sticker', 'STK-500G', '500gm', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(32, 5, '1Kg Sticker', 'STK-1KG', '1Kg', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(33, 5, 'Tile Power 1L Sticker', 'STK-TP-1L', '1L', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(34, 5, 'Tile Power 5L Sticker', 'STK-TP-5L', '5L', 3, 0.0000, 0.0000, 4840.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-29 14:01:26'),
(35, 5, 'Grout Admix Sticker', 'STK-GA', NULL, 3, 0.0000, 0.0000, 49440.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-29 04:27:25'),
(36, 5, 'Soltite 1.8Kg Sticker', 'STK-SLT-1.8KG', '1.8Kg', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(37, 5, 'Soltite 900gm Sticker', 'STK-SLT-900G', '900gm', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(38, 5, 'Soltite 450gm Sticker', 'STK-SLT-450G', '450gm', 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(39, 6, 'Grout Admix Box', 'BOX-GA', NULL, 3, 0.0000, 0.0000, 174.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-29 04:27:25'),
(40, 6, 'Sample Box', 'BOX-SMP', NULL, 3, 0.0000, 0.0000, 0.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-23 07:07:44'),
(41, 6, 'Tile Cleaner 1L Box', 'BOX-TC-1L', '1L', 3, 0.0000, 0.0000, 400.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:38:44'),
(42, 6, 'Tile Cleaner 5L Box', 'BOX-TC-5L', '5L', 3, 0.0000, 0.0000, 270.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-29 14:01:26'),
(43, 6, 'Epoxy 1Kg Box', 'BOX-EPX-1KG', '1Kg', 3, 0.0000, 0.0000, 300.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:55:03'),
(44, 6, 'Epoxy 5Kg Box', 'BOX-EPX-5KG', '5Kg', 3, 0.0000, 0.0000, 450.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:56:02'),
(45, 6, 'Small Grout Box', 'BOX-GRT-SM', NULL, 3, 0.0000, 0.0000, 380.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:33:30'),
(46, 6, 'Big Grout Box', 'BOX-GRT-BG', NULL, 3, 0.0000, 0.0000, 360.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:51:15'),
(47, 6, 'Jari Box', 'BOX-JRI', NULL, 3, 0.0000, 0.0000, 100.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:39:36'),
(48, 6, 'Soltite 1.8Kg Box', 'BOX-SLT-1.8KG', '1.8Kg', 3, 0.0000, 0.0000, 1200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:33:48'),
(49, 6, 'Soltite 900gm Box', 'BOX-SLT-900G', '900gm', 3, 0.0000, 0.0000, 1200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:34:29'),
(50, 6, 'Soltite 450gm Box', 'BOX-SLT-450G', '450gm', 3, 0.0000, 0.0000, 150.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:34:06'),
(51, 7, 'Acid Barrel', 'BRL-ACD', NULL, 3, 0.0000, 0.0000, 8.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:50:30'),
(52, 7, 'SBR Barrel 200 KG', 'BRL-SBR-50L', '200 KG', 3, 0.0000, 0.0000, 225.0000, NULL, 'active', '2026-07-23 07:07:44', '2026-07-28 12:33:23'),
(53, 8, 'Sponge', 'ACC-SPG', NULL, 3, 50.0000, 150.0000, 9150.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:38:17'),
(54, 8, 'Blade', 'ACC-BLD', NULL, 3, 50.0000, 150.0000, 1500.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 06:52:15'),
(55, 8, 'Hand Gloves', 'ACC-GLV', NULL, 3, 50.0000, 150.0000, 11200.0000, '', 'active', '2026-07-23 07:07:44', '2026-07-28 12:18:33'),
(56, 5, 'JACK LEVELER', 'JL-01', '1', 3, 0.0000, 1000.0000, 25.0000, NULL, 'active', '2026-07-24 06:02:31', '2026-07-28 12:26:34'),
(57, 6, 'PLASTIC BOX', 'BOX-P', '0.100', 3, 10.0000, 100.0000, 90.0000, NULL, 'active', '2026-07-24 07:00:37', '2026-07-25 12:03:16'),
(58, 2, 'Jack Leveller', 'JKL', '50PCS', 3, 10.0000, 500.0000, 0.0000, NULL, 'active', '2026-07-24 07:03:12', '2026-07-25 12:03:16'),
(59, 8, 'Trowel', 'THR', '0.100', 3, 100.0000, 200.0000, 150.0000, NULL, 'active', '2026-07-25 04:20:58', '2026-07-25 04:21:56'),
(60, 2, 'Spacer Pouch 6mm', 'PCH-SPC-6MM', '5mm', 3, 100.0000, 5000.0000, 4000.0000, NULL, 'active', '2026-07-25 11:52:35', '2026-07-25 11:57:26'),
(61, 8, 'PLIER', 'PLIER', '0.100', 3, 100.0000, 2000.0000, 25.0000, NULL, 'active', '2026-07-25 12:05:43', '2026-07-28 12:30:55'),
(62, 8, 'VACUUM', 'VAC', '1KG', 3, 100.0000, 7.0000, 0.0000, NULL, 'active', '2026-07-28 12:18:15', '2026-07-29 14:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `packing_material_categories`
--

DROP TABLE IF EXISTS `packing_material_categories`;
CREATE TABLE IF NOT EXISTS `packing_material_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packing_material_categories_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packing_material_categories`
--

INSERT INTO `packing_material_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Adhesive Bags', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(2, 'Pouches', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(3, 'Buckets', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(4, 'Bottles', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(5, 'Stickers', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(6, 'Boxes / Cartons', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(7, 'Barrels', '2026-07-23 07:07:43', '2026-07-23 07:07:43'),
(8, 'Epoxy Accessories', '2026-07-23 07:07:43', '2026-07-23 07:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Manage Masters', 'manage-masters', 'Create and edit departments, machines, units, raw materials and grades', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(2, 'Manage Formulas', 'manage-formulas', 'Define formulas for grades', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(3, 'Log Production', 'log-production', 'Start, track and complete production batches', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(4, 'View Reports', 'view-reports', 'Generate and view production reports', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(5, 'Manage Users', 'manage-users', 'Manage user accounts and permissions', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(6, 'Manage Settings', 'manage-settings', 'Manage global factory settings', '2026-07-12 10:41:55', '2026-07-12 10:41:55');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`permission_id`, `role_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(3, 3),
(4, 2),
(4, 3),
(5, 2),
(6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `production_batches`
--

DROP TABLE IF EXISTS `production_batches`;
CREATE TABLE IF NOT EXISTS `production_batches` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `machine_id` bigint UNSIGNED NOT NULL,
  `grade_id` bigint UNSIGNED NOT NULL,
  `formula_id` bigint UNSIGNED NOT NULL,
  `formula_snapshot` json DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `output_bags` decimal(12,4) DEFAULT NULL,
  `output_kg` decimal(12,4) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'running',
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `production_batches_batch_no_unique` (`batch_no`),
  KEY `production_batches_machine_id_foreign` (`machine_id`),
  KEY `production_batches_grade_id_foreign` (`grade_id`),
  KEY `production_batches_formula_id_foreign` (`formula_id`),
  KEY `production_batches_supervisor_id_foreign` (`supervisor_id`),
  KEY `production_batches_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_batches`
--

INSERT INTO `production_batches` (`id`, `batch_no`, `machine_id`, `grade_id`, `formula_id`, `formula_snapshot`, `supervisor_id`, `start_time`, `end_time`, `output_bags`, `output_kg`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'ADH-20260713-0001', 4, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 2, '2026-07-13 09:40:52', '2026-07-13 09:40:00', 98.0000, 1960.0000, 'completed', NULL, '2026-07-13 04:10:52', '2026-07-13 04:11:06'),
(2, 'ADH-20260713-0002', 4, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 7, \"raw_material_code\": \"RS-10\", \"raw_material_name\": \"RS-10 Solcon\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}]', 2, '2026-07-13 09:58:25', '2026-07-13 09:58:00', 100.0000, 2000.0000, 'completed', NULL, '2026-07-13 04:28:25', '2026-07-13 04:28:46'),
(3, 'ADH-20260713-0003', 4, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 2, '2026-07-13 14:54:02', '2026-07-13 14:54:00', 100.0000, 2000.0000, 'completed', NULL, '2026-07-13 09:24:02', '2026-07-13 09:24:18'),
(4, 'ADH-20260714-0001', 5, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:23:14', '2026-07-14 12:23:00', 97.0000, 1940.0000, 'completed', NULL, '2026-07-14 06:53:14', '2026-07-14 06:53:25'),
(5, 'ADH-20260714-0002', 5, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:23:57', '2026-07-14 12:24:00', 107.0000, 2140.0000, 'completed', NULL, '2026-07-14 06:53:57', '2026-07-14 06:54:08'),
(6, 'ADH-20260714-0003', 5, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:24:15', '2026-07-14 12:24:00', 104.0000, 2080.0000, 'completed', NULL, '2026-07-14 06:54:15', '2026-07-14 06:54:24'),
(7, 'ADH-20260714-0004', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:24:40', '2026-07-14 12:24:00', 101.0000, 2020.0000, 'completed', NULL, '2026-07-14 06:54:40', '2026-07-14 06:54:51'),
(8, 'ADH-20260714-0005', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:25:01', '2026-07-14 12:25:00', 106.0000, 2120.0000, 'completed', NULL, '2026-07-14 06:55:01', '2026-07-14 06:55:11'),
(9, 'ADH-20260714-0006', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:25:21', '2026-07-14 12:25:00', 106.0000, 2120.0000, 'completed', NULL, '2026-07-14 06:55:21', '2026-07-14 06:55:28'),
(10, 'ADH-20260714-0007', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 10, \"raw_material_code\": \"RS-40\", \"raw_material_name\": \"RS-40 Solcon\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:25:42', '2026-07-14 12:25:00', 108.0000, 2160.0000, 'completed', NULL, '2026-07-14 06:55:42', '2026-07-14 06:55:58'),
(11, 'ADH-20260714-0008', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 10, \"raw_material_code\": \"RS-40\", \"raw_material_name\": \"RS-40 Solcon\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:26:12', '2026-07-14 12:26:00', 108.0000, 2160.0000, 'completed', NULL, '2026-07-14 06:56:12', '2026-07-14 06:56:18'),
(12, 'ADH-20260714-0009', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 10, \"raw_material_code\": \"RS-40\", \"raw_material_name\": \"RS-40 Solcon\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:26:36', '2026-07-14 12:26:00', 106.0000, 2120.0000, 'completed', NULL, '2026-07-14 06:56:36', '2026-07-14 06:56:45'),
(13, 'ADH-20260714-0010', 5, 2, 2, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 2, \"raw_material_code\": \"F-107\", \"raw_material_name\": \"Empty Bag F-107\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 600, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 10, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:26:56', '2026-07-14 12:27:00', 107.0000, 2140.0000, 'completed', NULL, '2026-07-14 06:56:56', '2026-07-14 06:57:08'),
(14, 'ADH-20260714-0011', 6, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:30:11', '2026-07-14 12:30:00', 162.0000, 3240.0000, 'completed', NULL, '2026-07-14 07:00:11', '2026-07-14 07:00:16'),
(15, 'ADH-20260714-0012', 6, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:30:27', '2026-07-14 12:30:00', 102.0000, 2040.0000, 'completed', NULL, '2026-07-14 07:00:27', '2026-07-14 07:00:36'),
(16, 'ADH-20260714-0013', 6, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:30:46', '2026-07-14 12:30:00', 102.0000, 2040.0000, 'completed', NULL, '2026-07-14 07:00:46', '2026-07-14 07:00:56'),
(17, 'ADH-20260714-0014', 6, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:31:08', '2026-07-14 12:31:00', 103.0000, 2060.0000, 'completed', NULL, '2026-07-14 07:01:08', '2026-07-14 07:01:16'),
(18, 'ADH-20260714-0015', 6, 3, 3, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 3, \"raw_material_code\": \"F-121\", \"raw_material_name\": \"Empty Bag F-121\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 700, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 28, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 6, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 2, \"unit_code\": \"KG\", \"raw_material_id\": 19, \"raw_material_code\": \"CF\", \"raw_material_name\": \"Calcium Formate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:31:38', '2026-07-14 12:31:00', 115.0000, 2300.0000, 'completed', NULL, '2026-07-14 07:01:38', '2026-07-14 07:01:47'),
(19, 'ADH-20260714-0016', 4, 3, 3, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 3, \"raw_material_code\": \"F-121\", \"raw_material_name\": \"Empty Bag F-121\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 700, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 28, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 6, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 2, \"unit_code\": \"KG\", \"raw_material_id\": 19, \"raw_material_code\": \"CF\", \"raw_material_name\": \"Calcium Formate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 4, '2026-07-14 12:31:57', '2026-07-14 12:32:00', 112.0000, 2240.0000, 'completed', NULL, '2026-07-14 07:01:57', '2026-07-14 07:02:07'),
(20, 'ADH-20260722-0001', 4, 1, 1, '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]', 2, '2026-07-22 16:54:19', '2026-07-22 16:54:00', 98.0000, 1960.0000, 'completed', NULL, '2026-07-22 11:24:19', '2026-07-22 11:24:51');

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

DROP TABLE IF EXISTS `raw_materials`;
CREATE TABLE IF NOT EXISTS `raw_materials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `stock_unit_id` bigint UNSIGNED NOT NULL,
  `purchase_unit_id` bigint UNSIGNED NOT NULL,
  `purchase_conversion` decimal(12,4) NOT NULL DEFAULT '1.0000',
  `opening_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `current_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `minimum_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `maximum_stock` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_coupon` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `raw_materials_code_unique` (`code`),
  KEY `raw_materials_department_id_foreign` (`department_id`),
  KEY `raw_materials_stock_unit_id_foreign` (`stock_unit_id`),
  KEY `raw_materials_purchase_unit_id_foreign` (`purchase_unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `code`, `department_id`, `stock_unit_id`, `purchase_unit_id`, `purchase_conversion`, `opening_stock`, `current_stock`, `minimum_stock`, `maximum_stock`, `description`, `is_active`, `is_coupon`, `created_at`, `updated_at`) VALUES
(6, 'Empty Bag F-147', 'F-147', 2, 3, 3, 1.0000, 12000.0000, 12000.0000, 2000.0000, 20000.0000, '', 1, 0, '2026-07-12 11:05:03', '2026-07-12 11:05:03'),
(7, 'RS-10 Solcon', 'RS-10', 2, 3, 3, 1.0000, 3000.0000, 2900.0000, 500.0000, 5000.0000, '', 1, 1, '2026-07-12 11:24:04', '2026-07-13 04:28:46'),
(8, 'RS-20 Solcon', 'RS-20', 2, 3, 3, 1.0000, 3000.0000, 3000.0000, 500.0000, 5000.0000, '', 1, 1, '2026-07-12 11:24:04', '2026-07-12 11:24:04'),
(9, 'RS-30 Solcon', 'RS-30', 2, 3, 3, 1.0000, 3000.0000, 3000.0000, 500.0000, 5000.0000, '', 1, 1, '2026-07-12 11:24:04', '2026-07-12 11:24:04'),
(10, 'RS-40 Solcon', 'RS-40', 2, 3, 3, 1.0000, 3000.0000, 2678.0000, 500.0000, 5000.0000, '', 1, 1, '2026-07-12 11:24:04', '2026-07-14 06:56:45'),
(11, 'RS-50 Solcon', 'RS-50', 2, 3, 3, 1.0000, 3000.0000, 3000.0000, 500.0000, 5000.0000, '', 1, 1, '2026-07-12 11:24:04', '2026-07-12 11:24:04'),
(12, 'Silica', 'SL', 2, 4, 4, 1.0000, 350000.0000, 1294124.8500, 50000.0000, 400000.0000, NULL, 1, 0, '2026-07-12 11:26:25', '2026-07-28 04:53:25'),
(13, 'Gray Cement', 'GRY-01', 2, 1, 1, 1.0000, 50000.0000, 86527.8100, 10000.0000, 60000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-26 05:34:42'),
(14, 'White Cement', 'WHT-01', 2, 1, 1, 1.0000, 14500.0000, 750.0000, 5000.0000, 20000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-28 04:18:52'),
(15, 'Calcium Carbonate', 'C.C', 2, 1, 1, 1.0000, 50000.0000, 46000.0000, 20000.0000, 60000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-22 11:24:51'),
(16, 'Dolomite', 'DL', 2, 1, 1, 1.0000, 20000.0000, 14477.5662, 5000.0000, 50000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-14 03:02:37'),
(17, 'RDP 5010N', 'RDP-N', 2, 1, 1, 1.0000, 4000.0000, 1750.0000, 300.0000, 5000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-26 05:41:46'),
(18, 'MHEC', 'MHEC', 2, 1, 1, 1.0000, 4000.0000, 750.0000, 300.0000, 5000.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-26 05:40:07'),
(19, 'Calcium Formate', 'CF', 2, 1, 1, 1.0000, 2000.0000, 2542.0000, 300.0000, 3000.0000, NULL, 1, 0, '2026-07-12 11:26:25', '2026-07-26 05:43:48'),
(20, 'RDP 8620', 'RDP-E', 2, 1, 1, 1.0000, 100.0000, 100.0000, 50.0000, 200.0000, '', 1, 0, '2026-07-12 11:26:25', '2026-07-12 11:26:25'),
(21, 'Starch Ether', 'SE', 2, 1, 1, 1.0000, 100.0000, 100.0000, 50.0000, 200.0000, NULL, 1, 0, '2026-07-12 11:34:52', '2026-07-12 11:34:52'),
(22, 'Prigment Color Black', 'PMT-01', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(23, 'Prigment Color Red 130', 'PMT-02', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(24, 'Prigment Color Red 110', 'PMT-03', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(25, 'Prigment Color Blue', 'PMT-04', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(26, 'Prigment Color Green', 'PMT-05', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(28, 'Prigment Color Yellow', 'PMT-07', 1, 1, 1, 1.0000, 150.0000, 142.0668, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 18:08:35'),
(29, 'Prigment Color Orange', 'PMT-08', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(30, 'Prigment Color Alphine', 'PMT-09', 1, 1, 1, 1.0000, 150.0000, 150.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 04:03:05', '2026-07-13 04:03:05'),
(33, 'Empty Bucket 1KG', 'EXP-01', 3, 3, 3, 1.0000, 150.0000, 4200.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 08:38:47', '2026-07-28 06:41:51'),
(34, 'Empty Bucket 5KG', 'EXP-02', 3, 3, 3, 1.0000, 150.0000, 380.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 08:38:47', '2026-07-28 06:42:40'),
(90, 'Bulk - Jari Powder - Copper', 'CJC', 3, 1, 1, 1.0000, 50.0000, 30.0000, 10.0000, 50.0000, NULL, 1, 0, '2026-07-28 12:27:14', '2026-07-28 12:39:36'),
(89, 'Bulk - Jari Powder - Red', 'BJR', 3, 1, 1, 1.0000, 50.0000, 30.0000, 10.0000, 50.0000, NULL, 1, 0, '2026-07-28 12:26:42', '2026-07-28 12:39:36'),
(88, 'Bulk - Jari Powder - Gold', 'BJG', 3, 1, 1, 1.0000, 50.0000, 30.0000, 10.0000, 50.0000, NULL, 1, 0, '2026-07-28 12:26:12', '2026-07-28 12:39:36'),
(87, 'Bulk - Jari Powder - Sliver', 'BJS', 3, 1, 1, 1.0000, 50.0000, 30.0000, 10.0000, 50.0000, NULL, 1, 0, '2026-07-28 12:24:33', '2026-07-28 12:39:36'),
(40, 'Empty Sprakle Pouch', 'EXP-08', 3, 3, 3, 1.0000, 150.0000, 60000.0000, 50.0000, 150.0000, '', 1, 0, '2026-07-13 08:38:47', '2026-07-28 06:44:50'),
(86, '1 KG RESIN BOTTLE', 'REN-1K', 3, 3, 3, 1.0000, 0.0000, 271.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-28 05:29:47', '2026-07-28 12:19:20'),
(85, '500 GM HARDNER BOTTLE', 'HRD-500', 3, 3, 3, 1.0000, 0.0000, 341.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-28 05:29:07', '2026-07-28 12:20:49'),
(44, 'Bulk Epoxy Bag-Black', 'EXP-12', 3, 1, 1, 1.0000, 1000.0000, 1625.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 08:38:47', '2026-07-28 04:56:48'),
(45, '700gm Black Filler Pouch', 'EPX-BLK', 3, 3, 3, 1.0000, 0.0000, 1735.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-13 08:49:04', '2026-07-28 04:42:02'),
(46, 'Bulk Epoxy Bag-White', 'EXP-13', 3, 1, 1, 1.0000, 1000.0000, 375.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:13:19'),
(47, 'Bulk Epoxy Bag-Ivory', 'EXP-14', 3, 1, 1, 1.0000, 1000.0000, 250.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:04:23'),
(48, 'Bulk Epoxy Bag-Parchment', 'EXP-15', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:08:28'),
(49, 'Bulk Epoxy Bag-Jaisalmer', 'EXP-16', 3, 1, 1, 1.0000, 1000.0000, 100.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:05:44'),
(50, 'Bulk Epoxy Bag-Dusty-Rose', 'EXP-17', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:00:27'),
(51, 'Bulk Epoxy Bag-Buff', 'EXP-18', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:57:35'),
(52, 'Bulk Epoxy Bag-CO.Brown', 'EXP-19', 3, 1, 1, 1.0000, 1000.0000, 575.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:59:46'),
(53, 'Bulk Epoxy Bag-Choco.Brown', 'EXP-20', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:58:54'),
(54, 'Bulk Epoxy Bag-Mocha', 'EXP-21', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:07:43'),
(55, 'Bulk Epoxy Bag-Sterling-Sliver', 'EXP-22', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:12:01'),
(56, 'Bulk Epoxy Bag-Hemp', 'EXP-23', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:02:31'),
(57, 'Bulk Epoxy Bag-Marble-Beige', 'EXP-24', 3, 1, 1, 1.0000, 1000.0000, 25.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:07:17'),
(58, 'Bulk Epoxy Bag-Sauterne', 'EXP-25', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:09:50'),
(59, 'Bulk Epoxy Bag-Smoke-Gray', 'EXP-26', 3, 1, 1, 1.0000, 1000.0000, 200.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:11:38'),
(60, 'Bulk Epoxy Bag-Silver-Shadow', 'EXP-27', 3, 1, 1, 1.0000, 1000.0000, 150.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:10:22'),
(61, 'Bulk Epoxy Bag-Slate-Gray', 'EXP-28', 3, 1, 1, 1.0000, 1000.0000, 250.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:11:13'),
(62, 'Bulk Epoxy Bag-Natural-Grey', 'EXP-29', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:08:02'),
(63, 'Bulk Epoxy Bag-Platium', 'EXP-30', 3, 1, 1, 1.0000, 1000.0000, 100.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:09:19'),
(64, 'Bulk Epoxy Bag-Terracotta', 'EXP-31', 3, 1, 1, 1.0000, 1000.0000, 250.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:12:20'),
(65, 'Bulk Epoxy Bag-Saltilo', 'EXP-32', 3, 1, 1, 1.0000, 1000.0000, 100.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:09:36'),
(66, 'Bulk Epoxy Bag-Cadium-Red', 'EXP-33', 3, 1, 1, 1.0000, 1000.0000, 250.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:58:13'),
(67, 'Bulk Epoxy Bag- Orange', 'EXP-34', 3, 1, 1, 1.0000, 1000.0000, 125.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:55:27'),
(68, 'Bulk Epoxy Bag-Light-Grey', 'EXP-35', 3, 1, 1, 1.0000, 1000.0000, 425.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:06:53'),
(69, 'Bulk Epoxy Bag-IncaGold', 'EXP-36', 3, 1, 1, 1.0000, 1000.0000, 1000.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-13 10:21:52'),
(70, 'Bulk Epoxy Bag-Blue', 'EXP-37', 3, 1, 1, 1.0000, 1000.0000, 75.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 04:57:17'),
(71, 'Bulk Epoxy Bag-Ivy', 'EXP-38', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:05:15'),
(72, 'Bulk Epoxy Bag-Light-Green', 'EXP-39', 3, 1, 1, 1.0000, 1000.0000, 25.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:06:12'),
(73, 'Bulk Epoxy Bag-Sky Blue', 'EXP-40', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:10:51'),
(74, 'Bulk Epoxy Bag-Vilote', 'EXP-41', 3, 1, 1, 1.0000, 1000.0000, 50.0000, 100.0000, 1500.0000, '', 1, 0, '2026-07-13 10:21:52', '2026-07-28 05:12:36'),
(84, 'SPACER 3MM', 'EPX-SP-3MM', 3, 3, 3, 1.0000, 0.0000, 2450.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-24 04:02:31', '2026-07-28 06:47:01'),
(83, 'SPACER 2MM', 'EPX-SP-2MM', 3, 3, 3, 1.0000, 0.0000, 900.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-24 04:01:35', '2026-07-28 06:46:21'),
(77, '700gm White Filler Pouch', 'EPX-WHT', 3, 3, 3, 1.0000, 0.0000, 375.0000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-13 10:55:58', '2026-07-28 04:42:51'),
(78, 'Custom 10rs', 'CUSTOM-10RS', 2, 3, 3, 1.0000, 0.0000, 999999.0000, 0.0000, 0.0000, NULL, 1, 1, '2026-07-21 15:10:47', '2026-07-21 15:10:47'),
(79, '100 GM HARDNER BOTTLE', 'EPX-BLT-01', 3, 3, 3, 1.0000, 0.0000, 2594.2000, 0.0000, 0.0000, NULL, 1, 0, '2026-07-22 11:33:42', '2026-07-29 14:04:43'),
(80, 'Resin', 'r-01', 3, 1, 1, 1.0000, 500.0000, 796.4000, 100.0000, 500.0000, NULL, 1, 0, '2026-07-22 11:35:08', '2026-07-29 14:04:34'),
(81, 'Hardner', 'h-01', 3, 1, 1, 1.0000, 500.0000, 90.0000, 100.0000, 500.0000, NULL, 1, 0, '2026-07-22 11:35:30', '2026-07-28 05:20:33'),
(82, '200GM RESIN BOTTLE', 'F-011', 3, 3, 3, 1.0000, 1000.0000, 2100.0000, 500.0000, 1000.0000, NULL, 1, 0, '2026-07-22 11:36:55', '2026-07-28 06:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Marketing', 'marketing', 'Solcon Marketing user with access to order generation', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(2, 'Administrator', 'admin', 'Solcon Administrator with full access to masters, formulas and settings', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(3, 'Supervisor', 'supervisor', 'Solcon Production Supervisor with access to department-level batch operations', '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(4, 'Super Administrator', 'super-admin', 'Solcon Super Administrator with root settings control.', '2026-07-14 08:42:23', '2026-07-14 08:42:23'),
(5, 'Dispatch', 'dispatch', 'Solcon Dispatch department user responsible for loading and dispatching orders', '2026-07-21 15:55:52', '2026-07-21 15:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 2),
(2, 4),
(3, 2),
(4, 3),
(5, 3),
(6, 3),
(7, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7AUijjCNaRsoRQ8slmepnnPz3WKfXtBJaYhWbiSd', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6S0xjb1h6WWVvcHlmQ2NLWTl5ZHVaUEtTa0VYQW5rT0kzM1l3cXZKIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwODBcL2FkbWluXC9lcG94eS1wcm9kdWN0cyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1785332420),
('0RCWyfgBID2X8DW0MYHbsH1dqEj4iGuBfigpwImN', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ1cTRxdDI0VktHWXRUWEc2OTM0R3dZN0ZleUFyUDU5N1JvMlpxVUpsIiwidXJsIjpbXSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwODBcL2ZpbmlzaGVkLWdvb2RzIiwicm91dGUiOiJmaW5pc2hlZC1nb29kcy5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwiY3VycmVudF9kZXBhcnRtZW50X2lkXzIiOjN9', 1785333944);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('maintenance_mode', 'disable', '2026-07-14 10:00:03', '2026-07-22 12:38:47'),
('maintenance_unlock_password', '$2y$12$v53qIAvf/BpnDdaK.Uj7OORUC7d3ZmaoAiMaZEUGBQg0VSuGXMhkW', '2026-07-14 10:00:03', '2026-07-22 04:53:51'),
('maintenance_password', '$2y$12$YVm3y.eS3Z5a1V6AubSUvuoKtVHVypkmze65fMRNiXcXgU1SLJbSC', '2026-07-22 10:37:44', '2026-07-22 10:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
CREATE TABLE IF NOT EXISTS `stock_adjustments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `packing_material_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_raw_material_id_foreign` (`raw_material_id`),
  KEY `stock_adjustments_created_by_foreign` (`created_by`),
  KEY `stock_adjustments_packing_material_id_foreign` (`packing_material_id`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustments`
--

INSERT INTO `stock_adjustments` (`id`, `raw_material_id`, `packing_material_id`, `quantity`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 12, NULL, 350000.0000, 'new', 2, '2026-07-22 11:21:59', '2026-07-22 11:21:59'),
(2, NULL, 21, 1000.0000, 'sbc', 2, '2026-07-22 11:28:52', '2026-07-22 11:28:52'),
(3, NULL, 9, 50000.0000, 'NEW', 2, '2026-07-22 11:46:45', '2026-07-22 11:46:45'),
(4, NULL, 24, 12.0000, '', 2, '2026-07-23 06:20:44', '2026-07-23 06:20:44'),
(5, NULL, 14, 500.0000, '', 2, '2026-07-23 08:32:34', '2026-07-23 08:32:34'),
(6, NULL, 46, 500.0000, '', 2, '2026-07-23 08:32:49', '2026-07-23 08:32:49'),
(7, NULL, 10, 1000.0000, '', 2, '2026-07-24 10:45:11', '2026-07-24 10:45:11'),
(8, NULL, 14, 1000.0000, '', 2, '2026-07-24 10:45:37', '2026-07-24 10:45:37'),
(9, NULL, 10, 1000.0000, '', 2, '2026-07-25 11:54:30', '2026-07-25 11:54:30'),
(10, NULL, 11, 2000.0000, '', 2, '2026-07-25 11:55:11', '2026-07-25 11:55:11'),
(11, NULL, 12, 20000.0000, '', 2, '2026-07-25 11:55:23', '2026-07-25 11:55:23'),
(12, NULL, 13, 2000.0000, '', 2, '2026-07-25 11:55:37', '2026-07-25 11:55:37'),
(13, NULL, 10, 2000.0000, '', 2, '2026-07-25 11:56:45', '2026-07-25 11:56:45'),
(14, NULL, 17, 2000.0000, '', 2, '2026-07-25 11:59:40', '2026-07-25 11:59:40'),
(15, 12, NULL, 594864.8500, '', 2, '2026-07-26 05:33:20', '2026-07-26 05:33:20'),
(16, 13, NULL, 47727.8100, '', 2, '2026-07-26 05:34:42', '2026-07-26 05:34:42'),
(17, 14, NULL, 4472.3000, '', 2, '2026-07-26 05:38:25', '2026-07-26 05:38:25'),
(18, 18, NULL, -3150.2217, '', 2, '2026-07-26 05:40:07', '2026-07-26 05:40:07'),
(19, 17, NULL, -2042.4432, '', 2, '2026-07-26 05:41:46', '2026-07-26 05:41:46'),
(20, 19, NULL, 546.0000, '', 2, '2026-07-26 05:43:48', '2026-07-26 05:43:48'),
(21, NULL, 1, 18073.0000, '', 2, '2026-07-26 06:54:40', '2026-07-26 06:54:40'),
(22, NULL, 2, 59842.0000, '', 2, '2026-07-26 06:55:45', '2026-07-26 06:55:45'),
(23, NULL, 4, -4500.0000, '', 2, '2026-07-26 06:57:30', '2026-07-26 06:57:30'),
(24, NULL, 3, -3273.0000, '', 2, '2026-07-26 06:58:14', '2026-07-26 06:58:14'),
(25, NULL, 5, -5500.0000, '', 2, '2026-07-26 06:58:46', '2026-07-26 06:58:46'),
(26, 14, NULL, -16250.0023, '', 2, '2026-07-28 04:18:52', '2026-07-28 04:18:52'),
(27, 45, NULL, 1625.0000, '', 6, '2026-07-28 04:42:02', '2026-07-28 04:42:02'),
(28, 77, NULL, 375.0000, '', 6, '2026-07-28 04:42:51', '2026-07-28 04:42:51'),
(29, 67, NULL, 175.0000, '', 6, '2026-07-28 04:43:16', '2026-07-28 04:43:16'),
(30, 67, NULL, 175.0000, '', 6, '2026-07-28 04:51:07', '2026-07-28 04:51:07'),
(31, 44, NULL, 1625.0000, '', 6, '2026-07-28 04:51:55', '2026-07-28 04:51:55'),
(32, 67, NULL, -10.0000, '', 6, '2026-07-28 04:52:36', '2026-07-28 04:52:36'),
(33, 12, NULL, 24860.0000, '', 2, '2026-07-28 04:53:25', '2026-07-28 04:53:25'),
(34, 67, NULL, -1215.0000, '', 6, '2026-07-28 04:55:27', '2026-07-28 04:55:27'),
(35, 44, NULL, -860.0000, '', 6, '2026-07-28 04:56:48', '2026-07-28 04:56:48'),
(36, 70, NULL, -925.0000, '', 6, '2026-07-28 04:57:17', '2026-07-28 04:57:17'),
(37, 51, NULL, -950.0000, '', 6, '2026-07-28 04:57:35', '2026-07-28 04:57:35'),
(38, 66, NULL, -750.0000, '', 6, '2026-07-28 04:58:13', '2026-07-28 04:58:13'),
(39, 53, NULL, -925.0000, '', 6, '2026-07-28 04:58:54', '2026-07-28 04:58:54'),
(40, 52, NULL, -425.0000, '', 6, '2026-07-28 04:59:46', '2026-07-28 04:59:46'),
(41, 50, NULL, -925.0000, '', 6, '2026-07-28 05:00:27', '2026-07-28 05:00:27'),
(42, 56, NULL, -925.0000, '', 6, '2026-07-28 05:02:31', '2026-07-28 05:02:31'),
(43, 47, NULL, -750.0000, '', 6, '2026-07-28 05:04:23', '2026-07-28 05:04:23'),
(44, 71, NULL, -950.0000, '', 6, '2026-07-28 05:05:15', '2026-07-28 05:05:15'),
(45, 49, NULL, -900.0000, '', 6, '2026-07-28 05:05:44', '2026-07-28 05:05:44'),
(46, 72, NULL, -975.0000, '', 6, '2026-07-28 05:06:12', '2026-07-28 05:06:12'),
(47, 68, NULL, -575.0000, '', 6, '2026-07-28 05:06:53', '2026-07-28 05:06:53'),
(48, 57, NULL, -975.0000, '', 6, '2026-07-28 05:07:17', '2026-07-28 05:07:17'),
(49, 54, NULL, -950.0000, '', 6, '2026-07-28 05:07:43', '2026-07-28 05:07:43'),
(50, 62, NULL, -925.0000, '', 6, '2026-07-28 05:08:02', '2026-07-28 05:08:02'),
(51, 48, NULL, -925.0000, '', 6, '2026-07-28 05:08:28', '2026-07-28 05:08:28'),
(52, 63, NULL, -900.0000, '', 6, '2026-07-28 05:09:19', '2026-07-28 05:09:19'),
(53, 65, NULL, -900.0000, '', 6, '2026-07-28 05:09:36', '2026-07-28 05:09:36'),
(54, 58, NULL, -950.0000, '', 6, '2026-07-28 05:09:50', '2026-07-28 05:09:50'),
(55, 60, NULL, -850.0000, '', 6, '2026-07-28 05:10:22', '2026-07-28 05:10:22'),
(56, 73, NULL, -950.0000, '', 6, '2026-07-28 05:10:51', '2026-07-28 05:10:51'),
(57, 61, NULL, -750.0000, '', 6, '2026-07-28 05:11:13', '2026-07-28 05:11:13'),
(58, 59, NULL, -800.0000, '', 6, '2026-07-28 05:11:38', '2026-07-28 05:11:38'),
(59, 55, NULL, -950.0000, '', 6, '2026-07-28 05:12:01', '2026-07-28 05:12:01'),
(60, 64, NULL, -750.0000, '', 6, '2026-07-28 05:12:20', '2026-07-28 05:12:20'),
(61, 74, NULL, -950.0000, '', 6, '2026-07-28 05:12:36', '2026-07-28 05:12:36'),
(62, 46, NULL, -625.0000, '', 6, '2026-07-28 05:13:19', '2026-07-28 05:13:19'),
(63, 81, NULL, -350.0000, '', 6, '2026-07-28 05:20:33', '2026-07-28 05:20:33'),
(64, 80, NULL, 320.0000, '', 6, '2026-07-28 05:21:43', '2026-07-28 05:21:43'),
(65, 86, NULL, 75.0000, '', 2, '2026-07-28 05:32:49', '2026-07-28 05:32:49'),
(66, 79, NULL, 2000.0000, '', 6, '2026-07-28 06:38:29', '2026-07-28 06:38:29'),
(67, 82, NULL, 2000.0000, '', 6, '2026-07-28 06:39:45', '2026-07-28 06:39:45'),
(68, 85, NULL, 200.0000, '', 6, '2026-07-28 06:40:19', '2026-07-28 06:40:19'),
(69, 86, NULL, -4.0000, '', 6, '2026-07-28 06:40:55', '2026-07-28 06:40:55'),
(70, 33, NULL, 4050.0000, '', 6, '2026-07-28 06:41:51', '2026-07-28 06:41:51'),
(71, 34, NULL, 230.0000, '', 6, '2026-07-28 06:42:40', '2026-07-28 06:42:40'),
(72, 40, NULL, 59850.0000, '', 6, '2026-07-28 06:44:50', '2026-07-28 06:44:50'),
(73, 83, NULL, 900.0000, '', 6, '2026-07-28 06:46:21', '2026-07-28 06:46:21'),
(74, 84, NULL, 2450.0000, '', 6, '2026-07-28 06:47:01', '2026-07-28 06:47:01'),
(75, NULL, 21, 1550.0000, '', 6, '2026-07-28 06:47:37', '2026-07-28 06:47:37'),
(76, NULL, 24, 238.0000, '', 6, '2026-07-28 06:48:09', '2026-07-28 06:48:09'),
(77, NULL, 19, -5800.0000, '', 6, '2026-07-28 06:48:54', '2026-07-28 06:48:54'),
(78, NULL, 22, 0.0000, '', 6, '2026-07-28 06:49:07', '2026-07-28 06:49:07'),
(79, NULL, 22, -50.0000, '', 6, '2026-07-28 06:49:25', '2026-07-28 06:49:25'),
(80, NULL, 23, 30.0000, '', 6, '2026-07-28 06:49:40', '2026-07-28 06:49:40'),
(81, NULL, 20, -9620.0000, '', 6, '2026-07-28 06:50:12', '2026-07-28 06:50:12'),
(82, NULL, 51, 8.0000, '', 6, '2026-07-28 06:50:30', '2026-07-28 06:50:30'),
(83, NULL, 46, 40.0000, '', 6, '2026-07-28 06:51:15', '2026-07-28 06:51:15'),
(84, NULL, 54, 1350.0000, '', 6, '2026-07-28 06:52:15', '2026-07-28 06:52:15'),
(85, NULL, 14, -400.0000, '', 6, '2026-07-28 06:52:50', '2026-07-28 06:52:50'),
(86, NULL, 14, -25.0000, '', 6, '2026-07-28 06:53:04', '2026-07-28 06:53:04'),
(87, NULL, 15, 200.0000, '', 6, '2026-07-28 06:53:46', '2026-07-28 06:53:46'),
(88, NULL, 16, 450.0000, '', 6, '2026-07-28 06:54:15', '2026-07-28 06:54:15'),
(89, NULL, 43, 300.0000, '', 6, '2026-07-28 06:55:03', '2026-07-28 06:55:03'),
(90, NULL, 44, 450.0000, '', 6, '2026-07-28 06:56:02', '2026-07-28 06:56:02'),
(91, NULL, 55, 11050.0000, '', 6, '2026-07-28 12:18:33', '2026-07-28 12:18:33'),
(92, 86, NULL, 200.0000, '', 6, '2026-07-28 12:19:20', '2026-07-28 12:19:20'),
(93, 85, NULL, 141.0000, '', 6, '2026-07-28 12:20:49', '2026-07-28 12:20:49'),
(94, NULL, 39, 190.0000, '', 6, '2026-07-28 12:23:31', '2026-07-28 12:23:31'),
(95, NULL, 56, 965.0000, '', 6, '2026-07-28 12:25:45', '2026-07-28 12:25:45'),
(96, NULL, 56, -1930.0000, '', 6, '2026-07-28 12:26:34', '2026-07-28 12:26:34'),
(97, NULL, 47, 180.0000, '', 6, '2026-07-28 12:27:36', '2026-07-28 12:27:36'),
(98, NULL, 61, 25.0000, '', 6, '2026-07-28 12:30:19', '2026-07-28 12:30:19'),
(99, NULL, 61, -1900.0000, '', 6, '2026-07-28 12:30:55', '2026-07-28 12:30:55'),
(100, NULL, 27, 200.0000, '', 6, '2026-07-28 12:31:27', '2026-07-28 12:31:27'),
(101, NULL, 28, 230.0000, '', 6, '2026-07-28 12:32:05', '2026-07-28 12:32:05'),
(102, NULL, 52, 225.0000, '', 6, '2026-07-28 12:32:50', '2026-07-28 12:32:50'),
(103, NULL, 45, 380.0000, '', 6, '2026-07-28 12:33:30', '2026-07-28 12:33:30'),
(104, NULL, 48, 1200.0000, '', 6, '2026-07-28 12:33:48', '2026-07-28 12:33:48'),
(105, NULL, 50, 150.0000, '', 6, '2026-07-28 12:34:06', '2026-07-28 12:34:06'),
(106, NULL, 49, 1200.0000, '', 6, '2026-07-28 12:34:29', '2026-07-28 12:34:29'),
(107, NULL, 10, 900.0000, '', 6, '2026-07-28 12:35:06', '2026-07-28 12:35:06'),
(108, NULL, 10, -1500.0000, '', 6, '2026-07-28 12:35:41', '2026-07-28 12:35:41'),
(109, NULL, 11, 1500.0000, '', 6, '2026-07-28 12:36:24', '2026-07-28 12:36:24'),
(110, NULL, 12, -16200.0000, '', 6, '2026-07-28 12:37:23', '2026-07-28 12:37:23'),
(111, NULL, 13, -400.0000, '', 6, '2026-07-28 12:37:50', '2026-07-28 12:37:50'),
(112, NULL, 53, 9000.0000, '', 6, '2026-07-28 12:38:17', '2026-07-28 12:38:17'),
(113, NULL, 18, 60000.0000, '', 2, '2026-07-28 12:38:40', '2026-07-28 12:38:40'),
(114, NULL, 41, 400.0000, '', 6, '2026-07-28 12:38:44', '2026-07-28 12:38:44'),
(115, NULL, 42, 310.0000, '', 6, '2026-07-28 12:39:44', '2026-07-28 12:39:44'),
(116, NULL, 25, 1100.0000, '', 6, '2026-07-28 12:40:16', '2026-07-28 12:40:16'),
(117, NULL, 26, 280.0000, '', 6, '2026-07-28 12:40:46', '2026-07-28 12:40:46'),
(118, NULL, 17, 1475.0000, '', 6, '2026-07-28 12:41:28', '2026-07-28 12:41:28'),
(119, NULL, 17, 2950.0000, '', 6, '2026-07-28 12:42:13', '2026-07-28 12:42:13'),
(120, NULL, 17, -5900.0000, '', 6, '2026-07-28 12:42:38', '2026-07-28 12:42:38'),
(121, NULL, 22, 5000.0000, '', 2, '2026-07-28 12:52:31', '2026-07-28 12:52:31'),
(122, NULL, 30, 5000.0000, '', 2, '2026-07-28 12:52:45', '2026-07-28 12:52:45'),
(123, NULL, 35, 50000.0000, '', 2, '2026-07-28 12:54:15', '2026-07-28 12:54:15'),
(124, NULL, 34, 5000.0000, '', 2, '2026-07-29 14:00:10', '2026-07-29 14:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `stock_ledgers`
--

DROP TABLE IF EXISTS `stock_ledgers`;
CREATE TABLE IF NOT EXISTS `stock_ledgers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `raw_material_id` bigint UNSIGNED DEFAULT NULL,
  `packing_material_id` bigint UNSIGNED DEFAULT NULL,
  `batch_id` bigint UNSIGNED DEFAULT NULL,
  `grout_batch_id` bigint UNSIGNED DEFAULT NULL,
  `epoxy_assembly_id` bigint UNSIGNED DEFAULT NULL,
  `transaction_type` enum('IN','OUT','ADJUSTMENT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `balance_after` decimal(12,4) NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_ledgers_batch_id_foreign` (`batch_id`),
  KEY `stock_ledgers_created_by_foreign` (`created_by`),
  KEY `stock_ledgers_raw_material_id_created_at_index` (`raw_material_id`,`created_at`),
  KEY `stock_ledgers_grout_batch_id_foreign` (`grout_batch_id`),
  KEY `stock_ledgers_epoxy_assembly_id_foreign` (`epoxy_assembly_id`),
  KEY `stock_ledgers_packing_material_id_created_at_index` (`packing_material_id`,`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=380 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_ledgers`
--

INSERT INTO `stock_ledgers` (`id`, `raw_material_id`, `packing_material_id`, `batch_id`, `grout_batch_id`, `epoxy_assembly_id`, `transaction_type`, `quantity`, `balance_after`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, NULL, NULL, 'OUT', -98.0000, 11902.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(2, 12, NULL, 1, NULL, NULL, 'OUT', -1280.0000, 348720.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(3, 13, NULL, 1, NULL, NULL, 'OUT', -500.0000, 49500.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(4, 15, NULL, 1, NULL, NULL, 'OUT', -200.0000, 49800.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(5, 17, NULL, 1, NULL, NULL, 'OUT', -4.0000, 3996.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(6, 18, NULL, 1, NULL, NULL, 'OUT', -4.0000, 3996.0000, 'Consumed in production batch #ADH-20260713-0001', 2, '2026-07-13 04:11:06', '2026-07-13 04:11:06'),
(7, NULL, 2, 2, NULL, NULL, 'OUT', -100.0000, 11900.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(8, 12, NULL, 2, NULL, NULL, 'OUT', -1280.0000, 347440.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(9, 13, NULL, 2, NULL, NULL, 'OUT', -600.0000, 48900.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(10, 15, NULL, 2, NULL, NULL, 'OUT', -200.0000, 49600.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(11, 17, NULL, 2, NULL, NULL, 'OUT', -10.0000, 3986.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(12, 18, NULL, 2, NULL, NULL, 'OUT', -4.0000, 3992.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(13, 7, NULL, 2, NULL, NULL, 'OUT', -100.0000, 2900.0000, 'Consumed in production batch #ADH-20260713-0002', 2, '2026-07-13 04:28:46', '2026-07-13 04:28:46'),
(14, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 993.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:47:15', '2026-07-13 08:47:15'),
(15, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 140.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:47:15', '2026-07-13 08:47:15'),
(16, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 986.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:49:28', '2026-07-13 08:49:28'),
(17, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 130.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:49:28', '2026-07-13 08:49:28'),
(18, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 979.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:17', '2026-07-13 08:50:17'),
(19, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 120.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:17', '2026-07-13 08:50:17'),
(20, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 972.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:24', '2026-07-13 08:50:24'),
(21, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 110.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:24', '2026-07-13 08:50:24'),
(22, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 965.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:36', '2026-07-13 08:50:36'),
(23, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 100.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:50:36', '2026-07-13 08:50:36'),
(24, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 958.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:51:07', '2026-07-13 08:51:07'),
(25, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 90.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:51:07', '2026-07-13 08:51:07'),
(26, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 951.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:51:45', '2026-07-13 08:51:45'),
(27, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 80.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:51:45', '2026-07-13 08:51:45'),
(28, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 944.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:53:18', '2026-07-13 08:53:18'),
(29, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 70.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:53:18', '2026-07-13 08:53:18'),
(30, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 937.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:53:40', '2026-07-13 08:53:40'),
(31, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 60.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:53:40', '2026-07-13 08:53:40'),
(32, 44, NULL, NULL, NULL, NULL, 'OUT', -7.0000, 930.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:55:15', '2026-07-13 08:55:15'),
(33, NULL, 9, NULL, NULL, NULL, 'OUT', -10.0000, 50.0000, 'Formula consumed to prepare 10 units of component: 700gm Black Filler Pouch', 2, '2026-07-13 08:55:15', '2026-07-13 08:55:15'),
(34, 45, NULL, NULL, NULL, NULL, 'IN', 10.0000, 10.0000, 'Prepared component stock increment: 700gm Black Filler Pouch', 2, '2026-07-13 08:55:15', '2026-07-13 08:55:15'),
(35, NULL, 1, 3, NULL, NULL, 'OUT', -100.0000, 11802.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:17', '2026-07-13 09:24:17'),
(36, 12, NULL, 3, NULL, NULL, 'OUT', -1280.0000, 346160.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:17', '2026-07-13 09:24:17'),
(37, 13, NULL, 3, NULL, NULL, 'OUT', -500.0000, 48400.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:17', '2026-07-13 09:24:17'),
(38, 15, NULL, 3, NULL, NULL, 'OUT', -200.0000, 49400.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:17', '2026-07-13 09:24:17'),
(39, 17, NULL, 3, NULL, NULL, 'OUT', -4.0000, 3982.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:17', '2026-07-13 09:24:17'),
(40, 18, NULL, 3, NULL, NULL, 'OUT', -4.0000, 3988.0000, 'Consumed in production batch #ADH-20260713-0003', 2, '2026-07-13 09:24:18', '2026-07-13 09:24:18'),
(41, 16, NULL, NULL, 1, NULL, 'OUT', -658.9958, 19341.0042, 'Consumed in Grout production batch #GRT-20260713-0001', 2, '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(42, 14, NULL, NULL, 1, NULL, 'OUT', -235.3556, 14264.6444, 'Consumed in Grout production batch #GRT-20260713-0001', 2, '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(43, 17, NULL, NULL, 1, NULL, 'OUT', -3.7657, 3978.2343, 'Consumed in Grout production batch #GRT-20260713-0001', 2, '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(44, 18, NULL, NULL, 1, NULL, 'OUT', -1.8828, 3986.1172, 'Consumed in Grout production batch #GRT-20260713-0001', 2, '2026-07-13 11:04:08', '2026-07-13 11:04:08'),
(45, 16, NULL, NULL, 4, NULL, 'OUT', -658.9958, 18682.0084, 'Consumed in Grout production batch #GRT-20260713-0004', 2, '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(46, 14, NULL, NULL, 4, NULL, 'OUT', -235.3556, 14029.2888, 'Consumed in Grout production batch #GRT-20260713-0004', 2, '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(47, 17, NULL, NULL, 4, NULL, 'OUT', -3.7657, 3974.4686, 'Consumed in Grout production batch #GRT-20260713-0004', 2, '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(48, 18, NULL, NULL, 4, NULL, 'OUT', -1.8828, 3984.2344, 'Consumed in Grout production batch #GRT-20260713-0004', 2, '2026-07-13 15:38:39', '2026-07-13 15:38:39'),
(49, 16, NULL, NULL, 5, NULL, 'OUT', -732.2176, 17949.7908, 'Consumed in Grout production batch #GRT-20260713-0005', 5, '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(50, 14, NULL, NULL, 5, NULL, 'OUT', -261.5063, 13767.7825, 'Consumed in Grout production batch #GRT-20260713-0005', 5, '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(51, 17, NULL, NULL, 5, NULL, 'OUT', -4.1841, 3970.2845, 'Consumed in Grout production batch #GRT-20260713-0005', 5, '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(52, 18, NULL, NULL, 5, NULL, 'OUT', -2.0921, 3982.1423, 'Consumed in Grout production batch #GRT-20260713-0005', 5, '2026-07-13 16:15:52', '2026-07-13 16:15:52'),
(53, 16, NULL, NULL, 6, NULL, 'OUT', -657.6200, 17292.1708, 'Consumed in Grout production batch #GRT-20260713-0006', 5, '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(54, 14, NULL, NULL, 6, NULL, 'OUT', -234.8643, 13532.9182, 'Consumed in Grout production batch #GRT-20260713-0006', 5, '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(55, 17, NULL, NULL, 6, NULL, 'OUT', -3.7578, 3966.5267, 'Consumed in Grout production batch #GRT-20260713-0006', 5, '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(56, 18, NULL, NULL, 6, NULL, 'OUT', -1.8789, 3980.2634, 'Consumed in Grout production batch #GRT-20260713-0006', 5, '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(57, 28, NULL, NULL, 6, NULL, 'OUT', -1.8789, 148.1211, 'Consumed in Grout production batch #GRT-20260713-0006', 5, '2026-07-13 16:46:53', '2026-07-13 16:46:53'),
(58, 16, NULL, NULL, 7, NULL, 'OUT', -694.1545, 16598.0163, 'Consumed in Grout production batch #GRT-20260713-0007', 5, '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(59, 14, NULL, NULL, 7, NULL, 'OUT', -247.9123, 13285.0059, 'Consumed in Grout production batch #GRT-20260713-0007', 5, '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(60, 17, NULL, NULL, 7, NULL, 'OUT', -3.9666, 3962.5601, 'Consumed in Grout production batch #GRT-20260713-0007', 5, '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(61, 18, NULL, NULL, 7, NULL, 'OUT', -1.9833, 3978.2801, 'Consumed in Grout production batch #GRT-20260713-0007', 5, '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(62, 28, NULL, NULL, 7, NULL, 'OUT', -1.9833, 146.1378, 'Consumed in Grout production batch #GRT-20260713-0007', 5, '2026-07-13 16:55:21', '2026-07-13 16:55:21'),
(63, 16, NULL, NULL, 8, NULL, 'OUT', -712.4217, 15885.5946, 'Consumed in Grout production batch #GRT-20260713-0008', 5, '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(64, 14, NULL, NULL, 8, NULL, 'OUT', -254.4363, 13030.5696, 'Consumed in Grout production batch #GRT-20260713-0008', 5, '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(65, 17, NULL, NULL, 8, NULL, 'OUT', -4.0710, 3958.4891, 'Consumed in Grout production batch #GRT-20260713-0008', 5, '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(66, 18, NULL, NULL, 8, NULL, 'OUT', -2.0355, 3976.2446, 'Consumed in Grout production batch #GRT-20260713-0008', 5, '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(67, 28, NULL, NULL, 8, NULL, 'OUT', -2.0355, 144.1023, 'Consumed in Grout production batch #GRT-20260713-0008', 5, '2026-07-13 17:24:59', '2026-07-13 17:24:59'),
(68, 16, NULL, NULL, 9, NULL, 'OUT', -712.4217, 15173.1729, 'Consumed in Grout production batch #GRT-20260713-0009', 5, '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(69, 14, NULL, NULL, 9, NULL, 'OUT', -254.4363, 12776.1333, 'Consumed in Grout production batch #GRT-20260713-0009', 5, '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(70, 17, NULL, NULL, 9, NULL, 'OUT', -4.0710, 3954.4181, 'Consumed in Grout production batch #GRT-20260713-0009', 5, '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(71, 18, NULL, NULL, 9, NULL, 'OUT', -2.0355, 3974.2091, 'Consumed in Grout production batch #GRT-20260713-0009', 5, '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(72, 28, NULL, NULL, 9, NULL, 'OUT', -2.0355, 142.0668, 'Consumed in Grout production batch #GRT-20260713-0009', 5, '2026-07-13 18:08:35', '2026-07-13 18:08:35'),
(73, 16, NULL, NULL, 10, NULL, 'OUT', -695.6067, 14477.5662, 'Consumed in Grout production batch #GRT-20260713-0010', 5, '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(74, 14, NULL, NULL, 10, NULL, 'OUT', -248.4310, 12527.7023, 'Consumed in Grout production batch #GRT-20260713-0010', 5, '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(75, 17, NULL, NULL, 10, NULL, 'OUT', -3.9749, 3950.4432, 'Consumed in Grout production batch #GRT-20260713-0010', 5, '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(76, 18, NULL, NULL, 10, NULL, 'OUT', -1.9874, 3972.2217, 'Consumed in Grout production batch #GRT-20260713-0010', 5, '2026-07-14 03:02:37', '2026-07-14 03:02:37'),
(77, NULL, 1, 4, NULL, NULL, 'OUT', -97.0000, 11705.0000, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(78, 12, NULL, 4, NULL, NULL, 'OUT', -1280.0000, 344880.0000, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(79, 13, NULL, 4, NULL, NULL, 'OUT', -500.0000, 47900.0000, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(80, 15, NULL, 4, NULL, NULL, 'OUT', -200.0000, 49200.0000, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(81, 17, NULL, 4, NULL, NULL, 'OUT', -4.0000, 3946.4432, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(82, 18, NULL, 4, NULL, NULL, 'OUT', -4.0000, 3968.2217, 'Consumed in production batch #ADH-20260714-0001', 4, '2026-07-14 06:53:25', '2026-07-14 06:53:25'),
(83, NULL, 1, 5, NULL, NULL, 'OUT', -107.0000, 11598.0000, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(84, 12, NULL, 5, NULL, NULL, 'OUT', -1280.0000, 343600.0000, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(85, 13, NULL, 5, NULL, NULL, 'OUT', -500.0000, 47400.0000, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(86, 15, NULL, 5, NULL, NULL, 'OUT', -200.0000, 49000.0000, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(87, 17, NULL, 5, NULL, NULL, 'OUT', -4.0000, 3942.4432, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(88, 18, NULL, 5, NULL, NULL, 'OUT', -4.0000, 3964.2217, 'Consumed in production batch #ADH-20260714-0002', 4, '2026-07-14 06:54:08', '2026-07-14 06:54:08'),
(89, NULL, 1, 6, NULL, NULL, 'OUT', -104.0000, 11494.0000, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(90, 12, NULL, 6, NULL, NULL, 'OUT', -1280.0000, 342320.0000, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(91, 13, NULL, 6, NULL, NULL, 'OUT', -500.0000, 46900.0000, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(92, 15, NULL, 6, NULL, NULL, 'OUT', -200.0000, 48800.0000, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(93, 17, NULL, 6, NULL, NULL, 'OUT', -4.0000, 3938.4432, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(94, 18, NULL, 6, NULL, NULL, 'OUT', -4.0000, 3960.2217, 'Consumed in production batch #ADH-20260714-0003', 4, '2026-07-14 06:54:24', '2026-07-14 06:54:24'),
(95, NULL, 2, 7, NULL, NULL, 'OUT', -101.0000, 11799.0000, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(96, 12, NULL, 7, NULL, NULL, 'OUT', -1280.0000, 341040.0000, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(97, 13, NULL, 7, NULL, NULL, 'OUT', -600.0000, 46300.0000, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(98, 15, NULL, 7, NULL, NULL, 'OUT', -200.0000, 48600.0000, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(99, 17, NULL, 7, NULL, NULL, 'OUT', -10.0000, 3928.4432, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(100, 18, NULL, 7, NULL, NULL, 'OUT', -4.0000, 3956.2217, 'Consumed in production batch #ADH-20260714-0004', 4, '2026-07-14 06:54:51', '2026-07-14 06:54:51'),
(101, NULL, 2, 8, NULL, NULL, 'OUT', -106.0000, 11693.0000, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(102, 12, NULL, 8, NULL, NULL, 'OUT', -1280.0000, 339760.0000, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(103, 13, NULL, 8, NULL, NULL, 'OUT', -600.0000, 45700.0000, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(104, 15, NULL, 8, NULL, NULL, 'OUT', -200.0000, 48400.0000, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(105, 17, NULL, 8, NULL, NULL, 'OUT', -10.0000, 3918.4432, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(106, 18, NULL, 8, NULL, NULL, 'OUT', -4.0000, 3952.2217, 'Consumed in production batch #ADH-20260714-0005', 4, '2026-07-14 06:55:11', '2026-07-14 06:55:11'),
(107, NULL, 2, 9, NULL, NULL, 'OUT', -106.0000, 11587.0000, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(108, 12, NULL, 9, NULL, NULL, 'OUT', -1280.0000, 338480.0000, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(109, 13, NULL, 9, NULL, NULL, 'OUT', -600.0000, 45100.0000, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(110, 15, NULL, 9, NULL, NULL, 'OUT', -200.0000, 48200.0000, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(111, 17, NULL, 9, NULL, NULL, 'OUT', -10.0000, 3908.4432, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(112, 18, NULL, 9, NULL, NULL, 'OUT', -4.0000, 3948.2217, 'Consumed in production batch #ADH-20260714-0006', 4, '2026-07-14 06:55:28', '2026-07-14 06:55:28'),
(113, NULL, 2, 10, NULL, NULL, 'OUT', -108.0000, 11479.0000, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(114, 12, NULL, 10, NULL, NULL, 'OUT', -1280.0000, 337200.0000, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(115, 13, NULL, 10, NULL, NULL, 'OUT', -600.0000, 44500.0000, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(116, 15, NULL, 10, NULL, NULL, 'OUT', -200.0000, 48000.0000, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(117, 17, NULL, 10, NULL, NULL, 'OUT', -10.0000, 3898.4432, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(118, 18, NULL, 10, NULL, NULL, 'OUT', -4.0000, 3944.2217, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(119, 10, NULL, 10, NULL, NULL, 'OUT', -108.0000, 2892.0000, 'Consumed in production batch #ADH-20260714-0007', 4, '2026-07-14 06:55:58', '2026-07-14 06:55:58'),
(120, NULL, 2, 11, NULL, NULL, 'OUT', -108.0000, 11371.0000, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(121, 12, NULL, 11, NULL, NULL, 'OUT', -1280.0000, 335920.0000, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(122, 13, NULL, 11, NULL, NULL, 'OUT', -600.0000, 43900.0000, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(123, 15, NULL, 11, NULL, NULL, 'OUT', -200.0000, 47800.0000, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(124, 17, NULL, 11, NULL, NULL, 'OUT', -10.0000, 3888.4432, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(125, 18, NULL, 11, NULL, NULL, 'OUT', -4.0000, 3940.2217, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(126, 10, NULL, 11, NULL, NULL, 'OUT', -108.0000, 2784.0000, 'Consumed in production batch #ADH-20260714-0008', 4, '2026-07-14 06:56:18', '2026-07-14 06:56:18'),
(127, NULL, 2, 12, NULL, NULL, 'OUT', -106.0000, 11265.0000, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(128, 12, NULL, 12, NULL, NULL, 'OUT', -1280.0000, 334640.0000, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(129, 13, NULL, 12, NULL, NULL, 'OUT', -600.0000, 43300.0000, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(130, 15, NULL, 12, NULL, NULL, 'OUT', -200.0000, 47600.0000, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(131, 17, NULL, 12, NULL, NULL, 'OUT', -10.0000, 3878.4432, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(132, 18, NULL, 12, NULL, NULL, 'OUT', -4.0000, 3936.2217, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(133, 10, NULL, 12, NULL, NULL, 'OUT', -106.0000, 2678.0000, 'Consumed in production batch #ADH-20260714-0009', 4, '2026-07-14 06:56:45', '2026-07-14 06:56:45'),
(134, NULL, 2, 13, NULL, NULL, 'OUT', -107.0000, 11158.0000, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(135, 12, NULL, 13, NULL, NULL, 'OUT', -1280.0000, 333360.0000, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(136, 13, NULL, 13, NULL, NULL, 'OUT', -600.0000, 42700.0000, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(137, 15, NULL, 13, NULL, NULL, 'OUT', -200.0000, 47400.0000, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(138, 17, NULL, 13, NULL, NULL, 'OUT', -10.0000, 3868.4432, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(139, 18, NULL, 13, NULL, NULL, 'OUT', -4.0000, 3932.2217, 'Consumed in production batch #ADH-20260714-0010', 4, '2026-07-14 06:57:08', '2026-07-14 06:57:08'),
(140, NULL, 1, 14, NULL, NULL, 'OUT', -162.0000, 11332.0000, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(141, 12, NULL, 14, NULL, NULL, 'OUT', -1280.0000, 332080.0000, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(142, 13, NULL, 14, NULL, NULL, 'OUT', -500.0000, 42200.0000, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(143, 15, NULL, 14, NULL, NULL, 'OUT', -200.0000, 47200.0000, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(144, 17, NULL, 14, NULL, NULL, 'OUT', -4.0000, 3864.4432, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(145, 18, NULL, 14, NULL, NULL, 'OUT', -4.0000, 3928.2217, 'Consumed in production batch #ADH-20260714-0011', 4, '2026-07-14 07:00:16', '2026-07-14 07:00:16'),
(146, NULL, 1, 15, NULL, NULL, 'OUT', -102.0000, 11230.0000, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(147, 12, NULL, 15, NULL, NULL, 'OUT', -1280.0000, 330800.0000, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(148, 13, NULL, 15, NULL, NULL, 'OUT', -500.0000, 41700.0000, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(149, 15, NULL, 15, NULL, NULL, 'OUT', -200.0000, 47000.0000, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(150, 17, NULL, 15, NULL, NULL, 'OUT', -4.0000, 3860.4432, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(151, 18, NULL, 15, NULL, NULL, 'OUT', -4.0000, 3924.2217, 'Consumed in production batch #ADH-20260714-0012', 4, '2026-07-14 07:00:36', '2026-07-14 07:00:36'),
(152, NULL, 1, 16, NULL, NULL, 'OUT', -102.0000, 11128.0000, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(153, 12, NULL, 16, NULL, NULL, 'OUT', -1280.0000, 329520.0000, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(154, 13, NULL, 16, NULL, NULL, 'OUT', -500.0000, 41200.0000, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(155, 15, NULL, 16, NULL, NULL, 'OUT', -200.0000, 46800.0000, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(156, 17, NULL, 16, NULL, NULL, 'OUT', -4.0000, 3856.4432, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(157, 18, NULL, 16, NULL, NULL, 'OUT', -4.0000, 3920.2217, 'Consumed in production batch #ADH-20260714-0013', 4, '2026-07-14 07:00:56', '2026-07-14 07:00:56'),
(158, NULL, 1, 17, NULL, NULL, 'OUT', -103.0000, 11025.0000, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(159, 12, NULL, 17, NULL, NULL, 'OUT', -1280.0000, 328240.0000, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(160, 13, NULL, 17, NULL, NULL, 'OUT', -500.0000, 40700.0000, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(161, 15, NULL, 17, NULL, NULL, 'OUT', -200.0000, 46600.0000, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(162, 17, NULL, 17, NULL, NULL, 'OUT', -4.0000, 3852.4432, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(163, 18, NULL, 17, NULL, NULL, 'OUT', -4.0000, 3916.2217, 'Consumed in production batch #ADH-20260714-0014', 4, '2026-07-14 07:01:16', '2026-07-14 07:01:16'),
(164, NULL, 3, 18, NULL, NULL, 'OUT', -115.0000, 11885.0000, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(165, 12, NULL, 18, NULL, NULL, 'OUT', -1280.0000, 326960.0000, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(166, 13, NULL, 18, NULL, NULL, 'OUT', -700.0000, 40000.0000, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(167, 15, NULL, 18, NULL, NULL, 'OUT', -200.0000, 46400.0000, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(168, 17, NULL, 18, NULL, NULL, 'OUT', -28.0000, 3824.4432, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(169, 18, NULL, 18, NULL, NULL, 'OUT', -6.0000, 3910.2217, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(170, 19, NULL, 18, NULL, NULL, 'OUT', -2.0000, 1998.0000, 'Consumed in production batch #ADH-20260714-0015', 4, '2026-07-14 07:01:47', '2026-07-14 07:01:47'),
(171, NULL, 3, 19, NULL, NULL, 'OUT', -112.0000, 11773.0000, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(172, 12, NULL, 19, NULL, NULL, 'OUT', -1280.0000, 325680.0000, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(173, 13, NULL, 19, NULL, NULL, 'OUT', -700.0000, 39300.0000, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(174, 15, NULL, 19, NULL, NULL, 'OUT', -200.0000, 46200.0000, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(175, 17, NULL, 19, NULL, NULL, 'OUT', -28.0000, 3796.4432, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(176, 18, NULL, 19, NULL, NULL, 'OUT', -6.0000, 3904.2217, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(177, 19, NULL, 19, NULL, NULL, 'OUT', -2.0000, 1996.0000, 'Consumed in production batch #ADH-20260714-0016', 4, '2026-07-14 07:02:07', '2026-07-14 07:02:07'),
(178, 12, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 350000.0000, 675680.0000, 'Adjustment: new', 2, '2026-07-22 11:21:59', '2026-07-22 11:21:59'),
(179, NULL, 1, 20, NULL, NULL, 'OUT', -98.0000, 10927.0000, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(180, 12, NULL, 20, NULL, NULL, 'OUT', -1280.0000, 674400.0000, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(181, 13, NULL, 20, NULL, NULL, 'OUT', -500.0000, 38800.0000, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(182, 15, NULL, 20, NULL, NULL, 'OUT', -200.0000, 46000.0000, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(183, 17, NULL, 20, NULL, NULL, 'OUT', -4.0000, 3792.4432, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(184, 18, NULL, 20, NULL, NULL, 'OUT', -4.0000, 3900.2217, 'Consumed in production batch #ADH-20260722-0001', 2, '2026-07-22 11:24:51', '2026-07-22 11:24:51'),
(185, NULL, 21, NULL, NULL, NULL, 'ADJUSTMENT', 1000.0000, 1150.0000, 'Adjustment: sbc', 2, '2026-07-22 11:28:52', '2026-07-22 11:28:52'),
(186, NULL, 21, NULL, NULL, NULL, 'OUT', -500.0000, 650.0000, 'Formula consumed to prepare 500 units of component: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:38:20', '2026-07-22 11:38:20'),
(187, 81, NULL, NULL, NULL, NULL, 'OUT', -50.0000, 450.0000, 'Formula consumed to prepare 500 units of component: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:38:20', '2026-07-22 11:38:20'),
(188, 79, NULL, NULL, NULL, NULL, 'IN', 500.0000, 500.0000, 'Prepared component stock increment: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:38:20', '2026-07-22 11:38:20'),
(189, 80, NULL, NULL, NULL, NULL, 'OUT', -20.0000, 480.0000, 'Formula consumed to prepare 100 units of component: 200GM RESIN BOTTLE FINISH', 2, '2026-07-22 11:41:05', '2026-07-22 11:41:05'),
(190, NULL, 22, NULL, NULL, NULL, 'OUT', -100.0000, 50.0000, 'Formula consumed to prepare 100 units of component: 200GM RESIN BOTTLE FINISH', 2, '2026-07-22 11:41:05', '2026-07-22 11:41:05'),
(191, 82, NULL, NULL, NULL, NULL, 'IN', 100.0000, 100.0000, 'Prepared component stock increment: 200GM RESIN BOTTLE FINISH', 2, '2026-07-22 11:41:05', '2026-07-22 11:41:05'),
(192, NULL, 9, NULL, NULL, NULL, 'ADJUSTMENT', 50000.0000, 50050.0000, 'Adjustment: NEW', 2, '2026-07-22 11:46:45', '2026-07-22 11:46:45'),
(193, 44, NULL, NULL, NULL, NULL, 'OUT', -70.0000, 860.0000, 'Formula consumed to prepare 100 units of component: 700gm Black Filler Pouch', 2, '2026-07-22 11:47:04', '2026-07-22 11:47:04'),
(194, NULL, 9, NULL, NULL, NULL, 'OUT', -100.0000, 49950.0000, 'Formula consumed to prepare 100 units of component: 700gm Black Filler Pouch', 2, '2026-07-22 11:47:04', '2026-07-22 11:47:04'),
(195, 45, NULL, NULL, NULL, NULL, 'IN', 100.0000, 110.0000, 'Prepared component stock increment: 700gm Black Filler Pouch', 2, '2026-07-22 11:47:04', '2026-07-22 11:47:04'),
(196, NULL, 21, NULL, NULL, NULL, 'OUT', -100.0000, 550.0000, 'Formula consumed to prepare 100 units of component: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:48:11', '2026-07-22 11:48:11'),
(197, 81, NULL, NULL, NULL, NULL, 'OUT', -10.0000, 440.0000, 'Formula consumed to prepare 100 units of component: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:48:11', '2026-07-22 11:48:11'),
(198, 79, NULL, NULL, NULL, NULL, 'IN', 100.0000, 600.0000, 'Prepared component stock increment: 100 GM HARDNER BOTTLE FINISH', 2, '2026-07-22 11:48:11', '2026-07-22 11:48:11'),
(199, NULL, 24, NULL, NULL, NULL, 'ADJUSTMENT', 12.0000, 162.0000, 'Manual Stock Adjustment', 2, '2026-07-23 06:20:44', '2026-07-23 06:20:44'),
(200, NULL, 14, NULL, NULL, NULL, 'ADJUSTMENT', 500.0000, 500.0000, 'Manual Stock Adjustment', 2, '2026-07-23 08:32:34', '2026-07-23 08:32:34'),
(201, NULL, 46, NULL, NULL, NULL, 'ADJUSTMENT', 500.0000, 500.0000, 'Manual Stock Adjustment', 2, '2026-07-23 08:32:49', '2026-07-23 08:32:49'),
(202, NULL, 46, NULL, NULL, NULL, 'OUT', -10.0000, 490.0000, 'Formula consumed to prepare 10 units of component: Clip Box 2MM', 2, '2026-07-23 08:33:21', '2026-07-23 08:33:21'),
(203, NULL, 14, NULL, NULL, NULL, 'OUT', -250.0000, 250.0000, 'Formula consumed to prepare 10 units of component: Clip Box 2MM', 2, '2026-07-23 08:33:21', '2026-07-23 08:33:21'),
(204, NULL, 56, NULL, NULL, NULL, 'IN', 1000.0000, 1000.0000, 'Initial Opening Stock', 2, '2026-07-24 06:02:31', '2026-07-24 06:02:31'),
(205, NULL, 57, NULL, NULL, NULL, 'IN', 100.0000, 100.0000, 'Initial Opening Stock', 2, '2026-07-24 07:00:37', '2026-07-24 07:00:37'),
(206, NULL, 58, NULL, NULL, NULL, 'IN', 500.0000, 500.0000, 'Initial Opening Stock', 2, '2026-07-24 07:03:12', '2026-07-24 07:03:12'),
(207, NULL, 10, NULL, NULL, NULL, 'ADJUSTMENT', 1000.0000, 1000.0000, 'Manual Stock Adjustment', 2, '2026-07-24 10:45:11', '2026-07-24 10:45:11'),
(208, NULL, 14, NULL, NULL, NULL, 'ADJUSTMENT', 1000.0000, 1250.0000, 'Manual Stock Adjustment', 2, '2026-07-24 10:45:37', '2026-07-24 10:45:37'),
(209, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 470.0000, 'Formula consumed to prepare 20 units of component: CLIP 2MM', 2, '2026-07-24 10:45:56', '2026-07-24 10:45:56'),
(210, NULL, 14, NULL, NULL, NULL, 'OUT', -500.0000, 750.0000, 'Formula consumed to prepare 20 units of component: CLIP 2MM', 2, '2026-07-24 10:45:56', '2026-07-24 10:45:56'),
(211, NULL, 10, NULL, NULL, NULL, 'OUT', -500.0000, 500.0000, 'Formula consumed to prepare 10 units of component: SPACER 2MM', 2, '2026-07-24 10:48:10', '2026-07-24 10:48:10'),
(212, NULL, 46, NULL, NULL, NULL, 'OUT', -10.0000, 460.0000, 'Formula consumed to prepare 10 units of component: SPACER 2MM', 2, '2026-07-24 10:48:10', '2026-07-24 10:48:10'),
(213, NULL, 59, NULL, NULL, NULL, 'IN', 200.0000, 200.0000, 'Initial Opening Stock', 2, '2026-07-25 04:20:58', '2026-07-25 04:20:58'),
(214, NULL, 59, NULL, NULL, NULL, 'OUT', -50.0000, 150.0000, 'Formula consumed to prepare 50 units of component: TROWEL', 2, '2026-07-25 04:21:56', '2026-07-25 04:21:56'),
(215, NULL, 60, NULL, NULL, NULL, 'IN', 5000.0000, 5000.0000, 'Initial Opening Stock', 2, '2026-07-25 11:52:35', '2026-07-25 11:52:35'),
(216, NULL, 10, NULL, NULL, NULL, 'ADJUSTMENT', 1000.0000, 1500.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:54:30', '2026-07-25 11:54:30'),
(217, NULL, 10, NULL, NULL, NULL, 'OUT', -1000.0000, 500.0000, 'Formula consumed to prepare 20 units of component: SPACER 2MM', 2, '2026-07-25 11:54:49', '2026-07-25 11:54:49'),
(218, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 440.0000, 'Formula consumed to prepare 20 units of component: SPACER 2MM', 2, '2026-07-25 11:54:49', '2026-07-25 11:54:49'),
(219, NULL, 11, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2000.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:55:11', '2026-07-25 11:55:11'),
(220, NULL, 12, NULL, NULL, NULL, 'ADJUSTMENT', 20000.0000, 20000.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:55:23', '2026-07-25 11:55:23'),
(221, NULL, 13, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2000.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:55:37', '2026-07-25 11:55:37'),
(222, NULL, 10, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2500.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:56:45', '2026-07-25 11:56:45'),
(223, NULL, 10, NULL, NULL, NULL, 'OUT', -1000.0000, 1500.0000, 'Formula consumed to prepare 20 units of component: SPACER 2MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(224, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 420.0000, 'Formula consumed to prepare 20 units of component: SPACER 2MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(225, NULL, 11, NULL, NULL, NULL, 'OUT', -1000.0000, 1000.0000, 'Formula consumed to prepare 20 units of component: SPACER 3MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(226, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 400.0000, 'Formula consumed to prepare 20 units of component: SPACER 3MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(227, NULL, 12, NULL, NULL, NULL, 'OUT', -1000.0000, 19000.0000, 'Formula consumed to prepare 20 units of component: SPACER 4MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(228, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 380.0000, 'Formula consumed to prepare 20 units of component: SPACER 4MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(229, NULL, 13, NULL, NULL, NULL, 'OUT', -1000.0000, 1000.0000, 'Formula consumed to prepare 20 units of component: SPACER 5MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(230, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 360.0000, 'Formula consumed to prepare 20 units of component: SPACER 5MM', 2, '2026-07-25 11:57:25', '2026-07-25 11:57:25'),
(231, NULL, 60, NULL, NULL, NULL, 'OUT', -1000.0000, 4000.0000, 'Formula consumed to prepare 20 units of component: SPACER 6MM', 2, '2026-07-25 11:57:26', '2026-07-25 11:57:26'),
(232, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 340.0000, 'Formula consumed to prepare 20 units of component: SPACER 6MM', 2, '2026-07-25 11:57:26', '2026-07-25 11:57:26'),
(233, NULL, 17, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2000.0000, 'Manual Stock Adjustment', 2, '2026-07-25 11:59:40', '2026-07-25 11:59:40'),
(234, NULL, 58, NULL, NULL, NULL, 'OUT', -500.0000, 0.0000, 'Formula consumed to prepare 10 units of component: JACK LEVELLING', 2, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(235, NULL, 56, NULL, NULL, NULL, 'OUT', -10.0000, 990.0000, 'Formula consumed to prepare 10 units of component: JACK LEVELLING', 2, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(236, NULL, 57, NULL, NULL, NULL, 'OUT', -10.0000, 90.0000, 'Formula consumed to prepare 10 units of component: JACK LEVELLING', 2, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(237, NULL, 17, NULL, NULL, NULL, 'OUT', -500.0000, 1500.0000, 'Formula consumed to prepare 20 units of component: WEDGE', 2, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(238, NULL, 46, NULL, NULL, NULL, 'OUT', -20.0000, 320.0000, 'Formula consumed to prepare 20 units of component: WEDGE', 2, '2026-07-25 12:03:16', '2026-07-25 12:03:16'),
(239, NULL, 61, NULL, NULL, NULL, 'IN', 2000.0000, 2000.0000, 'Initial Opening Stock', 2, '2026-07-25 12:05:43', '2026-07-25 12:05:43'),
(240, NULL, 61, NULL, NULL, NULL, 'OUT', -100.0000, 1900.0000, 'Formula consumed to prepare 100 units of component: PLIER', 2, '2026-07-25 12:06:29', '2026-07-25 12:06:29'),
(241, 12, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 594864.8500, 1269264.8500, 'Manual Stock Adjustment', 2, '2026-07-26 05:33:20', '2026-07-26 05:33:20'),
(242, 13, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 47727.8100, 86527.8100, 'Manual Stock Adjustment', 2, '2026-07-26 05:34:42', '2026-07-26 05:34:42'),
(243, 14, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 4472.3000, 17000.0023, 'Manual Stock Adjustment', 2, '2026-07-26 05:38:25', '2026-07-26 05:38:25'),
(244, 18, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -3150.2217, 750.0000, 'Manual Stock Adjustment', 2, '2026-07-26 05:40:07', '2026-07-26 05:40:07'),
(245, 17, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -2042.4432, 1750.0000, 'Manual Stock Adjustment', 2, '2026-07-26 05:41:46', '2026-07-26 05:41:46'),
(246, 19, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 546.0000, 2542.0000, 'Manual Stock Adjustment', 2, '2026-07-26 05:43:48', '2026-07-26 05:43:48'),
(247, NULL, 1, NULL, NULL, NULL, 'ADJUSTMENT', 18073.0000, 29000.0000, 'Manual Stock Adjustment', 2, '2026-07-26 06:54:40', '2026-07-26 06:54:40'),
(248, NULL, 2, NULL, NULL, NULL, 'ADJUSTMENT', 59842.0000, 71000.0000, 'Manual Stock Adjustment', 2, '2026-07-26 06:55:45', '2026-07-26 06:55:45'),
(249, NULL, 4, NULL, NULL, NULL, 'ADJUSTMENT', -4500.0000, 7500.0000, 'Manual Stock Adjustment', 2, '2026-07-26 06:57:30', '2026-07-26 06:57:30'),
(250, NULL, 3, NULL, NULL, NULL, 'ADJUSTMENT', -3273.0000, 8500.0000, 'Manual Stock Adjustment', 2, '2026-07-26 06:58:14', '2026-07-26 06:58:14'),
(251, NULL, 5, NULL, NULL, NULL, 'ADJUSTMENT', -5500.0000, 6500.0000, 'Manual Stock Adjustment', 2, '2026-07-26 06:58:46', '2026-07-26 06:58:46'),
(252, 14, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -16250.0023, 750.0000, 'Manual Stock Adjustment', 2, '2026-07-28 04:18:52', '2026-07-28 04:18:52'),
(253, 45, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 1625.0000, 1735.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:42:02', '2026-07-28 04:42:02'),
(254, 77, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 375.0000, 375.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:42:51', '2026-07-28 04:42:51'),
(255, 67, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 175.0000, 1175.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:43:16', '2026-07-28 04:43:16'),
(256, 67, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 175.0000, 1350.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:51:07', '2026-07-28 04:51:07'),
(257, 44, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 1625.0000, 2485.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:51:55', '2026-07-28 04:51:55'),
(258, 67, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -10.0000, 1340.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:52:36', '2026-07-28 04:52:36'),
(259, 12, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 24860.0000, 1294124.8500, 'Manual Stock Adjustment', 2, '2026-07-28 04:53:25', '2026-07-28 04:53:25'),
(260, 67, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -1215.0000, 125.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:55:27', '2026-07-28 04:55:27'),
(261, 44, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -860.0000, 1625.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:56:48', '2026-07-28 04:56:48'),
(262, 70, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:57:17', '2026-07-28 04:57:17'),
(263, 51, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:57:35', '2026-07-28 04:57:35'),
(264, 66, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -750.0000, 250.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:58:13', '2026-07-28 04:58:13'),
(265, 53, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:58:54', '2026-07-28 04:58:54'),
(266, 52, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -425.0000, 575.0000, 'Manual Stock Adjustment', 6, '2026-07-28 04:59:46', '2026-07-28 04:59:46'),
(267, 50, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:00:27', '2026-07-28 05:00:27'),
(268, 56, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:02:31', '2026-07-28 05:02:31'),
(269, 47, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -750.0000, 250.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:04:23', '2026-07-28 05:04:23'),
(270, 71, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:05:15', '2026-07-28 05:05:15'),
(271, 49, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -900.0000, 100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:05:44', '2026-07-28 05:05:44'),
(272, 72, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -975.0000, 25.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:06:12', '2026-07-28 05:06:12'),
(273, 68, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -575.0000, 425.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:06:53', '2026-07-28 05:06:53'),
(274, 57, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -975.0000, 25.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:07:17', '2026-07-28 05:07:17'),
(275, 54, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:07:43', '2026-07-28 05:07:43'),
(276, 62, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:08:02', '2026-07-28 05:08:02'),
(277, 48, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -925.0000, 75.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:08:28', '2026-07-28 05:08:28'),
(278, 63, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -900.0000, 100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:09:19', '2026-07-28 05:09:19'),
(279, 65, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -900.0000, 100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:09:36', '2026-07-28 05:09:36'),
(280, 58, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:09:50', '2026-07-28 05:09:50'),
(281, 60, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -850.0000, 150.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:10:22', '2026-07-28 05:10:22'),
(282, 73, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:10:51', '2026-07-28 05:10:51'),
(283, 61, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -750.0000, 250.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:11:13', '2026-07-28 05:11:13'),
(284, 59, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -800.0000, 200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:11:38', '2026-07-28 05:11:38'),
(285, 55, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:12:01', '2026-07-28 05:12:01'),
(286, 64, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -750.0000, 250.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:12:20', '2026-07-28 05:12:20'),
(287, 74, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -950.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:12:36', '2026-07-28 05:12:36'),
(288, 46, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -625.0000, 375.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:13:19', '2026-07-28 05:13:19'),
(289, 81, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -350.0000, 90.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:20:33', '2026-07-28 05:20:33'),
(290, 80, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 320.0000, 800.0000, 'Manual Stock Adjustment', 6, '2026-07-28 05:21:43', '2026-07-28 05:21:43'),
(291, 86, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 75.0000, 75.0000, 'Manual Stock Adjustment', 2, '2026-07-28 05:32:49', '2026-07-28 05:32:49'),
(292, 79, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2600.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:38:30', '2026-07-28 06:38:30'),
(293, 82, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 2000.0000, 2100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:39:45', '2026-07-28 06:39:45'),
(294, 85, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 200.0000, 200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:40:19', '2026-07-28 06:40:19'),
(295, 86, NULL, NULL, NULL, NULL, 'ADJUSTMENT', -4.0000, 71.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:40:55', '2026-07-28 06:40:55'),
(296, 33, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 4050.0000, 4200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:41:51', '2026-07-28 06:41:51'),
(297, 34, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 230.0000, 380.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:42:40', '2026-07-28 06:42:40'),
(298, 40, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 59850.0000, 60000.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:44:50', '2026-07-28 06:44:50'),
(299, 83, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 900.0000, 900.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:46:21', '2026-07-28 06:46:21'),
(300, 84, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 2450.0000, 2450.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:47:01', '2026-07-28 06:47:01'),
(301, NULL, 21, NULL, NULL, NULL, 'ADJUSTMENT', 1550.0000, 2100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:47:37', '2026-07-28 06:47:37'),
(302, NULL, 24, NULL, NULL, NULL, 'ADJUSTMENT', 238.0000, 400.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:48:09', '2026-07-28 06:48:09'),
(303, NULL, 19, NULL, NULL, NULL, 'ADJUSTMENT', -5800.0000, 4200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:48:54', '2026-07-28 06:48:54'),
(304, NULL, 22, NULL, NULL, NULL, 'ADJUSTMENT', 0.0000, 50.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:49:07', '2026-07-28 06:49:07'),
(305, NULL, 22, NULL, NULL, NULL, 'ADJUSTMENT', -50.0000, 0.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:49:25', '2026-07-28 06:49:25'),
(306, NULL, 23, NULL, NULL, NULL, 'ADJUSTMENT', 30.0000, 180.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:49:40', '2026-07-28 06:49:40'),
(307, NULL, 20, NULL, NULL, NULL, 'ADJUSTMENT', -9620.0000, 380.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:50:12', '2026-07-28 06:50:12'),
(308, NULL, 51, NULL, NULL, NULL, 'ADJUSTMENT', 8.0000, 8.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:50:30', '2026-07-28 06:50:30'),
(309, NULL, 46, NULL, NULL, NULL, 'ADJUSTMENT', 40.0000, 360.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:51:15', '2026-07-28 06:51:15'),
(310, NULL, 54, NULL, NULL, NULL, 'ADJUSTMENT', 1350.0000, 1500.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:52:15', '2026-07-28 06:52:15'),
(311, NULL, 14, NULL, NULL, NULL, 'ADJUSTMENT', -400.0000, 350.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:52:50', '2026-07-28 06:52:50'),
(312, NULL, 14, NULL, NULL, NULL, 'ADJUSTMENT', -25.0000, 325.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:53:04', '2026-07-28 06:53:04'),
(313, NULL, 15, NULL, NULL, NULL, 'ADJUSTMENT', 200.0000, 200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:53:46', '2026-07-28 06:53:46');
INSERT INTO `stock_ledgers` (`id`, `raw_material_id`, `packing_material_id`, `batch_id`, `grout_batch_id`, `epoxy_assembly_id`, `transaction_type`, `quantity`, `balance_after`, `remarks`, `created_by`, `created_at`, `updated_at`) VALUES
(314, NULL, 16, NULL, NULL, NULL, 'ADJUSTMENT', 450.0000, 450.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:54:15', '2026-07-28 06:54:15'),
(315, NULL, 43, NULL, NULL, NULL, 'ADJUSTMENT', 300.0000, 300.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:55:03', '2026-07-28 06:55:03'),
(316, NULL, 44, NULL, NULL, NULL, 'ADJUSTMENT', 450.0000, 450.0000, 'Manual Stock Adjustment', 6, '2026-07-28 06:56:02', '2026-07-28 06:56:02'),
(317, NULL, 62, NULL, NULL, NULL, 'IN', 7.0000, 7.0000, 'Initial Opening Stock', 2, '2026-07-28 12:18:15', '2026-07-28 12:18:15'),
(318, NULL, 55, NULL, NULL, NULL, 'ADJUSTMENT', 11050.0000, 11200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:18:33', '2026-07-28 12:18:33'),
(319, 86, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 200.0000, 271.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:19:20', '2026-07-28 12:19:20'),
(320, 85, NULL, NULL, NULL, NULL, 'ADJUSTMENT', 141.0000, 341.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:20:49', '2026-07-28 12:20:49'),
(321, NULL, 39, NULL, NULL, NULL, 'ADJUSTMENT', 190.0000, 190.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:23:31', '2026-07-28 12:23:31'),
(322, NULL, 56, NULL, NULL, NULL, 'ADJUSTMENT', 965.0000, 1955.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:25:45', '2026-07-28 12:25:45'),
(323, NULL, 56, NULL, NULL, NULL, 'ADJUSTMENT', -1930.0000, 25.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:26:34', '2026-07-28 12:26:34'),
(324, NULL, 47, NULL, NULL, NULL, 'ADJUSTMENT', 180.0000, 180.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:27:36', '2026-07-28 12:27:36'),
(325, NULL, 61, NULL, NULL, NULL, 'ADJUSTMENT', 25.0000, 1925.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:30:19', '2026-07-28 12:30:19'),
(326, NULL, 61, NULL, NULL, NULL, 'ADJUSTMENT', -1900.0000, 25.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:30:55', '2026-07-28 12:30:55'),
(327, NULL, 27, NULL, NULL, NULL, 'ADJUSTMENT', 200.0000, 200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:31:27', '2026-07-28 12:31:27'),
(328, NULL, 28, NULL, NULL, NULL, 'ADJUSTMENT', 230.0000, 230.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:32:05', '2026-07-28 12:32:05'),
(329, NULL, 52, NULL, NULL, NULL, 'ADJUSTMENT', 225.0000, 225.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:32:51', '2026-07-28 12:32:51'),
(330, NULL, 45, NULL, NULL, NULL, 'ADJUSTMENT', 380.0000, 380.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:33:30', '2026-07-28 12:33:30'),
(331, NULL, 48, NULL, NULL, NULL, 'ADJUSTMENT', 1200.0000, 1200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:33:48', '2026-07-28 12:33:48'),
(332, NULL, 50, NULL, NULL, NULL, 'ADJUSTMENT', 150.0000, 150.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:34:06', '2026-07-28 12:34:06'),
(333, NULL, 49, NULL, NULL, NULL, 'ADJUSTMENT', 1200.0000, 1200.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:34:29', '2026-07-28 12:34:29'),
(334, NULL, 10, NULL, NULL, NULL, 'ADJUSTMENT', 900.0000, 2400.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:35:06', '2026-07-28 12:35:06'),
(335, NULL, 10, NULL, NULL, NULL, 'ADJUSTMENT', -1500.0000, 900.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:35:41', '2026-07-28 12:35:41'),
(336, NULL, 11, NULL, NULL, NULL, 'ADJUSTMENT', 1500.0000, 2500.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:36:24', '2026-07-28 12:36:24'),
(337, NULL, 12, NULL, NULL, NULL, 'ADJUSTMENT', -16200.0000, 2800.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:37:23', '2026-07-28 12:37:23'),
(338, NULL, 13, NULL, NULL, NULL, 'ADJUSTMENT', -400.0000, 600.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:37:50', '2026-07-28 12:37:50'),
(339, NULL, 53, NULL, NULL, NULL, 'ADJUSTMENT', 9000.0000, 9150.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:38:17', '2026-07-28 12:38:17'),
(340, NULL, 18, NULL, NULL, NULL, 'ADJUSTMENT', 60000.0000, 60000.0000, 'Manual Stock Adjustment', 2, '2026-07-28 12:38:40', '2026-07-28 12:38:40'),
(341, NULL, 41, NULL, NULL, NULL, 'ADJUSTMENT', 400.0000, 400.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:38:44', '2026-07-28 12:38:44'),
(342, 90, NULL, NULL, NULL, NULL, 'OUT', -20.0000, 30.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Copper', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(343, NULL, 18, NULL, NULL, NULL, 'OUT', -400.0000, 59600.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Copper', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(344, NULL, 47, NULL, NULL, NULL, 'OUT', -20.0000, 160.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Copper', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(345, 88, NULL, NULL, NULL, NULL, 'OUT', -20.0000, 30.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Gold', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(346, NULL, 47, NULL, NULL, NULL, 'OUT', -20.0000, 140.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Gold', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(347, NULL, 18, NULL, NULL, NULL, 'OUT', -400.0000, 59200.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Gold', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(348, 89, NULL, NULL, NULL, NULL, 'OUT', -20.0000, 30.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Red', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(349, NULL, 47, NULL, NULL, NULL, 'OUT', -20.0000, 120.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Red', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(350, NULL, 18, NULL, NULL, NULL, 'OUT', -400.0000, 58800.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Red', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(351, 87, NULL, NULL, NULL, NULL, 'OUT', -20.0000, 30.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Silver', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(352, NULL, 47, NULL, NULL, NULL, 'OUT', -20.0000, 100.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Silver', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(353, NULL, 18, NULL, NULL, NULL, 'OUT', -400.0000, 58400.0000, 'Formula consumed to prepare 20 units of component: Jari Powder - Silver', 2, '2026-07-28 12:39:36', '2026-07-28 12:39:36'),
(354, NULL, 42, NULL, NULL, NULL, 'ADJUSTMENT', 310.0000, 310.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:39:44', '2026-07-28 12:39:44'),
(355, NULL, 25, NULL, NULL, NULL, 'ADJUSTMENT', 1100.0000, 1100.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:40:16', '2026-07-28 12:40:16'),
(356, NULL, 26, NULL, NULL, NULL, 'ADJUSTMENT', 280.0000, 280.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:40:46', '2026-07-28 12:40:46'),
(357, NULL, 17, NULL, NULL, NULL, 'ADJUSTMENT', 1475.0000, 2975.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:41:28', '2026-07-28 12:41:28'),
(358, NULL, 17, NULL, NULL, NULL, 'ADJUSTMENT', 2950.0000, 5925.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:42:13', '2026-07-28 12:42:13'),
(359, NULL, 17, NULL, NULL, NULL, 'ADJUSTMENT', -5900.0000, 25.0000, 'Manual Stock Adjustment', 6, '2026-07-28 12:42:38', '2026-07-28 12:42:38'),
(360, NULL, 22, NULL, NULL, NULL, 'ADJUSTMENT', 5000.0000, 5000.0000, 'Manual Stock Adjustment', 2, '2026-07-28 12:52:31', '2026-07-28 12:52:31'),
(361, NULL, 30, NULL, NULL, NULL, 'ADJUSTMENT', 5000.0000, 5000.0000, 'Manual Stock Adjustment', 2, '2026-07-28 12:52:45', '2026-07-28 12:52:45'),
(362, NULL, 35, NULL, NULL, NULL, 'ADJUSTMENT', 50000.0000, 50000.0000, 'Manual Stock Adjustment', 2, '2026-07-28 12:54:15', '2026-07-28 12:54:15'),
(363, NULL, 22, NULL, NULL, NULL, 'OUT', -525.0000, 4475.0000, 'Formula consumed to prepare 15 units of component: Grout Admix 200GM', 2, '2026-07-28 12:54:33', '2026-07-28 12:54:33'),
(364, NULL, 35, NULL, NULL, NULL, 'OUT', -525.0000, 49475.0000, 'Formula consumed to prepare 15 units of component: Grout Admix 200GM', 2, '2026-07-28 12:54:33', '2026-07-28 12:54:33'),
(365, NULL, 39, NULL, NULL, NULL, 'OUT', -15.0000, 175.0000, 'Formula consumed to prepare 15 units of component: Grout Admix 200GM', 2, '2026-07-28 12:54:33', '2026-07-28 12:54:33'),
(366, NULL, 22, NULL, NULL, NULL, 'OUT', -35.0000, 4440.0000, 'Formula consumed to prepare 1 units of component: Grout Admix 200GM', 2, '2026-07-29 04:27:25', '2026-07-29 04:27:25'),
(367, NULL, 35, NULL, NULL, NULL, 'OUT', -35.0000, 49440.0000, 'Formula consumed to prepare 1 units of component: Grout Admix 200GM', 2, '2026-07-29 04:27:25', '2026-07-29 04:27:25'),
(368, NULL, 39, NULL, NULL, NULL, 'OUT', -1.0000, 174.0000, 'Formula consumed to prepare 1 units of component: Grout Admix 200GM', 2, '2026-07-29 04:27:25', '2026-07-29 04:27:25'),
(369, NULL, 34, NULL, NULL, NULL, 'ADJUSTMENT', 5000.0000, 5000.0000, 'Manual Stock Adjustment', 2, '2026-07-29 14:00:10', '2026-07-29 14:00:10'),
(370, NULL, 42, NULL, NULL, NULL, 'OUT', -20.0000, 290.0000, 'Formula consumed to prepare 20 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:00:25', '2026-07-29 14:00:25'),
(371, NULL, 34, NULL, NULL, NULL, 'OUT', -80.0000, 4920.0000, 'Formula consumed to prepare 20 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:00:25', '2026-07-29 14:00:25'),
(372, NULL, 42, NULL, NULL, NULL, 'OUT', -10.0000, 280.0000, 'Formula consumed to prepare 10 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:00:42', '2026-07-29 14:00:42'),
(373, NULL, 34, NULL, NULL, NULL, 'OUT', -40.0000, 4880.0000, 'Formula consumed to prepare 10 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:00:42', '2026-07-29 14:00:42'),
(374, NULL, 42, NULL, NULL, NULL, 'OUT', -10.0000, 270.0000, 'Formula consumed to prepare 10 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:01:26', '2026-07-29 14:01:26'),
(375, NULL, 34, NULL, NULL, NULL, 'OUT', -40.0000, 4840.0000, 'Formula consumed to prepare 10 units of component: Tiles Cleaner 5-LTR', 2, '2026-07-29 14:01:26', '2026-07-29 14:01:26'),
(376, NULL, 62, NULL, NULL, NULL, 'OUT', -7.0000, 0.0000, 'Formula consumed to prepare 7 units of component: VACUUM', 2, '2026-07-29 14:02:23', '2026-07-29 14:02:23'),
(377, 79, NULL, NULL, NULL, 1, 'OUT', -1.8000, 2598.2000, 'Consumed in Epoxy assembly #1', 2, '2026-07-29 14:04:34', '2026-07-29 14:04:34'),
(378, 80, NULL, NULL, NULL, 1, 'OUT', -3.6000, 796.4000, 'Consumed in Epoxy assembly #1', 2, '2026-07-29 14:04:34', '2026-07-29 14:04:34'),
(379, 79, NULL, NULL, NULL, 2, 'OUT', -4.0000, 2594.2000, 'Consumed in Epoxy assembly #2', 2, '2026-07-29 14:04:43', '2026-07-29 14:04:43');

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

DROP TABLE IF EXISTS `todos`;
CREATE TABLE IF NOT EXISTS `todos` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `assigned_to` bigint UNSIGNED NOT NULL,
  `priority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `todos_department_id_foreign` (`department_id`),
  KEY `todos_created_by_foreign` (`created_by`),
  KEY `todos_assigned_to_foreign` (`assigned_to`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_code_unique` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'KGS', 'KG', NULL, 1, '2026-07-12 10:43:34', '2026-07-12 10:43:34'),
(2, 'GMS', 'GM', NULL, 1, '2026-07-12 10:43:45', '2026-07-12 10:43:45'),
(3, 'PCS', 'PCS', NULL, 1, '2026-07-12 10:43:55', '2026-07-12 10:43:55'),
(4, 'TON', 'TON', NULL, 1, '2026-07-22 12:33:54', '2026-07-22 12:33:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profile_photo`, `last_login_at`, `department_id`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Marketing User', 'marketing@solcon.com', NULL, '$2y$12$kTjfNSx1/qYpYhfX002DVO8gmGqshezrPcV1uOD0BLWxfku95Iq9i', NULL, NULL, NULL, 1, NULL, '2026-07-12 10:41:55', '2026-07-12 10:41:55'),
(2, 'Admin User', 'admin@solcon.com', NULL, '$2y$12$BpX8bNgNsrsnVvtv8qq56.OoFoouponRvFluTdrhnr3r36egg/i0W', NULL, NULL, NULL, 1, NULL, '2026-07-12 10:41:56', '2026-07-13 04:22:08'),
(3, 'MANAGER', 'manager@solcon.com', NULL, '$2y$12$UZKcPoAHQDHP5Q9CfCQRIeBStq3Z1t4hJWCMWxwxpzM4eKdhAQpc6', NULL, NULL, NULL, 1, NULL, '2026-07-12 10:48:34', '2026-07-12 10:48:34'),
(4, 'Adhesive', 'adhesive@solcon.com', NULL, '$2y$12$Xf2bvRwq1usVNCFwtZ4yN.fbiSmWrnfQW2AVaRI5wXC1JXX0Zb0QW', NULL, NULL, NULL, 1, NULL, '2026-07-12 10:49:59', '2026-07-12 10:49:59'),
(5, 'Grout', 'grout@solcon.com', NULL, '$2y$12$XXY1lEnGKJSOvAU4MGH8POF0W3MSdTt3Bbmcnpelxe6Pt04asF6Om', NULL, NULL, NULL, 1, NULL, '2026-07-12 10:50:44', '2026-07-12 10:50:44'),
(6, 'Epoxy', 'epoxy@solcon.com', NULL, '$2y$12$e08Bznecd8/foX7fcVhP2O5K64c3A25/OQXuTX83e397YYbUnXMQS', NULL, NULL, NULL, 1, 'GkvQb8QMth9PGIvI34SA3vCpH0gcmzBINpoAG0ADsYmf7FhsY2QkaQA7O8p1', '2026-07-12 10:51:07', '2026-07-12 10:51:07'),
(7, 'Dispatch Staff', 'dispatch@solcon.com', NULL, '$2y$12$klnTjyf6CCxUFn3PQ/32AeL7CQDlz8iHRXLhC3vzH0PyyiZoWfYTC', NULL, NULL, NULL, 1, NULL, '2026-07-21 15:55:53', '2026-07-21 15:55:53');

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--

DROP TABLE IF EXISTS `user_departments`;
CREATE TABLE IF NOT EXISTS `user_departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_departments_user_id_department_id_unique` (`user_id`,`department_id`),
  KEY `user_departments_department_id_foreign` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_departments`
--

INSERT INTO `user_departments` (`id`, `user_id`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 3, 3, NULL, NULL),
(2, 3, 1, NULL, NULL),
(3, 3, 2, NULL, NULL),
(4, 4, 2, NULL, NULL),
(5, 5, 1, NULL, NULL),
(6, 6, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

DROP TABLE IF EXISTS `user_devices`;
CREATE TABLE IF NOT EXISTS `user_devices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `device_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `browser_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_devices_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_devices`
--

INSERT INTO `user_devices` (`id`, `user_id`, `device_token`, `browser_name`, `platform`, `device_name`, `ip_address`, `last_seen_at`, `created_at`, `updated_at`) VALUES
(11, 2, 'eWTgtqTgX8Dkh2kYVh0mAf:APA91bEPWso69iKVqxu8hq5KovBZ-vH9ldOn1_9hUD7bYPrnAAs4kPr9J-2mTz_Sgv4B5oA4EN4gqygkMYrilgA1hDTeulJtfNJe8XW-rbyEjZVvro-yylg', 'Chrome', 'Windows', 'Desktop', '127.0.0.1', '2026-07-13 17:45:25', '2026-07-13 17:31:36', '2026-07-25 12:08:33'),
(15, 5, 'fWxRteuaA-PujAMwj-Xg91:APA91bH9-2TtN04hCOiSZJ41PQvx5gd_fCapf-h_XMHoohQ4QUw7P7IuM1adTCrackhD3i0u5Y6CnhU3KR6otXBbBD_sYnAa54Mjequ9f-YScY4nvZENLIA', 'Chrome', 'Linux', 'Mobile/Tablet', '127.0.0.1', '2026-07-14 05:31:17', '2026-07-14 04:08:17', '2026-07-29 14:02:42'),
(20, 6, 'fa-M29b1oq328lVxcVNYDP:APA91bHwQZry6w0iIqLmb-Opr7L2GTgUppswq5_F9QrFOCiBQ3oSRHbWU9HaD7JExFZIDazNXlFTaxsGoOuehau_8e_ej1AeN1-_YDszzjfT2xnnMcug3mA', 'Chrome', 'Linux', 'Mobile/Tablet', '127.0.0.1', '2026-07-28 06:56:03', '2026-07-28 04:42:10', '2026-07-29 14:02:43'),
(17, 1, 'foB_yuuHGmg3DfZLjiMyzq:APA91bE7L0Vy50lBEMzxf5KAbnQnNG60r2fyNKNhCmLkRjfqikVSnqHI7glns7xBBa9XY4j4Bu5gxKaxHYojCJe7_7vLUokvw1QSLYSa1EDy0zLh-awDi5c', 'Chrome', 'Linux', 'Mobile/Tablet', '127.0.0.1', '2026-07-14 11:56:53', '2026-07-14 11:56:53', '2026-07-14 11:56:53'),
(19, 2, 'cqUKPOsITd7mgnoNxROSxo:APA91bE7K7Bu6SVPf2ogcfftGjFOBHEI0VrfDZkAWidwGRfOxTEFn9Fego_GQFECncQEmV6jvLu9VblXqhWMudhwEyRWtNwCpBOD1qAWapaxKQ9XjScBJTg', 'Chrome', 'Windows', 'Desktop', '127.0.0.1', '2026-07-29 14:05:03', '2026-07-25 11:34:58', '2026-07-29 14:05:03'),
(18, 1, 'foB_yuuHGmg3DfZLjiMyzq:APA91bF5dqYBWAgI5-j0kMQlqBggasoEf77uL8M5qWmaWuWgiUPvqLc-pcagHFjhzdY4rEtigKjTlpIG_bdX3qFHbC2X5srs_gXgsdFgJ4DjBP7e3-8jcv4', 'Chrome', 'Linux', 'Mobile/Tablet', '127.0.0.1', '2026-07-14 11:59:53', '2026-07-14 11:56:53', '2026-07-14 11:59:53');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
