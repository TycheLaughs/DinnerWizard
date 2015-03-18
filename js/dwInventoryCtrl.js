/** 
* File: dwInventoryCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
*/
DinnerWizardApp.controller('inventoryController',function($scope, $http, persistentService) {
      
      $scope.list = persistentService.List();
      $scope.oneAtATime = true;
      $scope.message = 'Inventory';
      $scope.temp = '' ;
      $scope.selected = undefined;
      $http.get("data/recipesTest2.json").success(function(data){
         $scope.recipes = data.RECIPES; //assign the array of objects called
       //RECIPES in the json file to a variable named recipes
         $scope.ingredients = data.INGREDIENTS; //assign the array of objects called
       //INGREDIENTS in the json file to a variable named ingredients
        $scope.filterList = data.TAGS; //assign the array of objects called
       //Tags in the json file to a variable named ingredients
      });
      /* call mutators for the arrays stored 'globally' in a service*/
     $scope.clickedFromListing = function(content){
         persistentService.addIngredient(content);    
      };
      
      $scope.clickedFromInventory = function(item){
         persistentService.removeIngredient(item);
      };

      $scope.clearInv = function(){
         persistentService.clearInventory();
         persistentService.filtering($scope.ingredients, persistentService.Tags(), $scope.temp ) ;
      };
      
     
      $scope.search = function(){

         persistentService.filtering($scope.ingredients, persistentService.Tags(), $scope.temp ) ;
      };
      
         
});
