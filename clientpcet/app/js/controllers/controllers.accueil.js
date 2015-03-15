'use strict';

/* Controllers */

angular.module('myApp.controllers.accueil', []).controller('ModalSupDoc',
		[ '$scope', '$modalInstance','data', function($scope, $modalInstance, data) {
			$scope.data=data;
			$scope.textHeaderSupp = "Suppression d'un document";
			$scope.textBodySupp ="Etes-vous sur de vouloir supprimer le document "+ data.nom_document + " ?";
			$scope.bool = true;
			$scope.ok = function() {
				$modalInstance.close({b:$scope.bool,data:$scope.data});
			};
			$scope.cancel = function() {
				$modalInstance.dismiss('cancel');
			};

} ]).controller("accueil", [ '$scope','$modal','$http','proConnexion', function($scope,$modal, $http,proConnexion) {
	$scope.documents=[];
	$scope.getDocumentList = function(){
		$http.get('/serveurpcet/index.php/document/list').success(function(data, status, headers) {
			$scope.documents = data;
			console.log("We have " +$scope.documents.length);
		});
	};
	
	$scope.getDocumentList();
	
	$scope.controlleSupDoc = function(document){
		$scope.del=false;
		$http({method: 'DELETE', url: '/serveurpcet/index.php/document/delete/'+document.id}).
	    success(function(data, status, headers, config) {
	    	$scope.getDocumentList();
	    	$scope.del=true;
	    });
	};
	
	
	$scope.supDoc = function(document) {
		$scope.textHeaderSupp= "TEST";
		var modaleInstance = $modal.open({
					templateUrl : "partials/modal.suppression.html",
					controller : "ModalSupDoc",
					resolve : {
						data : function() {
							return document;
						}
					}
				});
		modaleInstance.result.then(function(doubledata) {
					$scope.modaleResult = doubledata.b;
					if (doubledata.b == true) {
						$scope.controlleSupDoc(doubledata.data);
					}
				});
	};	
	
} ]);