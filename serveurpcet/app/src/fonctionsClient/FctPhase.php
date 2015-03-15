<?php
use RedBean_Facade as R;
use Symfony\Component\Validator\Constraints\Date;

class FctPhase {
	/* toutes les phases d'une fiche action*/
	public static function listerPhasesAction($codeAction){
		$actions = Action::getAction($codeAction);
		$phases = $actions->ownPhase;
		
		$phasesAEncoder = array();
		
		foreach ($phases as $phase){
			//Remplissage du tableau de l'action courante pour encodage
			$phaseAEncoder=array();
			$phaseAEncoder['id']=$phase->id;
			$phaseAEncoder['nom_phase']=$phase->nom_phase;
			$phaseAEncoder['date_debut_prevue']=$phase->date_debut_prevue;
			$phaseAEncoder['date_fin_prevue']=$phase->date_fin_prevue;
			$phaseAEncoder['date_debut_reelle']=$phase->date_debut_reelle;
			$phaseAEncoder['date_fin_reelle']=$phase->date_fin_reelle;
			$phaseAEncoder['description_phase']=$phase->description_phase;
			$phaseAEncoder['ordre_phase']=$phase->ordre_phase;
			$phaseAEncoder['services_porteurs']=$phase->services_porteurs;
			$phaseAEncoder['avancement_phase']=$phase->avancement_phase;
			$phaseAEncoder['commentaires_phase']=$phase->commentaires_phase;
			$phasesAEncoder[]=$phaseAEncoder;
		}
		
		return $phasesAEncoder;
	}
	
	/*Suppression d'une phase actuelle*/
	public static function supprimerPhase($idPhase, $idUtilisateur, $codeAction, $ordrePhase, $dateModif, $typeModif){
		$phase = Phase::recupererPhase($idPhase);
		if ($phase == null)
			return null;
		$action = $phase->action;
		if($action == null)
			return null;
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		/*Verification du droit de l'utilisateur*/
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		if(strcmp($action->code_action, $codeAction) != 0)
			return null;
		/*Suppression de la phase*/
		Phase::supprimerPhase($idPhase);
		/*Sauvegarde de la trace de la suppression*/
		Log::creerLog($dateModif,$typeModif, $utilisateur->login_utilisateur, $action->code_action, $typeModif ." - Nom de la phase : " .$phase->nom_phase);
		/*Gestion de l'ordre des phases restantes*/
		foreach ($action->ownPhase as $phaseAction){
			$ordrePhaseAction = $phaseAction->ordre_phase;
			if($ordrePhaseAction > $ordrePhase)
				Phase::decrementerOrdrePhase($phaseAction);
		}
		return $action;
	}

	/*Affichage des informations d'une phase actuelle*/
	public static function visualiserPhase($idAction, $idPhase){
		$phase = Phase::recupererPhase($idPhase);
		if($phase != null){
			if($phase->action->id == $idAction){
				$detailsPhase = array();
				$detailsPhase['id'] = $phase->id;
				$detailsPhase['nom_phase'] = $phase->nom_phase;
				$detailsPhase['commentaires_phase'] = $phase->commentaires_phase;
				$detailsPhase['date_debut_prevue'] = $phase->date_debut_prevue;
				$detailsPhase['date_fin_prevue'] = $phase->date_fin_prevue;
				$detailsPhase['date_debut_reelle'] = $phase->date_debut_reelle;
				$detailsPhase['date_fin_reelle'] = $phase->date_fin_reelle;
				$detailsPhase['description_phase'] = $phase->description_phase;
				$detailsPhase['ordre_phase'] = $phase->ordre_phase;
				$detailsPhase['services_porteurs'] = $phase->services_porteurs;
				$detailsPhase['ponderation_phase'] = $phase->ponderation_phase;
				$detailsPhase['avancement_phase'] = $phase->avancement_phase;
				return $detailsPhase;
			}
			return null;
		}
		return null;
	}
	
