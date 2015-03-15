<?php
use RedBean_Facade as R;

class Financeur {
	
	public static $nameTable = "financeur";
	
	public static function creerFinanceur($nomFinanceur) {
		$financeur = R::dispense(Financeur::$nameTable);
		$financeur->nom_financeur = $nomFinanceur;
		R::store($financeur);
		
		return $financeur;
	}
	
	public static function listerFinanceurs() {
		$financeurs = R::findall(Financeur::$nameTable, "ORDER BY nom_financeur ASC");
		
		return $financeurs;
	}
	
	public static function recupererFinanceur($idFinanceur) {
		$financeur = R::findOne(Financeur::$nameTable, " id = ? ", array($idFinanceur));
		
		if($financeur == null)
			return null;
		
		return $financeur;
	}

	public static function chercherFinanceurs($nomFinanceur) {
		$nom = "%".$nomFinanceur."%";
		$financeurs = R::find(Financeur::$nameTable, " nom_financeur like ? ", array($nom));

		return $financeurs;
	}

	public static function renommerFinanceur($idFinanceur, $nouveauNom){
		$financeur = Financeur::recupererFinanceur($idFinanceur);
		
		if($financeur == null)
			return null;
		
		$financeur->nom_financeur = $nouveauNom;
		R::store($financeur);
		
		return $financeur;
	}
	
	public static function supprimerFinanceur($idFinanceur){
		$financeur = Financeur::recupererFinanceur($idFinanceur);
		
		if($financeur == null)
			return null;
		
		R::trash($financeur);
		return "OK";
	}
	
	public static function ajouterFinanceurAction($financeur,$financeurAction){
		$financeur->ownFinanceuraction[]=$financeurAction;
		R::store($financeur);
		return;
	}
	
}
?>
