/** 
* File: dwInventoryCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
*/
DinnerWizardApp.controller('inventoryController',function($scope, $http, $modal, persistentService) {
      
      $scope.list = persistentService.List();
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
     

      /*$http.get("php/generate_recipe_json.php").success(function(data) {
         $scope.recipes = data.recipes; //assign the array of objects called
         //RECIPES in the json file to a variable named recipes 
         console.log(JSON.stringify(data));
      });*/
      $http.get("php/generate_recipe_categories_json.php").success(function(data) {
         $scope.filterList = data.RecipeTags; //assign the array of objects called
         //console.log(JSON.stringify(data));
      });
      $http.get("php/generate_equipment_json.php").success(function(data) {
         $scope.equipment = data.equipment;
        // console.log(JSON.stringify(data));
         //Tags in the json file to a variable named ingredients
      });
      $http.get("php/generate_ingredient_json.php").success(function(data) {
         $scope.ingredients = data.ingredients; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
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
         search();        
      };
      
      $scope.clickedFromInventory = function(item){
         persistentService.removeIngredient(item);
         search();
         
      };

      $scope.clearInv = function(){
         persistentService.clearInventory();
        // $scope.recipes=  persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList) ;  
        search();
      };
      
        $scope.checkIt = function(){
         persistentService.toggleCheck($scope.box);
         $scope.restricted = persistentService.isItChecked();
         console.log($scope.restricted);
      };  
      function search(){
      // $scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList);
      //console.log(JSON.stringify(persistentService.Tags() + persistentService.List()));
      $emit.persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
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
});
         