	/*Ajout d'une nouvelle phase actuelle*/
	public static function ajouterPhase($tabParametres, $login){
		$action = Action::getActionById($tabParametres['code_action']);
		if($action == null)
			return null;
		
		$utilisateur = Utilisateur::getUtilisateur($login);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		

		//Decapsulation des donnees a inserer
		$nomPhase = $tabParametres['nom_phase'];
		$commentairesPhase = $tabParametres['commentaires_phase'];
		$descriptionPhase = $tabParametres['description_phase'];
		$ordrePhase = $tabParametres['ordre_phase'];
		$servicesPorteurs = $tabParametres['services_porteurs'];
		
		if(isset($tabParametres['ponderation_phase']))
			$ponderationPhase = $tabParametres['ponderation_phase'];
		else
			$ponderationPhase = null;
		
		$avancementPhase = $tabParametres['avancement_phase'];
		
		
		//Manipulation des dates
		if(isset($tabParametres['date_debut_prevue'])) {
			$dateDebutPhase = $tabParametres['date_debut_prevue'];
		}
		else {
			$dateDebutPhase = null;
		}
		
		if(isset($tabParametres['date_fin_prevue'])) {
			$dateFinPhase= $tabParametres['date_fin_prevue'];
		}
		else {
			$dateFinPhase = null;
		}
		
		/*Création de la phase dans la BD grâce à la fonction creerPhase de la classe Phase*/
		$phase = Phase::creerPhase($nomPhase, $commentairesPhase, 
									$dateDebutPhase, $dateFinPhase, null, null, 
									$descriptionPhase, $ordrePhase, $servicesPorteurs, 
									$ponderationPhase, $avancementPhase);
	    
		if($phase == null)
			return null;
		//Gestion ordre
		/*foreach ($action->ownPhase as $phaseAction) {
			$ordrePhaseAction = $phaseAction->ordre_phase;
			if($ordrePhaseAction >= $ordrePhase)
				Phase::incrementerOrdrePhase($phaseAction);
		}*/
		/*Association de la phase à l'action concernée*/
		Action::addPhase($action, $phase);
		
		/*Sauvegarde de la trace d'ajout de phase*/
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Ajout d'une phase";
		$description = "Ajout d'une phase : " .$phase->nom_phase;
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		
		return;
	}
	
	/*Modification d'une phase actuelle*/
	public static function modifierPhaseAction($tabParametres, $login){
		/*Verification des informations à mettre à jour*/	
		$tabParamChanges = FctPhase::verifModifPhase($tabParametres);
		
		/*Récupération de la phase à mettre à jour*/
		$phase = Phase::recupererPhase($tabParametres['id']);
		if($phase == null)
			return null;
		
		/*Verification du droit de l'utilisateur*/
		$utilisateur = Utilisateur::getUtilisateur($login);
		if(!Action::aDroitDeModification($phase->action,$utilisateur)){
			return null;
		}
		
		/*Mise à jour des informations*/
		$phase = Phase::modifierPhase($tabParamChanges);
		if ($tabParamChanges['descriptionModification'] != null) {
			$dateModif = date('Y-m-d H:i:s');
			$typeModif = "Modification d'une phase";
			$description = "Modification d'une phase : " .$phase->nom_phase;
			Log::creerLog($dateModif, $typeModif, $login, $phase->action->code_action, $tabParamChanges['descriptionModification']);
		}
		return;
	}
	
