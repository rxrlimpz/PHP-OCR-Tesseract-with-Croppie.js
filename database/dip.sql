-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2024 at 10:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dip`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `ardoctbl`
-- (See below for the actual view)
--
CREATE TABLE `ardoctbl` (
`id` int(10)
,`DocName` varchar(50)
,`College` varchar(6)
,`Course` varchar(50)
,`Subject` varchar(50)
,`year_level` varchar(8)
,`DocIMG` longblob
,`SchoolYear` varchar(9)
,`Semester` varchar(7)
,`classType` varchar(10)
,`added_date` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE `college` (
  `CollegeID` int(2) NOT NULL,
  `CollegeName` varchar(6) NOT NULL,
  `Description` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`CollegeID`, `CollegeName`, `Description`) VALUES
(1, 'COE', 'College of Engineering'),
(3, 'CIC', 'College of Information and Computing'),
(5, 'CED', 'College of Education');

-- --------------------------------------------------------

--
-- Stand-in structure for view `coll_course`
-- (See below for the actual view)
--
CREATE TABLE `coll_course` (
`CollegeName` varchar(6)
,`CourseName` varchar(50)
,`CollID` int(2)
);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `CourseID` int(10) NOT NULL,
  `CourseName` varchar(50) NOT NULL,
  `CollID` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`CourseID`, `CourseName`, `CollID`) VALUES
(2, 'BSCS', 3),
(3, 'BLIS', 3),
(4, 'BSME', 1),
(5, 'BSCE', 1),
(8, 'BTVTED', 5),
(9, 'BSIE', 1),
(13, 'BECEd', 5),
(16, 'BSIT-BTM', 3),
(80, 'BSIT-IS', 3),
(89, 'MAED', 5);

-- --------------------------------------------------------

--
-- Table structure for table `docstbl`
--

CREATE TABLE `docstbl` (
  `id` int(10) NOT NULL,
  `DocName` varchar(50) NOT NULL,
  `College` varchar(6) NOT NULL,
  `Course` varchar(50) NOT NULL,
  `Subject` varchar(50) NOT NULL,
  `year_level` int(1) NOT NULL,
  `DocIMG` longblob NOT NULL,
  `originalFileType` varchar(255) DEFAULT NULL,
  `SchoolYear` varchar(9) NOT NULL COMMENT 'School Year of Document',
  `Semester` int(1) NOT NULL,
  `classType` int(1) DEFAULT NULL,
  `page_num` int(3) DEFAULT NULL,
  `added_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `srecordstbl`
