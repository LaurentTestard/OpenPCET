'use strict';

	    
describe('controllers.actions', function() {
	var $httpBackend, $scope, $location, $rootScope, createController;

	beforeEach(angular.mock.module('myApp.controllers.actions'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend');
		// backend definition common for all tests
		$httpBackend.when('GET', '/serveurpcet/index.php/pcaet/actions/:utilisateur_id',{}).respond(
				{action:"D11",dateDebut:"01/02/2014" , dateFin:"05/03/2014" ,avancement:"Débutée"});
		$httpBackend.when('GET', '/serveurpcet/index.php/pcaet/actions',{}).respond(
				{action:"Elaborer rapports",dateDebut:"20/03/2014" ,dateFin:"30/03/2014" ,avancement:"Non Débutée"});

		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('actions', {
				'$scope' : $rootScope
			});
		}; 
	})); 
   
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});
 
	it('testActionGeneral', function() { 
	    var controller = createController();
	    $rootScope.actionGeneralRequest();
	    $httpBackend.flush(); 
	    expect($rootScope.actionsGeneral).toEqual({action:"Elaborer rapports",dateDebut:"20/03/2014" ,dateFin:"30/03/2014" ,avancement:"Non Débutée"});
	}); 
	
	it('testActionUtilisateur', function() { 
	    var controller = createController();
	    $rootScope.actionUtilisateurRequest();
	    $httpBackend.flush(); 
	    expect($rootScope.actionsUtilisateur).toEqual({action:"D11",dateDebut:"01/02/2014" , dateFin:"05/03/2014" ,avancement:"Débutée"});
	});
	
	it("test create controller", function() {
		createController(); 
	});
});
