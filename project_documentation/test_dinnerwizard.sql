-- phpMyAdmin SQL Dump
-- version 4.3.10
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2015 at 02:21 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

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
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`) VALUES
(1, 'oven'),
(2, 'mixing bowl'),
(3, 'chopping knife'),
(4, 'blender'),
(5, 'food processor'),
(6, 'microwave'),
(7, 'double boiler'),
(8, 'cookie sheet'),
(9, 'frying pan'),
(10, 'wok'),
(11, 'skillet'),
(12, 'deep fryer'),
(13, 'grill'),
(14, 'stock pot'),
(15, 'butcher knife'),
(16, 'oven'),
(17, 'oven'),
(18, 'oven');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`) VALUES
(1, 'Tofu'),
(2, 'Lentils'),
(3, 'Beans'),
(4, 'Eggs'),
(5, 'Cheese'),
(6, 'Cow'),
(7, 'Pork'),
(8, 'Chicken'),
(9, 'Turkey'),
(10, 'Deer'),
(11, 'Swordfish'),
(12, 'Potatoes'),
(13, 'Beans'),
(14, 'Bell Peppers'),
(15, 'Avacado'),
(16, 'Tofu'),
(17, 'Tofu'),
(18, 'Tofu');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_recipe_map`
--

CREATE TABLE IF NOT EXISTS `ingredient_recipe_map` (
  `ingredientID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `isOptional` tinyint(1) DEFAULT NULL,
  `replaceableCategory` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ingredient_recipe_map`
--

INSERT INTO `ingredient_recipe_map` (`ingredientID`, `recipeID`, `isOptional`, `replaceableCategory`) VALUES
(1, 1, 0, 0),
(2, 2, 1, 5),
(3, 3, 0, 3),
(4, 4, 1, 5),
(5, 1, 0, 0),
(6, 2, 0, 11),
(7, 3, 1, 1),
(8, 4, 1, 6),
(9, 1, 0, 0),
(10, 2, 0, 7),
(11, 3, 1, 12),
(12, 4, 0, 0),
(1, 1, 0, 0),
(2, 2, 1, 5),
(3, 3, 0, 3),
(4, 4, 1, 5),
(5, 1, 0, 0),
(6, 2, 0, 11),
(7, 3, 1, 1),
(8, 4, 1, 6),
(9, 1, 0, 0),
(10, 2, 0, 7),
(11, 3, 1, 12),
(12, 4, 0, 0),
(1, 1, 0, 0),
(2, 2, 1, 5),
(3, 3, 0, 3),
(4, 4, 1, 5),
(5, 1, 0, 0),
(6, 2, 0, 11),
(7, 3, 1, 1),
(8, 4, 1, 6),
(9, 1, 0, 0),
(10, 2, 0, 7),
(11, 3, 1, 12),
(12, 4, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tag_map`
--

CREATE TABLE IF NOT EXISTS `ingredient_tag_map` (
  `ingredientID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ingredient_tag_map`
--

INSERT INTO `ingredient_tag_map` (`ingredientID`, `tagID`) VALUES
(13, 5),
(1, 1),
(2, 1),
(4, 9),
(5, 10),
(6, 1),
(7, 1),
(8, 5),
(8, 7),
(8, 8),
(8, 11),
(9, 1),
(11, 5),
(14, 2),
(15, 6),
(15, 1),
(15, 1),
(13, 5),
(1, 1),
(2, 1),
(4, 9),
(5, 10),
(6, 1),
(7, 1),
(8, 5),
(8, 7),
(8, 8),
(8, 11),
(9, 1),
(11, 5),
(14, 2),
(15, 6),
(15, 1),
(15, 1),
(13, 5),
(1, 1),
(2, 1),
(4, 9),
(5, 10),
(6, 1),
(7, 1),
(8, 5),
(8, 7),
(8, 8),
(8, 11),
(9, 1),
(11, 5),
(14, 2),
(15, 6),
(15, 1),
(15, 1),
(13, 5),
(1, 1),
(2, 1),
(4, 9),
(5, 10),
(6, 1),
(7, 1),
(8, 5),
(8, 7),
(8, 8),
(8, 11),
(9, 1),
(11, 5),
(14, 2),
(15, 6),
(15, 1),
(15, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `prepInst` text
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `prepInst`) VALUES
(1, 'Pot Roast', 'Put all ingredients in a slow cooker and cook for 10 hours'),
(2, 'Sushi', 'Put all ingredients in a seaweed wrap and eat'),
(3, 'Steak Dinner', 'Steak goes on the grill then microwave your vegetables'),
(4, 'Grill Cheese', 'Butter the bread, put it on the frying pan, put the cheese on it, stick it together'),
(5, 'Pot Roast', 'Put all ingredients in a slow cooker and cook for 10 hours'),
(6, 'Pot Roast', 'Put all ingredients in a slow cooker and cook for 10 hours'),
(7, 'Pot Roast', 'Put all ingredients in a slow cooker and cook for 10 hours');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_equipment_map`
--

CREATE TABLE IF NOT EXISTS `recipe_equipment_map` (
  `recipeID` int(11) NOT NULL,
  `equipmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe_equipment_map`
--

INSERT INTO `recipe_equipment_map` (`recipeID`, `equipmentID`) VALUES
(2, 2),
(3, 14),
(4, 5),
(2, 2),
(3, 14),
(4, 5),
(2, 2),
(3, 14),
(4, 5),
(2, 2),
(3, 14),
(4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_tag_map` (
  `recipeID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe_tag_map`
--

INSERT INTO `recipe_tag_map` (`recipeID`, `tagID`) VALUES
(5, 5),
(6, 11),
(5, 5),
(6, 11),
(2, 2),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `isFilterable` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `isFilterable`) VALUES
(1, 'Spicy', NULL),
(2, 'Seafood', NULL),
(3, 'Paleo', NULL),
(4, 'Gluten Free', NULL),
(5, 'food processor', NULL),
(6, 'Chinese', NULL),
(7, 'Italian', NULL),
(8, 'Western', NULL),
(9, 'Southeast Asian', NULL),
(10, 'Pasta', NULL),
(11, 'Rice Dish', NULL),
(12, 'Soup', NULL),
(13, 'Stew', NULL),
(14, 'Finger Food', NULL),
(15, 'Under 30 Minute', NULL),
(16, 'Spicy', NULL),
(17, 'Spicy', NULL),
(18, 'Spicy', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredient_recipe_map`
--
ALTER TABLE `ingredient_recipe_map`
  ADD KEY `ingredientID` (`ingredientID`), ADD KEY `recipeID` (`recipeID`);

--
-- Indexes for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
  ADD KEY `ingredientID` (`ingredientID`), ADD KEY `tagID` (`tagID`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
  ADD KEY `equipmentID` (`equipmentID`), ADD KEY `recipeID` (`recipeID`);

--
-- Indexes for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
  ADD KEY `recipeID` (`recipeID`), ADD KEY `tagID` (`tagID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient_recipe_map`
--
ALTER TABLE `ingredient_recipe_map`
ADD CONSTRAINT `ingredient_recipe_map_ibfk_1` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ingredient_recipe_map_ibfk_2` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
ADD CONSTRAINT `ingredient_tag_map_ibfk_1` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ingredient_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
ADD CONSTRAINT `recipe_equipment_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_equipment_map_ibfk_2` FOREIGN KEY (`equipmentID`) REFERENCES `equipment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
ADD CONSTRAINT `recipe_tag_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
