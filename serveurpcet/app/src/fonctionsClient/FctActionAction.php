<?php
use RedBean_Facade as R;

class FctActionAction {

	/*Fonction pour l'association des actions*/
	// Pas de notion de père et de fils mais seulement une table avec idActionAction idAction1 idAction2
	public static function formeActionActionArray($action) {
		$actionactions = ActionAction::listerFils($action->id);
		$actionactionRes = array();
		foreach ($actionactions as $actionaction){
			$actionactionTab = array();
			// Récupération du bon id
			if($actionaction->id_action_fils == $action->id) {
				$idActionLie = $actionaction->id_action_pere;
			} else {
				$idActionLie = $actionaction->id_action_fils;
			}
			$actionactionTab['id']=$idActionLie;
			$actionLie = Action::getActionById($idActionLie);
			$actionactionTab['code_action']=$actionLie->code_action;
			$actionactionTab['nom_action']=$actionLie->nom_action;
			$actionactionRes[] = $actionactionTab;
		}
		return $actionactionRes;
	}
	
	/*R�cup�ration des actions associ�es � une action*/
	public static function recupActions($id){
		$actions=ActionAction::listerFils($id);
		$actionactionRes = array();
		foreach ($actions as $actionaction){
			$actionactionTab = array();
			// Récupération du bon id
			if($actionaction->id_action_fils == $id) {
				$idActionLie = $actionaction->id_action_pere;
			} else {
				$idActionLie = $actionaction->id_action_fils;
			}
			$actionactionTab['id']=$idActionLie;
			$actionLie = Action::getActionById($idActionLie);
			$actionactionTab['code_action']=$actionLie->code_action;
			$actionactionTab['nom_action']=$actionLie->nom_action;
			$actionactionRes[] = $actionactionTab;
		}
		return $actionactionRes;
		}
		
		/*R�cup�ration des actions qui ne sont pas associ�es � une action*/
		public static function recupActionsnonliees($id){
			$actions=Action::getAllAction();
			$actionsliees=FctActionAction::recupActions($id);
			$actionsRes = array();
			foreach ($actions as $action){
				$identique=false;
				foreach($actionsliees as $actionliee){
					if(($action->id)==($actionliee['id'])){
						$identique=true;
					}
				}
				if($identique==false){
					$actionactionTab = array();
					$actionactionTab['id']=$action->id;
					$actionactionTab['code_action']=$action->code_action;
					$actionactionTab['nom_action']=$action->nom_action;
					$actionsRes[] = $actionactionTab;
				}
			}
			return $actionsRes;
		}
		
	/*Liaison de deux actions*/
	public static function lierActionPereFils($idPere,$idFils,$loginUtilisateur){
		$actionPere = Action::getActionById($idPere);
		$actionFille = Action::getActionById($idFils);
		ActionAction::ajouterActionFils($idPere, $idFils);
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison de l'action à une autre";
		$description = "L'action a été liée à l'action ".$actionFille->code_action.".";
		/*Sauvegarde du trace de cette liaison*/
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $actionPere->code_action, $description);
		$description = "L'action a été liée à l'action ".$actionPere->code_action.".";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $actionFille->code_action, $description);
		return true;
	}
	
	/*Suppression du lien entre deux actions*/
	public static function delierActionPereFils($idPere,$idFils,$loginUtilisateur){
		$actionPere = Action::getActionById($idPere);
		$actionFille = Action::getActionById($idFils);
		$actionaction = ActionAction::recupererActionActionParIdAction($idPere, $idFils);
		ActionAction::supprimerActionAction($actionaction->id);
		/*Sauvegarde du trace de cette modification*/
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Déliaison de deux actions";
		$description = "L'action a été déliée de l'action ".$actionFille->code_action.".";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $actionPere->code_action, $description);
		$description = "L'action a été déliée de l'action ".$actionPere->code_action.".";
		Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $actionFille->code_action, $description);
		return true;
	}
}
?>