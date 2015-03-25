-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2015 at 02:16 AM
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
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
`equipmentID` int(11) NOT NULL,
  `equipmentName` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
`ingredientID` int(11) NOT NULL,
  `ingredientName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredientID`, `ingredientName`) VALUES
(1, 'Olive Oil'),
(2, 'Garlic'),
(3, 'Beef'),
(4, 'Ham'),
(5, 'Bacon'),
(6, 'Mutton'),
(7, 'Sausage'),
(8, 'Chicken'),
(9, 'Turkey'),
(11, 'Shrimp'),
(12, 'Scallops'),
(13, 'Tuna'),
(14, 'Swordfish'),
(15, 'Salmon'),
(16, 'Mackerel'),
(17, 'Tofu'),
(18, 'Beans'),
(19, 'Lentils'),
(20, 'Cheese'),
(21, 'Egg'),
(22, 'Flour'),
(23, 'Milk'),
(24, 'Vinegar'),
(25, 'Cornstarch'),
(26, 'Ketchup'),
(27, 'Fortune cookie'),
(28, 'Pasta'),
(29, 'Carrot'),
(30, 'Ice');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tags`
--

CREATE TABLE IF NOT EXISTS `ingredient_tags` (
`tagID` int(11) NOT NULL,
  `tagName` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `isFilterable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ingredient_tags`
--

INSERT INTO `ingredient_tags` (`tagID`, `tagName`, `isFilterable`) VALUES
(1, 'Alternate Protien', 1),
(2, 'Meat', 1),
(3, 'Seafood', 1),
(4, 'Starch', 1),
(5, 'Vegetables', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
`recipeID` int(11) NOT NULL,
  `recipeName` varchar(255) NOT NULL,
  `prepInst` text
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`recipeID`, `recipeName`, `prepInst`) VALUES
(1, 'Test recipe', 'Do a thing with some stuff'),
(2, 'A Western dish', 'Mix ingredients in bowl. Serve with knife or sharp spoon.\r\n'),
(3, 'A Chinese dish', 'Go to local Chinese restaurant, ask for fortune cookie, open for full instructions.'),
(5, 'A Mediterranean dish', 'Discard all ingredients and fly to Spain. Estimated cost of meal is $1500, depending on prep time.'),
(6, 'Low calorie snack', 'Fill ice cube trays with water.  Freeze for 30 minutes or until solid.  Eat them before they melt, or place them in a glass of water and drink.'),
(7, 'Tomato soup', 'Boil ketchup until smoke alarm sounds.  Extinguish fire and serve.'),
(8, 'Boiled beef and carrots', 'Boil beef and carrots in large pot until mushy and inedible. Serve with more water than substance.'),
(9, '3-second special', 'Mix various ingredients in bowl. Cook in dishwasher for 3-4 seconds or until soggy.');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_equipment_map`
--

