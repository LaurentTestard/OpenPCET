'use strict';

/* Controllers */

angular
.module('myApp.controllers.gestion', [ 'ui.bootstrap' ]).provider("proConnexion",function(){	
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
})
.controller('gestion',['$scope','$http','$filter','$modal', '$routeParams','proConnexion',function($scope, $http, $filter, $modal, $routeParams,proConnexion) {
	$scope.action_id = $routeParams.id;
	$scope.phases = [];
	$scope.phasesdep = [];
	$scope.budgets = [];
	$scope.indicateurs = [];
	

	$scope.genererfiche=function(){
		$http.post('/serveurpcet/index.php/pcaet/fiche/genere/'+$scope.actionDetails.code_action).success(function(data, status, headers) {
		});
	};
							
	$scope.verifierLigne=function(index)
	{
		if ($scope.indicateurs[index].indicateur=='')
			{
			$scope.indicateurs.splice(index, 1);
			}
	};
	$scope.ajout = false;
	$scope.propritaire=$routeParams.modif;
	// gestion des indicateurs
								
	$scope.openIndic = function(indicateur) {
		var modaleInstance = $modal
				.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSuppressionIndicateur",
					resolve : {
						data : function() {
							return indicateur;
						}
					}
				});
		modaleInstance.result.then(function(b) {
			$scope.modaleResult = b;
			if (b.b) {
					$scope.suppressionIndicateur(b.data);

			}
		});
	};
	
	$scope.suppressionIndicateur = function(data) {
	    $http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/indicateurs/'+data.id}).
	    success(function(data, status, headers, config) {
	    	$scope.chargerIndicateurs();
	    	$scope.genererfiche();
	    });
	};
	
	$scope.openCrAction = function(craction) {
		console.log(craction);
		var modaleInstance = $modal
				.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSuppressionCrAction",
					resolve : {
						data : function() {
							return craction;
						}
					}
				});
		
		modaleInstance.result.then(function(b) {
			$scope.modaleResult = b;
			
			console.log(b);
			if (b.b) {
					$scope.suppressionCrAction(b.data);
			}
		});
	};
	
	$scope.suppressionCrAction = function(data) {
		console.log(data);
	    $http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/cractions/'+data.id}).
	    success(function(data, status, headers, config) {
	    	$scope.refreshCompteRendu();
	    	$scope.genererfiche();
	    });
	};
			
	
            proConnexion.getDonneeUtilisateur().then(function(data){
          		$scope.donneeUtilisateur = data;
          		if($scope.donneeUtilisateur.role_utilisateur==1  || $scope.propritaire=='true' ){
          			$scope.annulerButton='Annuler';
          		}else{
          			$scope.annulerButton='Retour';
          		}
          	});
				            
			$scope.sauvegarderIndicateurModif = function() {
				if($scope.titleModal == "Modifier un indicateur"){
				    $http.put('/serveurpcet/index.php/pcaet/actions/indicateurs/', $scope.indicateur).
					success(function(data, status, headers, config) {
						$scope.chargerIndicateurs();
						$scope.genererfiche();
				    });
				}else {
					var b = true;
					angular.forEach($scope.indicateur, function(value, key){
					     if(value ==""){
					    	 b = false;
					     }
					     else {
					    	 b = true;
					     }
					});
					if(b){
						$scope.indicateur.code_action = $scope.action_id;
						$scope.indicateur.type_modification = "Ajout d'un nouvel indicateur";
						$http.post('/serveurpcet/index.php/pcaet/actions/indicateurs/ajout_indicateur/', $scope.indicateur).
						success(function(data, status, headers, config) {
							$scope.chargerIndicateurs();
							$('#idModalMofifierIndicateur').modal('hide');
					    });
					}else{
						alert("Veuillez renseigner tous les champs");
					}
					
				}
				$scope.cacherPopupAjouterIndicateur();
			};
							
							
				//------------------------------------
				$scope.ajouterNouveauindicateur= function() {
					$scope.phase.code_action = $scope.action_id;
					$scope.phase.type_modification = "Ajout d'une nouvelle phase";
				};
				
				$scope.indicateur = {};
				$scope.objectif_enjeu = {};
				$scope.ajouterIndicateur = function(indicateur, objectif_enjeu) {
					$('#idModalMofifierIndicateur').modal('show');
					$scope.titleModal = "Ajouter un indicateur";
					$scope.indicateur.nom_indicateur = "";
					$scope.indicateur.nom_objectif_enjeu = "";
					$scope.indicateur.valeur_actuelle = "";
					$scope.indicateur.valeur_objectif = "";
					$scope.indicateur.description_indicateur = "";
					$scope.genererfiche();
				};
				
				$scope.chargerPopupModifierIndicateur = function(indicateur, objectif_enjeu) {
					if($scope.propritaire != 'false'){	
						$('#idModalMofifierIndicateur').modal('show');
						$scope.titleModal = "Modifier un indicateur";
						$scope.indicateur.id = indicateur.id;
						$scope.indicateur.nom_indicateur = indicateur.nom_indicateur;
						$scope.indicateur.nom_objectif_enjeu = indicateur.nom_objectif_enjeu;
						$scope.indicateur.valeur_actuelle = indicateur.valeur_actuelle;
						$scope.indicateur.valeur_objectif = indicateur.valeur_objectif;
						$scope.indicateur.description_indicateur = indicateur.description_indicateur;
					}
				};
				
				$scope.cacherPopupAjouterIndicateur = function() {
					$('#idModalMofifierIndicateur').modal('hide');
				};
				
				
				// gestion des phases et phasesdep
				$scope.phase = {};
				$scope.chargerPopupModifier = function(phase) {
					$('#idModalMofifierPhase').modal('show');
					$scope.titleModal = "Modifier une phase";
					$scope.phase.id = phase.id;
					$scope.phase.ordre_phase = phase.ordre_phase;
					$scope.phase.nom_phase = phase.nom_phase;
					$scope.phase.date_debut_prevue = phase.date_debut_prevue;
					$scope.phase.date_fin_prevue = phase.date_fin_prevue;
					$scope.phase.date_debut_reelle = phase.date_debut_prevue;
					$scope.phase.date_fin_reelle = phase.date_fin_prevue;
					$scope.phase.description_phase = phase.description_phase;
					$scope.phase.services_porteurs = phase.services_porteurs;
					$scope.phase.avancement_phase = phase.avancement_phase;
					$scope.phase.commentaires_phase = phase.commentaires_phase;
				 };
				 
				 $scope.phasedep = {};
					$scope.chargerPopupModifierPhasedep = function(phasedep) {
						$('#idModalMofifierPhasedep').modal('show');
						$scope.titleModal = "Modifier une phasedep";
						$scope.phasedep.id = phasedep.id;
						$scope.phasedep.ordre_phasedep = phasedep.ordre_phasedep;
						$scope.phasedep.nom_phasedep = phasedep.nom_phasedep;
						$scope.phasedep.date_debut_prevuephd = phasedep.date_debut_prevuephd;
						$scope.phasedep.date_fin_prevuephd = phasedep.date_fin_prevuephd;
						$scope.phasedep.description_phasedep = phasedep.description_phasedep;
						$scope.phasedep.services_porteursphd = phasedep.services_porteursphd;
						$scope.phasedep.commentaires_phasedep = phasedep.commentaires_phasedep;
					 };
				 
				 
				 $scope.chargerPopupVoirphasedep = function(phasedep) {
						$('#idModalVoirPhasedep').modal('show');
						$scope.phasedep.id = phasedep.id;
						$scope.phasedep.ordre_phasedep = phasedep.ordre_phasedep;
						$scope.phasedep.nom_phasedep = phasedep.nom_phasedep;
						$scope.phasedep.date_debut_prevuephd = phasedep.date_debut_prevuephd;
						$scope.phasedep.date_fin_prevuephd = phasedep.date_fin_prevuephd;
						$scope.phasedep.description_phasedep = phasedep.description_phasedep;
						$scope.phasedep.services_porteursphd = phasedep.services_porteursphd;
						$scope.phasedep.commentaires_phasedep = phasedep.commentaires_phasedep;
					 };
				 
				 
				 $scope.chargerPopupVoir = function(phase) {
						$('#idModalVoirPhase').modal('show');
						$scope.phase.id = phase.id;
						$scope.phase.ordre_phase = phase.ordre_phase;
						$scope.phase.nom_phase = phase.nom_phase;
						$scope.phase.date_debut_prevue = phase.date_debut_prevue;
						$scope.phase.date_fin_prevue = phase.date_fin_prevue;
						$scope.phase.date_debut_reelle = phase.date_debut_prevue;
						$scope.phase.date_fin_reelle = phase.date_fin_prevue;
						$scope.phase.description_phase = phase.description_phase;
						$scope.phase.services_porteurs = phase.services_porteurs;
						$scope.phase.avancement_phase = phase.avancement_phase;
						$scope.phase.commentaires_phase = phase.commentaires_phase;
					 };
					 

				$scope.afficherPopupAjouter = function() {
					$('#idModalMofifierPhase').modal('show');
					$scope.titleModal = "Ajouter une nouvelle phase";
					$scope.phase.ordre_phase = "";
					$scope.phase.nom_phase = "";
					$scope.phase.date_debut_prevue = "";
					$scope.phase.date_fin_prevue = "";
					$scope.phase.date_debut_reelle = "";
					$scope.phase.date_fin_reelle = "";
					$scope.phase.description_phase = "";
					$scope.phase.services_porteurs = "";
					$scope.phase.avancement_phase = "Non démarrée";
					$scope.phase.commentaires_phase = "";
				};
				
				$scope.afficherPopupAjouterPhasedep = function() {
					$('#idModalMofifierPhasedep').modal('show');
					$scope.titleModal = "Ajouter une nouvelle phase";
					$scope.phasedep.ordre_phasedep = "";
					$scope.phasedep.nom_phasedep = "";
					$scope.phasedep.date_debut_prevuephd = "";
					$scope.phasedep.date_fin_prevuephd = "";
					$scope.phasedep.description_phasedep = "";
					$scope.phasedep.services_porteursphd = "";
					$scope.phasedep.commentaires_phasedep = "";
				};
			
				// ouverture pop up controlle suppression
				$scope.open = function(phase) {
					var modaleInstance = $modal
							.open({
								templateUrl : "partials/modal.suppression.html",
								controller : "ModalSuppressionPhase",
								resolve : {
									data : function() {
										return phase;
									}
								}
							});
					modaleInstance.result.then(function(b, data) {
						$scope.modaleResult = b;
						if (b.b) {
							$scope.controlleSuppressionPhase(b.data);
						}
					});
				};
				
				$scope.sort_phases = "ordre_phase";

				$scope.controlleSuppressionPhase = function(data) {
					$http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/phases/'+data.id, data : data}).
				    success(function(data, status, headers, config) {
				    	$scope.chargerPhases();
				    	$scope.genererfiche();
				    });
				    
				};
				
				
				
				$scope.supp = function(phasedep) {
					var modaleInstance = $modal
							.open({
								templateUrl : "partials/modal.suppression.html",
								controller : "ModalSuppressionPhasedep",
								resolve : {
									data : function() {
										return phasedep;
									}
								}
							});
					modaleInstance.result.then(function(b, data) {
						$scope.modaleResult = b;
						if (b.b) {
							$scope.controlleSuppressionPhasedep(b.data);
						}
					});
				};
				

				$scope.controlleSuppressionPhasedep = function(data) {
					$http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/phasesdep/'+data.id, data : data}).
				    success(function(data, status, headers, config) {
				    	$scope.chargerPhasesdep();
				    	$scope.genererfiche();
				    });
				    
				};
				
				$scope.cacherPopupAjouter = function() {
					$('#idModalMofifierPhase').modal('hide');
				};
				
				$scope.cacherPopupAjouterphasedep = function() {
					$('#idModalMofifierPhasedep').modal('hide');
				};
				
				$scope.associerdoc = function(id) {
					console.log('0');
					var modaleInstance = $modal
							.open({
								templateUrl : "partials/modal.associer.actiondocument.html",
								controller : "ModalAssocierdoc",
								resolve : {
									data : function() {
										return id;
									}
								}
							});
					modaleInstance.result.then(function(result) {
						 $scope.documentsAction=result;
					});
				};
							

		 $scope.sauvegarderPhaseModif = function() {
				if($scope.titleModal == "Modifier une phase"){
					//formatage des dates
					var day_date_debut_prevue = $scope.formatDate($scope.phase.date_debut_prevue.getDate());
				    var month_date_debut_prevue = $scope.formatDate($scope.phase.date_debut_prevue.getMonth() + 1);
				    var year_date_debut_prevue = $scope.phase.date_debut_prevue.getFullYear();
				    $scope.phase.date_debut_prevue = year_date_debut_prevue+"-"+month_date_debut_prevue+"-"+day_date_debut_prevue;
				    
				    var day_date_fin_prevue = $scope.formatDate($scope.phase.date_fin_prevue.getDate());
				    var month_date_fin_prevue = $scope.formatDate($scope.phase.date_fin_prevue.getMonth() + 1);
				    var year_date_fin_prevue = $scope.phase.date_fin_prevue.getFullYear();
				    $scope.phase.date_fin_prevue = year_date_fin_prevue+"-"+month_date_fin_prevue+"-"+day_date_fin_prevue;
					
				    var day_date_debut_reelle = $scope.formatDate($scope.phase.date_debut_reelle.getDate());
				    var month_date_debut_reelle = $scope.formatDate($scope.phase.date_debut_reelle.getMonth() + 1);
				    var year_date_debut_reelle = $scope.phase.date_debut_reelle.getFullYear();
				    $scope.phase.date_debut_reelle = year_date_debut_reelle+"-"+month_date_debut_reelle+"-"+day_date_debut_reelle;
				    
				    var day_date_fin_reelle = $scope.formatDate($scope.phase.date_fin_reelle.getDate());
				    var month_date_fin_reelle = $scope.formatDate($scope.phase.date_fin_reelle.getMonth() + 1);
				    var year_date_fin_reelle = $scope.phase.date_fin_reelle.getFullYear();
				    $scope.phase.date_fin_reelle = year_date_fin_reelle+"-"+month_date_fin_reelle+"-"+day_date_fin_reelle;
				    
				    
				    $http.put('/serveurpcet/index.php/pcaet/actions/phases/', $scope.phase).
					success(function(data, status, headers, config) {
						$scope.chargerPhases();
						$scope.genererfiche();
				    });
				}else {
					var b = true;
					angular.forEach($scope.phase, function(value, key){
					     if(value ==""){
					    	 b = false;
					     }
					     else {
					    	 b = true;
					     }
					});
					if(b){
						$scope.phase.code_action = $scope.action_id;
						$scope.phase.type_modification = "Ajout d'une nouvelle phase";
						$http.post('/serveurpcet/index.php/pcaet/actions/phases/ajout_phase/', $scope.phase).
						success(function(data, status, headers, config) {
							$scope.chargerPhases();
							$scope.genererfiche();
					    });
					}else{
						alert("Veuillez renseigner tous les champs");
					}
					
				}
				$scope.cacherPopupAjouter();
			};
			
			$scope.phasedep.code_action = $scope.action_id;
			$scope.sauvegarderPhasedepModif = function() {
				if($scope.titleModal == "Modifier une phasedep"){
					//formatage des dates
					var day_date_debut_prevuephd = $scope.formatDate($scope.phasedep.date_debut_prevuephd.getDate());
				    var month_date_debut_prevuephd = $scope.formatDate($scope.phasedep.date_debut_prevuephd.getMonth() + 1);
				    var year_date_debut_prevuephd = $scope.phasedep.date_debut_prevuephd.getFullYear();
				    $scope.phasedep.date_debut_prevuephd = year_date_debut_prevuephd+"-"+month_date_debut_prevuephd+"-"+day_date_debut_prevuephd;
				    
				    var day_date_fin_prevuephd = $scope.formatDate($scope.phasedep.date_fin_prevuephd.getDate());
				    var month_date_fin_prevuephd = $scope.formatDate($scope.phasedep.date_fin_prevuephd.getMonth() + 1);
				    var year_date_fin_prevuephd = $scope.phasedep.date_fin_prevuephd.getFullYear();
				    $scope.phasedep.date_fin_prevuephd = year_date_fin_prevuephd+"-"+month_date_fin_prevuephd+"-"+day_date_fin_prevuephd;
				    
				    $http.put('/serveurpcet/index.php/pcaet/actions/phasesdep/', $scope.phasedep).success(function(data, status, headers, config) {
						$scope.chargerPhasesdep();
						$scope.chargerPhases();
						$scope.genererfiche();
						$scope.cacherPopupAjouterphasedep();
				    });
				}else {
					var b = true;
					angular.forEach($scope.phasedep, function(value, key){
					     if(value ==""){
					    	 b = false;
					     }
					     else {
					    	 b = true;
					     }
					});
					if(b){
						$scope.phasedep.code_action = $scope.action_id;
						$scope.phasedep.type_modification = "Ajout d'une nouvelle phase";
						$http.post('/serveurpcet/index.php/pcaet/actions/phasesdep/ajout_phasedep', $scope.phasedep).
						success(function(data, status, headers, config) {
							$scope.chargerPhasesdep();
							$scope.genererfiche();
							//$('#idModalMofifierPhasedep').modal('hide');
					    });
					}else{
						alert("Veuillez renseigner tous les champs");
					}
					
				}
				$scope.cacherPopupAjouterphasedep();
			};
			
		
			$scope.formatDate = function(date) {
				if (date<10){
					date = "0"+date;
				};
				return date;
			};
		
			// gestion du budget
			
			
			$scope.openBudgetConfim = function() {
				console.log('0');
				var modaleInstance = $modal
						.open({
							templateUrl : "partials/modal.suppression.html",
							controller : "ModalModifierBudget",
							resolve : {
								data : function() {
									return null;
								}
							}
						});
				modaleInstance.result.then(function() {
							$scope.sauvegarderBudget();

				});
			};
			
			$scope.sauvegarderBudget = function() {
				$scope.budget.type_modification = "Modification du budget";
				$http.put('/serveurpcet/index.php/pcaet/actions/budget/'+$routeParams.id, $scope.budget).
				success(function(data, status, headers, config) {
					$scope.chargerBudget();
					$scope.genererfiche();
			    });
			};
			
			$scope.aj=false;
			$scope.ajouterBudgetModal = function() {
				
				var modaleInstanceAction = $modal
						.open({
							templateUrl : "partials/modal.ajouter.budget.html",
							controller : "ModalAjouterBudgetCtrl",
							resolve : {
								data : function() {
									return $scope.action_id;
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
			
			
			$scope.aj=false;
			$scope.ajouterEnjeuModal = function() {
				var modaleInstanceAction = $modal
						.open({
							templateUrl : "partials/modal.ajout.enjeu.html",
							controller : "ModalAjouterEnjeuCtrl",
							resolve : {
								data : function() {
									return null;
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
			
			$scope.aj=false;
			$scope.creerIndicateurModal = function() {
				
				var modaleInstanceAction = $modal
						.open({
							templateUrl : "partials/modal.creer.indicateur.html",
							controller : "ModalAjouterEnjeuCtrl",
							resolve : {
								data : function() {
									return $routeParams.id;
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
			
			$scope.aj=false;
			$scope.ajouterIndicateurModal = function() {
				
				var modaleInstanceAction = $modal
						.open({
							templateUrl : "partials/modal.ajout.valeurindicateur.html",
							controller : "ModalAjouterEnjeuCtrl",
							resolve : {
								data : function() {
									return null;
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
			
			
			// requete get pour remplir le champ phasesDep à
			// l'ouverture de la page
			//$scope.phasesdep = {};
			$scope.chargerPhasesdep = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/phasesdep/'+$routeParams.id).success(function(data, status, headers) {
			  		$scope.phasesdep = data;
			  		for (var i = 0; i < $scope.phasesdep.length; i++) {
			  			$scope.phasesdep[i].date_debut_prevuephd = new Date($scope.phasesdep[i].date_debut_prevuephd);
						$scope.phasesdep[i].date_fin_prevuephd = new Date($scope.phasesdep[i].date_fin_prevuephd);
			  		}
				});
			};
			
			$scope.chargerPhasesdep();
			
			
			// requete get pour remplir le champ phases à
			// l'ouverture de la page
			$scope.chargerPhases = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/phases/'+$routeParams.id).success(function(data, status, headers) {
			  		$scope.phases = data;
			  		for (var i = 0; i < $scope.phases.length; i++) {
			  			$scope.phases[i].date_debut_prevue = new Date($scope.phases[i].date_debut_prevue);
						$scope.phases[i].date_fin_prevue = new Date($scope.phases[i].date_fin_prevue);
						$scope.phases[i].date_debut_reelle = new Date($scope.phases[i].date_debut_reelle);
						$scope.phases[i].date_fin_reelle = new Date($scope.phases[i].date_fin_reelle);
			  		}
				});
			};
			
			$scope.chargerPhases();
			
			
			// requete get pour remplir le champ budget à
			// l'ouverture de la page
			$scope.chargerBudget = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/budget/'+$routeParams.id).success(function(data, status, headers) {
		  		$scope.budget = data;
				});
			};
			$scope.chargerBudget();
			// requete get pour remplir le champ indicateurs à
			// l'ouverture de la page
			$scope.chargerIndicateurs = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/indicateurs/'+$routeParams.id).success(function(data, status, headers) {
					$scope.indicateurs =[];
		  		for(var i=0;i<data.length;i++)
		  			{
		  			for(var j=0;j<data[i].length;j++)
		  			$scope.indicateurs.push(data[i][j]);
		  			}
				});
			};
			$scope.chargerIndicateurs();
			// requete get pour remplir les enjeux des indicateurs
			$scope.chargerObjectifs_enjeu = function() {
				$http.get('/serveurpcet/index.php/pcaet/actions/objectifs/'+$routeParams.id).success(function(data, status, headers) {
					$scope.objectifs_enjeu = data;
				});
			};
			$scope.chargerObjectifs_enjeu();
			
			
			$scope.chargerBudgets = function() {
				$http.get('/serveurpcet/index.php/budgets/'+$routeParams.id).success(function(data, status, headers) {
					$scope.budgets = data;
				});
			};
			$scope.chargerBudgets();

							
							/*
							 * Comptes-rendus
							 */
              $scope.cractions = [];
              
              $scope.newCRAction = {
                  description_cr:'',
                  est_actualite:'false',
                  action_id:$routeParams.id
              };
              
              // requete get pour remplir le tableau des comptes-rendus
              $scope.refreshCompteRendu= function() {
              $http.get('/serveurpcet/index.php/pcaet/actions/cractions/'+$routeParams.id).success(function(data, status, headers) {
                $scope.cractions = data;
              })};
              $scope.refreshCompteRendu();
              
              $scope.afficherAjouterCompteRendu = function() {
                $('#idModalAjouterCR').modal('show');
              }
              
              $scope.fermerAjouterCompteRendu = function() {
                $scope.newCRAction.description_cr = '';
                $('#idModalAjouterCR').modal('hide');
              }
              
              $scope.ajouterCompteRendu = function() { 
                $http.post('/serveurpcet/index.php/pcaet/actions/cractions/', $scope.newCRAction);
                $('#idModalAjouterCR').modal('hide');
                $scope.refreshCompteRendu();
              }
                            // requete get pour lier les documents
              $scope.showDocumentsAction = function() { 
              $http.get('/serveurpcet/index.php/action/documents/'+$routeParams.id).success(function(data, status, headers) {
                $scope.documentsAction = data;
              })};
              $scope.showDocumentsAction();
              
          	$scope.delierDocumentAction = function(document	){
        		$http({
        			method: 'PUT', 
        			url: '/serveurpcet/index.php/document/delier/'+$scope.action_id+'/'+document.id
        		}).success(function(data, status, headers, config) {
        			$scope.showDocumentsAction();
        			$scope.genererfiche();
				  	});
        		
          	
          	
          	};
        	

						} ]).controller('ModalSuppressionPhase',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'une phase";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer la phase : " + data.nom_phase  + " ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									
						} ]).controller('ModalSuppressionPhasedep',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'une phase";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer la phase : " + data.nom_phasedep  + " ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									
						} ]).controller('ModalSuppressionCrAction',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'un compte rendu";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer ce commentaire ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};

						} ]).controller('ModalSuppressionIndicateur',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Suppression d'un indicateur";
									$scope.textBodySupp ="Etes-vous sur de vouloir supprimer l'indicateur : " + data.nom_indicateur  + " ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									

						} ]).controller('ModalAjouterBudgetCtrl',
								[ '$scope', '$modal', '$modalInstance','$http','data', function($scope, $modal, $modalInstance, $http, data) {
									
									//$scope.themes = [{"key":1,"nom_thematique_concernee":1},{"key":2,"nom_thematique_concernee":2},{"key":3,"nom_thematique_concernee":3}];
									$scope.taille=0;
									$scope.partenaire={"nom":"","budget":""};
									$scope.budget = {
											annee:"",
											renseigner:"",
											commcomm:"",
											partenairesfin:[],
											budget_consomme:"",
											budget_total:'0',
											commentaire:"",
											idaction:data,
									};
									
									$scope.ajoutpartenaire = function(partenaire) {
										$scope.budget.partenairesfin[$scope.taille]=partenaire;
										$scope.budget.budget_total=parseInt($scope.budget.budget_total)+parseInt(partenaire.budget);
										$scope.taille++;
										$scope.partenaire={"nom":"","budget":""};
									};
									
									
									$scope.modifier = false;
									$scope.nomPart="";
									$scope.budgetPart="";
									$scope.ok = function() {
									    $http.post('/serveurpcet/index.php/budget/ajout/',$scope.budget).
										success(function(data, status, headers, config) {
											for (var i = 0; i < $scope.budget.partenairesfin.length; i++) {
												$scope.nomPart=$scope.budget.partenairesfin[i].nom;
												$scope.budgetPart=$scope.budget.partenairesfin[i].budget;
												$scope.ajoutpartenaireFin();
											}
									    }).then(function(data){									    	
									    	$modalInstance.close($scope.bool);
									    },function(err){
									    	//$modalInstance.close($scope.bool);
									    	
									    },function(notif){
									    	
									    });
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
								};
								
								$scope.ajoutpartenaireFin = function() {
								    $http.post('/serveurpcet/index.php/partenaireFinancier/ajout/'+$scope.budget.idaction+'/'+$scope.budget.annee+'/'+$scope.nomPart+'/'+$scope.budgetPart).
									success(function(data, status, headers, config) {
								    }).then(function(data){
								    },function(err){
								    });
								};
							}]).controller('ModalModifierBudget',
								[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
									$scope.data=data;
									$scope.textHeaderSupp = "Modification du budget";
									$scope.textBodySupp ="Etes-vous sur de vouloir modifier le budget dédié à cette action ?";
												$scope.bool = true;
									$scope.ok = function() {
										$modalInstance.close({b:$scope.bool,data:$scope.data});
										$modalInstance.iscancel=false;
									};
									$scope.cancel = function() {
										$modalInstance.dismiss('cancel');
									};
									

						} ]).controller('ModalAjouterEnjeuCtrl',
								[ '$scope', '$modal', '$modalInstance','$http','data', function($scope, $modal, $modalInstance, $http, data) {
									
									$scope.enjeu = "";
									//$scope.id_action=data;
									$scope.ok= function(enjeu) {
									    $http.post('/serveurpcet/index.php/enjeu/ajout/'+enjeu).
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
									
									$scope.indicateur ={nom:"",enjeu:""};
									$scope.indicateurval ={nom:"",annee:"",valeur:"",id_action:"3"};
									$scope.enjeux=[];
									$scope.indicateurs=[];
									
									$scope.save= function() {
									    $http.post('/serveurpcet/index.php/indicateur/ajout/'+$scope.indicateur.nom+'/'+$scope.indicateur.enjeu).
										success(function(data, status, headers, config) {
									    }).then(function(data){									    	
									    	$modalInstance.close($scope.bool);
									    },function(err){
									    	$modalInstance.close($scope.bool);
									    	
									    },function(notif){
									    	
									    });
									};
									
									$scope.saveIndic = function() {
									    $http.post('/serveurpcet/index.php/indicateurvaleur/ajout/',$scope.indicateurval).
										success(function(data, status, headers, config) {
									    }).then(function(data){
									    	$modalInstance.close($scope.bool);									    	
									    },function(err){
									    },function(notif){
									    	
									    });
									};
									
									$scope.recupererEnjeux = function() {
										$http.get('/serveurpcet/index.php/enjeux').success(function(data, status, headers) {
											$scope.enjeux=data;
										});
									};
									
									$scope.recupererIndicateurs = function() {
										$http.get('/serveurpcet/index.php/indicateurs').success(function(data, status, headers) {
											$scope.indicateurs=data;
										});
									};
									
									$scope.recupererIndicateurs();
									$scope.recupererEnjeux();
									

							}]).run(function(editableOptions) {
							  editableOptions.theme = 'bs3'; // bootstrap3
																// theme. Can be
																// also 'bs2',
																// 'default'
						});;
