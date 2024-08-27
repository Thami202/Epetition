-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2022 at 11:43 AM
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
-- Table structure for table `tbl_petition`
--

CREATE TABLE `tbl_petition` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `submissiontype` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `rsa_id` int(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phonenumber` varchar(20) NOT NULL,
  `receiveDate` datetime NOT NULL DEFAULT current_timestamp(),
  `feedbackDate` datetime NOT NULL DEFAULT current_timestamp(),
  `petitionDate` datetime NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `referencenoparliament` varchar(255) NOT NULL,
  `referencenopublic` varchar(255) NOT NULL,
  `comment2` varchar(255) NOT NULL,
  `comment3` varchar(255) NOT NULL,
  `createddate` datetime NOT NULL DEFAULT current_timestamp(),
  `updateddate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_petition`
--

INSERT INTO `tbl_petition` (`id`, `userid`, `submissiontype`, `status`, `name`, `surname`, `province`, `rsa_id`, `email`, `phonenumber`, `receiveDate`, `feedbackDate`, `petitionDate`, `title`, `description`, `referencenoparliament`, `referencenopublic`, `comment2`, `comment3`, `createddate`, `updateddate`) VALUES
(1, 1, 'Online', 'pending', 'Rodwell', 'Shivambu', 'Limpopo', 32, 'rodwellshibambu@gmail.com', '0794054815', '2022-11-23 14:40:54', '2022-11-23 14:40:54', '2022-11-23 14:40:54', 'New Election', 'New Election went well', '', '', 'New Election went well', 'New Election went well', '2022-11-23 14:40:54', '2022-11-23 14:40:54'),
(2, 0, '', '', 'Rodwell', 'Shivambu', 'Western Cape', 2022000880, 'rshibambu@parliament.gov.za', '0794054815', '2022-11-23 14:46:43', '2022-11-23 14:46:43', '2022-11-23 14:46:43', 'New Election', 'The election never went well', '', '', '', '', '2022-11-23 14:46:43', '2022-11-23 14:46:43'),
(3, 0, '', '', 'Rodwell', 'Shivambu', 'Western Cape', 2022000880, 'rshibambu@parliament.gov.za', '0794054815', '2022-11-23 14:46:59', '2022-11-23 14:46:59', '2022-11-23 14:46:59', 'New Election', 'The election never went well', '', '', '', '', '2022-11-23 14:46:59', '2022-11-23 14:46:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_petition`
--
ALTER TABLE `tbl_petition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_petition`
--
ALTER TABLE `tbl_petition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
