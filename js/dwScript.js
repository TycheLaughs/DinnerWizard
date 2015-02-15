/**
* File: dwScript.js
* Project Team: Susan Souza, 
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  2/12/2015 by Susan Souza for use in the Dinner Wizard application
* Modified: 2/14/2015 by Susan Souza to add inventory JSON test file and initial 
* click functions.
* Modified: 2/15/2015 by Susan Souza to further add functionality to inventory-building 
* functions and inventoryController.
*
*
* 
* code for routing initially found here, with initial comments:
* http://plnkr.co/edit/dd8Nk9PDFotCQu4yrnDg?p=preview
*/
// create the module
var DinnerWizardApp = angular.module('DinnerWizardApp', ['ngRoute', 'ui.bootstrap']);

// configure our routes
DinnerWizardApp.config(function($routeProvider) {
   $routeProvider

      // route for the home/inventory page
      .when('/', {
         templateUrl : 'inventory.html',
         controller  : 'inventoryController',
         
      })
      
               // route for the home/inventory page
      .when('/inventory', {
         templateUrl : 'inventory.html',
         controller  : 'inventoryController',
       
      })

      // route for the recipe view page
      .when('/recipes', {
         templateUrl : 'recipeview.html',
         controller  : 'recipesController',
         
      })

      // route for the contact page
      .when('/filter', {
         templateUrl : 'recipefilters.html',
         controller  : 'filterController',
        
      });
});

// create each controller and inject Angular's $scope

//Inventory Builder
DinnerWizardApp.controller('inventoryController', function($scope, $http) {
   $scope.oneAtATime = true;
   $scope.list =['Click ingredients to add them to your inventory'];//user prompt to build inventory
   $scope.message = 'Inventory';
   $http.get("data/ingredientsTest.json").success(function(data){
      $scope.ingredients = data.INGREDIENTS; //assign the array of objects called 
       //Ingredients in the json file to a variable named ingredients
   });
   $scope.status = {
      isFirstOpen: true,
   };
   /** clickedFromListing
   * Function that reports click to console and adds the clicked item to the 
   * inventory list
   * @param content the item initially clicked, a string that should be copied/pushed
   * @param list  $scope.list, a public array owned by inventoryController 
   * onto which items are pushed
   * @return nothing.  $scope.list should now have more items, and the list displayed 
   * by the ng-repeat directive in the list column should reflect that
   * reference: 
   * http://www.intelligrape.com/blog/angularjs-adding-items-to-a-javascript-list-and-updating-corresponding-dom-dynamically/
   */
   $scope.clickedFromListing = function(content, list){
      console.log('Clicked '+ content);
      if($scope.list[0] == 'Click ingredients to add them to your inventory'){
         $scope.list.splice(0, 1);//an ingredient was clicked to add, so remove the user 
         //prompt before adding the item
      }
      var isPresent = $scope.list.indexOf(content);/* check if the item is already in 
      the constructed list  */
      if(isPresent >= 0){
         console.log(content + ' already in list.');
      }
      else{
         $scope.list.push(content);  
      }         
   };
   
   /** clickedFromInventory
   * Function that reports click to console and removes the clicked item from the 
   * inventory list
   * @param item the item initially clicked, a string that should be copied/pushed
   * @param list  $scope.list, a public array owned by inventoryController 
   * from which items are removed
   * @return nothing.  $scope.list should now have fewer items, and the list displayed 
   * by the ng-repeat directive in the list column should reflect that
   * reference: 
   * http://stackoverflow.com/questions/18303040/how-to-remove-elements-nodes-from-angular-js-array
   */
   $scope.clickedFromInventory = function(item, list){
      var itemIndex = $scope.list.indexOf(item);/*index found this way to avoid an issue found that removed 
      items starting at index zero regardless of which item was clicked.*/
      console.log('Clicked '+ item +' in contructed inventory.');
      $scope.list.splice(itemIndex, 1); 
         if($scope.list.length === 0){//if array is empty, print user prompt
            $scope.list.push('Click ingredients to add them to your inventory');
         }
   };

});

//Recipe Selection and Browsing
	DinnerWizardApp.controller('recipesController', function($scope, $http) {
		$scope.message = 'Recipe View';
	});
   
//Recipe Filtering
	DinnerWizardApp.controller('filterController', function($scope, $http) {
		$scope.message = 'Filter Recipes View';
	});