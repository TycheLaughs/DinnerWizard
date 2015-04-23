/** 
* File: dwRecipeCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 3/22/2015 by Susan Souza to add a quick little variable to reflect display changes 
* to the box above Procedure when a recipe is selected from the accordion.





* Modified: 4/23/2015 by Susan Souza to add Matt's ratio charts and tweak how they are displayed
*/
DinnerWizardApp.controller('recipesController', function($scope, $http, $sce, persistentService)
   {
      $scope.list = persistentService.List();
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
      $scope.tags = persistentService.Tags();
      $scope.currentRecipe = {};

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
     $scope.showRecipe = function(recipeName, recipe){
         console.log( recipeName +' clicked.');
         if($scope.showMeRecipe === recipeName){
            $scope.insert = '';
            $scope.showMeRecipe = '';
            clearCanvas(); //added to avoid some isses where created charts would overlap, 
            //causing hover-related bugs
            
         }
         else{
            
            $scope.showMeRecipe = recipeName;
            //$scope.insert = recipeName + " Ingredient Suggested Ratio chart goes here";
            $scope.currentRecipe = recipe;
            clearCanvas(); //added to avoid some isses where created charts would overlap, 
            //causing hover-related bugs
            foo();
          
          
            
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
      
      
      
      /* charts to follow */
      
      function shuffle(o)
		{ 
			for (var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
			return o;
		};
		
		// Pass this function a recipe object and the string ID of a canvas and a pie chart will be placed inside it.
		function createPieChart(recipe, canvasID)
		{			
			// These are the colors the graph will use. 
			// They're hardcoded right now. Could we dynamically generate nice-looking colors...?
			
			//							Dark red,  Blue       Green      Light red   Cyan      Orange-ish  Dark gray  Purple   
			var graphNormalColors =    ["#992F0F", "#4D53A3", "#00A719", "#F7464A", "#46BFBD", "#FDB45C", "#4D5360", "#8C2BAF"];
			var graphHighlightColors = ["#CC3C14", "#616ACC", "#00DB19", "#FF5A5E", "#5AD3D1", "#FFC870", "#616774", "#A333CC"];

			// Combine the arrays into one.
			// We shuffle the colors to add some element of randomness,
			// But if we shuffle each array individually we'll get strange results with the colors seemingly changing randomly when highlighted.
			// Sure would be nice if we could set Math.random()'s seed...
			var graphCombinedColors = new Array();
			for (var i = 0; i < graphNormalColors.length; i++)
			{
				graphCombinedColors[i] = { normalColor: graphNormalColors[i], highlightColor: graphHighlightColors[i] };
			}
			graphCombinedColors = shuffle(graphCombinedColors);
			
			
			// This is the data we'll pass to chart.js.
			// It contains the data that will be used to create our graph
			var graphData = new Array();
			
			var ingredientCount = recipe.ingredients.length;
			
			// Need this because not all recipe ratios add up to 100.
			var ratioMaxValue = 0;
			
			// First, find out what the "total" ratio is 
			// (ideally it should be close to 100, but that's not always the case)
			for (var i = 0; i < ingredientCount; i++)
			{
				if (typeof(recipe.ingredients[i].ratio) === "undefined")
					continue;
					
				// Negative ratios should not exist, but just in case...
				// Also remove really tiny ratios, since they're hard to see and select.
				if (recipe.ingredients[i].ratio <= 1)
					continue;
				
				ratioMaxValue += recipe.ingredients[i].ratio;
			}
			
			// Now with that information, we can build our graph data.
			
			var colorIndex = 0;
			for (var i = 0; i < ingredientCount; i++)
			{
				if (typeof(recipe.ingredients[i].ratio) === "undefined")
					continue;
				if (recipe.ingredients[i].ratio <= 1)
					continue;
				
				
				var correctedRatio = recipe.ingredients[i].ratio / ratioMaxValue;
				
				// Just in case, make sure we don't run past the end of the color array.
				// We really shouldn't need to, though--this is just in case.
				var normalColor, highlightColor;
				normalColor = typeof(graphCombinedColors[colorIndex]) === "undefined"? "#000" : graphCombinedColors[colorIndex].normalColor;
				highlightColor = typeof(graphCombinedColors[colorIndex]) === "undefined"? "#333" : graphCombinedColors[colorIndex].highlightColor;
				
				graphData[graphData.length] = { 
					value: correctedRatio,
					color: normalColor,
					highlight: highlightColor,
					label: recipe.ingredients[i].name + ": " + (correctedRatio * 100).toFixed(0).toString() + "%"
				};
				
				colorIndex++;
			}
			
			
			// Finally, create our pie chart.
			var ctx = document.getElementById(canvasID).getContext("2d");	
/* HERE--------------------------------------> */         
			new Chart(ctx).Pie(graphData, { animationEasing : "easeOutQuart", animateScale: true, tooltipTemplate: "<%=label%>" });
		}
      
      function foo() 
		{
			// Then just call this. 
			// The second argument is the element ID of the canvas the chart will be drawn in.
			createPieChart($scope.currentRecipe, "ratioChart");
			
		}
      function clearCanvas(){
         var canv = document.getElementById("ratioChart");
         var ctx = canv.getContext("2d");	
         ctx.clearRect(0, 0, canv.width, canv.height);
   
      };
	});