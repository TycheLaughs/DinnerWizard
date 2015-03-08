<?php

    $temp = new db_lib;
    $temp->getTables();
    $temp->closeConnection();

    class db_lib
    {

        //database connection information
        private $conn = NULL;
        private $_HOST = 'localhost';
        private $_USERNAME = 'tommy';
        private $_PASSWORD = 'p@ssw0rd';
        private $_DATABASE = 'dinnerwizard';

        //table defines
        private $mTable_Ingredients         = "ingredients";
        private $mTable_Recipes             = "recipes";
        private $mTable_Tags                = "tags" ;
        private $mTable_Equipment           = "equipment" ;
        private $mTable_IngredientTagMap    = "ingredient_tag_map";
        private $mTable_IngredientRecipeMap = "ingredient_recipe_map";
        private $mTable_RecipeEquipmentMap  = "recipe_equipment_map";
        private $mTable_RecipeTagMap        = "recipe_tag_map" ;


        //The most used queries for sustainability and easy formatting
        private $mQuery_SelectAll          = "SELECT * FROM %s";                                                       //SELECT * FROM <tableName>
        private $mQuery_SelectLastID       = "SELECT max(id) FROM %s" ;                                                //SELECT max(id) FROM <tableName>
        private $mQuery_SelectFromTable    = "SELECT %s FROM %s WHERE %s = '%s'";                                      //SELECT <attribute> FROM <table> WHERE <attribute> = <value>
        private $mQuery_SelectFromMapTable = "SELECT %s FROM %s WHERE %s = '%s' AND %s = '%s'";                        //SELECT <attribute> FROM <table> WHERE <attribute> = <value> AND <attribute> = <value>
        private $mQuery_InsBaseTable       = "INSERT INTO %s( id, name ) VALUES( '%d', '%s' ) ";                       //INSERT INTO <table>( id, name ) VALUES( <id>, <name> )
        private $mQuery_InsTagsTable       = "INSERT INTO tags( id, name, isFilterable ) VALUES '%s', '%s' )" ;        //INSERT INTO tags( id, name, isFilterable ) VALUES( <id>, <name>, <isFilterable> ) ;
        private $mQuery_InsRecipesTable    = "INSERT INTO recipes( id, name, prepInst ) VALUES ( '%d', '%s', '%s' ) "; //INSERT INTO recipes( id, name, prepInst ) VALUES( <id>, <name>, <prepInst> )
        private $mQuery_InsMapTable        = "INSERT INTO %s( id, id) VALUES( '%d', '%d')";                            //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;


        /**
         * Summary:
         *      Create a connection to the SQL Server and the database we want to use.
         */
        public function __construct()
        {
            $this->conn = new mysqli( $this->_HOST, $this->_USERNAME, $this->_PASSWORD, $this->_DATABASE );

            //Create any connection error warnings
            if( $this->conn->connect_error )
            {
                //set error information for debugging if we fail to connect
                die( "Failed to connect to server with error: " . $this->conn->connect_error );
            }

        }

        public function closeConnection()
        {
            $this->conn->close();
        }

        /**
         * Summary:
         *      Get the information from the user and then use it to update the appropriate tables
         */
        public function update()
        {

            /*
            "recipes" :
              [{
                  "id"          : id,
                  "name"        : name,
                  "prepInst"    : inst,
                  "tags"        : [ id, name ],
                  "equipment"   : [ id, name ],
                  "ingredients" : [{ id, name, isOptional, replaceableWith [ name ] }],
              }],
              "ingredients" :
              [{
                  "id"   : id,
                  "name" : name,
                  "tags" : [ name ]
              }],
              "recipeTags" :
              [{
                  "id"      : id,
                  "name"    : name,
                  "recipes" : [ name ]
              }],
            "equipment" :
              [{
                  "id"      : id
                  "name"    : name,
                  "recipes" : [ name ]
              }],
              "ingredientTags" :
              [{
                  "id"          : id,
                  "name"        : name,
                  "ingredients" : [ name ]
              }]
            */

            //get what's being updated from the front end
            //$strRecipeName = $_POST["recipeName"];
            //$strPrepInst = $_POST["prepInst"];
            //$strTags = $_POST["tags"];
            //$strIngredients = $_POST["ingredients"];


            $recipe = [ "id" => 0, "name" => "burrito", "prepInst" => "Cook the rice, saute the vegetables, cook the chicken, microwave the wrap for 10 seconds.",
                        "tags" => [ [ "id" => 2, "name" => "spicy", "isFilterable" => true ], [ "id" => 4, "name" => "Mexican", "isFilterable" => true ] ],
                        "equipment" => [ "id" => 0, "name" => "stove" ],
                        "ingredients" => [ "id" => 0, "name" => "chicken", "isOptional" => TRUE, "replaceableWith" => [ "turkey", "steak", "pork" ] ] ];
            $ingredient1 = [ "id" => 0, "name" => "chicken", "tags" => [ ] ];
            $ingredient2 = [ "id" => 1, "name" => "turkey", "tags" => [ "turkey" ] ];
            $ingredient3 = [ "id" => 2, "name" => "steak", "tags" => [ "cow", "read meat" ] ];
            $ingredient4 = [ "id" => 3, "name" => "pork", "tags" => [ ] ];
            $equipment = [ "id" => 0, "name" => "stove", "recipes" => [ "burrito" ] ];
            $recipeTags = [ "id" => 2, "name" => "spicy", "isFilterable" => true, "recipes" => [ "burrito" ] ];
            $ingredientTags1 = [ "id" => 0, "name" => "read meat", "isFilterable" => false, "ingredients" => [ "steak" ] ];
            $ingredientTags2 = [ "id" => 1, "name" => "cow", "isFilterable" => true, "ingredients" => [ "steak" ] ];
            $ingredientTags3 = [ "id" => 3, "name" => "turkey", "isFilterable" => true, "ingredients" => [ "turkey" ] ];

            //if being passed just a new ingredient
            $this->updateBaseObjects( $ingredient1, $this->mTable_Ingredients );
            $this->updateBaseObjects( $ingredient2, $this->mTable_Ingredients );
            $this->updateBaseObjects( $ingredient3, $this->mTable_Ingredients );
            $this->updateBaseObjects( $ingredient4, $this->mTable_Ingredients );
            $this->updateBaseObjects( $equipment, $this->mTable_Equipment );
            $this->updateBaseObjects( $recipeTags, $this->mTable_Tags  );
            $this->updateBaseObjects( $ingredientTags1, $this->mTable_Tags );
            $this->updateBaseObjects( $ingredientTags2, $this->mTable_Tags );
            $this->updateBaseObjects( $ingredientTags3, $this->mTable_Tags );
            $this->updateBaseObjects( $recipe, $this->mTable_Tags );

        }

        /**
         * Summary:
         *      Try to update the database with the row for the given objType.
         *
         * @param $objDinnerWizard
         *      The object we want to put into the database
         *
         * @param $strTableName
         *      The table we want to update
         */
        function updateBaseTables( $objDinnerWizard, $strTableName )
        {

            //If the item is already in the database make sure that our id's match otherwise add a new item to the database
            if( $this->exists( $objDinnerWizard["name"], $strTableName, "name" ) )
            {

                //SELECT id FROM $strTableName WHERE name = $objDinnerWizard["name"]
                $intObjID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', $strTableName, 'name', $objDinnerWizard["name"] ) )->fetch_row()[0];

                if( $objDinnerWizard["id"] != $intObjID )
                {
                    //Provided ID and equipment ID don't match, log an error and return
                    return;
                }

                //Item exists already so do nothing
                return;

            }
            else
            {

                //get the last id from the table
                $intLastID = $this->conn->query( sprintf( $this->mQuery_SelectLastID, $strTableName ) )->fetch_row()[0];

                //if there is no id available we must be adding the first row so set intLastID to 0
                if( $intLastID == NULL )
                {
                    $intLastID = 0;
                }
                else
                {
                    $intLastID++;
                }

                //The recipe and tags tables have extra attributes so do something else with it
                if( $strTableName == $this->mTable_Recipes )
                {
                    //INSERT INTO recipes( id, name, prepInst ) VALUES( $intLastID, $objDinnerWizard["name"], $objDinnerWizard["prepInst"] )
                    $this->conn->query( sprintf( $this->$mQuery_InsRecipesTable, $intLastID, $objDinnerWizard["name"], $objDinnerWizard["prepInst"] ) );
                }
                elseif( $strTableName == $this->mTable_Tags )
                {
                    //INSERT INTO tags( id, name, isFilterable ) VALUES( $intLastID, $objDinnerWizard["name"], $objDinnerWizard["isFilterable"] ) ;
                    $this->conn->query( sprintf( $this->$mQuery_InsTagsTable, $intLastID, $objDinnerWizard["name"], $objDinnerWizard["isFilterable"] ) );
                }
                else
                {
                    //INSERT INTO $strTableName( id, name ) VALUES( $intIngredientID, $ingredient )
                    $this->conn->query( sprintf( $this->mQuery_InsBaseTable, $strTableName, $intLastID, $objDinnerWizard["name"] ) );
                }

            }

            return;
        }

        /**
         * Summary:
         *      Update the mapping tables with the appropriate id's
         *
         * @param $objDinnerWizard
         *      The object we are adding to the database
         * @param $strTableName
         *      The name of the base table we are updating
         */
        function updateMapTables( $objDinnerWizard, $strTableName )
        {

//            "ingredients" :
//              [{
//            "id"   : id,
//                  "name" : name,
//                  "tags" : [ name ]
//              }],

            $intNewItemID = $objDinnerWizard["id"];
            $strTableToMap = '';
            $objListOfItemsToMap = [ ];

            switch( $strTableName )
            {
                case "ingredients":
                {
                    $strTableToMap = $this->$mTable_IngredientTagMap;
                    $objListOfItemsToMap = $objDinnerWizard["tags"];
                    break;
                }
                case "tags":
                {

                    if( $objDinnerWizard["recipes"] )
                    {
                        $strTableToMap = $this->$mTable_RecipeTagMap;
                        $objListOfItemsToMap = $objDinnerWizard["recipes"];
                    }
                    else
                    {
                        $strTableToMap = $this->$mTable_IngredientTagMap;
                        $objListOfItemsToMap = $objDinnerWizard["ingredients"];
                    }
                    break;
                }
                case "equipment":
                {
                    $strTableToMap = $this->$mTable_RecipeEquipmentMap;
                    $objListOfItemsToMap = $objDinnerWizard["recipes"];
                    break;
                }
                case "recipes":
                {
                    //recipes map to all the tables so leave this empty for now
                    break;
                }
                default:
                {
                    //log error because the wrong table type was passed in
                }
            }

            if( $strTableName != "recipes" )
            {

                foreach( $objListOfItemsToMap as $item )
                {

                    //SELECT id FROM $strTableName WHERE name = <value>
                    $itemID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "id", $strTableName, "name", $item["name"] ) );

                    //if we found the item in a base table then we just have to update the map table, otherwise we have to add the item to the base table as first
                    if( $itemID != NULL )
                    {
                        //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;
                        $this->conn->query( sprintf( $this->mQuery_InsMapTable, $strTableToMap, $intNewItemID, $itemID ) );
                    }
                    else
                    {
                        if( $strTableName == "tags" )
                        {
                            $tempObj = [ "name" => $item["name"], "isFilterable" => false ] ;
                        }
                        else
                        {
                            $tempObj = [ "name" => $item["name"] ] ;
                        }

                        $this->updateBaseTables( $tempObj, $strTableName ) ;
                        $this->updateMapTables( $objDinnerWizard, $strTableName ) ;
                    }
                }
            }
            else
            {
                $recipe = [ "id" => 0, "name" => "burrito", "prepInst" => "Cook the rice, saute the vegetables, cook the chicken, microwave the wrap for 10 seconds.",
                    "tags" => [ [ "id" => 2, "name" => "spicy", "isFilterable" => true ], [ "id" => 4, "name" => "Mexican", "isFilterable" => true ] ],
                    "equipment" => [ "id" => 0, "name" => "stove" ],
                    "ingredients" => [ "id" => 0, "name" => "chicken", "isOptional" => TRUE, "replaceableWith" => [ "turkey", "steak", "pork" ] ] ];

                $objListOfItemsToMap = $objDinnerWizard["tags"];
                $strBaseTable = $this->mTable_Tags ;
                $strTableToMap = $this->$mTable_RecipeTagMap;
                $updateRecipes = true;

                while( $updateRecipes == true )
                {
                    if( $strTableToMap == $this->$mTable_RecipeEquipmentMap )
                    {
                        $updateRecipes = false ;
                    }

                    foreach( $objListOfItemsToMap as $item )
                    {

                        //SELECT id FROM $strTableName WHERE name = <value>
                        $itemID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "id", $strBaseTable, "name", $item["name"] ) );

                        //if we found the item in a base table then we just have to update the map table, otherwise we have to add the item to the base table as first
                        if( $itemID != NULL )
                        {
                            //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;
                            $this->conn->query( sprintf( $this->mQuery_InsMapTable, $strTableToMap, $intNewItemID, $itemID ) );
                        }
                        else
                        {
                            if( $strBaseTable == "tags" )
                            {
                                $tempObj = [ "name" => $item["name"], "isFilterable" => false ];
                            }
                            else
                            {
                                $tempObj = [ "name" => $item["name"] ];
                            }

                            $this->updateBaseTables( $tempObj, $strBaseTable );
                            $this->updateMapTables( $objDinnerWizard, $strTableName );
                        }
                    }

                    if( $strTableToMap == $mTable_RecipeTagMap )
                    {
                        $objListOfItemsToMap = $objDinnerWizard["ingredients"];
                        $strTableToMap = $this->$mTable_IngredientRecipeMap;
                    }
                    else
                    {
                        $objListOfItemsToMap = $objDinnerWizard["equipment"];
                        $strTableToMap = $this->$mTable_RecipeEquipmentMap;
                    }
                }
            }
        }

        private function filter( $ObjFilters )
        {

        }
        /**
         * Summary:
         *      check to see if the given value exists in the given table, by default we check for ids but
         *      you can also check by name.
         *
         * @param $strValue
         *      The value we are checking to see if exists in the given table.
         * @param $strTable
         *      The table we should be checking.
         * @param string $strIdentifier
         *      An identifier telling us if we are checking for an id or a name.
         *
         * @return bool
         *      True if we found the value and false if we didn't
         */
        private function exists( $strValue, $strTable, $strIdentifier = "id" )
        {


            if( $strIdentifier == 'id' )
            {
                //SELECT * from $strTable WHERE id = '$strValue'
                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'id', $strTable, 'id', $strValue );
            }
            else
            {
                //SELECT * from $strTable WHERE name = '$strValue'
                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'name', $strTable, 'name', $strValue );
            }

            //If the query returns a value then return true otherwise return false
            if( $this->conn->query( $strQuery )->num_rows > 0 )
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
    }

