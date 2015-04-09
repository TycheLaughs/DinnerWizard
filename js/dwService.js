/** 
* File: dwService.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely 
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* This service makes the inventory built by users and teh filters/tags they select
* persist for the entire session, or until they refresh the page. References:
* http://onehungrymind.com/angularjs-sticky-notes-pt-1-architecture/
* and
* http://jsfiddle.net/b2fCE/1/
*/
DinnerWizardApp.service('persistentService', function($http, $sce){
   var list = ['Click ingredients to add them to your inventory, or search for them by name.'];
   var tagsList = ['No Search Filters Selected'];
   var restrict = false;
  
   var response ;
   return{
   
   
   
   
   /** toggleCheck
   * Toggles checked status on checkbox
   * @param box the checkbox to toggle
   * This function also sets a Boolean to include in the filtering function  
   */
   toggleCheck:function(box){
      if(box.checked === true){
         box.checked = false;
         console.log('Recipe Restriction box unchecked!');
         restrict = false;
      }
      else {
         box.checked = true;
         console.log('Recipe Restriction box checked!');
         restrict = true;
        
      }
   },
   
   
   
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
        
        if(isPresent >= 0 ){
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
      /** addWithoutTag
      * Function that reports click to console and adds the clicked item to the 
      * inventory list with a concatenated NO as a prefix
      * @param clicked the item initially clicked, a string that should be copied/pushed
      * @param list a private array owned by persistentService, representing a user's Inventory
      * onto which items are pushed
      * @return nothing.  list should now have more items, and the list displayed 
      * by the ng-repeat directive in the list column of the inventory view should reflect that
      */
      addWithoutTag: function(clicked){
         console.log('Selected Without "'+ clicked +'"');
         var item = "NO ";
         item = item.concat(clicked);
         if(tagsList[0] == 'No Search Filters Selected'){
            tagsList.splice(0, 1);//a tag was clicked to add, so remove the user 
            //prompt before adding the item
         }
         var isPresent = tagsList.indexOf(clicked);/* check if the item is already in 
         the constructed list  */
         var isEquipSelect = tagsList.indexOf('Use '+ clicked);
         var isWithout = tagsList.indexOf(item);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0 || isWithout >=0 || isEquipSelect >=0){
            console.log('"' +clicked + '" already selected.');
         }
         else{
            tagsList.push(item);
         }
      },
      /** addEquipTag
      * Function that reports click to console and adds the clicked item to the 
      * inventory list with a concatenated Use as a prefix
      * @param clicked the item initially clicked, a string that should be copied/pushed
      * @param list a private array owned by persistentService, representing a user's Inventory
      * onto which items are pushed
      * @return nothing.  list should now have more items, and the list displayed 
      * by the ng-repeat directive in the list column of the inventory view should reflect that
      */
      addEquipTag: function(clicked){
         console.log('Selected Equipment '+ clicked);
         var item = "Use ";
         item = item.concat(clicked);
         if(tagsList[0] == 'No Search Filters Selected'){
            tagsList.splice(0, 1);//a tag was clicked to add, so remove the user 
            //prompt before adding the item
         }
         
         var isPresent = tagsList.indexOf('Use ' + clicked);/* check if the item is already in 
         the constructed list  */
         var isWithout = tagsList.indexOf('NO ' + clicked);/* check if the item is already in 
         the constructed list  */
        if(isPresent >= 0 || isWithout>=0){
            console.log('Equipment "'+ clicked + '" already selected.');
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
         console.log('Clicked "'+ clicked +'" in selected search tags.');
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
      filtering:function(ingredients, equipment, recTags){

         var filter = {};
         var idFinder = '';
         filter.ingredientTags = [];
         filter.recipeTags = [];
         filter.equipment = [];
         filter.without = [];
         filter.ExclusiveIngredients = restrict;
         //console.log(JSON.stringify(recTags));
         /* found way to return objects from get/post methods in an angular service here: http://stackoverflow.com/questions/12505760/processing-http-response-in-service
         with plunkr here: http://plnkr.co/edit/TTlbSv?p=preview */
         if(list[0] === 'Click ingredients to add them to your inventory, or search for them by name.' && tagsList[0] === 'No Search Filters Selected' ){
            var response = $http.get("php/generate_recipe_json.php")
            .success(function(data) {
               //console.log(JSON.stringify(data) ) ;
               return data.recipes;
           
            })
            .error( function( error ){
                     console.log( error ) ;
               });
            //console.log(JSON.stringify(response));
            return response;
         }
         else{
         //console.log("entered else");
          //test print to see that we are in fact getting the right thing from ingredients param
          //console.log(JSON.stringify(ingredients));
            if ( list[0] !== 'Click ingredients to add them to your inventory, or search for them by name.' ){
               for ( var i = 0; i < list.length; i++ ){ //iterate through array for inventory
                  for(var k = 0; k < ingredients.length; k++){
                     if(ingredients[k].ingredientName === list[i]){
                        idFinder = ingredients[k].id;
                        //console.log(idFinder);
                     }
                  }
                  var ing = {};
                  ing.id = idFinder;
                  ing.name = list[i];
                  filter.ingredientTags.push(ing);
                  //console.log(i + (JSON.stringify(filter.ingredientTags)));
               }
            }
            if ( tagsList[0] !== 'No Search Filters Selected' ){//iterate through array for tags
               for ( var i = 0; i < tagsList.length; i++ ){ 
                  //console.log(tagsList[i].substr(0, 3));
                  if((tagsList[i]).substr(0, 3) !== 'NO ' && (tagsList[i]).substr(0, 4) !== 'Use '){
                  //might have found a regular tag for recipes
                     for(var k = 0; k < recTags.length; k++){
                        for(var j = 0; j < recTags[k].Contents.length; j++){
                           //console.log("comparing "+tagsList[i]+" to " + recTags[k].Contents[j].name);
                           if(recTags[k].Contents[j].name === tagsList[i]){
                              //console.log(JSON.stringify(recTags[k].Contents[j].name) + ": "+ JSON.stringify(recTags[k].id));
                              idFinder = recTags[k].Contents[j].id;
                              //console.log(idFinder);
                           }
                        }
                     }
                     var rec = {};
                     rec.id = idFinder;
                     rec.name = tagsList[i];
                     filter.recipeTags.push(rec);
                  }
                  else if((tagsList[i]).substr(0, 3) === 'NO '){//found a Without tag
                     //console.log('Found a Without tag to process into JSON');
                     var wo = {};
                     var truncWO = tagsList[i].substr(3); //remove the 'NO '
                     //iterate through equipment list (JSON) to see if there's a match (also find ID at this time
                     for (var j = 0; j < equipment.length; j++){
                        if(truncWO === equipment[j].name){// without equipment
                           idFinder = equipment[j].id;
                           wo.name = truncWO;
                           wo.id = idFinder;
                           wo.group = "Equipment";
                        }
                        else{//without ingredient
                           for(var k = 0; k < ingredients.length; k++){
                              if(ingredients[k].ingredientName === truncWO){
                              idFinder = ingredients[k].id;
                              wo.name = truncWO;
                              wo.id = idFinder;
                              wo.group = "Ingredients";
                     //console.log(idFinder);
                              }
                           }
                        }
                     }  
                     filter.without.push(wo);  
                  }
                  else{//we found an equipment tag
                        //find id
                     var equip = {};
                     equip.name = tagsList[i].substr(3);//trim off the first four characters that spell out 'Use '
                     for (var j = 0; j < equipment.length; j++){
                        if(equip.name === equipment[j].name){
                           idFinder = equipment[j].id;
                           equip.name = equipment[j].name;
                           equip.id = idFinder;
                           equip.group = "Equipment";
                        }
                     }  
                     filter.equipment.push(equip);
                  }
               }
            }

             //test print to see what we built
             console.log( "HERE" + JSON.stringify( filter ) );

             //based on code from http://codeforgeek.com/2014/07/angular-post-request-php/
             //Tommy Leedberg - 3/10/2015 - Added steps for doing an http post. Not sure if this will work or not. We'll need to test/debug
               var response = $http.post( "php/request_filter.php", { 'filter': filter } )
                  .success(function(data) {
               //console.log(JSON.stringify(data) ) ;
               return data.recipes;
           
            })
            .error( function( error ){
                     console.log( error ) ;
               });
            //console.log(JSON.stringify(response));
            return response;
         }
      }
   };
});