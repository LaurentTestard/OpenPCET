<?php
use RedBean_Facade as R;

class Budget {
	
	public static $nameTable = "budget";
	
	public static function ajoutBudget($annee,$idaction,$renseigner,$commcomm,$budget_consomme,$commentaire,$budget_total) {
		$budget = R::dispense(Budget::$nameTable);
		$budget->annee = $annee;
		$budget->idaction = $idaction;
		$budget->renseigner = $renseigner;
		$budget->commcomm = $commcomm;
		$budget->budget_consomme = $budget_consomme;
		$budget->budget_total = $budget_total;
		$budget->commentaire = $commentaire;
		R::store($budget);
				
		return $budget;
	}
	
	public static function recupererBudget($idBudget) {
		$budget = R::findOne(Budget::$nameTable, " id = ? ", array($idBudget));
	
		if($budget == null)
			return null;
	
		return $budget;
	}
	
	public static function recupererBudgetParActAnnee($idAction,$annee) {
		$budget = R::findOne(Budget::$nameTable, " idaction = ? and annee=? ", array($idAction,$annee));
	
		if($budget == null){
			return null;
		}
	
		return $budget;
	}
	
	/*Liste les budgets d'une action par annee*/
	public static function listerBudgets($idaction) {
		$budgets = R::findAll(Budget::$nameTable, " idaction = ?", array($idaction),"ORDER BY annee ASC");
		return $budgets;
	}
	
	public static function supprimerBudget($idBudget){
		$budget = Budget::recupererBudget($idBudget);
		
		if($budget == null)
			return null;
		
		R::trash($budget);
		return "OK";
	}
	
}
?>
