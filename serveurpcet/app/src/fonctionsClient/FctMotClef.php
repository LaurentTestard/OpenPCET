<?php
use RedBean_Facade as R;
class FctMotClef {
	
	/*Cr�ation de mot clef et association avec une action*/
	public static function ajoutNouveauMotClefOuLie($idAction,$motClef,$loginUtilisateur){

		$action = Action::getActionById($idAction);
		$utilisateur = Utilisateur::getUtilisateur($loginUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		$motclefBean = MotClef::chercherOneMotClef($motClef);
		if($motclefBean == null){
			$motclefBean = MotClef::creerMotClef($motClef);
		}
		Action::addMotClef($action, $motclefBean);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison d'un mot clef à l'action";
		$description = "Le mot clef \"".$motclefBean->nom_mot_clef."\" a été lié à l'action.";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $action->code_action, $description);
	}
	
	/*Associer mot clef � une action*/
	public static function lierActionMotClef($idAction,$idMotClef, $loginUtilisateur){
		
		$action = Action::getActionById($idAction);
		$utilisateur = Utilisateur::getUtilisateur($loginUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		$motClef = MotClef::recupererMotClef($idMotClef);
		Action::addMotClef($action, $motClef);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison d'un mot clef à l'action";
		$description = "Le mot clef \"".$motClef->nom_mot_clef."\" a été lié à l'action.";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $action->code_action, $description);
	}
	
	/*Suppression du lien entre une action et un mot clef*/
	public static function delierActionMotClef($idAction,$idMotClef,$loginUtilisateur){
		
		$action = Action::getActionById($idAction);
		$utilisateur = Utilisateur::getUtilisateur($loginUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		$motClef = MotClef::recupererMotClef($idMotClef);
		$nomMotClef = $motClef->nom_mot_clef;
		Action::deleteMotClef($action, $motClef);
		if(count($motClef->sharedAction)==0){
			MotClef::supprimerMotClef($motClef->id);
			$dateModif = date('Y-m-d H:i:s');
			$typeModif = "Déliaison d'un mot clef de l'action";
			$description = "Le mot clef \"".$nomMotClef."\" a été délié de l'action.";
			Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $action->code_action, $description);
		}
	}
	
	/*R�cup�ration des mots clefs d'une action*/
	public static function motClefLier($idAction){
		
		$action = Action::getActionById($idAction);
		$motclefs = array();
		foreach($action->sharedMotclef as $motclef){
			$motclefs[] = FctMotClef::formeMotClefArray($motclef);
		}
		return $motclefs;
	}
	
	/*R�cup�ration des mots clefs qui qui ne sont pas li�s � une action*/
	public static function motClefPasLier($idAction){
		$action = Action::getActionById($idAction);
		$motclefs = MotClef::getMotClefNonLie($action);
		$motClefRes = array();
		foreach($motclefs as $motclef){
			$motClefRes[] = FctMotClef::formeMotClefArray($motclef);
		}
		return $motClefRes;
	}

	//Ajout d'un nouveau motclef
	public static function ajoutNouveauMotClef($nomMotClef) {
		return MotClef::creerMotClef($nomMotClef);
	}
	
	//Afficher tous les motsclefs lies a l'action 
	public static function recupererMotsClefsLies($idAction) {
		$action = Action::getActionById($idAction);
		$motsclefs=$action->sharedMotclef;
		$motsclefsRep=array();
		foreach($motsclefs as $motclef){
			$motclefRep=array();
			$motclefRep['id']=$motclef->id;
			$motclefRep['nom_action']=$motclef->nom_mot_clef;
			$motsclefsRep[]=$motclefRep;
		}
		return $motsclefsRep;
	}
	
	//Afficher tous les motsclefs non lies a l'action
	public static function recupererMotsClefsNonLies($idAction) {
		$action = Action::getActionById($idAction);
		$motsclefs = MotClef::getMotsClefsNonLiesAction($action);
		$motsclefsRep=array();
		foreach($motsclefs as $motclef){
			$motclefRep=array();
			$motclefRep['id']=$motclef->id;
			$motclefRep['nom_action']=$motclef->nom_mot_clef;
			$motsclefsRep[]=$motclefRep;
		}
		return $motsclefsRep;		 
	}
	
	//Afficher tous les motsclefs
	public static function recupererMotsClefs() {
		$motsclefs = MotClef::getMotsClefs();
		$motsclefsRep=array();
		foreach($motsclefs as $motclef){
			$motclefRep=array();
			$motclefRep=FctMotClef::formeMotClefArray($motclef);
			$motsclefsRep[]=$motclefRep;
		}
		return $motsclefsRep;
	}
	
	
	//Delie un motclef d'une action
	public static function delieMotClefAction($idAction,$idMotClef) {
		$action = Action::getActionById($idAction);
		$motclef=MotClef::recupererMotClef($idMotClef);
		Action::deleteMotClef($action, $motclef);
		return array('success' => 'ok');
	}
	
	//Lie un motclef a une action
	public static function lieMotClefAction($idAction,$idMotClef) {
		$action = Action::getActionById($idAction);
		$motclef=MotClef::recupererMotClef($idMotClef);
		MotClef::lierMotClefAction($motclef, $action);
		return array('success' => 'ok');
	}
	
	/*Repr�sentation des informations d'un mot clef sous forme de tableau*/
	public static function formeMotClefArray($motclef){
		$motClefTab = array();
		$motClefTab['id']=$motclef->id;
		$motClefTab['nom_mot_clef']=$motclef->nom_mot_clef;
		return $motClefTab;
	}
}
?>