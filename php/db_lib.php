<?php

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
        private $mTable_Equipment           = "equipment" ;
        private $mTable_Error               = "error" ;
        private $mTable_RecipeTags          = "recipe_tags";
        private $mTable_IngredientTags      = "ingredient_tags" ;
        private $mTable_RecipeCategories    = "recipe_filter_categories";
        private $mTable_IngredientTagMap    = "ingredient_tag_map";
        private $mTable_RecipeIngredientMap = "recipe_ingredient_map";
        private $mTable_RecipeEquipmentMap  = "recipe_equipment_map";
        private $mTable_RecipeTagMap        = "recipe_tag_map" ;
        private $mTable_CategoryTagMap      = "recipe_filter_category_tag_map";

        //The most used queries for sustainability and easy formatting
        private $mQuery_SelectAll          = "SELECT * FROM %s";                                                       //SELECT * FROM <tableName>
        private $mQuery_SelectLastID       = "SELECT max(id) FROM %s" ;                                                //SELECT max(id) FROM <tableName>
        private $mQuery_SelectFromTable    = "SELECT %s FROM %s WHERE %s = '%s'";                                      //SELECT <attribute> FROM <table> WHERE <attribute> = <value>
        private $mQuery_InsBaseTable       = "INSERT INTO %s( id, name ) VALUES( '%d', '%s' ) ";                       //INSERT INTO <table>( id, name ) VALUES( <id>, <name> )
        private $mQuery_InsTagsTable       = "INSERT INTO tags( id, name, isFilterable ) VALUES '%s', '%s' )" ;        //INSERT INTO tags( id, name, isFilterable ) VALUES( <id>, <name>, <isFilterable> ) ;
        private $mQuery_InsRecipesTable    = "INSERT INTO recipes( id, name, prepInst ) VALUES ( '%d', '%s', '%s' ) "; //INSERT INTO recipes( id, name, prepInst ) VALUES( <id>, <name>, <prepInst> )
        private $mQuery_InsMapTable        = "INSERT INTO %s( id, id) VALUES( '%d', '%d')";                            //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;

        //Going to use these data members at some point
        //private $filterObj = [] ;
        //private $updateObj = [] ;

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

        /**
         * Summary:
         *      The only thing the destructor really needs to do is close the connection to make sure we are
         *      being clean.
         */
        function  __destruct()
        {
            $this->closeConnection() ;
        }

        /**
         * Summary:
         *      Close the connection that was opened.
         */
        public function closeConnection()
        {
            $this->conn->close();
        }

        /**
         * Summary:
         *      This method will call the filter method to build an array of objects that can be passed to the
         *      create JSON method so we can return the correct recipes
         * @param $request
         *      The request containing the filter parameters that should be in the JSON format that is specified in
         *      the filter_request_schema file in project_documentation/schemas
         * @return array
         *      This is going to be a JSON encoded array that will match the required filter_response_schema that is
         *      provided in project_documentation/schemas
         */
        public function buildFilterObjects( $request )
        {

            //Test Filter request to be removed when we get the request working from the front end
            $testFilter = ["ingredientTags" => [["id" => 4, "name" => "eggs"]],
                           "recipeTags" => "",
                           "equipment" => [["id" => 9, "name" => "frying pan"]],
                           "without" => [["id" => 1, "name" => "spicy", "group" => "recipes"], [ "id" => 2, "name" => "seafood", "group" => "ingredients" ]]] ;

            $recipeList = [];
            $ingredientFilter = $testFilter["ingredientTags"] ;
            $recipeFilter = $testFilter["recipeTags"] ;
            $equipmentFilter = $testFilter["equipment"] ;
            $withoutFilter = $testFilter["without"] ;


            //If we have there has been a filter provided in each of the fields try to generate an array of the correct
            //recipes, if you can then add that array to the recipeList.
            if( $ingredientFilter != "" AND ( $result = $this->filter( $ingredientFilter, "ingredientTags" ) ) != NULL )
            {
                    array_push( $recipeList, $result );
            }
            if( $recipeFilter != "" AND ( $result = $this->filter( $recipeFilter, "recipeTags" ) ) != NULL)
            {
                array_push( $recipeList, $result );
            }
            if( $equipmentFilter != "" AND ( $result = $this->filter( $equipmentFilter, "equipment" ) ) != NULL )
            {
                array_push( $recipeList, $result );
            }
            if( $withoutFilter != "" AND ( $result = $this->filter( $withoutFilter, "without" ) ) != NULL )
            {
                array_push( $recipeList, $result );
            }

            return json_encode( $recipeList ) ;

        }

        /**
         * @param $objFilterObj
         * @param $strFilterGroup
         * @return array|int
         */
        private function filter( $objFilterObj, $strFilterGroup )
        {

            $recipeList   = "" ;
            $mapTable     = "" ;
            $mapAttribute = "" ;

            switch( $strFilterGroup )
            {
                case "ingredientTags":
                {
                    $mapTable = $this->mTable_RecipeIngredientMap ;
                    $mapAttribute = "ingredientID" ;
                    break ;
                }
                case "recipeTags":
                {
                    //do nothing because we have a list of recipe id's so we can just go straight to the recipes table
                    break ;
                }
                case "equipment":
                {
                    $mapTable = $this->mTable_RecipeEquipmentMap ;
                    $mapAttribute = "equipmentID" ;
                    break ;
                }
                case "without":
                {
                    //this is a special case i need to pull the group value out to get the appropriate tabless
                    break ;
                }
                default:
                {
                    //log an error
                    return -1 ;
                }
            }

            foreach( $objFilterObj as $item )
            {

                if( $item == "" )
                {
                    continue ;
                }
                //We already have the id of a recipe while filtering so there is no need to go to a map table
                if( $strFilterGroup != "recipes" )
                {

                    //SELECT recipeID FROM $mapTable WHERE $mapApptribute = $item["id"]
                    $recipeIDList = $this->conn->query( $temp = sprintf( $this->mQuery_SelectFromTable, "recipeID", $mapTable, $mapAttribute, $item["id"] ) );

                    if( $recipeIDList != true OR $recipeIDList != false )
                    {
                        $recipeIDList->data_seek( 0 );
                    }

                    while( $row = $recipeIDList->fetch_assoc() )
                    {

                        //SELECT name FROM recipes WHERE id = row
                        //TODO: When queries successfully run a query but there are no results the return value is just true. We need to check for this in the future.
                        $recipe = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "name", $this->mTable_Recipes, "id", $row["recipeID"] ) )->fetch_row()[0];
                        if( $recipeList == "" )
                        {
                            $recipeList = $recipe ;
                        }
                        else
                        {
                            $recipeList = $recipeList . ',' . $recipe  ;
                        }
                    }
                }
                else
                {
                    //SELECT name FROM recipes WHERE id = $item["id"]
                    $recipe = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "name", $this->mTable_Recipes, "id", $item["id"] ) )->fetch_row()[0];
                    if( $recipeList == "" )
                    {
                        $recipeList = $recipe ;
                    }
                    else
                    {
                        $recipeList = $recipeList . ',' . $recipe  ;
                    }
                }
            }

            return $recipeList ;

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
         *      True if we found the value and false if we did not
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

