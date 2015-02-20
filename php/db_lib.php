<?php

    class db_lib
    {

        private $conn = NULL ; // class member to hold our database connection
        private $_HOST = 'localhost' ;
        private $_USERNAME = 'root' ;
        private $_PASSWORD = '' ;
        private $_DATABASE = 'dinnerwizard' ;


        /**
         * Summary:
         *      Create a connection to the SQL Server and the database we want to use.
         */
        public function __construct()
        {
            $this->$conn = new mysqli( $this->_HOST,  $this->_USERNAME, $this->$_PASSWORD, $this->$_DATABASE ) ;

            //Create any connection error warnings
            if( $this->conn->connect_error)
            {
                //set error information for debugging if we fail to connect
                die( "Failed to connect to server with error: " . $this->conn->connect_error ) ;
            }

        }

        public function getTables()
        {


/*        {
            "recipeName" : "test recipe",
            "categories" :
            [
            "category1",
            "category2"
            ],
            "tags" :
            [
            "tag1",
            "tag2"
            ],
            "ingredients" :
            [
            "ingredient1",
            "ingredient2"
            ]
        }*/

            /**
             * Because this is only a class project these tables are not going to be very large it makes sense
             * to bring all of the tables in, send them to the front end as a JSON object, and then filter them there.
             * This will be a lot faster then querying the database for the information whenever we need it.
             * If this was for a release project where the database could grow exponentially you would keep
             * everything in the database and then write queries so you are only sending small pieces of data to the
             * browser.
             */
            //get all of the tables
            $recipeTable                = $this->conn->query( 'SELECT * FROM recipes' ) ;               //id,name
            $categoryTable              = $this->conn->query( 'SELECT * FROM categories' ) ;            //id,name
            $tagsTable                  = $this->conn->query( 'SELECT * FROM tags' ) ;                  //id,name
            $ingredientsTable           = $this->conn->query( 'SELECT * FROM ingredients' ) ;           //id,name
            $ingredientTagMapTable      = $this->conn->query( 'SELECT * FROM ingredient_tag_map' ) ;    //ingredientID, tagID
            $ingredientRecipeMapTable   = $this->conn->query( 'SELECT * FROM ingredient_recipe_map' ) ; //ingredientID, recipeID
            $recipeTagMapTable          = $this->conn->query( 'SELECT * FROM recipe_tag_map' ) ;        //recipeID, tagID
            $recipeCategoryMapTable     = $this->conn->query( 'SELECT * FROM recipe_category_map' ) ;   //recipeID, categoryID

            $recipeTable->data_seek(0) ;
            $categoryTable->data_seek(0) ;
            $tagsTable->data_seek(0) ;
            $ingredientsTable->data_seek(0) ;
            $ingredientTagMapTable->data_seek(0) ;
            $ingredientRecipeMapTable->data_seek(0) ;
            $recipeTagMapTable->data_seek(0) ;
            $recipeCategoryMapTable->data_seek(0) ;

            $arRecipes = array() ;
            while( $row = $recipeTable->fetch_assoc() )
            {
                $arRecipe = array( 'name' => $row['name'] ) ;

                $intRecipeID = $row['id'] ;




                $arRecipes.push( $arRecipe ) ;
            }

        }

        /**
         * Summary:
         *      Get the information from the user and then use it to update the appropriate tables
         */
        public function update()
        {

            //get what's being updated from the front end
            $strRecipeName  = $_POST["recipeName"] ;
            $strTags        = $_POST["tags"] ;
            $strCategories  = $_POST["categories"] ;
            $strIngredients = $_POST["ingredients"] ;

            $this->updateTables( $strRecipeName, $strTags, $strCategories, $strIngredients ) ;

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
         * @param $strCategories
         *      A category or list of categories we are going to update in the database, value is not required.
         * @param $strIngredients
         *      An ingredient or ist of ingredients we are going to update in the database, we always require at least 1
         *      ingredient.
         */
        private function updateTables( $strRecipeName, $strTags, $strCategories, $strIngredients )
        {

            //Check if we have a recipe to update
            if( !$strRecipeName == '' )
            {
                $this->updateRecipes($strRecipeName, $strCategories, $strTags, $strIngredients);
            }

            //Update the ingredients and corresponding tables
            $this->updateIngredients( $strIngredients, $strRecipeName, $strTags ) ;

        }

        private function updateRecipes( $strRecipeName, $strCategories, $strTags )
        {

            //get the id for the recipe if it exists otherwise generate a new one
            if( $this->exists( $strRecipeName, 'recipes', 'name' ) )
            {
                $intRecipeID = $this->conn->query( 'SELECT id FROM recipes WHERE recipeName = \'' . $strRecipeName . '\'' ) ;
            }
            else
            {

                //get the last recipe id
                $intRecipeID = $this->conn->query('SELECT max(id) FROM recipes')->data_seek(0);
                $intRecipeID++;

                //Insert the new recipe in the recipe table with the new last id
                $strInsertQuery = 'INSERT INTO recipes( id, name ) VALUES( ' . $intRecipeID . ',' . $strRecipeName . ')' ;
                $this->conn->query( $strInsertQuery ) ;

            }

            //if there is 1 or more categories then we need to update the categories and category_tag_map tables
            if( !$strCategories == '' )
            {
                $this->updateCategories( $strCategories, $intRecipeID ) ;
            }

            //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
            if( !$strTags == '' )
            {
                $this->updateTags( $strTags, $intRecipeID, "recipes" ) ;
            }

        }

        private function updateIngredients( $strIngredients, $strRecipeName, $strTags )
        {

            foreach( $strIngredients as $ingredient )
            {

                //get the id for the tag if it exists otherwise generate a new one
                if( $this->exists( $ingredient, 'ingredient', 'name' ) )
                {
                    $intIngredientID = $this->conn->query( 'SELECT id FROM ingredients WHERE ingredientName = \'' . $ingredient . '\'' ) ;
                }
                else
                {

                    //get the last recipe id
                    $intIngredientID = $this->conn->query('SELECT max(id) FROM ingredients')->data_seek(0);
                    $intIngredientID++;

                    //because there is a new tag we have to insert it into the tag table
                    $this->conn->query( 'INSERT INTO tags( id, name ) VALUES( ' . $intIngredientID . ',' . $ingredient . ')' ) ;

                }

                //if there is 1 or more tags then we need to update the tags and recipe_tag_map tables
                if( !$strTags == '' )
                {
                    $this->updateTags( $strTags, $intIngredientID, "recipes" ) ;
                }

                if( !$strRecipeName == '' )
                {

                    //CYA: At this point the recipe should already exist but if for some reason it doesn't then we have to add
                    //it to the database before we can map the ingredients and recipes together
                    if( !$this->exists( $strRecipeName, 'recipe', 'name' ) )
                    {
                        $this->updateRecipes( $strRecipeName, '', $strTags ) ;
                    }

                    //Get the recipeID so we can update the map table
                    $intRecipeID = $this->conn->query( 'SELECT id FROM recipes WHERE RecipeName = \'' . $strRecipeName . '\'' ) ;

                    $this->conn->query('INSERT INTO ingredient_recipe_map( ingredientID, recipeID ) VALUES( ' . $intIngredientID . ',' . $intRecipeID . ')');

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
                    $intTagID = $this->conn->query( 'SELECT id FROM tags WHERE tagName = \'' . $tag . '\'' ) ;
                }
                else
                {

                    //get the last recipe id
                    $intTagID = $this->conn->query('SELECT max(id) FROM tags')->data_seek(0);
                    $intTagID++;

                    //because there is a new tag we have to insert it into the tag table
                    $this->conn->query( 'INSERT INTO tags( id, name ) VALUES( ' . $intTagID . ',' . $tag . ')' ) ;

                }

                //update either the ingredient or the recipe tag_map tables
                //ASSUMPTION: we have already checked to see if the recipe or the ingredient exists
                if( $strTable == 'recipes' )
                {
                    $this->conn->query('INSERT INTO recipe_tag_map( recipeID, tagID ) VALUES( ' . $intMapID . ',' . $intTagID . ')');
                }
                else // strMapTable is set to ingredients
                {
                    $this->conn->query('INSERT INTO ingredient_tag_map( ingredientID, tagID ) VALUES( ' . $intMapID . ',' . $intTagID . ')');
                }

            }
        }

        /**
         * Summary:
         *      Update the categories table with a new category or a list of categories.
         *
         * @param $categories
         *      Either an individual category or a list of categories that need to be added to the categories table.
         * @param $intRecipeID
         *      The id of the new recipe to be added to the recipe_category_map table.
         */
        private function updateCategories( $categories, $intRecipeID )
        {

            foreach( $categories as $category )
            {

                //get the id for the tag if it exists otherwise generate a new one
                if( $this->exists( $category, 'categories', 'name' ) )
                {
                    $intCategoryID = $this->conn->query( 'SELECT id FROM categories WHERE $categoryName = \'' . $category . '\'' ) ;
                }
                else
                {

                    //get the last category id
                    $intCategoryID = $this->conn->query('SELECT max(id) FROM categories')->data_seek(0);
                    $intCategoryID++;

                    //because there is a new tag we have to insert it into the $categories table
                    $this->conn->query( 'INSERT INTO categories( id, name ) VALUES( ' . $intCategoryID . ',' . $category . ')' ) ;

                }

                //update the recipe_category_map table
                //ASSUMPTION: we have already checked to see if the recipe exists
                $this->conn->query( 'INSERT INTO recipe_category_map( recipeID, categoryID ) VALUES( ' . $intRecipeID . ',' . $intCategoryID . ')' ) ;

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
            $strQuery = '' ;


            //Determine which table we are checking and generate a SELECT query for either the id or the name
            switch( $strTable )
            {

                case "categories":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from categories WHERE id = \'' .  $strValue . '\'' ;
                    }
                    else
                    {
                        $strQuery = 'SELECT * from categories WHERE name = \'' .  $strValue . '\'' ;
                    }
                    break ;
                }

                case "ingredients":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from ingredients WHERE id = \'' .  $strValue . '\'' ;
                    }
                    else
                    {
                        $strQuery = 'SELECT * from ingredients WHERE name = \'' .  $strValue . '\'' ;
                    }
                    break ;
                }

                case "recipes":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from recipes WHERE id = \'' .  $strValue . '\'' ;
                    }
                    else
                    {
                        $strQuery = 'SELECT * from recipes WHERE name = \'' .  $strValue . '\'' ;
                    }
                    break ;
                }
                
                case "tags":
                {
                    if( $strIdentifier == 'id' )
                    {
                        $strQuery = 'SELECT * from tags WHERE id = \'' .  $strValue . '\'' ;
                    }
                    else
                    {
                        $strQuery = 'SELECT * from tags WHERE name = \'' .  $strValue . '\'' ;
                    }
                    break ;
                }
                default:
                {
                    echo("db_lib.exists() invalid table passed in");
                    return false;
                }
            }

            //If the query returns a value then return true otherwise return false
            if( $this->conn->query( $strQuery ) != NULL )
            {
                return true ;
            }
            else
            {
                return false ;
            }
        }
    }
?>