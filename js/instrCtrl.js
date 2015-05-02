/** 
* File:  instrCtrl.js
* Project Team: Susan Souza, Tommy Leedberg, Matthew Szekely
* 91.462 GUI Programming II, Prof. Heines, University of Massachusetts Lowell
* Created:  4/25/2015 by Susan Souza for use in the Dinner Wizard application
* 
*/ //ref: https://angular-ui.github.io/bootstrap/
DinnerWizardApp.controller('instrController', function($scope, $modalInstance, page) {
  
  //track which 'page' we're on in the guide so we can go forward or back
  $scope.guideCount = page;
   //console.log($scope.guideCount);
   
   //move forward through the guide
  $scope.cont = function () {
      $scope.guideCount++;
      //console.log($scope.guideCount);
  };
  //move backward through the guide
   $scope.back = function(){
      $scope.guideCount--;
   };
   
   //leave  the guide
  $scope.skip = function () {
    $modalInstance.dismiss('skip');
  };
  $scope.close = function () {
    $modalInstance.dismiss('close');
  };
});