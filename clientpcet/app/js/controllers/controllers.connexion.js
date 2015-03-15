'use strict';

/* Controllers */
// get /compte/info
/**
 *  email: "anne.foret@gmail.com"
	login_utilisateur: "Foreta"
	nom_utilisateur: "Anne"
	prenom_utilisateur: "Foret"
	role_utilisateur: "chef de projet"
	tel_interne: "6532"
	tel_standard: "0474568790"
 */


angular.module('myApp.controllers.connexion', [])
.provider("proConnexion",function(){	
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
.controller("connexion", [ '$scope', '$http','proConnexion', function($scope, $http,proConnexion) {
	$scope.connexion = "";
	$scope.login = "";
	$scope.password = "";
	$('#mot_de_passe').checkAndTriggerAutoFillEvent();
	// Na pas regarder en dessous ( ce n'est pas tres joli )
	function make_base_auth(user, password) {
		  var tok = user + ':' + password;
		  var hash = btoa(tok);
		  return "Basic " + hash;
		}
	
	$scope.seConnecter = function(login,password) {
	    $http.defaults.headers.common['Authorization'] = make_base_auth(login,password);
		$http.get('/serveurpcet/index.php/compte/authentification').success(function(data, status, headers) {
			//authToken = headers('A-Token');
			$scope.connexion = data.success;
			location.reload();
		}).error(function(data, status, headers) {
      if (status == 401) { // Unauthorized
        $scope.connexion = data.success;
      }
		});
	};
} ]).controller("infoConnexion", [ '$scope', '$http','proConnexion', function($scope, $http,proConnexion) {
	$scope.donneeUtilisateur = {};
	
	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.donneeUtilisateur = data;
	});
	
	$scope.deconnexion = function(){
		$http.get('/serveurpcet/index.php/compte/deconnexion').success(function(data, status, headers) {
			location.href = "index.html";
		});
	};

}]);