?>

<!-- Original Table Building Functions -- 3/3/2015
/**
 * Summary:
 *      Queries the tables in the database and builds a json object representation of their contents.
 *      The schema for the JSON object can be found at project_documentation/json_schema.json
 */
public function getTables()
{

    /**
     * Because this is only a class project these tables are not going to be very large it makes sense
     * to bring all of the tables in, send them to the front end as a JSON object, and then filter them there.
     * This will be a lot faster then querying the database for the information whenever we need it.
     * If this was for a release project where the database could grow exponentially you would keep
     * everything in the database and then write queries so you are only sending small pieces of data to the
     * browser.
     */

    //Get the main tables we need to build our json object
    $recipesTable = $this->conn->query( sprintf( $this->mQuery_SelectAll, 'recipes' ) );               //id,name,prepInst
    $ingredientsTable = $this->conn->query( sprintf( $this->mQuery_SelectAll, 'ingredients' ) );           //id,name
    $ingredientRecipeMapTable = $this->conn->query( sprintf( $this->mQuery_SelectAll, 'ingredient_recipe_map' ) ); //ingredientID, recipeID


    //Set everyone to their first rows
    $ingredientRecipeMapTable->data_seek( 0 );
    $recipesTable->data_seek( 0 );
    $ingredientsTable->data_seek( 0 );

    $arRecipes = $this->buildObject( $recipesTable, 'recipes', $ingredientRecipeMapTable );
    $arIngredients = $arRecipes = $this->buildObject( $ingredientsTable, 'ingredients', $ingredientRecipeMapTable );

    print_r( $arRecipes );
    //$arDinnerWizard = [ $arRecipes, $arIngredients ];

    //$this->buildJSONObject( $arDinnerWizard );

}

