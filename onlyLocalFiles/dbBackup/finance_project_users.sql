-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2021 at 09:29 AM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finance_project_users`
--

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `expenses_category_assigned_to_user_id` int(11) NOT NULL,
  `payment_method_assigned_to_user_id` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date_of_expense` date NOT NULL,
  `expense_comment` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `expenses_category_assigned_to_user_id`, `payment_method_assigned_to_user_id`, `amount`, `date_of_expense`, `expense_comment`) VALUES
(4, 26, 34, 11, '50.00', '2021-02-14', ''),
(5, 26, 40, 11, '100.00', '2021-02-08', ''),
(6, 26, 32, 11, '555.00', '2021-02-11', 'aaa'),
(7, 26, 38, 12, '789.00', '2021-02-17', 'bbb'),
(8, 26, 44, 13, '456.00', '2021-02-27', 'cccLorem Ipsum is simply dummy text of the printing and typesetting i'),
(9, 26, 35, 11, '123123.00', '2021-01-17', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque posuere, justo vel egestas sce'),
(10, 26, 39, 11, '753.00', '2021-01-12', 'Lorem Ipsum is simply dummy text of the printing');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_category_assigned_to_users`
--

CREATE TABLE `expenses_category_assigned_to_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `expenses_category_assigned_to_users`
--

INSERT INTO `expenses_category_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(32, 26, 'Food'),
(33, 26, 'House/Flat'),
(34, 26, 'Transport'),
(35, 26, 'Telecommunication'),
(36, 26, 'Healthcare'),
(37, 26, 'Clothes'),
(38, 26, 'Hygiene'),
(39, 26, 'Kids'),
(40, 26, 'Entertainment'),
(41, 26, 'Trip'),
(42, 26, 'Schooling'),
(43, 26, 'Books'),
(44, 26, 'Savings'),
(45, 26, 'Gold autumn - retiring'),
(46, 26, 'Debts payment'),
(47, 26, 'Donation'),
(48, 26, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `expenses_category_default`
--

CREATE TABLE `expenses_category_default` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `expenses_category_default`
--

INSERT INTO `expenses_category_default` (`id`, `name`) VALUES
(1, 'Food'),
(2, 'House/Flat'),
(3, 'Transport'),
(4, 'Telecommunication'),
(5, 'Healthcare'),
(6, 'Clothes'),
(7, 'Hygiene'),
(8, 'Kids'),
(9, 'Entertainment'),
(10, 'Trip'),
(11, 'Schooling'),
(12, 'Books'),
(13, 'Savings'),
(14, 'Gold autumn - retiring'),
(15, 'Debts payment'),
(16, 'Donation'),
(17, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `incomes_category_assigned_to_user_id` int(11) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `date_of_income` date NOT NULL,
  `income_comment` varchar(100) CHARACTER SET utf8 COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `user_id`, `incomes_category_assigned_to_user_id`, `amount`, `date_of_income`, `income_comment`) VALUES
(3, 26, 55, '50.00', '2021-02-14', 'comment'),
(4, 26, 56, '44.00', '2021-03-13', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been'),
(5, 26, 55, '44.00', '2021-02-05', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque posuere, justo vel egestas sce'),
(6, 26, 56, '5555.00', '2021-02-05', ''),
(7, 26, 54, '4.00', '2021-02-22', '');

-- --------------------------------------------------------

--
-- Table structure for table `incomes_category_assigned_to_users`
--

CREATE TABLE `incomes_category_assigned_to_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `incomes_category_assigned_to_users`
--

INSERT INTO `incomes_category_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(1, 17, 'Payment'),
(2, 17, 'Bank interests'),
(3, 17, 'Selling on Allegro'),
(4, 17, 'Other'),
(22, 20, 'Payment'),
(23, 20, 'Bank interests'),
(24, 20, 'Selling on Allegro'),
(25, 20, 'Other'),
(54, 26, 'Payment'),
(55, 26, 'Bank interests'),
(56, 26, 'Selling on Allegro'),
(57, 26, 'Other'),
(58, 26, 'OLX');

-- --------------------------------------------------------

--
-- Table structure for table `incomes_category_default`
--

CREATE TABLE `incomes_category_default` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `incomes_category_default`
--

INSERT INTO `incomes_category_default` (`id`, `name`) VALUES
(1, 'Payment'),
(2, 'Bank interests'),
(3, 'Selling on Allegro'),
(4, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods_assigned_to_users`
--

CREATE TABLE `payment_methods_assigned_to_users` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `payment_methods_assigned_to_users`
--

INSERT INTO `payment_methods_assigned_to_users` (`id`, `user_id`, `name`) VALUES
(11, 26, 'Card'),
(12, 26, 'Bank transfer'),
(13, 26, 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods_default`
--

CREATE TABLE `payment_methods_default` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Dumping data for table `payment_methods_default`
--

INSERT INTO `payment_methods_default` (`id`, `name`) VALUES
(1, 'Card'),
(2, 'Bank transfer'),
(3, 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(20) COLLATE utf8_polish_ci NOT NULL,
  `pass` text COLLATE utf8_polish_ci NOT NULL,
  `email` text COLLATE utf8_polish_ci NOT NULL,
  `name` text COLLATE utf8_polish_ci DEFAULT NULL,
  `surname` text COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `pass`, `email`, `name`, `surname`) VALUES
(1, 'adam', '$2y$10$MS1NTUR8w7yAtEcaArgyN.qUhzn58y4qAcEXfIVemVapsjnUpksha', 'adam@gmail.com', NULL, NULL),
(11, 'klusek', '$2y$10$QpEEB/1/0A89adtUOPb2U.aM/1FR0W1qzURWIahm.NhBnE7zTcn3S', 'klusek@gm.pl', NULL, NULL),
(12, 'Gladiator', '$2y$10$.2R1PaypagmH6tCoGbIkdOiQY5QBBuPj8I6/Ei/JWWOkOFHwXs5oK', 'gladiator@klus.kl', 'Noximus', 'Maximus'),
(13, 'Shadow', '$2y$10$j1Tb8yGRSTL/dHnSxzHvYOjU5fBoTV4ayybsFo3JOiavBa1P/IpRm', 'diego@oldcamp.gd', 'Diego', ''),
(17, 'mercenary', '$2y$10$.6scxSVRCPIXiAYNrfS4lu9vB7mZmRpSzIDdwMXPrafLAjLewnRLu', 'merc@newcamp.kor', 'Gorn', 'PopCorn'),
(20, 'novice', '$2y$10$HViPAB9QC/3To/FHoxSzXujmeQH0yoeRAUNg/ZjVT46BruZc3a3Uy', 'lester@swampcamp.mv', 'Lester', ''),
(26, 'firemage', '$2y$10$ENkKeNFXMYng4kQbfE/bMuYV8tUOampHmHXY./TjnwTMRFKb4UnBO', 'milten@oldcamp.mv', 'Milten', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_category_assigned_to_users`
--
ALTER TABLE `expenses_category_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_category_default`
--
ALTER TABLE `expenses_category_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes_category_assigned_to_users`
--
ALTER TABLE `incomes_category_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incomes_category_default`
--
ALTER TABLE `incomes_category_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods_assigned_to_users`
--
ALTER TABLE `payment_methods_assigned_to_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods_default`
--
ALTER TABLE `payment_methods_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses_category_assigned_to_users`
--
ALTER TABLE `expenses_category_assigned_to_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `expenses_category_default`
--
ALTER TABLE `expenses_category_default`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `incomes_category_assigned_to_users`
--
ALTER TABLE `incomes_category_assigned_to_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `incomes_category_default`
--
ALTER TABLE `incomes_category_default`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_methods_assigned_to_users`
--
ALTER TABLE `payment_methods_assigned_to_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `payment_methods_default`
--
ALTER TABLE `payment_methods_default`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
