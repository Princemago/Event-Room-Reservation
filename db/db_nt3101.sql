-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2023 at 12:57 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nt3101`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`department_id`, `department_name`) VALUES
(1, 'CICS'),
(2, 'CABE'),
(3, 'CIT'),
(4, 'CAS');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `teacher_id`, `student_id`, `venue_id`, `department_id`, `start_time`, `end_time`, `status`) VALUES
(14, 1, 0, 1, 1, '2023-11-22 09:37:00', NULL, 'disapproved'),
(15, 1, 0, 1, 1, '2023-11-22 09:37:00', NULL, 'disapproved'),
(16, 0, 1, 1, 1, '2023-11-22 09:41:00', NULL, 'approved'),
(17, NULL, 1, 1, 1, '2023-11-22 09:41:00', NULL, 'approved'),
(18, NULL, 1, 1, 1, '2023-11-22 09:54:00', NULL, 'disapproved');

--
-- Triggers `reservations`
--
DELIMITER $$
CREATE TRIGGER `before_insert_reservation` BEFORE INSERT ON `reservations` FOR EACH ROW BEGIN
    -- Set the status as pending for new reservations
    SET NEW.status = 'pending';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbempinfo`
--

CREATE TABLE `tbempinfo` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbempinfo`
--

INSERT INTO `tbempinfo` (`teacher_id`, `teacher_name`, `email`, `department_id`, `password`) VALUES
(1, 'John Smith', '21-44232@g-batsate-u.edu.ph', 1, 'password_hash1'),
(2, 'Alice Johnson', '21-47293@g-batsate-u.edu.ph', 2, 'password_hash2'),
(3, 'Bob Anderson', '21-48979@g-batsate-u.edu.ph', 3, 'password_hash3'),
(4, 'Emily Davis', '21-65643@g-batsate-u.edu.ph', 1, 'password_hash4');

-- --------------------------------------------------------

--
-- Table structure for table `tbstudinfo`
--

CREATE TABLE `tbstudinfo` (
  `student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbstudinfo`
--

INSERT INTO `tbstudinfo` (`student_id`, `student_name`, `email`, `department_id`, `password`) VALUES
(1, 'Prince Mago', '21-30079@g.batstate-u.edu.ph', 1, 'password_hash5'),
(2, 'Jazpher Bartido', '21-32343@g.batstate-u.edu.ph', 2, 'password_hash6'),
(3, 'John Paul Samonte', '21-12212@g.batstate-u.edu.ph', 3, 'password_hash7'),
(5, 'Daniel Lat', '21-22123@g.batstate-u.edu.ph', 1, 'password_hash8');

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `ID` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`ID`, `full_name`, `email`, `password`) VALUES
(4, 'prince mago', '21-30079@g.batstate-u.edu.ph', '$2y$10$uROFe/Oceu.7n10V5pTkguQpBisPYjkY6uhhLh80ZjxCOGYM7QbfK'),
(7, 'prince mago', 'prince.mago123@gmail.com', '$2y$10$w0PJ0l7L/CLD6.oKqpUJfeOmp1cgCnyTUnNBQ7SOG3Bck5zlvx6KK'),
(8, 'prince mago', 'prince.mago@yahoo.com', '$2y$10$ytLKCr6OQ4b7kqW6nhDRG.8VkWfes4dXP0tkFvv1zFgKnYRd3StDy'),
(9, 'prince mago', 'prince.mago52@gmail.com', '$2y$10$iPJINQnPk1rOOsMVHDQFM.gOT5euXYUzroIkinM64GmOAyQiCCIGK');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `venue_id` int(11) NOT NULL,
  `venue_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`venue_id`, `venue_name`) VALUES
(1, '5th floor HEB room'),
(2, 'Field'),
(3, 'Gym'),
(4, 'Multi Media Room');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tbempinfo`
--
ALTER TABLE `tbempinfo`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tbstudinfo`
--
ALTER TABLE `tbstudinfo`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbempinfo`
--
ALTER TABLE `tbempinfo`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbstudinfo`
--
ALTER TABLE `tbstudinfo`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `venue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbempinfo`
--
ALTER TABLE `tbempinfo`
  ADD CONSTRAINT `tbempinfo_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);

--
-- Constraints for table `tbstudinfo`
--
ALTER TABLE `tbstudinfo`
  ADD CONSTRAINT `tbstudinfo_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
