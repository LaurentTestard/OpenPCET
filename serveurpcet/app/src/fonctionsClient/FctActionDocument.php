<?php
use RedBean_Facade as R;
class FctActionDocument {
	
	/*Fonction pour r�cup�rer tous les documents li�s � une action*/
	public static function documentsDAction($idAction) {
		$action = Action::getActionById($idAction);
		$documents = array();
		foreach($action->sharedDocument as $document){
			$documents[]=FctDocument::formeDocumentArray($document);
		}
		return $documents;
	}

}


?>
