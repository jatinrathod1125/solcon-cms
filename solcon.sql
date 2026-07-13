-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 13, 2026 at 04:23 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

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

CREATE TABLE
    IF NOT EXISTS `activity_logs` (
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
    ) ENGINE = MyISAM AUTO_INCREMENT = 23 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--
INSERT INTO
    `activity_logs` (
        `id`,
        `user_id`,
        `action`,
        `description`,
        `module`,
        `ip_address`,
        `user_agent`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        2,
        'USER_CREATED',
        'User MANAGER (manager@solcon.com) created with role and department assignments.',
        'User Management',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 10:48:34',
        '2026-07-12 10:48:34'
    ),
    (
        2,
        2,
        'USER_CREATED',
        'User Adhesive (adhesive@solcon.com) created with role and department assignments.',
        'User Management',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 10:49:59',
        '2026-07-12 10:49:59'
    ),
    (
        3,
        2,
        'USER_CREATED',
        'User Grout (grout@solcon.com) created with role and department assignments.',
        'User Management',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 10:50:44',
        '2026-07-12 10:50:44'
    ),
    (
        4,
        2,
        'USER_CREATED',
        'User Epoxy (epoxy@solcon.com) created with role and department assignments.',
        'User Management',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 10:51:07',
        '2026-07-12 10:51:07'
    ),
    (
        5,
        2,
        'RAW_MATERIALS_IMPORTED',
        'Imported raw materials inventory successfully from CSV containing 6 records.',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:05:03',
        '2026-07-12 11:05:03'
    ),
    (
        6,
        2,
        'RAW_MATERIALS_IMPORTED',
        'Imported raw materials inventory successfully from CSV containing 20 records.',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:26:25',
        '2026-07-12 11:26:25'
    ),
    (
        7,
        2,
        'FORMULA_UPDATED',
        'Created new formula version #1 for grade ID 1.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        8,
        2,
        'FORMULA_UPDATED',
        'Created new formula version #1 for grade ID 2.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        9,
        2,
        'FORMULA_UPDATED',
        'Created new formula version #1 for grade ID 3.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        10,
        2,
        'FORMULA_UPDATED',
        'Created new formula version #1 for grade ID 7.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:33:30',
        '2026-07-12 11:33:30'
    ),
    (
        11,
        2,
        'FORMULA_UPDATED',
        'Updated formula version #1 for grade ID 7.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        12,
        2,
        'FORMULA_UPDATED',
        'Created new Grout formula version #1 for color ID 5.',
        'Formula',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-12 12:28:06',
        '2026-07-12 12:28:06'
    ),
    (
        13,
        2,
        'RAW_MATERIALS_IMPORTED',
        'Imported raw materials inventory successfully from CSV containing 9 records.',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        14,
        2,
        'MARKETING_ORDER_CREATED',
        'Marketing order MKT-20260713-001 created for party: jatin bhai',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:09:20',
        '2026-07-13 04:09:20'
    ),
    (
        15,
        2,
        'MARKETING_ORDER_STATUS_CHANGED',
        'Marketing order MKT-20260713-001 status changed from pending to in_progress',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:09:42',
        '2026-07-13 04:09:42'
    ),
    (
        16,
        2,
        'BATCH_CREATED',
        'Production batch #ADH-20260713-0001 started on machine ID 4 (Grade: F-101).',
        'Production',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:10:52',
        '2026-07-13 04:10:52'
    ),
    (
        17,
        2,
        'BATCH_COMPLETED',
        'Production batch #ADH-20260713-0001 completed. Output: 98 bags (1960 KG).',
        'Production',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        18,
        2,
        'STOCK_DEDUCTED',
        'Stock deducted for production batch #ADH-20260713-0001.',
        'Stock',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        19,
        2,
        'LEDGER_CREATED',
        'Stock ledger entries created for production batch #ADH-20260713-0001.',
        'Stock',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        20,
        2,
        'FINISHED_GOODS_ADJUSTED',
        'Manual stock adjustment (increase) of 2 units for product: N/A (20KG). Reason: opening stock.',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:20:09',
        '2026-07-13 04:20:09'
    ),
    (
        21,
        2,
        'FINISHED_GOODS_ADJUSTED',
        'Manual stock adjustment (decrease) of 100 units for product: N/A (20KG). Reason: Marketing Order MKT-20260713-001.',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:20:29',
        '2026-07-13 04:20:29'
    ),
    (
        22,
        2,
        'MARKETING_ORDER_COMPLETED',
        'Marketing order MKT-20260713-001 completed. Finished goods deducted for party: jatin bhai',
        'System',
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        '2026-07-13 04:20:29',
        '2026-07-13 04:20:29'
    );

-- --------------------------------------------------------
--
-- Table structure for table `bag_sizes`
--
DROP TABLE IF EXISTS `bag_sizes`;

CREATE TABLE
    IF NOT EXISTS `bag_sizes` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `value` decimal(8, 2) NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `bag_sizes`
--
INSERT INTO
    `bag_sizes` (
        `id`,
        `name`,
        `value`,
        `description`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        '20KG',
        20.00,
        NULL,
        1,
        '2026-07-12 10:47:23',
        '2026-07-12 10:47:23'
    ),
    (
        2,
        '25KG',
        25.00,
        NULL,
        1,
        '2026-07-12 10:47:34',
        '2026-07-12 10:47:34'
    );

-- --------------------------------------------------------
--
-- Table structure for table `cache`
--
DROP TABLE IF EXISTS `cache`;

