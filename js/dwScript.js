/**
* File: dwScript.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
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
* Modified 3/9/2015 by Susan Souza to begin work on generating JSON objects to pass to the PHP function
* that filters recipes
* Modified: 3/18/2015 by Susan Souza to begin to address Equipment and Without tags, both for user
* selection and to send to the back end. Moved service, controllers to different files as per 
* suggestions from style guide: https://github.com/mgechev/angularjs-style-guide#general
* 
*/
// create the module
var DinnerWizardApp = angular.module('DinnerWizardApp', ['ngRoute', 'ui.bootstrap', 'ngTouch']);

/** code for routing initially found here, with initial comments:
* http://plnkr.co/edit/dd8Nk9PDFotCQu4yrnDg?p=preview
*/
// configure our routes
DinnerWizardApp.config(function($routeProvider, $locationProvider) {
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
})/*
.run(function($rootScope, $locatione){

   $rootScope.$on( "$routeChangeSuccess", function() {
      var wearehere = $location.path();
      console.log(wearehere.substr(1));
   });
})*/;




// create each controller and inject Angular's $scope

//Inventory Builder

   
//Recipe Filtering
	
   
   //Recipe Selection and Browsing
	