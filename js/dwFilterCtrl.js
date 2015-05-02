/** 
* File: dwFilterCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 4/08/2015 by Susan Souza to add search function (access filtering() from persistentService)
* 
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
     // console.log("We got this back: " +JSON.stringify(R.data));
      if(JSON.stringify(R.data)==='[]'){
         $scope.recipes = '';
        // console.log("Just to be clear, we got an empty array back: " +JSON.stringify(R.data));
         console.log($scope.recipes.length);
      }
      else{
         $scope.recipes = R.data.recipes; 
         console.log($scope.recipes.length);
         //console.log(JSON.stringify($scope.recipes[0].ingredients));
         //console.log($scope.recipes.length);
         }
      }); 
      
      $scope.clickedFromTagListing = function(item){
         persistentService.addTag(item); 
         /*search();*/
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
      $scope.clickedFromWithout= function(item){
         persistentService.addWithoutTag(item);  
         /*search();*/
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
      $scope.clickedFromEquip = function(item){
         persistentService.addEquipTag(item);
         /*search();*/
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
      $scope.clickedFromSelectedTags = function(item){
         persistentService.removeTag(item);
         /*search();*/
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
      $scope.clearList = function(){
         persistentService.clearTags();
         //$scope.recipes = persistentService.filtering($scope.ingredients, $scope.equipment, $scope.filterList) ;
         /*search();*/
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