CREATE TABLE IF NOT EXISTS `recipe_equipment_map` (
`mapID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `equipmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what equipment is needed to prepare any given recipe' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_categories`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_categories` (
`categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='The categories that appear on the recipe filter page' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `recipe_filter_categories`
--

INSERT INTO `recipe_filter_categories` (`categoryID`, `categoryName`) VALUES
(1, 'Flavors'),
(2, 'Dishes'),
(3, 'Other'),
(4, 'Without');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_category_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_category_tag_map` (
`mapID` int(11) NOT NULL,
  `filterCategoryID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `recipe_filter_category_tag_map`
--

INSERT INTO `recipe_filter_category_tag_map` (`mapID`, `filterCategoryID`, `tagID`) VALUES
(1, 1, 6),
(2, 1, 7),
(3, 1, 8),
(4, 1, 9),
(5, 1, 10),
(6, 1, 11),
(16, 2, 12),
(17, 2, 13),
(18, 2, 14),
(19, 2, 15),
(20, 2, 16),
(21, 2, 17),
(22, 2, 18),
(23, 2, 19),
(24, 2, 20),
(25, 3, 21),
(26, 3, 22),
(27, 3, 23);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredient_map`
--

CREATE TABLE IF NOT EXISTS `recipe_ingredient_map` (
`mapID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `isOptional` tinyint(1) NOT NULL DEFAULT '0',
  `replaceableTagID` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what ingredients any given recipe uses' AUTO_INCREMENT=18 ;

--
-- Dumping data for table `recipe_ingredient_map`
--

INSERT INTO `recipe_ingredient_map` (`mapID`, `recipeID`, `ingredientID`, `isOptional`, `replaceableTagID`) VALUES
(1, 1, 1, 0, 3),
(2, 1, 16, 0, NULL),
(3, 2, 15, 0, NULL),
(4, 2, 8, 0, NULL),
(5, 3, 19, 0, 4),
(8, 5, 11, 0, 1),
(9, 5, 13, 0, NULL),
(10, 6, 5, 0, NULL),
(11, 6, 19, 0, NULL),
(12, 7, 12, 0, NULL),
(13, 7, 4, 0, 2),
(14, 8, 30, 0, NULL),
(15, 8, 12, 0, NULL),
(16, 9, 12, 0, 5),
(17, 9, 25, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tags`
--

CREATE TABLE IF NOT EXISTS `recipe_tags` (
`tagID` int(11) NOT NULL,
  `tagName` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `recipe_tags`
--

INSERT INTO `recipe_tags` (`tagID`, `tagName`) VALUES
(6, 'Mexican'),
(7, 'Chinese'),
(8, 'Italian'),
(9, 'Western'),
(10, 'Mediterranean'),
(11, 'Southeast Asian'),
(12, 'Casserole'),
(13, 'Pasta'),
(14, 'Rice Dish'),
(15, 'Meat and Starch'),
(16, 'Soup'),
(17, 'Stew'),
(18, 'Finger Food'),
(19, 'Snack or Appetizer'),
(20, 'Sweet'),
(21, 'Vegetarian'),
(22, 'Under 30 Minutes'),
(23, 'Cost Cutter');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_tag_map` (
`mapID` int(11) NOT NULL,
  `recipeID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Details what any given recipe is tagged with' AUTO_INCREMENT=19 ;

--
-- Dumping data for table `recipe_tag_map`
--

INSERT INTO `recipe_tag_map` (`mapID`, `recipeID`, `tagID`) VALUES
(1, 1, 19),
(2, 1, 10),
(3, 2, 9),
(4, 2, 15),
(5, 3, 7),
(6, 3, 19),
(9, 5, 10),
(10, 5, 18),
(11, 6, 19),
(12, 6, 21),
(13, 7, 23),
(14, 7, 21),
(15, 8, 17),
(16, 8, 18),
(17, 9, 22),
(18, 9, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
 ADD PRIMARY KEY (`equipmentID`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
 ADD PRIMARY KEY (`ingredientID`);

--
-- Indexes for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
 ADD PRIMARY KEY (`tagID`);

--
-- Indexes for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
 ADD PRIMARY KEY (`mapID`), ADD KEY `ingredientID` (`ingredientID`), ADD KEY `categoryID` (`tagID`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
 ADD PRIMARY KEY (`recipeID`);

--
-- Indexes for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
 ADD PRIMARY KEY (`mapID`), ADD KEY `recipeID` (`recipeID`), ADD KEY `equipmentID` (`equipmentID`);

--
-- Indexes for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
 ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `recipe_filter_category_tag_map`
--
ALTER TABLE `recipe_filter_category_tag_map`
 ADD PRIMARY KEY (`mapID`), ADD KEY `filterCategoryID` (`filterCategoryID`), ADD KEY `tagID` (`tagID`);

--
-- Indexes for table `recipe_ingredient_map`
--
ALTER TABLE `recipe_ingredient_map`
 ADD PRIMARY KEY (`mapID`), ADD UNIQUE KEY `mapID` (`mapID`), ADD KEY `recipeID` (`recipeID`), ADD KEY `ingredientID` (`ingredientID`), ADD KEY `replaceableCategoryID` (`replaceableTagID`);

--
-- Indexes for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
 ADD PRIMARY KEY (`tagID`);

--
-- Indexes for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
 ADD PRIMARY KEY (`mapID`), ADD KEY `recipeID` (`recipeID`), ADD KEY `tagID` (`tagID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
MODIFY `equipmentID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `ingredientID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
MODIFY `recipeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `recipe_filter_category_tag_map`
--
ALTER TABLE `recipe_filter_category_tag_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `recipe_ingredient_map`
--
ALTER TABLE `recipe_ingredient_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
MODIFY `mapID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
ADD CONSTRAINT `ingredient_tag_map_ibfk_1` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`ingredientID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ingredient_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `ingredient_tags` (`tagID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
ADD CONSTRAINT `recipe_equipment_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_filter_category_tag_map`
--
ALTER TABLE `recipe_filter_category_tag_map`
ADD CONSTRAINT `recipe_filter_category_tag_map_ibfk_1` FOREIGN KEY (`filterCategoryID`) REFERENCES `recipe_filter_categories` (`categoryID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_filter_category_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `recipe_tags` (`tagID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_ingredient_map`
--
ALTER TABLE `recipe_ingredient_map`
ADD CONSTRAINT `recipe_ingredient_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_ingredient_map_ibfk_2` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`ingredientID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
ADD CONSTRAINT `recipe_tag_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`recipeID`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `recipe_tags` (`tagID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
