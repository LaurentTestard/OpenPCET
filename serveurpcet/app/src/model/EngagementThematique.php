<?php
use RedBean_Facade as R;

/*Le contenu de cette classe concerne la th�matique d'une action*/
class EngagementThematique {
	
	/*Nom de la table de la base de donn�es sur laquelle cette classe va travailler*/
	public static $nameTable = "engagementthematique";
	
	/*Cr�ation d'une th�matique*/
	public static function creerEngagementThematique($codeEngagementThematique,$nomEngagementThematique){
		$engagement_thema = R::dispense(EngagementThematique::$nameTable);
		$engagement_thema->code_engagement_thematique = $codeEngagementThematique;
		$engagement_thema->nom_engagement_thematique = $nomEngagementThematique;
		
		$idEngageThema = R::store($engagement_thema);
		return $engagement_thema;
	}
	
	/*Ajout d'un objectif strat�gique � une th�matique*/
	public static function addObjectifStrategique($engagement_thema,$objectifStrategique){
		$engagement_thema->ownObjectifstrategique[]=$objectifStrategique;
		R::store($engagement_thema);
		return;
	}
	
	/*Liste de toutes les th�matiques sur la plateforme*/
	public static function listerEngagementsThematiques() {
		$engagements_themas = R::findAll(EngagementThematique::$nameTable, "ORDER BY code_engagement_thematique ASC");
	
		return $engagements_themas;
	}
	
	/*Fonctions pour la r�cup�ration d'une th�matique*/
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
	
	/*Renommage d'une th�matique */
	public static function renommerEngagementThematique($idEngagementThematique, $nouveauNom){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		$engagement_thema->nom_engagement_thematique = $nouveauNom;
		R::store($engagement_thema);
	
		return $engagement_thema;
	}
	
	/*Suppression d'une th�matique*/
	public static function supprimerEngagementThematique($idEngagementThematique){
		$engagement_thema = EngagementThematique::recupererEngagementThematique($idEngagementThematique);
	
		if($engagement_thema == null)
			return null;
	
		R::trash($engagement_thema);
		return "OK";
	}
	
}

?>
