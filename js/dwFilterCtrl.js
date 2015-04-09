/** 
* File: dwFilterCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
*/
DinnerWizardApp.controller('filterController', function($scope, $http, persistentService) {
        //console.log( "filterController") ;
		$scope.message = 'Recipe Search Filters';
      $scope.wherearewe = 'filters';
      $scope.tags = persistentService.Tags();
      $scope.oneAtATime = true;
      $scope.recipes = [];
      $scope.ingrCats = ['Alternate Protein', 'Meat', 'Seafood', 'Starch', 'Vegetables', 'Spices and Herbs', 'Odds and Ends','Pre-Made']; 
      $scope.buttonClass = "filtStyle";
      $scope.temp = '' ;
     
      $http.get("php/generate_recipe_categories_json.php").success(function(data) {
         $scope.filterList = data.RecipeTags; //assign the array of objects called
      });
      $http.get("php/generate_equipment_json.php").success(function(data) {
         $scope.equipment = data.equipment;
         //Tags in the json file to a variable named ingredients
      });
      $http.get("php/generate_ingredient_json.php").success(function(data) {
         $scope.ingredients = data.ingredients; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
      });
     // $scope.recipes =  persistentService.filtering($scope.ingredients, , $scope.equipment, $scope.filterList) ;
      /* call mutators for the arrays stored 'globally' in a service*/
      persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
      //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
         $scope.recipes = R.data.recipes; 
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         console.log($scope.recipes.length);
      });    
      $scope.clickedFromTagListing = function(item){
         persistentService.addTag(item);    
      };
      $scope.clickedFromWithout= function(item){
         persistentService.addWithoutTag(item);  
      };
      $scope.clickedFromEquip = function(item){
         persistentService.addEquipTag(item);
      }
      $scope.clickedFromSelectedTags = function(item){
         persistentService.removeTag(item);
      }; 
      $scope.clearList = function(){
         persistentService.clearTags();
         //$scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList) ;
          persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
      //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
         $scope.recipes = R.data.recipes; 
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         console.log($scope.recipes.length);
      });    
      };
      $scope.search = function(){
        // $scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList);
         //console.log(JSON.stringify(persistentService.Tags() + persistentService.List()));
      persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
      //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
         $scope.recipes = R.data.recipes; 
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         console.log($scope.recipes.length);
      });    
       };
	});