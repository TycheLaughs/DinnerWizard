/** 
* File: dwAboutCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  4/11/2015 by Susan Souza for use in the Dinner Wizard application
* Modified: 4/27/2015 by Susan Souza to add modal function for opening the guide from this 'page'
* 
*/
DinnerWizardApp.controller('aboutController',function($scope, $http, $modal, persistentService) {
      
    /** Honestly, this controller does very little aside from exist...*/
    
      $scope.message = 'About';

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
      
      /* Open guide modal from this page; skip the first page, which asks if users want to skip the guide*/
      $scope.openModal = function(page){
   //ref: https://angular-ui.github.io/bootstrap/
         //console.log('opening modal');
         
         $scope.modalInstance = $modal.open({
            templateUrl: 'instructions.html',
            controller: 'instrController',
            resolve: {
               page: function () {
               return page;
               }
            }
         });

      };
   
});
