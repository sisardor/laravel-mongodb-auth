<!doctype html>
<html ng-app="project">
  <head>
    <!-- Use LATEST folder to always get the latest version-->
    <script src="http://code.angularjs.org/1.1.4/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.1.4/angular-resource.js"></script>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>
    <script type="text/javascript" src="js/rest/restangular.js"></script>

    </script>
    <script src="js/rest/app_test.js"></script>
    <script src="js/rest/mongolab.js"></script>

    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css">
  </head>
  <body>
    <h2>JavaScript Projects</h2>
    <div ng-view></div>
  </body>
</html>