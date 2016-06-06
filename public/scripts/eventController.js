/**
 * Created by Duy Khoa on 5/31/2016.
 */
app.controller('EventController', function ($state, $http, $rootScope, $scope, $auth, $stateParams, $uibModal) {

    $scope.event = {_id: null, name: null, venue: null, address: null, address2: null, postcode: null, date: null, time: null,
        description: null, logo: null, banner: null, search:null};

    $scope.events = [];

    $scope.cropper = {};
    $scope.cropper.sourceImage1 = null;
    $scope.cropper.croppedImage1 = null;

    $scope.cropper.sourceImage2 = null;
    $scope.cropper.croppedImage2 = null;

    $scope.error = false;
    $scope.errorText = '';

    $scope.event.date = new Date();

    $scope.dateOptions = {
        formatYear: 'yy',
        maxDate: new Date(2020, 5, 22),
        minDate: new Date(),
        startingDay: 1
    };

    $scope.open1 = function () {
        $scope.popup1.opened = true;
    };

    $scope.open2 = function () {
        $scope.popup2.opened = true;
    };

    $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[0];
    $scope.altInputFormats = ['M!/d!/yyyy'];

    $scope.popup1 = {
        opened: false
    };

    $scope.popup2 = {
        opened: false
    };
    var d = new Date();
    d.setHours(14);
    d.setMinutes(0);
    $scope.event.time =  new Date();

    $scope.timer = {
        timepickerOptions: {
            readonlyInput: false,
            showMeridian: false
        }
    };

    var geocoder = new google.maps.Geocoder();

    $scope.onSearch = function () {
        geocodeAddress(geocoder, $scope.map);
    };

    if($state.is('event')) {
        $http.get('/api/event').success(function (data) {
            $scope.events = data;
        })
    }

    if($state.is('edit-event')) {
        $http.get('/api/event/' + $stateParams.eventId).success(function (data) {
            $scope.event = data;
            $scope.event.date = new Date(data.date);
            $scope.event.time = new Date(data.time);
            $scope.cropper.croppedImage1 = $scope.event.logo;
            $scope.cropper.croppedImage2 = $scope.event.banner;

            $scope.onSearch();
        })
    }

    $scope.save = function (id) {
        $scope.event.logo = $scope.cropper.croppedImage1;
        $scope.event.banner = $scope.cropper.croppedImage2;
        if(id){
            $http.post('/api/event/update/' + id, $scope.event).success(function (data) {
                if(data._id) {
                    $state.go('event');
                }
                $scope.error = true;
                $scope.errorText = data;
            });
        }else{
            $http.post('/api/event/save', $scope.event).success(function (data) {
                if(data._id) {
                    $state.go('event');
                }
                $scope.error = true;
                $scope.errorText = data;
            });
        }
    };

    $scope.search = function() {
        if($scope.event.search) {
            $http.get('/api/event/search/' + $scope.event.search).success(function (data) {
                $scope.events = data;
            })
        }else{
            $http.get('/api/event').success(function (data) {
                $scope.events = data;
            })
        }
    }

    $scope.confirmDelete = function (index) {

        var modalInstance = $uibModal.open({
            animation: true,
            templateUrl: 'myModalContent.html',
            controller: 'ModalInstanceCtrl',
            size: 'sm'
        });

        modalInstance.result.then(function () {
            var id = $scope.events[index]._id;
            $http.get('/api/event/delete/' + id).success(function () {
                $scope.events.splice(index,1);
            });
        }, function () {
            //nothing
        });
    };

    $scope.cancel = function(){
        $state.go('event');
    };

    $scope.tinymceOptions = {
        plugins: 'link image code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
    };

    var mapOptions = {
        zoom: 15,
        center: new google.maps.LatLng(40.0000, -98.0000),
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.TOP_CENTER
        },
        zoomControl: true,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_TOP
        },
        scaleControl: true,
        streetViewControl: true,
        streetViewControlOptions: {
            position: google.maps.ControlPosition.LEFT_TOP
        }
    };

    function geocodeAddress(geocoder, resultsMap) {
        geocoder.geocode({'address': $scope.event.address}, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                resultsMap.setCenter(results[0].geometry.location);

                var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: $scope.map
                });
            }
        });
    }

    if($state.is('add-event') || $state.is('edit-event')) {
        $scope.map = new google.maps.Map(document.getElementById('map'), mapOptions);
        google.maps.event.addDomListener(window, "resize", function () {
            var center = $scope.map.getCenter();
            google.maps.event.trigger($scope.map, "resize");
            $scope.map.setCenter(center);
        });
    }

});

app.filter('html', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});

app.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance) {

    $scope.ok = function () {
        $uibModalInstance.close();
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
});