-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2015 at 01:46 AM
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
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `name`) VALUES
(1, 'Stove'),
(2, 'Large Pan'),
(3, 'Stock Pot'),
(4, 'Colander'),
(5, 'Large Round Pan'),
(6, 'Grater'),
(7, 'Medium Pot'),
(8, 'Oven'),
(9, 'Baking Dish'),
(10, 'Frying Pan'),
(11, 'Food Processor'),
(12, 'Slotted Spoon'),
(13, 'Baking Sheet'),
(14, 'Gallon Plastic Bag');

-- --------------------------------------------------------

--
-- Table structure for table `error_log`
--

CREATE TABLE IF NOT EXISTS `error_log` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `level` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `description` text COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=121 ;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`id`, `name`) VALUES
(1, 'Pasta'),
(2, 'Olive Oil'),
(3, 'Garlic'),
(4, 'Cauliflower'),
(5, 'Chili Flakes'),
(6, 'Kale'),
(7, 'Breadcrumbs'),
(8, 'Broccoli Rabe'),
(9, 'Butternut Squash'),
(10, 'Nutmeg'),
(11, 'Black Pepper'),
(12, 'Onion'),
(14, 'Rosemary'),
(15, 'Rice'),
(16, 'Lentils'),
(17, 'Broth'),
(18, 'Salt'),
(19, 'Bread'),
(20, 'Milk'),
(21, 'Eggs'),
(22, 'Cheese'),
(23, 'Roasted Mushrooms'),
(24, 'Bell Peppers'),
(25, 'Ground Beef'),
(26, 'Tomatoes'),
(27, 'Oregano'),
(28, 'Chicken'),
(29, 'Cream Sauce'),
(30, 'Chickpeas'),
(31, 'Cilantro'),
(32, 'Baking Powder'),
(33, 'Lemon'),
(34, 'Flour'),
(35, 'Cumin'),
(36, 'Coriander'),
(37, 'Tahini Paste'),
(38, 'Hot Peppers'),
(39, 'Peanut Oil'),
(40, 'Scallion'),
(41, 'Ginger'),
(42, 'Pea Pods'),
(43, 'Broccoli'),
(44, 'Mushrooms'),
(46, 'Cornstarch'),
(47, 'Soy Sauce'),
(48, 'Shrimp'),
(49, 'Oyster Sauce'),
(50, 'Sesame Oil'),
(51, 'Peanut Butter'),
(52, 'Peanuts'),
(53, 'Sugar'),
(54, 'Fish Sauce'),
(55, 'Pineapple'),
(56, 'Lamb'),
(57, 'Cayenne'),
(58, 'Caraway Seeds'),
(59, 'Cinnamon'),
(60, 'Dried Figs'),
(61, 'Couscous'),
(62, 'Tomato Sauce'),
(63, 'Water'),
(64, 'Paprika'),
(65, 'Butter'),
(66, 'Canola Oil'),
(67, 'Vegetable Oil'),
(68, 'Brussels Sprouts'),
(69, 'Asparagus'),
(70, 'Zucchini'),
(71, 'Spinach'),
(72, 'Escarole'),
(73, 'Acorn Squash'),
(74, 'Hubbard Squash'),
(75, 'Pumpkin'),
(76, 'Beets'),
(77, 'Turnips'),
(78, 'Parsnips'),
(79, 'Carrots'),
(80, 'Beans'),
(81, 'Tofu'),
(82, 'Slivered Almonds'),
(83, 'Beef'),
(84, 'Ground Lamb'),
(85, 'Ground Turkey'),
(86, 'Ground Pork'),
(87, 'Pork'),
(88, 'Fish'),
(89, 'Imitation Crabmeat'),
(90, 'Scallops'),
(91, 'Potatoes'),
(92, 'Pitas'),
(93, 'Corn'),
(94, 'Leek'),
(95, 'Shallot'),
(96, 'Green Beans'),
(97, 'Bean Sprouts'),
(98, 'Bok Choy'),
(99, 'Napa Cabbage'),
(100, 'Celery'),
(101, 'Cabbage'),
(102, 'Chipotle Powder'),
(103, 'Parsley'),
(104, 'Thyme'),
(105, 'Basil'),
(106, 'Sunflower Oil'),
(107, 'Anchovy Paste'),
(108, 'Tomato Paste'),
(109, 'Cooking Sherry'),
(110, 'Rice Vinegar'),
(111, 'Red Wine Vinegar'),
(112, 'Dates'),
(113, 'Dried Apricots'),
(114, 'Golden Raisins'),
(115, 'Greek Olives'),
(116, 'Oranges'),
(117, 'Velveted Meat'),
(118, 'Pressed Tofu'),
(119, 'Roasted Peppers'),
(120, 'Roasted Eggplant');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tags`
--

CREATE TABLE IF NOT EXISTS `ingredient_tags` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `isFilterable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ingredient_tags`
--

INSERT INTO `ingredient_tags` (`id`, `name`, `isFilterable`) VALUES
(1, 'Alternate Protein', 1),
(2, 'Meat', 1),
(3, 'Seafood', 1),
(4, 'Starch', 1),
(5, 'Vegetables', 1),
(6, 'Spices and Herbs', 1),
(7, 'Odds and Ends', 1),
(8, 'Pre-Made', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tag_map`
--

