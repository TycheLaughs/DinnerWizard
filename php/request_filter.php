<?php
    //This file is probably going to be temporary when i create a get/post response functions in the db_lib class

    namespace db_lib ;
    include 'db_lib.php';

    //have to make sure true flag is set otherwise we get a data class rather than an associative array
    $data = json_decode( file_get_contents("php://input"), true ) ;
    $request = $data["filter"] ;

    $temp = new db_lib;

    //echo "<div id='recipeFilter' style='visibility:hidden'>" ;
    echo json_encode( $temp->buildFilterObjects( $request ), JSON_PRETTY_PRINT ) ;
    //echo "</div>";

?>