private function buildObject( $baseTable, $strTable, $ingredientRecipeMapTable )
{

    //Storeage space for all of the recipies
    $arObject = [ ];

    //Determin if we're adding a recipe or an ingredient
    if( $strTable == 'recipes' )
    {
        $strMapTable = 'recipe_tag_map';
        $strAttrToMatch = 'recipeID';
    }
    else
    {
        $strMapTable = 'ingredient_tag_map';
        $strAttrToMatch = 'ingredientID';
    }

    while( $row = $baseTable->fetch_assoc() )
    {

        //reset the arrays temporary array to empty
        $strTemp = '';

        //get the recipe name and save it's id to find tags
        $arItem = [ 'name' => $row['name'] ];

        if( $strTable == 'recipes' )
        {
            $arItem['prepIns'] = $row['prepInst'];
        }

        $intIdToMatch = $row['id'];


        while( $rowMap = $ingredientRecipeMapTable->fetch_assoc() )
        {

            //SELECT name FROM ingredients WHERE id = 'ingredientID'
            $tempTable = $this->conn->query( $this->mQuery_SelectFromTable, 'name', $strTable, 'id', $rowMap[ $strAttrToMatch ] );


            while( $rowIngredientOrRecipe = $tempTable->fetch_assoc() )
            {
                $strTemp = "\"" . $strTemp . $rowIngredientOrRecipe['name'] . "\",";
            }

        }

        //get rid of the last comma
        rtrim( $strTemp, "," );


        //update the recipe array with the tag's
        $arRecipe['ingredients'] = $strTemp;

        array_push( $arRecipes, $arRecipe );
        //get all of the tagID's that are associated with this recipe
        //SELECT tagID FROM mapTable WHERE attributeToMatch = '$intIdToMatch' );
        $rsltMatchingIDs = $this->conn->query( sprintf( $this->$mQuery_SelectFromTable, 'tagID', $strMapTable, $strAttrToMatch, $intIdToMatch ) );
        $rsltMatchingIDs->data_seek( 0 );

        while( $rowMap = $rsltMatchingIDs->fetch_assoc() )
        {

            //SELECT name FROM tags WHERE id = 'tagID'
            $tempTable = $this->conn->query( $this->$mQuery_SelectFromTable, 'name', 'tags', 'id', $rowMap['tagID'] );
            $tempTable->data_seek( 0 );

            while( $rowTag = $tempTable->fetch_assoc() )
            {
                $strTemp = "\"" . $strTemp . $rowTag['name'] . "\",";
            }

        }

        //get rid of the last comma
        rtrim( $strTemp, "," );

        //Add all of the tags to the array
        $arItem['tags'] = $strTemp;

        print_r( $arItem );
        array_push( $arObject, $arItem );
    }

    print_r( $arObject );

    return $arObject;

}

       /**
         * Summary:
         *      Update the database tables, we will not always be updating the recipe table because
         *      not every ingredient will need belong to a recipe but every recipe will require at least 1 ingredient.
         *
         * @param $strRecipeName
         *      The recipe name we are going to add to or update in the database, value is not required.
         * @param $strPrepInst
         *      The preparation instructions for the recipe the user has submitted
         * @param $strTags
         *      A tag or list of tags we are going to add to or update in the database, value is not required.
         * @param $strIngredients
         *      An ingredient or ist of ingredients we are going to update in the database, we always require at least 1
         *      ingredient.
         */
        private function updateTables( $strRecipeName, $strPrepInst, $strTags, $strIngredients )
        {

            //get the id for the recipe if it exists otherwise generate a new one
            if( $this->exists( $strRecipeName, 'recipes', 'name' ) )
            {
                //SELECT id FROM recipes WHERE recipeName = '$strRecipeName '
                $intRecipeID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'recipes', 'name', $strRecipeName ) )->fetch_row()[0];
            }
            else
            {

                //get the last recipe id
                $intRecipeID = $this->conn->query( 'SELECT max(id) FROM recipes' )->fetch_row()[0];

                if( $intRecipeID == NULL )
                {
                    $intRecipeID = 1;
                }
                else
                {
                    $intRecipeID++;
                }
                //Insert the new recipe in the recipe table with the new last id
                //'INSERT INTO recipes( id, name, prepInst ) VALUES( $intRecipeID, $strRecipeName, $strPrepInst )
                $strQuery = sprintf( $this->mQuery_InsRecipesTableWithID, $intRecipeID, $strRecipeName, $strPrepInst );
                $this->conn->query( $strQuery );

            }

            //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
            if( sizeOf( $strTags ) > 0 )
            {
                $this->updateTags( $strTags, $intRecipeID, "recipes" );
            }


            if( sizeOf( $strIngredients ) > 0 )
            {

                foreach( $strIngredients as $ingredient )
                {

                    //get the id for the ingredient if it exists otherwise generate a new one
                    if( $this->exists( $ingredient, 'ingredients', 'name' ) )
                    {
                        //SELECT id FROM ingredients WHERE ingredientName = '$ingredient'
                        $intIngredientID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'ingredients', 'name', $ingredient ) )->fetch_row()[0];
                    }
                    else
                    {

                        //get the last ingredient id
                        $intIngredientID = $this->conn->query( 'SELECT max(id) FROM ingredients' )->fetch_row()[0];

                        if( $intIngredientID == NULL )
                        {
                            $intIngredientID = 1;
                        }
                        else
                        {
                            $intIngredientID++;
                        }

                        //because there is a new tag we have to insert it into the tag table
                        //INSERT INTO ingredients( id, name ) VALUES( $intIngredientID, $ingredient )
                        $this->conn->query( sprintf( $this->mQuery_InsBaseTableWithID, 'ingredients', $intIngredientID, $ingredient ) );

                    }

                    //TODO: Do we really need tags for ingredients
                    //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
                    //if( sizeOf( $strTags ) > 0 )
                    //{
                    //    $this->updateTags( $strTags, $intIngredientID, "ingredients" );
                    //}

                    if( !$strRecipeName == '' )
                    {

                        //Get the recipeID so we can update the map table
                        //SELECT id FROM recipes WHERE name = '$strRecipeName'
                        $intRecipeID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'recipes', 'name', $strRecipeName ) )->fetch_row()[0];

                        //Find out if this mapping already exists and if it does don't add it
                        //SELECT id FROM ingredient_recipe_map WHERE ingredientID = $intIngredientID AND recipeID = $intRecipeID
                        if( $this->conn->query( sprintf( $this->mQuery_SelectFromMapTable, 'id', 'ingredient_recipe_map', 'ingredientID', $intIngredientID, 'recipeID', $intRecipeID ) ) == NULL )
                        {
                            //INSERT INTO ingredient_recipe_map( ingredientID, recipeID ) VALUES( $intIngredientID, $intRecipeID )
                            $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'ingredient_recipe_map', 'ingredientID', 'recipeID', $intIngredientID, $intRecipeID ) );
                        }

                    }
                }
            }
        }

        /**
         * Summary:
         *      Update the tags and appropriate mapping tables with a new tag or a list of tags.
         *
         * @param $tags
         *      Either an individual tag or a list of tags that need to be added to the tags table.
         * @param $intMapID
         *      The id of the new recipe or Ingredient to be added to the recipe_tag_map or ingredient_tag_Map table.
         * @param $strTable
         *      This is a descriptor to tell us which map table to use, it will either be ingredients or recipes.
         */
        private function updateTags( $tags, $intMapID, $strTable )
        {

            foreach( $tags as $tag )
            {

                //get the id for the tag if it exists otherwise generate a new one
                if( $this->exists( $tag, 'tags', 'name' ) )
                {
                    //SELECT id FROM tags WHERE tagName = $tag
                    $intTagID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'tags', 'name', $tag ) )->fetch_row()[0];
                }
                else
                {

                    //get the last recipe id
                    $intTagID = $this->conn->query( 'SELECT max(id) FROM tags' )->fetch_row()[0];

                    if( $intTagID == NULL )
                    {
                        $intTagID = 1;
                    }
                    else
                    {
                        $intTagID++;
                    }

                    //because there is a new tag we have to insert it into the tag table
                    //INSERT INTO tags( id, name ) VALUES( $intTagID, $tag )
                    $strQuery = sprintf( $this->mQuery_InsBaseTableWithID, 'tags', $intTagID, $tag );
                    $this->conn->query( $strQuery );

                }

                //update either the ingredient or the recipe tag_map tables
                if( $strTable == 'recipes' )
                {

                    //Find out if this mapping already exists and if it does don't add it
                    //SELECT id FROM recipe_tag_map WHERE tagID = $intTagID AND recipeID = $intMapID
                    if( $this->conn->query( sprintf( $this->mQuery_SelectFromMapTable, 'id', 'recipe_tag_map', 'tagID', $intTagID, 'recipeID', $intMapID ) ) == NULL )
                    {
                        //INSERT INTO recipe_tag_map( recipeID, tagID ) VALUES( $intMapID, $intTagID )
                        $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'recipe_tag_map', 'recipeID', 'tagID', $intMapID, $intTagID ) );
                    }

                }
                else
                {
                    //Find out if this mapping already exists and if it does don't add it
                    //SELECT id FROM ingredient_tag_map WHERE <tagID> = $intTagID AND ingredientID = $intMapID
                    if( $this->conn->query( sprintf( $this->mQuery_SelectFromMapTable, 'id', 'ingredient_tag_map', 'tagID', $intTagID, 'ingredientID', $intMapID ) ) == NULL )
                    {
                        //INSERT INTO ingredient_tag_map( ingredientID, tagID ) VALUES( $intMapID,  $intTagID )
                        $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'ingredient_tag_map', 'ingredientID', 'tagID', $intMapID, $intTagID ) );
                    }

                }

            }
        }
-->