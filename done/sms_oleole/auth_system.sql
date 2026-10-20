-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2026 at 09:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auth_system`
--
CREATE DATABASE IF NOT EXISTS `auth_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `auth_system`;

-- --------------------------------------------------------

--
-- Table structure for table `login_sessions`
--

DROP TABLE IF EXISTS `login_sessions`;
CREATE TABLE `login_sessions` (
  `session_id` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students_info_tbl`
--

DROP TABLE IF EXISTS `students_info_tbl`;
CREATE TABLE `students_info_tbl` (
  `id` int(10) UNSIGNED NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(5) NOT NULL,
  `extension_name` varchar(20) DEFAULT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `birthdate` date NOT NULL,
  `street` varchar(150) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `course` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_info_tbl`
--

INSERT INTO `students_info_tbl` (`id`, `last_name`, `first_name`, `middle_initial`, `extension_name`, `age`, `birthdate`, `street`, `barangay`, `city`, `province`, `zip_code`, `course`, `department`, `user_id`, `created_at`) VALUES
(3, 'Ocampo', 'Dominic', 'L', '', 23, '2002-12-29', '222', 'Diamantina', 'Cauayan City', '222', '33315', 'BSIT', 'CCS', 4, '2026-02-17 07:32:39'),
(4, 'Ocampo', 'Dominic', 'L', '', 24, '2001-02-22', '222', 'Diamantina', 'Cauayan City', '222', '33315', 'BSIT', 'CCS', 4, '2026-02-17 08:31:18'),
(5, 'Ocampo', 'Dominic', 'L', '', 21, '2004-03-14', '555', 'wwww', 'Cauayan City', '222', '3315', 'BSIT', 'CIT', 4, '2026-02-17 09:54:05'),
(6, 'Janille', 'Dominic', 'L', '', 20, '2005-12-29', '222', 'Diamantina', 'Cauayan City', '222', '33315', 'BSIT', 'CIT', 4, '2026-02-17 10:17:38'),
(7, 'Janille', 'Dominic', 'L', '', 20, '2005-12-29', '222', 'Diamantina', 'Cauayan City', '222', '33315', 'BSIT', 'CIT', 4, '2026-02-17 11:01:04'),
(8, 'Janille', 'Dominic', 'L', '', 20, '2005-12-29', '222', 'Diamantina', 'Cauayan City', '222', '33315', 'BSIT', 'CIT', 4, '2026-02-17 11:03:29'),
(9, 'Garcia', 'Elie', '', '', 18, '2007-09-22', '99', 'Luzon', 'Cabatuan', 'ISabela', '3315', 'BSED', 'COED', 4, '2026-02-17 13:47:07'),
(10, 'Acosta', 'Janille', '', '', 16, '2009-02-22', '555', 'Luzon', 'Cabatuan', 'ISabela', '3315', 'BSIT', 'CIT', 4, '2026-02-17 17:59:35');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `system_name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT 'default-logo.png',
  `registration_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `system_name`, `logo`, `registration_enabled`, `updated_at`) VALUES
(1, 'Student LMS', 'default-logo.png', 1, '2026-02-17 14:44:30'),
(2, 'Student Management System', 'default-logo.png', 1, '2026-02-17 15:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-02-16 17:18:48'),
(2, 'janille', 'wwww@gmail.com', '$2y$10$ZZncxHQ8SFuNuKRyP6FIq.f/WxuqjE2Nxhi3GgRXfve4/JVz3O1.m', 'user', '2026-02-17 05:16:18'),
(3, 'janille', 'janilleacosta89@gmail.com', '$2y$10$EiRQffKSUaEFk4IVkRHYneh4yCI9fVAwASvpAOgTA56oe5xUqr81e', 'user', '2026-02-17 05:16:43'),
(4, 'janille acosta', 'janilleacosta@gmail.com', '$2y$10$u2sx2oHMzIRU23tG5LDY9.Z.zSOV7b4rUfVRHyXJYy6WjCdLbWDeW', 'user', '2026-02-17 06:37:06'),
(10, 'jaja', 'janilleacosta22@gmail.com', '$2y$10$XDK2mdiYeOakqNfItwqyMOiM7tkKbiJZAY7372abLON.4ZvyUMMMW', 'user', '2026-02-17 08:29:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_sessions`
--
ALTER TABLE `login_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_info_tbl`
--
ALTER TABLE `students_info_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students_info_tbl`
--
ALTER TABLE `students_info_tbl`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `login_sessions`
--
ALTER TABLE `login_sessions`
  ADD CONSTRAINT `login_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students_info_tbl`
--
ALTER TABLE `students_info_tbl`
  ADD CONSTRAINT `students_info_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
