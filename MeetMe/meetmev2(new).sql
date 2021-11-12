-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2021 at 02:13 PM
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
-- Database: `meetmev2`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `BookingID` int(40) NOT NULL,
  `ConvenerID` int(40) NOT NULL,
  `OrganizerID` int(40) DEFAULT NULL,
  `StudentID` int(40) DEFAULT NULL,
  `Booking_date` date NOT NULL,
  `Booking_start` datetime NOT NULL,
  `Booking_end` datetime DEFAULT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Not confirmed',
  `Duration` int(255) DEFAULT NULL,
  `Initial` tinyint(1) NOT NULL DEFAULT 1,
  `PreviousMeetingID` int(40) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL,
  `Auth_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`BookingID`, `ConvenerID`, `OrganizerID`, `StudentID`, `Booking_date`, `Booking_start`, `Booking_end`, `Status`, `Duration`, `Initial`, `PreviousMeetingID`, `Comment`, `Auth_key`) VALUES
(1, 1, NULL, 33753618, '2021-10-28', '2021-10-28 19:06:00', '2021-10-28 19:30:00', 'ended', 24, 1, 0, 'This meeting has ended', 'b917115b0769707b446db32bde223ef9'),
(2, 1, NULL, 12345678, '2021-10-28', '2021-10-28 20:00:00', '2021-10-28 20:20:00', 'ended', 20, 1, 0, 'this booking has ended', '6e45b466b56a432beb7894ac278f108d');

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `ListID` int(40) NOT NULL,
  `UserID` int(40) NOT NULL,
  `ListDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`ListID`, `UserID`, `ListDate`) VALUES
(5, 1, '2021-10-23 11:38:06'),
(6, 1, '2021-10-23 11:39:20'),
(7, 1, '2021-11-02 10:52:44'),
(8, 1, '2021-11-02 10:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` int(40) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `First_name` varchar(30) NOT NULL,
  `Last_name` varchar(30) NOT NULL,
  `Meeting_duration` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `Email`, `Username`, `Password`, `First_name`, `Last_name`, `Meeting_duration`) VALUES
(1, 'johndoe@test.com', 'johndoe', '$2y$10$kgPFEHO/hZLWELnaua1jfu7NVq8hU4gsgHsOD7DFs5SF.NQjJkkAi', 'John', 'Doe', 30),
(2, 'jamesdoe@gmail.com', 'jamesdoe', '$2y$10$X3dwcc5zw3k1UbrjEMPeSOStcFOxnD9194iUQPAm31OTNNaGo1Hp2', 'james', 'doe', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` int(40) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `First_name` varchar(30) NOT NULL,
  `Last_name` varchar(30) NOT NULL,
  `Meeting_duration` int(255) DEFAULT NULL,
  `Appcount` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `Email`, `First_name`, `Last_name`, `Meeting_duration`, `Appcount`) VALUES
(12345678, 'marydoe@gmail.com', 'mary', 'doe', NULL, NULL),
(33753618, 'seowweicheng@gmail.com', 'WeiCheng', 'Seow', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `studentlist`
--

CREATE TABLE `studentlist` (
  `StudentID` int(40) NOT NULL,
  `ListID` int(40) NOT NULL,
  `Auth_key` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `studentlist`
--

INSERT INTO `studentlist` (`StudentID`, `ListID`, `Auth_key`) VALUES
(12345678, 5, '0fce93291cb745f58099fcae27e960bc'),
(33753618, 8, '20c580cda580a915c656a802d71cc81f'),
(33753618, 6, '5b1486a7b32ffbf7c5d7a7e730ffcf77'),
(33753618, 5, '5e9d739fcb689b92900e977734e77d47'),
(12345678, 6, '677c8e9b39cda496d5233c3ccd7f105a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD UNIQUE KEY `uniqueBooking` (`Auth_key`),
  ADD KEY `BookingConvenerID` (`ConvenerID`),
  ADD KEY `BookingOrganizerID` (`OrganizerID`),
  ADD KEY `BookingStudentID` (`StudentID`),
  ADD KEY `bookingAuth` (`Auth_key`);

--
-- Indexes for table `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`ListID`),
  ADD KEY `StaffListFK` (`UserID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `studentlist`
--
ALTER TABLE `studentlist`
  ADD PRIMARY KEY (`StudentID`,`ListID`),
  ADD UNIQUE KEY `studentAuth` (`Auth_key`),
  ADD KEY `studentlist_listidfk` (`ListID`),
  ADD KEY `Auth_key` (`Auth_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BookingID` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `list`
--
ALTER TABLE `list`
  MODIFY `ListID` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `BookingConvenerID` FOREIGN KEY (`ConvenerID`) REFERENCES `staff` (`StaffID`),
  ADD CONSTRAINT `BookingOrganizerID` FOREIGN KEY (`OrganizerID`) REFERENCES `staff` (`StaffID`),
  ADD CONSTRAINT `BookingStudentID` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`);

--
-- Constraints for table `list`
--
ALTER TABLE `list`
  ADD CONSTRAINT `StaffListFK` FOREIGN KEY (`UserID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `studentlist`
--
ALTER TABLE `studentlist`
  ADD CONSTRAINT `studentlist_listidfk` FOREIGN KEY (`ListID`) REFERENCES `list` (`ListID`),
  ADD CONSTRAINT `studentlist_studentid` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
