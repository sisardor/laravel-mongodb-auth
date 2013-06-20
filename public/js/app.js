var app = angular.module("app", [])

app.config(function($routeProvider) {

  $routeProvider.when('/login', {
    templateUrl: 'templates/login.html',
    controller: 'LoginController'
  });

  $routeProvider.when('/home', {
    templateUrl: 'templates/home.html',
    controller: 'HomeController'
  });

  $routeProvider.otherwise({ redirectTo: '/login' });

});

app.factory("AuthContorller", function($http, $location) {
	return {
		login: function(credentials) {
      return $http.post("/auth/login", credentials);
		},
		logout: function() {
			return $http.get("/auth/logout");
		}
	};
});

app.controller("LoginController", function($scope, $location, AuthContorller) {
	$scope.credentials = {username:"", password:""};
	
	$scope.login = function() {
		AuthContorller.login($scope.credentials).success(function() {
      $location.path('/home');
    });
	};
});

app.controller("HomeController", function($scope, AuthContorller) {
   $scope.title = "Awesome Home";
  	$scope.message = "Mouse Over these images to see a directive at work!";
  	
 $scope.logout = function() {
    AuthContorller.logout();
  };
});

app.directive("showsMessageWhenHovered", function() {
  return {
    restrict: "A", // A = Attribute, C = CSS Class, E = HTML Element, M = HTML Comment
    link: function(scope, element, attributes) {
      var originalMessage = scope.message;
      element.bind("mouseenter", function() {
        scope.message = attributes.message;
        scope.$apply();
      });
      element.bind("mouseleave", function() {
        scope.message = originalMessage;
        scope.$apply();
      });
    }
  };
});




