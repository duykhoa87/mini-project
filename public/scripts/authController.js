/**
 * Created by Duy Khoa on 5/31/2016.
 */
app.controller('AuthController', function ($auth, $state, $http, $rootScope, $scope) {

    $scope.email = '';
    $scope.password = '';
    $scope.newUser = {};
    $scope.error = false;
    $scope.errorText = '';

    $scope.login = function () {
        var credentials = {
            email: $scope.email,
            password: $scope.password
        }

        $auth.login(credentials).then(function () {

            return $http.get('api/authenticate/user');

        }, function (error) {
            $scope.error = true;
            $scope.errorText = error.data.error;

        }).then(function (response) {
            $rootScope.currentUser = response.data.user;
            $rootScope.authenticated = true;

            var user = JSON.stringify(response.data.user);
            localStorage.setItem('user', user);

            $state.go('event');
        });
    }

    $scope.register = function () {
        $http.post('api/register', $scope.newUser)
            .success(function (data) {
                if(data._id) {
                    $scope.email = $scope.newUser.email;
                    $scope.password = $scope.newUser.password;
                    $scope.login();
                }else{
                    $scope.error = true;
                    $scope.errorText = data;
                }
            })
    };

});