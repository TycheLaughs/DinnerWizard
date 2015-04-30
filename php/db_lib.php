<?php
/** db_lib.php
 * Tommy Leedberg
 * Date: 1/27/2015
 * Revision: 1.0
 * Summary:
 *      This is the base base class for the db_lib.php. It comprises of most of the backend for DinnerWizard.
 *      The majority of the class was written by Tommy Leedberg but the recipe/ingredient/equipment generation methods
 *      were developed by Matt Szekely and ported over from generate_equipment_json/generate_ingredient_json/
 *      generate_recipe_categories_json/and generate_recipe_json.
 */
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
    define( "TABLE_CATEGORY_TAG_MAP",      "recipe_filter_category_tag_map" );
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
        //private $mPath_AllRecipesJSON = "../data/recipes.json" ;
        //private $mPath_AllIngredientsJSON = "../data/ingredients.json";
        //private $mPath_AllEquipmentJSON = "../data/equipment.json" ;

        //The most used queries for sustainability and easy formatting
        //SELECT * FROM <tableName>
        private $mQuery_SelectAll = "SELECT * FROM %s";
        //SELECT max(id) FROM <tableName>
        private $mQuery_SelectLastID = "SELECT max(id) FROM %s";
        //SELECT <attribute> FROM <table> WHERE <attribute> = <value>
        private $mQuery_SelectFromTable = "SELECT %s FROM %s WHERE %s = '%s'";
        //INSERT INTO <table>( id, name ) VALUES( <id>, <name> )
        private $mQuery_InsBaseTable = "INSERT INTO %s( id, name ) VALUES( '%d', '%s' ) ";
        //INSERT INTO tags( id, name, isFilterable ) VALUES( <id>, <name>, <isFilterable> ) ;
        private $mQuery_InsTagsTable = "INSERT INTO tags( id, name, isFilterable ) VALUES '%s', '%s' )";
        //INSERT INTO recipes( id, name, prepInst ) VALUES( <id>, <name>, <prepInst> )
        private $mQuery_InsRecipesTable = "INSERT INTO recipes( id, name, prepInst ) VALUES ( '%d', '%s', '%s' ) ";
        //INSERT INTO <table>( id, id ) VALUES( <id>, <id> ) ;
        private $mQuery_InsMapTable = "INSERT INTO %s( id, id) VALUES( '%d', '%d')";

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

            //if we aren't already connected then lets get a database connection going
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

                // If we don't have this the ° characters in, for example, "heat the oven to 400°", will cause json_encode to just fail silently.
                if (!$this->conn->set_charset("utf8mb4"))
                {
                    die("Error loading character set utf8: " . $this->conn->error);
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

            //Allow the user to do only the ingredients the supply exclude all recipes that contain other ones.
            $exclusiveIngredients = $request["exclusiveIngredients"];
            $ingredientFilter = $request["ingredientTags"];
            $recipeTagFilter = $request["recipeTags"];
            $equipmentFilter = $request["equipment"];
            $withoutFilter = $request["without"];

            //If we have there has been a filter provided in each of the fields try to generate an array of the correct
            //recipes, if you can then add that array to the recipeList.
            if( $ingredientFilter != NULL AND ( $result = $this->filter( $ingredientFilter, "ingredientTags" ) ) != NULL )
            {

                //Having to loop through the results to rebuild our recipeList isn't very elegant but merging arrays
                //in php has issues with non unique keys and we were overwriting results because of duplicate key names
                foreach( $result as $recipeID )
                {

                    array_push( $recipeList, $recipeID ) ;
                    //there's no reason for duplicate recipes if we dont want specific ingredients
                    $recipeList = array_unique( $recipeList );

                }

            }
            if( $equipmentFilter != NULL AND ( $result = $this->filter( $equipmentFilter, "equipment" ) ) != NULL )
            {
                foreach( $result as $recipeID )
                {
                    array_push( $recipeList, $recipeID ) ;
                    $recipeList = array_unique( $recipeList ); //there's no reason for duplicate recipes
                }
            }

            //The without filter is a little different, if it finds a recipe it then searches the recipeList and
            //removes it from the list if it is present
            if( $withoutFilter != NULL AND ( $result = $this->filter( $withoutFilter, "without" ) ) != NULL )
            {

                foreach( $result as $item )
                {
                    if( ( $key = array_search( $item, $recipeList ) ) != false )
                    {
                        unset( $recipeList[$key] );
                    }
                }

            }

            //Only recipes for ingredients/equipment that we found that also contain the requested tag can be used
            //so we need to check our recipe list and make sure that the tag matches
            if( $recipeTagFilter != NULL )
            {

                //Now that we've simplified our current list we can go in and get all the other recipes that pertain
                //to this recipe filter
                if( ($result = $this->filter( $recipeTagFilter, "recipeTags" )) != NULL )
                {
                    foreach( $result as $recipeID )
                    {
                        array_push( $recipeList, $recipeID ) ;
                        $recipeList = array_unique( $recipeList ); //there's no reason for duplicate recipes
                    }
                }

                //first lets get only recipes that pertain to this tag from our current recipe list
                $recipeList = $this->matchRecipeTags( $recipeTagFilter, $recipeList ) ;

            }
            if( $exclusiveIngredients )
            {
                $recipeList = $this->exclusiveIngredientList( $ingredientFilter, $recipeList ) ;
            }


            $recipeList = $this->buildFilterResponse( $recipeList ) ;
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
                    $mapTable = TABLE_RECIPE_TAG_MAP ;
                    $mapAttribute = "tagID" ;
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
                            foreach( $this->filter( [$withoutFilter], "recipeTags" ) as $recipeID )
                            {
                                array_push( $recipeIDList, $recipeID ) ;
                                array_unique( $recipeIDList  );
                            }

                        }
                        elseif( $withoutFilter["group"] == "ingredients" )
                        {

                            unset( $withoutFilter["group"] );
                            //when calling filter with withoutFilter must be turned back into an array of id, name objects
                            foreach( $this->filter( [$withoutFilter], "ingredientTags" ) as $recipeID )
                            {
                                array_push( $recipeIDList, $recipeID ) ;
                                array_unique( $recipeIDList  );
                            }

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

                //SELECT recipeID FROM $mapTable WHERE $mapApptribute = $item["id"]
                $recipeIDs = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "recipeID", $mapTable, $mapAttribute, $item["id"] ) ) ;

                //When we run the query we are getting the first row right away because recipes are unique. If the
                //result is NULL then there is no recipe associated with the current filter
                if( $recipeIDs != NULL )
                {

                    while( $row = mysqli_fetch_row( $recipeIDs ) )
                    {
                        array_push( $recipeIDList, $row[0] );
                    }

                }

            }

            return $recipeIDList;

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
        private function buildFilterResponse( $filterList )
        {

            $recipes = $this->getRecipes() ;
            $recipeList = [] ;

            //Get all the recipes and decode them into an associative array
            $allRecipes = json_decode( $recipes, true ) ;

            foreach( $filterList as $filter )
            {

                foreach( $allRecipes["recipes"] as $recipe )
                {

                    if( $filter == $recipe["id"]  )
                    {

                        if( empty( $recipeList ) )
                        {
                            $recipeList["recipes"] = [$recipe] ;
                            break ;
                        }
                        else
                        {
                            //after we have created the initial array of recipe objects we dont want to
                            //add extra arrays, because the JSON should look like [{recipe},{recipe}] if we continue to
                            //push arrays we end up with [{recipe}[{recipe]} which is inproper formating
                            array_push( $recipeList["recipes"], $recipe );
                            break ;
                        }
                    }
                }
            }

            return $recipeList ;
        }

        /**
         * Summary:
         *      Take a list of recipeID's and TagId's and remove any recipe from the recipeList that doesnt contain a tag
         *      from the taglist
         * @param $tagList
         *      A list of Tag objects [ 'id' => id, 'name' => name ]
         * @param $recipeList
         *      A list of recipe Ids
         * @return mixed
         *      A list of recipeID's that have tagId's from the tag list.
         */
        private function matchRecipeTags( $tagList, $recipeList )
        {

            //To ensure our recipelist only conatins recipes that match the tag we will get all the tagID's for the recipes
            //we have found and then check to make sure they match a tagID in our tag list
            foreach( $recipeList as $item )
            {

                $tempTagList = $tagList ;
                $tagMatch = FALSE ;

                //SELECT tagID FROM TABLE_RECIPE_TAG_MAP WHERE recipeID = $item["id"]
                $tagID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, "tagID", TABLE_RECIPE_TAG_MAP, "recipeID", $item ) ) ;

                if( $tagID != NULL )
                {

                    while( $row = mysqli_fetch_row( $tagID ) )
                    {

                        //Check to see if a tagID from the tagList and the tagID from the recipe_tag_map, for the given
                        //recipeID, match
                        foreach( $tempTagList as $tagItem )
                        {
                            if( $row[0] == $tagItem["id"] )
                            {

                                //If we found a tag that is in our tag list remove it from the temporary list so we can tell
                                //if we have found all of the required tags for this recipe.
                                if( ( $key = array_search( $tagItem, $tempTagList) ) !== false )
                                {
                                    unset( $tempTagList[$key] );
                                }

                            }
                        }

                        if( count( $tempTagList ) == 0 )
                        {
                            $tagMatch = TRUE ;
                        }
                    }

                }

                if( $tagMatch == FALSE )
                {
                    if( ( $key = array_search( $item, $recipeList) ) !== false )
                    {
                        unset( $recipeList[$key] );
                    }
                }
            }

            return $recipeList ;

        }

        /**
         * Summary:
         *      Exclusive ingredients require that any recipe that we return has all of the requested ingredients in
         *      it and nothing more so we have to filter through the list of recipes we've currently generated and check
         *      to see that all of the ingredients are present in it, if not we need to remove it.
         * @param $ingredientList
         *      The list of ingredient objects that we are filtering on
         * @param $recipeList
         *      The current list of recipe Id's that we have gathered from filtering
         * @return mixed
         *      A recipe list that contains only recipes which contain all ingredients in the ingredientList
         */
        private function exclusiveIngredientList( $ingredientList, $recipeList )
        {

            //To ensure our recipelist only conatins recipes that match the tag we will get all the tagID's for the recipes
            //we have found and then check to make sure they match a tagID in our tag list
            foreach( $recipeList as $item )
            {

                $tempArray = [] ;

                //SELECT ingredientID FROM TABLE_RECIPE_TAG_MAP WHERE recipeID = $item["id"]
                $ingredientID = $this->conn->query( $temp = sprintf( $this->mQuery_SelectFromTable, "ingredientID", TABLE_RECIPE_INGREDIENT_MAP, "recipeID", $item ) ) ;

                if( $ingredientID != NULL )
                {

                    while( $row = mysqli_fetch_row( $ingredientID ) )
                    {

                        //Check to see if a tagID from the tagList and the tagID from the recipe_tag_map, for the given
                        //recipeID, match
                        foreach( $ingredientList as $ingredient )
                        {
                            if( $row[0] == $ingredient["id"] )
                            {
                                array_push( $tempArray, $ingredient );

                            }
                        }

                    }

                }

                if( $tempArray != $ingredientList )
                {

                    if( ( $key = array_search( $item, $recipeList) ) !== false )
                    {
                        unset( $recipeList[$key] );
                    }

                }

            }

            return $recipeList ;

        }
        private function buildRecipeList()
        {

            // Get our query.
            // This monster gets all the information about recipes that we could possibly need
            // A lot of it is redundant, however, because of the massive number of left joins.
            // Luckily because we convert the results into an associative array later on we don't have to care much.
            $queryResult = $this->conn->query("
                    SELECT recipes.ID AS recipeID,
                    recipes.name AS recipeName,
                    recipes.prepInst,
                    recipe_ingredient_map.ingredientID AS ingredientID,
                    ingredients1.name AS ingredientName,
                    recipe_ingredient_map.isOptional AS isOptional,
                    recipe_replaceable_ingredient_map.replaceableIngredientID AS replaceableIngredientID,
                    ingredients2.name AS replaceableIngredientName,
                    recipe_ingredient_map.ratio AS ratio,
                    equipment.ID AS equipmentID,
                    equipment.name AS equipmentName,
                    recipe_tags.ID AS tagID,
                    recipe_tags.name AS tagName
                    FROM recipes 
                    LEFT JOIN recipe_ingredient_map	ON recipes.ID = recipe_ingredient_map.recipeID 
                    LEFT JOIN recipe_replaceable_ingredient_map ON recipes.ID = recipe_replaceable_ingredient_map.recipeID AND recipe_ingredient_map.ingredientID = recipe_replaceable_ingredient_map.ingredientID
                    LEFT JOIN ingredients AS ingredients1 ON recipe_ingredient_map.ingredientID = ingredients1.ID
                    LEFT JOIN ingredients AS ingredients2 ON recipe_replaceable_ingredient_map.replaceableIngredientID = ingredients2.ID
                    LEFT JOIN recipe_equipment_map 	ON recipes.ID = recipe_equipment_map.recipeID
                    LEFT JOIN equipment 		ON equipment.ID = recipe_equipment_map.equipmentID 
                    LEFT JOIN recipe_tag_map 	ON recipes.ID = recipe_tag_map.recipeID 
                    LEFT JOIN recipe_tags 		ON recipe_tags.ID = recipe_tag_map.tagID 
                    ORDER BY recipeID, ingredientID, replaceableIngredientID, equipmentID, tagID
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
                $ingredientRatio = $row['ratio'];
                $ingredientReplaceableID = $row['replaceableIngredientID'];
                $ingredientReplaceableName = $row['replaceableIngredientName'];
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
            //echo json_encode($result, JSON_PRETTY_PRINT);
            //$this->storeInformation( json_encode($result, JSON_PRETTY_PRINT),  $this->mPath_AllIngredientsJSON ) ;
            return json_encode($result, JSON_PRETTY_PRINT) ;
        }

        private function buildIngredientList()
        {

            $queryResult = $this->conn->query("
                    SELECT ingredients.ID AS ingredientID,
                    ingredients.name AS ingredientName,
                    ingredient_tags.ID AS tagID,
                    ingredient_tags.name AS tagName
                    FROM ingredients
                    LEFT JOIN ingredient_tag_map ON ingredients.ID = ingredient_tag_map.ingredientID
                    LEFT JOIN ingredient_tags ON ingredient_tag_map.tagID = ingredient_tags.ID
                    ORDER BY ingredientID");

            // Set up our result array.
            // This will be turned into a JSON object later on and then returned.
            $result = array();
            $result['ingredients'] = array();

            for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
            {
                $queryResult->data_seek($rowNumber);
                $row = $queryResult->fetch_assoc();

                $ingredientID = $row['ingredientID'];
                $ingredientName = $row['ingredientName'];
                $tagID = $row['tagID'];
                $tagName = $row['tagName'];

                $result['ingredients'][$ingredientID]['id'] = (int)$ingredientID;
                $result['ingredients'][$ingredientID]['ingredientName'] = $ingredientName;

                if (!array_key_exists('tags', $result['ingredients'][$ingredientID]))
                    $result['ingredients'][$ingredientID]['tags'] = array();

                if ($tagID != null)
                {
                    $result['ingredients'][$ingredientID]['tags'][$tagID]['id'] = (int)$tagID;
                    $result['ingredients'][$ingredientID]['tags'][$tagID]['name'] = $tagName;
                }
            }

            $result['ingredients'] = array_values($result['ingredients']);

            for ($i = 0; $i < count($result['ingredients']); $i++)
            {
                // Do the same thing for our categories, equipment, and ingredients.
                $result['ingredients'][$i]['tags'] = array_values($result['ingredients'][$i]['tags']);
            }

            // And we're done.
            //echo json_encode($result, JSON_PRETTY_PRINT);
            //$this->storeInformation( json_encode($result, JSON_PRETTY_PRINT), $this->mPath_AllIngredientsJSON ) ;
            return json_encode( $result, JSON_PRETTY_PRINT ) ;
        }

        private function buildEquipmentList()
        {
            $queryResult = $this->conn->query("
                          SELECT equipment.id AS equipmentID,
                          equipment.name AS equipmentName FROM equipment");

            // Set up our result array.
            // This will be turned into a JSON object later on and then returned.
            $result = array();
            $result['equipment'] = array();

            for ($rowNumber = 0; $rowNumber < $queryResult->num_rows; $rowNumber++)
            {
                $queryResult->data_seek($rowNumber);
                $row = $queryResult->fetch_assoc();

                $equipmentID = $row['equipmentID'];
                $equipmentName = $row['equipmentName'];

                $result['equipment'][$equipmentID]['id'] = (int)$equipmentID;
                $result['equipment'][$equipmentID]['name'] = $equipmentName;
            }

            $result['equipment'] = array_values($result['equipment']);

            // And we're done.
            //echo json_encode($result, JSON_PRETTY_PRINT);
            //$this->storeInformation( json_encode($result, JSON_PRETTY_PRINT), $this->mPath_AllEquipmentJSON ) ;
            return json_encode( $result, JSON_PRETTY_PRINT ) ;
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

        /**
         * Summary:
         *      Get a json object representing all of the recipes in the database
         *
         * @return string
         *      The JSON object representing the recipes
         */
        public function getRecipes()
        {
            //make sure we have the most up to date version of the recipes
            return $this->buildRecipeList() ;
            //return $this->getBaseTableInfo( $this->mPath_AllRecipesJSON ) ;
        }

        /**
         * Summary:
         *      Get a json object representing all of the ingredients in the database
         *
         * @return string
         *      The JSON object representing the ingredients
         */
        public function getIngredients()
        {
            //make sure we have the most up to date version of the recipes
            return $this->buildIngredientList() ;
            //return $this->getBaseTableInfo( $this->mPath_AllIngredientsJSON ) ;
        }

        /**
         * Summary:
         *      Get a json object representing all of the equipment in the database
         *
         * @return string
         *      The JSON object representing the equipment
         */
        public function getEquipment()
        {
            //make sure we have the most up to date version of the recipes
            return $this->buildEquipmentList() ;
            //return $this->getBaseTableInfo( $this->mPath_AllEquipmentJSON ) ;
        }

//        /**
//         * Summary:
//         *      check to see if the given value exists in the given table, by default we check for ids but
//         *      you can also check by name.
//         *
//         * @param $strValue
//         *      The value we are checking to see if exists in the given table.
//         * @param $strTable
//         *      The table we should be checking.
//         * @param string $strIdentifier
//         *      An identifier telling us if we are checking for an id or a name.
//         *
//         * @return bool
//         *      True if we found the value and false if we didn't
//         */
//        private function exists( $strValue, $strTable, $strIdentifier = "id" )
//        {
//
//            if( $strIdentifier == 'id' )
//            {
//                //SELECT * from $strTable WHERE id = '$strValue'
//                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'id', $strTable, 'id', $strValue );
//            }
//            else
//            {
//                //SELECT * from $strTable WHERE name = '$strValue'
//                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'name', $strTable, 'name', $strValue );
//            }
//
//            //If the query returns a value then return true otherwise return false
//            if( $this->conn->query( $strQuery )->num_rows > 0 )
//            {
//                return TRUE;
//            }
//            else
//            {
//                return FALSE;
//            }
//        }
//
//        /**
//         * Summary:
//         *      Write the entire recipe list to a stored JSON file for access to later
//         *
//         * @param $infoJSON
//         *      The information we want to store in the storepath's file
//         * @param $storagePath
//         *      The json object that contains every recipe in the database
//         */
//        private function storeInformation( $infoJSON, $storagePath )
//        {
//
//            if( ( $handle = fopen( $storagePath, "w" ) ) != FALSE )
//            {
//                fwrite( $handle, $infoJSON );
//                fclose( $handle );
//            }
//            else
//            {
//                //Log an Error.
//            }
//
//        }
//
//        /**
//         * Summary:
//         *      This method is called when we want to get a json object containing all of the recipes, ingredients,
//         *      or equipment that live in the database.
//         *
//         * @param $path
//         *      The path of the file we are going to to get the database information
//         * @return array
//         *      The information from the JSON file we have pertaining to the objects we are trying to get, either
//         *      recipes, ingredients, or equipment
//         */
//        private function getBaseTableInfo( $path )
//        {
//
//            if( ( $jsonFile = fopen( $path, "r" ) ) != FALSE )
//            {
//                $jsonObject = fread( $path, filesize( $path ) );
//                fclose( $path );
//            }
//            else
//            {
//                $this->logError( "Failed to read " . $path, ERROR );
//            }
//
//            return $jsonObject ;
//        }

    }

?>
