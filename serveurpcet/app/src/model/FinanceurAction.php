<?php
use RedBean_Facade as R;

class FinanceurAction {
	
	public static $nameTable = "financeuraction";
	
	public static function creerFinanceurAction($montantHt) {
		$financeurAction = R::dispense(FinanceurAction::$nameTable);
		$financeurAction->montant_ht = $montantHt;
		R::store($financeurAction);
		
		return $financeurAction;
	}
	
	public static function recupererFinanceurAction($idFinanceurAction) {
		$financeurAction = R::findOne(FinanceurAction::$nameTable, " id = ? ", array($idFinanceurAction));
	
		return $financeurAction;
	}

	public static function mettreAjourFinanceurAction($idFinanceurAction, $nouveauMontant){
		$financeurAction = FinanceurAction::recupererFinanceurAction($idFinanceurAction);
		
		if($financeurAction == null)
			return null;
		
		$financeurAction->montant_ht = $nouveauMontant;
		R::store($financeurAction);
		
		return $financeurAction;
	}
	
	public static function supprimerFinanceurAction($idFinanceurAction){
		$financeurAction = FinanceurAction::recupererFinanceurAction($idFinanceurAction);
		
		if($financeurAction == null)
			return null;
		
		R::trash($financeurAction);
		return "OK";
	}
}
?>
