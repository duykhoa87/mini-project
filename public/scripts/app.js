var app = angular.module('authApp', ['ui.router', 'satellizer', 'ui.bootstrap', 'ui.bootstrap.datetimepicker',
    'angular-img-cropper', 'ui.tinymce', 'ui.bootstrap.modal'])
    .config(function ($stateProvider, $urlRouterProvider, $authProvider, $provide, $httpProvider) {

        $authProvider.loginUrl = '/api/authenticate';

        $urlRouterProvider.otherwise('/login');

        $stateProvider
            .state('login', {
                url: '/login',
                templateUrl: '../views/auth.html',
                controller: 'AuthController'
            })
            .state('scan', {
                url: '/scan',
                templateUrl: '../views/qrcode.html',
                controller: 'AuthController'
            })
            .state('register', {
                url: '/register',
                templateUrl: '../views/register.html',
                controller: 'AuthController'
            })
            .state('event', {
                url: '/event',
                templateUrl: '../views/event.html',
                controller: 'EventController'
            })
            .state('add-event', {
                url: '/add-event',
                templateUrl: '../views/addEvent.html',
                controller: 'EventController'
            })
            .state('edit-event', {
                url: '/edit-event/:eventId',
                templateUrl: '../views/addEvent.html',
                controller: 'EventController'
            })
            .state('user', {
                url: '/user',
                templateUrl: '../views/user.html',
                controller: 'UserController'
            })
            .state('edit-user', {
                url: '/edit-user/:userId',
                templateUrl: '../views/editUser.html',
                controller: 'UserController'
            });

        function redirectWhenLoggedOut($q, $injector) {
            return {
                responseError: function (rejection) {
                    var $state = $injector.get('$state');
                    var rejectionReasons = ['token_not_provided', 'token_expired', 'token_absent', 'token_invalid'];

                    angular.forEach(rejectionReasons, function (value, key) {
                        if (rejection.data.error === value) {
                            localStorage.removeItem('user');
                            $state.go('login');
                        }
                    });

                    return $q.reject(rejection);
                }
            }
        }

        $provide.factory('redirectWhenLoggedOut', redirectWhenLoggedOut);

        $httpProvider.interceptors.push('redirectWhenLoggedOut');

    })
    .run(function($rootScope, $state, $auth) {
        $rootScope.logout = function () {
            $auth.logout().then(function () {
                localStorage.removeItem('user');
                $rootScope.authenticated = false;

                $rootScope.currentUser = null;
                $state.go('login');
            });
        }
        $rootScope.$on('$stateChangeStart', function(event, toState) {

            // Grab the user from local storage and parse it to an object
            var user = JSON.parse(localStorage.getItem('user'));

            if(user) {
                $rootScope.authenticated = true;
                $rootScope.currentUser = user;

                if(toState.name === "login") {
                    event.preventDefault();
                    $state.go('event');
                }
            }
        });
    });