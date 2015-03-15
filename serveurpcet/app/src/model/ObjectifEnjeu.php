<?php
use RedBean_Facade as R;

/*CETTE CLASSE N'EST PAS UTILISEE*/
class ObjectifEnjeu {
	

	public static $nameTable = "objectifenjeu";
	
	public static function creerObjectifEnjeu($nomObjectifEnjeu){
		$objectifEnjeu = R::dispense(ObjectifEnjeu::$nameTable);
		$objectifEnjeu->nom_objectif_enjeu = $nomObjectifEnjeu;
		$idObjectifEnjeu = R::store($objectifEnjeu);
		return $objectifEnjeu;
	}
	
	public static function getNomObjectifEnjeu($idObjectifEnjeu){
		$objectifEnjeu = R::findOne(ObjectifEnjeu::$nameTable, ' id = ?  ', array($idObjectifEnjeu));
		return $objectifEnjeu->nom_objectif_enjeu;
	}
	
	public static function getObjectifEnjeu($nomObjectifEnjeu){
		$objectifEnjeu = R::findOne(ObjectifEnjeu::$nameTable, ' nom_objectif_enjeu = ?  ', array($nomObjectifEnjeu));
		return $objectifEnjeu;
	}
	
	public static function getObjectifsEnjeuxByAction($action) {
		$objectifsEnjeux = R::find(ObjectifEnjeu::$nameTable, " action_id = ? ", array($action->id));
		return $objectifsEnjeux;
		
	}
	
	public static function getObjectifsEnjeuxByIdAction($idaction) {
		$objectifsEnjeux = R::find(ObjectifEnjeu::$nameTable, " action_id = ? ", array($idaction));
		return $objectifsEnjeux;
	
	}
	
	public static function addIndicateur($objectif,$indicateur){
		$objectif->ownIndicateur[]=$indicateur;
		R::store($objectif);
		return;
	}
	
	
	
	public static function listerObjectifsEnjeux() {
		$objectifs = R::findall(ObjectifEnjeu::$nameTable, "ORDER BY nom_objectif_enjeu ASC");
		return $objectifs;
	}
	
	public static function chercherObjectifsEnjeux($nomObjectifEnjeu) {
		$nom = "%" . $nomObjectifEnjeu . "%";
		$objectifsEnjeux = R::find(ObjectifEnjeu::$nameTable, " nom_objectif_enjeu LIKE ? ", array($nom));
		return $objectifsEnjeux;
	}
	
	
	public static function recupererObjectifEnjeu($idObjectifEnjeu) {
		$objectifEnjeu = R::findOne(ObjectifEnjeu::$nameTable, " id = ? ", array($idObjectifEnjeu));
		if($objectifEnjeu == null)
			return null;
		return $objectifEnjeu;
	}
	
	public static function recupererObjectifEnjeuByNom($nomObjectifEnjeu) {
		$objectif = R::findOne(ObjectifEnjeu::$nameTable, " nom_objectif_enjeu = ? ", array($nomObjectifEnjeu));
		if($objectif == null)
			return null;
		return $objectif;
	}
	
	public static function renommerObjectifEnjeu($idObjectifEnjeu, $nouveauNom){
		$objectifEnjeu = ObjectifEnjeu::recupererObjectifEnjeu($idObjectifEnjeu);
		if($objectifEnjeu == null)
			return null;
		$objectifEnjeu->nom_objectif_enjeu = $nouveauNom;
		R::store($objectifEnjeu);
		return $objectifEnjeu;
	}
	
	public static function supprimerObjectifEnjeu($idObjectifEnjeu){
		$objectifEnjeu = ObjectifEnjeu::recupererObjectifEnjeu($idObjectifEnjeu);
		if($objectifEnjeu == null)
			return null;
		R::trash($objectifEnjeu);
		return "OK";
	}
	
}
?>
