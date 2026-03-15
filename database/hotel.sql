-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 07:58 AM
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
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(20) NOT NULL,
  `cus_name` varchar(100) NOT NULL,
  `cus_phone` varchar(20) NOT NULL,
  `cus_email` varchar(150) NOT NULL,
  `passport_no` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `booking_type` enum('Room','Table') NOT NULL,
  `rooms` int(11) NOT NULL DEFAULT 0,
  `adults` int(20) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `numGuest` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` enum('Cash','Card','Online') NOT NULL,
  `pay_status` enum('Paid','Unpaid','Partial') NOT NULL DEFAULT 'Unpaid',
  `special_request` text DEFAULT NULL,
  `booking_status` enum('new','Accepted','Rejected') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `cus_name`, `cus_phone`, `cus_email`, `passport_no`, `address`, `booking_type`, `rooms`, `adults`, `room_type`, `numGuest`, `check_in`, `check_out`, `amount`, `payment_method`, `pay_status`, `special_request`, `booking_status`, `created_at`, `updated_at`) VALUES
(2, 'Munsif Khan', '0313-9578642', 'munsif23@gmail.com', 'VDE23549E', 'landi Shah Katlang', 'Room', 1, 1, 'Single', 1, '2026-02-16', '2026-03-06', 90000.00, 'Card', 'Paid', 'Accepte please', 'Accepted', '2026-02-15 07:04:07', '2026-03-04 11:35:40'),
(3, 'Uzair', '03419523248', 'uzair80@gmail.com', 'VDE235468CR', 'Mardan', 'Room', 2, 4, 'Family', 4, '2026-02-18', '2026-02-25', 10000.00, 'Online', 'Partial', '', 'Rejected', '2026-02-15 07:10:36', '2026-03-04 11:36:54'),
(4, 'Ali Khan', '03419523933', 'ali@gmail.com', 'GW254038M', 'Mata spen khak katlang', 'Room', 1, 1, '', 1, '2026-02-23', '2026-02-27', 15000.00, 'Cash', 'Paid', 'ok', 'Accepted', '2026-02-23 06:09:18', '2026-03-04 11:34:22'),
(5, 'Aslam Khan', '03457826478', 'aslam23@gmail.com', 'VDE23549E', 'Mardan', 'Room', 1, 1, '1', 1, '2026-02-23', '2026-02-28', 25000.00, 'Card', 'Partial', 'ok', 'Rejected', '2026-02-23 07:23:57', '2026-03-03 06:44:54'),
(6, 'Zubair Khan', '03419523933', 'zubair@gmail.com', 'TR354488B', 'Mata spen khak katlang', 'Room', 1, 1, '1', 1, '2026-03-04', '2026-03-06', 10000.00, 'Online', 'Paid', 'Testing', 'Accepted', '2026-03-03 06:43:56', '2026-03-03 06:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contact` varchar(120) NOT NULL,
  `expense_type` enum('Salary','Maintenance','Utilities','other') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `name`, `email`, `contact`, `expense_type`, `amount`, `expense_date`, `description`, `created_at`) VALUES
(1, 'Zeeshan', 'zameendotcom@gmail.com', '2147483647', 'Salary', 65000.00, '2026-03-02', 'March Salary Recived', '2026-03-03 07:53:13');

-- --------------------------------------------------------

--
-- Table structure for table `roomtables`
--

CREATE TABLE `roomtables` (
  `id` int(11) NOT NULL,
  `item_num` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `item_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `item_category` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `createdby` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomtables`
--

INSERT INTO `roomtables` (`id`, `item_num`, `item_type`, `item_category`, `price`, `createdby`, `created`) VALUES
(1, 'R-101', 'Room', 'Single', 5000.00, 'accepted', '2026-02-15 10:52:43'),
(2, 'R-102', 'Room', 'Double', 0.00, 'accepted', '2026-02-15 10:52:43'),
(3, 'R-103', 'Room', 'VIP', 8000.00, 'rejected', '2026-02-15 10:52:43'),
(4, 'R-104', 'Room', 'Family', 0.00, 'pending', '2026-02-15 10:52:43'),
(5, 'R-105', 'Room', 'Double', 0.00, 'new', '2026-02-15 10:52:43'),
(6, 'T-201', 'Table', 'VIP', 0.00, 'accepted', '2026-02-15 10:52:43'),
(7, 'T-202', 'Table', 'Family', 0.00, 'new', '2026-02-15 10:52:43'),
(8, 'T-203', 'Table', 'Double', 0.00, 'rejected', '2026-02-15 10:52:43'),
(9, 'T-203', 'Table', 'Family', 0.00, 'pending', '2026-02-15 10:52:43'),
(10, 'T-205', 'Table', 'Double', 0.00, 'accepted', '2026-02-15 10:52:43'),
(11, 'R-106', 'Room', 'Double', 0.00, 'new', '2026-02-15 10:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `userName` varchar(12) NOT NULL,
  `userEmail` varchar(20) NOT NULL,
  `uPhone` int(15) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `role` varchar(10) NOT NULL,
  `password` varchar(12) NOT NULL,
  `address` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `userName`, `userEmail`, `uPhone`, `date`, `role`, `password`, `address`) VALUES
(1, 'Munsif Khan', 'munsif23@gmail.com', 2147483647, '2025-11-27 20:09:54', 'Admin', 'khan1122', 'landi Shah'),
(2, 'Iqbal Khan', 'iqbalk45@gmail.com', 2147483647, '2025-11-27 20:11:37', 'Accountant', '12345aa', 'Battagram'),
(3, 'Zeeshan', 'zeeshan657@gmail.com', 2147483647, '2025-11-27 20:15:18', 'Director', '123khan', 'Islamabad');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtables`
--
ALTER TABLE `roomtables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roomtables`
--
ALTER TABLE `roomtables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
