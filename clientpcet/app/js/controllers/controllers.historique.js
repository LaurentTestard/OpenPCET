'use strict';

/* Controllers */

angular
.module('myApp.controllers.historique', [])
.controller('historique',['$scope','$http','$modal','$filter','$routeParams','proConnexion',function($scope, $http, $modal, $filter,  $routeParams, proConnexion) {
							
	$scope.historique = [];


		$scope.getHistoriqueList = function(){	
			$http.get('/serveurpcet/index.php/pcaet/actions/historique/'+$routeParams.id).success(function(data, status, headers) {
				$scope.historiques = data;
			});
		};
		
		$scope.getHistoriqueList();
		
		$scope.controlleSuppressionHistorique = function(historique){
			$scope.del=false;
			$http({method: 'DELETE', url: '/serveurpcet/index.php/pcaet/actions/historique/delete/'+historique.id}).
		    success(function(data, status, headers, config) {
		    	$scope.getHistoriqueList();
		    	$scope.del=true;
		    });
		};
		
		
		$scope.supprimerHistorique = function(historique) {
			$scope.textHeaderSupp= "TEST";
			var modaleInstance = $modal.open({
						templateUrl : "partials/modal.suppression.html",
						controller : "ModalSuppressionHistorique",
						resolve : {
							data : function() {
								return historique;
							}
						}
					});
			modaleInstance.result.then(function(doubledata) {
						$scope.modaleResult = doubledata.b;
						if (doubledata.b == true) {
							$scope.controlleSuppressionHistorique(doubledata.data);
						}
					});
		};	
		
	} ]).controller('ModalSuppressionHistorique',
			[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
				$scope.data=data;
				$scope.textHeaderSupp = "Suppression d'une historique";
				$scope.textBodySupp ="Etes-vous sur de vouloir supprimer l'historique ?";
				$scope.bool = true;
				$scope.ok = function() {
					$modalInstance.close({b:$scope.bool,data:$scope.data});
				};
				$scope.cancel = function() {
					$modalInstance.dismiss('cancel');
				};

	} ]);