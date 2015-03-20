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
      $scope.tags = persistentService.Tags();
      $scope.oneAtATime = true;

      $scope.buttonClass = "filtStyle";
    
      $scope.temp = '' ;
       $http.get("data/recipesTest2.json").success(function(data){
         $scope.recipes = data.RECIPES; //assign the array of objects called
       //RECIPES in the json file to a variable named recipes
         $scope.ingredients = data.INGREDIENTS; //assign the array of objects called
       //INGREDIENTS in the json file to a variable named ingredients
        $scope.filterList = data.TAGS; //assign the array of objects called
       //Tags in the json file to a variable named ingredients
      });
     
      /* call mutators for the arrays stored 'globally' in a service*/
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
         persistentService.filtering($scope.ingredients, persistentService.Tags(), $scope.temp ) ;
      };
      $scope.search = function(){
         persistentService.filtering($scope.ingredients, persistentService.Tags());
       };
	});