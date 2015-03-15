'use strict';

describe('controllers.monCompte', function() {
	var $httpBackend, $scope, $location, $rootScope, createController;
	var dataUser = [
	        {utilisateur_prenom:"Brigitte",
			utilisateur_nom:"Dupond",email:"brigitte.dupond@gmail.com", 
			login_utilisateur:"bdupond", utilisateur_id:"1"}];

	var motdepasse = "toto";
	beforeEach(angular.mock.module('myApp.controllers.monCompte'));
	beforeEach(inject(function($injector) {
		$location = $injector.get('$location');
		$rootScope = $injector.get('$rootScope');
		$scope = $rootScope.$new();
 
		$httpBackend = $injector.get('$httpBackend'); 
		// backend definition common for all tests
		$httpBackend.when('GET', '/serveurpcet/index.php/comptes/compte_personnel',{}).respond(dataUser);

		$httpBackend.when('PUT', '/serveurpcet/index.php/comptes/compte_personnel',{}).respond({valide:'true'}); 
		
		$httpBackend.when('PUT', '/serveurpcet/index.php/comptes/compte_personnel/modification_mot_de_passe',motdepasse,{}).respond({valide:'ok'});
		
		
		var $controller = $injector.get('$controller');

		createController = function() {
			return $controller('monCompte', {
				'$scope' : $rootScope
			});
		};
		
	 

		
	})); 
  
	afterEach(function() {
		$httpBackend.verifyNoOutstandingExpectation();
		$httpBackend.verifyNoOutstandingRequest();
	});

	it('dois_recup�rer_les_informations_d_un_utilisateur', function() {
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
	
	
	it('dois_modifier_le_mot_de_passe', function() {
	    var controller = createController();
	    $rootScope.enregistrerMotDePasse();
	    $httpBackend.flush(); 
	    expect($rootScope.valie).toBe('ok');
	    
	});
	
});
