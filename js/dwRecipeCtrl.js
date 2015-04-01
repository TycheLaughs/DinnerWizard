/** 
* File: dwRecipeCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 3/22/2015 by Susan Souza to add a quick little variable to reflect display changes 
* to the box above Procedure when a recipe is selected from the accordion.
*/
DinnerWizardApp.controller('recipesController', function($scope, $http, persistentService)
   {

      $scope.message = 'Recipe View';
      $scope.oneAtATime = true;
      $scope.showMeRecipe = '';
      $scope.componentRecipe = '';
      $scope.subs = false;//bool to determine if we should be viewing ingredient substitutions
      $scope.comp = false;//bool to determine if we should be viewing complex component recipes
      $scope.comps = [];
      $scope.buttonClass = "recStyle";

      $scope.tags = persistentService.Tags()
      //console.log()
      $http.get("data/recipes.json").success(function(data) {
         $scope.recipes = data.recipes; //assign the array of objects called
         //RECIPES in the json file to a variable named recipes
        
      });
       $http.get("data/recipesTest2.json").success(function(data) {
       $scope.ingredients = data.INGREDIENTS; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         $scope.filterList = data.TAGS; //assign the array of objects called
         //Tags in the json file to a variable named ingredients
      });
      
      //console.log($scope.recipes));
      /*get the mapping between name of ingredient in an ingredient list and name in recipes*/
      $scope.comps = persistentService.Components($scope.recipes, $scope.ingredients);
      
      /**showRecipe
      * Determines which JSON object to be using based on parameters (gotten via click)
      * and stores this information in a variable for use outside of the accordion's scope
      * @param recipe the recipe name that was clicked
      * @return nothing: showMeRecipe should now have the value from recipe
      */
     $scope.showRecipe = function(recipe){
         console.log( recipe +' clicked.');
         $scope.showMeRecipe = recipe;
         $scope.insert = recipe + " Ratio chart here";
         $scope.subs = false;
         $scope.comp = false;
      };
      
      $scope.substitutions = function(clickedI){
         $scope.subs = true;
         console.log('clicked ' + clickedI +' in ' + $scope.showMeRecipe + $scope.subs);
         $scope.clickedIngr = clickedI;
         
        //$scope.insert = '<div ng-repeat="rec in recipes| filter: {name:showMeRecipe}:true"><ul ng-repeat="ingr in rec.ingredients"><li class ="rec" ng-repeat="item in ingr.replaceableWith">{{item}}</li></ul></div>';
      };
      $scope.showComponent = function(clicked){
         $scope.comp = true;
         $scope.subs = false;
         console.log('clicked ' + clicked +' in ' + $scope.showMeRecipe +' subs: ' + $scope.subs+ ', comp: '+$scope.comp);
         $scope.componentRecipe = clicked;
      };
      
	});