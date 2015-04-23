/* instrCtrl.js */ //ref: https://angular-ui.github.io/bootstrap/
DinnerWizardApp.controller('instrController', function($scope, $modalInstance) {

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
});