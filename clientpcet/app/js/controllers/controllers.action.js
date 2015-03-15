'use strict';

/* Controllers */

angular.module('myApp.controllers.action', [])
.controller('action', [ '$scope', '$http', '$routeParams','$modal','proConnexion', function($scope,$http,$routeParams,$modal,proConnexion) {
	$scope.thematiques = [];
	/**
	 * Partie du controller gérant les sous pages
	 */
	// Début configuration du menu
	$scope.onglets = [{
			display:"Action",
			path:"consulter"
		},{
			display:"Mise en oeuvre",
			path:"gestion"
		}
//	,{
//		display:"Visualisation graphique",
//		path:"graphique"
//	},
		,{
			display:"Historique des modifications",
			path:"historique"
		}
	];
	
	$scope.idbd = $routeParams.id+'/'+$routeParams.modif; // ID de l'action appraet en fin d'url
	$scope.starturl = "action"; // le début de l'url doit être spécifier
	//
	// Permet de spécifier l'onglet selectionner par rapport à l'attribut de
	// l'url
	$scope.ongletselect = $routeParams.etat;
	// Fin configuration du menu
	
	// sousPage défini l'url de la sous pages
	$scope.sousPage = 'partials/action.'+$routeParams.etat+'.html';
	$scope.propritaire=$routeParams.modif;

	/**
	 * Onglet Action (consulter)
	 */
	
	
	if ($routeParams.etat == 'consulter' || $routeParams.etat== 'historique'|| $routeParams.etat== 'gestion') {
	  	$scope.action_id = $routeParams.id;
		$scope.actionData;
		$scope.actionnonlie=[];
		
		/** DEBUT Associer action **/
		$scope.associerlieraction = function() {
			var modaleInstance = $modal.open({
				templateUrl : "partials/modal.associer.lieraction.html",
				controller : "ModalLierAction",
				resolve : {
					data : function() {
						return $scope.actionDetails;
					}
				}
			});
			modaleInstance.result.then(function(result) {
				$scope.getactionactions();
				$scope.getInfoAction();
				$scope.actionnonlie=result;
			});
		};
		
		$scope.initfiches=function(){
			$http.post('/serveurpcet/index.php/fiche/initfiches/').success(function(data, status, headers) {
			});
		};
		
		//$scope.initfiches();
		
		$scope.genererfiche=function(){
			$http.post('/serveurpcet/index.php/pcaet/fiche/genere/'+$scope.actionDetails.code_action).success(function(data, status, headers) {
			});
		};		
		
		$scope.delieraction=function(actionlier){
			$http.put('/serveurpcet/index.php/action/delier/'+$scope.actionDetails.id+'/'+actionlier.id).success(function(data, status, headers) {
				$scope.getInfoAction();
				$scope.getactionactions();
			}); 
		};
		/** FIN Associer action **/
		
		/** DEBUT Sauvegarder action **/
		$scope.sauvegarderAction=function(){
			$scope.actionData = $scope.actionData;
			if(angular.isUndefined($scope.actionDetails.nom_objectif_strategique)){
				$scope.actionDetails.nom_objectif_strategique = "";
			}
					
			$http.put('/serveurpcet/index.php/pcaet/actions/',$scope.actionDetails).success(function(res) {
				
				$scope.getInfoAction();
		    }).error(function(e){
		    	
		    });
			$scope.genererfiche();
		};
		/** FIN Sauvegarder action **/
		
		/** DEBUT afficher action **/
		$scope.transformeServeurBool = function(b){
			if(angular.isString(b)){
				if(b=="1"){
					b = true;
				}else{
					b = false;
				};
			};
			return b;
		};
		
		
		
		
		$scope.getInfoAction = function(){
		  	$http.get('/serveurpcet/index.php/pcaet/action/'+$scope.action_id).success(function(data, status, headers) {
		  		$scope.actionDetails = data;
		  		$scope.actionDetails.est_adaptation = $scope.transformeServeurBool($scope.actionDetails.est_adaptation);
		  		$scope.actionDetails.est_attenuation = $scope.transformeServeurBool($scope.actionDetails.est_attenuation);
		  		$scope.actionDetails.est_communication = $scope.transformeServeurBool($scope.actionDetails.est_communication);
		  		$scope.actionDetails.est_formation = $scope.transformeServeurBool($scope.actionDetails.est_formation);
		  		$scope.actionDetails.est_appui_technique = $scope.transformeServeurBool($scope.actionDetails.est_appui_technique);
		  		$scope.actionDetails.est_appui_financier = $scope.transformeServeurBool($scope.actionDetails.est_appui_financier);
		  		/**
		  		 * Correction CSS
		  		 */
		  		$(".formEditable").css("padding-top","0px");
		  	});
		};
		$scope.getInfoAction();
		/** FIN Sauvegarder action **/
		
		/** DEBUT lier mot clé action **/
		
		$scope.motclefnonliers = [];
		$scope.motclefliers = [];
		$scope.nouveaumc=[];
		$scope.actionactions=[];
		
		$scope.getactionactions = function(){
			$http.get('/serveurpcet/index.php/action/actionaction/'+$scope.action_id).success(function(data, status, headers) {
				$scope.actionactions = data;
			});
		};
		$scope.getactionactions();
		
		$scope.motclefnonlier = function(){
			$http.get('/serveurpcet/index.php/action/motclef/lier/'+$scope.action_id).success(function(data, status, headers) {
				
				$scope.motclefliers = data;
			});
		};
		
		$scope.motcleflier = function(){
			$http.get('/serveurpcet/index.php/action/motclef/delier/'+$scope.action_id).success(function(data, status, headers) {
				
				$scope.motclefnonliers = data;
			});
		};
		
		$scope.motcleflier();
		$scope.motclefnonlier();
		
		$scope.addMotclef = function(nouveauxMotclef){
			$http.post('/serveurpcet/index.php/action/motclef/',{
				id:$scope.action_id,
				motclef:nouveauxMotclef
			}).success(function(data, status, headers) {
				$scope.motcleflier();
				$scope.motclefnonlier();
				$scope.nouveauxMotclef = "";
			});			
			$scope.genererfiche();
		};
		
		$scope.delierMotClef = function(motclef){
			$http.put('/serveurpcet/index.php/action/motclef/delier/'+$scope.action_id+'/'+motclef.id).success(function(){
				$scope.motcleflier();
				$scope.motclefnonlier();
				$scope.genererfiche();
			});
		};
		$scope.lierMotClef = function(motclef){
			$http.put('/serveurpcet/index.php/action/motclef/lier/'+$scope.action_id+'/'+motclef.id).success(function(){
				$scope.motcleflier();
				$scope.motclefnonlier();
				$scope.genererfiche();
			});
		};
		
		/** DEBUT lier mot clé action **/
		
		
		/** DEBUT list thematique concernee **/
		$scope.thematiques = [];
		$scope.getThematiques = function(){
			$http.get('/serveurpcet/index.php/thematiques').success(function(data){
				$scope.thematiques = data;
			});
		};
		$scope.setThematiqueInData = function(thematique){
			$http.put('/serveurpcet/index.php/thematiques/lierAction/'+$scope.action_id+'/'+thematique.id).success(function(){
				$scope.getInfoAction();
				$scope.genererfiche();
			});
		};
		$scope.getThematiques();
		/** FIN list thematique concernee **/
		
		
		$scope.engagements = [];
		$scope.getengagements = function(){
			$http.get('/serveurpcet/index.php/engagements').success(function(data){
				$scope.engagements = data;
			});
		};
		
		$scope.getengagements();
		
		$scope.engagement=[];
		$scope.objs=[];
		$scope.getobjs = function(engagement){
			$http.get('/serveurpcet/index.php/engagements/'+engagement.id).success(function(data){
				$scope.engagement=engagement;
				$scope.objs=data;
			});
		};
		
		$scope.setobjInData = function(objectif){
			$http.put('/serveurpcet/index.php/objectif/lieraction/'+$scope.action_id+'/'+objectif.id).success(function(){
				$scope.getInfoAction();
				$scope.genererfiche();
			});
		};
		
		
		/** DEBUT partenaire **/
		$scope.setParametre = function(nouveauxPartenaire){
			$http.post('/serveurpcet/index.php/partenaire/',{
				id:$scope.action_id,
				nom_partenaire:nouveauxPartenaire
			}).success(function(){
				$scope.getInfoAction();
				$scope.genererfiche();
			});
		};
		
		$scope.delParametre = function(partenaire){
			$http({method: 'DELETE', url: '/serveurpcet/index.php/partenaire/'+partenaire.id}).
				success(function(data, status, headers, config) {
					$scope.getInfoAction();
					$scope.genererfiche();
		    });
		};
		/** FIN partenaire **/
	}
	

	$scope.lienAction={url:'',nom:''};
	$scope.partenaire={nom:''};
	$scope.AjouterLien=function(){
	 var lien={code_action:$scope.lienAction.url, nom_action:$scope.lienAction.nom};
	 //$scope.actionDetails.lien_avec_autres_actions.push(lien);
	 $scope.actionDetails.actionaction.push(lien);
	};

	$scope.setUrl=function(index){
		$scope.lienAction.nom=$scope.actionsGeneral[index].nom_action;
		$scope.lienAction.url=$scope.actionsGeneral[index].action_id;
	};
	$scope.donneeUtilisateur = {};

	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.donneeUtilisateur = data;
	});
	
	$scope.chargerPopupVoirContexte = function() {
		$('#idModalVoirContexte').modal('show');
		$scope.actionDetails.contexte_action = $scope.actionDetails.contexte_action;
	 };
	
	$scope.aj=false;
	$scope.envoyermail = function(email) {
		
		var modaleInstanceAction = $modal
				.open({
					templateUrl : "partials/modal.envoyer.mail.html",
					controller : "ModalEnvMailCtrl",
					resolve : {
						data : function() {
							return email;
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
	
	
	
} ]).controller('ModalLierAction',
		[ '$scope', '$modalInstance','$http','data', function($scope, $modalInstance,$http,data) {
			
			$scope.getactionsnonliees = function (){
				  $http.get('/serveurpcet/index.php/action/actionnonaction/'+data.id).success(function(data, status, headers) {
					  $scope.actionnonliers = data;
				  }); 
				};
				
			
			$scope.actionnonliers = $scope.getactionsnonliees();
			$scope.ok = function(actionnonlie) {
				$http.put('/serveurpcet/index.php/action/lier/'+data.id+'/'+actionnonlie.id).success(function(data, status, headers) {
					  $scope.actionnonliers = data;
					  $modalInstance.close(actionnonlie);
					  $scope.genererfiche();
				}); 
			};
			
			
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).controller('ModalEnvMailCtrl',
		[ '$scope', '$modalInstance','$http','data', function($scope, $modalInstance, $http, data) {
			
			//$scope.themes = [{"key":1,"nom_thematique_concernee":1},{"key":2,"nom_thematique_concernee":2},{"key":3,"nom_thematique_concernee":3}];
			$scope.addmail=data;
			$scope.mail = {
					exp:"",
					sujet:"",
					dest:"rnundoo@le-gresivaudan.fr",
					message:"",
			};
			
			
			$scope.bool = true;
			$scope.textHeader = 'Envoyer mail';
			$scope.labelBouton = 'Envoyer';
			$scope.modifier = false;
			$scope.ok = function() {
				$http({method: 'POST', url: '/serveurpcet/index.php/action/mail',data:$scope.mail}).
			    success(function(data, status, headers, config) {
			    	$modalInstance.close($scope.bool);
			    }).then(function(data){
			    	
			    },function(err){
			    	//$modalInstance.close($scope.bool);
			    	
			    },function(notif){
			    	
			    });
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
	
		};
	}]).run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});
