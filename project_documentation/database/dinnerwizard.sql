--
-- Database: dinnerwizard
--

CREATE DATABASE IF NOT EXISTS dinnerwizard
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

  USE dinnerwizard;

-- --------------------------------------------------------

--
-- Table structure for table ingredients
--

CREATE TABLE IF NOT EXISTS ingredients
(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table tags
--

CREATE TABLE IF NOT EXISTS tags
(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) DEFAULT NULL,
  isFilterable bool
);

-- --------------------------------------------------------

--
-- Table structure for table recipes
--

CREATE TABLE IF NOT EXISTS recipes
(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL,
  prepInst text
);

-- --------------------------------------------------------

--
-- Table structure for table equipment
--

CREATE TABLE IF NOT EXISTS equipment
(
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(255) NOT NULL
);

-- --------------------------------------------------------

--
-- Table structure for table recipe_equipment_map
--

CREATE TABLE IF NOT EXISTS recipe_equipment_map
(
  recipeID int(11) NOT NULL,
  equipmentID int(11) NOT NULL,
  INDEX (equipmentID),
  INDEX (recipeID),
  FOREIGN KEY (recipeID) REFERENCES recipes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (equipmentID) REFERENCES equipment(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for table ingredient_recipe_map
--

CREATE TABLE IF NOT EXISTS ingredient_recipe_map
(
  ingredientID int(11) NOT NULL,
  recipeID int(11) NOT NULL,
  isOptional bool,
  replaceableCategory int(11),
  INDEX (ingredientID),
  INDEX (recipeID),
  FOREIGN KEY (ingredientID) REFERENCES ingredients(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (recipeID) REFERENCES recipes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for table ingredient_tag_map
--

CREATE TABLE IF NOT EXISTS ingredient_tag_map
(
  ingredientID int(11) NOT NULL,
  tagID int(11) NOT NULL,
  categoryID int(11) NOT NULL,
  INDEX (ingredientID),
  INDEX (tagID),
  INDEX (categoryID),
  FOREIGN KEY (ingredientID) REFERENCES ingredients(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (tagID) REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (categoryID) REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for table recipe_tag_map
--

CREATE TABLE IF NOT EXISTS recipe_tag_map
(
  recipeID int(11) NOT NULL,
  tagID int(11) NOT NULL,
  categoryID int(11) NOT NULL,
  INDEX (recipeID),
  INDEX (tagID),
  INDEX (categoryID),
  FOREIGN KEY (recipeID) REFERENCES recipes(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (tagID) REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (categoryID) REFERENCES tags(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- --------------------------------------------------------

--
-- Table structure for table error_log
--

CREATE TABLE IF NOT EXISTS error_log
(
  id int(11) NOT NULL AUTO_INCREMENT,
  timestamp DATETIME NOT NULL,
  level varchar(20) NOT NULL,
  description text NOT NULL,
  INDEX (id),
  INDEX (level)

);