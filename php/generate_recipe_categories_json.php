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
	
	$queryResult = $conn->query("SELECT recipe_filter_categories.id AS categoryID,
recipe_filter_categories.name AS categoryName ,
recipe_tags.id AS tagID,
recipe_tags.name AS tagName
FROM recipe_filter_categories 
LEFT JOIN recipe_filter_category_tag_map ON recipe_filter_categories.id = recipe_filter_category_tag_map.filterCategoryID 
LEFT JOIN recipe_tags ON recipe_filter_category_tag_map.tagID = recipe_tags.id");

	// Set up our result array.
	// This will be turned into a JSON object later on and then returned.
	$result = array();
	$result['RecipeTags'] = array();
	
	for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
	{
		$queryResult->data_seek($rowNumber);
		$row = $queryResult->fetch_assoc();
		
		$filterCategoryID = $row['categoryID'];
		$filterCategoryName = $row['categoryName'];
		$tagID = $row['tagID'];
		$tagName = $row['tagName'];
		
		$result['RecipeTags'][$filterCategoryID]['id'] = (int)$filterCategoryID;
		$result['RecipeTags'][$filterCategoryID]['Category'] = $filterCategoryName;
		
		if (!array_key_exists('Contents', $result['RecipeTags'][$filterCategoryID]))
			$result['RecipeTags'][$filterCategoryID]['Contents'] = array();
		
		if ($tagID != null)
		{
			$result['RecipeTags'][$filterCategoryID]['Contents'][$tagID]['id'] = (int)$tagID;
			$result['RecipeTags'][$filterCategoryID]['Contents'][$tagID]['name'] = $tagName;
		}
	}
	
	$conn->close();
	
	$result['RecipeTags'] = array_values($result['RecipeTags']);

	for ($i = 0; $i < count($result['RecipeTags']); $i++)
	{
		$result['RecipeTags'][$i]['Contents'] = array_values($result['RecipeTags'][$i]['Contents']);
	}
	
	// And we're done.
	echo json_encode($result, JSON_PRETTY_PRINT);
?>