CREATE TABLE IF NOT EXISTS `ingredient_tag_map` (
  `ingredientID` int(11) NOT NULL,
  `tagID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Details what any given ingredient is tagged with';

--
-- Dumping data for table `ingredient_tag_map`
--

INSERT INTO `ingredient_tag_map` (`ingredientID`, `tagID`) VALUES
(80, 1),
(22, 1),
(21, 1),
(16, 1),
(81, 1),
(52, 1),
(51, 1),
(82, 1),
(30, 1),
(28, 2),
(83, 2),
(25, 2),
(84, 2),
(86, 2),
(85, 2),
(56, 2),
(87, 2),
(88, 3),
(89, 3),
(90, 3),
(48, 3),
(19, 4),
(61, 4),
(15, 4),
(91, 4),
(92, 4),
(93, 4),
(68, 5),
(12, 5),
(94, 5),
(40, 5),
(95, 5),
(71, 5),
(69, 5),
(43, 5),
(4, 5),
(70, 5),
(44, 5),
(24, 5),
(96, 5),
(42, 5),
(97, 5),
(98, 5),
(99, 5),
(101, 5),
(38, 5),
(6, 5),
(72, 5),
(8, 5),
(26, 5),
(73, 5),
(74, 5),
(75, 5),
(76, 5),
(77, 5),
(78, 5),
(79, 5),
(100, 5),
(3, 6),
(58, 6),
(57, 6),
(102, 6),
(36, 6),
(31, 6),
(103, 6),
(18, 6),
(11, 6),
(35, 6),
(59, 6),
(14, 6),
(27, 6),
(104, 6),
(64, 6),
(10, 6),
(105, 6),
(65, 7),
(46, 7),
(34, 7),
(20, 7),
(53, 7),
(63, 7),
(7, 7),
(2, 7),
(67, 7),
(66, 7),
(39, 7),
(50, 7),
(106, 7),
(47, 7),
(54, 7),
(49, 7),
(107, 7),
(108, 7),
(109, 7),
(110, 7),
(111, 7),
(112, 7),
(60, 7),
(113, 7),
(114, 7),
(115, 7),
(116, 7),
(55, 7),
(33, 7),
(62, 8),
(17, 8),
(117, 8),
(118, 8),
(29, 8),
(23, 8),
(119, 8),
(120, 8);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `prepInst` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_equipment_map`
--

CREATE TABLE IF NOT EXISTS `recipe_equipment_map` (
  `recipeID` int(11) NOT NULL,
  `equipmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what equipment is needed to prepare any given recipe';

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_categories`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_categories` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='The categories that appear on the recipe filter page' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `recipe_filter_categories`
--

INSERT INTO `recipe_filter_categories` (`id`, `name`) VALUES
(1, 'Flavors'),
(2, 'Dishes'),
(3, 'Equipment'),
(4, 'Other'),
(5, 'Without');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_category_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_category_tag_map` (
  `filterCategoryID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

--
-- Dumping data for table `recipe_filter_category_tag_map`
--

INSERT INTO `recipe_filter_category_tag_map` (`filterCategoryID`, `tagID`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(4, 15),
(4, 16),
(4, 17),
(4, 18);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredient_map`
--

CREATE TABLE IF NOT EXISTS `recipe_ingredient_map` (
  `recipeID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `isOptional` tinyint(1) NOT NULL DEFAULT '0',
  `replaceableTagID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what ingredients any given recipe uses';

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tags`
--

CREATE TABLE IF NOT EXISTS `recipe_tags` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `recipe_tags`
--

INSERT INTO `recipe_tags` (`id`, `name`) VALUES
(1, 'Chinese'),
(2, 'Italian'),
(3, 'Western'),
(4, 'Mediterranean'),
(5, 'Southeast Asian'),
(6, 'Casserole'),
(7, 'Pasta'),
(8, 'Rice Dish'),
(9, 'Meat and Starch'),
(10, 'Soup'),
(11, 'Stew'),
(12, 'Stir Fry'),
(13, 'Snack'),
(14, 'Other'),
(15, 'Under 30'),
(16, 'Vegetarian'),
(17, 'Spicy'),
(18, 'Component');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_tag_map` (
  `recipeID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Details what any given recipe is tagged with';

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
-- Indexes for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
 ADD PRIMARY KEY (`id`);

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
 ADD KEY `recipeID` (`recipeID`), ADD KEY `equipmentID` (`equipmentID`);

--
-- Indexes for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_filter_category_tag_map`
--
ALTER TABLE `recipe_filter_category_tag_map`
 ADD KEY `filterCategoryID` (`filterCategoryID`), ADD KEY `tagID` (`tagID`);

--
-- Indexes for table `recipe_ingredient_map`
--
ALTER TABLE `recipe_ingredient_map`
 ADD KEY `recipeID` (`recipeID`), ADD KEY `ingredientID` (`ingredientID`), ADD KEY `replaceableCategoryID` (`replaceableTagID`);

--
-- Indexes for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
 ADD KEY `recipeID` (`recipeID`), ADD KEY `tagID` (`tagID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ingredient_tag_map`
--
ALTER TABLE `ingredient_tag_map`
ADD CONSTRAINT `ingredient_tag_map_ibfk_1` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ingredient_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `ingredient_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_equipment_map`
--
ALTER TABLE `recipe_equipment_map`
ADD CONSTRAINT `recipe_equipment_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_equipment_map_ibfk_2` FOREIGN KEY (`equipmentID`) REFERENCES `equipment` (`id`);

--
-- Constraints for table `recipe_filter_category_tag_map`
--
ALTER TABLE `recipe_filter_category_tag_map`
ADD CONSTRAINT `recipe_filter_category_tag_map_ibfk_1` FOREIGN KEY (`filterCategoryID`) REFERENCES `recipe_filter_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_filter_category_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `recipe_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_ingredient_map`
--
ALTER TABLE `recipe_ingredient_map`
ADD CONSTRAINT `recipe_ingredient_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_ingredient_map_ibfk_2` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
ADD CONSTRAINT `recipe_tag_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `recipe_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
