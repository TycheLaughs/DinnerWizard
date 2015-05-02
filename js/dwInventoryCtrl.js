/** 
* File: dwInventoryCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 4/09/2015 by Susan Souza: recipe search added to get recipes from DB
* Modified: 4/24/2015 to make a small change that should preserve the active styling when the 'Restrict Search' button is checked
*/
DinnerWizardApp.controller('inventoryController',function($scope, $http, $modal, persistentService) {
      
      $scope.list = persistentService.List();// holds user inventory
      $scope.oneAtATime = true;
      $scope.message = 'Inventory';
      $scope.temp = '' ;
      $scope.wherearewe = 'inventory';
      $scope.box = document.getElementById('exclusionBox');
      $scope.recipes = [];
      $scope.ingrCats = ['Alternate Protein', 'Meat', 'Seafood', 'Starch', 'Vegetables', 'Spices and Herbs', 'Odds and Ends','Pre-Made']; 
      $scope.buttonClass = "invStyle";
      $scope.selected = undefined;
      $scope.toggled = 0;
      $scope.clickedHeader = '';
      $scope.restricted = persistentService.isItChecked();
      $scope.search = function(){
   
     // $scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList);
      //console.log(JSON.stringify(persistentService.Tags() + persistentService.List()));
      
      
      /* gets an array of recipe objects from the DB*/
         persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
         //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
         //console.log("We got this back: " +JSON.stringify(R.data));
         if(JSON.stringify(R.data)==='[]'){
            $scope.recipes = '';
            //console.log("Just to be clear, we got an empty array back: " +JSON.stringify(R.data));
            console.log($scope.recipes.length);
         }
         else{
            $scope.recipes = R.data.recipes; 
            console.log($scope.recipes.length);
            //console.log(JSON.stringify($scope.recipes[0].ingredients));
            //console.log($scope.recipes.length);
         }
      }); 
      
   };
      /* old recipe getting function. just gets all of them without any search parameters*/
      /*$http.get("php/generate_recipe_json.php").success(function(data) {
         $scope.recipes = data.recipes; //assign the array of objects called
         //RECIPES in the json file to a variable named recipes 
         console.log(JSON.stringify(data));
      });*/
      
      /*gets categories for search filters that aren't equipment or ingredients*/
      $http.get("php/generate_recipe_categories_json.php").success(function(data) {
         $scope.filterList = data.RecipeTags; 
         //console.log(JSON.stringify(data));
      });
      /*gets list of equipment from DB*/
      $http.get("php/generate_equipment_json.php").success(function(data) {
         $scope.equipment = data.equipment;
        // console.log(JSON.stringify(data));
         //Tags in the json file to a variable named ingredients
      });
      
      /*gets array of ingredients from DB*/
      $http.get("php/generate_ingredient_json.php").success(function(data) {
         $scope.ingredients = data.ingredients; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
      /*the following gets recipe objects from the DB */
      persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
      //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
     //console.log("We got this back: " +JSON.stringify(R.data));
         if(JSON.stringify(R.data)==='[]'){
            $scope.recipes = '';
            //console.log("Just to be clear, we got an empty array back: " +JSON.stringify(R.data));
            console.log($scope.recipes.length);
         }
         else{
            $scope.recipes = R.data.recipes; 
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         //console.log($scope.recipes.length);
         }
      }); 
      
      
      /* call mutators for the arrays stored 'globally' in a service*/
     $scope.clickedFromListing = function(content){
         persistentService.addIngredient(content);   
         $scope.search();       
      };
      
      /* removes an ingredient from user inventory*/
      $scope.clickedFromInventory = function(item){
         persistentService.removeIngredient(item);
         $scope.search(); 
         
      };
      
      /*removes all ingredients from user inventory*/
      $scope.clearInv = function(){
         persistentService.clearInventory();
        // $scope.recipes=  persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList) ;  
        $scope.search();
      };
      //This function checks the checkbox hidden by the 'Restrict Search' button
      $scope.checkIt = function(){
         persistentService.toggleCheck($scope.box);
         $scope.restricted = persistentService.isItChecked();
         console.log($scope.restricted);
      };  
      
      //while thsi seems identical to teh function on the fitlers page, putting this function on a 'button's' ng-click doesn't make it 
      //work in a way that is immediately displayed in the button at the top right.  This is a scope issue, and will requite a small 
      //bit of refactoring, namely having another controller to wrap the navigation buttons in and possibly using $emit
     /*function search(){
         // $scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList);
         //console.log(JSON.stringify(persistentService.Tags() + persistentService.List()));
         persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
            //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
            //console.log("We got this back: " +JSON.stringify(R.data));
            if(JSON.stringify(R.data)==='[]'){
               $scope.recipes = '';
               //console.log("Just to be clear, we got an empty array back: " +JSON.stringify(R.data));
               console.log($scope.recipes.length);
            }
            else{
               $scope.recipes = R.data.recipes; 
               console.log($scope.recipes.length);
               //console.log(JSON.stringify($scope.recipes[0].ingredients));
               //console.log($scope.recipes.length);
            }
         }); 
      };
      */
      
});
         
