<?php

	// No functions--just request the file and the JSON will be generated as a result.
	
	
	
	// Set our header so the browser knows what to expect
	// Also makes the returned JSON object look nice in debugging instead of one long line
	header('Content-Type: application/json');
	
	

	// Set up our connection
	$conn = NULL;
	$_HOST = 'localhost';
	$_USERNAME = 'root';
	$_PASSWORD = '';
	$_DATABASE = 'dinnerwizard';
	
	$conn = new mysqli($_HOST, $_USERNAME, $_PASSWORD, $_DATABASE);

	if ($conn->connect_error)
	{
		die("Failed to connect to server with error: " . $this->conn->connect_error);
	}
	
	// If we don't have this the ° characters in, for example, "heat the oven to 400°", will cause json_encode to just fail silently.
	if (!$conn->set_charset("utf8mb4")) 
	{
		die("Error loading character set utf8: " . $conn->error);
	}
	
	// Get our query.
	// This monster gets all the information about recipes that we could possibly need
	// A lot of it is redundant, however, because of the massive number of left joins.
	// Luckily because we convert the results into an associative array later on we don't have to care much.
	$queryResult = $conn->query("
SELECT recipes.ID AS recipeID, 
recipes.name AS recipeName, 
recipes.prepInst, 
recipe_ingredient_map.ingredientID AS ingredientID, 
ingredients1.name AS ingredientName, 
recipe_ingredient_map.isOptional AS isOptional, 
recipe_replaceable_ingredient_map.replaceableIngredientID AS replaceableIngredientID, 
ingredients2.name AS replaceableIngredientName, 
recipe_ingredient_map.ratio AS ratio,
equipment.ID AS equipmentID, 
equipment.name AS equipmentName, 
recipe_tags.ID AS tagID, 
recipe_tags.name AS tagName
FROM recipes 
LEFT JOIN recipe_ingredient_map	ON recipes.ID = recipe_ingredient_map.recipeID 
LEFT JOIN recipe_replaceable_ingredient_map ON recipes.ID = recipe_replaceable_ingredient_map.recipeID AND recipe_ingredient_map.ingredientID = recipe_replaceable_ingredient_map.ingredientID
LEFT JOIN ingredients AS ingredients1 ON recipe_ingredient_map.ingredientID = ingredients1.ID
LEFT JOIN ingredients AS ingredients2 ON recipe_replaceable_ingredient_map.replaceableIngredientID = ingredients2.ID
LEFT JOIN recipe_equipment_map 	ON recipes.ID = recipe_equipment_map.recipeID
LEFT JOIN equipment 		ON equipment.ID = recipe_equipment_map.equipmentID 
LEFT JOIN recipe_tag_map 	ON recipes.ID = recipe_tag_map.recipeID 
LEFT JOIN recipe_tags 		ON recipe_tags.ID = recipe_tag_map.tagID 
ORDER BY recipeID, ingredientID, replaceableIngredientID, equipmentID, tagID
");

	// Set up our result array.
	// This will be turned into a JSON object later on and then returned.
	$result = array();
	
	for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
	{
		// Fetch our data from the result into a bunch of variables for ease of use.
		$queryResult->data_seek($rowNumber);
		$row = $queryResult->fetch_assoc();
		
		$recipeID = $row['recipeID'];
		$recipeName = $row['recipeName'];
		$prepInst = $row['prepInst'];
		$ingredientID = $row['ingredientID'];
		$ingredientName = $row['ingredientName'];
		$ingredientIsOptional = $row['isOptional'];
		$ingredientRatio = $row['ratio'];
		$ingredientReplaceableID = $row['replaceableIngredientID'];
		$ingredientReplaceableName = $row['replaceableIngredientName'];
		$equipmentID = $row['equipmentID'];
		$equipmentName = $row['equipmentName'];
		$tagID = $row['tagID'];
		$tagName = $row['tagName'];
		
		
		// Easy stuff--just insert the recipe's ID, name, and prep instructions into the recipe object.
		$result['recipes'][$recipeID]['id'] = (int)$recipeID;
		$result['recipes'][$recipeID]['name'] = $recipeName;
		$result['recipes'][$recipeID]['prepInst'] = $prepInst;
		
		// For array-like objects this gets a bit trickier.
		// If we haven't yet created the categories, equipment, or ingredients array for this object, create them.
		// This is to prevent PHP from spitting out a bunch of errors later on about accessing undefined indices.
		// We want to ensure that, even if a recipe doesn't use, say, equipment, that there's still an equipment array in there.
		if (!array_key_exists('categories', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['categories'] = array();
		if (!array_key_exists('equipment', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['equipment'] = array();
		if (!array_key_exists('ingredients', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['ingredients'] = array();
		
		// Only add categories to the JSON object if both the ID and the name are non-null.
		// This is to prevent awkward things like "categories": [{"id": null, "name": null}] for recipes with no category.
		// We'd rather just have nothing there than a single entry with nulls in it.
		// The above part checking for existing array keys handles that.
		if ($tagID != null && $tagName != null)
		{			
			$result['recipes'][$recipeID]['categories'][$tagID]['id'] = (int)$tagID;
			$result['recipes'][$recipeID]['categories'][$tagID]['name'] = $tagName;
		}
		// Same as above, but with equipment.
		if ($equipmentID != null && $equipmentName != null)
		{
			$result['recipes'][$recipeID]['equipment'][$equipmentID]['id'] = (int)$equipmentID;
			$result['recipes'][$recipeID]['equipment'][$equipmentID]['name'] = $equipmentName;
		}
		// Finally, with ingredients
		// Though really a recipe with no ingredients would be really strange.
		if ($ingredientID != null && $ingredientName != null)
		{
			$result['recipes'][$recipeID]['ingredients'][$ingredientID]['id'] = (int)$ingredientID;
			$result['recipes'][$recipeID]['ingredients'][$ingredientID]['name'] = $ingredientName;
			$result['recipes'][$recipeID]['ingredients'][$ingredientID]['isOptional'] = (bool)$ingredientIsOptional;
			$result['recipes'][$recipeID]['ingredients'][$ingredientID]['ratio'] = (int)$ingredientRatio;
			
			
			// All that stuff we did above to ensure that we had valid arrays and whatever needs to be repeated here
			// since ingredients have substitutes in the form of arrays.
			if (!array_key_exists('replaceableWith', $result['recipes'][$recipeID]['ingredients'][$ingredientID]))
				$result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'] = array();
			
			if ($ingredientReplaceableID != null && $ingredientReplaceableName != null)
			{
				$result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'][$ingredientReplaceableID]['id'] = (int)$ingredientReplaceableID;
				$result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'][$ingredientReplaceableID]['name'] = $ingredientReplaceableName;
			}
		}
	}
	
			
	// We've gotten all our information. We still need to do a little bit of clean up with it, though
	// But that cleanup doesn't involve the database anymore, so close it just in case.
	$conn->close();
	
	// Turn the result from an associative array of recipeID => recipes into a normal array of recipes.
	$result['recipes'] = array_values($result['recipes']);
	
	for ($i = 0; $i < count($result['recipes']); $i++)
	{
		// Do the same thing for our categories, equipment, and ingredients.
		$result['recipes'][$i]['categories'] = array_values($result['recipes'][$i]['categories']);
		$result['recipes'][$i]['equipment'] = array_values($result['recipes'][$i]['equipment']);
		$result['recipes'][$i]['ingredients'] = array_values($result['recipes'][$i]['ingredients']);
		
		for ($j = 0; $j < count($result['recipes'][$i]['ingredients']); $j++)
		{
			// And finally to our replaceable ingredients
			$result['recipes'][$i]['ingredients'][$j]['replaceableWith'] = array_values($result['recipes'][$i]['ingredients'][$j]['replaceableWith']);
		}
	}
	
	// And we're done.
	echo json_encode($result, JSON_PRETTY_PRINT);

	
	
	
	
	
	
	
	
	
	/*
	Old query, just in case.
	Used when replaceable ingredients were based on categories instead of an array of ingredients.
	
SELECT recipes.recipeID, 
recipes.recipeName, 
recipes.prepInst, 
IngredientList.initialID AS ingredientID, 
IngredientList.initialName AS ingredientName, 
recipe_ingredient_map.isOptional AS isOptional, 
IngredientList.secondaryID AS replaceableID, 
IngredientList.secondaryName AS replaceableName, 
equipment.equipmentID, 
equipment.equipmentName, 
recipe_tags.tagID, 
recipe_tags.tagName
FROM recipes 
LEFT JOIN recipe_ingredient_map	ON recipes.recipeID = recipe_ingredient_map.recipeID 
LEFT JOIN 
(
	SELECT initial_ingredients.ingredientID AS initialID, 
	initial_ingredients.ingredientNAME AS initialName, 
	secondary_ingredients.ingredientID AS secondaryID, 
	secondary_ingredients.ingredientNAME AS secondaryName, 
	ingredient_tags.tagName AS categoryName 
	FROM ingredients AS initial_ingredients 
	LEFT JOIN ingredient_tag_map AS initial_map ON initial_ingredients.ingredientID = initial_map.ingredientID 
	LEFT JOIN ingredient_tag_map AS secondary_map ON initial_map.tagID = secondary_map.tagID 
	LEFT JOIN ingredients AS secondary_ingredients ON secondary_map.ingredientID = secondary_ingredients.ingredientID 
	LEFT JOIN ingredient_tags ON secondary_map.tagID = ingredient_tags.tagID
) AS IngredientList 		ON IngredientList.initialID = recipe_ingredient_map.ingredientID 
LEFT JOIN recipe_equipment_map 	ON recipe_equipment_map.recipeID = recipes.recipeID 
LEFT JOIN equipment 		ON equipment.equipmentID = recipe_equipment_map.equipmentID 
LEFT JOIN recipe_tag_map 	ON recipe_tag_map.recipeID = recipes.recipeID 
LEFT JOIN recipe_tags 		ON recipe_tags.tagID = recipe_tag_map.tagID 
ORDER BY recipeID, tagID, ingredientID, replaceableID, equipmentID
	*/
	
	
	
	
	
?>