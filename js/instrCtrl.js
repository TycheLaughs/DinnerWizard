/* instrCtrl.js */ //ref: https://angular-ui.github.io/bootstrap/
DinnerWizardApp.controller('instrController', function($scope, $modalInstance, page) {
  $scope.guideCount = page;
   //console.log($scope.guideCount);
  $scope.cont = function () {
      $scope.guideCount++;
      //console.log($scope.guideCount);
  };

  $scope.skip = function () {
    $modalInstance.dismiss('skip');
  };
  $scope.close = function () {
    $modalInstance.dismiss('close');
  };
});