	// Verification des modifications sur la phase
	public static function verifModifPhase($tabParametres){
		$phaseConcernee = Phase::recupererPhase($tabParametres['id']);
		$tabParamChanges=array();
		$tabParamChanges['descriptionModification'] = null;
		
		if ($phaseConcernee != null)
			$tabParamChanges['id']=$tabParametres['id'];
		else
			return null;
		
		if($tabParametres['nom_phase'] != $phaseConcernee->nom_phase){
			$tabParamChanges['nom_phase'] = $tabParametres['nom_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancien nom : ".$phaseConcernee->nom_phase ." ";
		}
		else {
			$tabParamChanges['nom_phase'] = $phaseConcernee->nom_phase;
		}
		
		if($tabParametres['commentaires_phase'] != $phaseConcernee->commentaires_phase){
			$tabParamChanges['commentaires_phase'] = $tabParametres['commentaires_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancien commentaire : ".$phaseConcernee->commentaires_phase ." ";
		}
		else {
			$tabParamChanges['commentaires_phase'] = $phaseConcernee->commentaires_phase;
		}
		
		if($tabParametres['date_debut_prevue'] != $phaseConcernee->date_debut_prevue){
			$tabParamChanges['date_debut_prevue'] = $tabParametres['date_debut_prevue'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne date de dÃ©but prÃ©vue : ".$phaseConcernee->date_debut_prevue ." ";
		}
		else {
			$tabParamChanges['date_debut_prevue'] = $phaseConcernee->date_debut_prevue;
		}
		
		if($tabParametres['date_fin_prevue'] != $phaseConcernee->date_fin_prevue){
			$tabParamChanges['date_fin_prevue'] = $tabParametres['date_fin_prevue'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne date de fin prÃ©vue : ".$phaseConcernee->date_fin_prevue ." ";
		}
		else {
			$tabParamChanges['date_fin_prevue'] = $phaseConcernee->date_fin_prevue;
		}
		
		if($tabParametres['date_debut_reelle'] != $phaseConcernee->date_debut_reelle){
			$tabParamChanges['date_debut_reelle'] = $tabParametres['date_debut_reelle'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne date de debut rÃ©elle : ".$phaseConcernee->date_debut_reelle ." ";
		}
		else {
			$tabParamChanges['date_debut_reelle'] = $phaseConcernee->date_debut_reelle;
		}
		
		if($tabParametres['date_fin_reelle'] != $phaseConcernee->date_fin_reelle){
			$tabParamChanges['date_fin_reelle'] = $tabParametres['date_fin_reelle'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne date de fin rÃ©elle : ".$phaseConcernee->date_fin_reelle ." ";
		}
		else {
			$tabParamChanges['date_fin_reelle'] = $phaseConcernee->date_fin_reelle;
		}
		
		if($tabParametres['description_phase'] != $phaseConcernee->description_phase){
			$tabParamChanges['description_phase'] = $tabParametres['description_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne description : ".$phaseConcernee->description_phase ." ";
		}
		else {
			$tabParamChanges['description_phase'] = $phaseConcernee->description_phase;
		}
		
		if($tabParametres['ordre_phase'] != $phaseConcernee->ordre_phase){
			$tabParamChanges['ordre_phase'] = $tabParametres['ordre_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne ordre : ".$phaseConcernee->ordre_phase ." ";
		}
		else {
			$tabParamChanges['ordre_phase'] = $phaseConcernee->ordre_phase;
		}
		
		if($tabParametres['services_porteurs'] != $phaseConcernee->services_porteurs){
			$tabParamChanges['services_porteurs'] = $tabParametres['services_porteurs'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancien service : ".$phaseConcernee->services_porteurs ." ";
		}
		else {
			$tabParamChanges['services_porteurs'] = $phaseConcernee->services_porteurs;
		}
		
		
		if((isset($tabParametres['ponderation_phase'])) && $tabParametres['ponderation_phase'] != $phaseConcernee->ponderation_phase){
			$tabParamChanges['ponderation_phase'] = $tabParametres['ponderation_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancienne pondÃ©ration : ".$phaseConcernee->ponderation_phase ." ";
		}
		else {
			$tabParamChanges['ponderation_phase'] = $phaseConcernee->ponderation_phase;
		}
			
		
		if($tabParametres['avancement_phase'] != $phaseConcernee->avancement_phase){
			$tabParamChanges['avancement_phase'] = $tabParametres['avancement_phase'];
			$tabParamChanges['descriptionModification'] = $tabParamChanges['descriptionModification']."Ancien avancement : ".$phaseConcernee->avancement_phase ." ";
		}
		else {
			$tabParamChanges['avancement_phase'] = $phaseConcernee->avancement_phase;
		}
		
		return $tabParamChanges;
	}
	
	/*Représentation d'une phase actuelle sous forme de tableau*/
	public static function formePhaseArray($phase){
		$phaseTab = array();
		$phaseTab['id']=$phase->id;
		$phaseTab['nom_phase']=$phase->nom_phase;
		$phaseTab['commentaires_phase']=$phase->commentaires_phase;
		$phaseTab['date_debut_prevue']=$phase->date_debut_prevue;
		$phaseTab['date_fin_prevue']=$phase->date_fin_prevue;
		$phaseTab['date_debut_reelle']=$phase->date_debut_reelle;
		$phaseTab['date_fin_reelle']=$phase->date_fin_reelle;
		$phaseTab['description_phase']=$phase->description_phase;
		$phaseTab['ordre_phase']=$phase->ordre_phase;
		$phaseTab['services_porteurs']=$phase->services_porteurs;
		$phaseTab['ponderation_phase']=$phase->ponderation_phase;
		$phaseTab['avancement_phase']=$phase->avancement_phase;
		return $phaseTab;
	}	
}
?>
