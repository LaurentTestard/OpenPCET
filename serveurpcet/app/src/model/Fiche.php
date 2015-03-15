<?php
use RedBean_Facade as R;

/*Cette classe contient les fonctions pour la gestion des version pdf des fiches actions*/
class Fiche {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "fiche";
	
	/*Création d'une fiche actin dans la BD*/
	public static function creerFiche($nom) {
		
		$exfiche=Fiche::recupererFicheByNom($nom);
		if($exfiche==null){
			$fiche = R::dispense(Fiche::$nameTable);
			
			$fiche->nom_fiche = $nom;
			$fiche->path = '/fichier/'.$nom;
			R::store($fiche);
			
			return $fiche;
		}
		/*Si la fiche action existe déjà, elle est supprimer et une nouvelle avec le même nom est créée*/
		else{
			R::trash($exfiche);
			$fiche = R::dispense(Fiche::$nameTable);
				
			$fiche->nom_fiche = $nom;
			$fiche->path = '/fichier/'.$nom;
			R::store($fiche);
				
			return $fiche;
		}
	}
	
	/*Fonction pour la création d'un seconde version d'une fiche*/
	/*Fonction à tester ou à revoir*/
	public static function majFiche($nom,$path) {
	
		$fiche = Fiche::recupererFicheByNom($nom);
		if($fiche==null){
			Fiche::creerFiche($nom);
		}
		else{
			$fiche->nom_fiche='V2'.$nom;
			$fiche->path=$path;
		}
		R::store($fiche);
	
		return $fiche;
	}
	
	/*Liste de toutes les fiches actions*/
	public static function listerFiches() {
		$fiches = R::findall(Fiche::$nameTable, "ORDER BY nom_fiche ASC");
		return $fiches;
	}
	
	/*Fonctions pour la récupération des informations des fiches actions*/
	public static function recupererFiche($idFiche) {
		$fiche = R::findOne(Fiche::$nameTable, " id = ? ", array($idFiche));
		if($fiche == null)
			return null;
		return $fiche;
	}
	
	public static function recupererFicheByNom($nameFiche) {
		$fiche = R::findOne(Fiche::$nameTable, " nom_fiche = ? ", array($nameFiche));
		if($fiche == null){
			return null;
		}
		return $fiche;
	}
	
	public static function chercherFiches($nomFiche) {
		$nom = "%".$nomFiche."%";
		$fiche = R::find(Fiche::$nameTable, " nom_fiche LIKE ? ", array($nom));
		if($fiche == null)
			return null;
		return $fiche;
	}
	
	/*Renommage d'une fiche action*/
	public static function renommerFiche($idFiche, $nouveauNom){
		$fiche = Fiche::recupererFiche($idFiche);
		if($fiche == null)
			return null;
		$fiche->nom_fiche = $nouveauNom;
		R::store($fiche);
		return $fiche;
	}
	
	/*Fonctions pour la suppression de fiche*/
	public static function supprimerFiche($idFiche){
		$fiche = Fiche::recupererFiche($idFiche);
		if($fiche == null){
			return null;
		}
		else{
			$deletefile = $fiche->path;
			R::trash($fiche);
			if(file_exists($deletefile)){
				unlink($deletefile);
			}
			return "ok";
		}
	}
	
	
	public static function supprimerFicheByNom($nom){
		$fiche = Fiche::recupererFicheByNom($nom);
		if($fiche == null){
			return null;
		}
		$deletefile = $fiche->path;
		if(file_exists($deletefile)){
			unlink($deletefile);
		}
		R::trash($fiche);
		return "ok";
	}
	
	/*Fonction pour lister toutes les fiches. Code en double*/
	public static function getAllFiche(){
		return R::findAll(Fiche::$nameTable);
	}
	

}
?>
