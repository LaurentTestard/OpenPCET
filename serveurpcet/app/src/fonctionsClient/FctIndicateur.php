<?php
use RedBean_Facade as R;

class FctIndicateur {
	//TODO : a tester
	/* tous les indicateurs d'un objectifEnjeu*/
	public static function listerIndicateursByObjectif($nomObjectifEnjeu){
		$objectif = ObjectifEnjeu::getObjectifEnjeu($nomObjectifEnjeu);
		$indicateurs = $objectif->ownIndicateur;
		
		$indicateursAEncoder = array();
		
		foreach ($indicateurs as $indicateur){
			//Remplissage du tableau de l'objectif courante pour encodage
			$indicateurAEncoder=array();
			$indicateurAEncoder['id']=$indicateur->id;
			$indicateurAEncoder['nom_indicateur']=$indicateur->nom_indicateur;
			$indicateurAEncoder['valeur_actuelle']=$indicateur->valeur_actuelle;
			$indicateurAEncoder['valeur_objectif']=$indicateur->valeur_objectif;
			$indicateurAEncoder['description_indicateur']=$indicateur->description_indicateur;
			$objectifNom = ObjectifEnjeu::getNomObjectifEnjeu($indicateur->objectifenjeu_id);
			$indicateurAEncoder['nom_objectif']=$objectifNom;
			$indicateursAEncoder[]=$indicateurAEncoder;
		}
		
		return $indicateursAEncoder;

	}
	
	public static function ListerIndicateurAction($idaction) {
		$objectifs = ObjectifEnjeu::getObjectifsEnjeuxByIdAction($idaction);
		$indicateurs = array();
		foreach($objectifs as $objectif){
			$indicateur = array();
			$indicateur = FctIndicateur::listerIndicateursByObjectif($objectif->nom_objectif_enjeu);
			$indicateurs[]=$indicateur;
		}
		return $indicateurs;
		
	}
	
	
			
	public static function ajouterIndicateur($tabParametres, $login) {
		$objectif = ObjectifEnjeu::getObjectifEnjeu($tabParametres['nom_objectif_enjeu']);
		$action = Action::getActionById($tabParametres['code_action']);
		
		
		if($objectif == null){
			$objectif = ObjectifEnjeu::creerObjectifEnjeu($tabParametres['nom_objectif_enjeu']);
			Action::addObjectifenjeu($action, $objectif);
		}
	
		$nomIndicateur = $tabParametres['nom_indicateur'];
		$valeurActuelle = $tabParametres['valeur_actuelle'];
		$valeurObjectif = $tabParametres['valeur_objectif'];
		$descriptionIndicateur = $tabParametres['description_indicateur'];
		$indicateur = Indicateur::creerIndicateurAction($nomIndicateur, $valeurActuelle, $valeurObjectif, $descriptionIndicateur);
		
		if($indicateur != null) {
			ObjectifEnjeu::addIndicateur($objectif, $indicateur);

			
			$dateModif = date('Y-m-d H:i:s');
			$typeModif = "Ajout d'un indicateur";
			$descriptionModification = "Nouvel indicateur, Nom : " .$nomIndicateur ." Valeur : " .$valeurActuelle;
			Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $descriptionModification);

			return;
		}
		else {
			return null;
		}
	}

	public static function supprimerIndicateur($idIndic, $login){
		$indicateur = Indicateur::recupererIndicateur($idIndic);
		if ($indicateur == null)
			return null;
		$action = $indicateur->objectifenjeu->action;

		
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Supression d'un indicateur";
		$description = "Suppression de l'indicateur : " .$indicateur->nom_indicateur;
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		
		Indicateur::supprimerIndicateur($idIndic);

		return $action ;
	}

	public static function modifierIndicateur($tabParametres, $login){
		$tabParamChanges = FctIndicateur::verifModifIndicateur($tabParametres);
		$indicateur = Indicateur::recupererIndicateur($tabParametres['id']);
		$action = $indicateur->objectifenjeu->action;
		if($action == null)
			return null;
		$utilisateur = Utilisateur::getUtilisateur($login);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		$indicateur = Indicateur::modifierIndicateur($tabParamChanges);
		if($indicateur==null){
			return null;
		}
		if ($tabParamChanges['descriptionModification'] != null) {
			$dateModif = date('Y-m-d H:i:s');
			$typeModif = "Modification d'un indicateur";
			Log::creerLog($dateModif, $typeModif, $login, $indicateur->objectifenjeu->action->code_action, $tabParamChanges['descriptionModification']);
		}
		return $indicateur;
	}

	// Verification des modifications sur l'indicateur
	public static function verifModifIndicateur($tabParametres){
		$indicateur = Indicateur::recupererIndicateur($tabParametres['id']);
		$tabParamChanges=array();
		$tabParamChanges['descriptionModification'] = null;
		if ($indicateur != null)
			$tabParamChanges['id']=$tabParametres['id'];
		else
			return null;
		
		if($tabParametres['nom_indicateur'] != $indicateur->nom_indicateur){
			$tabParamChanges['nom_indicateur'] = $tabParametres['nom_indicateur'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancien nom : ".$indicateur->nom_indicateur ." ";
		}
		else {
			$tabParamChanges['nom_indicateur'] = $indicateur->nom_indicateur;
		}
	
		if($tabParametres['valeur_actuelle'] != $indicateur->valeur_actuelle){
			$tabParamChanges['valeur_actuelle'] = $tabParametres['valeur_actuelle'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne valeur : ".$indicateur->valeur_actuelle ." ";
		}
		else {
			$tabParamChanges['valeur_actuelle'] = $indicateur->valeur_actuelle;
		}
	
		if($tabParametres['valeur_objectif'] != $indicateur->valeur_objectif){
			$tabParamChanges['valeur_objectif'] = $tabParametres['valeur_objectif'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne valeur de l'objectif : ".$indicateur->valeur_objectif ." ";
		}
		else {
			$tabParamChanges['valeur_objectif'] = $indicateur->valeur_objectif;
		}
	
		if($tabParametres['description_indicateur'] != $indicateur->description_indicateur){
			$tabParamChanges['description_indicateur'] = $tabParametres['description_indicateur'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne description : ".$indicateur->description_indicateur ." ";
		}
		else {
			$tabParamChanges['description_indicateur'] = $indicateur->description_indicateur;
		}
	
			
		return $tabParamChanges;
	}
	
	
	// -------------------------------------
	
	public static function formeIndicateurArray($indicateur){
		$indicateurTab = array();
		$indicateurTab['id']=$indicateur->id;
		$indicateurTab['nom_indicateur']=$indicateur->nom_indicateur;
		$indicateurTab['valeur_actuelle']=$indicateur->valeur_actuelle;
		$indicateurTab['$valeurObjectif']=$indicateur->valeur_objectif;
		$indicateurTab['description_indicateur']=$indicateur->description_indicateur;
		return $indicateurTab;
	}
}
?>
