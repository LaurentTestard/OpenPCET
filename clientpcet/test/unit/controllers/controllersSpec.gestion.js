'use strict';

describe('controllers.gestion', function() {
	var $httpBackend, $scope, $location, $rootScope, createController;
	var dataUser = [
	        {utilisateur_prenom:"Brigitte",
			utilisateur_nom:"Dupond",email:"brigitte.dupond@gmail.com", 
			login_utilisateur:"bdupond", utilisateur_id:"1"}];

	beforeEach(angular.mock.module('myApp.controllers.gestion'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend'); 
		// backend definition common for all tests
		$httpBackend.when('GET', '/api.php/comptes/compte_personnel',{}).respond(dataUser);

		$httpBackend.when('PUT', '/api.php/comptes/compte_personnel',{}).respond({valide:'true'});
		
		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('gestion', {
				'$scope' : $rootScope
			});
		};

	})); 
  
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});

	it('dois_recupérer_les_informations_d_un_utilisateur', function() {
	    var controller = createController();
	    $rootScope.recupererInfoPersonnelle();
	    $httpBackend.flush(); 
	    expect($rootScope.utilisateur).toEqual(dataUser);
	    
	});
	
	
	
	it('dois_modifier_les_champs_de_lutilisateur', function() {
	    var controller = createController();
	    $rootScope.enregistrerInfoPersonnelle();
	    $httpBackend.flush(); 
	    expect($rootScope.valide).toBe('true');
	   
	}); 
	
});
