/** 
* File: dwRecipeCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  3/18/2015 by Susan Souza for use in the Dinner Wizard application
* Re-factored into separate files 3/18/2015. See dwScript.js for earlier revision notes.
* Modified: 3/22/2015 by Susan Souza to add a quick little variable to reflect display changes 
* to the box above Procedure when a recipe is selected from the accordion.
* Modified: 4/02-4/14/2015 by Susan Souza to make a number of fairly small changes necessary 
* once recipe data was received from the DB rather than a flat file.
* Modified: 4/23/2015 by Susan Souza to add Matt's ratio charts and tweak how they are displayed
*/
DinnerWizardApp.controller('recipesController', function($scope, $http, $sce, persistentService)
   {
      $scope.list = persistentService.List();//user's inventory
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
     /* get search filter categories, so we can style them appropriately*/
      $http.get("php/generate_recipe_categories_json.php").success(function(data) {
         $scope.filterList = data.RecipeTags; //assign the array of objects called
         //console.log(JSON.stringify(data));
      });
      /*get equipment for the same reason as the filters above*/
      $http.get("php/generate_equipment_json.php").success(function(data) {
         $scope.equipment = data.equipment;
         //Tags in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
      /*get ingredients partly for the reason above and partly for displaying ingredient combinations/recipes*/
      $http.get("php/generate_ingredient_json.php").success(function(data) {
         $scope.ingredients = data.ingredients; //assign the array of objects called
         //INGREDIENTS in the json file to a variable named ingredients
         //console.log(JSON.stringify(data));
      });
      /*get recipes*/
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
      /*figure out which ingredients should have their own recipes*/
      //$scope.comps = persistentService.Components($scope.recipes, $scope.ingredients);
      for(var i = 0; i < $scope.ingredients.length; i++){
         if($scope.ingredients[i].tags[0].name === 'Pre-Made'){
            $scope.comps.push($scope.ingredients[i].ingredientName);
         }
      }
      //console.log(JSON.stringify($scope.comps));      

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
         }
         else{
            $scope.showMeRecipe = recipeName;
            //$scope.insert = recipeName + " Ingredient Suggested Ratio chart goes here";
            $scope.currentRecipe = recipe;
            $scope.subs = false;
            $scope.comp = false;
            createPieChart($scope.currentRecipe, "ratioChart");
 
         }
         $scope.subs = false;
         $scope.comp = false;
      };
      /* registers that a substitution chevron was clicked. and saves which ingredient it was into a variable so its substitutions can be displayed outside of the accordion scope*/
      $scope.substitutions = function(clickedI){
         $scope.subs = true;
         $scope.comp = false;
         console.log('clicked ' + clickedI +' in ' + $scope.showMeRecipe + $scope.subs);
         $scope.clickedIngr = clickedI;
         
        //$scope.insert = '<div ng-repeat="rec in recipes| filter: {name:showMeRecipe}:true"><ul ng-repeat="ingr in rec.ingredients"><li class ="rec" ng-repeat="item in ingr.replaceableWith">{{item}}</li></ul></div>';
      };
      /* registers that a complex component glyph was clicked. and saves which ingredient it was into a variable so its recipe can be displayed outside of the accordion scope*/
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
   /*   function search(){
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
      };*/
      
      
      
	/* charts to follow */
   

   /**Matt:*/
	// Variable that holds our chart.
	// Originally we just used new Chart and never saved that data to a variable
	// Unfortunately that led to old charts never being deleted.
	// This variable's existence fixes that.
	// Incidentally if it's not set to null at the start here Angular complains.
	var globalChart = null;
      
	function shuffleArray(o)
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
		graphCombinedColors = shuffleArray(graphCombinedColors);
		
		
		// This is the data we'll pass to chart.js.
		// It contains the data that will be used to create our graph
		var graphData = new Array();
		
		// Sort our ingredients in order of ratio to get all the small, insignificant stuff bunched together.
		recipe.ingredients.sort(function(a, b) 
		{  
			var ratioA = typeof(a.ratio) === "undefined"? 0 : a.ratio;	// Safely get our ratios,
			var ratioB = typeof(b.ratio) === "undefined"? 0 : b.ratio;	// guarding against if the ingredient doesn't have one
			return ratioB - ratioA;		// This sort order puts small ingredients at the far end (clockwise) of the graph.
		});
		
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
		// But first destroy our old chart, if we had one
		if (globalChart !== null) globalChart.destroy();
		
		var ctx = document.getElementById(canvasID).getContext("2d");	 
		globalChart = new Chart(ctx).Pie(graphData, { animationEasing : "easeOutQuart", animateScale: true, tooltipTemplate: "<%=label%>" });
	}
	
      
	});