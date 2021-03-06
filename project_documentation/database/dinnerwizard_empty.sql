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
-- Table structure for table `error_log`
--

CREATE TABLE IF NOT EXISTS `error_log` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `timestamp` DATETIME NOT NULL,
  `level` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE IF NOT EXISTS `equipment` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE IF NOT EXISTS `ingredients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tags`
--

CREATE TABLE IF NOT EXISTS `ingredient_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `isFilterable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tag_map`
--

CREATE TABLE IF NOT EXISTS `ingredient_tag_map` (
  `ingredientID` int(11) NOT NULL,
  `tagID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Details what any given ingredient is tagged with';

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `prepInst` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='The categories that appear on the recipe filter page';

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_category_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_category_tag_map` (
  `filterCategoryID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipe_ingredient_map`
--

CREATE TABLE IF NOT EXISTS `recipe_ingredient_map` (
  `recipeID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `isOptional` tinyint(1) NOT NULL DEFAULT 0,
  `replaceableTagID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what ingredients any given recipe uses';

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tags`
--

CREATE TABLE IF NOT EXISTS `recipe_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
