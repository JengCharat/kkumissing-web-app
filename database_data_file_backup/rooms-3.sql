-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2025 at 05:41 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jeng123`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `roomID` bigint(20) UNSIGNED NOT NULL,
  `roomNumber` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Available',
  `daily_rate` decimal(10,2) DEFAULT NULL,
  `water_price` decimal(10,2) DEFAULT NULL,
  `electricity_price` decimal(10,2) DEFAULT NULL,
  `overdue_fee_rate` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`roomID`, `roomNumber`, `status`, `daily_rate`, `water_price`, `electricity_price`, `overdue_fee_rate`, `created_at`, `updated_at`) VALUES
(1, 'L101', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(2, 'L102', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(3, 'L103', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(4, 'L104', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(5, 'L105', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(6, 'L201', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(7, 'L202', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(8, 'L203', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(9, 'L204', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(10, 'L205', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(11, 'R106', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(12, 'R107', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(13, 'R108', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(14, 'R109', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(15, 'R110', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(16, 'R111', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(17, 'R206', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(18, 'R207', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(19, 'R208', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(20, 'R209', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(21, 'R210', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57'),
(22, 'R211', 'Available', 400.00, NULL, NULL, NULL, '2025-02-03 03:49:57', '2025-02-03 03:49:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`roomID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `roomID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
