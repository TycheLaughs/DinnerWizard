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
	
	$queryResult = $conn->query("SELECT equipment.id AS equipmentID, equipment.name AS equipmentName FROM equipment");

	// Set up our result array.
	// This will be turned into a JSON object later on and then returned.
	$result = array();
	$result['equipment'] = array();
	
	for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
	{
		$queryResult->data_seek($rowNumber);
		$row = $queryResult->fetch_assoc();
		
		$equipmentID = $row['equipmentID'];
		$equipmentName = $row['equipmentName'];
		
		$result['equipment'][$equipmentID]['id'] = (int)$equipmentID;
		$result['equipment'][$equipmentID]['name'] = $equipmentName;
	}
	
	$conn->close();
	
	$result['equipment'] = array_values($result['equipment']);
	
	// And we're done.
	echo json_encode($result, JSON_PRETTY_PRINT);
?>