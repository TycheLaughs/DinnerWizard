<?php

    /**
    * This is just a sandbox to play around with the php server side stuff.
    */

    //Create a connection to the SQL Server and the database we want to use
    $conn = new mysqli( 'localhost', 'root', '', 'dinnerwizard') ;

    //Create any connection error warnings
    if( $conn->connect_error)
    {
        //set error information for debugging if we fail to connect
        die( "Failed to connect to server with error: " . $this->connection->connect_error ) ;
    }

    //Test query to get ingredients
    $ingredients = $conn->query( 'SELECT * FROM ingredients' ) ;

    //set the row pointer to the first row
    $ingredients->data_seek(0) ;

    //traverse the list of ingredients and output some sample information
    while ($row = $ingredients->fetch_assoc() )
    {
        print 'id = ' . $row['ingredientID']  . ' name = ' . $row['ingredientName'] . '</br>';

    }

    //Get the last id for ingredients and for recipe so we can build our map
    $lastIngredientID = $conn->query( 'SELECT max(ingredientID) FROM ingredients' ) ;
    $lastRecipeID = $conn->query( 'SELECT max(recipeID) FROM recipes' ) ;

    $lastIngredientID->data_seek(0) ;
    $lastRecipeID->data_seek(0) ;

    echo 'IngredientID: ' . $lastIngredientID->fetch_row()[0] ;
    echo 'RecipeID: ' . $lastRecipeID->fetch_row()[0]  ;



?>