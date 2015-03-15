<?php
use RedBean_Facade as R;

/*CETTE CLASSE EST OBSOLETE*/
class ThematiqueConcernee {
 	
	public static $nameTable = "thematiqueconcernee";
	
	public static function creerThematiqueConcernee($nomThematiqueConcernee){
		$thematiqueConcernee = R::dispense(ThematiqueConcernee::$nameTable);
		$thematiqueConcernee->nom_thematique_concernee = $nomThematiqueConcernee;
		$idThematiqueConcernee = R::store($thematiqueConcernee);
		return $thematiqueConcernee;
	}
	
	
	public static function listerThematiquesConcernees() {
		$thematiqueConcernee = R::findall(ThematiqueConcernee::$nameTable, "ORDER BY nom_thematique_concernee ASC");
	
		return $thematiqueConcernee;
	}
	
	public static function recupererThematiqueConcernee($idThematiqueConcernee) {
		$thematiqueConcernee = R::findOne(ThematiqueConcernee::$nameTable, " id = ? ", array($idThematiqueConcernee));
	
		if($thematiqueConcernee == null)
			return null;
	
		return $thematiqueConcernee;
	}
	
	public static function chercherThematiquesConcernees($nomThematiqueConcernee) {
		$nom = "%".$nomThematiqueConcernee."%";
		$thematiquesConcernees = R::find(ThematiqueConcernee::$nameTable, " nom_thematique_concernee like ? ", array($nom));
	
		return $thematiquesConcernees;
	}
	
	public static function renommerThematiqueConcernee($idThematiqueConcernee, $nouveauNom){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee($idThematiqueConcernee);
	
		if($thematiqueConcernee == null)
			return null;
	
		$thematiqueConcernee->nom_thematique_concernee = $nouveauNom;
		R::store($thematiqueConcernee);
	
		return $thematiqueConcernee;
	}
	
	public static function supprimerThematiqueConcernee($idThematiqueConcernee){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee($idThematiqueConcernee);
	
		if($thematiqueConcernee == null)
			return null;
	
		R::trash($thematiqueConcernee);
		return "OK";
	}

	public static function ajouterAction($thematiqueConcernee,$action){
		$thematiqueConcernee->ownAction[]=$action;
		R::store($thematiqueConcernee);
		return;
	}
}
?>