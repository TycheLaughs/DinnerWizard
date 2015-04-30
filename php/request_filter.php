<?php
/** request_filter.php
 * Tommy Leedberg
 * Date: 1/27/2015
 * Summary:
 *      This is the request filter php page. It takes a JSON request from the DinnerWizard front end that contains
 *      the following schema
 *              [ "exclusiveIngredients" => true,
 *                "ingredientTags" => [ "id" => id, "name" => name ],
 *                "recipeTags" =>     [ "id" => id, "name" => name ],
 *                "equipment" =>      [ "id" => id, "name" => name ],
 *                "without" =>        [ "id" => id, "name" => name ]];
 *      It will then return a recipe list with the following schema:
 *              "recipes" => [{ "id" => id, "name" => name,
 *                              "prepInst" => inst, "categories"  => [{ tag => : [ id, name, isFilterable ]}],
 *                              "equipment" => [ id, name ],
 *                              "ingredients" => [{ id, name, isOptional, "replaceableWith": [ name ] }]}],
 *
 * TODO: Bring all filters into one page
 * TODO: Add a new field to the JSON request that allows you to specify which request type you are making
 * TODO: Add security measures to prevent SQL Injection into the page
 *
 */

    namespace db_lib ;
    include 'db_lib.php';

    //have to make sure true flag is set otherwise we get a data class rather than an associative array
    $data = json_decode( file_get_contents("php://input"), true ) ;
    $request = $data["filter"] ;

    /*Test Data
     $request = [ "exclusiveIngredients" => true,
                  "ingredientTags" => [ [ "id" => 1, "name" => "Pasta" ],
                                        [ "id" => 2, "name" => "Olive Oil" ],
                                        [ "id" => 3, "name" => "Garlic" ],
                                        [ "id" => 4, "name" => "Cauliflower"]],
                  "recipeTags" => [[ "id" => 7, "name" => "Pasta" ]],
                  "equipment" => [],
                  "without" => []];
    */

    $temp = new db_lib;

    echo json_encode( $temp->buildFilterObjects( $request ), JSON_PRETTY_PRINT ) ;

?>