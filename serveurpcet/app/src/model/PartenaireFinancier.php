<?php
use RedBean_Facade as R;

class PartenaireFinancier{
	
	public static $nameTable = "partenairefinancier";
	
	public static function ajoutPartenaireFin($nomPart,$budgetPart,$idBudget) {
		$partenaireFin = R::dispense(PartenaireFinancier::$nameTable);
		$partenaireFin->nomPart = $nomPart;
		$partenaireFin->budgetPart = $budgetPart;
		$partenaireFin->idBudget = $idBudget;
		R::store($partenaireFin);
		
		return $budget;
	}
	
}
?>