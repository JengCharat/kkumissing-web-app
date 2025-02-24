-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 03, 2025 at 05:49 AM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `usertype` varchar(255) NOT NULL DEFAULT 'user',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `usertype`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'L201', 'user', 'L201@gmail.com', NULL, '$2y$12$nSTTGoD1FuByNH1GzblHxu2LYfHo8cm4NmSnDHvZMMSqAnXwLDdK6', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 20:54:17', '2025-02-02 20:54:17'),
(4, 'L202', 'user', 'L202@gmail.com', NULL, '$2y$12$./gOCJoM8cCv0xn6yo/GRu5CqeHjEW.U7pwf2vqaJX1jSwb7tKLra', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:03:52', '2025-02-02 21:03:52'),
(5, 'L203', 'user', 'L203@gmail.com', NULL, '$2y$12$g8TeR2fYhRyktgxADc61eeUBDXepKI..FoL3Dr7j2XncV6ApMD0cy', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:04:25', '2025-02-02 21:04:25'),
(6, 'L204', 'user', 'L204@gmail.com', NULL, '$2y$12$VHDln8QMhEczp3/jT6RgdOjiBb.G1/Xors/g4AGZVClxaEQH9dSX2', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:05:34', '2025-02-02 21:05:34'),
(9, 'L205', 'user', 'L205@gmail.com', NULL, '$2y$12$nDeWXXmTVs1/rBv28NEolOE8rMol8ZvZqzanSLOUscAzeUIdHocFa', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:06:15', '2025-02-02 21:06:15'),
(10, 'R206', 'user', 'R206@gmail.com', NULL, '$2y$12$KW71IcWw/0vdn4VF4TE9.Or4GmPgxCHFjpWeKI3MLFihSavuPxB66', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:08:21', '2025-02-02 21:08:21'),
(11, 'L101', 'user', 'L101@gmail.com', NULL, '$2y$12$uPqoov.tvSad7PGH.eS81u2CoYE1Snf8vXZFgxjj/Hzr0JVnOGzsS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:08:33', '2025-02-02 21:08:33'),
(12, 'L102', 'user', 'L102@gmail.com', NULL, '$2y$12$Hd4XFauFcO0nap933QB6QeOng.ytc/R4/N8P5tlgdKjjquZGDmeWa', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:09:10', '2025-02-02 21:09:10'),
(13, 'R207', 'user', 'R207@gmail.com', NULL, '$2y$12$uGPfIIWkJCIAtaUgFGtko.6ULASyZDceP76OKj7Z9RVCXo.j.3t8C', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:09:15', '2025-02-02 21:09:15'),
(14, 'L103', 'user', 'L103@gmail.com', NULL, '$2y$12$hhFAuP4oyzJpAs3Up.JLDOeSgl.xhrq11Cx4zjCHQzTZVji7qbLaa', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:09:38', '2025-02-02 21:09:38'),
(15, 'L104', 'user', 'L104@gmail.com', NULL, '$2y$12$Zx5/XnAUlkrDJp/.LT1XtupEdr0BIDI5HWt1rUtj4sxjN7BcNGYee', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:10:05', '2025-02-02 21:10:05'),
(16, 'R209', 'user', 'R209@gmail.com', NULL, '$2y$12$zjxoJVx4m5dnI0Ce9.E/8uOLQYC0VRnmCxpp3zybZQ/UqOC9sq3PG', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:10:19', '2025-02-02 21:10:19'),
(17, 'R208', 'user', 'R208@gmail.com', NULL, '$2y$12$jYzorRcFc8osz5bpU1Cyi.iShAB.rJTtV6a12vQykJWntGaheUNXa', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:11:05', '2025-02-02 21:11:05'),
(18, 'R210', 'user', 'R210@gmail.com', NULL, '$2y$12$neK4nnHameH6crxsRj/olOg3v0gmw1Mh1MALOVm2XHfMytjz8NHim', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:11:51', '2025-02-02 21:11:51'),
(19, 'R211', 'user', 'R211@gmail.com', NULL, '$2y$12$mbXHH/64fKAyziHyLSTmyeHfeucWxPap3PcPUK5d73yGifB15rt22', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:12:14', '2025-02-02 21:12:14'),
(20, 'R106', 'user', 'R106@gmail.com', NULL, '$2y$12$80qddWLtuJ.AkMcifr6J7e1n29Pk2/jR1ysKUld0f3J7OAq3G35jS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:12:54', '2025-02-02 21:12:54'),
(22, 'R108', 'user', 'R108@gmail.com', NULL, '$2y$12$JM7JsntTyEAszzraqvw0GORQVC.6pJJJcpmStXddUw2PFYK.5UEDO', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:14:23', '2025-02-02 21:14:23'),
(23, 'R107', 'user', 'R107@gmail.com', NULL, '$2y$12$N0OTY0PD07U1TEwyLl4aCObVb1/CGEncpJZaE30tyxDzmp8jJvmKK', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:14:58', '2025-02-02 21:14:58'),
(24, 'R109', 'user', 'R109@gmail.com', NULL, '$2y$12$SMSHyLXMd5CVKOnwtaeNu.ZWn/ADb4LoAR5S7MFnwKproNqf8Xf6q', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:16:21', '2025-02-02 21:16:21'),
(25, 'R110', 'user', 'R110@gmail.com', NULL, '$2y$12$t3x.01zxW5.WhBOyXkfNS.gSwa6FmO/amYmGsfTOrncQuWdm2SROi', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:16:59', '2025-02-02 21:16:59'),
(26, 'R111', 'user', 'R111@gmail.com', NULL, '$2y$12$Lz/6a1ySScNB9xAYlYXSIuPOxV7UaG5sd9ox3deaiZ7ReIJZvVKUy', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:17:29', '2025-02-02 21:17:29'),
(27, 'L105', 'user', 'L105@gmail.com', NULL, '$2y$12$idcrh90xjokxe8m91m7Kke9LawVHPtvAdrz.6JQo/UBGsP2tMNDaS', NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-02 21:22:24', '2025-02-02 21:22:24');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
