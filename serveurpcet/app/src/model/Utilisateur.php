<?php
use RedBean_Facade as R;

class Utilisateur {
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "utilisateur";
	
	/*Création d'un utilisateur dans la BD*/
	public static function creerUtilisateur($login,$nom,$prenom,$passwordMd5,$role,$organisation,$email,$telInterne,$telStandard){
// 		if($role != "chef de projet" && $role!="responsable action" && $role!="visiteur"){
// 			return null;
// 		}
		$utilisateur = R::dispense(Utilisateur::$nameTable);
		$utilisateur->login_utilisateur= $login;
		$utilisateur->prenom_utilisateur = $prenom;
		$utilisateur->nom_utilisateur = $nom;
		$utilisateur->mot_de_passe=$passwordMd5;
		$utilisateur->role_utilisateur=$role;
		$utilisateur->organisation = $organisation;
		$utilisateur->email = $email;
		$utilisateur->tel_interne=$telInterne;
		$utilisateur->tel_standard=$telStandard;
		$id = R::store($utilisateur);
		return $utilisateur;
	}
	
	/*Verrification de l'identité d'un utilisateur*/
	public static function authentification($login,$passwordMd5){
		$utilisateur = R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur!=null && $utilisateur->mot_de_passe === $passwordMd5){
			return $utilisateur;
		}
		return null;		
	}
	
	/*Récupération de tous les utilisateurs de la plateforme*/
	public static function getAllUtilisateur(){
		return R::findAll(Utilisateur::$nameTable);
	}
	
	/*Fonctions pour la récupération informations des utilisateurs*/
	public static function getUtilisateur($login){
		return R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
	}
	
	public static function recupererUtilisateur($idUtilisateur){
		$utilisateur = R::findOne(Utilisateur::$nameTable, " id = ? ", array($idUtilisateur));
		return $utilisateur;
	}
	
	/*Verrification que l'utilisateur est un chef de projet*/
	public static function estChefDeProjet($login){
		$utilisateur=R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur->role_utilisateur==1){
			return true;
		}
		return false;
	}
	
	public static function estChefDeProjetParUtilisateur($utilisateur){
		if($utilisateur->role_utilisateur==1){
			return true;
		}
		return false;
	}
	
	public static function estChefDeProjetOuResponsable($login){
		$utilisateur=R::findOne(Utilisateur::$nameTable, ' login_utilisateur = ?  ', array($login));
		if($utilisateur->role_utilisateur==1 || $utilisateur->role_utilisateur==2){
			return true;
		}
		return false;
	}
	
	/*Association d'un commentaire à un utilisateur*/
	public static function addCrAction($utilisateur,$crAction){
		$utilisateur->ownCraction[]=$crAction;
		R::store($utilisateur);
		return;
	}
	
	/*Suppression d'un utilisateur*/
	public static function deleteUtilisateur($utilisateur){
		R::trash($utilisateur);
		return;
	}
	
}
?>