-- (See below for the actual view)
--
CREATE TABLE `srecordstbl` (
`id` int(11)
,`FullName` varchar(112)
,`College` varchar(6)
,`Course` varchar(50)
,`Subject` varchar(50)
,`year_level` varchar(8)
,`Semester` varchar(7)
,`classType` varchar(10)
,`SchoolYear` varchar(9)
,`DocIMG` longblob
,`added_date` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `studenttbl`
--

CREATE TABLE `studenttbl` (
  `id` int(10) NOT NULL,
  `SFName` varchar(50) NOT NULL,
  `SLName` varchar(50) NOT NULL,
  `SMName` varchar(2) NOT NULL,
  `S_suffix` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taggingtbl`
--

CREATE TABLE `taggingtbl` (
  `id` int(11) NOT NULL,
  `studentID` int(11) NOT NULL,
  `docsID` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure for view `ardoctbl`
--
DROP TABLE IF EXISTS `ardoctbl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ardoctbl`  AS SELECT `docstbl`.`id` AS `id`, `docstbl`.`DocName` AS `DocName`, `docstbl`.`College` AS `College`, `docstbl`.`Course` AS `Course`, `docstbl`.`Subject` AS `Subject`, CASE WHEN `docstbl`.`year_level` = 1 THEN '1st' WHEN `docstbl`.`year_level` = 2 THEN '2nd' WHEN `docstbl`.`year_level` = 3 THEN '3rd' WHEN `docstbl`.`year_level` = 4 THEN '4th' WHEN `docstbl`.`year_level` = 5 THEN '5th' WHEN `docstbl`.`year_level` = 6 THEN '6th' WHEN `docstbl`.`year_level` = 7 THEN 'Masteral' ELSE cast(`docstbl`.`year_level` as char(8) charset utf8mb4) END AS `year_level`, `docstbl`.`DocIMG` AS `DocIMG`, `docstbl`.`SchoolYear` AS `SchoolYear`, CASE WHEN `docstbl`.`Semester` = 1 THEN '1st' WHEN `docstbl`.`Semester` = 2 THEN '2nd' WHEN `docstbl`.`Semester` = 0 THEN 'Off-Sem' ELSE cast(`docstbl`.`Semester` as char(7) charset utf8mb4) END AS `Semester`, CASE WHEN `docstbl`.`classType` = 0 THEN '' WHEN `docstbl`.`classType` = 1 THEN 'Lecture' WHEN `docstbl`.`classType` = 2 THEN 'Laboratory' ELSE cast(`docstbl`.`classType` as char(7) charset utf8mb4) END AS `classType`, `docstbl`.`added_date` AS `added_date` FROM `docstbl` ;

-- --------------------------------------------------------

--
-- Structure for view `coll_course`
--
DROP TABLE IF EXISTS `coll_course`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `coll_course`  AS SELECT `col`.`CollegeName` AS `CollegeName`, `crs`.`CourseName` AS `CourseName`, `col`.`CollegeID` AS `CollID` FROM (`college` `col` join `course` `crs` on(`col`.`CollegeID` = `crs`.`CollID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `srecordstbl`
--
DROP TABLE IF EXISTS `srecordstbl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `srecordstbl`  AS SELECT `tt`.`id` AS `id`, concat(if(`st`.`SLName` is not null and `st`.`SLName` <> '',`st`.`SLName`,''),if(`st`.`SLName` is not null and `st`.`SLName` <> '',', ',''),if(`st`.`SLName` is not null and `st`.`SLName` <> '',concat(' ',`st`.`SFName`),`st`.`SFName`),if(`st`.`SMName` is not null and `st`.`SMName` <> '',concat(' ',`st`.`SMName`),''),if(`st`.`S_suffix` is not null and `st`.`S_suffix` <> '',concat(' ',`st`.`S_suffix`),'')) AS `FullName`, `dt`.`College` AS `College`, `dt`.`Course` AS `Course`, `dt`.`Subject` AS `Subject`, CASE WHEN `dt`.`year_level` = 1 THEN '1st' WHEN `dt`.`year_level` = 2 THEN '2nd' WHEN `dt`.`year_level` = 3 THEN '3rd' WHEN `dt`.`year_level` = 4 THEN '4th' WHEN `dt`.`year_level` = 5 THEN '5th' WHEN `dt`.`year_level` = 6 THEN '6th' WHEN `dt`.`year_level` = 7 THEN 'Masteral' ELSE cast(`dt`.`year_level` as char(8) charset utf8mb4) END AS `year_level`, CASE WHEN `dt`.`Semester` = 1 THEN '1st' WHEN `dt`.`Semester` = 2 THEN '2nd' WHEN `dt`.`Semester` = 0 THEN 'Off-Sem' ELSE cast(`dt`.`Semester` as char(7) charset utf8mb4) END AS `Semester`, CASE WHEN `dt`.`classType` = 0 THEN '' WHEN `dt`.`classType` = 1 THEN 'Lecture' WHEN `dt`.`classType` = 2 THEN 'Laboratory' ELSE cast(`dt`.`classType` as char(10) charset utf8mb4) END AS `classType`, `dt`.`SchoolYear` AS `SchoolYear`, `dt`.`DocIMG` AS `DocIMG`, `dt`.`added_date` AS `added_date` FROM ((`taggingtbl` `tt` join `studenttbl` `st` on(`tt`.`studentID` = `st`.`id`)) join `docstbl` `dt` on(`tt`.`docsID` = `dt`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `college`
--
ALTER TABLE `college`
  ADD PRIMARY KEY (`CollegeID`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`CourseID`),
  ADD KEY `CollID` (`CollID`);

--
-- Indexes for table `docstbl`
--
ALTER TABLE `docstbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studenttbl`
--
ALTER TABLE `studenttbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taggingtbl`
--
ALTER TABLE `taggingtbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `docstbl` (`docsID`),
  ADD KEY `studenttbl` (`studentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `college`
--
ALTER TABLE `college`
  MODIFY `CollegeID` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `CourseID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `docstbl`
--
ALTER TABLE `docstbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=282;

--
-- AUTO_INCREMENT for table `studenttbl`
--
ALTER TABLE `studenttbl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=731;

--
-- AUTO_INCREMENT for table `taggingtbl`
--
ALTER TABLE `taggingtbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`CollID`) REFERENCES `college` (`CollegeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `taggingtbl`
--
ALTER TABLE `taggingtbl`
  ADD CONSTRAINT `docstbl` FOREIGN KEY (`docsID`) REFERENCES `docstbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studenttbl` FOREIGN KEY (`studentID`) REFERENCES `studenttbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
