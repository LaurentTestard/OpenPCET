<?php
use RedBean_Facade as R;

/*Cette classe gère les indicateurs des actions. Classe à revoir !*/
class Indicateur {
	
	/*Nom des tables de la base de données sur lesquelles cette classe va travailler*/
	public static $nameTable = "indicateur";
	public static $nameTable2 = "valeurindicateur";
	
	/*Créatin d'un indcateur*/
	public static function creerIndicateur($nomIndicateur,$idEnjeu){
		$indicateur = R::dispense(Indicateur::$nameTable);
		$indicateur->nom_indicateur= $nomIndicateur;
		$indicateur->id_enjeu= $idEnjeu;
		$idIndicateur = R::store($indicateur);
		return $indicateur;
	}
	
	/*Ajout d'une valeur pour un indcateur d'action*/
	public static function ajoutIndicateurAction($nomIndicateur,$valeur,$annee,$idAction){
		$indicateurAction = R::dispense(Indicateur::$nameTable2);
		$indicateurAction->nom_indicateur= $nomIndicateur;
		$indicateurAction->valeur = $valeur;
		$indicateurAction->annee = $annee;
		$indicateurAction->idAction = $idAction;
		$idIndicateurAction = R::store($indicateurAction);
		return $indicateurAction;
	}
	
	/*Liste de tous les indicateurs*/
	public static function listerIndicateurs() {
		$indicateurs = R::findall(Indicateur::$nameTable, "ORDER BY nom_indicateur ASC");
		return $indicateurs;
	}
	
	/*Fonction permettant la mise à jour d'un indicateur dans la BD*/
	public static function modifierIndicateur($tabParametres){
		$indicateur = Indicateur::recupererIndicateur($tabParametres['id']);
		//$phase = Phase::recupererPhase($tabParametres['id']);
		if($indicateur == null)
			return null;
		//decaspulation du tableau des nouvelles valeurs
		$nouveauNom = $tabParametres['nom_indicateur'];
		$nouvelleValeurActuelle = $tabParametres['valeur_actuelle'];
		$nouvelleValeurObjectif = $tabParametres['valeur_objectif'];
		$nouvelleDescription = $tabParametres['description_indicateur'];
		
	
		$indicateur->nom_indicateur = $nouveauNom;
		$indicateur->valeur_actuelle = $nouvelleValeurActuelle;
		$indicateur->valeur_objectif = $nouvelleValeurObjectif;
		$indicateur->description_indicateur = $nouvelleDescription;
		
		R::store($indicateur);
		return $indicateur;
	}




	public static function recupererIndicateur($idIndicateur) {
		$indicateur = R::findOne(Indicateur::$nameTable, " id = ? ", array($idIndicateur));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function recupererIndicateurByNom($nameIndicateur) {
		$indicateur = R::findOne(Indicateur::$nameTable, "  = ? ", array($nameIndicateur));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function chercherIndicateurs($nomIndicateur) {
		$nom = "%".$nomIndicateur."%";
		$indicateur = R::find(Indicateur::$nameTable, " nom_indicateur LIKE ? ", array($nom));
		if($indicateur == null)
			return null;
		return $indicateur;
	}
	
	public static function renommerIndicateur($idIndicateur, $nouveauNom){
		$indicateur = Indicateur::recupererIndicateur($idIndicateur);
		if($indicateur == null)
			return null;
		$indicateur->nom_indicateur = $nouveauNom;
		R::store($indicateur);
		return $indicateur;
	}
	
	public static function supprimerIndicateur($idIndicateur){
		$indicateur = Indicateur::recupererIndicateur($idIndicateur);
		if($indicateur == null)
			return null;
		R::trash($indicateur);
		return "OK";
	}
}
?>