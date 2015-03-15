<?php
use RedBean_Facade as R;

/*Cette classe permet de gérer les objectifs stratégiques dans la BD*/
class ObjectifStrategique {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "objectifstrategique";

	/*Création d'un objectif stratégique*/
	public static function creerObjectifStrategique($codeObjectifStrategique,$nomObjectifStrategique){
		$obj_strat = R::dispense(ObjectifStrategique::$nameTable);
		$obj_strat->code_objectif_strategique = $codeObjectifStrategique;
		$obj_strat->nom_objectif_strategique = $nomObjectifStrategique;
		$idObjStrat = R::store($obj_strat);
		return $obj_strat;
	}

	/*Ajout d'une action à un objectif stratégique*/
	public static function addAction($objectifstrategique,$action){
		$objectifstrategique->ownAction[]=$action;
		R::store($objectifstrategique);
		return;
	}
	
	/*Récupération d'un objectif stratégique*/
	public static function recupererObjsByNom($nom) {
		$objs = R::findOne(ObjectifStrategique::$nameTable, " nom_objectif_strategique = ? ", array($nom));
		if($objs == null)
			return null;
		return $objs;
	}
	
	/*Liste de tous les objectifs stratégiques*/
	public static function listerObjectifsStrategiques() {
		$objs_strats = R::findAll(ObjectifStrategique::$nameTable, "ORDER BY code_objectif_strategique ASC");
		
		return $objs_strats;
	}
	
	/*Liste des objectifs stratégiques d'une thématique*/
	public static function listerObjectifsStrategiquesParEngagement($id){
		$obj_strat = R::find(ObjectifStrategique::$nameTable, " engagementthematique_id = ? ", array($id));
		
		if($obj_strat == null)
			return null;
		
		return $obj_strat;
	}
	
	/*Fonction pour la récupérations d'objectifs stratégiques*/
	public static function recupererObjectifStrategique($idObjectifStrategique) {
		$obj_strat = R::findOne(ObjectifStrategique::$nameTable, " id = ? ", array($idObjectifStrategique));
	
		if($obj_strat == null)
			return null;
	
		return $obj_strat;
	}
	
	public static function chercherObjectifStrategique($nomObjectifStrategique) {
		$nomObjThema = "%" . $nomObjectifStrategique . "%";
		$objs_strats = R::find(ObjectifStrategique::$nameTable, " nom_objectif_strategique like ? ", array($nomObjThema));
	
		return $objs_strats;
	}
	
	/*Renommage d'objectif stratégique*/
	public static function renommerObjectifStrategique($idObjectifStrategique, $nouveauNom){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique($idObjectifStrategique);
	
		if($obj_strat == null)
			return null;
	
		$obj_strat->nom_objectif_strategique = $nouveauNom;
		R::store($obj_strat);
	
		return $obj_strat;
	}
	
	/*Suppression d'un objectif stratégique*/
	public static function supprimerObjectifStrategique($idObjectifStrategique){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique($idObjectifStrategique);
	
		if($obj_strat == null)
			return null;
	
		R::trash($obj_strat);
		return "OK";
	}
	
}
?>