<?php
use RedBean_Facade as R;

class FctFinanceurAction {

	public static function formeFinanceurActionArray($financeurAction){
		$financeurActionRes = array();
		$financeurActionRes['montant_ht']=$financeurAction->montant_ht;
		$financeurActionRes['nom_financeur']=$financeurAction->financeur->nom_financeur;
		return $financeurActionRes;
	}
	
}
?>