-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2025 at 05:43 AM
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
-- Table structure for table `meter_readings`
--

CREATE TABLE `meter_readings` (
  `meterID` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `tenant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `meter_details_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meter_readings`
--

INSERT INTO `meter_readings` (`meterID`, `room_id`, `tenant_id`, `meter_details_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, NULL, NULL),
(2, 2, NULL, 2, NULL, NULL),
(3, 3, NULL, 3, NULL, NULL),
(4, 4, NULL, 4, NULL, NULL),
(5, 5, NULL, 5, NULL, NULL),
(6, 6, NULL, 6, NULL, NULL),
(7, 7, NULL, 7, NULL, NULL),
(8, 8, NULL, 8, NULL, NULL),
(9, 9, NULL, 9, NULL, NULL),
(10, 10, NULL, 10, NULL, NULL),
(11, 11, NULL, 11, NULL, NULL),
(12, 12, NULL, 12, NULL, NULL),
(13, 13, NULL, 13, NULL, NULL),
(14, 14, NULL, 14, NULL, NULL),
(15, 15, NULL, 15, NULL, NULL),
(16, 16, NULL, 16, NULL, NULL),
(17, 17, NULL, 17, NULL, NULL),
(18, 18, NULL, 18, NULL, NULL),
(19, 19, NULL, 19, NULL, NULL),
(20, 20, NULL, 20, NULL, NULL),
(21, 21, NULL, 21, NULL, NULL),
(22, 22, NULL, 22, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD PRIMARY KEY (`meterID`),
  ADD KEY `meter_readings_room_id_foreign` (`room_id`),
  ADD KEY `meter_readings_tenant_id_foreign` (`tenant_id`),
  ADD KEY `meter_readings_meter_details_id_foreign` (`meter_details_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meter_readings`
--
ALTER TABLE `meter_readings`
  MODIFY `meterID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD CONSTRAINT `meter_readings_meter_details_id_foreign` FOREIGN KEY (`meter_details_id`) REFERENCES `meter_details` (`meter_detailID`),
  ADD CONSTRAINT `meter_readings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`roomID`),
  ADD CONSTRAINT `meter_readings_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenantID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
