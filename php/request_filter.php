<?php

    include 'db_lib.php';

    $data = json_decode(file_get_contents("php://input"));
    $request = $data->filter ;

    // print_r( $request ) ;

    $temp = new db_lib;
    //$temp->getTables();
    print_r( json_encode( $temp->buildFilterObjects(  ) ));


    //$temp->closeConnection();

?>