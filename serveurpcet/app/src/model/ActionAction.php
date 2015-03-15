<?php
use RedBean_Facade as R;
 
class ActionAction {
	
	/*Nom de la table de la base de donn�es sur laquelle cette classe va travailler*/
	public static $nameTable = "actionaction";

	//Fonction d'ajout de la relation entre une action père et fils, sans passer par les listes own et shared
	public static function ajouterActionFils($idActionPere,$idActionFils){
		$actionPere = R::dispense(ActionAction::$nameTable);
		
		//recherche d'une liaison existante, indépendemment du sens
		$actionExiste = ActionAction::recupererActionActionParIdAction($idActionFils, $idActionPere);
		
		if ($actionExiste != null) {
			return $actionExiste ;
		}
		
		//Création du lien
		$actionPere->id_action_pere = $idActionPere;
		$actionPere->id_action_fils = $idActionFils;

		R::store($actionPere);
		return;
	}
	
	public static function listerFils($idActionPere){
		//j'ai conservé le même nom de fonction. Mais suite à une précision, les liens sont bidirectionnelles
		//c'est à dire que une action liée avec une autre, doit être vue des deux côtés
		$actionActions = R::find(ActionAction::$nameTable, " id_action_pere = ? or id_action_fils = ?", array($idActionPere, $idActionPere));
		
		return $actionActions;
	}
	
	public static function recupererActionAction($idActionAction) {
		$actionAction = R::findOne(ActionAction::$nameTable, " id = ? ", array($idActionAction));
	
		if($actionAction == null)
			return null;
	
		return $actionAction;
	}
	
	public static function recupererActionActionParIdAction($idActionPere, $idActionFils) {
		//test de la récupération en faisant abstraction du sens de la relation
		$actionAction = R::findOne(ActionAction::$nameTable, " (id_action_pere = ? and id_action_fils = ?) or (id_action_pere = ? and id_action_fils = ?)", array($idActionPere, $idActionFils, $idActionFils, $idActionPere));
	
		if($actionAction == null)
			return null;
	
		return $actionAction;
	}
	
	/*Suppression d'un lien entre deux actions*/
	public static function supprimerActionAction($idActionAction){
		$actionAction = ActionAction::recupererActionAction($idActionAction);
	
		if($actionAction == null)
			return null;
	
		R::trash($actionAction);
		return "OK";
	
		return $actionActions;
	}
	

	}
?>		