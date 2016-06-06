<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Testing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/simple-sidebar.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body ng-app="authApp">

<nav class="navbar navbar-default navbar-static-top">
    <div class="navbar-header">
        <!-- Collapsed Hamburger -->
        <button type="button" class="navbar-toggle collapsed" href="#menu-toggle" id="menu-toggle" >
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Company</a>
    </div>
</nav>

<div class="container" id="wrapper" ng-class="{toggled:!authenticated}">
    <div ng-show="authenticated" ng-cloak>
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                        Company
                    </a>
                </li>
                <li>
                    <a ui-sref="event">Event</a>
                </li>
                <li>
                    <a ui-sref="user">Manage Users</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->
    </div>
    <div ui-view></div>
</div>

</body>

<!-- Application Dependencies -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src='http://maps.googleapis.com/maps/api/js?key=&sensor=false&extension=.js'></script>

<script type="text/javascript" src="bower_components/tinymce-dist/tinymce.js"></script>
<script src="bower_components/angular/angular.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="node_modules/angular-ui-router/build/angular-ui-router.js"></script>
<script src="node_modules/satellizer/satellizer.js"></script>
<script src="node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js"></script>
<script src="node_modules/bootstrap-ui-datetime-picker/dist/datetime-picker.min.js"></script>
<script src="bower_components/angular-img-cropper/dist/angular-img-cropper.min.js"></script>
<script src="bower_components/angular-ui-tinymce/dist/tinymce.min.js"></script>

<!-- Application Scripts -->
<script src="scripts/app.js"></script>
<script src="scripts/authController.js"></script>
<script src="scripts/eventController.js"></script>
<script src="scripts/userController.js"></script>

<script src="assets/js/qrcode/llqrcode.js"></script>
<script src="assets/js/qrcode/plusone.js"></script>
<script src="assets/js/qrcode/webqr.js"></script>

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</html>