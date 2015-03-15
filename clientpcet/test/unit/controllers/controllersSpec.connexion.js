'use strict';

/* jasmine specs for controllers go here */

describe('controllers.connexion', function() {
	var $httpBackend, $scope, $location, $rootScope, createController;

	beforeEach(angular.mock.module('myApp.controllers.connexion'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend');
		// backend definition common for all tests

		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('connexion', {
				'$scope' : $rootScope
			});
		};
	})); 
  
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});
 
	it('Connection ok (valide)', function() {
		$httpBackend.when('GET', '/serveurpcet/index.php/compte/authentification',{}).respond(
				{
					success:'ok',
					login:'vincent'
				}
				
		);
	    var controller = createController();
	    $rootScope.connexion();
	    $httpBackend.flush(); 
	    expect($rootScope.connexion).toBe('ok');
	});
	
	it('Connection ko (fail)', function() {
		$httpBackend.when('GET', '/serveurpcet/index.php/compte/authentification',{}).respond(
				{
					success:'ko',
				}
				
		);
	    var controller = createController();
	    $rootScope.connexion();
	    $httpBackend.flush(); 
	    expect($rootScope.connexion).toBe('ko');
	});
});