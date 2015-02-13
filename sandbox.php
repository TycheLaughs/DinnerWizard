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
        echo 'id = ' . $row['ingredientID']  . ' name = ' . $row['ingredientName'] . '\n' ;

    }

?>