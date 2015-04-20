-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2015 at 07:07 PM
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
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

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
(120, 'Roasted Eggplant'),
(121, 'Eggplant'),
(122, 'Dried Dates');

-- --------------------------------------------------------

--
-- Table structure for table `ingredient_tags`
--

CREATE TABLE IF NOT EXISTS `ingredient_tags` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL,
  `isFilterable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

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
(1,1),
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `name`, `prepInst`) VALUES
(4, 'Quick Pasta 1', '<ul>\n<li>Cook pasta according to package instructions, drain and set aside.</li>\n<li>While the pasta is cooking, heat the oil in a large, flat pan on medium-high, and sear or brown the vegetables (in slices or pieces no thicker than a finger), if using any.  Then reduce the heat and allow the vegetables to cook, at most ten minutes.</li>\n<li>Add garlic, chopped fine.  This should be about when the pasta is ready and just as the vegetables still have some bite.  Stir fry briefly with herbs and chili flakes if using them.</li>\n<li>Remove from heat and toss with pasta, adding lemon juice or zest if desired.</li>\n<li>Serve with grated cheese.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(5, 'Quick Pasta 2', '<ul>\n<li>Cook pasta according to package instructions, drain and set aside.</li>\n<li>When the pasta is about halfway done, heat the oil in a large, flat pan on medium, and add garlic, chopped fine.</li>\n<li>When the garlic is nearly cooked, add breadcrumbs, and the vegetable, stirring to coat them with the garlic-floavored oil and continuing to stir-fry until it is a very vivid green and just wilted.</li>\n<li>Toss briefly with chili flakes if using them.</li>\n<li>Remove from heat and toss with pasta, adding lemon juice or zest if desired.</li>\n<li>Serve with grated cheese.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(6, 'Quick Pasta 3', '<ul>\n<li>Boil water.  While it is not yet boiling, cut your vegetables to manageable size.</li>\n<li>Dip your vegetables in the boiling water for a minute or so in small batches, removing them to a colander with a slotted spoon.</li>\n<li>Cook pasta according to package instructions, drain and set aside.</li>\n<li>When the pasta is about halfway done, heat the oil in a large, flat pan on medium, and add garlic, chopped fine.</li>\n<li>When the garlic is nearly cooked, add breadcrumbs, and the vegetable, stirring to coat them with the garlic-floavored oil.</li>\n<li>Toss briefly with chili flakes if using them.</li>\n<li>Remove from heat and toss with pasta, adding lemon juice or zest if desired.</li>\n<li>Serve with grated cheese.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(7, 'Quick Pasta 4', '<ul>\n<li>Peel, then chop, shred or grate your vegetables to the approximate size of matchsticks, or perhaps a bit larger.</li>\n<li>Cook pasta according to package instructions, drain and set aside, reserving some of the water from cooking.</li>\n<li>While the pasta is cooking, heat your large, flat pan and add your vegetable with just barely enough oil to prevent it from burning.</li>\n<li>When the vegetable begins to soften, add some of the pasta water and continue to cook, adding nutmeg and black pepper</li>\n<li>Stir in the pasta, adding more pasta water if the ''sauce'' you''ve been making isn''t coating the pasta easily enough.</li>\n<li>Serve with grated cheese.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(8, 'Lentils and Rice', '<ul>\n<li>Heat oil in medium pot or high-sided pan on medium heat.</li>\n<li>Add the onion, if using, and gook gently until translucent.  If using garlic, add this also.</li>\n<li>Pour the uncooked rice and lentils into pan and stir its contents gently to coat.  Add any herbs and paper now, as well.</li>\n<li>Add water or broth to cover the rice and reduce heat to medium-low once it bubbles.</li>\n<li>Let it cook for approximately fifteen minutes, stirring occasionally.</li>\n<li>Add salt to taste, and lemon juice or zest if desired.</li>\n<li>Serve immediately.</li>\n</ul>'),
(9, 'Makeshift Casserole', '<ul>\n<li>Heat the oven to 375°F.</li>\n<li>Brush a baking dish with oil.</li>\n<li>Whisk together eggs, milk, herbs, salt and pepper.</li>\n<li>Layer bread torn into chunks, vegetables and cheese until the baking dish is 3/4 of the way full.</li>\n<li>Gently pour the egg mixture over the layers and top with any remaining cheese.</li>\n<li>Bake for approximately 40 minutes, or until a fork inserved at the center can be removed cleanly.</li>\n<li>Once removed from the oven, let the casserole rest for 10 minutes before serving.</li>\n<li>Note: This recipe is untested and may require adjustments outside of what is listed here.</li>\n</ul>'),
(10, 'Red Sauce Pasta Casserole', '<ul>\n<li>Heat the oven to 400°F.</li>\n<li>Brush a baking dish with oil.</li>\n<li>Cook the pasta, drain and set aside.</li>\n<li>Heat oil in a pan and cook the onion gently until translucent.  Add garlic and your chosen meat, stirring gently until your meat is browned, but not overcooked.</li>\n<li>Add tomatoes or tomato sauce, then herbs and cook until the liquid in your pan is bubbly.</li>\n<li>Layer pasta, sauce and vegetables in your baking dish, adding or topping with cheese if desired.</li>\n<li>Bake for 30-40 minutes, or until very bubbly.</li>\n</ul>'),
(11, 'White Sauce Pasta Casserole', '<ul>\n<li>Heat the oven to 400°F.</li>\n<li>Brush a baking dish with oil.</li>\n<li>Cook the pasta, drain and set aside.</li>\n<li>Heat oil in a pan and cook the onion gently until translucent. Add garlic.  Brown chicken if using and remove from pan.</li>\n<li>Add herbs to pan, cooking briefly, before adding cream sauce, stirring well until it bubbles slightly.  Remove from heat.</li>\n<li>Layer pasta, chicken(or your chosen vegetable) and sauce in your baking dish.</li>\n<li>Bake for 30-45 minutes. Serve with grated cheese, if desired.</li>\n</ul>'),
(12, 'Creamy Vegetable Pasta Casserole', '<ul>\n<li>Heat the oven to 400°F.</li>\n<li>Brush a baking dish with oil.</li>\n<li>While the oven is heating, boil some water and cook the cauliflower.</li>\n<li>Fish out each piece witha slotted spoon and puree in the food processor.</li>\n<li>Add half the cheese and any spices you like. Pulse a few more times.</li>\n<li>Cook the pasta in the boiling water still in the pot. Drain.</li>\n<li>Layer the pasta and the vegetable mixture in your baking dish, topping with cheese and breadcrumbs if desired.</li>\n<li>Bake for 20-30 minutes, or until bubbly.</li>\n<li>Serve with grated cheese.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(13, 'Baked Falafel', '<ul>\n<li>Heat the oven to 400°F.</li>\n<li>Pulse chickpeas and garlic in food processor.</li>\n<li>Add other ingredients slowly, one-by-one, pulsing as sparingly as possible, so that the mixture resembles coarse breadcrumbs when you''re finished, but still can be packed into good-sized clumps.</li>\n<li>Drop, in spoonfuls like cookies, onto a rimmed baking sheet and bake for about 30 minutes.</li>\n<li>Serve with pitas or rice, yoghurt and fresh cut vegetables for a filling and refreshing meal.</li>\n</ul>'),
(14, 'Hummus', '<ul>\n<li>Pulse chickpeas and garlic in food processor.</li>\n<li>Add other ingredients slowly, one-by-one, processing until a fairly smooth paste is produced.</li>\n<li>Best served with pitas, toasted or fresh.</li>\n</ul>'),
(15, 'Veggie Stir Fry', '<ul>\n<li>Cook the rice.</li>\n<li>When the rice is about halfway done, heat the oil in a large, rounded pan on medium-high, and add the scallions and ginger, if using, stirring lightly so they do not burn.</li>\n<li>Add vegetables(sliced) and garlic, chopped fine, keeping them moving lightly around the pan so as to keep them in contact with the bottom of the pan for as brief a moment at a time as you can.</li>\n<li>If you''re using it, mix some broth with a spoonful of soy sauce and a spoonful of cornstarch and add to the pan, stirring to incorporate this sauce.  Remove from heat within the minute, and serve immediately over rice.</li>\n</ul>'),
(16, 'Stir Fry with Meat or Tofu', '<ul>\n<li>Cook the rice.</li>\n<li>When the rice is about halfway done, heat the oil in a large, rounded pan on medium-high, and add the scallions and ginger, if using, stirring lightly so they do not burn.</li>\n<li>Add your favored protein, keeping it moving lightly around the pan, until it seems to be mostly cooked, about a minute, longer if using tofu.</li>\n<li>Add the vegetables(sliced) and garlic, chopped fine, continuing to keep the mixture in contact with the bottom of the pan for as brief a moment at a time as you can.</li>\n<li>If you''re using it, mix some broth with a spoonful of soy sauce and a spoonful of cornstarch and add to the pan, stirring to incorporate this sauce.  Remove from heat within the minute, and serve immediately over rice.</li>\n</ul>'),
(17, 'Stir Fry with Seafood', '<ul>\n<li>Cook the rice.</li>\n<li>When the rice is about halfway done, heat the oil in a large, rounded pan on medium-high, and add the scallions and ginger, if using, stirring lightly so they do not burn.</li>\n<li>Add your chosen seafood, keeping it moving lightly around the pan, until it seems to be mostly cooked, two or three minutes.</li>\n<li>Add the vegetables(sliced) and garlic, chopped fine, continuing to keep the mixture in contact with the bottom of the pan for as brief a moment at a time as you can.</li>\n<li>If you''re using it, mix some broth with a spoonful of soy sauce and a spoonful of cornstarch and add to the pan, stirring to incorporate this sauce.  Remove from heat within the minute, and serve immediately over rice.</li>\n</ul>'),
(18, 'Peanut Noodles', '<ul>\n<li>Boil water.  While it is not yet boiling, puree the garlic and ginger in a food processor.  If you prefer to do this by hand, grating them works just as well.</li>\n<li>When the water is boiling, cook the pasta. and drain.</li>\n<li>Place the pot back on the stove and heat the oil in it.  Once it shimmers, add the garlic-ginger mixture, stirring quickly so it doesn''t burn.  Immediately add your chili flakes or chopped hot peppers.</li>\n<li>As soon as the garlic-ginger mixture has appeared to cook a bit, add your sauce(s).  If using fish sauce, some sugar would not go amiss.</li>\n<li>Add peanut butter, stirring to make a sauce of medium thickness.  Add noodles and sliced bell peppers, stirring to coat.</li>\n<li>Serve immediately, or at room temperature later, sprinkled with peanuts, and, if you like, pineapple chunks.</li>\n<li>Adapted from a recipe by Martin Minow.</li>\n</ul>'),
(19, 'Essential Tagine', '<ul>\n<li>Heat oil in pan; add garlic and onion.  Cook gently until the onion is translucent, then add the garlic.</li>\n<li>Add your spices once the garlic has begun to soften, and fry until the contents of your pan are very fragrant.</li>\n<li>Add tomato sauce, raise the heat, and stir until bubbling. Then add your chosen meat, turning your pieces to coat it with your spice mixture. </li>\n<li>Add water and again, wait until it is bubbling before you cover your pan and turn the heat to low or medium-low, to simmer, lid slightly askew, for approximately twenty minutes (if there are bones, it may take longer).</li>\n<li>Add whichever fruit you prefer, chopped to smaller-than-bite-sized if necessary, and simmer again for another ten minutes.</li>\n<li>Serve with couscous.</li>\n<li>Adapted from recipes by Mark Bittman in The New York Times</li>\n</ul>'),
(20, 'Breaded Chicken', '<ul>\n<li>Preheat the oven to 375°F.</li>\n<li>Combine breadcrumbs, olive oil, herbs and spices in a gallon plastic bag.</li>\n<li>Drop in chicken pieces one at a time and shake or use hands to coat the chicken with the breading mixture.</li>\n<li>Bake chicken on a rimmed baking sheet or in a baking dish for 20-40 minutes.</li>\n</ul>');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_equipment_map`
--

