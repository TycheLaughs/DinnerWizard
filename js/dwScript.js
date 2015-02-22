/**
* File: dwScript.js
* Project Team: Susan Souza, 
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  2/12/2015 by Susan Souza for use in the Dinner Wizard application
* Modified: 2/14/2015 by Susan Souza to add inventory JSON test file and initial 
* click functions.
* Modified: 2/15/2015 by Susan Souza to further add functionality to inventory-building 
* functions and inventoryController.
* Modified: 2/21/2015 by Susan Souza to add persistentService service for use in having 
* persistent Inventories and search filters per session and be able to access that informaion 
* across multiple controller $scopes.  Made controllers reference functions and data from 
* persistentService rather than large functins within each controller.  Added basic filter
* click interactivity along same lines as that from inventory building.
*
* 
*/
// create the module
var DinnerWizardApp = angular.module('DinnerWizardApp', ['ngRoute', 'ui.bootstrap']);

/** code for routing initially found here, with initial comments:
* http://plnkr.co/edit/dd8Nk9PDFotCQu4yrnDg?p=preview
*/
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
})
/* This service makes the inventory built by users and teh filters/tags they select
persist for the entire session, or until they refresh the page. References:
http://onehungrymind.com/angularjs-sticky-notes-pt-1-architecture/
and
http://jsfiddle.net/b2fCE/1/
*/
DinnerWizardApp.service('persistentService', function(){
   var list = ['Click ingredients to add them to your inventory'];
   var tagsList = ['No Search Filters Selected'];
   return{
      List:function(){
         return list;
      },
      /** addIngredient
      * Function that reports click to console and adds the clicked item to the 
      * inventory list
      * @param clicked the item initially clicked, a string that should be copied/pushed
      * @param list a private array owned by persistentService, representing a user's Inventory
      * onto which items are pushed
      * @return nothing.  list should now have more items, and the list displayed 
      * by the ng-repeat directive in the list column of the inventory view should reflect that
      * reference: 
      * http://www.intelligrape.com/blog/angularjs-adding-items-to-a-javascript-list-and-updating-corresponding-dom-dynamically/
      */
      addIngredient:function(clicked){
         console.log('Clicked '+ clicked);
         if(list[0] == 'Click ingredients to add them to your inventory'){
            list.splice(0, 1);//an ingredient was clicked to add, so remove the user 
            //prompt before adding the item
         }
         var isPresent = list.indexOf(clicked);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0){
            console.log(clicked + ' already in list.');
         }
         else{
            list.push(clicked);
         }
      },
      /** removeIngredient
      * Function that reports click to console and removes the clicked item from the 
      * inventory list
      * @param item the item initially clicked, a string that should be copied/pushed
      * @param list a private array owned by persistentService, representing a user's Inventory
      * from which items are removed
      * @return nothing.  list should now have fewer items, and the list displayed 
      * by the ng-repeat directive in the list column should reflect that
      * reference: 
      * http://stackoverflow.com/questions/18303040/how-to-remove-elements-nodes-from-angular-js-array
      */
      removeIngredient:function(clicked){
      var itemIndex = list.indexOf(clicked);/*index found this way to avoid an issue found that removed 
         items starting at index zero regardless of which item was clicked.*/
         console.log('Clicked '+ clicked +' in contructed inventory.');
            list.splice(itemIndex, 1); 
            if(list.length === 0){//if array is empty, print user prompt
               list.push('Click ingredients to add them to your inventory');
            }
      },
      clearIventory:function(){
      
      },
      Tags:function(){
         return tagsList;
      },
      /** addTag
      * Function that reports click to console and adds the clicked item to the 
      * inventory list
      * @param clicked the item initially clicked, a string that should be copied/pushed
      * @param list a private array owned by persistentService, representing a user's Inventory
      * onto which items are pushed
      * @return nothing.  list should now have more items, and the list displayed 
      * by the ng-repeat directive in the list column of the inventory view should reflect that
      * reference: 
      * http://www.intelligrape.com/blog/angularjs-adding-items-to-a-javascript-list-and-updating-corresponding-dom-dynamically/
      */
      addTag:function(clicked){
         console.log('Clicked '+ clicked);
         if(tagsList[0] == 'No Search Filters Selected'){
            tagsList.splice(0, 1);//a tag was clicked to add, so remove the user 
            //prompt before adding the item
         }
         var isPresent = tagsList.indexOf(clicked);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0){
            console.log(clicked + ' already selected.');
         }
         else{
            tagsList.push(clicked);
         }
      },
      /** removeTag
      * Function that reports click to console and removes the clicked item from the 
      * search filters
      * @param item the item initially clicked, a string that should be copied/pushed
      * @param tagsList a private array owned by persistentService, representing the list of search filters
      * the user has selected
      * @return nothing.  tagsList should now have fewer items, and the list displayed 
      * by the ng-repeat directive in the list column should reflect that
      * reference: 
      * http://stackoverflow.com/questions/18303040/how-to-remove-elements-nodes-from-angular-js-array
      */
      removeTag:function(clicked){
      var itemIndex = tagsList.indexOf(clicked);/*index found this way to avoid an issue found that removed 
         items starting at index zero regardless of which item was clicked.*/
         console.log('Clicked '+ clicked +' in selected search tags.');
            tagsList.splice(itemIndex, 1); 
            if(tagsList.length === 0){//if array is empty, print user prompt
               tagsList.push('No Search Filters Selected');
            }
      
      },
      clearTags:function(){
      
      }

   };
});

// create each controller and inject Angular's $scope

//Inventory Builder
DinnerWizardApp.controller('inventoryController',function($scope, $http, persistentService) {

      $scope.list = persistentService.List();
      $scope.oneAtATime = true;
      $scope.message = 'Inventory';
      $http.get("data/ingredientsTest.json").success(function(data){
         $scope.ingredients = data.INGREDIENTS; //assign the array of objects called 
       //Ingredients in the json file to a variable named ingredients
      });

      
     $scope.clickedFromListing = function(content){
         persistentService.addIngredient(content);    
      };
      
      $scope.clickedFromInventory = function(item){
         persistentService.removeIngredient(item);
      };
});

   
//Recipe Filtering
	DinnerWizardApp.controller('filterController', function($scope, $http, persistentService) {
		$scope.message = 'Recipe Search Filters';
      $scope.tags = persistentService.Tags();
      $scope.oneAtATime = true;
      $http.get("data/taglistTest.json").success(function(data){
      $scope.filterList = data.TAGS; //assign the array of objects called 
       //Tags in the json file to a variable named ingredients
      });

       $scope.clickedFromTagListing = function(item){
         persistentService.addTag(item);    
      };
      
      $scope.clickedFromSelectedTags = function(item){
         persistentService.removeTag(item);
      }; 
	});
   
   //Recipe Selection and Browsing
	DinnerWizardApp.controller('recipesController', function($scope, $http, persistentService) {
		$scope.message = 'Recipe View';
      $scope.oneAtATime = true;
      $scope.showMeRecipe = '';
      $scope.tags = persistentService.Tags();
      $http.get("data/recipesThreeTest.json").success(function(data){
         $scope.recipes = data.RECIPES; //assign the array of objects called 
       //Recipes in the json file to a variable named recipes
      });
      $scope.showRecipe = function(recipe){
         console.log( recipe +' clicked.');
         $scope.showMeRecipe = recipe;
         //console.log($scope.showMeRecipe + ' is the variable we set.');
         
      };
      
      
      
	});