-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2021 at 02:03 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.11

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
  `ConvenerID` varchar(8) NOT NULL,
  `OrganizerID` varchar(8) DEFAULT NULL,
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
(1, 'A1234567', NULL, 12312312, '2021-11-27', '2021-11-27 15:00:00', '2021-11-27 16:00:00', 'Cancelled', NULL, 1, NULL, NULL, 'ddd39c077d891699a280aa275b2f1cda'),
(6, 'A1234567', NULL, NULL, '2021-11-29', '2021-11-29 19:00:00', '2021-11-29 20:00:00', 'Not confirmed', NULL, 1, NULL, NULL, '949f21c630c65c25e19c75bfdb86dcb0'),
(7, 'A1234567', NULL, NULL, '2021-12-29', '2021-12-29 13:00:00', '2021-12-29 14:00:00', 'Not confirmed', NULL, 1, NULL, NULL, '47c557ce108f2391b16f0c317e071ffd'),
(8, 'A1234567', NULL, NULL, '2021-12-07', '2021-12-07 15:00:00', '2021-12-07 16:00:00', 'Cancelled', 0, 1, 0, '', 'c353a7df8d8891a61a23380de8084d30'),
(9, 'A1234567', NULL, NULL, '2021-12-08', '2021-12-08 13:00:00', '2021-12-09 02:00:00', 'Cancelled', 0, 1, 0, '', '22b1f44de7e6a17ba5c17d84f3469cde'),
(10, 'A1234567', NULL, 31244321, '2021-12-22', '2021-12-22 13:15:00', '2021-12-22 13:45:00', 'Cancelled', 0, 1, 0, '', 'a7b094aa665cff40a8e2b4ffecb412b5'),
(11, 'A1234567', NULL, 38023453, '2021-11-25', '2021-11-25 18:50:00', '2021-11-25 19:50:00', 'Confirmed', NULL, 1, NULL, NULL, '34adfg4123gthyth567345223fftrreq'),
(12, 'A1234567', NULL, 38023453, '2021-11-27', '2021-11-27 20:00:00', '2021-11-27 21:15:00', 'Confirmed', 0, 0, 11, '', '18be590df9ca257b489c037db351d56f'),
(13, 'A1234567', NULL, 31244321, '2021-10-20', '2021-10-28 19:06:00', '2021-10-28 19:20:00', 'Ended', 14, 1, 0, 'This booking has ended on 28 Oct.', '5601eb91dc551c2425c90991b0f1a8ee'),
(17, 'A1234567', NULL, NULL, '2021-12-12', '2021-12-12 13:00:00', '2021-12-12 14:00:00', 'Not confirmed', NULL, 1, NULL, NULL, '1949106a349a613864335c196a9cdc83'),
(21, 'A1234567', NULL, NULL, '2021-12-14', '2021-12-14 13:00:00', '2021-12-14 14:00:00', 'Not confirmed', NULL, 1, NULL, NULL, '6ef5c7173cf86d791b251958746104cb'),
(22, 'B1234567', NULL, NULL, '2021-11-30', '2021-11-30 13:00:00', '2021-11-30 14:00:00', 'Not confirmed', NULL, 1, NULL, NULL, '859a4380b20b70dfec46298362ff04b8'),
(23, 'B1234567', NULL, 38023453, '2021-12-12', '2021-12-12 13:00:00', '2021-12-12 14:00:00', 'Cancelled', NULL, 1, NULL, NULL, '397e249eb542173390dd30fb9ce75f5b'),
(24, 'B1234567', NULL, NULL, '2021-12-14', '2021-12-14 13:00:00', '2021-12-14 14:00:00', 'Cancelled', 0, 1, 0, 'Cancelled due to personal reasons', '9dc005099890f5f75ab6ea586381a3fa'),
(25, 'A1234567', NULL, NULL, '2021-12-08', '2021-12-08 13:00:00', '2021-12-08 14:00:00', 'Not confirmed', NULL, 1, NULL, NULL, 'bef32238d1fb0f6238d6269564c88571');

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE `list` (
  `ListID` int(40) NOT NULL,
  `StaffID` varchar(8) NOT NULL,
  `ListDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `list`
--

INSERT INTO `list` (`ListID`, `StaffID`, `ListDate`) VALUES
(1, 'A1234567', '2021-11-25 15:07:57'),
(9, 'B1234567', '2021-11-27 11:12:29'),
(10, 'A1234567', '2021-11-27 11:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `StaffID` varchar(8) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `First_name` varchar(30) NOT NULL,
  `Last_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`StaffID`, `Email`, `Username`, `Password`, `First_name`, `Last_name`) VALUES
('A1234567', 'meetmestaff1@gmail.com', 'johndoe', '$2y$10$UOJ5czWLhoEtoZ6CxSFqne6eEe6KbqZFV/jsGwbWCpJnCia3jP1jC', 'John', 'Doe'),
('B1234567', 'meetmetstaff2@outlook.com', 'bliu', '$2y$10$E0oB.DLob8ioFeo/itLn3OskdtwcO3ADmkleECAmtWFbb8EdylClG', 'Betty', 'Liu');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` int(40) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `First_name` varchar(30) NOT NULL,
  `Last_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `Email`, `First_name`, `Last_name`) VALUES
(12312312, 'meetmestudent1@gmail.com', 'Angelus', 'Holm'),
(31244321, 'meetmestudent2@gmail.com', 'James', 'Gwin'),
(38023453, 'MeetMev2Dummy@outlook.com', 'Caitlin', 'Westcott');

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
(38023453, 10, '18f68ed4cecf798df4224b1f00434033'),
(31244321, 9, '2cf015d0bd0512a53093ba1db6d072b0'),
(31244321, 10, '8061a909cf57dba08e2d4ed6dd28e0ec'),
(12312312, 9, '9769c3f3d10819d811cac2a83abc3c35'),
(12312312, 10, '9a51205ccfd7600c84edcc25a0c39ff1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`BookingID`),
  ADD UNIQUE KEY `uniqueBooking` (`Auth_key`),
  ADD KEY `BookingStudentID` (`StudentID`),
  ADD KEY `bookingAuth` (`Auth_key`),
  ADD KEY `BookingConvenerID` (`ConvenerID`),
  ADD KEY `BookingOrganizerID` (`OrganizerID`);

--
-- Indexes for table `list`
--
ALTER TABLE `list`
  ADD PRIMARY KEY (`ListID`),
  ADD KEY `StaffFK` (`StaffID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`StaffID`) USING BTREE,
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
  MODIFY `BookingID` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `list`
--
ALTER TABLE `list`
  MODIFY `ListID` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  ADD CONSTRAINT `StaffFK` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`);

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
