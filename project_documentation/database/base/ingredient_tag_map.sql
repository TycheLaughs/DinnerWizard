DELETE FROM `ingredient_tag_map` WHERE 1;

INSERT INTO `dinnerwizard`.`ingredient_tag_map` (`ingredientID`, `tagID`) VALUES 

-- Alternate Protein
((SELECT id FROM ingredients WHERE ingredients.name = "Beans"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cheese"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Eggs"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Lentils"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Tofu"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Peanuts"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Peanut Butter"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Slivered Almonds"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),
((SELECT id FROM ingredients WHERE ingredients.name = "Chickpeas"),(SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Alternate Protein")),

-- Meat
((SELECT id FROM ingredients WHERE ingredients.name = "Chicken"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Beef"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Ground Beef"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Ground Lamb"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Ground Pork"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Ground Turkey"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Lamb"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pork"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Meat")),

-- Seafood
((SELECT id FROM ingredients WHERE ingredients.name = "Fish"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Seafood")),
((SELECT id FROM ingredients WHERE ingredients.name = "Imitation Crabmeat"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Seafood")),
((SELECT id FROM ingredients WHERE ingredients.name = "Scallops"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Seafood")),
((SELECT id FROM ingredients WHERE ingredients.name = "Shrimp"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Seafood")),

-- Starch
((SELECT id FROM ingredients WHERE ingredients.name = "Bread"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),
((SELECT id FROM ingredients WHERE ingredients.name = "Couscous"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),
((SELECT id FROM ingredients WHERE ingredients.name = "Rice"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),
((SELECT id FROM ingredients WHERE ingredients.name = "Potatoes"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pitas"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),
((SELECT id FROM ingredients WHERE ingredients.name = "Corn"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Starch")),

-- Vegetables
((SELECT id FROM ingredients WHERE ingredients.name = "Brussels Sprouts"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Onion"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Leek"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Scallion"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Shallot"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Spinach"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Asparagus"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Broccoli"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cauliflower"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Zucchini"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Mushrooms"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Bell Peppers"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Green Beans"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pea Pods"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Bean Sprouts"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Bok Choy"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Napa Cabbage"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cabbage"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Hot Peppers"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Kale"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Escarole"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Broccoli Rabe"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Tomatoes"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Acorn Squash"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Hubbard Squash"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pumpkin"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Beets"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Turnips"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Parsnips"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Carrots"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),
((SELECT id FROM ingredients WHERE ingredients.name = "Celery"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Vegetables")),

-- Spices and Herbs
((SELECT id FROM ingredients WHERE ingredients.name = "Garlic"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Caraway Seeds"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cayenne"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Chipotle Powder"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Coriander"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cilantro"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Parsley"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Salt"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Black Pepper"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cumin"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cinnamon"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Rosemary"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Oregano"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Thyme"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Paprika"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Nutmeg"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),
((SELECT id FROM ingredients WHERE ingredients.name = "Basil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Spices and Herbs")),

-- Odds and Ends
((SELECT id FROM ingredients WHERE ingredients.name = "Butter"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cornstarch"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Flour"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Milk"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Sugar"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Water"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Breadcrumbs"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Olive Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Vegetable Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Canola Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Peanut Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Sesame Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Sunflower Oil"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Soy Sauce"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Fish Sauce"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Oyster Sauce"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Anchovy Paste"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Tomato Paste"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cooking sherry"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Rice Vinegar"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Red Wine Vinegar"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Dates"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Dried Figs"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Dried Apricots"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Golden Raisins"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Greek Olives"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Oranges"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pineapple"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),
((SELECT id FROM ingredients WHERE ingredients.name = "Lemon"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Odds and Ends")),

-- Pre-made
((SELECT id FROM ingredients WHERE ingredients.name = "Tomato Sauce"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Broth"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Velveted Meat"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Pressed Tofu"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Cream Sauce"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Roasted Mushrooms"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Roasted Peppers"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made")),
((SELECT id FROM ingredients WHERE ingredients.name = "Roasted Eggplant"), (SELECT id FROM ingredient_tags WHERE ingredient_tags.name = "Pre-Made"))


;