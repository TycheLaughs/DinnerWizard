<?php

	// Note: see generate_recipe_json.php for more detailed information about what's going on here.
	// Much of the code is shared but cannot be easily abstracted out.
	header('Content-Type: application/json');
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
	
	if (!$conn->set_charset("utf8mb4")) 
	{
		die("Error loading character set utf8: " . $conn->error);
	}
	
	$queryResult = $conn->query("
	SELECT ingredients.ID AS ingredientID, 
	ingredients.name AS ingredientName,
	ingredient_tags.ID AS tagID,
	ingredient_tags.name AS tagName
	FROM ingredients 
	LEFT JOIN ingredient_tag_map ON ingredients.ID = ingredient_tag_map.ingredientID 
	LEFT JOIN ingredient_tags ON ingredient_tag_map.tagID = ingredient_tags.ID
    ORDER BY ingredientID");

	// Set up our result array.
	// This will be turned into a JSON object later on and then returned.
	$result = array();
	$result['ingredients'] = array();
	
	for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
	{
		$queryResult->data_seek($rowNumber);
		$row = $queryResult->fetch_assoc();
		
		$ingredientID = $row['ingredientID'];
		$ingredientName = $row['ingredientName'];
		$tagID = $row['tagID'];
		$tagName = $row['tagName'];
		
		$result['ingredients'][$ingredientID]['id'] = (int)$ingredientID;
		$result['ingredients'][$ingredientID]['ingredientName'] = $ingredientName;
		
		if (!array_key_exists('tags', $result['ingredients'][$ingredientID]))
			$result['ingredients'][$ingredientID]['tags'] = array();
		
		if ($tagID != null)
		{
			$result['ingredients'][$ingredientID]['tags'][$tagID]['id'] = (int)$tagID;
			$result['ingredients'][$ingredientID]['tags'][$tagID]['name'] = $tagName;
		}
	}
	
	$conn->close();
	
	$result['ingredients'] = array_values($result['ingredients']);

	for ($i = 0; $i < count($result['ingredients']); $i++)
	{
		// Do the same thing for our categories, equipment, and ingredients.
		$result['ingredients'][$i]['tags'] = array_values($result['ingredients'][$i]['tags']);
	}
	
	// And we're done.
	echo json_encode($result, JSON_PRETTY_PRINT);
?>