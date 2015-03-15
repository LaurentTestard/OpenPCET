<?php
use RedBean_Facade as R;

class CrAction {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "craction";
	
	/*Fonctions pour la création d'un commentaire d'une action*/
	public static function creerCrAction($DescriptionCRAction,$estActualite){
		return CrAction::creerCrActionP(date('Y-m-d H:i:s'), $DescriptionCRAction, $estActualite);
	}
	
	public static function creerCrActionP($dateCRAction,$DescriptionCRAction,$estActualite){
		$crAction = R::dispense(CrAction::$nameTable);
		$crAction->date_cr_action = $dateCRAction;
		$crAction->description_cr_action = $DescriptionCRAction;
		$crAction->est_actualite = $estActualite;
		$idCRAction = R::store($crAction);
		return $crAction;
	}
	
	/*Récupération du commentaire à partir de la BD*/
	public static function recupererCrAction($idCr) {
		$crAction = R::findOne(CrAction::$nameTable, " id = ? ", array($idCr));
		if($crAction == null)
			return null;
		return $crAction;
	}
	
	/*Seppression d'un commentaire*/
	public static function supprimerCr($idCr){
		$crAction = CrAction::recupererCrAction($idCr);
		if($crAction == null)
			return null;
		R::trash($crAction);
		return "OK";
	}

}
?>