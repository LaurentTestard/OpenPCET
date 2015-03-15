angular.module('myApp.controllers.impression', ['ui.bootstrap'])
.controller("impression", [ '$scope','$http','proConnexion', function($scope,$http,proConnexion) {  
	
	$scope.checkboxfiches={};
	
	$scope.fiches=[];
	$scope.getficheList = function(){
		$http.get('/serveurpcet/index.php/fiche/list').success(function(data, status, headers) {
			$scope.fiches = data;
			console.log("Nous avons " +$scope.fiches.length);
		});
	};
	
	$scope.getficheList();
	
	$scope.dlfiche = function(fiche){
		$http.get('/serveurpcet/index.php/fiche/'+fiche.id).success(function(data, status, headers) {
		
		});
	};
	
	$scope.imprimerfiche=function(){
		$http.post('/serveurpcet/index.php/fiche/imprimer/',$scope.checkboxfiches).success(function(data, status, headers, config) {
		});
	};
	
	$scope.toutimprimer=function(){
		$http.post('/serveurpcet/index.php/fiche/toutimprimer/').success(function(data, status, headers, config) {
		});
	};
	
	$scope.donneeUtilisateur={};
	proConnexion.getDonneeUtilisateur().then(function(data){
  		$scope.donneeUtilisateur = data;
  	});

}]);  