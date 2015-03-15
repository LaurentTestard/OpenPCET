'use strict';


describe('controllers.action', function() {
  var $httpBackend, $scope, $location, $rootScope, createController, $routeParams, $id_action = '1';

  beforeEach(angular.mock.module('myApp.controllers.action'));
  beforeEach(inject(function($injector) {
    $location = $injector.get('$location');
    $rootScope = $injector.get('$rootScope');
    $scope = $rootScope.$new();
    $routeParams = { etat:'consulter', id:$id_action};
 
    $httpBackend = $injector.get('$httpBackend');
    // backend definition common for all tests
    $httpBackend.when('GET', '/serveurpcet/index.php/pcaet/actions/details/'+$id_action,{}).respond(
        {
          code_action:'code_action',
          nom_action:'nom_action',
          nom_objectif_strategique:'nom_objectif_strategique',
          est_attenuation:'true',
          est_adaptation:'false',
          est_communication:'true',
          est_formation:'false',
          est_appui_technique:'false',
          est_appui_financier:'false',
          nom_engagement_thematique:'nom_engagement_thematique',
          lien_avec_autres_actions:[
            {code_action:'1', nom_action:'code_action_1'}, 
            {code_action:'2', nom_action:'code_action_2'},
            {code_action:'3', nom_action:'code_action_3'}
          ],
          contexte_action:'contexte_action',
          objectifs_quantitatifs:'objectifs_quantitatifs',
          gains_co2:'gains_co2',
          gains_energie:'gains_energie',
          gains_ges:'gains_ges',
          maitrise_ouvrage:'maitrise_ouvrage',
          liste_noms_partenaires:['nom_partenaire_1', 'nom_partenaire_2', 'nom_partenaire_3'],
          organisation:'organisation',
          nom_utilisateur:'nom_utilisateur',
          prenom_utilisateur:'prenom_utilisateur',
          email:'email',
          tel_interne:'tel_interne',
          tel_standard:'tel_standard',
          referents_associes:'referents_associes'
        }
    );

    var $controller = $injector.get('$controller');

    createController = function() {
      return $controller('action', {
        '$scope' : $rootScope,
        '$routeParams' : $routeParams
      });
    };
  })); 
  
  afterEach(function() {
    $httpBackend.verifyNoOutstandingExpectation();
    $httpBackend.verifyNoOutstandingRequest();
  });

  it("Test get action", function() {
    createController();
    $httpBackend.flush(); 
  });
  
});