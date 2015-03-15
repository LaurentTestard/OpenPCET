<?php
use RedBean_Facade as R;
class FctActionDocument {
	
	/*Fonction pour récupérer tous les documents liés à une action*/
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
