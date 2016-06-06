/**
 * Created by Duy Khoa on 5/31/2016.
 */
app.controller('UserController', function ($state, $http, $rootScope, $scope, $stateParams) {
    $scope.users = [];

    if($state.is('user')) {
        $http.get('/api/user').success(function (data) {
            $scope.users = data;
        })
    }

    if($state.is('edit-user')) {
        $http.get('/api/user/' + $stateParams.userId).success(function (data) {
            $scope.user = data;
        })
    }

    $scope.save = function (id) {
        $http.post('/api/user/update/' + id, $scope.user).success(function (data) {
            $state.go('user');
        });
    };
});