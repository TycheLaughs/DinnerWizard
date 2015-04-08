<?php

    namespace db_lib ;
    include 'db_lib.php';

    $temp = new db_lib;

    //echo "<div id='recipeFilter' style='visibility:hidden'>" ;
    echo json_encode( $temp->getEquipment(), JSON_PRETTY_PRINT ) ;
    //echo "</div>";

    ?>