<?php
use RedBean_Facade as R;

class FctUtilisateur {
	/*Récupération de tous le utilisateurs de la plateforme*/
	public static function getAllUtilisateur($login) {
		
		if(!Utilisateur::estChefDeProjet($login)){
			return null;
		}
		
		$utilisateurs = Utilisateur::getAllUtilisateur();
		$users = array();
		
		foreach($utilisateurs as $utilisateur){
			$user=array();
			$user['id']=$utilisateur->id;
			$user['login_utilisateur']=$utilisateur->login_utilisateur;
			$user['nom_utilisateur']=$utilisateur->nom_utilisateur;
			$user['prenom_utilisateur']=$utilisateur->prenom_utilisateur;
			$user['role_utilisateur']=$utilisateur->role_utilisateur;
			$user['organisation'] = $utilisateur->organisation;
			$user['email']=$utilisateur->email;
			$users[]=$user;
		}
		return $users;
	}
	
	/*Fonctions pour la récupération des informations d'un utilisateur*/
	public static function getUtilisateur($login) {
		$utilisateur = Utilisateur::getUtilisateur($login);
		return FctUtilisateur::formeUtilisateurArray($utilisateur);
	}
	
	public static function getUtilisateurParId($id) {
		$utilisateur = Utilisateur::recupererUtilisateur($id);
		return FctUtilisateur::formeUtilisateurArray($utilisateur);
	}
	
	/*Représentation des informations d'un utilisateur sous forme de tableau*/
	public static function formeUtilisateurArray($utilisateur){
		$user=array();
		$user['id']=$utilisateur->id;
		$user['login_utilisateur']=$utilisateur->login_utilisateur;
		$user['nom_utilisateur']=$utilisateur->nom_utilisateur;
		$user['prenom_utilisateur']=$utilisateur->prenom_utilisateur;
		$user['role_utilisateur']=$utilisateur->role_utilisateur;
		$user['email']=$utilisateur->email;
		$user['organisation']=$utilisateur->organisation;
		$user['tel_interne']=$utilisateur->tel_interne;
		$user['tel_standard']=$utilisateur->tel_standard;
		return $user;
	}
	
	/*Suppression d'un utilisateur*/
	public static function deleteUtilisateur($login,$id) {
		if(!Utilisateur::estChefDeProjet($login)){
			return null;
		}
		$utilisateur = Utilisateur::recupererUtilisateur($id);
		Utilisateur::deleteUtilisateur($utilisateur);
		return true;
	}
	
	/*Ajout d'un nouvel utilisateur*/
	public static function ajoutUtilisateur($utilisateur) {
		/*Verification que le mail et le login sont bien renseignés*/
		if(isset($utilisateur->email) && $utilisateur->login_utilisateur!=""){
			$utilisateur = Utilisateur::creerUtilisateur($utilisateur->login_utilisateur, $utilisateur->nom_utilisateur, $utilisateur->prenom_utilisateur, md5($utilisateur->mot_de_passe), $utilisateur->role_utilisateur, $utilisateur->organisation, $utilisateur->email, $utilisateur->tel_interne, $utilisateur->tel_standard);
			return FctUtilisateur::getUtilisateur($utilisateur->login_utilisateur);
		}
		return null;
	}
	
	/*Modification des informations d'un utilisateur*/
	public static function miseAJourUtilisateur($utilisateur){
		$user = Utilisateur::recupererUtilisateur($utilisateur->id);
		$user->nom_utilisateur = $utilisateur->nom_utilisateur;
		$user->prenom_utilisateur = $utilisateur->prenom_utilisateur;
		$user->role_utilisateur = $utilisateur->role_utilisateur;
		$user->organisation = $utilisateur->organisation;
		$user->email = $utilisateur->email;
		$user->tel_interne = $utilisateur->tel_interne;
		$user->tel_standard = $utilisateur->tel_standard;
		R::store($user);
		return $user;
	}
	
	/*Associer un utilisateur à une action*/
	public static function lieUtilisateurAction($utilisateurId,$actionId) {
		$utilisateur = Utilisateur::recupererUtilisateur($utilisateurId);
		$action = Action::getActionById($actionId);
		Action::addUtilisateur($action, $utilisateur);
		return;
	}
	
	/*Suppressio du lien entre une action et un utilisateur*/
	public static function delieUtilisateurAction($utilisateurId,$actionId) {
		$utilisateur = Utilisateur::recupererUtilisateur($utilisateurId);
		$action = Action::getActionById($actionId);
		Action::deleteUtilisateur($action, $utilisateur);
		return;
	}
	
	/*Récupération des actions qui ne sont pas associées à un utilisateur*/
	public static function getAllActionNonLie($idUtilisateur){
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		$actions = Action::getParUtilisateurNonLie($utilisateur);
		$actionsRep=array();
		/*Représentation des informations sous forme de tableau*/
		foreach($actions as $action){
			$actionRep=array();
			$actionRep['id']=$action->id;
			$actionRep['code_action']=$action->code_action;
			$actionRep['nom_action']=$action->nom_action;
			$actionsRep[]=$actionRep;
		}
		return $actionsRep;
	}
	
	/*Récupération des actions qui sont associées à un utilisateur*/
	public static function getAllActionLie($idUtilisateur){
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		$actions = $utilisateur->sharedAction;
		$actionsRep=array();
		foreach($actions as $action){
			$actionRep=array();
			$actionRep['id']=$action->id;
			$actionRep['code_action']=$action->code_action;
			$actionRep['nom_action']=$action->nom_action;
			$actionsRep[]=$actionRep;
		}
		return $actionsRep;
	}
	
	/*Fonction pour réinitialiser le mot de passe de l'utilisateur*/
	public static function resetPassword($idUtilisateur){
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		if($utilisateur!=null){
			$utilisateur->mot_de_passe = md5($utilisateur->login_utilisateur);
			R::store($utilisateur);
		}
		return $utilisateur;
	}
}
?>
