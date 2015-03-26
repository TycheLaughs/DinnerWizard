<?php

    namespace db_lib ;
    use mysqli ; //import the mysqli class

    //DATABASE INFORMATION
    define( "_HOST",     "localhost" );
    define( "_USERNAME", "root" );
    define( "_PASSWORD", "" );
    define( "_DATABASE", "dinnerwizard" ) ;

    //TABLE DEFINES
    define( "TABLE_EQUIPMENT",             "equipment" )  ;
    define( "TABLE_ERROR_LOG",             "error_log" );
    define( "TABLE_INGREDIENT_TAG_MAP",    "ingredient_tag_map" );
    define( "TABLE_INGREDIENT_TAGS",       "ingredient_tags" );
    define( "TABLE_INGREDIENTS",           "ingredients" );
    define( "TABLE_RECIPE_EQUIPMENT_MAP",  "recipe_equipment_map" );
    define( "TABLE_RECIPE_CATEGORIES",     "recipe_filter_categories" );
    define( "TABLE_CATEGPRY_TAG_MAP",      "recipe_filter_category_tag_map" );
    define( "TABLE_RECIPE_INGREDIENT_MAP", "recipe_ingredient_map" );
    define( "TABLE_RECIPE_TAG_MAP",        "recipe_tag_map" );
    define( "TABLE_RECIPE_TAGS",           "recipe_tags" );
    define( "TABLE_RECIPES",               "recipes" ) ;

    //ERROR LEVEL DEFINES
    define( "ERROR", "error" ) ;
    define( "INFO",  "information" ) ;
    define( "WARN",  "warning" ) ;

    class db_lib
    {

        //database connection information
        private $conn = NULL;

        //variable to let us know if we have a connection or not
        private $connected = false ;

        //member variable containing the path to the allRecipes.json file
        private $mPath_AllRecipesJSON = "../data/allRecipes.json" ;

        //The most used queries for sustainability and easy formatting
        private $mQuery_SelectAll = "SELECT * FROM %s";                                                       //SELECT * FROM <tableName>
        private $mQuery_SelectLastID = "SELECT max(id) FROM %s";                                                //SELECT max(id) FROM <tableName>
        private $mQuery_SelectFromTable = "SELECT %s FROM %s WHERE %s = '%s'";                                      //SELECT <attribute> FROM <table> WHERE <attribute> = <value>
        private $mQuery_InsBaseTable = "INSERT INTO %s( id, name ) VALUES( '%d', '%s' ) ";                       //INSERT INTO <table>( id, name ) VALUES( <id>, <name> )
        private $mQuery_InsTagsTable = "INSERT INTO tags( id, name, isFilterable ) VALUES '%s', '%s' )";        //INSERT INTO tags( id, name, isFilterable ) VALUES( <id>, <name>, <isFilterable> ) ;
        private $mQuery_InsRecipesTable = "INSERT INTO recipes( id, name, prepInst ) VALUES ( '%d', '%s', '%s' ) "; //INSERT INTO recipes( id, name, prepInst ) VALUES( <id>, <name>, <prepInst> )
        private $mQuery_InsMapTable = "INSERT INTO %s( id, id) VALUES( '%d', '%d')";                            //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;

        //Going to use these data members at some point
        //private $filterObj = [] ;
        //private $updateObj = [] ;

        //An array that will hold all of the recipes in the database -- NOTE: this may be removed after refactoring
        private $mJSON_AllRecipes = [] ;

        /**
         * Summary:
         *      The only thing the constructor does is connects to the database.
         */
        public function __construct()
        {
            $this->dinnerWizardConnect() ;
        }

        /**
         * Summary:
         *      The only thing the destructor really needs to do is close the connection to make sure we are
         *      being clean.
         */
        function  __destruct()
        {
            $this->closeConnection();
        }

        /**
         * Summary:
         *      Connect to the database
         */
        public function dinnerWizardConnect()
        {

            //if we arent already connected then lets get a database connection going
            if( !$this->connected )
            {
                $this->conn = new mysqli( _HOST, _USERNAME, _PASSWORD, _DATABASE );

                //Create any connection error warnings
                if( $this->conn->connect_error )
                {
                    $errorMsg = "Failed to connect to the server with the following error: " . $this->conn->connect_error ;
                    //Print the error to the screen and then log it
                    echo $errorMsg ;
                    $this->logError( $errorMsg, ERROR ) ;
                }

                $this->connected = true;
            }
        }
        /**
         * Summary:
         *      Close the connection if it is still opened.
         */
        public function closeConnection()
        {
            if( $this->connected )
            {
                $this->conn->close();
            }
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

            $recipeList = [ ];

            //Test Filter request to be removed when we get the request working from the front end
            $testFilter = [ "ingredientTags" => [ [ "id" => 4, "name" => "eggs" ] ],
                "recipeTags" => "",
                "equipment" => [ [ "id" => 9, "name" => "frying pan" ] ],
                "without" => [ [ "id" => 1, "name" => "spicy", "group" => "recipes" ], [ "id" => 2, "name" => "seafood", "group" => "ingredients" ] ] ];

            $ingredientFilter = $testFilter["ingredientTags"];
            $recipeFilter = $testFilter["recipeTags"];
            $equipmentFilter = $testFilter["equipment"];
            $withoutFilter = $testFilter["without"];

            //If we have there has been a filter provided in each of the fields try to generate an array of the correct
            //recipes, if you can then add that array to the recipeList.
            if( $ingredientFilter != "" AND ( $result = $this->filter( $ingredientFilter, "ingredientTags" ) ) != NULL )
            {
                array_push( $recipeList, $result );
            }
            if( $recipeFilter != "" AND ( $result = $this->filter( $recipeFilter, "recipeTags" ) ) != NULL )
            {
                array_push( $recipeList, $result );
            }
            if( $equipmentFilter != "" AND ( $result = $this->filter( $equipmentFilter, "equipment" ) ) != NULL )
            {
                array_push( $recipeList, $result );
            }

            //The without filter is a little different, if it finds a recipe it then searches the recipeList and
            //removes it from the list if it is present
            if( $withoutFilter != "" AND ( $result = $this->filter( $withoutFilter, "without" ) ) != NULL )
            {

                foreach( $result as $item )
                {
                    if( ( $key = array_search( $item, $recipeList ) ) != false )
                    {
                        unset( $recipeList[$key] );
                    }
                }

            }

            print_r( $recipeList ) ;

            $this->buildFilterResponse( $recipeList ) ;
            return $recipeList;

        }

        /**
         * Summary:
         *      Take in a filterObject and a filter group and use them to query the database for the appropriate
         *      recipe.
         *
         * @param $objFilterObj
         *      A filter object consisting of either ingredientTags, recipeTags, equipment information, or a without clause
         * @param $strFilterGroup
         *      The group we are filtering on
         * @return array|int
         *      The list of recipe ids that are associated with this particular filter
         */
        private function filter( $objFilterObj, $strFilterGroup )
        {

            $recipeIDList = [ ];
            $mapTable = "";
            $mapAttribute = "";

            switch( $strFilterGroup )
            {
                case "ingredientTags":
                {
                    $mapTable = TABLE_RECIPE_INGREDIENT_MAP ;
                    $mapAttribute = "ingredientID";
                    break;
                }
                case "recipeTags":
                {
                    //do nothing because we have a list of recipe id's so we can just go straight to the recipes table
                    break;
                }
                case "equipment":
                {
                    $mapTable = TABLE_RECIPE_EQUIPMENT_MAP ;
                    $mapAttribute = "equipmentID";
                    break;
                }
                case "without":
                {

                    //Without filtering is a little funky because we could be talking about an ingredient tag or a recipe
                    //tag. To handle this we are going to parse the without list and simply remove the group creating
                    //a recipe or ingredient filter and then call filter again.
                    foreach( $objFilterObj as $withoutFilter )
                    {

                        if( $withoutFilter["group"] == "recipes" )
                        {

                            unset( $withoutFilter["group"] );
                            //when calling filter with withoutFilter must be turned back into an array of id, name objects
                            array_push( $recipeIDList, $this->filter( [$withoutFilter], "recipeTags" ) );

                        }
                        elseif( $withoutFilter["group"] == "ingredients" )
                        {

                            unset( $withoutFilter["group"] );
                            //when calling filter with withoutFilter must be turned back into an array of id, name objects
                            array_push( $recipeIDList, $this->filter( [$withoutFilter], "ingredientTags" ) );

                        }

                    }

                    $objFilterObj = [ ]; //reset the objFilterObj so we skip further parsing and just return what we found
                    break;
                }
                default:
                {
                    //log an error
                    return NULL;
                }
            }

            foreach( $objFilterObj as $item )
            {

                //If we dont have an item there is no reason to keep looping so lets save some time and bounce out
                if( $item == "" )
                {
                    continue;
                }

                //If we aren't filtering on recipes then go to the map table to get the matching recipeID
                if( $strFilterGroup != "recipes" AND $strFilterGroup != "recipeTags" )
                {

                    //SELECT recipeID FROM $mapTable WHERE $mapApptribute = $item["id"]
                    $recipeId = $this->conn->query( $temp = sprintf( $this->mQuery_SelectFromTable, "recipeID", $mapTable, $mapAttribute, $item["id"] ) )->fetch_row()[0] ;

                    //When we run the query we are getting the first row right away because recipes are unique. If the
                    //result is NULL then there is no recipe associated with the current filter
                    if( $recipeId != NULL )
                    {
                        $recipeId ;
                        //Recipes are unique and because we have found one we can add it to our list without having to parse the full query results
                        array_push( $recipeIDList, $recipeId );
                    }
                }
                else
                {
                    //We already have the id of a recipe so just add it to the list
                    array_push( $recipeIDList, $item["id"] );

                }
            }

            return $recipeIDList;

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

        /**
         * Summary:
         *      This method will take in a filterList that contains recipe id's and then from there get all of the
         *      recipes that are in the database that match those ids.
         * @param $filterList
         *      The list of recipe ID's we are going to use in order to build the response json
         * @return array
         *      A json encoded array of all the recipes we found based on the filter
         */
        function buildFilterResponse( $filterList )
        {

            $recipeList = [] ;
            $this->getRecipes() ;

            print_r( $this->mJSON_AllRecipes ) ;

            return json_encode($recipeList, JSON_PRETTY_PRINT ) ;
        }

        function buildFullRecipeList()
        {
            // Get our query.
            // This monster gets all the information about recipes that we could possibly need
            // A lot of it is redundant, however, because of the massive number of left joins.
            // Luckily because we convert the results into an associative array later on we don't have to care much.
            $queryResult = $this->conn->query
            ("
                SELECT recipes.recipeID,
                recipes.recipeName,
                recipes.prepInst,
                IngredientList.initialID AS ingredientID,
                IngredientList.initialName AS ingredientName,
                recipe_ingredient_map.isOptional AS isOptional,
                IngredientList.secondaryID AS replaceableID,
                IngredientList.secondaryName AS replaceableName,
                equipment.equipmentID,
                equipment.equipmentName,
                recipe_tags.tagID,
                recipe_tags.tagName
                FROM recipes
                LEFT JOIN recipe_ingredient_map	ON recipes.recipeID = recipe_ingredient_map.recipeID
                LEFT JOIN
                (
                    SELECT initial_ingredients.ingredientID AS initialID,
                    initial_ingredients.ingredientNAME AS initialName,
                    secondary_ingredients.ingredientID AS secondaryID,
                    secondary_ingredients.ingredientNAME AS secondaryName,
                    ingredient_tags.tagName AS categoryName
                    FROM ingredients AS initial_ingredients
                    LEFT JOIN ingredient_tag_map AS initial_map ON initial_ingredients.ingredientID = initial_map.ingredientID
                    LEFT JOIN ingredient_tag_map AS secondary_map ON initial_map.tagID = secondary_map.tagID
                    LEFT JOIN ingredients AS secondary_ingredients ON secondary_map.ingredientID = secondary_ingredients.ingredientID
                    LEFT JOIN ingredient_tags ON secondary_map.tagID = ingredient_tags.tagID
                ) AS IngredientList 		ON IngredientList.initialID = recipe_ingredient_map.ingredientID
                LEFT JOIN recipe_equipment_map 	ON recipe_equipment_map.recipeID = recipes.recipeID
                LEFT JOIN equipment 		ON equipment.equipmentID = recipe_equipment_map.equipmentID
                LEFT JOIN recipe_tag_map 	ON recipe_tag_map.recipeID = recipes.recipeID
                LEFT JOIN recipe_tags 		ON recipe_tags.tagID = recipe_tag_map.tagID
                ORDER BY recipeID, tagID, ingredientID, replaceableID, equipmentID
            ");

            // Set up our result array.
            // This will be turned into a JSON object later on and then returned.
            $result = array();

            for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
            {
                // Fetch our data from the result into a bunch of variables for ease of use.
                $queryResult->data_seek($rowNumber);
                $row = $queryResult->fetch_assoc();

                $recipeID = $row['recipeID'];
                $recipeName = $row['recipeName'];
                $prepInst = $row['prepInst'];
                $ingredientID = $row['ingredientID'];
                $ingredientName = $row['ingredientName'];
                $ingredientIsOptional = $row['isOptional'];
                $ingredientRatio = rand(10, 30);	// We don't actually have ratio information in the database yet...
                $ingredientReplaceableID = $row['replaceableID'];
                $ingredientReplaceableName = $row['replaceableName'];
                $equipmentID = $row['equipmentID'];
                $equipmentName = $row['equipmentName'];
                $tagID = $row['tagID'];
                $tagName = $row['tagName'];

                // Easy stuff--just insert the recipe's ID, name, and prep instructions into the recipe object.
                $result['recipes'][$recipeID]['id'] = (int)$recipeID;
                $result['recipes'][$recipeID]['name'] = $recipeName;
                $result['recipes'][$recipeID]['prepInst'] = $prepInst;

                // For array-like objects this gets a bit trickier.
                // If we haven't yet created the categories, equipment, or ingredients array for this object, create them.
                // This is to prevent PHP from spitting out a bunch of errors later on about accessing undefined indices.
                // We want to ensure that, even if a recipe doesn't use, say, equipment, that there's still an equipment array in there.
                if (!array_key_exists('categories', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['categories'] = array();
                if (!array_key_exists('equipment', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['equipment'] = array();
                if (!array_key_exists('ingredients', $result['recipes'][$recipeID])) $result['recipes'][$recipeID]['ingredients'] = array();

                // Only add categories to the JSON object if both the ID and the name are non-null.
                // This is to prevent awkward things like "categories": [{"id": null, "name": null}] for recipes with no category.
                // We'd rather just have nothing there than a single entry with nulls in it.
                // The above part checking for existing array keys handles that.
                if ($tagID != null && $tagName != null)
                {
                    $result['recipes'][$recipeID]['categories'][$tagID]['id'] = (int)$tagID;
                    $result['recipes'][$recipeID]['categories'][$tagID]['name'] = $tagName;
                }
                // Same as above, but with equipment.
                if ($equipmentID != null && $equipmentName != null)
                {
                    $result['recipes'][$recipeID]['equipment'][$equipmentID]['id'] = (int)$equipmentID;
                    $result['recipes'][$recipeID]['equipment'][$equipmentID]['name'] = $equipmentName;
                }
                // Finally, with ingredients
                // Though really a recipe with no ingredients would be really strange.
                if ($ingredientID != null && $ingredientName != null)
                {
                    $result['recipes'][$recipeID]['ingredients'][$ingredientID]['id'] = (int)$ingredientID;
                    $result['recipes'][$recipeID]['ingredients'][$ingredientID]['name'] = $ingredientName;
                    $result['recipes'][$recipeID]['ingredients'][$ingredientID]['isOptional'] = (bool)$ingredientIsOptional;
                    $result['recipes'][$recipeID]['ingredients'][$ingredientID]['ratio'] = (int)$ingredientRatio;


                    // All that stuff we did above to ensure that we had valid arrays and whatever needs to be repeated here
                    // since ingredients have substitutes in the form of arrays.
                    if (!array_key_exists('replaceableWith', $result['recipes'][$recipeID]['ingredients'][$ingredientID]))
                        $result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'] = array();

                    if ($ingredientReplaceableID != null && $ingredientReplaceableName != null)
                    {
                        $result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'][$ingredientReplaceableID]['id'] = (int)$ingredientReplaceableID;
                        $result['recipes'][$recipeID]['ingredients'][$ingredientID]['replaceableWith'][$ingredientReplaceableID]['name'] = $ingredientReplaceableName;
                    }
                }

            }

            // Turn the result from an associative array of recipeID => recipes into a normal array of recipes.
            $result['recipes'] = array_values($result['recipes']);

            for ($i = 0; $i < count($result['recipes']); $i++)
            {
                // Do the same thing for our categories, equipment, and ingredients.
                $result['recipes'][$i]['categories'] = array_values($result['recipes'][$i]['categories']);
                $result['recipes'][$i]['equipment'] = array_values($result['recipes'][$i]['equipment']);
                $result['recipes'][$i]['ingredients'] = array_values($result['recipes'][$i]['ingredients']);

                for ($j = 0; $j < count($result['recipes'][$i]['ingredients']); $j++)
                {
                    // And finally to our replaceable ingredients
                    $result['recipes'][$i]['ingredients'][$j]['replaceableWith'] = array_values($result['recipes'][$i]['ingredients'][$j]['replaceableWith']);
                }
            }

            // And we're done.
            echo json_encode($result, JSON_PRETTY_PRINT);
            $this->storeRecipes( json_encode( $result, JSON_PRETTY_PRINT ) ) ;

        }

        /**
         * Summary:
         *      Write the entire recipe list to a stored JSON file for access to later
         * @param $recipeJSON
         *      The json object that contains every recipe in the database
         */
        private function storeRecipes( $recipeJSON )
        {

            if( ( $recipeJsonFile = fopen( $this->mPath_AllRecipesJSON, "w" ) ) != FALSE )
            {
                fwrite( $recipeJsonFile, $recipeJSON );
                fclose( $recipeJsonFile );
            }
            else
            {
                //Log an Error.
            }

        }

        /**
         * Summary:
         *      This method is called when we want to read and store all of the recipes that live in the allRecipes.json
         *      file.
         */
        private function getRecipes()
        {

            if( ( $recipeJsonFile = fopen( $this->mPath_AllRecipesJSON, "r" ) ) != FALSE )
            {
                $this->mJSON_AllRecipes = fread( $recipeJsonFile, filesize( $this->mPath_AllRecipesJSON ) );
                fclose( $recipeJsonFile );
            }
            else
            {
                $this->logError( "Failed to read allRecipes.json", ERROR ) ;
            }

        }

        /**
         * Summary:
         *          Get the current timestamp and then insert the error into the database for logging purposes
         * @param $description
         *          A description of the error
         * @param $level
         *          The error type, either Error, Warn, or Information
         */
        private function logError( $description, $level )
        {

            $currentTime = date("Y-m-d H:i:s"); //set the timestamp to the current time, use the mySQL format
            $logQuery = "INSERT INTO error_log( timestamp, level, description ) VALUES( %s %s %s)" ;
            $this->conn->query( sprintf( $logQuery, $currentTime, $level, $description ) ) ;

        }

        //I dont actually remember what i was building this function for so I'm going to leave it commented out for now
//        private function getObjectByID( $id, $baseTable )
//        {
//
//            //Base tables have unique id's so we can just fetch row zero without parsing the return
//            //SELECT * from $baseTable where id = $id
//            $result = $this->conn( sprintf( $this->mQuery_SelectAll, $baseTable, "id", $id ) )->fetch_row()[0];
//
//            switch( $baseTable )
//            {
//                case "recipes":
//                {
//                    break;
//                }
//                case "ingredients":
//                {
//                    return ( getIngredientsByID( $id ) );
//
//                    break;
//                }
//                case "equipment":
//                {
//                    return [ "equipment" => [ "id" => $result["id"], "name" => $result["name"] ] ];
//                    break;
//                }
//                case "categories":
//                {
//                    break;
//                }
//            }
//        }
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
