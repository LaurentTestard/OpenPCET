<?php
use RedBean_Facade as R;
use Symfony\Component\Validator\Constraints\Date;

class FctPhaseDep {
	/* Liste toutes les phases des départ*/
	public static function listerPhasesDepAction($codeAction){
		$actions = Action::getAction($codeAction);
		$phasesdep = $actions->ownPhasedep;
		
		$phasesdepAEncoder = array();
		
		foreach ($phasesdep as $phasedep){
			//Remplissage du tableau de l'action courante pour encodage
			$phasedepAEncoder=array();
			$phasedepAEncoder['id']=$phasedep->id;
			$phasedepAEncoder['nom_phasedep']=$phasedep->nom_phasedep;
			$phasedepAEncoder['date_debut_prevuephd']=$phasedep->date_debut_prevuephd;
			$phasedepAEncoder['date_fin_prevuephd']=$phasedep->date_fin_prevuephd;
			$phasedepAEncoder['date_debut_reellephd']=$phasedep->date_debut_reellephd;
			$phasedepAEncoder['date_fin_reellephd']=$phasedep->date_fin_reellephd;
			$phasedepAEncoder['description_phasedep']=$phasedep->description_phasedep;
			$phasedepAEncoder['ordre_phasedep']=$phasedep->ordre_phasedep;
			$phasedepAEncoder['services_porteursphd']=$phasedep->services_porteursphd;
			$phasedepAEncoder['avancement_phasedep']=$phasedep->avancement_phasedep;
			$phasedepAEncoder['commentaires_phasedep']=$phasedep->commentaires_phasedep;
			$phasesdepAEncoder[]=$phasedepAEncoder;
		}
		
		return $phasesdepAEncoder;
	}
	
	/*Suppression de phase de départ*/
	public static function supprimerPhasedep($idPhasedep, $idUtilisateur, $codeAction, $ordrePhasedep, $dateModif, $typeModif){
		$phasedep = Phasedep::recupererPhasedep($idPhasedep);
		if ($phasedep == null)
			return null;
		$action = $phasedep->action;
		if($action == null)
			return null;
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		if(strcmp($action->code_action, $codeAction) != 0)
			return null;
		Log::creerLog($dateModif,$typeModif, $utilisateur->login_utilisateur, $action->code_action, $typeModif ." - Nom de la phase : " .$phasedep->nom_phasedep);
		Phasedep::supprimerPhasedep($idPhasedep);
		foreach ($action->ownPhasedep as $phasedepAction){
			$ordrePhasedepAction = $phasedepAction->ordre_phasedep;
			if($ordrePhasedepAction > $ordrePhasedep)
				Phasedep::decrementerOrdrePhasedep($phasedepAction);
		}
		return $action;
	}
	
	/*Récupération des informations d'une phase départ*/
	public static function visualiserPhasedep($idAction, $idPhasedep){
		$phasedep = Phasedep::recupererPhasedep($idPhasedep);
		if($phasedep != null){
			if($phasedep->action->id == $idAction){
				$detailsPhasedep = array();
				$detailsPhasedep['id'] = $phase->id;
				$detailsPhasedep['nom_phasedep'] = $phasedep->nom_phasedep;
				$detailsPhasedep['commentaires_phasedep'] = $phasedep->commentaires_phasedep;
				$detailsPhasedep['date_debut_prevuephd'] = $phasedep->date_debut_prevuephd;
				$detailsPhasedep['date_fin_prevuephd'] = $phasedep->date_fin_prevuephd;
				$detailsPhasedep['description_phasedep'] = $phasedep->description_phasedep;
				$detailsPhasedep['ordre_phasedep'] = $phasedep->ordre_phasedep;
				$detailsPhasedep['services_porteursphd'] = $phasedep->services_porteursphd;
				return $detailsPhasedep;
			}
			return null;
		}
		return null;
	}
	
	/*Ajout d'une nouvelle phase de départ*/
	public static function ajouterPhasedep($tabParametres, $login){
		$action = Action::getActionById($tabParametres['code_action']);
		$id=$tabParametres['code_action'];
		if($action == null)
			return null;
		/*Verification du droit de l'utilisateur*/
		$utilisateur = Utilisateur::getUtilisateur($login);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		

		//Decapsulation des donnees a inserer
		$nomPhasedep = $tabParametres['nom_phasedep'];
		$commentairesPhasedep = $tabParametres['commentaires_phasedep'];
		$descriptionPhasedep = $tabParametres['description_phasedep'];
		$ordrePhasedep = $tabParametres['ordre_phasedep'];
		$servicesPorteursphd = $tabParametres['services_porteursphd'];
		
		
		//Manipulation des dates
		if(isset($tabParametres['date_debut_prevuephd'])) {
			$dateDebutPhasedep = $tabParametres['date_debut_prevuephd'];
		}
		else {
			$dateDebutPhasedep = null;
		}
		
		if(isset($tabParametres['date_fin_prevuephd'])) {
			$dateFinPhasedep= $tabParametres['date_fin_prevuephd'];
		}
		else {
			$dateFinPhasedep = null;
		}
		
		/*Création de la phase de départ et association avec l'action*/
		$phasedep = Phasedep::creerPhasedep($nomPhasedep, $commentairesPhasedep,$dateDebutPhasedep, $dateFinPhasedep,$descriptionPhasedep, $ordrePhasedep, $servicesPorteursphd,$id);
		if($phasedep == null)
			return null;
		//Gestion ordre
		/*foreach ($action->ownPhase as $phaseAction) {
			$ordrePhaseAction = $phaseAction->ordre_phase;
			if($ordrePhaseAction >= $ordrePhase)
				Phase::incrementerOrdrePhase($phaseAction);
		}*/
		//Action::addPhasedep($id, $phasedep);

		/*$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Ajout d'une phase";
		$description = "Ajout d'une phase : " .$phasedep->nom_phasedep;
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);*/
		
		return $phasedep;
	}
	
