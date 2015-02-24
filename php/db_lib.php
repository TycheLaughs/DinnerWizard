<?php
//TODO: Generate GLobal Queries
//TODO: Fix associative array's to properly generate nested arrays
//TODO: Encode to JSON.


    $temp = new db_lib;
    $temp->getTables();

    class db_lib
    {

        private $conn = NULL; // class member to hold our database connection
        private $_HOST = 'localhost';
        private $_USERNAME = 'root';
        private $_PASSWORD = '';
        private $_DATABASE = 'dinnerwizard';


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
            $recipeTable = $this->conn->query( 'SELECT * FROM recipes' );               //id,name
            $ingredientsTable = $this->conn->query( 'SELECT * FROM ingredients' );           //id,name
            $ingredientRecipeMapTable = $this->conn->query( 'SELECT * FROM ingredient_recipe_map' ); //ingredientID, recipeID

            $ingredientRecipeMapTable->data_seek( 0 );
            $recipeTable->data_seek( 0 );
            $ingredientsTable->data_seek( 0 );

            $arRecipes = $this->buildRecipesObject( $recipeTable, $ingredientsTable, $ingredientRecipeMapTable );
            //$arIngredients = $this->buildIngredientsObject( $recipeTable, $ingredientsTable, $ingredientRecipeMapTable );;

            print_r( $arRecipes );
            //$arDinnerWizard = [ $arRecipes, $arIngredients ];

            //$this->buildJSONObject( $arDinnerWizard );

        }

        private function buildRecipesObject( $recipeTable, $ingredientRecipeMapTable )
        {

            $arRecipes = [ ];

            while( $rowRecipe = $recipeTable->fetch_assoc() )
            {

                //reset the arrays temporary array to empty
                $strTemp = '';

                //get the recipe name and save it's id to find tags
                $arRecipe = [ 'name' => $rowRecipe['name'] ];
                $arRecipe[ 'prepIns' ] = $rowRecipe['prepInst'] ;

                $intRecipeID = $rowRecipe['id'];

                //get all of the tagID's that are associated with this recipe
                $recipeTagMapTable = $this->conn->query( 'SELECT tagID FROM recipe_tag_map WHERE recipeID = \'' . $intRecipeID . '\'' );
                $recipeTagMapTable->data_seek( 0 );

                while( $rowTemp = $recipeTagMapTable->fetch_assoc() )
                {

                    $intTempID = $rowTemp['tagID'];
                    $tempTable = $this->conn->query( 'SELECT name FROM tags WHERE id = \'' . $intTempID . '\'' );
                    $tempTable->data_seek( 0 );

                    while( $rowTag = $tempTable->fetch_assoc() )
                    {
                        $strTemp = "\"" . $strTemp . $rowTag['name'] . "\",";
                    }

                }

                //get rid of the last comma
                rtrim($strTemp, ",") ;

                //Add all of the tags to the array
                $arRecipe['tags'] =  $strTemp ;
                $strTemp = '' ;

                print_r( $arRecipe ) ;

                while( $rowTemp = $ingredientRecipeMapTable->fetch_assoc() )
                {

                    $intTempID = $rowTemp['ingredientID'];
                    $tempTable = $this->conn->query( 'SELECT name FROM ingredients WHERE id = \'' . $intTempID . '\'' );

                    while( $rowTag = $tempTable->fetch_assoc() )
                    {
                        $strTemp = "\"" . $strTemp . $rowTag['name'] . "\",";
                    }

                }

                //get rid of the last comma
                rtrim($strTemp, ",") ;

                //update the recipe array with the tag's
                $arRecipe['ingredients'] = $strTemp ;

                array_push( $arRecipes, $arRecipe ) ;
                print_r( $arRecipes ) ;
            }

            return $arRecipes;

        }

        private function buildIngredientsObject( $recipeTable, $ingredientsTable, $ingredientRecipeMapTable )
        {

            $arIngredients = [ ];

            while( $rowIngredient = $ingredientsTable->fetch_assoc() )
            {

                //reset the arrays temporary array to empty
                $strTemp = '';

                //get the recipe name and save it's id to find tags
                $arIngredient = [ 'name' => $rowIngredient['name'] ];
                $intIngredientID = $rowIngredient['id'];

                //get all of the tags that are associated with this recipe
                $ingredientTagMapTable = $this->conn->query( 'SELECT tagID FROM ingredient_tag_map WHERE ingredientID = \'' . $intIngredientID . '\'' );
                $ingredientTagMapTable->data_seek( 0 );

                while( $rowTemp = $ingredientTagMapTable->fetch_assoc() )
                {

                    $intTempID = $rowTemp['tagID'];
                    $tempTable = $this->conn->query( 'SELECT name FROM tags WHERE id = \'' . $intTempID . '\'' );
                    $tempTable->data_seek( 0 );

                    while( $rowTag = $tempTable->fetch_assoc() )
                    {
                        $strTemp = $strTemp . $rowTag['name'];
                    }

                }

                //update the tags array object with the tags and then update the recipe array
                $arIngredient['tags'] = $strTemp;
                $strTemp = '';

                while( $rowTemp = $ingredientRecipeMapTable->fetch_assoc() )
                {

                    $intTempID = $rowTemp['recipeID'];
                    $tempTable = $this->conn->query( 'SELECT name FROM recipes WHERE id = \'' . $intTempID . '\'' );

                    while( $rowTag = $tempTable->fetch_assoc() )
                    {
                        $strTemp->array_push( $rowTag['name'] );
                    }

                }

                //update the tags array object with the tags and then update the recipe array
                $arTags = [ 'ingredients' => $strTemp ];
                $arIngredient->array_push( $arTags );

                $arIngredients['recipes'] = $arIngredient ;
            }

            return $arIngredients;

        }

        private function buildJSONObject( $arDinnerWizard )
        {

        }

        /**
         * Summary:
         *      Get the information from the user and then use it to update the appropriate tables
         */
        public function update()
        {

            //get what's being updated from the front end
            $strRecipeName = $_POST["recipeName"];
            $strTags = $_POST["tags"] ;
            $strIngredients = $_POST["ingredients"];

            $this->updateTables( $strRecipeName, $strTags, $strIngredients );

        }

        /**
         * Summary:
         *      Update the database tables, we will not always be updating the recipe table because
         *      not every ingredient will need belong to a recipe but every recipe will require at least 1 ingredient.
         *
         * @param $strRecipeName
         *      The recipe name we are going to add to or update in the database, value is not required.
         * @param $strTags
         *      A tag or list of tags we are going to add to or update in the database, value is not required.
         * @param $strIngredients
         *      An ingredient or ist of ingredients we are going to update in the database, we always require at least 1
         *      ingredient.
         */
        private function updateTables( $strRecipeName, $strTags, $strIngredients )
        {

            //Check if we have a recipe to update
            if( !$strRecipeName == '' )
            {
                $this->updateRecipes( $strRecipeName, $strTags, $strIngredients );
            }

            //Update the ingredients and corresponding tables
            $this->updateIngredients( $strIngredients, $strRecipeName, $strTags );

        }

        private function updateRecipes( $strRecipeName, $strTags )
        {

            //get the id for the recipe if it exists otherwise generate a new one
            if( $this->exists( $strRecipeName, 'recipes', 'name' ) )
            {
                $intRecipeID = $this->conn->query( 'SELECT id FROM recipes WHERE recipeName = \'' . $strRecipeName . '\'' );
            }
            else
            {

                //get the last recipe id
                $intRecipeID = $this->conn->query( 'SELECT max(id) FROM recipes' )->data_seek( 0 );
                $intRecipeID++;

                //Insert the new recipe in the recipe table with the new last id
                $strInsertQuery = 'INSERT INTO recipes( id, name ) VALUES( ' . $intRecipeID . ',' . $strRecipeName . ')';
                $this->conn->query( $strInsertQuery );

            }

            //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
            if( !$strTags == '' )
            {
                $this->updateTags( $strTags, $intRecipeID, "recipes" );
            }

        }

        private function updateIngredients( $strIngredients, $strRecipeName, $strTags )
        {

            foreach( $strIngredients as $ingredient )
            {

                //get the id for the tag if it exists otherwise generate a new one
                if( $this->exists( $ingredient, 'ingredient', 'name' ) )
                {
                    $intIngredientID = $this->conn->query( 'SELECT id FROM ingredients WHERE ingredientName = \'' . $ingredient . '\'' );
                }
                else
                {

                    //get the last recipe id
                    $intIngredientID = $this->conn->query( 'SELECT max(id) FROM ingredients' )->data_seek( 0 );
                    $intIngredientID++;

                    //because there is a new tag we have to insert it into the tag table
                    $this->conn->query( 'INSERT INTO tags( id, name ) VALUES( ' . $intIngredientID . ',' . $ingredient . ')' );

                }

                //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
                if( !$strTags == '' )
                {
                    $this->updateTags( $strTags, $intIngredientID, "recipes" );
                }

                if( !$strRecipeName == '' )
                {

                    //CYA: At this point the recipe should already exist but if for some reason it doesn't then we have to add
                    //it to the database before we can map the ingredients and recipes together
                    if( !$this->exists( $strRecipeName, 'recipe', 'name' ) )
                    {
                        $this->updateRecipes( $strRecipeName, '', $strTags );
                    }

                    //Get the recipeID so we can update the map table
                    $intRecipeID = $this->conn->query( 'SELECT id FROM recipes WHERE RecipeName = \'' . $strRecipeName . '\'' );

                    $this->conn->query( 'INSERT INTO ingredient_recipe_map( ingredientID, recipeID ) VALUES( ' . $intIngredientID . ',' . $intRecipeID . ')' );

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
                    $intTagID = $this->conn->query( 'SELECT id FROM tags WHERE tagName = \'' . $tag . '\'' );
                }
                else
                {

                    //get the last recipe id
                    $intTagID = $this->conn->query( 'SELECT max(id) FROM tags' )->data_seek( 0 );
                    $intTagID++;

                    //because there is a new tag we have to insert it into the tag table
                    $this->conn->query( 'INSERT INTO tags( id, name ) VALUES( ' . $intTagID . ',' . $tag . ')' );

                }

                //update either the ingredient or the recipe tag_map tables
                //ASSUMPTION: we have already checked to see if the recipe or the ingredient exists
                if( $strTable == 'recipes' )
                {
                    $this->conn->query( 'INSERT INTO recipe_tag_map( recipeID, tagID ) VALUES( ' . $intMapID . ',' . $intTagID . ')' );
                }
                else // strMapTable is set to ingredients
                {
                    $this->conn->query( 'INSERT INTO ingredient_tag_map( ingredientID, tagID ) VALUES( ' . $intMapID . ',' . $intTagID . ')' );
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
         * @return bool
         *      True if we found the value and false if we didn't
         */
        public function exists( $strValue, $strTable, $strIdentifier = "id" )
        {

            $strQuery = '';

            //Determine which table we are checking and generate a SELECT query for either the id or the name
            switch( $strTable )
            {

                case "ingredients":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from ingredients WHERE id = \'' . $strValue . '\'';
                    }
                    else
                    {
                        $strQuery = 'SELECT * from ingredients WHERE name = \'' . $strValue . '\'';
                    }
                    break;
                }

                case "recipes":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from recipes WHERE id = \'' . $strValue . '\'';
                    }
                    else
                    {
                        $strQuery = 'SELECT * from recipes WHERE name = \'' . $strValue . '\'';
                    }
                    break;
                }

                case "tags":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from tags WHERE id = \'' . $strValue . '\'';
                    }
                    else
                    {
                        $strQuery = 'SELECT * from tags WHERE name = \'' . $strValue . '\'';
                    }
                    break;
                }
                default:
                {
                    echo( "db_lib.exists() invalid table passed in" );

                    return FALSE;
                }
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