CREATE TABLE IF NOT EXISTS `recipe_equipment_map` (
  `recipeID` int(11) NOT NULL,
  `equipmentID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what equipment is needed to prepare any given recipe';

--
-- Dumping data for table `recipe_equipment_map`
--

INSERT INTO `recipe_equipment_map` (`recipeID`, `equipmentID`) VALUES
(4, 1),
(4, 2),
(4, 3),
(4, 4),
(5, 1),
(5, 5),
(5, 3),
(5, 4),
(6, 1),
(6, 5),
(6, 3),
(6, 4),
(7, 1),
(7, 5),
(7, 3),
(7, 4),
(7, 6),
(8, 1),
(8, 7),
(9, 8),
(9, 9),
(10, 8),
(10, 9),
(10, 3),
(10, 10),
(11, 8),
(11, 9),
(11, 3),
(12, 8),
(12, 9),
(12, 3),
(12, 11),
(12, 12),
(12, 4),
(13, 8),
(13, 13),
(13, 11),
(14, 11),
(15, 5),
(15, 1),
(16, 5),
(16, 1),
(17, 5),
(17, 1),
(18, 3),
(18, 1),
(18, 11),
(18, 4),
(19, 5),
(19, 1),
(20, 8),
(20, 14),
(20, 9);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_filter_categories`
--

CREATE TABLE IF NOT EXISTS `recipe_filter_categories` (
`id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_general_mysql500_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='The categories that appear on the recipe filter page';

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
  `ratio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci COMMENT='Details what ingredients any given recipe uses';

--
-- Dumping data for table `recipe_ingredient_map`
--

INSERT INTO `recipe_ingredient_map` (`recipeID`, `ingredientID`, `isOptional`, `ratio`) VALUES
(4, 1, 0, 40),
(4, 2, 0, 12),
(4, 3, 0, 8),
(4, 4, 1, 40),
(4, 5, 1, 0),
(5, 1, 0, 35),
(5, 2, 0, 5),
(5, 3, 0, 5),
(5, 6, 0, 40),
(5, 5, 1, 0),
(5, 7, 1, 15),
(6, 1, 0, 35),
(6, 2, 0, 9),
(6, 3, 0, 9),
(6, 8, 0, 35),
(6, 5, 1, 0),
(6, 7, 0, 12),
(7, 1, 0, 45),
(7, 2, 0, 0),
(7, 9, 0, 55),
(7, 10, 1, 0),
(7, 11, 1, 0),
(8, 2, 0, 5),
(8, 12, 1, 5),
(8, 3, 1, 3),
(8, 14, 1, 1),
(8, 15, 0, 30),
(8, 16, 0, 10),
(8, 17, 0, 56),
(8, 11, 1, 1),
(8, 18, 1, 1),
(9, 2, 0, 2),
(9, 19, 0, 30),
(9, 20, 0, 15),
(9, 21, 0, 7),
(9, 22, 1, 5),
(9, 11, 1, 0),
(9, 18, 1, 0),
(9, 23, 0, 20),
(9, 24, 1, 20),
(9, 12, 1, 5),
(9, 3, 1, 1),
(9, 14, 1, 0),
(9, 5, 1, 0),
(10, 1, 0, 50),
(10, 2, 0, 2),
(10, 12, 1, 4),
(10, 3, 0, 2),
(10, 25, 1, 8),
(10, 26, 0, 20),
(10, 23, 1, 8),
(10, 24, 1, 0),
(10, 22, 1, 5),
(10, 27, 1, 0),
(10, 5, 1, 0),
(10, 7, 0, 0),
(11, 1, 0, 50),
(11, 2, 0, 3),
(11, 12, 1, 2),
(11, 3, 0, 2),
(11, 28, 1, 4),
(11, 22, 1, 12),
(11, 14, 1, 0),
(11, 5, 1, 0),
(11, 7, 1, 0),
(11, 29, 0, 20),
(12, 1, 0, 50),
(12, 2, 0, 1),
(12, 3, 1, 2),
(12, 4, 0, 40),
(12, 22, 0, 5),
(12, 10, 1, 0),
(12, 5, 1, 0),
(12, 7, 1, 2),
(13, 2, 0, 2),
(13, 3, 0, 6),
(13, 30, 0, 75),
(13, 31, 1, 2),
(13, 32, 0, 1),
(13, 33, 1, 2),
(13, 34, 1, 2),
(13, 18, 1, 0),
(13, 35, 1, 1),
(13, 36, 1, 1),
(14, 2, 1, 2),
(14, 3, 0, 6),
(14, 30, 0, 80),
(14, 18, 1, 0),
(14, 33, 1, 5),
(14, 37, 1, 2),
(14, 38, 1, 5),
(15, 15, 1, 0),
(15, 39, 0, 3),
(15, 3, 1, 2),
(15, 40, 1, 0),
(15, 41, 1, 0),
(15, 42, 1, 30),
(15, 43, 1, 20),
(15, 44, 1, 20),
(15, 17, 1, 5),
(15, 46, 1, 1),
(15, 47, 1, 2),
(16, 15, 1, 0),
(16, 39, 0, 3),
(16, 3, 1, 2),
(16, 40, 1, 0),
(16, 41, 1, 0),
(16, 42, 1, 30),
(16, 43, 1, 20),
(16, 28, 1, 20),
(16, 17, 1, 5),
(16, 46, 1, 1),
(16, 47, 1, 2),
(17, 15, 1, 0),
(17, 39, 0, 3),
(17, 3, 1, 2),
(17, 40, 1, 0),
(17, 41, 1, 0),
(17, 42, 1, 30),
(17, 43, 1, 20),
(17, 48, 1, 20),
(17, 17, 1, 5),
(17, 46, 1, 1),
(17, 49, 1, 2),
(18, 1, 0, 80),
(18, 50, 1, 5),
(18, 41, 0, 2),
(18, 3, 0, 2),
(18, 24, 0, 30),
(18, 51, 0, 5),
(18, 47, 0, 1),
(18, 52, 1, 1),
(18, 38, 1, 2),
(18, 53, 1, 1),
(18, 54, 1, 1),
(18, 55, 1, 2),
(19, 56, 0, 30),
(19, 12, 0, 4),
(19, 2, 0, 2),
(19, 3, 0, 3),
(19, 35, 0, 1),
(19, 57, 0, 0),
(19, 58, 1, 1),
(19, 59, 0, 2),
(19, 36, 0, 1),
(19, 60, 0, 5),
(19, 61, 0, 40),
(19, 62, 0, 4),
(19, 63, 0, 8),
(20, 28, 0, 60),
(20, 2, 0, 10),
(20, 7, 0, 30),
(20, 3, 1, 0),
(20, 64, 1, 0),
(20, 27, 1, 0),
(20, 11, 1, 0),
(20, 18, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_replaceable_ingredient_map`
--

CREATE TABLE IF NOT EXISTS `recipe_replaceable_ingredient_map` (
  `recipeID` int(11) NOT NULL,
  `ingredientID` int(11) NOT NULL,
  `replaceableIngredientID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci;

--
-- Dumping data for table `recipe_replaceable_ingredient_map`
--

INSERT INTO `recipe_replaceable_ingredient_map` (`recipeID`, `ingredientID`, `replaceableIngredientID`) VALUES
(4, 2, 65),
(4, 2, 66),
(4, 2, 67),
(4, 4, 43),
(4, 4, 68),
(4, 4, 69),
(4, 4, 70),
(4, 4, 44),
(4, 4, 23),
(5, 2, 65),
(5, 2, 66),
(5, 2, 67),
(5, 6, 71),
(5, 6, 72),
(6, 2, 65),
(6, 2, 66),
(6, 2, 67),
(7, 2, 65),
(7, 2, 66),
(7, 2, 67),
(7, 9, 73),
(7, 9, 74),
(7, 9, 75),
(7, 9, 76),
(7, 9, 77),
(7, 9, 78),
(7, 9, 79),
(8, 2, 65),
(8, 2, 66),
(8, 2, 67),
(8, 12, 40),
(8, 12, 95),
(8, 12, 94),
(8, 14, 27),
(8, 14, 104),
(8, 15, 61),
(8, 16, 80),
(8, 17, 63),
(9, 2, 65),
(9, 2, 66),
(9, 2, 67),
(9, 19, 15),
(9, 19, 91),
(9, 19, 1),
(9, 19, 61),
(9, 23, 44),
(9, 23, 119),
(9, 23, 24),
(9, 23, 71),
(9, 23, 77),
(9, 24, 44),
(9, 24, 119),
(9, 24, 26),
(9, 24, 71),
(9, 24, 77),
(9, 12, 40),
(9, 12, 95),
(9, 12, 94),
(9, 14, 27),
(9, 14, 104),
(10, 2, 65),
(10, 2, 66),
(10, 2, 67),
(10, 12, 40),
(10, 12, 95),
(10, 12, 94),
(10, 25, 86),
(10, 25, 84),
(10, 25, 85),
(10, 26, 62),
(10, 23, 44),
(10, 23, 119),
(10, 23, 26),
(10, 23, 71),
(10, 24, 44),
(10, 24, 119),
(10, 27, 14),
(10, 27, 104),
(11, 2, 65),
(11, 2, 66),
(11, 2, 67),
(11, 12, 40),
(11, 12, 95),
(11, 12, 94),
(11, 28, 23),
(11, 28, 44),
(11, 28, 71),
(11, 14, 27),
(11, 14, 104),
(12, 2, 65),
(12, 2, 66),
(12, 2, 67),
(12, 4, 70),
(12, 10, 27),
(12, 10, 104),
(12, 10, 14),
(12, 5, 57),
(12, 5, 64),
(13, 2, 66),
(13, 2, 67),
(13, 31, 103),
(14, 2, 66),
(14, 2, 67),
(15, 39, 67),
(15, 39, 66),
(15, 42, 98),
(15, 42, 99),
(15, 42, 101),
(15, 42, 96),
(15, 43, 24),
(15, 43, 97),
(15, 44, 121),
(15, 44, 70),
(15, 44, 96),
(15, 17, 63),
(15, 47, 54),
(15, 47, 49),
(16, 39, 67),
(16, 39, 66),
(16, 42, 98),
(16, 42, 99),
(16, 42, 101),
(16, 42, 96),
(16, 43, 24),
(16, 43, 97),
(16, 28, 83),
(16, 28, 87),
(16, 28, 56),
(16, 28, 81),
(16, 28, 117),
(16, 28, 118),
(16, 17, 63),
(16, 47, 54),
(16, 47, 49),
(17, 39, 67),
(17, 39, 66),
(17, 42, 98),
(17, 42, 99),
(17, 42, 101),
(17, 42, 96),
(17, 43, 24),
(17, 43, 97),
(17, 48, 89),
(17, 48, 88),
(17, 48, 90),
(17, 17, 63),
(17, 49, 54),
(17, 49, 47),
(18, 50, 39),
(18, 50, 66),
(18, 38, 5),
(19, 56, 28),
(19, 56, 83),
(19, 12, 95),
(19, 2, 66),
(19, 2, 67),
(19, 59, 28),
(19, 59, 83),
(19, 36, 28),
(19, 36, 83),
(19, 60, 113),
(19, 60, 122),
(19, 60, 115),
(19, 60, 114),
(19, 60, 116),
(19, 61, 15),
(19, 63, 17),
(20, 2, 39),
(20, 2, 66),
(20, 2, 67),
(20, 64, 57),
(20, 27, 14),
(20, 27, 104);

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tags`
--

CREATE TABLE IF NOT EXISTS `recipe_tags` (
`id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

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
(18, 'Component'),
(19, 'Seafood'),
(20, 'Meat & Starch');

-- --------------------------------------------------------

--
-- Table structure for table `recipe_tag_map`
--

CREATE TABLE IF NOT EXISTS `recipe_tag_map` (
  `recipeID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Details what any given recipe is tagged with';

--
-- Dumping data for table `recipe_tag_map`
--

INSERT INTO `recipe_tag_map` (`recipeID`, `tagID`) VALUES
(4, 16),
(4, 7),
(4, 15),
(4, 3),
(4, 2),
(4, 4),
(5, 16),
(5, 7),
(5, 15),
(5, 3),
(5, 2),
(6, 16),
(6, 7),
(6, 15),
(6, 3),
(6, 2),
(7, 16),
(7, 7),
(7, 15),
(7, 3),
(8, 16),
(8, 8),
(8, 15),
(8, 4),
(9, 3),
(9, 16),
(9, 6),
(10, 2),
(10, 6),
(10, 14),
(10, 3),
(11, 2),
(11, 6),
(11, 14),
(11, 3),
(12, 16),
(12, 6),
(12, 14),
(12, 3),
(13, 16),
(13, 4),
(13, 15),
(13, 14),
(13, 13),
(13, 17),
(14, 16),
(14, 4),
(14, 15),
(14, 14),
(14, 13),
(15, 16),
(15, 12),
(15, 15),
(15, 1),
(15, 8),
(16, 1),
(16, 12),
(16, 15),
(16, 8),
(17, 19),
(17, 15),
(17, 12),
(17, 1),
(17, 8),
(18, 5),
(18, 16),
(18, 7),
(18, 15),
(18, 17),
(19, 4),
(19, 11),
(19, 20),
(19, 17),
(20, 3),
(20, 20),
(20, 15),
(20, 14);

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
 ADD KEY `recipeID` (`recipeID`), ADD KEY `ingredientID` (`ingredientID`);

--
-- Indexes for table `recipe_replaceable_ingredient_map`
--
ALTER TABLE `recipe_replaceable_ingredient_map`
 ADD KEY `recipeID` (`recipeID`), ADD KEY `ingredientID` (`ingredientID`), ADD KEY `replaceableIngredientID` (`replaceableIngredientID`);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=123;
--
-- AUTO_INCREMENT for table `ingredient_tags`
--
ALTER TABLE `ingredient_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `recipe_filter_categories`
--
ALTER TABLE `recipe_filter_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `recipe_tags`
--
ALTER TABLE `recipe_tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
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
-- Constraints for table `recipe_replaceable_ingredient_map`
--
ALTER TABLE `recipe_replaceable_ingredient_map`
ADD CONSTRAINT `recipe_replaceable_ingredient_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_replaceable_ingredient_map_ibfk_2` FOREIGN KEY (`ingredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_replaceable_ingredient_map_ibfk_3` FOREIGN KEY (`replaceableIngredientID`) REFERENCES `ingredients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipe_tag_map`
--
ALTER TABLE `recipe_tag_map`
ADD CONSTRAINT `recipe_tag_map_ibfk_1` FOREIGN KEY (`recipeID`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `recipe_tag_map_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `recipe_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
