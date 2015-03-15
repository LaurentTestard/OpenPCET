angular
		.module('myApp.controllers.utilisateurs', [ 'ui.bootstrap' ])
		.controller('ModalAjoutUtilisateurCtrl',
				[ '$scope', '$modalInstance','$http', function($scope, $modalInstance,$http) {
					$scope.utilisateur = {
							login_utilisateur:"",
							mot_de_passe:"",
							prenom_utilisateur:"",
							nom_utilisateur:"",
							organisation:"",
							tel_interne:"",
							tel_standard:"",
							role_utilisateur:"",
							email:""
					};
					
					
					
					$scope.bool = true;
					$scope.textHeader = 'Ajout d\'un utilisateur';
					$scope.labelBouton = 'Ajouter l\'utilisateur';
					$scope.modifier = false;
					$scope.ok = function() {
						
						$http({method: 'PUT', url: '/serveurpcet/index.php/utilisateur/ajout',data:$scope.utilisateur}).
					    success(function(data, status, headers, config) {
					    	
					    }).then(function(data){
					    	$modalInstance.close($scope.bool);
					    },function(err){
					    	
					    },function(notif){
					    	
					    });
						
					};
					$scope.cancel = function() {
						$modalInstance.dismiss('cancel');
					};
				}]).controller('ModalModifierUtilisateurCtrl',
			[ '$scope', '$modalInstance','$http','data', function($scope, $modalInstance,$http,data) {
				//Scope du text Header, afin d'avoir un modal générique et des textes header qui changent.
			  
				$scope.textHeader = 'Modification d\'un utilisateur';
				$scope.labelBouton = 'Enregistrer les modifications';
				$scope.utilisateur = data;
				$scope.bool = true;
				$scope.modifier = true;
				$http({method: 'GET', url: '/serveurpcet/index.php/utilisateur/'+data.id,data:$scope.utilisateur}).
			    	success(function(data, status, headers, config) {
			    	$scope.utilisateur = data;
			    	$scope.mod=true;
			    });
				
				$scope.ok = function() {
					
					$http({method: 'POST', url: '/serveurpcet/index.php/utilisateur/miseajour',data:$scope.utilisateur}).
				    success(function(data, status, headers, config) {
				    	
				    }).then(function(data){
				    	$modalInstance.close($scope.bool);
				    },function(err){
				    	
				    },function(notif){
				    	
				    });
					
				};
				$scope.cancel = function() {
					$modalInstance.dismiss('cancel');
				};
			}]).controller('ModalAssocierUtilisateur',
			[ '$scope', '$modalInstance','d','$http', function($scope, $modalInstance, d,$http) {
		$scope.bool = true;
		$scope.loadActionNonlier = function(){
			$http.get('/serveurpcet/index.php/utilisateur/actionnonlie/'+d.id).success(function(data, status, headers) {
				$scope.actionnonlies = [];
				$scope.actionnonlies = data;
			});
		};
		
		$scope.loadActionlier = function(){
			$http.get('/serveurpcet/index.php/utilisateur/actionlie/'+d.id).success(function(data, status, headers) {
				$scope.actionlies = [];
				$scope.actionlies = data;
			});
		};
		
		$scope.loadActionNonlier();
		$scope.loadActionlier();
		
		$scope.lierAction = function(action){
			$http.put('/serveurpcet/index.php/utilisateur/lier/'+action.id+'/'+d.id).success(function(data, status, headers) {
				$scope.loadActionNonlier();
				$scope.loadActionlier();
			});
		};
		
		$scope.delierAction = function(action){
			$http.put('/serveurpcet/index.php/utilisateur/delier/'+action.id+'/'+d.id).success(function(data, status, headers) {
				$scope.loadActionNonlier();
				$scope.loadActionlier();
			});
		};
		
		$scope.ok = function() {
			$modalInstance.close($scope.bool);
		};
		$scope.cancel = function() {
			$modalInstance.dismiss('cancel');
		};
}]).controller('ModalSuppressionUtilisateur',
		[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
			$scope.data=data;
			$scope.textHeaderSupp = "Suppression d'un utilisateur";
			$scope.textBodySupp ="Etes-vous sur de vouloir supprimer l'utilisateur : " + data.login_utilisateur  + " ?";
			$scope.bool = true;
			$scope.ok = function() {
				$modalInstance.close({b:$scope.bool,data:$scope.data});
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).controller('ModalReinitialisationMDPUtilisateur',
		[ '$scope','$http', '$modalInstance','data', function($scope, $http,$modalInstance, data) {
			$scope.data=data;
			$scope.textHeaderSupp = "Réinitialisation d'un mot de passe";
			$scope.textBodySupp ="Etes-vous sur de vouloir réinitialiser le mot de passe de l'utilisateur : " + data.login_utilisateur  + " ?";
			$scope.textBodyWarning ="Après la réinitialisation, le mot de passe aura pour valeur le login de l'utilisateur.    Pensez à prévenir la personne concernée !";
			$scope.bool = true;
			$scope.ok = function() {
				$http({method: 'POST', url: '/serveurpcet/index.php/utilisateur/resetpassword/'+data.id,data:$scope.data});
				$modalInstance.close({b:$scope.bool,data:$scope.data});
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).controller(
				'utilisateurs',
				[
						'$scope',
						'$http',
						'$location',
						'$modal','proConnexion',
						function($scope, $http, $location, $modal,proConnexion) {
							$scope.listeUtilisateurs = [];
							
							
							$scope.associer = function(d) {
								var modaleInstance = $modal.open({
									templateUrl : "partials/modal.associer.utilisateur.html",
									controller : "ModalAssocierUtilisateur",
									    resolve: {
									        d : function () {
									          return d;
									        }
								}});
								modaleInstance.result.then(function() {
								});
							};
							
							
							$scope.modaleResult = false;

							$scope.ComptesUtilisateurRequest = function(a) {

							$http.get('/serveurpcet/index.php/utilisateur/list').success(
									function(data, status, headers) {
										$scope.listeUtilisateurs = data;
									});
							};
							$scope.ComptesUtilisateurRequest();
							$scope.del=false;
							$scope.controlleSuppressionUtilisateur = function(utilisateur) {
								$http({method: 'DELETE', url: '/serveurpcet/index.php/utilisateur/'+utilisateur.id}).
							    success(function(data, status, headers, config) {
							    	$scope.ComptesUtilisateurRequest();
							    	$scope.del=true;
							    });
							
							};

							$scope.open = function(utilisateur) {
								$scope.textHeaderSupp= "TEST";
								var modaleInstance = $modal.open({
											templateUrl : "partials/modal.suppression.html",
											controller : "ModalSuppressionUtilisateur",
											resolve : {
												data : function() {
													return utilisateur;
												}
											}
										});
								modaleInstance.result.then(function(doubledata) {
											$scope.modaleResult = doubledata.b;
											if (doubledata.b == true) {
												$scope.controlleSuppressionUtilisateur(doubledata.data);
											}
										});
							};
							
							$scope.aj=false;
							$scope.mod=false;
							$scope.modifierUtilisateurModal = function(utilisateur) {
								
								var modaleInstanceUtilisateur = $modal
										.open({
											templateUrl : "partials/modal.ajout.utilisateur.html",
											controller : "ModalModifierUtilisateurCtrl",
											resolve : {
												data : function() {
													return utilisateur;
												}
											}
										});
								modaleInstanceUtilisateur.result.then(function(b) {
									$scope.modaleResult = b;
									if (b == true) {
										$scope.ComptesUtilisateurRequest();
										$scope.mod=true;
									}
								});
							};
							
							$scope.ajouterUtilisateurModal = function() {

								var modaleInstanceUtilisateur = $modal
										.open({
											templateUrl : "partials/modal.ajout.utilisateur.html",
											controller : "ModalAjoutUtilisateurCtrl"
										});
								modaleInstanceUtilisateur.result.then(function(b) {
									$scope.modaleResult = b;
									if (b == true) {
										$scope.ComptesUtilisateurRequest();
										$scope.aj=true;
									}
								});
							};
							
							$scope.ajouterUtilisateur = function() {
								$scope.ajout = true;
								$scope.utilisateur.nom_utilisateur = "";
								$scope.utilisateur.login_utilisateur = "";
								$scope.utilisateur.role_utilisateur = "";
								$scope.utilisateur.code_action = "";
								$scope.utilisateur.email = "";

								// $scope.indicateurs.push($scope.insere);
							}
							
							//reinitialiser mot de passe
							$scope.reinitialiserMotdepasse = function(utilisateur) {
								var modaleInstance = $modal.open({
									templateUrl : "partials/modal.suppression.html",
								
									controller : "ModalReinitialisationMDPUtilisateur",
									resolve : {
										data : function() {
											return utilisateur;
										}
									}
								});
								modaleInstance.result.then(function(data) {
									$scope.modaleResult = data.b;
									if (data.b == true) {
										$scope.controlleReinitialiserMotdepasse(data);
									}
								});
							}
							
							$scope.controlleReinitialiserMotdepasse = function(utilisateur) {
								//TODO requete rest reinitialiser mot de passe
							}

							$scope.sort = "name";
							$scope.reverse = false;
							
							$scope.changerTri = function (value){ 
								
								
								if ($scope.sort == value){
								      $scope.reverse = !$scope.reverse;
								      return;
							 }

								    $scope.sort = value;
								    $scope.reverse = false;
								    
								    
							};
							$scope.donneeUtilisateur = {};

							$scope.donneeUtilisateur = {};
							proConnexion.getDonneeUtilisateur().then(function(data){
								$scope.donneeUtilisateur = data;
							});

							$scope.getRoleUtilisateur = function(role_utilisateur) {
							  switch (role_utilisateur) {
							  	case "1":
							  		return "Chef de projet";
							  	case "2":
							  		return "Responsable action";
							  	case "3":
							  		return "Visiteur";
							  	default:
							  		return "";
							  }
							};
							
						} ]);
