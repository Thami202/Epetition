-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2022 at 11:44 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epetition`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_petitionaudit`
--

CREATE TABLE `tbl_petitionaudit` (
  `id` int(11) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `submissiontype` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `rsa_id` int(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `receivedDate` datetime NOT NULL DEFAULT current_timestamp(),
  `feedbackDate` datetime NOT NULL DEFAULT current_timestamp(),
  `petitionDate` datetime NOT NULL DEFAULT current_timestamp(),
  `description` varchar(255) NOT NULL,
  `referencenoparliament` varchar(255) NOT NULL,
  `referencenopublic` varchar(255) NOT NULL,
  `comment2` varchar(255) NOT NULL,
  `comment3` varchar(255) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT current_timestamp(),
  `updateddate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_petitionaudit`
--
ALTER TABLE `tbl_petitionaudit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_petitionaudit`
--
ALTER TABLE `tbl_petitionaudit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
