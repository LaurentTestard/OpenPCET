<?php
use RedBean_Facade as R;

class FctDocument {
	
	/*Fonction pour représenter les informations d'un document dans la BD, sous forme de tableau*/
	public static function formeDocumentArray($document){
		$documentTab = array();
		$documentTab['id']=$document->id;
		$documentTab['nom_document']=$document->nom_document;
		$documentTab['path']=$document->path;
		$documentTab['type_document']=$document->type_document;
		$documentTab['proprietaire']=$document->proprietaire;
		return $documentTab;
	}
	
	/*Récupération de toutes les actions qui ne sont pas liées à un document*/
	public static function getAllActionNonLie($login,$idDocument) {
		
		$document = Document::recupererDocument($idDocument);
		$actions = Action::getDocumentNonLieDocument($document);
		
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
	
	/*Récupération de toutes les actions associées à un dcument*/
	public static function getAllActionLie($login,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$actions = $document->sharedAction;
		$actionsRep=array();
		/*Représentation des actions sous forme de tableau*/
		foreach($actions as $action){
			$actionRep=array();
			$actionRep['id']=$action->id;
			$actionRep['code_action']=$action->code_action;
			$actionRep['nom_action']=$action->nom_action;
			$actionsRep[]=$actionRep;
		}
		return $actionsRep;
	}
	
	/*Association d'une action et d'un document*/
	public static function lieActionDocument($login,$idAction,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$action = Action::getActionById($idAction);
		Action::addDocument($action, $document);
		/*Sauvegarde d'une trace de cette association*/
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Liaison d'un document Ã  l'action";
		$description = "Le document \"".$document->nom_document."\" a Ã©tÃ© liÃ© Ã  l'action.";
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		return array('success' => 'ok');
	}
	
	/*Suppression du lien entre une action et un document*/
	public static function delieActionDocument($login,$idAction,$idDocument) {
		$document = Document::recupererDocument($idDocument);
		$action = Action::getActionById($idAction);
		Action::deleteDocument($action, $document);
		/*Sauvegarde de la trace de cette suppression*/
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "DÃ©liaison d'un document de l'action";
		$description = "Le document \"".$document->nom_document."\" a Ã©tÃ© dÃ©liÃ© de l'action.";
		Log::creerLog($dateModif, $typeModif, $login, $action->code_action, $description);
		return array('success' => 'ok');
	}

	/*Suppression d'un document de la plateforme*/
	public static function supprimerDocument($login,$idDocument) {
		/*Appel de la fonction supprimerDocumentde la classe Document*/
		Document::supprimerDocument($idDocument);
		return array('success' => 'ok');
	}	
	
}
?>