CREATE TABLE
    IF NOT EXISTS `cache` (
        `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `expiration` bigint NOT NULL,
        PRIMARY KEY (`key`),
        KEY `cache_expiration_index` (`expiration`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--
INSERT INTO
    `cache` (`key`, `value`, `expiration`)
VALUES
    (
        'laravel-cache-app_settings_cache',
        'a:8:{s:12:\"factory_name\";s:17:\"Solcon Industries\";s:12:\"factory_logo\";N;s:13:\"report_header\";s:41:\"Solcon Industries Daily Production Report\";s:11:\"footer_text\";s:35:\"Solcon Production Management System\";s:16:\"default_bag_size\";s:2:\"20\";s:8:\"timezone\";s:12:\"Asia/Kolkata\";s:8:\"ui_theme\";s:4:\"dark\";s:16:\"ui_primary_color\";s:6:\"indigo\";}',
        2099276529
    ),
    (
        'laravel-cache-active_department_ids',
        'a:3:{i:0;i:3;i:1;i:1;i:2;i:2;}',
        1783918969
    );

-- --------------------------------------------------------
--
-- Table structure for table `cache_locks`
--
DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE
    IF NOT EXISTS `cache_locks` (
        `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `expiration` bigint NOT NULL,
        PRIMARY KEY (`key`),
        KEY `cache_locks_expiration_index` (`expiration`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `colors`
--
DROP TABLE IF EXISTS `colors`;

CREATE TABLE
    IF NOT EXISTS `colors` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `department_id` bigint UNSIGNED NOT NULL,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `packing_size` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `default_cement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
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
    ) ENGINE = MyISAM AUTO_INCREMENT = 31 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--
INSERT INTO
    `colors` (
        `id`,
        `department_id`,
        `name`,
        `code`,
        `packing_size`,
        `default_cement`,
        `is_active`,
        `description`,
        `created_by`,
        `updated_by`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        'White',
        'WHT',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        2,
        1,
        'White 500GM',
        'WHT-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        3,
        1,
        'Ivory',
        'IVY',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        4,
        1,
        'Ivory 500GM',
        'IVY-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        5,
        1,
        'Black',
        'BLK',
        '1 KG',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        6,
        1,
        'Black 500GM',
        'BLK-500',
        '500 GM',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        7,
        1,
        'Gray',
        'GRY',
        '1 KG',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        8,
        1,
        'Gray 500GM',
        'GRY-500',
        '500 GM',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        9,
        1,
        'Alpine Blue',
        'ABL',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        10,
        1,
        'Alpine Blue 500GM',
        'ABL-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        11,
        1,
        'Light Blue',
        'LBL',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        12,
        1,
        'Light Blue 500GM',
        'LBL-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        13,
        1,
        'Red',
        'RED',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        14,
        1,
        'Red 500GM',
        'RED-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        15,
        1,
        'Magenta',
        'MAG',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        16,
        1,
        'Magenta 500GM',
        'MAG-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        17,
        1,
        'Terracotta',
        'TER',
        '1 KG',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        18,
        1,
        'Terracotta 500GM',
        'TER-500',
        '500 GM',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        19,
        1,
        'Wooden',
        'WOD',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        20,
        1,
        'Wooden 500GM',
        'WOD-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        21,
        1,
        'Bottle Green',
        'BGR',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        22,
        1,
        'Bottle Green 500GM',
        'BGR-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        23,
        1,
        'Pink',
        'Pnk',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:34:04'
    ),
    (
        24,
        1,
        'Pink 500GM',
        'Pnk-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:34:27'
    ),
    (
        25,
        1,
        'Orange',
        'ORG',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        26,
        1,
        'Orange 500GM',
        'ORG-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        27,
        1,
        'Coffee Brown',
        'CBR',
        '1 KG',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        28,
        1,
        'Coffee Brown 500GM',
        'CBR-500',
        '500 GM',
        'Gray Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:25:59',
        '2026-07-12 12:25:59'
    ),
    (
        29,
        1,
        'Jesalmer',
        'JSL',
        '1 KG',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:34:53',
        '2026-07-12 12:35:02'
    ),
    (
        30,
        1,
        'Jesalmer 500',
        'JSL-500',
        '500 GM',
        'White Cement',
        1,
        NULL,
        2,
        2,
        '2026-07-12 12:35:20',
        '2026-07-12 12:35:30'
    );

-- --------------------------------------------------------
--
-- Table structure for table `departments`
--
DROP TABLE IF EXISTS `departments`;

CREATE TABLE
    IF NOT EXISTS `departments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `departments_code_unique` (`code`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--
INSERT INTO
    `departments` (
        `id`,
        `name`,
        `code`,
        `description`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Grout Department',
        'GRT',
        'Production department for Grout products',
        1,
        '2026-07-12 10:41:53',
        '2026-07-12 10:46:09'
    ),
    (
        2,
        'Tile Adheshive Department',
        'Tad',
        NULL,
        1,
        '2026-07-12 10:45:34',
        '2026-07-12 10:45:34'
    ),
    (
        3,
        'Epoxy Department',
        'EP',
        NULL,
        1,
        '2026-07-12 10:45:49',
        '2026-07-12 10:45:49'
    );

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_assemblies`
--
DROP TABLE IF EXISTS `epoxy_assemblies`;

CREATE TABLE
    IF NOT EXISTS `epoxy_assemblies` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_components`
--
DROP TABLE IF EXISTS `epoxy_components`;

CREATE TABLE
    IF NOT EXISTS `epoxy_components` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `requires_color` tinyint (1) NOT NULL DEFAULT '0',
        `template_material_id` bigint UNSIGNED DEFAULT NULL,
        `bulk_material_id` bigint UNSIGNED DEFAULT NULL,
        `bulk_qty_per_unit` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `packaging_material_id` bigint UNSIGNED DEFAULT NULL,
        `packaging_qty_per_unit` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `purpose` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Assembly Component',
        `unit_id` bigint UNSIGNED DEFAULT NULL,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_component_formulas`
--
DROP TABLE IF EXISTS `epoxy_component_formulas`;

CREATE TABLE
    IF NOT EXISTS `epoxy_component_formulas` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `epoxy_component_id` bigint UNSIGNED NOT NULL,
        `version` int NOT NULL DEFAULT '1',
        `is_active` tinyint (1) NOT NULL DEFAULT '0',
        `description` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint UNSIGNED NOT NULL,
        `updated_by` bigint UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `epoxy_component_formulas_epoxy_component_id_foreign` (`epoxy_component_id`),
        KEY `epoxy_component_formulas_created_by_foreign` (`created_by`),
        KEY `epoxy_component_formulas_updated_by_foreign` (`updated_by`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_component_formula_items`
--
DROP TABLE IF EXISTS `epoxy_component_formula_items`;

CREATE TABLE
    IF NOT EXISTS `epoxy_component_formula_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `epoxy_component_formula_id` bigint UNSIGNED NOT NULL,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `quantity` decimal(12, 4) NOT NULL,
        `unit_id` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `epoxy_component_formula_items_epoxy_component_formula_id_foreign` (`epoxy_component_formula_id`),
        KEY `epoxy_component_formula_items_raw_material_id_foreign` (`raw_material_id`),
        KEY `epoxy_component_formula_items_unit_id_foreign` (`unit_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_component_mappings`
--
DROP TABLE IF EXISTS `epoxy_component_mappings`;

CREATE TABLE
    IF NOT EXISTS `epoxy_component_mappings` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `epoxy_component_id` bigint UNSIGNED NOT NULL,
        `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `comp_color_mapping_unique` (`epoxy_component_id`, `epoxy_filler_color_id`),
        KEY `epoxy_component_mappings_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
        KEY `epoxy_component_mappings_raw_material_id_foreign` (`raw_material_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_component_preparations`
--
DROP TABLE IF EXISTS `epoxy_component_preparations`;

CREATE TABLE
    IF NOT EXISTS `epoxy_component_preparations` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_filler_colors`
--
DROP TABLE IF EXISTS `epoxy_filler_colors`;

CREATE TABLE
    IF NOT EXISTS `epoxy_filler_colors` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `description` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint UNSIGNED DEFAULT NULL,
        `updated_by` bigint UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `epoxy_filler_colors_code_unique` (`code`),
        KEY `epoxy_filler_colors_created_by_foreign` (`created_by`),
        KEY `epoxy_filler_colors_updated_by_foreign` (`updated_by`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_formulas`
--
DROP TABLE IF EXISTS `epoxy_formulas`;

CREATE TABLE
    IF NOT EXISTS `epoxy_formulas` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `epoxy_product_id` bigint UNSIGNED NOT NULL,
        `version` int NOT NULL DEFAULT '1',
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `description` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `epoxy_formulas_epoxy_product_id_version_unique` (`epoxy_product_id`, `version`),
        KEY `epoxy_formulas_created_by_foreign` (`created_by`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_formula_items`
--
DROP TABLE IF EXISTS `epoxy_formula_items`;

CREATE TABLE
    IF NOT EXISTS `epoxy_formula_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `epoxy_formula_id` bigint UNSIGNED NOT NULL,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `quantity` decimal(10, 4) NOT NULL,
        `unit_id` bigint UNSIGNED NOT NULL,
        `is_dynamic_color` tinyint (1) NOT NULL DEFAULT '0',
        `material_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `epoxy_formula_items_epoxy_formula_id_foreign` (`epoxy_formula_id`),
        KEY `epoxy_formula_items_raw_material_id_foreign` (`raw_material_id`),
        KEY `epoxy_formula_items_unit_id_foreign` (`unit_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `epoxy_products`
--
DROP TABLE IF EXISTS `epoxy_products`;

CREATE TABLE
    IF NOT EXISTS `epoxy_products` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `requires_color` tinyint (1) NOT NULL DEFAULT '0',
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `description` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint UNSIGNED DEFAULT NULL,
        `updated_by` bigint UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `epoxy_products_code_unique` (`code`),
        KEY `epoxy_products_created_by_foreign` (`created_by`),
        KEY `epoxy_products_updated_by_foreign` (`updated_by`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `failed_jobs`
--
DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE
    IF NOT EXISTS `failed_jobs` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `finished_goods`
--
DROP TABLE IF EXISTS `finished_goods`;

CREATE TABLE
    IF NOT EXISTS `finished_goods` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `department_id` bigint UNSIGNED NOT NULL,
        `grade_id` bigint UNSIGNED DEFAULT NULL,
        `color_id` bigint UNSIGNED DEFAULT NULL,
        `epoxy_filler_color_id` bigint UNSIGNED DEFAULT NULL,
        `epoxy_product_id` bigint UNSIGNED DEFAULT NULL,
        `packing` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `available_bags` int NOT NULL DEFAULT '0',
        `available_weight` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `minimum_stock` int NOT NULL DEFAULT '20',
        `last_production_date` datetime DEFAULT NULL,
        `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
        `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `epoxy_component_id` bigint UNSIGNED DEFAULT NULL,
        `coupon_raw_material_id` bigint UNSIGNED DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `finished_goods_unique` (
            `grade_id`,
            `color_id`,
            `epoxy_product_id`,
            `packing`,
            `coupon_raw_material_id`
        ),
        KEY `finished_goods_department_id_foreign` (`department_id`),
        KEY `finished_goods_color_id_foreign` (`color_id`),
        KEY `finished_goods_epoxy_product_id_foreign` (`epoxy_product_id`),
        KEY `finished_goods_epoxy_filler_color_id_foreign` (`epoxy_filler_color_id`),
        KEY `finished_goods_epoxy_component_id_foreign` (`epoxy_component_id`),
        KEY `finished_goods_coupon_raw_material_id_foreign` (`coupon_raw_material_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `finished_goods`
--
INSERT INTO
    `finished_goods` (
        `id`,
        `department_id`,
        `grade_id`,
        `color_id`,
        `epoxy_filler_color_id`,
        `epoxy_product_id`,
        `packing`,
        `available_bags`,
        `available_weight`,
        `minimum_stock`,
        `last_production_date`,
        `status`,
        `remarks`,
        `created_at`,
        `updated_at`,
        `epoxy_component_id`,
        `coupon_raw_material_id`
    )
VALUES
    (
        1,
        2,
        1,
        NULL,
        NULL,
        NULL,
        '20KG',
        0,
        0.0000,
        20,
        '2026-07-13 09:41:06',
        'out_of_stock',
        'Auto-deducted for order MKT-20260713-001, Party: jatin bhai',
        '2026-07-13 04:11:06',
        '2026-07-13 04:20:29',
        NULL,
        NULL
    ),
    (
        2,
        2,
        2,
        NULL,
        NULL,
        NULL,
        '20KG',
        100,
        2000.0000,
        20,
        '2026-07-13 09:58:46',
        'active',
        NULL,
        '2026-07-13 09:58:46',
        '2026-07-13 09:58:46',
        NULL,
        7
    );

-- --------------------------------------------------------
--
-- Table structure for table `formulas`
--
DROP TABLE IF EXISTS `formulas`;

CREATE TABLE
    IF NOT EXISTS `formulas` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `grade_id` bigint UNSIGNED NOT NULL,
        `version` int NOT NULL,
        `remarks` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_by` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `formulas_grade_id_version_unique` (`grade_id`, `version`),
        KEY `formulas_created_by_foreign` (`created_by`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `formulas`
--
INSERT INTO
    `formulas` (
        `id`,
        `grade_id`,
        `version`,
        `remarks`,
        `is_active`,
        `created_by`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        1,
        NULL,
        1,
        2,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        2,
        2,
        1,
        NULL,
        1,
        2,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        3,
        3,
        1,
        NULL,
        1,
        2,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        4,
        7,
        1,
        NULL,
        1,
        2,
        '2026-07-12 11:33:30',
        '2026-07-12 11:33:30'
    );

-- --------------------------------------------------------
--
-- Table structure for table `formula_items`
--
DROP TABLE IF EXISTS `formula_items`;

CREATE TABLE
    IF NOT EXISTS `formula_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `formula_id` bigint UNSIGNED NOT NULL,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `quantity` decimal(12, 4) NOT NULL,
        `unit_id` bigint UNSIGNED NOT NULL,
        `consumption_method` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'formula',
        `consumption_per_unit` decimal(12, 4) NOT NULL DEFAULT '1.0000',
        `sequence` int NOT NULL DEFAULT '1',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `formula_items_formula_id_foreign` (`formula_id`),
        KEY `formula_items_raw_material_id_foreign` (`raw_material_id`),
        KEY `formula_items_unit_id_foreign` (`unit_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 35 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `formula_items`
--
INSERT INTO
    `formula_items` (
        `id`,
        `formula_id`,
        `raw_material_id`,
        `quantity`,
        `unit_id`,
        `consumption_method`,
        `consumption_per_unit`,
        `sequence`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        1,
        1.0000,
        3,
        'output',
        1.0000,
        1,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        2,
        1,
        12,
        1280.0000,
        1,
        'formula',
        1.0000,
        2,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        3,
        1,
        13,
        500.0000,
        1,
        'formula',
        1.0000,
        3,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        4,
        1,
        15,
        200.0000,
        1,
        'formula',
        1.0000,
        4,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        5,
        1,
        17,
        4.0000,
        1,
        'formula',
        1.0000,
        5,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        6,
        1,
        18,
        4.0000,
        1,
        'formula',
        1.0000,
        6,
        '2026-07-12 11:28:52',
        '2026-07-12 11:28:52'
    ),
    (
        7,
        2,
        2,
        1.0000,
        3,
        'output',
        1.0000,
        1,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        8,
        2,
        12,
        1280.0000,
        1,
        'formula',
        1.0000,
        2,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        9,
        2,
        13,
        600.0000,
        1,
        'formula',
        1.0000,
        3,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        10,
        2,
        15,
        200.0000,
        1,
        'formula',
        1.0000,
        4,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        11,
        2,
        17,
        10.0000,
        1,
        'formula',
        1.0000,
        5,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        12,
        2,
        18,
        4.0000,
        1,
        'formula',
        1.0000,
        6,
        '2026-07-12 11:29:49',
        '2026-07-12 11:29:49'
    ),
    (
        13,
        3,
        3,
        1.0000,
        3,
        'output',
        1.0000,
        1,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        14,
        3,
        12,
        1280.0000,
        1,
        'formula',
        1.0000,
        2,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        15,
        3,
        13,
        700.0000,
        1,
        'formula',
        1.0000,
        3,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        16,
        3,
        15,
        200.0000,
        1,
        'formula',
        1.0000,
        4,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        17,
        3,
        17,
        28.0000,
        1,
        'formula',
        1.0000,
        5,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        18,
        3,
        18,
        6.0000,
        1,
        'formula',
        1.0000,
        6,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        19,
        3,
        19,
        2.0000,
        1,
        'formula',
        1.0000,
        7,
        '2026-07-12 11:30:57',
        '2026-07-12 11:30:57'
    ),
    (
        33,
        4,
        18,
        7.0000,
        1,
        'formula',
        1.0000,
        7,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        32,
        4,
        20,
        36.0000,
        1,
        'formula',
        1.0000,
        6,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        31,
        4,
        17,
        60.0000,
        1,
        'formula',
        1.0000,
        5,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        30,
        4,
        15,
        200.0000,
        1,
        'formula',
        1.0000,
        4,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        29,
        4,
        13,
        700.0000,
        1,
        'formula',
        1.0000,
        3,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        28,
        4,
        12,
        1280.0000,
        1,
        'formula',
        1.0000,
        2,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        27,
        4,
        6,
        1.0000,
        3,
        'output',
        1.0000,
        1,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    ),
    (
        34,
        4,
        21,
        1.2000,
        1,
        'formula',
        1.0000,
        8,
        '2026-07-12 11:35:16',
        '2026-07-12 11:35:16'
    );

-- --------------------------------------------------------
--
-- Table structure for table `grades`
--
DROP TABLE IF EXISTS `grades`;

CREATE TABLE
    IF NOT EXISTS `grades` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `department_id` bigint UNSIGNED NOT NULL,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `bag_size_id` bigint UNSIGNED NOT NULL,
        `output_unit_id` bigint UNSIGNED NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
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
    ) ENGINE = MyISAM AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--
INSERT INTO
    `grades` (
        `id`,
        `department_id`,
        `name`,
        `code`,
        `bag_size_id`,
        `output_unit_id`,
        `description`,
        `is_active`,
        `created_by`,
        `updated_by`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        2,
        'F-101',
        'F-101',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:54:33',
        '2026-07-12 10:54:33'
    ),
    (
        2,
        2,
        'F-107',
        'F-107',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:54:48',
        '2026-07-12 10:54:48'
    ),
    (
        3,
        2,
        'F-121',
        'F-121',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:55:01',
        '2026-07-12 10:55:01'
    ),
    (
        4,
        2,
        'F-115 (White)',
        'F-115',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:55:40',
        '2026-07-12 10:56:18'
    ),
    (
        5,
        2,
        'F-133 (White)',
        'F-133',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:56:02',
        '2026-07-12 10:56:02'
    ),
    (
        6,
        2,
        'F-147 (White)',
        'F-147',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:56:46',
        '2026-07-12 10:56:46'
    ),
    (
        7,
        2,
        'F-147 (Gray)',
        'F-147G',
        1,
        1,
        NULL,
        1,
        2,
        2,
        '2026-07-12 10:57:18',
        '2026-07-12 10:57:18'
    );

-- --------------------------------------------------------
--
-- Table structure for table `grout_formulas`
--
DROP TABLE IF EXISTS `grout_formulas`;

CREATE TABLE
    IF NOT EXISTS `grout_formulas` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `color_id` bigint UNSIGNED NOT NULL,
        `version` int NOT NULL,
        `remarks` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_by` bigint UNSIGNED NOT NULL,
        `updated_by` bigint UNSIGNED DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `grout_formulas_color_id_version_unique` (`color_id`, `version`),
        KEY `grout_formulas_created_by_foreign` (`created_by`),
        KEY `grout_formulas_updated_by_foreign` (`updated_by`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `grout_formulas`
--
INSERT INTO
    `grout_formulas` (
        `id`,
        `color_id`,
        `version`,
        `remarks`,
        `is_active`,
        `created_by`,
        `updated_by`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        5,
        1,
        NULL,
        1,
        2,
        NULL,
        '2026-07-12 12:28:06',
        '2026-07-12 12:28:06'
    );

-- --------------------------------------------------------
--
-- Table structure for table `grout_formula_items`
--
DROP TABLE IF EXISTS `grout_formula_items`;

CREATE TABLE
    IF NOT EXISTS `grout_formula_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `grout_formula_id` bigint UNSIGNED NOT NULL,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `quantity` decimal(10, 4) NOT NULL,
        `unit_id` bigint UNSIGNED NOT NULL,
        `mix_stage` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `display_order` int NOT NULL DEFAULT '0',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `grout_formula_items_grout_formula_id_foreign` (`grout_formula_id`),
        KEY `grout_formula_items_raw_material_id_foreign` (`raw_material_id`),
        KEY `grout_formula_items_unit_id_foreign` (`unit_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `grout_formula_items`
--
INSERT INTO
    `grout_formula_items` (
        `id`,
        `grout_formula_id`,
        `raw_material_id`,
        `quantity`,
        `unit_id`,
        `mix_stage`,
        `display_order`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        16,
        225.0000,
        1,
        'Stage 1',
        0,
        '2026-07-12 12:28:06',
        '2026-07-12 12:28:06'
    ),
    (
        2,
        1,
        17,
        2.0000,
        1,
        'Stage 2',
        1,
        '2026-07-12 12:28:06',
        '2026-07-12 12:28:06'
    ),
    (
        3,
        1,
        18,
        0.6000,
        1,
        'Stage 1',
        2,
        '2026-07-12 12:28:06',
        '2026-07-12 12:28:06'
    );

-- --------------------------------------------------------
--
-- Table structure for table `grout_production_batches`
--
DROP TABLE IF EXISTS `grout_production_batches`;

CREATE TABLE
    IF NOT EXISTS `grout_production_batches` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `machine_id` bigint UNSIGNED NOT NULL,
        `color_id` bigint UNSIGNED NOT NULL,
        `grout_formula_id` bigint UNSIGNED NOT NULL,
        `formula_snapshot` json NOT NULL,
        `operator_id` bigint UNSIGNED NOT NULL,
        `status` enum (
            'Waiting',
            'Stage 1 Mixing',
            'Timer Running',
            'Waiting Cement',
            'Stage 2 Mixing',
            'Ready For Packing',
            'Packing',
            'Completed'
        ) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Waiting',
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
        `total_weight_kg` decimal(12, 4) DEFAULT NULL,
        `remarks` text COLLATE utf8mb4_unicode_ci,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `timer_skipped` tinyint (1) NOT NULL DEFAULT '0',
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `jobs`
--
DROP TABLE IF EXISTS `jobs`;

CREATE TABLE
    IF NOT EXISTS `jobs` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `attempts` smallint UNSIGNED NOT NULL,
        `reserved_at` int UNSIGNED DEFAULT NULL,
        `available_at` int UNSIGNED NOT NULL,
        `created_at` int UNSIGNED NOT NULL,
        PRIMARY KEY (`id`),
        KEY `jobs_queue_index` (`queue`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `job_batches`
--
DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE
    IF NOT EXISTS `job_batches` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `machines`
--
DROP TABLE IF EXISTS `machines`;

CREATE TABLE
    IF NOT EXISTS `machines` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `department_id` bigint UNSIGNED NOT NULL,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `machines_code_unique` (`code`),
        KEY `machines_department_id_foreign` (`department_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 9 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--
INSERT INTO
    `machines` (
        `id`,
        `department_id`,
        `name`,
        `code`,
        `description`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        'Automatic Packing Machine M-01',
        'M-01',
        'Automatic packing machine for White & Ivory grouts (500 GM & 1 KG pouches)',
        1,
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        2,
        1,
        'Manual Mixing Machine M-04',
        'M-04',
        'Manual mixer for colored grouts with 1-hour dry mix timers',
        1,
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        3,
        1,
        'Manual Mixing Machine M-05',
        'M-05',
        'Manual mixer for colored grouts with 1-hour dry mix timers',
        1,
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        4,
        2,
        'M-07',
        'M-07',
        NULL,
        1,
        '2026-07-12 10:46:29',
        '2026-07-12 10:46:29'
    ),
    (
        5,
        2,
        'M-08',
        'M-08',
        NULL,
        1,
        '2026-07-12 10:46:41',
        '2026-07-12 10:46:41'
    ),
    (
        6,
        2,
        'M-09',
        'M-09',
        NULL,
        1,
        '2026-07-12 10:46:54',
        '2026-07-12 10:46:54'
    ),
    (
        7,
        2,
        'Pan-Mixer',
        'M-02',
        NULL,
        1,
        '2026-07-12 10:53:37',
        '2026-07-12 10:53:37'
    ),
    (
        8,
        2,
        'Pan-Mixer',
        'M-03',
        NULL,
        1,
        '2026-07-12 10:53:52',
        '2026-07-12 10:53:52'
    );

-- --------------------------------------------------------
--
-- Table structure for table `marketing_orders`
--
DROP TABLE IF EXISTS `marketing_orders`;

CREATE TABLE
    IF NOT EXISTS `marketing_orders` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `party_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `vehicle_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `order_date` date NOT NULL,
        `priority` enum ('low', 'medium', 'high', 'urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
        `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
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
        KEY `marketing_orders_status_sort_order_index` (`status`, `sort_order`),
        KEY `marketing_orders_party_name_index` (`party_name`),
        KEY `marketing_orders_order_date_index` (`order_date`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_orders`
--
INSERT INTO
    `marketing_orders` (
        `id`,
        `order_number`,
        `party_name`,
        `vehicle_number`,
        `order_date`,
        `priority`,
        `status`,
        `availability`,
        `remarks`,
        `created_by`,
        `approved_by`,
        `approved_at`,
        `completed_at`,
        `cancelled_at`,
        `cancel_reason`,
        `sort_order`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'MKT-20260713-001',
        'jatin bhai',
        NULL,
        '2026-07-14',
        'urgent',
        'completed',
        'available',
        NULL,
        2,
        2,
        '2026-07-13 04:09:42',
        '2026-07-13 04:20:29',
        NULL,
        NULL,
        1,
        '2026-07-13 04:09:20',
        '2026-07-13 04:20:29'
    );

-- --------------------------------------------------------
--
-- Table structure for table `marketing_order_items`
--
DROP TABLE IF EXISTS `marketing_order_items`;

CREATE TABLE
    IF NOT EXISTS `marketing_order_items` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `marketing_order_id` bigint UNSIGNED NOT NULL,
        `department_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `grade_id` bigint UNSIGNED DEFAULT NULL,
        `color_id` bigint UNSIGNED DEFAULT NULL,
        `epoxy_product_id` bigint UNSIGNED DEFAULT NULL,
        `quantity_bags` int NOT NULL,
        `quantity_kg` decimal(10, 2) DEFAULT NULL,
        `packing` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `coupon_raw_material_id` bigint UNSIGNED DEFAULT NULL,
        `coupon_quantity` int DEFAULT NULL,
        `is_product_available` tinyint (1) NOT NULL DEFAULT '0',
        `is_coupon_available` tinyint (1) DEFAULT NULL,
        `item_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
        `remarks` text COLLATE utf8mb4_unicode_ci,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `marketing_order_items_grade_id_foreign` (`grade_id`),
        KEY `marketing_order_items_color_id_foreign` (`color_id`),
        KEY `marketing_order_items_epoxy_product_id_foreign` (`epoxy_product_id`),
        KEY `marketing_order_items_coupon_raw_material_id_foreign` (`coupon_raw_material_id`),
        KEY `marketing_order_items_marketing_order_id_index` (`marketing_order_id`),
        KEY `marketing_order_items_department_code_index` (`department_code`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_order_items`
--
INSERT INTO
    `marketing_order_items` (
        `id`,
        `marketing_order_id`,
        `department_code`,
        `grade_id`,
        `color_id`,
        `epoxy_product_id`,
        `quantity_bags`,
        `quantity_kg`,
        `packing`,
        `coupon_raw_material_id`,
        `coupon_quantity`,
        `is_product_available`,
        `is_coupon_available`,
        `item_status`,
        `remarks`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        'TAD',
        1,
        NULL,
        NULL,
        100,
        NULL,
        '20KG',
        NULL,
        NULL,
        0,
        NULL,
        'completed',
        NULL,
        '2026-07-13 04:09:20',
        '2026-07-13 04:20:32'
    );

-- --------------------------------------------------------
--
-- Table structure for table `migrations`
--
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE
    IF NOT EXISTS `migrations` (
        `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
        `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `batch` int NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 50 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--
INSERT INTO
    `migrations` (`id`, `migration`, `batch`)
VALUES
    (1, '0001_01_01_000000_create_users_table', 1),
    (2, '0001_01_01_000001_create_cache_table', 1),
    (3, '0001_01_01_000002_create_jobs_table', 1),
    (
        4,
        '2026_06_27_000000_create_roles_and_permissions_tables',
        1
    ),
    (
        5,
        '2026_06_27_000001_create_departments_table',
        1
    ),
    (6, '2026_06_27_000002_create_machines_table', 1),
    (7, '2026_06_27_000003_create_units_table', 1),
    (8, '2026_06_27_000004_create_bag_sizes_table', 1),
    (
        9,
        '2026_06_27_000005_create_raw_materials_table',
        1
    ),
    (10, '2026_06_27_000006_create_grades_table', 1),
    (11, '2026_06_27_000007_create_formulas_table', 1),
    (
        12,
        '2026_06_27_000008_create_formula_items_table',
        1
    ),
    (
        13,
        '2026_06_27_000009_create_production_batches_table',
        1
    ),
    (
        14,
        '2026_06_27_000010_create_stock_ledgers_table',
        1
    ),
    (
        15,
        '2026_06_27_000011_create_stock_adjustments_table',
        1
    ),
    (
        16,
        '2026_06_27_000012_create_activity_logs_table',
        1
    ),
    (
        17,
        '2026_06_28_000000_add_formula_snapshot_to_production_batches_table',
        1
    ),
    (18, '2026_06_28_000002_create_settings_table', 1),
    (
        19,
        '2026_06_28_000004_add_profile_fields_to_users_table',
        1
    ),
    (
        20,
        '2026_06_28_000005_add_module_to_activity_logs_table',
        1
    ),
    (
        21,
        '2026_06_29_000000_create_user_departments_table',
        1
    ),
    (22, '2026_06_29_000001_create_colors_table', 1),
    (
        23,
        '2026_06_29_000002_create_grout_formulas_table',
        1
    ),
    (
        24,
        '2026_06_29_000003_create_grout_formula_items_table',
        1
    ),
    (
        25,
        '2026_06_29_000004_create_grout_production_batches_table',
        1
    ),
    (
        26,
        '2026_06_29_000005_add_grout_batch_id_to_stock_ledgers_table',
        1
    ),
    (
        27,
        '2026_06_30_000000_add_skip_timer_fields_to_grout_production_batches_table',
        1
    ),
    (
        28,
        '2026_07_01_000000_create_epoxy_products_table',
        1
    ),
    (
        29,
        '2026_07_01_000001_create_epoxy_formulas_table',
        1
    ),
    (
        30,
        '2026_07_01_000002_create_epoxy_formula_items_table',
        1
    ),
    (
        31,
        '2026_07_01_000003_create_epoxy_assemblies_table',
        1
    ),
    (
        32,
        '2026_07_01_000004_add_epoxy_assembly_id_to_stock_ledgers_table',
        1
    ),
    (
        33,
        '2026_07_02_000001_create_finished_goods_table',
        1
    ),
    (
        34,
        '2026_07_05_000000_add_consumption_method_to_formula_items_table',
        1
    ),
    (
        35,
        '2026_07_05_000001_remove_dual_color_from_colors_table',
        1
    ),
    (
        36,
        '2026_07_06_000000_create_epoxy_filler_colors_table',
        1
    ),
    (
        37,
        '2026_07_06_000001_create_epoxy_components_table',
        1
    ),
    (
        38,
        '2026_07_06_000002_add_epoxy_color_to_tables',
        1
    ),
    (
        39,
        '2026_07_06_000003_upgrade_epoxy_components_table',
        1
    ),
    (
        40,
        '2026_07_06_000004_create_user_devices_table',
        1
    ),
    (
        41,
        '2026_07_06_000005_create_notifications_table',
        1
    ),
    (
        42,
        '2026_07_09_000000_change_production_batches_status_to_string',
        1
    ),
    (43, '2026_07_09_000001_create_todos_table', 1),
    (
        44,
        '2026_07_10_160000_add_is_coupon_to_raw_materials_table',
        1
    ),
    (
        45,
        '2026_07_10_160001_create_marketing_orders_table',
        1
    ),
    (
        46,
        '2026_07_10_160002_create_marketing_order_items_table',
        1
    ),
    (47, '2026_07_10_160003_seed_marketing_role', 1),
    (48, '2026_07_10_160004_seed_all_coupons', 1),
    (
        49,
        '2026_07_13_000000_add_coupon_raw_material_id_to_finished_goods_table',
        2
    );

-- --------------------------------------------------------
--
-- Table structure for table `notifications`
--
DROP TABLE IF EXISTS `notifications`;

CREATE TABLE
    IF NOT EXISTS `notifications` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `password_reset_tokens`
--
DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE
    IF NOT EXISTS `password_reset_tokens` (
        `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`email`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `permissions`
--
DROP TABLE IF EXISTS `permissions`;

CREATE TABLE
    IF NOT EXISTS `permissions` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `permissions_slug_unique` (`slug`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--
INSERT INTO
    `permissions` (
        `id`,
        `name`,
        `slug`,
        `description`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Manage Masters',
        'manage-masters',
        'Create and edit departments, machines, units, raw materials and grades',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        2,
        'Manage Formulas',
        'manage-formulas',
        'Define formulas for grades',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        3,
        'Log Production',
        'log-production',
        'Start, track and complete production batches',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        4,
        'View Reports',
        'view-reports',
        'Generate and view production reports',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        5,
        'Manage Users',
        'manage-users',
        'Manage user accounts and permissions',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        6,
        'Manage Settings',
        'manage-settings',
        'Manage global factory settings',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    );

-- --------------------------------------------------------
--
-- Table structure for table `permission_role`
--
DROP TABLE IF EXISTS `permission_role`;

CREATE TABLE
    IF NOT EXISTS `permission_role` (
        `permission_id` bigint UNSIGNED NOT NULL,
        `role_id` bigint UNSIGNED NOT NULL,
        PRIMARY KEY (`permission_id`, `role_id`),
        KEY `permission_role_role_id_foreign` (`role_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--
INSERT INTO
    `permission_role` (`permission_id`, `role_id`)
VALUES
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

CREATE TABLE
    IF NOT EXISTS `production_batches` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `batch_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `machine_id` bigint UNSIGNED NOT NULL,
        `grade_id` bigint UNSIGNED NOT NULL,
        `formula_id` bigint UNSIGNED NOT NULL,
        `formula_snapshot` json DEFAULT NULL,
        `supervisor_id` bigint UNSIGNED NOT NULL,
        `start_time` datetime NOT NULL,
        `end_time` datetime DEFAULT NULL,
        `output_bags` decimal(12, 4) DEFAULT NULL,
        `output_kg` decimal(12, 4) DEFAULT NULL,
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
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `production_batches`
--
INSERT INTO
    `production_batches` (
        `id`,
        `batch_no`,
        `machine_id`,
        `grade_id`,
        `formula_id`,
        `formula_snapshot`,
        `supervisor_id`,
        `start_time`,
        `end_time`,
        `output_bags`,
        `output_kg`,
        `status`,
        `remarks`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'ADH-20260713-0001',
        4,
        1,
        1,
        '[{\"quantity\": 1, \"unit_code\": \"PCS\", \"raw_material_id\": 1, \"raw_material_code\": \"F-101\", \"raw_material_name\": \"Empty Bag F-101\", \"consumption_method\": \"output\", \"consumption_per_unit\": 1}, {\"quantity\": 1280, \"unit_code\": \"KG\", \"raw_material_id\": 12, \"raw_material_code\": \"SL\", \"raw_material_name\": \"Silica\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 500, \"unit_code\": \"KG\", \"raw_material_id\": 13, \"raw_material_code\": \"GRY-01\", \"raw_material_name\": \"Gray Cement\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 200, \"unit_code\": \"KG\", \"raw_material_id\": 15, \"raw_material_code\": \"C.C\", \"raw_material_name\": \"Calcium Carbonate\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 17, \"raw_material_code\": \"RDP-N\", \"raw_material_name\": \"RDP 5010N\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}, {\"quantity\": 4, \"unit_code\": \"KG\", \"raw_material_id\": 18, \"raw_material_code\": \"MHEC\", \"raw_material_name\": \"MHEC\", \"consumption_method\": \"formula\", \"consumption_per_unit\": 1}]',
        2,
        '2026-07-13 09:40:52',
        '2026-07-13 09:40:00',
        98.0000,
        1960.0000,
        'completed',
        NULL,
        '2026-07-13 04:10:52',
        '2026-07-13 04:11:06'
    );

-- --------------------------------------------------------
--
-- Table structure for table `raw_materials`
--
DROP TABLE IF EXISTS `raw_materials`;

CREATE TABLE
    IF NOT EXISTS `raw_materials` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `department_id` bigint UNSIGNED NOT NULL,
        `stock_unit_id` bigint UNSIGNED NOT NULL,
        `purchase_unit_id` bigint UNSIGNED NOT NULL,
        `purchase_conversion` decimal(12, 4) NOT NULL DEFAULT '1.0000',
        `opening_stock` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `current_stock` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `minimum_stock` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `maximum_stock` decimal(12, 4) NOT NULL DEFAULT '0.0000',
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `is_coupon` tinyint (1) NOT NULL DEFAULT '0',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `raw_materials_code_unique` (`code`),
        KEY `raw_materials_department_id_foreign` (`department_id`),
        KEY `raw_materials_stock_unit_id_foreign` (`stock_unit_id`),
        KEY `raw_materials_purchase_unit_id_foreign` (`purchase_unit_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 31 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_materials`
--
INSERT INTO
    `raw_materials` (
        `id`,
        `name`,
        `code`,
        `department_id`,
        `stock_unit_id`,
        `purchase_unit_id`,
        `purchase_conversion`,
        `opening_stock`,
        `current_stock`,
        `minimum_stock`,
        `maximum_stock`,
        `description`,
        `is_active`,
        `is_coupon`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Empty Bag F-101',
        'F-101',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        11902.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:00:05',
        '2026-07-13 04:11:06'
    ),
    (
        2,
        'Empty Bag F-107',
        'F-107',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        12000.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:05:03',
        '2026-07-12 11:25:30'
    ),
    (
        3,
        'Empty Bag F-121',
        'F-121',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        12000.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:05:03',
        '2026-07-12 11:05:03'
    ),
    (
        4,
        'Empty Bag F-115',
        'F-115',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        12000.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:05:03',
        '2026-07-12 11:05:03'
    ),
    (
        5,
        'Empty Bag F-133',
        'F-133',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        12000.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:05:03',
        '2026-07-12 11:05:03'
    ),
    (
        6,
        'Empty Bag F-147',
        'F-147',
        2,
        3,
        3,
        1.0000,
        12000.0000,
        12000.0000,
        2000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:05:03',
        '2026-07-12 11:05:03'
    ),
    (
        7,
        'RS-10 Solcon',
        'RS-10',
        2,
        3,
        3,
        1.0000,
        3000.0000,
        3000.0000,
        500.0000,
        5000.0000,
        '',
        1,
        1,
        '2026-07-12 11:24:04',
        '2026-07-12 11:24:04'
    ),
    (
        8,
        'RS-20 Solcon',
        'RS-20',
        2,
        3,
        3,
        1.0000,
        3000.0000,
        3000.0000,
        500.0000,
        5000.0000,
        '',
        1,
        1,
        '2026-07-12 11:24:04',
        '2026-07-12 11:24:04'
    ),
    (
        9,
        'RS-30 Solcon',
        'RS-30',
        2,
        3,
        3,
        1.0000,
        3000.0000,
        3000.0000,
        500.0000,
        5000.0000,
        '',
        1,
        1,
        '2026-07-12 11:24:04',
        '2026-07-12 11:24:04'
    ),
    (
        10,
        'RS-40 Solcon',
        'RS-40',
        2,
        3,
        3,
        1.0000,
        3000.0000,
        3000.0000,
        500.0000,
        5000.0000,
        '',
        1,
        1,
        '2026-07-12 11:24:04',
        '2026-07-12 11:24:04'
    ),
    (
        11,
        'RS-50 Solcon',
        'RS-50',
        2,
        3,
        3,
        1.0000,
        3000.0000,
        3000.0000,
        500.0000,
        5000.0000,
        '',
        1,
        1,
        '2026-07-12 11:24:04',
        '2026-07-12 11:24:04'
    ),
    (
        12,
        'Silica',
        'SL',
        2,
        1,
        1,
        1.0000,
        350000.0000,
        348720.0000,
        50000.0000,
        400000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-13 04:11:06'
    ),
    (
        13,
        'Gray Cement',
        'GRY-01',
        2,
        1,
        1,
        1.0000,
        50000.0000,
        49500.0000,
        10000.0000,
        60000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-13 04:11:06'
    ),
    (
        14,
        'White Cement',
        'WHT-01',
        2,
        1,
        1,
        1.0000,
        14500.0000,
        14500.0000,
        5000.0000,
        20000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-12 11:26:25'
    ),
    (
        15,
        'Calcium Carbonate',
        'C.C',
        2,
        1,
        1,
        1.0000,
        50000.0000,
        49800.0000,
        20000.0000,
        60000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-13 04:11:06'
    ),
    (
        16,
        'Dolomite',
        'DL',
        2,
        1,
        1,
        1.0000,
        20000.0000,
        20000.0000,
        5000.0000,
        50000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-12 11:26:25'
    ),
    (
        17,
        'RDP 5010N',
        'RDP-N',
        2,
        1,
        1,
        1.0000,
        4000.0000,
        3996.0000,
        300.0000,
        5000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-13 04:11:06'
    ),
    (
        18,
        'MHEC',
        'MHEC',
        2,
        1,
        1,
        1.0000,
        4000.0000,
        3996.0000,
        300.0000,
        5000.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-13 04:11:06'
    ),
    (
        19,
        'Calcium Formate',
        'CF',
        2,
        1,
        1,
        1.0000,
        2000.0000,
        2000.0000,
        300.0000,
        3000.0000,
        NULL,
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-12 11:26:51'
    ),
    (
        20,
        'RDP 8620',
        'RDP-E',
        2,
        1,
        1,
        1.0000,
        100.0000,
        100.0000,
        50.0000,
        200.0000,
        '',
        1,
        0,
        '2026-07-12 11:26:25',
        '2026-07-12 11:26:25'
    ),
    (
        21,
        'Starch Ether',
        'SE',
        2,
        1,
        1,
        1.0000,
        100.0000,
        100.0000,
        50.0000,
        200.0000,
        NULL,
        1,
        0,
        '2026-07-12 11:34:52',
        '2026-07-12 11:34:52'
    ),
    (
        22,
        'Prigment Color Black',
        'PMT-01',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        23,
        'Prigment Color Red 130',
        'PMT-02',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        24,
        'Prigment Color Red 110',
        'PMT-03',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        25,
        'Prigment Color Blue',
        'PMT-04',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        26,
        'Prigment Color Green',
        'PMT-05',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        27,
        'Prigment Color Black',
        'PMT-06',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        28,
        'Prigment Color Yellow',
        'PMT-07',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        29,
        'Prigment Color Orange',
        'PMT-08',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    ),
    (
        30,
        'Prigment Color Alphine',
        'PMT-09',
        1,
        1,
        1,
        1.0000,
        150.0000,
        150.0000,
        50.0000,
        150.0000,
        '',
        1,
        0,
        '2026-07-13 04:03:05',
        '2026-07-13 04:03:05'
    );

-- --------------------------------------------------------
--
-- Table structure for table `roles`
--
DROP TABLE IF EXISTS `roles`;

CREATE TABLE
    IF NOT EXISTS `roles` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `roles_slug_unique` (`slug`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--
INSERT INTO
    `roles` (
        `id`,
        `name`,
        `slug`,
        `description`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Marketing',
        'marketing',
        'Solcon Marketing user with access to order generation',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        2,
        'Administrator',
        'admin',
        'Solcon Administrator with full access to masters, formulas and settings',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        3,
        'Supervisor',
        'supervisor',
        'Solcon Production Supervisor with access to department-level batch operations',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    );

-- --------------------------------------------------------
--
-- Table structure for table `role_user`
--
DROP TABLE IF EXISTS `role_user`;

CREATE TABLE
    IF NOT EXISTS `role_user` (
        `user_id` bigint UNSIGNED NOT NULL,
        `role_id` bigint UNSIGNED NOT NULL,
        PRIMARY KEY (`user_id`, `role_id`),
        KEY `role_user_role_id_foreign` (`role_id`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--
INSERT INTO
    `role_user` (`user_id`, `role_id`)
VALUES
    (1, 1),
    (2, 2),
    (3, 2),
    (4, 3),
    (5, 3),
    (6, 3);

-- --------------------------------------------------------
--
-- Table structure for table `sessions`
--
DROP TABLE IF EXISTS `sessions`;

CREATE TABLE
    IF NOT EXISTS `sessions` (
        `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `user_id` bigint UNSIGNED DEFAULT NULL,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `user_agent` text COLLATE utf8mb4_unicode_ci,
        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
        `last_activity` int NOT NULL,
        PRIMARY KEY (`id`),
        KEY `sessions_user_id_index` (`user_id`),
        KEY `sessions_last_activity_index` (`last_activity`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--
INSERT INTO
    `sessions` (
        `id`,
        `user_id`,
        `ip_address`,
        `user_agent`,
        `payload`,
        `last_activity`
    )
VALUES
    (
        'nA3RPt1O0MKmsbIGGy4EaeAFNfvSma0zvZdaIUm1',
        2,
        '127.0.0.1',
        'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',
        'eyJfdG9rZW4iOiJra0JUN3dxS3VKTDc0SkdTVzhYM05vS1FoRWMwTk9NaHpEcDVwcjZnIiwidXJsIjpbXSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImFkbWluLmRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwiY3VycmVudF9kZXBhcnRtZW50X2lkXzIiOjN9',
        1783916566
    );

-- --------------------------------------------------------
--
-- Table structure for table `settings`
--
DROP TABLE IF EXISTS `settings`;

CREATE TABLE
    IF NOT EXISTS `settings` (
        `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `value` text COLLATE utf8mb4_unicode_ci,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`key`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--
INSERT INTO
    `settings` (`key`, `value`, `created_at`, `updated_at`)
VALUES
    (
        'factory_name',
        'Solcon Industries',
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        'factory_logo',
        NULL,
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        'report_header',
        'Solcon Industries Daily Production Report',
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        'footer_text',
        'Solcon Production Management System',
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        'default_bag_size',
        '20',
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:53'
    ),
    (
        'timezone',
        'Asia/Kolkata',
        '2026-07-12 10:41:53',
        '2026-07-12 10:41:55'
    ),
    (
        'ui_theme',
        'dark',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        'ui_primary_color',
        'indigo',
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    );

-- --------------------------------------------------------
--
-- Table structure for table `stock_adjustments`
--
DROP TABLE IF EXISTS `stock_adjustments`;

CREATE TABLE
    IF NOT EXISTS `stock_adjustments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `quantity` decimal(12, 4) NOT NULL,
        `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_by` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `stock_adjustments_raw_material_id_foreign` (`raw_material_id`),
        KEY `stock_adjustments_created_by_foreign` (`created_by`)
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `stock_ledgers`
--
DROP TABLE IF EXISTS `stock_ledgers`;

CREATE TABLE
    IF NOT EXISTS `stock_ledgers` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `raw_material_id` bigint UNSIGNED NOT NULL,
        `batch_id` bigint UNSIGNED DEFAULT NULL,
        `grout_batch_id` bigint UNSIGNED DEFAULT NULL,
        `epoxy_assembly_id` bigint UNSIGNED DEFAULT NULL,
        `transaction_type` enum ('IN', 'OUT', 'ADJUSTMENT') COLLATE utf8mb4_unicode_ci NOT NULL,
        `quantity` decimal(12, 4) NOT NULL,
        `balance_after` decimal(12, 4) NOT NULL,
        `remarks` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `stock_ledgers_batch_id_foreign` (`batch_id`),
        KEY `stock_ledgers_created_by_foreign` (`created_by`),
        KEY `stock_ledgers_raw_material_id_created_at_index` (`raw_material_id`, `created_at`),
        KEY `stock_ledgers_grout_batch_id_foreign` (`grout_batch_id`),
        KEY `stock_ledgers_epoxy_assembly_id_foreign` (`epoxy_assembly_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_ledgers`
--
INSERT INTO
    `stock_ledgers` (
        `id`,
        `raw_material_id`,
        `batch_id`,
        `grout_batch_id`,
        `epoxy_assembly_id`,
        `transaction_type`,
        `quantity`,
        `balance_after`,
        `remarks`,
        `created_by`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        1,
        1,
        NULL,
        NULL,
        'OUT',
        -98.0000,
        11902.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        2,
        12,
        1,
        NULL,
        NULL,
        'OUT',
        -1280.0000,
        348720.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        3,
        13,
        1,
        NULL,
        NULL,
        'OUT',
        -500.0000,
        49500.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        4,
        15,
        1,
        NULL,
        NULL,
        'OUT',
        -200.0000,
        49800.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        5,
        17,
        1,
        NULL,
        NULL,
        'OUT',
        -4.0000,
        3996.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    ),
    (
        6,
        18,
        1,
        NULL,
        NULL,
        'OUT',
        -4.0000,
        3996.0000,
        'Consumed in production batch #ADH-20260713-0001',
        2,
        '2026-07-13 04:11:06',
        '2026-07-13 04:11:06'
    );

-- --------------------------------------------------------
--
-- Table structure for table `todos`
--
DROP TABLE IF EXISTS `todos`;

CREATE TABLE
    IF NOT EXISTS `todos` (
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
    ) ENGINE = MyISAM DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- --------------------------------------------------------
--
-- Table structure for table `units`
--
DROP TABLE IF EXISTS `units`;

CREATE TABLE
    IF NOT EXISTS `units` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `units_code_unique` (`code`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 4 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--
INSERT INTO
    `units` (
        `id`,
        `name`,
        `code`,
        `description`,
        `is_active`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'KGS',
        'KG',
        NULL,
        1,
        '2026-07-12 10:43:34',
        '2026-07-12 10:43:34'
    ),
    (
        2,
        'GMS',
        'GM',
        NULL,
        1,
        '2026-07-12 10:43:45',
        '2026-07-12 10:43:45'
    ),
    (
        3,
        'PCS',
        'PCS',
        NULL,
        1,
        '2026-07-12 10:43:55',
        '2026-07-12 10:43:55'
    );

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;

CREATE TABLE
    IF NOT EXISTS `users` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `email_verified_at` timestamp NULL DEFAULT NULL,
        `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
        `profile_photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `last_login_at` timestamp NULL DEFAULT NULL,
        `department_id` bigint UNSIGNED DEFAULT NULL,
        `is_active` tinyint (1) NOT NULL DEFAULT '1',
        `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `users_email_unique` (`email`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--
INSERT INTO
    `users` (
        `id`,
        `name`,
        `email`,
        `email_verified_at`,
        `password`,
        `profile_photo`,
        `last_login_at`,
        `department_id`,
        `is_active`,
        `remember_token`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        'Marketing User',
        'marketing@solcon.com',
        NULL,
        '$2y$12$kTjfNSx1/qYpYhfX002DVO8gmGqshezrPcV1uOD0BLWxfku95Iq9i',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:41:55',
        '2026-07-12 10:41:55'
    ),
    (
        2,
        'Admin User',
        'admin@solcon.com',
        NULL,
        '$2y$12$BpX8bNgNsrsnVvtv8qq56.OoFoouponRvFluTdrhnr3r36egg/i0W',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:41:56',
        '2026-07-13 04:22:08'
    ),
    (
        3,
        'MANAGER',
        'manager@solcon.com',
        NULL,
        '$2y$12$UZKcPoAHQDHP5Q9CfCQRIeBStq3Z1t4hJWCMWxwxpzM4eKdhAQpc6',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:48:34',
        '2026-07-12 10:48:34'
    ),
    (
        4,
        'Adhesive',
        'adhesive@solcon.com',
        NULL,
        '$2y$12$Xf2bvRwq1usVNCFwtZ4yN.fbiSmWrnfQW2AVaRI5wXC1JXX0Zb0QW',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:49:59',
        '2026-07-12 10:49:59'
    ),
    (
        5,
        'Grout',
        'grout@solcon.com',
        NULL,
        '$2y$12$XXY1lEnGKJSOvAU4MGH8POF0W3MSdTt3Bbmcnpelxe6Pt04asF6Om',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:50:44',
        '2026-07-12 10:50:44'
    ),
    (
        6,
        'Epoxy',
        'epoxy@solcon.com',
        NULL,
        '$2y$12$e08Bznecd8/foX7fcVhP2O5K64c3A25/OQXuTX83e397YYbUnXMQS',
        NULL,
        NULL,
        NULL,
        1,
        NULL,
        '2026-07-12 10:51:07',
        '2026-07-12 10:51:07'
    );

-- --------------------------------------------------------
--
-- Table structure for table `user_departments`
--
DROP TABLE IF EXISTS `user_departments`;

CREATE TABLE
    IF NOT EXISTS `user_departments` (
        `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` bigint UNSIGNED NOT NULL,
        `department_id` bigint UNSIGNED NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `user_departments_user_id_department_id_unique` (`user_id`, `department_id`),
        KEY `user_departments_department_id_foreign` (`department_id`)
    ) ENGINE = MyISAM AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `user_departments`
--
INSERT INTO
    `user_departments` (
        `id`,
        `user_id`,
        `department_id`,
        `created_at`,
        `updated_at`
    )
VALUES
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

CREATE TABLE
    IF NOT EXISTS `user_devices` (
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
    ) ENGINE = MyISAM AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

--
-- Dumping data for table `user_devices`
--
INSERT INTO
    `user_devices` (
        `id`,
        `user_id`,
        `device_token`,
        `browser_name`,
        `platform`,
        `device_name`,
        `ip_address`,
        `last_seen_at`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        1,
        2,
        'fNt_KFUT6XNTOXVIVN-0GG:APA91bE65XShho_FVF0XHLtJQA_xzzaQTOzcRdp9Y6HqSg7sWviBBAemAx5mhzD_xqQo1ELaGpjYsJr6YdQqvK9JpsS1d66WE8tZUGl0bd0DA2uqPA2ZwrE',
        'Chrome',
        'Windows',
        'Desktop',
        '127.0.0.1',
        '2026-07-13 04:22:16',
        '2026-07-12 10:42:12',
        '2026-07-13 04:22:16'
    );

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
