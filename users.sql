-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 03, 2025 at 01:03 AM
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
-- Database: `ilp`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `student_id` int(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `con_password` varchar(255) NOT NULL,
  `profile_picture` blob NOT NULL,
  `birth_date` date DEFAULT NULL,
  `phone_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `religion` varchar(20) NOT NULL,
  `ethnicity` varchar(20) NOT NULL,
  `father_name` varchar(50) NOT NULL,
  `father_id_number` int(12) NOT NULL,
  `father_phone` int(12) NOT NULL,
  `father_address` text NOT NULL,
  `father_occupation` varchar(50) NOT NULL,
  `family_income` decimal(10,2) NOT NULL,
  `mother_name` varchar(50) NOT NULL,
  `mother_id_number` int(12) NOT NULL,
  `mother_phone` int(12) NOT NULL,
  `mother_address` text NOT NULL,
  `mother_occupation` varchar(50) NOT NULL,
  `guardian_name` varchar(50) NOT NULL,
  `guardian_id_number` int(12) NOT NULL,
  `guardian_phone` int(12) NOT NULL,
  `guardian_address` text NOT NULL,
  `guardian_occupation` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `student_id`, `email`, `password`, `con_password`, `profile_picture`, `birth_date`, `phone_number`, `address`, `religion`, `ethnicity`, `father_name`, `father_id_number`, `father_phone`, `father_address`, `father_occupation`, `family_income`, `mother_name`, `mother_id_number`, `mother_phone`, `mother_address`, `mother_occupation`, `guardian_name`, `guardian_id_number`, `guardian_phone`, `guardian_address`, `guardian_occupation`) VALUES
(2, 'Ana', 'Huang', 0, 'anahuang@gmail.com', '$2y$10$P3HqQLZPR8i/wOIbtOdiqO.AKNAiziJsrQadg.hVEinRkyl5ET62.', '', '', NULL, '', '', '', '', '', 0, 0, '', '', 0.00, '', 0, 0, '', '', '', 0, 0, '', ''),
(3, 'Caroline', 'Nelson', 0, 'carol34@jtm.gov.my', '$2y$10$hEFOLM5XekTVWWapvao4wOpWjNuuoPsHxgRtjUrye/WY7J7h9hmHK', '', '', NULL, '', '', '', '', '', 0, 0, '', '', 0.00, '', 0, 0, '', '', '', 0, 0, '', ''),
(4, 'Shuzane', 'Sandom', 0, 'shuzanesandom3@gmail.com', '$2y$10$whF2IxiLJdJPnfhfxCJb0eRrsbn7TmiuWgwYEEcz4X/h.8pfi00Cq', '', '', NULL, '', '', '', '', '', 0, 0, '', '', 0.00, '', 0, 0, '', '', '', 0, 0, '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
