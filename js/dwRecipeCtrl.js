/** 
* File: dwRecipeCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
*/
DinnerWizardApp.controller('recipesController', function($scope, $http, persistentService)
   {

      $scope.message = 'Recipe View';
      $scope.oneAtATime = true;
      $scope.showMeRecipe = '';

      $scope.buttonClass = "recStyle";

      $scope.tags = persistentService.Tags()
      //console.log()
      $http.get("data/recipesTest2.json").success(function(data) {
         $scope.recipes = data.RECIPES; //assign the array of objects called
         //RECIPES in the json file to a variable named recipes
         $scope.ingredients = data.INGREDIENTS; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         $scope.filterList = data.TAGS; //assign the array of objects called
         //Tags in the json file to a variable named ingredients
      });

      /**showRecipe
      * Determines which JSON object to be using based on parameters (gotten via click)
      * and stores this information in a variable for use outside of the accordion's scope
      * @param recipe the recipe name that was clicked
      * @return nothing: showMeRecipe should now have the value from recipe
      */
     $scope.showRecipe = function(recipe){
         console.log( recipe +' clicked.');
         $scope.showMeRecipe = recipe;

      };
	});