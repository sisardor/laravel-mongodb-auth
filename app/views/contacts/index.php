 <!DOCTYPE html>
<html ng-app="contacts">
<meta charset="utf-8">
<title>Contacts</title>
<meta name="viewport" content="width=device-width">

<!-- <base href="/contact"> -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
<script src="js/underscore.js"></script>
<script src="js/angular-resource.min.js"></script>
<script src="http://cdn.jsdelivr.net/restangular/latest/restangular.min.js"></script>

<style>
  * { box-sizing: border-box; }
  body { font: 14px/1.5 sans-serif; color: #222; margin: 3em; }
  table, input, textarea { width: 100%; }
  th { text-align: left; }
  h4 { margin: 0; }
</style>


<div ng-controller="Contacts">
  <h1>Contacts</h1>
<!--   <table>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Number</th>
  </tr>
  <tr ng-repeat="contact in contacts">
    <td><a href="/contacts/{{contact.name.clean}}">{{contact.name.first}} {{contact.name.last}}</a></td>
    <td>{{contact.email}}</td>
    <td>{{contact.number}}</td>
  </tr>
</table> -->
  <div ng-view></div>
</div>
<script>
angular.module('ng').filter('tel', function () {
    return function (tel) {
        if (!tel) { return ''; }

        var value = tel.toString().trim().replace(/^\+/, '');

        if (value.match(/[^0-9]/)) {
            return tel;
        }

        var country, city, number;

        switch (value.length) {
            case 10: // +1PPP####### -> C (PPP) ###-####
                country = 1;
                city = value.slice(0, 3);
                number = value.slice(3);
                break;

            case 11: // +CPPP####### -> CCC (PP) ###-####
                country = value[0];
                city = value.slice(1, 4);
                number = value.slice(4);
                break;

            case 12: // +CCCPP####### -> CCC (PP) ###-####
                country = value.slice(0, 3);
                city = value.slice(3, 5);
                number = value.slice(5);
                break;

            default:
                return tel;
        }

        if (country == 1) {
            country = "";
        }

        number = number.slice(0, 3) + '-' + number.slice(3);

        return (country + " (" + city + ") " + number).trim();
    };
});
</script>
<script>

  angular.module('contacts', ['ngResource'])
  .config(function ($routeProvider, $locationProvider) {
    $routeProvider
      .when('/view/:name', { controller:'Single', templateUrl:'assets/single.html'})
      .when('/add', { controller: 'Add', templateUrl: 'assets/add.html'})
      .when('/', { controller:'Table', templateUrl:'assets/table.html'});
      
    //$locationProvider.html5Mode(true);
  })
  .factory('Contact', function($resource) {
    return $resource('contact/:name', {name: '@clean_name'}, 
          {
          'save':{method:'PUT'/*,params:{action:'update'}*/},
          'add' :{method:'POST' },

        });
  })

  // .factory('Contactxx', function($resource) {
  //   return $resource('contact/:name', {name: '@name.clean'}, 
  //     { 
  //       //'query' : { method:'GET' },
  //       'save'  : { method:'PUT', params:{action:'update'} },
  //       'add'   : { method:'POST', params:{action:'store'} },
  //       'delete': { method:'DELETE', params:{action:'delete'} } 
  //     }
  //   );
  // })
 /*===========Contacts controller=============*/
  .controller('Contacts', function($scope, $resource, Contact){
    $scope.newCreate = function() {
      $routeProvider

    console.log("Clicked");
    $scope.contact = new Contact({});
    $scope.save = function() {
      $scope.contact.$save(function(){
        $location.path('/contact');
      });
    };
    };
  })
  /*===========Table controller=============*/
  .controller('Table', function($scope, $resource, Contact) {
    $scope.contacts = Contact.query();
  })

  /*===========Single controller=============*/
  .controller('Single', function($scope, $resource, $routeParams, Contact, $location) {

    $scope.contact = Contact.get({ name: $routeParams.name}, function() {
      // Success
    }, function(response){
        // Error
        if(response.status === 404) {
          console.log(response.data);
          $location.path('/');
          
        }
    });

    $scope.save = function() {
      $scope.contact.$save(function(updated_contact){
        $scope.contact = updated_contact;
        $location.path('/view/' + updated_contact.clean_name).replace();
      });
    };
  })

  /*===========Add controller=============*/
  .controller('Add', function($scope, $resource, Contact, $location) {
    $scope.contact = new Contact({});
    $scope.contact.added = new Date();
    $scope.save = function() {
      $scope.contact.$add(function(){
        $location.path('/');
      });
    };
  })
  //});
</script>