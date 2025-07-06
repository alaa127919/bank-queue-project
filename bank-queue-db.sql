-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 11:58 PM
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
-- Database: `bank-queue-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `in_progress_transactions`
--

CREATE TABLE `in_progress_transactions` (
  `id` bigint(20) NOT NULL COMMENT 'ticket_number',
  `user_id` int(11) DEFAULT NULL,
  `transactions_type_id` bigint(20) NOT NULL,
  `window_id` bigint(20) NOT NULL,
  `transaction_duration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `postponed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `in_progress_transactions`
--

INSERT INTO `in_progress_transactions` (`id`, `user_id`, `transactions_type_id`, `window_id`, `transaction_duration`, `postponed`) VALUES
(400, NULL, 2, 2, '2025-06-19 21:43:41', 0),
(800, NULL, 3, 3, '2025-06-15 14:14:23', 0),
(801, NULL, 3, 3, '2025-06-19 21:43:46', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transactions_type`
--

CREATE TABLE `transactions_type` (
  `id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `time_to_finish` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions_type`
--

INSERT INTO `transactions_type` (`id`, `type`, `time_to_finish`) VALUES
(1, 'إيداع', 10),
(2, 'سحب', 10),
(3, 'إنشاء حساب', 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_window`
--

CREATE TABLE `transaction_window` (
  `window_id` bigint(20) NOT NULL,
  `transaction_type_id` bigint(20) NOT NULL,
  `transaction_duration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_window`
--

INSERT INTO `transaction_window` (`window_id`, `transaction_type_id`, `transaction_duration`) VALUES
(1, 1, '2025-06-15 10:51:52'),
(2, 2, '2025-06-15 10:52:01'),
(3, 3, '2025-06-15 10:52:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` bigint(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `admin`) VALUES
(3, 'alaa', '601f1889667efaebb33b8c12572835da3f027f78', 0),
(4, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1),
(5, 'aisha', '7c4a8d09ca3762af61e59520943dc26494f8941b', 0),
(6, 'mohamad', '5491c11f9ee6ff22b260040f4f1b1a3442d127c4', 0),
(7, 'ayham', '601f1889667efaebb33b8c12572835da3f027f78', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_online`
--

CREATE TABLE `users_online` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `windows_number` int(111) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `windows`
--

CREATE TABLE `windows` (
  `id` bigint(20) NOT NULL,
  `name` varchar(55) NOT NULL,
  `window_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `windows`
--

INSERT INTO `windows` (`id`, `name`, `window_id`) VALUES
(1, 'A', 1),
(2, 'B', 2),
(3, 'C', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `in_progress_transactions`
--
ALTER TABLE `in_progress_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_type_id` (`transactions_type_id`),
  ADD KEY `window_id` (`window_id`),
  ADD KEY `in_progress_transactions_ibfk_1` (`user_id`);

--
-- Indexes for table `transactions_type`
--
ALTER TABLE `transactions_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_window`
--
ALTER TABLE `transaction_window`
  ADD PRIMARY KEY (`window_id`,`transaction_type_id`),
  ADD KEY `idx_transaction_type` (`transaction_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `users_online`
--
ALTER TABLE `users_online`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `windows`
--
ALTER TABLE `windows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `window_id` (`window_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions_type`
--
ALTER TABLE `transactions_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `windows`
--
ALTER TABLE `windows`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `in_progress_transactions`
--
ALTER TABLE `in_progress_transactions`
  ADD CONSTRAINT `in_progress_transactions_ibfk_2` FOREIGN KEY (`transactions_type_id`) REFERENCES `transactions_type` (`id`),
  ADD CONSTRAINT `in_progress_transactions_ibfk_3` FOREIGN KEY (`window_id`) REFERENCES `windows` (`id`);

--
-- Constraints for table `transaction_window`
--
ALTER TABLE `transaction_window`
  ADD CONSTRAINT `transaction_window_ibfk_1` FOREIGN KEY (`window_id`) REFERENCES `windows` (`id`),
  ADD CONSTRAINT `transaction_window_ibfk_2` FOREIGN KEY (`transaction_type_id`) REFERENCES `transactions_type` (`id`);

--
-- Constraints for table `windows`
--
ALTER TABLE `windows`
  ADD CONSTRAINT `windows_ibfk_1` FOREIGN KEY (`window_id`) REFERENCES `transactions_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
