DELETE FROM `recipe_filter_category_tag_map` WHERE 1;

INSERT INTO `recipe_filter_category_tag_map` (`filterCategoryID`, `tagID`) VALUES 

((SELECT id FROM recipe_filter_categories WHERE name = "Flavors"), (SELECT id FROM recipe_tags WHERE name = "Chinese")),
((SELECT id FROM recipe_filter_categories WHERE name = "Flavors"), (SELECT id FROM recipe_tags WHERE name = "Italian")),
((SELECT id FROM recipe_filter_categories WHERE name = "Flavors"), (SELECT id FROM recipe_tags WHERE name = "Western")),
((SELECT id FROM recipe_filter_categories WHERE name = "Flavors"), (SELECT id FROM recipe_tags WHERE name = "Mediterranean")),
((SELECT id FROM recipe_filter_categories WHERE name = "Flavors"), (SELECT id FROM recipe_tags WHERE name = "Southeast Asian")),


((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Casserole")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Pasta")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Rice Dish")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Meat and Starch")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Soup")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Stew")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Stir Fry")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Snack")),
((SELECT id FROM recipe_filter_categories WHERE name = "Dishes"), (SELECT id FROM recipe_tags WHERE name = "Other")),

((SELECT id FROM recipe_filter_categories WHERE name = "Other"), (SELECT id FROM recipe_tags WHERE name = "Under 30")),
((SELECT id FROM recipe_filter_categories WHERE name = "Other"), (SELECT id FROM recipe_tags WHERE name = "Vegetarian")),
((SELECT id FROM recipe_filter_categories WHERE name = "Other"), (SELECT id FROM recipe_tags WHERE name = "Spicy")),
((SELECT id FROM recipe_filter_categories WHERE name = "Other"), (SELECT id FROM recipe_tags WHERE name = "Component"))