	/*Modification d'une phase de départ*/
	public static function modifierPhasedepAction($tabParametres, $login){
		/*Verification des informations à mettre à jour*/	
		$tabParamChanges = FctPhaseDep::verifModifPhasedep($tabParametres);
		
		$phasedep = PhaseDep::recupererPhasedep($tabParametres['id']);
		if($phasedep == null)
			return null;
		/*Verification du droit de l'utilisateur*/
		$utilisateur = Utilisateur::getUtilisateur($login);
		if(!Action::aDroitDeModification($phasedep->action,$utilisateur)){
			return null;
		}
		/*Mise à jour des modifications*/
		$phasedep = PhaseDep::modifierPhasedep($tabParametres);
		
		return;
	}
	
	// Verification des modifications sur la phase
	public static function verifModifPhasedep($tabParametres){
		$phasedepConcernee = Phasedep::recupererPhasedep($tabParametres['id']);
		$tabParamChanges=array();		
		
		if ($phasedepConcernee != null)
			$tabParamChanges['id']=$tabParametres['id'];
		else
			return null;
		
		if($tabParametres['nom_phasedep'] != $phasedepConcernee->nom_phasedep){
			$tabParamChanges['nom_phasedep'] = $tabParametres['nom_phasedep'];
		}
		else {
			$tabParamChanges['nom_phasedep'] = $phasedepConcernee->nom_phasedep;
		}
		
		if($tabParametres['commentaires_phasedep'] != $phasedepConcernee->commentaires_phasedep){
			$tabParamChanges['commentaires_phasedep'] = $tabParametres['commentaires_phasedep'];
		}
		else {
			$tabParamChanges['commentaires_phasedep'] = $phasedepConcernee->commentaires_phasedep;
		}
		
		if($tabParametres['date_debut_prevuephd'] != $phasedepConcernee->date_debut_prevuephd){
			$tabParamChanges['date_debut_prevuephd'] = $tabParametres['date_debut_prevuephd'];
		}
		else {
			$tabParamChanges['date_debut_prevuephd'] = $phasedepConcernee->date_debut_prevuephd;
		}
		
		if($tabParametres['date_fin_prevuephd'] != $phasedepConcernee->date_fin_prevuephd){
			$tabParamChanges['date_fin_prevuephd'] = $tabParametres['date_fin_prevuephd'];
		}
		else {
			$tabParamChanges['date_fin_prevuephd'] = $phasedepConcernee->date_fin_prevuephd;
		}
		
		if($tabParametres['description_phasedep'] != $phasedepConcernee->description_phasedep){
			$tabParamChanges['description_phasedep'] = $tabParametres['description_phasedep'];
		}
		else {
			$tabParamChanges['description_phasedep'] = $phasedepConcernee->description_phasedep;
		}
		
		if($tabParametres['ordre_phasedep'] != $phasedepConcernee->ordre_phasedep){
			$tabParamChanges['ordre_phasedep'] = $tabParametres['ordre_phasedep'];
		}
		else {
			$tabParamChanges['ordre_phasedep'] = $phasedepConcernee->ordre_phasedep;
		}
		
		if($tabParametres['services_porteursphd'] != $phasedepConcernee->services_porteursphd){
			$tabParamChanges['services_porteursphd'] = $tabParametres['services_porteursphd'];
		}
		else {
			$tabParamChanges['services_porteursphd'] = $phasedepConcernee->services_porteursphd;
		}
			
		return $tabParamChanges;
	}
	
	/*Représentation d'une phase de départ sous forme de tableau*/
	public static function formePhaseArray($phasedep){
		$phasedepTab = array();
		$phasedepTab['id']=$phasedep->id;
		$phasedepTab['nom_phasedep']=$phasedep->nom_phasedep;
		$phasedepTab['commentaires_phasedep']=$phasedep->commentaires_phasedep;
		$phasedepTab['date_debut_prevuephd']=$phasedep->date_debut_prevuephd;
		$phasedepTab['date_fin_prevuephd']=$phasedep->date_fin_prevuephd;
		$phasedepTab['description_phasedep']=$phasedep->description_phasedep;
		$phasedepTab['ordre_phasedep']=$phasedep->ordre_phasedep;
		$phasedepTab['services_porteursphd']=$phasedep->services_porteursphd;
		
		return $phasedepTab;
	}	
}
?>
