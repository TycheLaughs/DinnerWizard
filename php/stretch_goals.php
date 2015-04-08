
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
