-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 10:07 PM
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
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE `auth_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','IT','QAQC','MLE') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `office_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`id`, `name`, `email`, `password`, `role`, `department_id`, `position_id`, `office_id`, `location_id`, `created_at`, `updated_at`) VALUES
(1, 'Rita Kogi', 'rhyttahkogi@gmail.com', '$2y$10$Er7WrH69Y0rcaJLiR5wLz.8pbqshJmMZIDZEyGwUEcaX4cm5ZmtuK', 'super_admin', 4, 1, 5, 3, '2025-02-27 17:02:09', '2025-03-09 14:05:25'),
(2, 'Rama Ali', 'rama@gmail.com', '$2y$10$YHY5x7N0xNepseznXxyXSO/JfM40eooazBFH3SLG/oJhg2YvXsNgu', 'MLE', 4, 2, 3, 1, '2025-02-27 17:03:11', '2025-03-07 17:32:13'),
(3, 'John Mark', 'john@gmail.com', '$2y$10$fcH..6wXptO7rnKwikU4lOQJPalUnjucYeqXQbYMkAKnSMxGhyv62', 'IT', 3, 2, 1, 2, '2025-02-27 17:03:42', '2025-03-07 17:33:16'),
(4, 'Terence', 'user@gmail.com', '$2y$10$xBASGk95IMn0YydRnz1ReuDF9JNvvqYDgB1HC8jrZmqqxCb8ysQyO', 'QAQC', 1, 3, 3, 1, '2025-02-27 17:04:14', '2025-03-03 08:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Laptop', 'lenovo', '2025-02-21 12:58:41'),
(2, 'Laptop', 'hp', '2025-02-21 12:58:48'),
(3, 'Smart Phone', 'Samsung A33', '2025-02-21 12:59:03'),
(4, 'Smart Phone', 'Iphone 16 Pro-Max', '2025-02-21 12:59:12'),
(5, 'Mouse', 'HP', '2025-02-24 06:10:15'),
(6, 'Printer', 'inkjet printers', '2025-02-25 07:36:43'),
(7, 'Printer', 'laser printers', '2025-02-25 07:41:53'),
(8, 'Printer', 'Solid Ink printers', '2025-02-25 07:44:58'),
(9, 'Smart Phone', 'Samsung A52', '2025-02-25 07:45:38'),
(10, 'Laptop', 'Macbook', '2025-02-27 07:07:36');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created`) VALUES
(1, 'QA&QC', '2025-02-25 07:26:55'),
(3, 'IT', '2025-02-25 07:27:16'),
(4, 'MLE', '2025-02-25 07:27:26');

-- --------------------------------------------------------

--
-- Stand-in structure for view `disposed`
-- (See below for the actual view)
--
CREATE TABLE `disposed` (
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `instock`
-- (See below for the actual view)
--
CREATE TABLE `instock` (
);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `tag_number` varchar(100) NOT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_cost` decimal(10,2) NOT NULL,
  `warranty_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `category_id`, `description`, `serial_number`, `tag_number`, `acquisition_date`, `acquisition_cost`, `warranty_date`, `created_at`) VALUES
(2, 5, 'HP', '9892902', 'ea-1991', '2025-02-19', 60.00, '2025-02-28', '2025-02-21 13:58:54'),
(8, 3, 'Redmi', '5ftgy', 'ea-1175w', '2025-02-04', 150.00, '2025-03-08', '2025-02-21 14:33:56'),
(11, 4, 'Samsung A33', '6666666', 'ea-5555', '2025-02-01', 20.00, '2025-03-08', '2025-02-21 15:21:05'),
(12, 4, 'Lenovo', 'abc22999', 'ea-0001', '2025-02-20', 30.00, '2025-02-28', '2025-02-21 15:49:22'),
(13, 4, 'Redmi', 'abc2219', 'ea-00033', '2025-02-20', 30.00, '2025-02-28', '2025-02-21 15:49:51'),
(17, 8, 'Macbook', '4567890', 'ea-1109', '2025-02-04', 999.00, '2025-03-08', '2025-02-24 10:21:19'),
(27, 2, 'Lenovo', '999', '098u7y6t5r', '2025-02-12', 88.00, '2025-03-08', '2025-02-24 10:58:56'),
(34, 10, 'Macbook', '9w9w', '9wyyt6t76', '2025-02-25', 90.00, '2025-03-08', '2025-02-27 08:06:57'),
(35, 8, 'Solid Ink printers', '6t6t6y', 'ea-700', '2025-03-01', 500.00, '2025-03-10', '2025-03-03 05:31:47'),
(36, 10, 'Macbook', 'im123', 'u111', '2025-02-26', 10.00, '2025-03-08', '2025-03-03 07:04:01'),
(37, 9, 'Samsung A52', 'lkjhygt111', 'ea-1807', '2025-02-26', 90.00, '2025-03-20', '2025-03-03 07:04:28'),
(38, 5, 'HP', '78wuswk', '899-ea', '2025-02-25', 56.00, '2025-03-20', '2025-03-03 07:05:00'),
(40, 4, 'Iphone 16 Pro-Max', '67772qt', 'ea-145', '2025-02-27', 194.00, '2025-03-19', '2025-03-03 07:06:09'),
(41, 6, 'inkjet printers', '08090909', 'ea125', '2025-02-26', 20.00, '2025-03-28', '2025-03-06 11:55:02'),
(43, 10, 'Macbook', 'qwj8888', 'ea444', '2025-03-05', 120.00, '2025-04-05', '2025-03-09 16:36:38'),
(44, 7, 'laser printers', '8u7uu', 'evac22', '2025-02-25', 50.00, '2025-04-04', '2025-03-09 18:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `item_assignments`
--

CREATE TABLE `item_assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `date_assigned` date DEFAULT NULL,
  `managed_by` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `acknowledged` enum('pending','acknowledged') DEFAULT 'pending',
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_assignments`
--

INSERT INTO `item_assignments` (`id`, `user_id`, `inventory_id`, `date_assigned`, `managed_by`, `created`, `acknowledged`, `created_by`) VALUES
(4, 1, 34, '2025-02-12', 'Rama', '2025-02-27 18:59:24', 'acknowledged', 0),
(5, 1, 11, '2025-02-12', 'Rama', '2025-02-27 18:59:24', 'acknowledged', 0),
(6, 2, 13, '2025-02-12', 'Dennis', '2025-02-28 05:11:13', 'pending', 0),
(7, 4, 2, '2025-02-19', 'Dennis', '2025-02-28 05:12:17', 'acknowledged', 0),
(8, 3, 17, '2025-02-11', 'Dennis', '2025-02-28 05:28:48', 'pending', 0),
(9, 3, 27, '2025-02-11', 'Dennis', '2025-02-28 05:28:48', 'pending', 0),
(10, 4, 34, '2025-02-20', 'Dennis', '2025-02-28 08:46:43', 'pending', 0),
(11, 1, 8, '2025-03-12', 'Rama', '2025-03-03 05:09:02', 'acknowledged', 0),
(12, 1, 12, '2025-03-12', 'Rama', '2025-03-03 05:09:02', 'acknowledged', 0),
(13, 1, 35, '2025-02-28', 'Rama', '2025-03-03 07:07:15', 'acknowledged', 0),
(14, 1, 37, '2025-02-28', 'Rama', '2025-03-03 07:07:15', 'acknowledged', 0),
(15, 1, 12, '2025-02-28', 'Rama', '2025-03-03 07:07:15', 'acknowledged', 0),
(16, 1, 38, '2025-02-28', 'Rama', '2025-03-03 07:07:15', 'acknowledged', 0),
(17, 1, 12, '2025-02-28', 'Dennis', '2025-03-03 08:10:13', 'acknowledged', 0),
(19, 1, 36, '2025-03-01', 'Terence', '2025-03-03 08:48:34', 'acknowledged', 0),
(20, 1, 8, '2025-02-27', 'Terence', '2025-03-03 09:04:10', 'acknowledged', 1),
(21, 1, 40, '2025-02-27', 'Dennis', '2025-03-03 09:31:27', 'acknowledged', 1),
(22, 1, 12, '2025-03-03', 'Rama', '2025-03-04 11:44:15', 'acknowledged', 1),
(23, 1, 8, '2025-02-26', 'Rama', '2025-03-04 11:47:08', 'acknowledged', 1),
(24, 1, 35, '2025-02-27', 'Rama', '2025-03-04 11:48:13', 'acknowledged', 1),
(25, 1, 35, '2025-02-27', 'Rama', '2025-03-04 11:48:31', 'acknowledged', 1),
(27, 1, 41, '2025-02-26', 'Eric', '2025-03-06 14:38:45', 'acknowledged', 1),
(28, 1, 12, '2025-03-05', 'Eric', '2025-03-07 06:29:05', 'acknowledged', 1),
(29, 4, 43, '2025-02-24', 'Dennis', '2025-03-09 17:29:51', 'pending', 0),
(30, 3, 44, '2025-03-05', 'Eric Eric', '2025-03-09 19:38:07', 'pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_returns`
--