<!-- PROTOTYPE UPDATE FUNCTIONS
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
                    $this->conn->query( sprintf( $this->mQuery_InsRecipesTable, $intLastID, $objDinnerWizard["name"], $objDinnerWizard["prepInst"] ) );
                }
                elseif( $strTableName == $this->mTable_Tags )
                {
                    //INSERT INTO tags( id, name, isFilterable ) VALUES( $intLastID, $objDinnerWizard["name"], $objDinnerWizard["isFilterable"] ) ;
                    $this->conn->query( sprintf( $this->mQuery_InsTagsTable, $intLastID, $objDinnerWizard["name"], $objDinnerWizard["isFilterable"] ) );
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

            $intNewItemID = $objDinnerWizard["id"];
            $strTableToMap = '';
            $objListOfItemsToMap = [ ];

            switch( $strTableName )
            {
                case "ingredients":
                {
                    $strTableToMap = $this->mTable_IngredientTagMap;
                    $objListOfItemsToMap = $objDinnerWizard["tags"];
                    break;
                }
                case "tags":
                {

                    if( $objDinnerWizard["recipes"] )
                    {
                        $strTableToMap = $this->mTable_RecipeTagMap;
                        $objListOfItemsToMap = $objDinnerWizard["recipes"];
                    }
                    else
                    {
                        $strTableToMap = $this->mTable_IngredientTagMap;
                        $objListOfItemsToMap = $objDinnerWizard["ingredients"];
                    }
                    break;
                }
                case "equipment":
                {
                    $strTableToMap = $this->mTable_RecipeEquipmentMap;
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
//                $recipe = [ "id" => 0, "name" => "burrito", "prepInst" => "Cook the rice, saute the vegetables, cook the chicken, microwave the wrap for 10 seconds.",
//                    "tags" => [ [ "id" => 2, "name" => "spicy", "isFilterable" => true ], [ "id" => 4, "name" => "Mexican", "isFilterable" => true ] ],
//                    "equipment" => [ "id" => 0, "name" => "stove" ],
//                    "ingredients" => [ "id" => 0, "name" => "chicken", "isOptional" => TRUE, "replaceableWith" => [ "turkey", "steak", "pork" ] ] ];

                $objListOfItemsToMap = $objDinnerWizard["tags"];
                $strBaseTable = $this->mTable_Tags ;
                $strTableToMap = $this->mTable_RecipeTagMap;
                $updateRecipes = true;

                while( $updateRecipes == true )
                {
                    if( $strTableToMap == $this->mTable_RecipeEquipmentMap )
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

                    if( $strTableToMap == $this->mTable_RecipeTagMap )
                    {
                        $objListOfItemsToMap = $objDinnerWizard["ingredients"];
                        $strTableToMap = $this->mTable_IngredientRecipeMap;
                    }
                    else
                    {
                        $objListOfItemsToMap = $objDinnerWizard["equipment"];
                        $strTableToMap = $this->mTable_RecipeEquipmentMap;
                    }
                }
            }
        }
-->
