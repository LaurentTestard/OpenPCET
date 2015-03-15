<?php
use RedBean_Facade as R;

class FctCompte {
	
	/*Fonction pour le changement de mot de passe d'un utilisateur*/
	public static function changerMotDePasse($utilisateur,$ancienMotDePasse,$nouveauMotDePasse) {
		if($utilisateur->mot_de_passe == md5($ancienMotDePasse)){
			$utilisateur->mot_de_passe = md5($nouveauMotDePasse);
		}
		R::store($utilisateur);
	}
	
	/*Modification des information d'un utilisateur dans la BD*/
	public static function miseAJourUtilisateur($user,$infoutilisateur) {
		$user->nom_utilisateur = $infoutilisateur->nom_utilisateur;
		$user->prenom_utilisateur = $infoutilisateur->prenom_utilisateur;
		$user->login_utilisateur = $infoutilisateur->login_utilisateur;
		$user->email = $infoutilisateur->email;
		$user->organisation = $infoutilisateur->organisation;
		$user->tel_interne = $infoutilisateur->tel_interne;
		$user->tel_standard = $infoutilisateur->tel_standard;
		/*Sauvegarde des modifications dans la BD*/
		R::store($user);
		return $user;
	}
	
	
}
?>
