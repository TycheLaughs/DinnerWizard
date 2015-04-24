/* instrCtrl.js */ //ref: https://angular-ui.github.io/bootstrap/
DinnerWizardApp.controller('instrController', function($scope, $modalInstance) {
  $scope.guideCount = 0;
  $scope.cont = function () {
   $scope.guideCount++;
  };

  $scope.skip = function () {
    $modalInstance.dismiss('skip');
  };
  $scope.close = function () {
    $modalInstance.dismiss('close');
  };
});