CREATE TABLE `item_returns` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `item_state` enum('functional','damaged','lost') DEFAULT NULL,
  `return_date` date NOT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `repair_status` enum('Repairable','Unrepairable') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_returns`
--

INSERT INTO `item_returns` (`id`, `user_id`, `item_id`, `receiver_id`, `status`, `item_state`, `return_date`, `approved_by`, `approved_date`, `created_at`, `updated_at`, `repair_status`) VALUES
(2, 1, 11, 1, 'approved', 'lost', '2025-03-04', 1, '2025-03-03 06:08:23', '2025-03-01 12:04:00', '2025-03-03 05:08:23', NULL),
(3, 1, 8, 1, 'approved', 'damaged', '2025-02-25', 1, '2025-03-03 06:10:56', '2025-03-03 05:10:15', '2025-03-03 06:54:19', 'Repairable'),
(4, 1, 12, 1, 'approved', 'functional', '2025-02-27', 1, '2025-03-03 06:11:01', '2025-03-03 05:10:46', '2025-03-03 05:43:30', NULL),
(5, 1, 34, 3, 'pending', NULL, '2025-02-25', NULL, NULL, '2025-03-03 05:35:18', '2025-03-03 05:35:18', NULL),
(6, 1, 35, 1, 'approved', 'functional', '2025-03-04', 1, '2025-03-04 12:45:53', '2025-03-04 11:45:18', '2025-03-04 11:45:53', NULL),
(7, 1, 37, 1, 'approved', 'damaged', '2025-02-26', 1, '2025-03-06 11:58:26', '2025-03-04 11:54:52', '2025-03-07 04:51:55', 'Unrepairable'),
(8, 1, 36, 1, 'approved', 'damaged', '2025-03-05', 1, '2025-03-07 05:48:09', '2025-03-06 10:27:34', '2025-03-07 09:02:29', 'Unrepairable'),
(9, 1, 38, 1, 'approved', 'damaged', '2025-03-05', 1, '2025-03-07 10:02:11', '2025-03-06 10:31:11', '2025-03-07 09:02:11', NULL),
(10, 1, 40, 1, 'approved', 'functional', '2025-03-05', 1, '2025-03-09 23:50:37', '2025-03-07 09:02:00', '2025-03-09 20:50:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `created`) VALUES
(1, 'Awendo', '2025-02-25 07:58:49'),
(2, 'Busia', '2025-02-25 08:06:05'),
(3, 'Chavakali', '2025-02-25 08:06:23'),
(4, 'Matunda', '2025-02-25 08:06:36'),
(5, 'Ugunja', '2025-02-25 08:06:54'),
(6, 'Nairobi', '2025-02-25 08:07:04');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`id`, `location_id`, `name`, `created`) VALUES
(1, 2, 'Amagoro Hub', '2025-02-25 08:12:36'),
(3, 1, 'Awendo Field Office', '2025-02-25 08:15:04'),
(4, 1, 'Kuria Hub', '2025-02-25 08:15:52'),
(5, 3, 'Busia Field Office', '2025-02-25 08:16:31');

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `name`, `created`) VALUES
(1, 'Manager', '2025-02-25 07:26:14'),
(2, 'Associate  Manager', '2025-02-25 07:46:14'),
(3, 'Senior Associate', '2025-02-25 07:48:02');

-- --------------------------------------------------------

--
-- Structure for view `disposed`
--
DROP TABLE IF EXISTS `disposed`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `disposed`  AS SELECT `ir`.`id` AS `id`, `ir`.`user_id` AS `user_id`, `ir`.`inventory_id` AS `inventory_id`, `ir`.`return_date` AS `return_date`, `ir`.`received_by` AS `received_by`, `ir`.`created` AS `created`, `ir`.`approval_date` AS `approval_date`, `ir`.`approved_by` AS `approved_by`, `ir`.`rejection_reason` AS `rejection_reason`, `ir`.`item_state` AS `item_state`, `ir`.`repair_status` AS `repair_status`, `ir`.`processed_at` AS `processed_at`, `u`.`name` AS `name`, `i`.`description` AS `description`, `i`.`serial_number` AS `serial_number` FROM ((`item_returns` `ir` join `user_profiles` `u` on(`ir`.`user_id` = `u`.`id`)) join `inventory` `i` on(`ir`.`inventory_id` = `i`.`id`)) WHERE `ir`.`item_state` = 'lost' OR `ir`.`item_state` = 'damaged' AND `ir`.`repair_status` = 'unrepairable' ;

-- --------------------------------------------------------

--
-- Structure for view `instock`
--
DROP TABLE IF EXISTS `instock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `instock`  AS SELECT `ir`.`id` AS `id`, `ir`.`user_id` AS `user_id`, `ir`.`inventory_id` AS `inventory_id`, `ir`.`return_date` AS `return_date`, `ir`.`received_by` AS `received_by`, `ir`.`created` AS `created`, `ir`.`approval_date` AS `approval_date`, `ir`.`approved_by` AS `approved_by`, `ir`.`rejection_reason` AS `rejection_reason`, `ir`.`item_state` AS `item_state`, `ir`.`repair_status` AS `repair_status`, `ir`.`processed_at` AS `processed_at`, `u`.`name` AS `name`, `i`.`description` AS `description`, `i`.`serial_number` AS `serial_number` FROM ((`item_returns` `ir` join `user_profiles` `u` on(`ir`.`user_id` = `u`.`id`)) join `inventory` `i` on(`ir`.`inventory_id` = `i`.`id`)) WHERE `ir`.`item_state` = 'functional' OR `ir`.`item_state` = 'damaged' AND `ir`.`repair_status` = 'repaired' ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `position_id` (`position_id`),
  ADD KEY `office_id` (`office_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD UNIQUE KEY `tag_number` (`tag_number`),
  ADD UNIQUE KEY `unique_serial` (`serial_number`),
  ADD UNIQUE KEY `unique_tag` (`tag_number`),
  ADD UNIQUE KEY `serial_number_2` (`serial_number`),
  ADD UNIQUE KEY `tag_number_2` (`tag_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `item_assignments`
--
ALTER TABLE `item_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_id` (`inventory_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `item_returns`
--
ALTER TABLE `item_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_users`
--
ALTER TABLE `auth_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `item_assignments`
--
ALTER TABLE `item_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `item_returns`
--
ALTER TABLE `item_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_users`
--
ALTER TABLE `auth_users`
  ADD CONSTRAINT `auth_users_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `auth_users_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `auth_users_ibfk_3` FOREIGN KEY (`office_id`) REFERENCES `offices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `auth_users_ibfk_4` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_assignments`
--
ALTER TABLE `item_assignments`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_assignments_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_returns`
--
ALTER TABLE `item_returns`
  ADD CONSTRAINT `item_returns_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_returns_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_returns_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `auth_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_returns_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `auth_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `offices`
--
ALTER TABLE `offices`
  ADD CONSTRAINT `offices_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
