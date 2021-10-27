-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Oct 18, 2021 at 04:41 PM
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
  `OrganizerID` int(40) NOT NULL,
  `StudentID` int(40) NOT NULL,
  `CalendarID` int(40) NOT NULL,
  `Booking_date` date NOT NULL,
  `Booking_start` datetime NOT NULL,
  `Booking_end` datetime NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Not confirmed',
  `Duration` time DEFAULT '00:00:00',
  `Initial` tinyint(1) NOT NULL DEFAULT 1,
  `PreviousMeetingID` int(40) DEFAULT NULL,
  `Comment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `CalendarID` int(40) NOT NULL,
  `ConvenerID` int(40) NOT NULL,
  `OrganizerID` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `ListID` int(40) NOT NULL,
  `UserID` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- --------------------------------------------------------

--
-- Table structure for table `studentlist`
--

CREATE TABLE `studentlist` (
  `ListID` int(40) NOT NULL,
  `StudentID` int(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD KEY `calendarIDFK` (`CalendarID`),
  ADD KEY `BookingConvenerID` (`ConvenerID`),
  ADD KEY `BookingOrganizerID` (`OrganizerID`),
  ADD KEY `BookingStudentID` (`StudentID`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`CalendarID`),
  ADD KEY `ConvenerIDFK` (`ConvenerID`),
  ADD KEY `OrganizerIDFK` (`OrganizerID`);

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
  ADD PRIMARY KEY (`ListID`,`StudentID`),
  ADD KEY `StudentIDFK` (`StudentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `BookingID` int(40) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendar`
--
ALTER TABLE `calendar`
  MODIFY `CalendarID` int(40) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `list`
--
ALTER TABLE `list`
  MODIFY `ListID` int(40) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `StaffID` int(40) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `BookingConvenerID` FOREIGN KEY (`ConvenerID`) REFERENCES `staff` (`StaffID`),
  ADD CONSTRAINT `BookingOrganizerID` FOREIGN KEY (`OrganizerID`) REFERENCES `staff` (`StaffID`),
  ADD CONSTRAINT `BookingStudentID` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentiD`),
  ADD CONSTRAINT `calendarIDFK` FOREIGN KEY (`CalendarID`) REFERENCES `calendar` (`CalendarID`);

--
-- Constraints for table `calendar`
--
ALTER TABLE `calendar`
  ADD CONSTRAINT `ConvenerIDFK` FOREIGN KEY (`ConvenerID`) REFERENCES `staff` (`StaffID`),
  ADD CONSTRAINT `OrganizerIDFK` FOREIGN KEY (`OrganizerID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `list`
--
ALTER TABLE `list`
  ADD CONSTRAINT `StaffListFK` FOREIGN KEY (`UserID`) REFERENCES `staff` (`StaffID`);

--
-- Constraints for table `studentlist`
--
ALTER TABLE `studentlist`
  ADD CONSTRAINT `ListIDFK` FOREIGN KEY (`ListID`) REFERENCES `list` (`ListID`),
  ADD CONSTRAINT `StudentIDFK` FOREIGN KEY (`StudentID`) REFERENCES `student` (`StudentiD`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
