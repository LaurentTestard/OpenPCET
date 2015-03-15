'use strict';

/* jasmine specs for controllers go here */

describe('controllers.historique', function() {
	var $httpBackend, $scope, $location, $rootScope,$routeParams ,createController;

	beforeEach(angular.mock.module('myApp.controllers.historique'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$routeParams=$injector.get('$routeParams');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend');
		// backend definition common for all tests 

		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('historique', {
				'$scope' : $rootScope
			});
		};
	})); 
  
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});
 
	it('testGetHistorique', function() {
		$httpBackend.when('GET','/serveurpcet/index.php/pcaet/actions/historique1',{}).respond(
				{
					success:'ok',
					login:'vincent'
				}
				
		);
	    var controller = createController();
	    expect($rootScope.historique.length).toBe('2');
	});
});