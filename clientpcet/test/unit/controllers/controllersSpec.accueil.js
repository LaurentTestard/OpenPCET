'use strict';

/* jasmine specs for controllers go here */

describe('controllers.accueil', function() {
	var $httpBackend, $scope, $location, $rootScope, createController;

	beforeEach(angular.mock.module('myApp.controllers.accueil'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend');
		// backend definition common for all tests
		//$httpBackend.when('GET', '/api.php/compte/authentification',{}).respond(
		//		{valide:'true'});

		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('accueil', {
				'$scope' : $rootScope
			});
		};
	})); 
  
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});

	it("test create controller", function() {
		createController();
	});
});