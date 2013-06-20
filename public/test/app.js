angular.module('project', ['mongolab']).
  config(function($routeProvider,$locationProvider) {
    $locationProvider.html5Mode(true);
    $routeProvider.
      when('/', {controller:ListCtrl, templateUrl:'list'}).
      when('/edit/:projectId', {controller:EditCtrl, templateUrl:'detail'}).
      when('/new', {controller:CreateCtrl, templateUrl:'detail'}).
      otherwise({redirectTo:'/'});
  });
 
 
function ListCtrl($scope, Project) {
  console.log("stub: 1");
  $scope.projects = Project.query();
}
 
 
function CreateCtrl($scope, $location, Project) {
  $scope.save = function() {
    Project.save($scope.project, function(project) {
      $location.path('/edit/' + project._id.$oid);
    });
  }
}
 
 
function EditCtrl($scope, $location, $routeParams, Project) {
  var self = this;
  console.log("stub: 2");
 
  Project.get({id: $routeParams.projectId}, function(project) {
    console.log("stub: 3");
    self.original = project;
    $scope.project = new Project(self.original);
  });
 
  $scope.isClean = function() {
    return angular.equals(self.original, $scope.project);
  }
 
  $scope.destroy = function() {
    self.original.destroy(function() {
      $location.path('/list');
    });
  };
 
  $scope.save = function() {
    $scope.project.update(function() {
      $location.path('/');
    });
  };
}