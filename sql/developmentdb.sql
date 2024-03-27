-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Mar 27, 2024 at 05:57 PM
-- Server version: 11.3.2-MariaDB-1:11.3.2+maria~ubu2204
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Lists`
--

CREATE TABLE `Lists` (
  `list_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listname` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Lists`
--

INSERT INTO `Lists` (`list_id`, `user_id`, `listname`, `created_at`, `updated_at`) VALUES
(15, 9, 'Coding', '2024-03-27 17:50:45', '2024-03-27 17:50:45'),
(16, 9, 'Finished', '2024-03-27 17:51:12', '2024-03-27 17:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `Tasks`
--

CREATE TABLE `Tasks` (
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `status` enum('completed','pending') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `list_id` int(11) DEFAULT NULL,
  `time_elapsed` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Tasks`
--

INSERT INTO `Tasks` (`task_id`, `user_id`, `title`, `description`, `deadline`, `status`, `created_at`, `updated_at`, `list_id`, `time_elapsed`) VALUES
(53, 9, 'Code Documentation Needs To Get Done', 'I need to add comments and explain my code to my colleagues. Primarily, I implemented the content management system. ', '2024-03-30 00:00:00', 'pending', '2024-03-27 17:50:50', '2024-03-27 17:50:50', 15, 0),
(54, 9, 'Drawing A Portrait ', 'I need to draw a portrait of my family by Monday next week. The teacher was very specific about the fact that it had to be done in charcoal. ', '2024-04-01 00:00:00', 'completed', '2024-03-27 17:52:13', '2024-03-27 17:56:30', 16, 136),
(55, 9, 'Study For Math Exam ', 'Study algebra for the coming math exam. ', '2024-04-06 00:00:00', 'pending', '2024-03-27 17:52:50', '2024-03-27 17:52:50', NULL, 0),
(56, 9, 'Write implementation for checking payment status', 'I need to write a payment implementation with a testing sandbox with utilizing the Mollie API.', '2024-04-18 00:00:00', 'pending', '2024-03-27 17:53:58', '2024-03-27 17:53:58', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `email`, `name`, `password_hash`, `role`) VALUES
(6, 'joan.idevelop@gmail.com', 'admin', '$2y$10$AkY.0gqaJzYiHowSPVJJTODU0MJTLBmHPHGXQuKP6YlqsqWz3aaAi', 'admin'),
(9, 'm@gmail.com', 'Marta', '$2y$10$/E3lqUFAYmcbDvhOQnMzs.NVE3ZJ8Zb4gYBmOXFxwCNuX7V9jJX2G', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Lists`
--
ALTER TABLE `Lists`
  ADD PRIMARY KEY (`list_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `list_id` (`list_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Lists`
--
ALTER TABLE `Lists`
  MODIFY `list_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Lists`
--
ALTER TABLE `Lists`
  ADD CONSTRAINT `Lists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD CONSTRAINT `Tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Tasks_ibfk_2` FOREIGN KEY (`list_id`) REFERENCES `Lists` (`list_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
