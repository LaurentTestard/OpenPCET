<?php
use RedBean_Facade as R;
 
/*Cette classe gère les partenaires d'une action dans la BD*/
class Partenaire {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "partenaire";
	
	/*Création d'un partenaire*/
	public static function creerPartenaire($nomPartenaire){
		$partenaire = R::dispense(Partenaire::$nameTable);
		$partenaire->nom_partenaire = $nomPartenaire;
		$idPartenaire = R::store($partenaire);
		return $partenaire;
	}
	
	/*Liste de tous les partenaires de la BD*/
	public static function listerPartenaires() {
		$partenaires = R::findall(Partenaire::$nameTable, "ORDER BY nom_partenaire ASC");
	
		return $partenaires;
	}
	
	/*Fonctions pour la récupération des informations des partenaires*/
	public static function recupererPartenaire($idPartenaire) {
		$partenaire = R::findOne(Partenaire::$nameTable, " id = ? ", array($idPartenaire));
	
		if($partenaire == null)
			return null;
	
		return $partenaire;
	}
	
	public static function chercherPartenaires($nomPartenaire) {
		$nom = "%".$nomPartenaire."%";
		$partenaires = R::find(Partenaire::$nameTable, " nom_partenaire like ? ", array($nom));
	
		return $partenaires;
	}
	
	/*Renommage d'un partenaire*/
	public static function renommerPartenaire($idPartenaire, $nouveauNom){
		$partenaire = Partenaire::recupererPartenaire($idPartenaire);
	
		if($partenaire == null)
			return null;
	
		$partenaire->nom_partenaire = $nouveauNom;
		R::store($partenaire);
	
		return $partenaire;
	}
	
	/*Suppression d'un partenaire*/
	public static function supprimerPartenaire($idPartenaire){
		$partenaire = Partenaire::recupererPartenaire($idPartenaire);
	
		if($partenaire == null)
			return null;
	
		R::trash($partenaire);
		return "OK";
	}
	
	/*Association d'une action à un partenaire*/
	public static function ajouterAction($partenaire,$action){
		$partenaire->sharedAction[]=$action;
		R::store($partenaire);
		return;
	}
	
	}
?>
		