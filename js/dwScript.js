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
* Modified 2/22/2015 by Susan Souza to add some more functionality to recipesController
* Modified 2/25/2015 by Susan Souza to implement functions to clear the inventory and search 
* filters arrays (list and tagsList) and to clean up a little.
* Modified 3/9/2015 by Uzi to begin work on generating JSON objects to pass to the PHP function
* that filters recipes
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
         controller  : 'inventoryController'
         
      })
      
               // route for the home/inventory page
      .when('/inventory', {
         templateUrl : 'inventory.html',
         controller  : 'inventoryController'
       
      })

      // route for the recipe view page
      .when('/recipes', {
         templateUrl : 'recipeview.html',
         controller  : 'recipesController'
         
      })

      // route for the contact page
      .when('/filter', {
         templateUrl : 'recipefilters.html',
         controller  : 'filterController'
        
      });
})
/* This service makes the inventory built by users and teh filters/tags they select
persist for the entire session, or until they refresh the page. References:
http://onehungrymind.com/angularjs-sticky-notes-pt-1-architecture/
and
http://jsfiddle.net/b2fCE/1/
*/
DinnerWizardApp.service('persistentService', function($http){
   var list = ['Click ingredients to add them to your inventory, or search for them by name.'];
   var tagsList = ['No Search Filters Selected'];
   var response ;
   return{
   
   /**
      * constructs an array called list to store ingredients selected by one user at a time
      */
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
         if(list[0] == 'Click ingredients to add them to your inventory, or search for them by name.'){
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
               list.push('Click ingredients to add them to your inventory, or search for them by name.');
            }
      },
      /** clearInventory
      * Function that shrinks the array of ingredients to nothing and then adds on the user prompt
      * @return nothing.  list should now have only the prompt.
      */
      clearInventory:function(){
       
         list.length = 0;
         list.push('Click ingredients to add them to your inventory, or search for them by name.');
      },
      /**
      * constructs an array called tagsList to store search filters selected by one user at a time
      */
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
      
      addWithoutTag: function(clicked){
         console.log('Selected "Without '+ clicked +'"');
         var item = "NO ";
         item = item.concat(clicked);
         if(tagsList[0] == 'No Search Filters Selected'){
            tagsList.splice(0, 1);//a tag was clicked to add, so remove the user 
            //prompt before adding the item
         }
         var isPresent = tagsList.indexOf(item);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0){
            console.log('"Without '+ clicked + '" already selected.');
         }
         else{
            tagsList.push(item);
         }
         
      },
      
      addEquipTag: function(clicked){
         console.log('Selected "Equipment '+ clicked +'"');
         var item = "Use ";
         item = item.concat(clicked);
         if(tagsList[0] == 'No Search Filters Selected'){
            tagsList.splice(0, 1);//a tag was clicked to add, so remove the user 
            //prompt before adding the item
         }
         var isPresent = tagsList.indexOf(item);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0){
            console.log('"Equipment '+ clicked + '" already selected.');
         }
         else{
            tagsList.push(item);
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
     /** clearTags
      * Function that shrinks the array of search filters to none and then adds the user prompt
      * @return nothing.  tagsList should now have only the prompt.
      */
      clearTags:function(){
         tagsList.length = 0;
         tagsList.push('No Search Filters Selected');
      
      },
      /** filtering
      * take data from the user-constructed inventory and filters lists,
      * convert it into JSON objects and send if off to query the database
      * references:
      * http://www.keyboardninja.eu/webdevelopment/a-simple-search-with-angularjs-and-php
      * http://stackoverflow.com/questions/19970301/convert-javascript-object-or-array-to-json-for-ajax-data
      */
      filtering:function(ingredients, tags, temp)
      {

          var filter = {};
          var ing = {};
          var rec = {};
          var wo = {};
          var equip = {};
          var idFinder = '';
          filter.ingredientTags = [];
          filter.recipeTags = [];
          filter.equipment = [];
          filter.without = [];
          //test print to see that we are in fact getting the right thing from ingredients param
          //console.log(JSON.stringify(ingredients));
         if ( list[0] !== 'Click ingredients to add them to your inventory, or search for them by name.' )
         {
            for ( var i = 0; i < list.length; i++ )
            { //iterate through array for inventory
               for(var k = 0; k < ingredients.length; k++){
                  if(ingredients[k].name === list[i]){
                     idFinder = ingredients[k].id;
                     //console.log(idFinder);
                  }
               }
               var ing = {};
              ing.id = idFinder;
              ing.name = list[i];
              filter.ingredientTags.push(ing);
              // console.log(i + (JSON.stringify(filter.ingredientTags)));
            }
         }
         if ( tagsList[0] !== 'No Search Filters Selected' )
         {//iterate through array for tags
            for ( var i = 0; i < tagsList.length; i++ )
            { 
               //console.log(tagsList[i].substr(0, 3));
               if((tagsList[i]).substr(0, 3) !== 'NO ' && (tagsList[i]).substr(0, 4) !== 'Use '){
                  for(var k = 0; k < tags.length; k++){
                     if(tags[k].name === tagsList[i]){
                        idFinder = tags[k].id;
                        //console.log(idFinder);
                     }
                  }
                  var rec = {};
                  rec.id = idFinder;
                  rec.name = tagsList[i];
                  filter.recipeTags.push(rec);
               }
               else if((tagsList[i]).substr(0, 3) !== 'NO '){
                  console.log('Found a Without tag to process into JSON');
                    //is it an ingredient or equipment item?
                    //determine which and find id
                     var wo = {};
                     wo.name = tagsList[i].substr(3); //remove the 'NO '
                     //wo.id = idFinder;
                     //filter.without.push(wo);
                     
                  }
                  else{//we found an equipment tag
                     //find id
                     var equip = {};
                     equip.name = tagsList[i].substr(4);//trim off the first four characters that spell out 'Use '
                     //equip.id=idFinder;
                     //filter.equipment.push(equip);
                  }
            }
         }

          //test print to see what we built
          console.log( "HERE" + JSON.stringify( filter ) )

          //Tommy Leedberg - 3/10/2015 - Added steps for doing an http post. not sure if this will work or not well need to test/debug
          //based on code from http://codeforgeek.com/2014/07/angular-post-request-php/
            $http.post( "/dinnerwizard/php/request_filter.php", { 'filter': filter } ).
            success( function( data )
            {

                console.log( data ) ;

            }).error( function( error )
            {
               console.log( error ) ;
            });

      }
   };
});



// create each controller and inject Angular's $scope

//Inventory Builder
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
      };
      
     
       $scope.search = function(){

           persistentService.filtering($scope.ingredients, persistentService.Tags(), $scope.temp ) ;
       };
      
         
});

   
//Recipe Filtering
	DinnerWizardApp.controller('filterController', function($scope, $http, persistentService) {
        //console.log( "filterController") ;
		$scope.message = 'Recipe Search Filters';
      $scope.tags = persistentService.Tags();
      $scope.oneAtATime = true;
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
      };
      $scope.search = function(){
         persistentService.filtering($scope.ingredients, persistentService.Tags());
       };
	});
   
   //Recipe Selection and Browsing
	DinnerWizardApp.controller('recipesController', function($scope, $http, persistentService)
    {

        $scope.message = 'Recipe View';
        $scope.oneAtATime = true;
        $scope.showMeRecipe = '';

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