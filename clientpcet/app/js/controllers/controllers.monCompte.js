'use strict';

/* Controllers */

angular.module('myApp.controllers.monCompte', []).controller('monCompte', [ '$scope','$http','proConnexion' ,function($scope, $http,proConnexion) {
	

	
	$scope.utilisateur = {};
	proConnexion.getDonneeUtilisateur().then(function(data){
		$scope.utilisateur = data;
	});
	
	$scope.enregistrerInfoPersonnelle = function(){
		$http.post('/serveurpcet/index.php/compte/miseajour',$scope.utilisateur).success(function(data, status, headers) {
			$scope.valide = data.valide;
			location.reload();
		});
	};  
	
	
	$scope.controleChampsMonCompte = function(){
		if($scope.form_utilisateur.$valid){
			 $scope.enregistrerInfoPersonnelle();			
		}else {
			 $scope.message_erreur_monCompte = "Veuillez complêter toutes les informations";
		 }
	};
	
	$scope.verification = function(){			
		if($scope.form_changer_MDP.mdp_nouveau.$valid && $scope.form_changer_MDP.mdp_confirmation.$valid && $scope.form_changer_MDP.mdp_actuel.$valid){			
			if($scope.motdepasse_nouveau != $scope.motdepasse_confirmation){			
				$scope.message_erreur = "Les mots de passe doit être identiques";
				document.getElementById('mdp_confirmation').style.cssText='border:2px solid red';
				document.getElementById('mdp_nouveau').style.cssText='border:2px solid red';
				document.getElementById('btn_modifierMDP').setAttribute('disabled', true);
			}else {
				document.getElementById('mdp_confirmation').style.cssText='';
				document.getElementById('mdp_nouveau').style.cssText='';
				document.getElementById('btn_modifierMDP').removeAttribute('disabled');
				$scope.message_erreur="";
				
			}
		}
		else {
			document.getElementById('mdp_confirmation').style.cssText='';
			document.getElementById('mdp_nouveau').style.cssText='';
			document.getElementById('btn_modifierMDP').setAttribute('disabled', true);
			$scope.message_erreur="";
		}
	
			
	};
	
	$scope.enregistrerMotDePasse = function(){
		 if($scope.form_changer_MDP.$valid){
			 
			 
			 
			 $http.post('/serveurpcet/index.php/compte/password',{
				 motdepasse_actuel:$scope.motdepasse_actuel,
				 motdepasse_nouveau:$scope.motdepasse_confirmation
			 }).success(function(data, status, headers) {
				 	alert("Mot de passe sauvegard� !");
					$scope.valide = data.valide;
			});
			 
			 
		 }
		
		
	
		 
	};
	
} ]);
