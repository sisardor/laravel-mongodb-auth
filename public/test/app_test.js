angular.module('project', ['restangular'])
  .config(function($routeProvider, RestangularProvider, $locationProvider) {
  //$locationProvider.html5Mode(true);
  $routeProvider.
      when('/', {
        controller:ListCtrl, 
        templateUrl:'list'
      }).
      when('/edit/:projectId', {
        controller:EditCtrl, 
        templateUrl:'detail',
        resolve: {
          project: function(Restangular, $route){
            return Restangular.one('projects', $route.current.params.projectId).get()
          }
        }
      }).
      when('/new', {controller:CreateCtrl, templateUrl:'detail'}).
      otherwise({redirectTo:'/'});
      
      RestangularProvider.setBaseUrl('rest');
      //RestangularProvider.setDefaultRequestParams({ apiKey: '4f847ad3e4b08a2eed5f3b54' })
      RestangularProvider.setRestangularFields({
        id: '_id'
      });
      
      RestangularProvider.setRequestInterceptor(function(elem, operation, what) {
        
        if (operation === 'put') {
          elem._id = undefined;
          return elem;
        }
        return elem;
      })

  });

function ListCtrl($scope, Restangular) {
  $scope.projects = Restangular.all("projects").getList();
}
function CreateCtrl($scope, $location, Restangular) {
  $scope.save = function() {
    Restangular.all('projects').post($scope.project).then(function(project) {
      $location.path('/list');
    });
  }
}



function EditCtrl($scope, $location, Restangular, project) {
	console.log("Hit");
  var original = project;
  $scope.project = Restangular.copy(original);
  

  $scope.isClean = function() {
    return angular.equals(original, $scope.project);
  }

  $scope.destroy = function() {
    original.remove().then(function() {
      $location.path('/list');
    });
  };

  $scope.save = function() {
    $scope.project.put().then(function() {
      $location.path('/');
    });
  };
}
