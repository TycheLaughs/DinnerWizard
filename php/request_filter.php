<?php
    //This file is probably going to be temporary when i create a get/post response functions in the db_lib class

    namespace db_lib ;
    include 'db_lib.php';

    //$data = json_decode(file_get_contents("php://input"));
    //$request = $data->filter ;
      $request = "FooBar";

    // print_r( $request ) ;

    $temp = new db_lib;
    //$temp->getTables();
    print_r( json_encode( $temp->buildFilterObjects( $request ) ));

    $temp->closeConnection() ;


    //$temp->closeConnection();

?>