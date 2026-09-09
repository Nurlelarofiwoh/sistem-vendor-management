-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2026 at 02:02 PM
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
-- Database: `db_invoice_app`
--
CREATE DATABASE IF NOT EXISTS `db_invoice_app` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_invoice_app`;

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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL DEFAULT 'Pelanggan Umum',
  `customer_email` varchar(255) DEFAULT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('paid','unpaid','overdue') NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `customer_name`, `customer_email`, `issue_date`, `due_date`, `notes`, `total_amount`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 'INV-202608-001', 'Ropa', NULL, '2026-08-24', '2026-09-24', NULL, 3235000.00, 'paid', '2026-08-31 07:55:34', '2026-08-31 08:13:24'),
(3, 'INV-202609-001', 'Ropa', NULL, '2026-09-07', '2026-09-08', NULL, 21100000.00, 'unpaid', '2026-09-07 09:53:34', '2026-09-07 10:18:39'),
(4, 'INV-202609-002', 'Paisal', NULL, '2026-09-07', '2026-09-08', NULL, 14700000.00, 'unpaid', '2026-09-07 10:01:10', '2026-09-07 10:37:23'),
(5, 'INV-202609-003', 'Pacon', NULL, '2026-09-07', '2026-09-21', NULL, 26185000.00, 'unpaid', '2026-09-07 10:27:33', '2026-09-07 10:34:15');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `order_date` date DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `order_date`, `product_id`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 29, 1, 500000.00, 500000.00, '2026-08-31 07:55:34', '2026-08-31 07:55:34'),
(2, 1, NULL, 24, 10, 115000.00, 1150000.00, '2026-08-31 07:55:34', '2026-08-31 07:55:34'),
(3, 1, NULL, 22, 3, 245000.00, 735000.00, '2026-08-31 07:55:34', '2026-08-31 07:55:34'),
(4, 1, NULL, 19, 5, 170000.00, 850000.00, '2026-08-31 07:55:34', '2026-08-31 07:55:34'),
(98, 3, '2026-08-31', 21, 10, 120000.00, 1200000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(99, 3, '2026-08-31', 24, 10, 115000.00, 1150000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(100, 3, '2026-08-31', 13, 10, 115000.00, 1150000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(101, 3, '2026-08-31', 14, 5, 115000.00, 575000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(102, 3, '2026-08-31', 19, 10, 170000.00, 1700000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(103, 3, '2026-08-31', 16, 5, 140000.00, 700000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(104, 3, '2026-08-31', 15, 10, 140000.00, 1400000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(105, 3, '2026-08-31', 33, 1, 750000.00, 750000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(106, 3, '2026-08-31', 28, 1, 500000.00, 500000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(107, 3, '2026-08-31', 22, 2, 245000.00, 490000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(108, 3, '2026-08-31', 30, 2, 360000.00, 720000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(109, 3, '2026-08-31', 32, 1, 420000.00, 420000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(110, 3, '2026-08-31', 44, 1, 300000.00, 300000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(111, 3, '2026-08-31', 45, 2, 450000.00, 900000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(112, 3, '2026-09-02', 23, 1, 400000.00, 400000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(113, 3, '2026-09-02', 22, 1, 245000.00, 245000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(114, 3, '2026-09-02', 17, 3, 300000.00, 900000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(115, 3, '2026-09-02', 46, 2, 275000.00, 550000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(116, 3, '2026-09-02', 13, 5, 115000.00, 575000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(117, 3, '2026-09-02', 21, 5, 120000.00, 600000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(118, 3, '2026-09-02', 30, 1, 360000.00, 360000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(119, 3, '2026-09-04', 31, 2, 400000.00, 800000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(120, 3, '2026-09-04', 32, 1, 420000.00, 420000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(121, 3, '2026-09-04', 18, 1, 750000.00, 750000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(122, 3, '2026-09-04', 15, 10, 140000.00, 1400000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(123, 3, '2026-09-04', 16, 5, 140000.00, 700000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(124, 3, '2026-09-04', 21, 10, 120000.00, 1200000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(125, 3, '2026-09-04', 22, 1, 245000.00, 245000.00, '2026-09-07 10:18:39', '2026-09-07 10:18:39'),
(149, 5, '2026-08-31', 54, 1, 365000.00, 365000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(150, 5, '2026-08-31', 3, 1, 240000.00, 240000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(151, 5, '2026-09-01', 16, 10, 140000.00, 1400000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(152, 5, '2026-09-01', 22, 2, 245000.00, 490000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(153, 5, '2026-09-02', 3, 1, 240000.00, 240000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(154, 5, '2026-09-04', 32, 20, 420000.00, 8400000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(155, 5, '2026-09-04', 33, 20, 750000.00, 15000000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(156, 5, '2026-08-31', 53, 1, 50000.00, 50000.00, '2026-09-07 10:34:15', '2026-09-07 10:34:15'),
(157, 4, '2026-09-07', 47, 40, 75000.00, 3000000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23'),
(158, 4, '2026-09-07', 48, 40, 35000.00, 1400000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23'),
(159, 4, '2026-09-07', 49, 40, 95000.00, 3800000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23'),
(160, 4, '2026-09-07', 51, 1, 1300000.00, 1300000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23'),
(161, 4, '2026-09-07', 52, 1, 1400000.00, 1400000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23'),
(162, 4, '2026-09-07', 50, 2, 1900000.00, 3800000.00, '2026-09-07 10:37:23', '2026-09-07 10:37:23');

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
(4, '2026_08_31_000001_create_products_table', 1),
(5, '2026_08_31_000002_create_invoices_table', 1),
(6, '2026_08_31_000003_create_invoice_items_table', 1),
(7, '2026_09_07_000001_add_order_date_to_invoice_items_table', 2);

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `name`, `category`, `unit_price`, `stock_quantity`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PRD-KASAR-001', 'full kasar mio sporty', 'Body Full Kasar', 225000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(2, 'PRD-KASAR-002', 'full kasar mio smile', 'Body Full Kasar', 260000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(3, 'PRD-KASAR-003', 'full kasar beat karbu', 'Body Full Kasar', 240000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(4, 'PRD-KASAR-004', 'full kasar beat eco/esp', 'Body Full Kasar', 375000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(5, 'PRD-KASAR-005', 'full kasar beat deluxe charge', 'Body Full Kasar', 470000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(6, 'PRD-KASAR-006', 'full kasar beat deluxe no charge', 'Body Full Kasar', 450000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(7, 'PRD-KASAR-007', 'full kasar beat fi st kasar', 'Body Full Kasar', 285000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(8, 'PRD-KASAR-008', 'full kasar beat fi st halus', 'Body Full Kasar', 315000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(9, 'PRD-KASAR-009', 'full kasar vario karbu', 'Body Full Kasar', 330000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(10, 'PRD-KASAR-010', 'full kasar mio soul', 'Body Full Kasar', 260000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(11, 'PRD-KASAR-011', 'full kasar vario led old', 'Body Full Kasar', 365000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(12, 'PRD-KASAR-012', 'full kasar vario 125 ( kzr )', 'Body Full Kasar', 320000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(13, 'PRD-HALUS-001', 'full halus beat karbu bahan spd besar', 'Body Full Halus', 115000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(14, 'PRD-HALUS-002', 'full halus beat karbu bahan spd kecil', 'Body Full Halus', 115000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(15, 'PRD-HALUS-003', 'full halus beat fi bahan st kasar', 'Body Full Halus', 140000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(16, 'PRD-HALUS-004', 'full halus beat fi bahan st halus', 'Body Full Halus', 140000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(17, 'PRD-HALUS-005', 'full halus beat deluxe', 'Body Full Halus', 300000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(18, 'PRD-HALUS-006', 'full halus beat deluxe gen 2', 'Body Full Halus', 750000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(19, 'PRD-HALUS-007', 'full halus beat eco/esp', 'Body Full Halus', 170000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(20, 'PRD-HALUS-008', 'full halus beat pop', 'Body Full Halus', 185000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(21, 'PRD-HALUS-009', 'full halus mio sporty', 'Body Full Halus', 120000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(22, 'PRD-HALUS-010', 'full halus mio j', 'Body Full Halus', 245000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(23, 'PRD-HALUS-011', 'full halus mio m3', 'Body Full Halus', 400000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(24, 'PRD-HALUS-012', 'full halus mio smile', 'Body Full Halus', 115000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(25, 'PRD-HALUS-013', 'full halus mio soul karbu', 'Body Full Halus', 250000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(26, 'PRD-HALUS-014', 'full halus scoopy karbu', 'Body Full Halus', 665000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(27, 'PRD-HALUS-015', 'full halus vario techno', 'Body Full Halus', 550000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(28, 'PRD-HALUS-016', 'full halus vario agness', 'Body Full Halus', 500000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(29, 'PRD-HALUS-017', 'full halus vario fi', 'Body Full Halus', 500000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(30, 'PRD-HALUS-018', 'full halus vario karbu bahan', 'Body Full Halus', 360000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(31, 'PRD-HALUS-019', 'full halus vario 125 old ( KZR )', 'Body Full Halus', 400000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(32, 'PRD-HALUS-020', 'full halus vario 150 led old', 'Body Full Halus', 420000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(33, 'PRD-HALUS-021', 'full halus vario all new', 'Body Full Halus', 750000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(34, 'PRD-HALUS-022', 'full halus vario all new gen ( 1 )', 'Body Full Halus', 750000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(35, 'PRD-HALUS-023', 'full halus vario all new gen ( 2 )', 'Body Full Halus', 1050000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(36, 'PRD-PART-001', 'Spakbor vario all new', 'Part Body & Aksesoris', 35000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(37, 'PRD-PART-002', 'Body kanan kiri KZR', 'Part Body & Aksesoris', 80000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(38, 'PRD-PART-003', 'Body kanan kiri mio', 'Part Body & Aksesoris', 45000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(39, 'PRD-PACK-001', 'Polyfoam 1kg', 'Bahan Packing & Operational', 600000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(40, 'PRD-PACK-002', 'Lakban 1 rol', 'Bahan Packing & Operational', 60000.00, 0, 'active', '2026-08-31 07:52:28', '2026-08-31 07:52:28'),
(41, 'PRD-PACK-003', 'Plastik body 1 karung 25kg', 'Bahan Packing & Operational', 43000.00, 0, 'active', '2026-08-31 07:52:29', '2026-08-31 07:52:29'),
(42, 'PRD-PACK-004', 'Plastik tameng 1 karung', 'Bahan Packing & Operational', 43000.00, 0, 'active', '2026-08-31 07:52:29', '2026-08-31 07:52:29'),
(43, 'PRD-PACK-005', 'Kardus', 'Bahan Packing & Operational', 12000.00, 0, 'active', '2026-08-31 07:52:29', '2026-08-31 07:52:29'),
(44, 'PRD-0WSMGH', 'thiner hg', 'Bahan Packing & Operational', 300000.00, 0, 'active', '2026-09-07 09:38:08', '2026-09-07 09:38:08'),
(45, 'PRD-CK2NV4', 'thiner pu', 'Bahan Packing & Operational', 450000.00, 0, 'active', '2026-09-07 09:38:59', '2026-09-07 09:38:59'),
(46, 'PRD-YNH44B', 'full halus beat deluxe non batok', 'Body Full Halus', 275000.00, 0, 'active', '2026-09-07 09:43:27', '2026-09-07 09:43:27'),
(47, 'PRD-LQTWFG', 'body mio m3', 'Part Body & Aksesoris', 75000.00, 0, 'active', '2026-09-07 09:56:38', '2026-09-07 09:56:38'),
(48, 'PRD-63H5FS', 'spakbor mio m3', 'Part Body & Aksesoris', 35000.00, 0, 'active', '2026-09-07 09:57:01', '2026-09-07 09:57:01'),
(49, 'PRD-MWIWLZ', 'sayap mio m3', 'Part Body & Aksesoris', 95000.00, 0, 'active', '2026-09-07 09:57:36', '2026-09-07 09:57:36'),
(50, 'PRD-TWH3TC', 'pernis pail', 'Bahan Packing & Operational', 1900000.00, 0, 'active', '2026-09-07 09:58:08', '2026-09-07 10:36:54'),
(51, 'PRD-8SLR2I', 'cat nc hitam', 'Part Body & Aksesoris', 1300000.00, 0, 'active', '2026-09-07 09:58:41', '2026-09-07 09:58:41'),
(52, 'PRD-3WYTYF', 'cat nc silver', 'Bahan Packing & Operational', 1400000.00, 0, 'active', '2026-09-07 09:59:17', '2026-09-07 10:10:32'),
(53, 'PRD-MZ9S3G', 'paru beat karbu', 'Part Body & Aksesoris', 50000.00, 0, 'active', '2026-09-07 10:27:59', '2026-09-07 10:27:59'),
(54, 'PRD-0QTWBC', 'full kasar vario 150 led old', 'Body Full Kasar', 365000.00, 0, 'active', '2026-09-07 10:33:53', '2026-09-07 10:33:53');

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
('ksCiMMUJAiwAfl5S0zGidV9psf495nCtIIwdJVmK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2FJaHBRRDdKVGM1SWxqd21DMU0wb0xnSFBHdEQ1OHR0bnRIQ0RBWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788843354),
('qWbyawJRK60rGljpBzQ73oSmvyt0qdnJJy5YNa6a', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36 Edg/152.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGRFZ3hKaUVvTDRwQjJlSXNtRGlqYWl0M0hPcG9QbXpkR3FOa1JMSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788953701);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_product_id_foreign` (`product_id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_product_code_unique` (`product_code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-09-09 12:00:38', '{\"Console\\/Mode\":\"collapse\",\"NavigationWidth\":0}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
--
-- Database: `vendor_automation`
--
CREATE DATABASE IF NOT EXISTS `vendor_automation` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `vendor_automation`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `aksi` varchar(255) NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_name`, `aksi`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien putri dan membuat PDF rekomendasi otomatis.', '2026-06-08 10:07:51', '2026-06-08 10:07:51'),
(2, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien putri, menunggu validasi Partnership.', '2026-06-08 10:12:07', '2026-06-08 10:12:07'),
(3, 'Divisi Partnership', 'ACC Vendor & Buat Event', 'Memberikan validasi vendor akhir dan menerbitkan event wedding putri ke operasional.', '2026-06-08 10:15:18', '2026-06-08 10:15:18'),
(4, 'Manager Operasional', 'Penyelesaian Event', 'Event Resepsi Pernikahan Dinda & Dimas selesai. Sistem otomatis mengirimkan E-Survey ke Klien dan tagihan komisi ke semua Vendor.', '2026-06-09 06:39:30', '2026-06-09 06:39:30'),
(5, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien Zahra dan membuat PDF rekomendasi otomatis.', '2026-06-14 06:56:07', '2026-06-14 06:56:07'),
(6, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice untuk event wedding putri', '2026-06-14 06:56:58', '2026-06-14 06:56:58'),
(7, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien Zahra, menunggu validasi Partnership.', '2026-06-14 06:59:01', '2026-06-14 06:59:01'),
(8, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding putri', '2026-06-14 09:33:02', '2026-06-14 09:33:02'),
(9, 'Manager Operasional', 'ACC Vendor & Buat Event', 'Memberikan validasi vendor akhir dan menerbitkan event wedding zahra ke operasional.', '2026-06-14 09:35:42', '2026-06-14 09:35:42'),
(10, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memvalidasi kesiapan logistik dan memulai operasional event wedding zahra', '2026-06-14 09:46:16', '2026-06-14 09:46:16'),
(11, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding zahra', '2026-06-14 09:47:43', '2026-06-14 09:47:43'),
(12, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding putri', '2026-06-14 09:49:07', '2026-06-14 09:49:07'),
(13, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding putri', '2026-06-14 09:53:07', '2026-06-14 09:53:07'),
(14, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding zahra', '2026-06-14 09:53:26', '2026-06-14 09:53:26'),
(15, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding putri\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-06-14 10:02:16', '2026-06-14 10:02:16'),
(16, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding zahra', '2026-06-14 10:11:50', '2026-06-14 10:11:50'),
(17, 'Tim Finance', 'Transaksi Komplit', 'Finance menutup siklus finansial event wedding putri. Komisi vendor telah diterima.', '2026-06-14 10:12:43', '2026-06-14 10:12:43'),
(18, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding zahra', '2026-06-14 10:13:01', '2026-06-14 10:13:01'),
(19, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding zahra\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-06-14 10:22:16', '2026-06-14 10:22:16'),
(20, 'Tim Finance', 'Transaksi Komplit', 'Finance menutup siklus finansial event wedding zahra. Komisi vendor telah diterima.', '2026-06-14 10:28:02', '2026-06-14 10:28:02'),
(21, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien mila dan membuat PDF rekomendasi otomatis.', '2026-06-23 09:21:36', '2026-06-23 09:21:36'),
(22, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien mila, menunggu validasi Partnership.', '2026-06-23 22:43:00', '2026-06-23 22:43:00'),
(23, 'Manager Operasional', 'ACC Vendor & Buat Event', 'Memberikan validasi vendor akhir dan menerbitkan event wedding mila ke operasional.', '2026-06-23 22:44:14', '2026-06-23 22:44:14'),
(24, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memvalidasi kesiapan logistik dan memulai operasional event wedding mila', '2026-06-23 22:51:04', '2026-06-23 22:51:04'),
(25, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 100.000.000 untuk event wedding mila', '2026-06-23 22:53:20', '2026-06-23 22:53:20'),
(26, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding mila', '2026-06-23 22:54:24', '2026-06-23 22:54:24'),
(27, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding mila', '2026-06-23 22:55:57', '2026-06-23 22:55:57'),
(28, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding mila', '2026-06-23 22:56:39', '2026-06-23 22:56:39'),
(29, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding mila\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-06-23 22:59:52', '2026-06-23 22:59:52'),
(30, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding mila\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-06-23 22:59:53', '2026-06-23 22:59:53'),
(31, 'Tim Finance', 'Transaksi Komplit', 'Finance menutup siklus finansial event wedding mila. Komisi vendor telah diterima.', '2026-06-23 23:02:48', '2026-06-23 23:02:48'),
(32, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien mila dan membuat PDF rekomendasi otomatis.', '2026-06-24 21:18:07', '2026-06-24 21:18:07'),
(33, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien mila, menunggu validasi Partnership.', '2026-06-29 21:30:26', '2026-06-29 21:30:26'),
(34, 'Manager Operasional', 'ACC Vendor & Buat Event', 'Memberikan validasi vendor akhir dan menerbitkan event wedding mila ke operasional.', '2026-06-29 21:31:13', '2026-06-29 21:31:13'),
(35, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien atha dan membuat PDF rekomendasi otomatis.', '2026-06-29 22:19:02', '2026-06-29 22:19:02'),
(36, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien atha, menunggu validasi Partnership.', '2026-06-29 22:19:48', '2026-06-29 22:19:48'),
(37, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memvalidasi kesiapan logistik dan memulai operasional event wedding mila', '2026-06-29 22:20:45', '2026-06-29 22:20:45'),
(38, 'Manager Operasional', 'ACC Vendor & Buat Event', 'Memberikan validasi vendor akhir dan menerbitkan event wedding atha ke operasional.', '2026-06-29 22:21:01', '2026-06-29 22:21:01'),
(39, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 5.000.000.000 untuk event wedding mila', '2026-06-29 22:22:02', '2026-06-29 22:22:02'),
(40, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding mila', '2026-06-29 22:22:33', '2026-06-29 22:22:33'),
(41, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding mila', '2026-06-29 22:23:37', '2026-06-29 22:23:37'),
(42, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding mila', '2026-06-29 22:24:01', '2026-06-29 22:24:01'),
(43, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding mila\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-06-29 22:26:26', '2026-06-29 22:26:26'),
(44, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memvalidasi kesiapan logistik dan memulai operasional event wedding atha', '2026-06-29 22:53:01', '2026-06-29 22:53:01'),
(45, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding atha', '2026-07-01 20:55:24', '2026-07-01 20:55:24'),
(46, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding atha', '2026-07-01 20:57:30', '2026-07-01 20:57:30'),
(47, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding atha', '2026-07-01 20:58:52', '2026-07-01 20:58:52'),
(48, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding atha', '2026-07-01 20:59:27', '2026-07-01 20:59:27'),
(49, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding atha\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-01 21:01:57', '2026-07-01 21:01:57'),
(50, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding atha\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-01 21:01:58', '2026-07-01 21:01:58'),
(51, 'Tim Finance', 'Transaksi Komplit', 'Finance menutup siklus finansial event wedding atha. Komisi vendor telah diterima.', '2026-07-01 21:05:11', '2026-07-01 21:05:11'),
(52, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien putra dan membuat PDF rekomendasi otomatis.', '2026-07-01 21:16:21', '2026-07-01 21:16:21'),
(53, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 3 vendor untuk klien putra, menunggu validasi Partnership.', '2026-07-01 21:20:11', '2026-07-01 21:20:11'),
(54, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding putra (Status: Berjalan) ke operasional.', '2026-07-01 21:21:35', '2026-07-01 21:21:35'),
(55, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien dewi dan membuat PDF rekomendasi otomatis.', '2026-07-08 21:33:51', '2026-07-08 21:33:51'),
(56, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 1 vendor untuk klien dewi, menunggu validasi Partnership.', '2026-07-08 21:34:33', '2026-07-08 21:34:33'),
(57, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding dewi (Status: Berjalan) ke operasional.', '2026-07-08 21:35:29', '2026-07-08 21:35:29'),
(58, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding dewi', '2026-07-08 21:36:14', '2026-07-08 21:36:14'),
(59, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding dewi', '2026-07-08 21:37:02', '2026-07-08 21:37:02'),
(60, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding dewi', '2026-07-08 21:38:07', '2026-07-08 21:38:07'),
(61, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding dewi', '2026-07-08 21:39:12', '2026-07-08 21:39:12'),
(62, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien dinda dan membuat PDF rekomendasi otomatis.', '2026-07-13 20:48:31', '2026-07-13 20:48:31'),
(63, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien Ali dan membuat PDF rekomendasi otomatis.', '2026-07-13 20:58:36', '2026-07-13 20:58:36'),
(64, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien atha dan membuat PDF rekomendasi otomatis.', '2026-07-13 21:20:49', '2026-07-13 21:20:49'),
(65, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding dewi\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-14 21:23:47', '2026-07-14 21:23:47'),
(66, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien anita dan membuat PDF rekomendasi otomatis.', '2026-07-27 12:18:45', '2026-07-27 12:18:45'),
(67, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 5 vendor untuk klien anita, menunggu validasi Partnership.', '2026-07-27 12:24:35', '2026-07-27 12:24:35'),
(68, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding anita (Status: Berjalan) ke operasional.', '2026-07-27 12:27:23', '2026-07-27 12:27:23'),
(69, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 25.000.000 untuk event wedding anita', '2026-07-27 12:28:38', '2026-07-27 12:28:38'),
(70, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding anita', '2026-07-27 12:29:20', '2026-07-27 12:29:20'),
(71, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding anita', '2026-07-27 12:30:01', '2026-07-27 12:30:01'),
(72, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding anita', '2026-07-27 12:31:13', '2026-07-27 12:31:13'),
(73, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding anita\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-27 12:34:17', '2026-07-27 12:34:17'),
(74, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien balqis dan membuat PDF rekomendasi otomatis.', '2026-07-27 21:55:23', '2026-07-27 21:55:23'),
(75, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 3 vendor untuk klien balqis, menunggu validasi Partnership.', '2026-07-27 21:57:02', '2026-07-27 21:57:02'),
(76, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding balqis (Status: Berjalan) ke operasional.', '2026-07-27 21:57:52', '2026-07-27 21:57:52'),
(77, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 200.000.000 untuk event wedding balqis', '2026-07-27 21:58:42', '2026-07-27 21:58:42'),
(78, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding balqis', '2026-07-27 21:59:14', '2026-07-27 21:59:14'),
(79, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding balqis', '2026-07-27 22:00:03', '2026-07-27 22:00:03'),
(80, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding balqis', '2026-07-27 22:02:18', '2026-07-27 22:02:18'),
(81, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding balqis\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-27 22:04:38', '2026-07-27 22:04:38'),
(82, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding balqis\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-27 22:04:39', '2026-07-27 22:04:39'),
(83, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien lili dan membuat PDF rekomendasi otomatis.', '2026-07-27 23:54:38', '2026-07-27 23:54:38'),
(84, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien lili, menunggu validasi Partnership.', '2026-07-27 23:56:25', '2026-07-27 23:56:25'),
(85, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding lili (Status: Berjalan) ke operasional.', '2026-07-27 23:57:20', '2026-07-27 23:57:20'),
(86, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding lili', '2026-07-27 23:58:24', '2026-07-27 23:58:24'),
(87, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding lili', '2026-07-27 23:58:56', '2026-07-27 23:58:56'),
(88, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding lili', '2026-07-27 23:59:42', '2026-07-27 23:59:42'),
(89, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding lili', '2026-07-28 00:00:10', '2026-07-28 00:00:10'),
(90, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding lili\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-28 00:02:41', '2026-07-28 00:02:41'),
(91, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding lili\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-07-28 00:02:44', '2026-07-28 00:02:44'),
(92, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien ziza dan membuat PDF rekomendasi otomatis.', '2026-08-11 07:54:26', '2026-08-11 07:54:26'),
(93, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 3 vendor untuk klien ziza, menunggu validasi Partnership.', '2026-08-11 07:57:37', '2026-08-11 07:57:37'),
(94, 'Manager Operasional', 'Tolak Vendor/Logistik Klien', 'Menolak pengajuan vendor untuk klien ziza. Alasan: logistik tidak mencukupi kebutuhan klien', '2026-08-11 07:59:35', '2026-08-11 07:59:35'),
(95, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 3 vendor untuk klien ziza, menunggu validasi Partnership.', '2026-08-11 08:00:52', '2026-08-11 08:00:52'),
(96, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding zize (Status: Berjalan) ke operasional.', '2026-08-11 08:02:06', '2026-08-11 08:02:06'),
(97, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding zize', '2026-08-11 08:05:21', '2026-08-11 08:05:21'),
(98, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding zize', '2026-08-11 08:08:53', '2026-08-11 08:08:53'),
(99, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding zize', '2026-08-11 08:12:08', '2026-08-11 08:12:08'),
(100, 'Tim Finance', 'Tolak Pembayaran', 'Finance menolak bukti transfer wedding zize: bukti buram tidak terlihat', '2026-08-11 08:27:37', '2026-08-11 08:27:37'),
(101, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding zize', '2026-08-11 08:28:25', '2026-08-11 08:28:25'),
(102, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding zize', '2026-08-11 08:31:43', '2026-08-11 08:31:43'),
(103, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding zize\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-08-11 08:37:35', '2026-08-11 08:37:35'),
(104, 'Admin CS', 'Input Klien Baru', 'Menambahkan klien dwi dan membuat PDF rekomendasi otomatis.', '2026-08-11 20:49:10', '2026-08-11 20:49:10'),
(105, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:08', '2026-08-11 20:51:08'),
(106, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:12', '2026-08-11 20:51:12'),
(107, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:13', '2026-08-11 20:51:13'),
(108, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:14', '2026-08-11 20:51:14'),
(109, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:17', '2026-08-11 20:51:17'),
(110, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:20', '2026-08-11 20:51:20'),
(111, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:22', '2026-08-11 20:51:22'),
(112, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:23', '2026-08-11 20:51:23'),
(113, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:23', '2026-08-11 20:51:23'),
(114, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:24', '2026-08-11 20:51:24'),
(115, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:26', '2026-08-11 20:51:26'),
(116, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:27', '2026-08-11 20:51:27'),
(117, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:28', '2026-08-11 20:51:28'),
(118, 'Admin CS', 'Pengajuan Vendor', 'Mengajukan 2 vendor untuk klien dwi, menunggu validasi Partnership.', '2026-08-11 20:51:29', '2026-08-11 20:51:29'),
(119, 'Manager Operasional', 'Validasi Logistik (ACC)', 'Memberikan validasi vendor akhir, kesiapan logistik, dan menerbitkan event wedding dwi (Status: Berjalan) ke operasional.', '2026-08-11 20:54:05', '2026-08-11 20:54:05'),
(120, 'Admin CS', 'Minta Invoice', 'Admin CS meminta penerbitan invoice senilai Rp 50.000.000 untuk event wedding dwi', '2026-08-11 20:55:08', '2026-08-11 20:55:08'),
(121, 'Tim Finance', 'Upload Invoice', 'Finance mengunggah invoice untuk event wedding dwi', '2026-08-11 20:55:54', '2026-08-11 20:55:54'),
(122, 'Admin CS', 'Upload Bukti Transfer', 'CS mengunggah bukti pembayaran klien untuk event wedding dwi', '2026-08-11 20:56:49', '2026-08-11 20:56:49'),
(123, 'Tim Finance', 'Validasi Pembayaran', 'Finance mensahkan pembayaran untuk event wedding dwi', '2026-08-11 20:57:42', '2026-08-11 20:57:42'),
(124, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding dwi\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-08-11 21:01:00', '2026-08-11 21:01:00'),
(125, 'Manager Operasional', 'Finish Event', 'Manager Operasional menyatakan event \"wedding dwi\" selesai. E-Survey & tagihan komisi dikirim otomatis.', '2026-08-11 21:01:03', '2026-08-11 21:01:03');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-admin@vms.test|127.0.0.1', 'i:1;', 1782280553),
('laravel-cache-admin@vms.test|127.0.0.1:timer', 'i:1782280553;', 1782280553),
('laravel-cache-partner@vms.test|127.0.0.1', 'i:1;', 1781012541),
('laravel-cache-partner@vms.test|127.0.0.1:timer', 'i:1781012541;', 1781012541),
('laravel-cache-sp@vms.test|127.0.0.1', 'i:2;', 1781012323),
('laravel-cache-sp@vms.test|127.0.0.1:timer', 'i:1781012323;', 1781012323);

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
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_klien` varchar(255) NOT NULL,
  `instansi` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `no_telepon` varchar(255) NOT NULL,
  `kebutuhan_klien` text NOT NULL,
  `tanggal_acara` date DEFAULT NULL,
  `budget` bigint(20) DEFAULT NULL,
  `tempat_acara` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_terpilih_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_vendor_acc` tinyint(1) NOT NULL DEFAULT 0,
  `catatan_operasional` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `nama_klien`, `instansi`, `email`, `no_telepon`, `kebutuhan_klien`, `tanggal_acara`, `budget`, `tempat_acara`, `created_at`, `updated_at`, `vendor_terpilih_id`, `is_vendor_acc`, `catatan_operasional`) VALUES
(1, 'Keluarga Bapak Budi', 'Personal', 'budi.family@email.test', '089988776655', 'Kebutuhan paket dekorasi pelaminan elegan, tenda roder VIP transparan, dan katering prasmanan untuk 1000 tamu undangan.', NULL, NULL, NULL, '2026-06-08 07:02:03', '2026-06-08 07:02:03', NULL, 0, NULL),
(2, 'putri', 'personal', 'nurlelarofiqohwdy@gmail.com', '0895703000308', 'Venue\r\n----------------------------------', '2026-07-09', 50000000, 'jl. jakarta', '2026-06-08 10:07:51', '2026-06-08 10:15:18', NULL, 1, NULL),
(3, 'Keluarga Bapak Budi', 'Personal', 'budi.family@email.test', '089988776655', 'Kebutuhan paket dekorasi pelaminan elegan, tenda roder VIP transparan, dan katering prasmanan untuk 1000 tamu undangan.', NULL, NULL, NULL, '2026-06-11 03:53:13', '2026-06-11 03:53:13', NULL, 0, NULL),
(4, 'Zahra', 'personal', 'nurlelarofiqohwdy@gmail.com', '0895703000308', 'Venue\r\n----------------------------------\r\nKapasitas : > 1000 Pax\r\nTipe Venue : Semi-Outdoor\r\n\r\nSound System\r\n----------------------------------\r\nTotal Daya : 5.000 - 10.000 Watt', '2026-06-14', 50000000, 'Jakarta Pusat', '2026-06-14 06:56:07', '2026-06-14 09:35:42', NULL, 1, NULL),
(5, 'mila', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------', '2026-07-30', 100000000, 'Jakarta Selatan', '2026-06-23 09:21:36', '2026-06-23 22:44:14', NULL, 1, NULL),
(6, 'mila', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------', '2026-06-30', 5000000000, 'Jakarta Pusat', '2026-06-24 21:18:07', '2026-06-29 21:31:13', NULL, 1, NULL),
(7, 'atha', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Catering\r\n----------------------------------', '2026-07-11', 50000000, 'Jakarta Pusat', '2026-06-29 22:19:02', '2026-06-29 22:21:01', NULL, 1, NULL),
(8, 'putra', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Catering\r\n----------------------------------\r\n\r\nVenue\r\n----------------------------------\r\n\r\nDokumentasi\r\n----------------------------------', '2026-07-31', 50000000, 'Jakarta Barat', '2026-07-01 21:16:21', '2026-07-01 21:21:35', NULL, 1, NULL),
(9, 'dewi', 'peersonal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nCatering\r\n----------------------------------\r\n\r\nDekorasi\r\n----------------------------------', '2026-07-31', 50000000, 'Jakarta Pusat', '2026-07-08 21:33:51', '2026-07-08 21:35:28', NULL, 1, NULL),
(10, 'dinda', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Dokumentasi\r\n----------------------------------', '2026-08-08', 50000000, 'Jakarta Pusat', '2026-07-13 20:48:31', '2026-07-13 20:48:31', NULL, 0, NULL),
(11, 'Ali', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Sound System\r\n----------------------------------\r\n\r\nVenue\r\n----------------------------------', '2026-08-29', 50000000, 'Jakarta Pusat', '2026-07-13 20:58:36', '2026-07-13 20:58:36', NULL, 0, NULL),
(12, 'atha', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------', '2026-08-08', 50000000, 'Jakarta Selatan', '2026-07-13 21:20:49', '2026-07-13 21:20:49', NULL, 0, NULL),
(13, 'anita', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nCatering\r\n----------------------------------\r\n\r\nDokumentasi\r\n----------------------------------\r\n\r\nSound System\r\n----------------------------------\r\n\r\nEntertainment\r\n----------------------------------\r\n\r\nAttire\r\n----------------------------------\r\n\r\nMakeup Artist\r\n----------------------------------', '2026-08-08', 25000000, 'Jakarta Pusat', '2026-07-27 12:18:45', '2026-07-27 12:27:23', NULL, 1, NULL),
(14, 'balqis', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nSound System\r\n----------------------------------\r\n\r\nDekorasi\r\n----------------------------------', '2026-07-30', 200000000, 'Jakarta Selatan', '2026-07-27 21:55:23', '2026-07-27 21:57:52', NULL, 1, NULL),
(15, 'lili', 'pesonal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nCatering\r\n----------------------------------', '2026-08-08', 50000000, 'Jakarta Pusat', '2026-07-27 23:54:38', '2026-07-27 23:57:19', NULL, 1, NULL),
(16, 'ziza', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nAttire\r\n----------------------------------\r\n\r\nDokumentasi\r\n----------------------------------', '2026-09-05', 50000000, 'Jakarta Barat', '2026-08-11 07:54:26', '2026-08-11 08:02:06', NULL, 1, NULL),
(17, 'dwi', 'personal', 'nurlelarofiqohwdy@gmail.com', '085880526213', 'Venue\r\n----------------------------------\r\n\r\nCatering\r\n----------------------------------', '2026-09-02', 50000000, 'Jakarta Pusat', '2026-08-11 20:49:10', '2026-08-11 20:54:05', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_vendor`
--

CREATE TABLE `client_vendor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_komisi` decimal(15,2) DEFAULT NULL,
  `status_komisi` varchar(255) NOT NULL DEFAULT 'Belum Lunas',
  `tanggal_bayar_komisi` timestamp NULL DEFAULT NULL,
  `jumlah_reminder_terkirim` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `skor_survey_klien` decimal(3,1) DEFAULT NULL,
  `skor_kecepatan_komisi` decimal(3,1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_vendor`
--

INSERT INTO `client_vendor` (`id`, `client_id`, `vendor_id`, `jumlah_komisi`, `status_komisi`, `tanggal_bayar_komisi`, `jumlah_reminder_terkirim`, `skor_survey_klien`, `skor_kecepatan_komisi`, `created_at`, `updated_at`) VALUES
(4, 6, 429, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-06-29 21:30:26', '2026-06-29 21:30:26'),
(5, 7, 432, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-06-29 22:19:48', '2026-06-29 22:19:48'),
(6, 8, 530, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-07-01 21:20:11', '2026-07-01 21:20:11'),
(7, 8, 532, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-07-01 21:20:11', '2026-07-01 21:20:11'),
(8, 8, 539, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-07-01 21:20:11', '2026-07-01 21:20:11'),
(9, 9, 429, NULL, 'Belum Lunas', NULL, 0, 5.0, NULL, '2026-07-08 21:34:33', '2026-07-08 21:34:33'),
(10, 13, 429, NULL, 'Lunas', '2026-07-27 12:53:50', 0, 4.0, NULL, '2026-07-27 12:24:35', '2026-07-27 12:53:50'),
(11, 13, 434, NULL, 'Belum Lunas', NULL, 0, 4.0, NULL, '2026-07-27 12:24:35', '2026-07-27 12:24:35'),
(12, 13, 439, NULL, 'Lunas', '2026-07-27 12:54:00', 0, 4.0, NULL, '2026-07-27 12:24:35', '2026-07-27 12:54:00'),
(13, 13, 606, NULL, 'Belum Lunas', NULL, 0, 5.0, NULL, '2026-07-27 12:24:35', '2026-07-27 12:24:35'),
(14, 13, 654, NULL, 'Lunas', '2026-07-27 12:54:10', 0, 5.0, NULL, '2026-07-27 12:24:35', '2026-07-27 12:54:10'),
(15, 14, 619, NULL, 'Lunas', '2026-07-27 22:07:28', 0, 4.0, NULL, '2026-07-27 21:57:02', '2026-07-27 22:07:28'),
(16, 14, 600, NULL, 'Lunas', '2026-07-27 22:07:38', 0, 4.0, NULL, '2026-07-27 21:57:02', '2026-07-27 22:07:38'),
(17, 14, 434, NULL, 'Belum Lunas', NULL, 0, 4.0, NULL, '2026-07-27 21:57:02', '2026-07-27 21:57:02'),
(18, 15, 654, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-07-27 23:56:25', '2026-07-27 23:56:25'),
(19, 15, 552, NULL, 'Belum Lunas', NULL, 0, NULL, NULL, '2026-07-27 23:56:25', '2026-07-27 23:56:25'),
(20, 16, 530, NULL, 'Lunas', '2026-08-11 08:45:40', 0, 4.0, NULL, '2026-08-11 07:57:37', '2026-08-11 08:45:40'),
(21, 16, 538, NULL, 'Lunas', '2026-08-11 08:46:30', 0, 3.0, NULL, '2026-08-11 07:57:37', '2026-08-11 08:46:30'),
(23, 16, 532, NULL, 'Belum Lunas', NULL, 0, 3.0, NULL, '2026-08-11 08:00:52', '2026-08-11 08:00:52'),
(24, 17, 430, NULL, 'Lunas', '2026-08-11 21:02:29', 0, 5.0, NULL, '2026-08-11 20:51:08', '2026-08-11 21:02:29'),
(25, 17, 432, NULL, 'Belum Lunas', NULL, 0, 4.0, NULL, '2026-08-11 20:51:08', '2026-08-11 20:51:08');

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
-- Table structure for table `finance_payments`
--

CREATE TABLE `finance_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `jumlah_komisi` decimal(15,2) NOT NULL,
  `status_pembayaran` varchar(255) NOT NULL DEFAULT 'pending',
  `tenggat_waktu` date NOT NULL,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
<div class="alert alert-danger" role="alert"><h1>Error</h1><p><strong>SQL query:</strong>  <a href="#" class="copyQueryBtn" data-text="SET SQL_QUOTE_SHOW_CREATE = 1">Copy</a>
<a href="index.php?route=/server/sql&sql_query=SET+SQL_QUOTE_SHOW_CREATE+%3D+1&show_query=1"><span class="text-nowrap"><img src="themes/dot.gif" title="Edit" alt="Edit" class="icon ic_b_edit">&nbsp;Edit</span></a>    </p>
<p>
<code class="sql"><pre>
SET SQL_QUOTE_SHOW_CREATE = 1
</pre></code>
</p>
<p>
    <strong>MySQL said: </strong><a href="./url.php?url=https%3A%2F%2Fdev.mysql.com%2Fdoc%2Frefman%2F8.0%2Fen%2Fserver-error-reference.html" target="mysql_doc"><img src="themes/dot.gif" title="Documentation" alt="Documentation" class="icon ic_b_help"></a>
</p>
<code>#2006 - MySQL server has gone away</code><br></div>