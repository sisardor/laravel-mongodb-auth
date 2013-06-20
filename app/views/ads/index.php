<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
    <title>Laravel Demo</title>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.min.js"> </script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"> </script>
	<script src="js/angular-resource.min.js"></script>
    <link media="all" type="text/css" rel="stylesheet" href="css/style.css">
</head>


    <body>
    <div id="container">
    	<div id="nav">
            <ul>
                <li><a href="/account">My Account</a></li>
                <li><a href="/profile">Profile</a></li>
                <li><a href="/logout">Logout</a></li>
            </ul>
        </div><!-- end nav -->

        

        <div ng-app="myAds">
		  <h1>My Ads</h1>
		  <a href="#/new"><button>Add new</button></a><br>
		  <div ng-view></div>


		  


    </div><!-- end container -->





    </body>
	<script type="text/javascript">

	var demo = angular.module( "myAds", ['ngResource', 'filters'] );

	demo.factory('$templateCache', function($cacheFactory, $http, $injector) {
	  var cache = $cacheFactory('templates');
	  var allTplPromise;
	 
	  return {
	    get: function(url) {
	      var fromCache = cache.get(url);
	 
	      // already have required template in the cache
	      if (fromCache) {
	        return fromCache;
	      }
	 
	      // first template request ever - get the all tpl file
	      if (!allTplPromise) {
	        allTplPromise = $http.get('assets/all-templates.html').then(function(response) {
	          // compile the response, which will put stuff into the cache
	          $injector.get('$compile')(response.data);
	          return response;
	        });
	      }
	 
	      // return the all-tpl promise to all template requests
	      return allTplPromise.then(function(response) {
	        return {
	          status: response.status,
	          data: cache.get(url)
	        };
	      });
	    },
	 
	    put: function(key, value) {
	      cache.put(key, value);
	    }
	  };
	});

	demo.config(function($routeProvider) {
		$routeProvider
		      .when('/view/:name', { controller:'Single', templateUrl:'single_view.html'})
		      .when('/edit/:name', { controller:'Edit', templateUrl:'single.html'})
		      .when('/new', { controller: 'Add', templateUrl: 'add.html'})
		      .when('/', { controller:'MainCtrl', templateUrl:'table.html'});
	});

	/*===============================*/
	angular.module('filters', []).filter('truncate', function () {
        return function (text, length, end) {
            if (isNaN(length))
                length = 10;

            if (end === undefined)
                end = "...";

            if (text.length <= length || text.length - end.length <= length) {
                return text;
            }
            else {
                return String(text).substring(0, length-end.length) + end;
            }

        };
    });

	/*================================*/
	demo.factory('myCache', function($cacheFactory) {
	    return $cacheFactory('myCache', { capacity: 3 });
	});

	/*================================*/
	demo.factory('Content', function($resource,myCache) {
		return $resource('account/ads/:name', 
					{name: '@_id'}, 
					{cache:myCache},
			        { 'save':{method:'PUT' }, 'add' :{method:'POST'},'get' :{method: 'GET'} }
       			);
	});

	/*===============================*/
	demo.controller('MainCtrl',  function($scope, $resource, $http, Content,myCache) {
		var stat = "pulled from Cache";
		$scope.ads = myCache.get("ads");
		if(!$scope.ads) {
			stat = "pulled from Server";

			$http.get('account/ads')
			  .success(function (data) {
			    $scope.ads = data; // { foo: 'bar' }
			    myCache.put("ads",data);
			})
			  .error(function (data, status, headers, config) {
			    // handle error
			});
		}
		var info = "[Cache info capacity: " + myCache.info().capacity +  " size: " + myCache.info().size + "]"
		console.log(stat + "     " + document.URL + "    " + info);
	});

	/*===============================*/
	demo.controller('Single',  function($scope, $resource, $routeParams, $http, Content,myCache) {
		var test = myCache.get('ads');
		var stat = "--> from Cache";
		if(!test) {
			$scope.ad = Content.get({name:$routeParams.name});
			//console.log($scope.ad);
			stat = "--> from Server"
		}
		else {
			for(var i in test) {
				if(test[i]._id.$id === $routeParams.name) {
					$scope.ad = test[i];
					//console.log($scope.ad );
				}
			}
		}
		var info = "[Cache info capacity: " + myCache.info().capacity +  " size: " + myCache.info().size + "]"
		console.log(stat + "     " + document.URL + "    " + info);
		//console.log(myCache.info());
	});

	/*===============================*/
	demo.directive('optionsDisabled', function($parse) {
	    var disableOptions = function(scope, attr, element, data, fnDisableIfTrue) {
	        // refresh the disabled options in the select element.
	        $("option[value!='?']", element).each(function(i, e) {
	            var locals = {};
	            locals[attr] = data[i];
	            $(this).attr("disabled", fnDisableIfTrue(scope, locals));
	        });
	    };
	    return {
	        priority: 0,
	        require: 'ngModel',
	        link: function(scope, iElement, iAttrs, ctrl) {
	            // parse expression and build array of disabled options
	            var expElements = iAttrs.optionsDisabled.match(/^\s*(.+)\s+for\s+(.+)\s+in\s+(.+)?\s*/);
	            var attrToWatch = expElements[3];
	            var fnDisableIfTrue = $parse(expElements[1]);
	            scope.$watch(attrToWatch, function(newValue, oldValue) {
	                if(newValue)
	                    disableOptions(scope, expElements[2], iElement, newValue, fnDisableIfTrue);
	            }, true);
	            // handle model updates properly
	            scope.$watch(iAttrs.ngModel, function(newValue, oldValue) {
	                var disOptions = $parse(attrToWatch)(scope);
	                if(newValue)
	                    disableOptions(scope, expElements[2], iElement, disOptions, fnDisableIfTrue);
	            });
	        }
	    };
	});

	/*===============================*/
	demo.controller('Edit',  function($scope, $resource, $routeParams, $location, $http, Content,myCache) {
		$scope.options = [
		    {category:'коммерческая', group:'недвижимость', value:'nko',isinuse: true},
		    {category:'гаражи и стоянки', group:'недвижимость',value:'nga',isinuse: true},
		    {category:'автомобили - частные', group:'транспорт',value:'aup',isinuse: true},
		    {category:'автомобили - дилерские', group:'транспорт',value:'aud',isinuse: true},
		    {category:'события', group:'разное',value:'rso', isinuse: true}
	  	];

	  	$scope.templates = [ 
	  		{ name: 'form_nko.html', url: 'form_nko.html'},
			{ name: 'form_aup.html', url: 'form_aup.html'} 
		];
	  	$scope.option = $scope.options; // red


		var temp = myCache.get('ads');
		if(!temp) {
			$scope.ad = Content.get({name:$routeParams.name}, function(){
				if($scope.ad.cat === "nko") {
					$scope.template = $scope.templates[0];
					$scope.category = $scope.ad.cat;
					$scope.options[0].isinuse = false;
					$scope.option = $scope.options[1]
				}
				if($scope.ad.cat === "aup") {
					$scope.template = $scope.templates[1];
					$scope.category = $scope.ad.cat;
					$scope.options[2].isinuse = false;
					$scope.option = $scope.options[2]
				}
			});
		}
		else {
			for(var i in temp) {
				if(temp[i]._id.$id === $routeParams.name) {
					$scope.ad = temp[i];
					
					if($scope.ad.cat === "nko") {
						$scope.template = { name: 'form_nko.html', url: 'form_nko.html'};
						$scope.category = $scope.ad.cat;
						$scope.options[0].isinuse = false;
						$scope.option = $scope.options[0];
					}
					if($scope.ad.cat === "aup") {
						$scope.template = { name: 'form_aup.html', url: 'form_aup.html'};
						$scope.category = $scope.ad.cat;
						$scope.options[2].isinuse = false;
						$scope.option = $scope.options[2]
					}
					//$scope.ad = temp[i];
				}
			}
		} 

		$scope.master = {};
		$scope.update = function(ad) {
			$scope.master = angular.copy(ad);
			$scope.master.cat = $scope.category;

			$http({
				url 	: '/account/ads/' + $scope.ad._id.$id,
				method	: 'PUT',
				data	: $scope.master
			}).error(function(data,status, headers,config){
				console.log("Error!!");
			}).success(function(data,status,headers,config) {
				$scope.updateCache('ads', $scope.master, $scope.ad._id.$id);
				//$location.path('/'); //Redirect to /
			});
		};
	 
		$scope.reset = function() {
			$scope.ad = angular.copy($scope.master); 
		};

		$scope.updateCache = function(key, value, id) {
			var info = "[Cache info capacity: " + myCache.info().capacity +  " size: " + myCache.info().size + "]"
			console.log('updateing Cache' + "    " + info);

			var tmp = myCache.get(key);
			if(tmp) {
				for(var i in tmp) {
					if(tmp[i]._id.$id === id) {
						tmp[i] = value;
					}
				}
				myCache.remove(key);
				myCache.put(key, tmp);
			}
			else 
				myCache.remove('ads');
		}
		$scope.numberOfRooms = function(num) {
			$scope.ad.rooms = num;
		};

		//$scope.reset();
	});

	/*===============================*/
	demo.controller('Add',  function($scope, $resource, $http, Content) {
		$scope.options = [
		    {category:'коммерческая', group:'недвижимость', value:'nko'},
		    {category:'гаражи и стоянки', group:'недвижимость',value:'nga'},
		    {category:'автомобили - частные', group:'транспорт',value:'aup'},
		    {category:'автомобили - дилерские', group:'транспорт',value:'aud'},
		    {category:'события', group:'разное',value:'rso'}
	  	];
	  	$scope.templates = [ 
	  		{ name: 'form_nko.html', url: 'form_nko.html'},
			{ name: 'form_aup.html', url: 'form_aup.html'} 
		];
	  	$scope.option = $scope.options; // red
	  	$scope.category = "ngo";
	  	$scope.$watch("option", function( selected  ) {
			if ( selected.value === "nko" || selected.value === "nga" ) {
				$scope.template = $scope.templates[0];
				$scope.category = selected.value;
				$scope.partial_clear();

			}
			if ( selected.value === "aup" || selected.value === "aud" ) {
				$scope.template = $scope.templates[1];
				$scope.category = selected.value;
				$scope.partial_clear();
			}
			if ( selected.value === "rso" ) {
				$scope.template = null;
				$scope.category = selected.value;
				$scope.partial_clear();
			}
		});
		$scope.master = {};
		$scope.update = function(ad) {
			$scope.master= angular.copy(ad);
			console.log($scope.master);
			$scope.master.cat = $scope.category;

			$http({
				url 	: '/account/ads',
				method	: 'POST',
				data	: $scope.master
			}).error(function(data,status, headers,config){
				  		console.log("Error!!");
			});
		};
	 
		$scope.reset = function() {
			$scope.ad = angular.copy($scope.master); 
		};
		$scope.numberOfRooms = function(num) {
			$scope.ad.rooms = num;
		};

		$scope.partial_clear = function() {
			$scope.master = {};
			var tmp   = new Object();
			tmp.title = $scope.ad.title;
			tmp.price = $scope.ad.price;
			tmp.text  = $scope.ad.text;
			$scope.ad = angular.copy(tmp);
		};
			 
		$scope.reset();
	});

	</script>

</html>