<?php
use RedBean_Facade as R;

/*Le contenu de cette classe concerne la thématique d'une action*/
class EngagementThematique {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "engagementthematique";
	
	/*Création d'une thématique*/
	public static function creerEngagementThematique($codeEngagementThematique,$nomEngagementThematique){
		$engagement_thema = R::dispense(EngagementThematique::$nameTable);
		$engagement_thema->code_engagement_thematique = $codeEngagementThematique;
		$engagement_thema->nom_engagement_thematique = $nomEngagementThematique;
		
		$idEngageThema = R::store($engagement_thema);
		return $engagement_thema;
	}
	
	/*Ajout d'un objectif stratégique à une thématique*/
	public static function addObjectifStrategique($engagement_thema,$objectifStrategique){
		$engagement_thema->ownObjectifstrategique[]=$objectifStrategique;
		R::store($engagement_thema);
		return;
	}
	
	/*Liste de toutes les thématiques sur la plateforme*/
	public static function listerEngagementsThematiques() {
		$engagements_themas = R::findAll(EngagementThematique::$nameTable, "ORDER BY code_engagement_thematique ASC");
	
		return $engagements_themas;
	}
	
	/*Fonctions pour la récupération d'une thématique*/
	public static function recupererEngagementThematique($idEngagementThematique) {
		$engagement_thema = R::findOne(EngagementThematique::$nameTable, " id = ? ", array($idEngagementThematique));
	
		if($engagement_thema == null)
			return null;
	
		return $engagement_thema;
	}
	
	public static function chercherEngagementsThematique($nomEngagementThematique) {
		$nomEngagementThema = "%" . $nomEngagementThematique . "%";
		$engagements_themas = R::find(EngagementThematique::$nameTable, " nom_engagement_thematique like ? ", array($nomEngagementThema));
	
		return $engagements_themas;
	}
	
	/*Renommage d'une thématique */
	public static function renommerEngagementThematique($idEngagementThematique, $nouveauNom){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		$engagement_thema->nom_engagement_thematique = $nouveauNom;
		R::store($engagement_thema);
	
		return $engagement_thema;
	}
	
	/*Suppression d'une thématique*/
	public static function supprimerEngagementThematique($idEngagementThematique){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		R::trash($engagement_thema);
		return "OK";
	}
	
}

?>
