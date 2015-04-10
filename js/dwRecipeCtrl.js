/** 
* File: dwRecipeCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 3/22/2015 by Susan Souza to add a quick little variable to reflect display changes 
* to the box above Procedure when a recipe is selected from the accordion.
*/
DinnerWizardApp.controller('recipesController', function($scope, $http, $sce, persistentService)
   {

      $scope.message = 'Recipe View';
      $scope.oneAtATime = true;
      $scope.showMeRecipe = '';
      $scope.wherearewe = 'recipes';
      $scope.componentRecipe = '';
      $scope.subs = false;//bool to determine if we should be viewing ingredient substitutions
      $scope.comp = false;//bool to determine if we should be viewing complex component recipes
      $scope.comps = [];
      $scope.buttonClass = "recStyle";
      $scope.recipes = [];
      $scope.tags = persistentService.Tags()
      //console.log()
     
      $http.get("php/generate_recipe_categories_json.php").success(function(data) {
         $scope.filterList = data.RecipeTags; //assign the array of objects called
         //console.log(JSON.stringify(data));
      });
      $http.get("php/generate_equipment_json.php").success(function(data) {
         $scope.equipment = data.equipment;
         //Tags in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
      $http.get("php/generate_ingredient_json.php").success(function(data) {
         $scope.ingredients = data.ingredients; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
      persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList).then(function(R){
      //console.log("R.data.recipes: "+JSON.stringify(R.data.recipes));
      console.log("We got this back: " +JSON.stringify(R.data));
      if(JSON.stringify(R.data)==='[]'){
         $scope.recipes = '';
         console.log("Just to be clear, we got an empty array back: " +JSON.stringify(R.data));
         console.log($scope.recipes.length);
      }
      else{
         $scope.recipes = R.data.recipes; 
         console.log($scope.recipes.length);
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         //console.log($scope.recipes.length);
         }
      }); 
      
      //console.log($scope.recipes));
      /*get the mapping between name of ingredient in an ingredient list and name in recipes*/
      //$scope.comps = persistentService.Components($scope.recipes, $scope.ingredients);
      for(var i = 0; i < $scope.ingredients.length; i++){
         if($scope.ingredients[i].tags[0].name === 'Pre-Made'){
            $scope.comps.push($scope.ingredients[i].ingredientName);
         }
      }
console.log(JSON.stringify($scope.comps));      

      /**showRecipe
      * Determines which JSON object to be using based on parameters (gotten via click)
      * and stores this information in a variable for use outside of the accordion's scope
      * @param recipe the recipe name that was clicked
      * @return nothing: showMeRecipe should now have the value from recipe
      */
     $scope.showRecipe = function(recipe){
         console.log( recipe +' clicked.');
         if($scope.showMeRecipe === recipe){
            $scope.insert = '';
            $scope.showMeRecipe = '';
            
         }
         else{
            $scope.showMeRecipe = recipe;
            $scope.insert = recipe + " Ratio chart here";
         }
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
      
      /** magic
      * should produce usable html from html embedded in JSON
      *@param html_text the JSON string that contains markup
      *@return some magical, valid html in my document
      */
      $scope.magic = function(html_text){
         return $sce.trustAsHtml(html_text);
      };
      function search(){
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
	});