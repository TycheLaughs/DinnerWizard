-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2015 at 01:03 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dinnerwizard`
--

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tag_map`
--

CREATE TABLE IF NOT EXISTS `ingredient_tag_map` (
`mapID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `tagID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Details what any given ingredient is tagged with' AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ingredient_tag_map`
--

INSERT INTO `ingredient_tag_map` (`mapID`, `ingredientID`, `tagID`) VALUES
(1, 1, 4),
(2, 1, 5),
(3, 2, 1),
(4, 3, 3),
(5, 4, 5),
(6, 5, NULL),
(7, 6, 4),
(8, 7, 2),
(9, 8, 4),
(10, 9, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
 ADD PRIMARY KEY (`mapID`), ADD KEY `ingredientID` (`ingredientID`), ADD KEY `categoryID` (`tagID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
ADD CONSTRAINT `ingredient_tag_map_ibfk_1` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`ingredientID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ingredient_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `ingredient_tags` (`tagID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
