'use strict';

/* Controllers */

angular.module('myApp.controllers.actions', []).provider("proConnexion",function(){	
	this.$get = ["$http","$q",function($http,$q){
		return {
			getDonneeUtilisateur:function(){
				var d = $q.defer();
				$http.get('/serveurpcet/index.php/compte/info').success(function(data, status, headers) {
					//authToken = headers('A-Token');
					d.resolve(data);
				}).error(function(err){
					if(_dev)
						$('#myModalConnexion').modal('hide');
					else
						$('#myModalConnexion').modal('show');
				});
				return d.promise;
			}
		};
	}];
}).controller('actions', [ '$scope','$http','$modal','$location','$anchorScroll' ,'proConnexion',function($scope, $http, $modal, $location,$anchorScroll,proConnexion) {
$scope.actionsGeneral = [];
$scope.mots_clefs = [];
$scope.objectifs_strategiques = [];
$scope.engagement_thematique = [];

$scope.annulerFiltreEtat=true;
$scope.formulaireCollapse=true;

$scope.actionGeneralRequest = function (){
  $http.get('/serveurpcet/index.php/pcaet/actions').success(function(data, status, headers) {
	  $scope.actionsGeneral = data;
	  //TODO: DSL ces lignes pose problème pour le merge coté serveur 
	  //$scope.actionsGeneral.date_debut = Date.parse(date_debut);
	  //$scope.actionsGeneral.date_fin = Date.parse(date_fin);
  }); 
}; 
$scope.actionGeneralRequest();


$scope.actionUtilisateurRequest = function (utilisateur){
	$http.get('/serveurpcet/index.php/pcaet/actions/'+utilisateur.id).success(function(data, status, headers) {
		$scope.actionsUtilisateur = data;
	});
};

$scope.donneeUtilisateur = {};
proConnexion.getDonneeUtilisateur().then(function(data){
	$scope.donneeUtilisateur = data;
	$scope.actionUtilisateurRequest(data);
	console.log(data);
	$scope.actionUtilisateurClique = function(action) {
		   $location.path("/action/consulter/"+action.id+"/true");
	};
	$scope.actionNomClique = function(action) {
		if(data.role_utilisateur == 1){
			$location.path("/action/consulter/"+action.id+"/true");
		}else{
			$location.path("/action/consulter/"+action.id+"/false");
		}
	};
});


$scope.sort_mes_actions = "code_action";
$scope.sort_toutes_actions = "code_action";
$scope.reverse = false;
$scope.reverse_toutes_actions = false;

$scope.changerTri = function (value){ 
	if ($scope.sort_mes_actions == value){
	      $scope.reverse = !$scope.reverse;
	      return;
	}

	    $scope.sort_mes_actions = value;
	    $scope.reverse = false;
	    
};

$scope.changerTriToutesActions = function (value){ 
	if ($scope.sort_toutes_actions == value){
	      $scope.reverse_toutes_actions = !$scope.reverse_toutes_actions;
	      return;
	}

	    $scope.sort_toutes_actions = value;
	    $scope.reverse_toutes_actions = false;
	    
};
$scope.filtre_avance="mesactions";

$scope.RAZfiltre = function(){
	$scope.mesactions = "";
	$scope.toutesactions= "";
	$scope.filtre_avance="mesactions";
	$scope.sort_mes_actions = "code_action";
	$scope.sort_toutes_actions = "code_action";
	$scope.reverse = false;
	$scope.reverse_toutes_actions = false;
	$scope.annulerFiltreEtat=true;
};

$scope.enableFiltreButton=function(){
	if($scope.formulaireCollapse==true) $scope.formulaireCollapse=false;
	else $scope.formulaireCollapse=true;
	$scope.annulerFiltreEtat=$scope.formulaireCollapse;
}; 

	
	/*
	 * Recherche des actions
	 */
 
  $scope.getObjectifsStrategiques = function(){
	  $http.get('/serveurpcet/index.php/objectifs').success(function(data, status, headers) {
		    $scope.objectifs_strategiques = data;
	  });
  };
  $scope.getObjectifsStrategiques();
  
	
  $scope.getmots_clefs = function(){
	  $http.get('/serveurpcet/index.php/motsclefs').success(function(data, status, headers) {
		    $scope.mots_clefs = data;
	  });
  };
  $scope.getmots_clefs();
 
  $scope.thematiques = [];
  $scope.getThematiques = function(){
		$http.get('/serveurpcet/index.php/thematiques').success(function(data){
			$scope.thematiques = data;
		});
	};
	
  $scope.engagements = [];
  $scope.getengagements = function(){
	$http.get('/serveurpcet/index.php/engagements').success(function(data){
		$scope.engagements = data;
	});
  };
	
	$scope.getengagements();
	
  
  $scope.getThematiques();
  $scope.getObjectifsStrategiques();
  
  $scope.aj=false;
	$scope.ajouterActionModal = function(objectifs_strategiques) {
	
		var modaleInstanceAction = $modal
				.open({
					templateUrl : "partials/modal.ajouter.action.html",
					controller : "ModalAjoutActionCtrl",
					resolve : {
						data : function() {
							return objectifs_strategiques;
						}
					}
				});
		modaleInstanceAction.result.then(function(b) {
			$scope.modaleResult = b;
			if (b == true) {
				$scope.aj=true;
			}
		});
	};
	
	$scope.ajouterAction = function() {
		$scope.ajout = true;
		$scope.action.code_action="";
		$scope.action.nom_action="";
		$scope.action.est_attenuation="";
		$scope.action.est_adaptation="";
		$scope.action.est_communication="";
		$scope.action.est_formation="";
		$scope.action.est_appui_technique="";
		$scope.action.est_appui_financier="";
		//$scope.action.date_mise_a_jour="";
		$scope.action.contexte_action="";
		$scope.action.objectifs_quantitatifs="";
		$scope.action.gains_ges="";
		$scope.action.gains_energie="";
		$scope.action.gains_co2="";
		$scope.action.maitrise_ouvrage="";
		//$scope.action.referents_associes="";
		$scope.action.budget_total="";
		$scope.action.budget_consomme="";
		$scope.action.objectifstrategique="";
		
	};
}
  
]).controller('ModalAjoutActionCtrl',
		[ '$scope', '$modal', '$modalInstance','$http','data', function($scope, $modal, $modalInstance, $http, data) {
			
			//$scope.themes = [{"key":1,"nom_thematique_concernee":1},{"key":2,"nom_thematique_concernee":2},{"key":3,"nom_thematique_concernee":3}];
			$scope.objs_strats=data;
			$scope.action = {
					code_action:"",
					nom_action:"",
					est_attenuation:"",
					est_adaptation:"",
					est_communication:"",
					est_formation:"",
					est_appui_technique:"",
					est_appui_financier:"",
					contexte_action:"",
					objectifs_quantitatifs:"",
					gains_ges:"",
					gains_energie:"",
					gains_co2:"",
					maitrise_ouvrage:"",
					referents_associes:"",
					budget_total:"",
					budget_consomme:"",
					objectifstrategique:"",
			};
			
			
			$scope.aj=false;
			$scope.ajouterPhasesModal = function() {
			
				var modaleInstanceAction = $modal
						.open({
							templateUrl : "partials/modal.ajout.phases.html",
							controller : "ModalAjoutPhasesCtrl",
						});
				modaleInstanceAction.result.then(function(b) {
					$scope.modaleResult = b;
					if (b == true) {
						$scope.aj=true;
					}
				});
			};
			
			
			$scope.bool = true;
			$scope.textHeader = 'Ajout d\'une action';
			$scope.labelBouton = 'Ajouter l\'action';
			$scope.modifier = false;
			$scope.ok = function() {
				$http({method: 'PUT', url: '/serveurpcet/index.php/action/ajout',data:$scope.action}).
			    success(function(data, status, headers, config) {
			    }).then(function(data){
			    	$scope.ajoutobjStrat();
			    	$modalInstance.close($scope.bool);
			    },function(err){
			    	$modalInstance.close($scope.bool);
			    	
			    },function(notif){
			    	
			    });
			};
			
			/*A compl�ter*/
			$scope.ajoutObjStrat = function(){
				$http.get('/serveurpcet/index.php/objectif/lieractionObj/'+$scope.action.code_action+','+$scope.action.objectifstrategique).success(function(data){
				});
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
	
		};
	}]).controller('ModalAjoutPhasesCtrl',
			[ '$scope', '$modalInstance','$http','data', function($scope, $modalInstance, $http, data) {
				
				//$scope.themes = [{"key":1,"nom_thematique_concernee":1},{"key":2,"nom_thematique_concernee":2},{"key":3,"nom_thematique_concernee":3}];
				//$scope.action=data;
				$scope.phase = {
						nom_phase:"",
						services_porteurs:"",
						date_debut_prevue:"",
						date_fin_prevue:"",
						avancement_phase:"",
						date_debut_reelle:"",
						date_fin_reelle:"",
						ordre_phase:"",
						description_phase:"",
						commentaires_phase:""
				};
				
				
				$scope.bool = true;
				$scope.textHeader = 'Ajouter une phase';
				$scope.labelBouton = 'Ajouter phase';
				$scope.modifier = false;
				$scope.ok = function() {
					$http({method: 'PUT', url: '/serveurpcet/index.php/action/ajout',data:$scope.action}).
				    success(function(data, status, headers, config) {
				    	
				    }).then(function(data){
				    	$modalInstance.close($scope.bool);
				    },function(err){
				    	$modalInstance.close($scope.bool);
				    	
				    },function(notif){
				    	
				    });
				};
				$scope.cancel = function() {
					$modalInstance.dismiss('cancel');
		
			};
		}]);
