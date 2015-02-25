<?php
    //TODO: Generate GLobal Queries
    //TODO: Fix associative array's to properly generate nested arrays
    //TODO: Encode to JSON.


    $temp = new db_lib;
    $temp->update();

    class db_lib
    {

        //datbase connection information
        private $conn = NULL;
        private $_HOST = 'localhost';
        private $_USERNAME = 'root';
        private $_PASSWORD = '';
        private $_DATABASE = 'dinnerwizard';

        //The most used queries for sustainability and easy formating
        private $mQuery_SelectAll = "SELECT * FROM %s";                                                        //SELECT * FROM <tableName>
        private $mQuery_SelectFromTable = "SELECT %s FROM %s WHERE %s = '%s'";                                      //SELECT <attribute> FROM <table> WHERE <attribute> = <value>
        private $mQuery_InsBaseTableWithID = "INSERT INTO %s( id, name ) VALUES( '%d', '%s' ) ";                        //INSERT INTO <table>( id, name ) VALUES( <id>, <name> )
        private $mQuery_InsBaseTables = "INSERT INTO %s( name ) VALUES( '%s' ) ";                                  //INSERT INTO <table>( name ) VALUES( <name> )
        private $mQuery_InsRecipesTable = "INSERT INTO recipes( name, prepInst ) VALUES( '%s', '%s' ) ";             //INSERT INTO recipes( name, prepInst ) VALUES( <name>, <prepInst> )
        private $mQuery_InsRecipesTableWithID = "INSERT INTO recipes( id, name, prepInst ) VALUES ( '%d', '%s', '%s' ) ";  //INSERT INTO recipes( id, name, prepInst ) VALUES( <id>, <name>, <prepInst> )
        private $mQuery_InsMapTable = "INSERT INTO %s( %s, %s) VALUES( '%d', '%d')";


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

        // private function buildJSONObject( $arDinnerWizard )
        //{
        //}

        /**
         * Summary:
         *      Get the information from the user and then use it to update the appropriate tables
         */
        public function update()
        {

            //get what's being updated from the front end
            //$strRecipeName = $_POST["recipeName"];
            //$strPrepInst = $_POST["prepInst"];
            //$strTags = $_POST["tags"];
            //$strIngredients = $_POST["ingredients"];

            //Sample Code
            $strRecipeName = "Garlic Herb Chicken";
            $strPrepInst = "Marinate CHick in italian dressing, then pan fry with minced garlic and onion";
            $strTags = [ "Garlicky", "Chicken" ];
            $strIngredients = [ "Chicken", "Garlic", "Italian Dressing", "Onion" ];
            $this->updateTables( $strRecipeName, $strPrepInst, $strTags, $strIngredients );

        }

        /**
         * Summary:
         *      Update the database tables, we will not always be updating the recipe table because
         *      not every ingredient will need belong to a recipe but every recipe will require at least 1 ingredient.
         *
         * @param $strRecipeName
         *      The recipe name we are going to add to or update in the database, value is not required.
         * @param $strPrepInst
         *      The prepration instructions for the recipe the user has submitted
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
                $intRecipeID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'recipes', 'name', $strRecipeName ) )->data_seek( 0 );
            }
            else
            {

                //get the last recipe id
                $intRecipeID = $this->conn->query( 'SELECT max(id) FROM recipes' )->data_seek( 0 );
                $intRecipeID++;

                //Insert the new recipe in the recipe table with the new last id
                //'INSERT INTO recipes( id, name, prepInst ) VALUES( $intRecipeID, $strRecipeName, $strPrepInst )
                $this->conn->query( sprintf( $this->mQuery_InsRecipesTableWithID, $intRecipeID, $strRecipeName, $strPrepInst ) );

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
                    if( $this->exists( $ingredient, 'ingredient', 'name' ) )
                    {
                        //SELECT id FROM ingredients WHERE ingredientName = '$ingredient'
                        $intIngredientID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'ingredients', 'name', $ingredient ) )->data_seek( 0 );
                    }
                    else
                    {

                        //get the last ingredient id
                        $intIngredientID = $this->conn->query( 'SELECT max(id) FROM ingredients' )->data_seek( 0 );
                        $intIngredientID++;

                        //because there is a new tag we have to insert it into the tag table
                        //INSERT INTO ingredients( id, name ) VALUES( $intIngredientID, $ingredient )
                        $this->conn->query( sprintf( $this->mQuery_InsBaseTableWithID, 'ingredients', $intIngredientID, $ingredient ) );

                    }

                    //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
                    if( sizeOf( $strTags ) > 0 )
                    {
                        $this->updateTags( $strTags, $intIngredientID, "ingredients" );
                    }

                    if( !$strRecipeName == '' )
                    {

                        //Get the recipeID so we can update the map table
                        //SELECT id FROM recipes WHERE name = '$strRecipeName'
                        $intRecipeID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'recipes', 'name', $strRecipeName ) )->data_seek(0);

                        //INSERT INTO ingredient_recipe_map( ingredientID, recipeID ) VALUES( $intIngredientID, $intRecipeID )
                        $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'ingredient_recipe_map', 'ingredientID', 'recipeID', $intIngredientID, $intRecipeID ) );

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
                    $intTagID = $this->conn->query( sprintf( $this->mQuery_SelectFromTable, 'id', 'tags', 'name', $tag ) )->data_seek(0);
                }
                else
                {

                    //get the last recipe id
                    $intTagID = $this->conn->query( 'SELECT max(id) FROM tags' )->data_seek(0);
                    $intTagID++;

                    //because there is a new tag we have to insert it into the tag table
                    //INSERT INTO tags( id, name ) VALUES( $intTagID, $tag )
                    $this->conn->query( sprintf( $this->$mQuery_InsBaseTableWithID, 'tags', $intTagID, $tag ) );

                }

                echo $intMapID;
                echo $intTagID;
                //update either the ingredient or the recipe tag_map tables
                //ASSUMPTION: we have already checked to see if the recipe or the ingredient exists
                if( $strTable == 'recipes' )
                {
                    //                    echo $intTagID ;
                    //INSERT INTO recipe_tag_map( recipetID, tagID ) VALUES( $intMapID, $intTagID )
                    $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'recipe_tag_map', 'recipeID', 'tagID', $intMapID, $intTagID ) );
                }
                else
                {
                    //INSERT INTO ingredient_tag_map( ingredientID, tagID ) VALUES( $intMapID,  $intTagID )
                    $this->conn->query( sprintf( $this->mQuery_InsMapTable, 'ingredient_tag_map', 'ingredientID', 'tagID', $intMapID, $intTagID ) );
                }

            }
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
        public function exists( $strValue, $strTable, $strIdentifier = "id" )
        {


            if( $strIdentifier == 'id' )
            {
                //SELECT * from ingredients WHERE id = '$strValue '
                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'id', $strTable, 'id', $strValue );
            }
            else
            {
                //'SELECT * from ingredients WHERE name = \'' . $strValue . '\'';
                $strQuery = sprintf( $this->mQuery_SelectFromTable, 'name', $strTable, 'name', $strValue );
            }

            //If the query returns a value then return true otherwise return false
            if( $this->conn->query( $strQuery ) != NULL )
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