<?php
use RedBean_Facade as R;
class FctEngagementThematique {
	
	/*Représentation des informations d'une thématique en tableau*/
	public static function formeEngagementThematiqueArray($engagementThematique){
		$engagement = array();
		$engagement['id'] = $engagementThematique->id;
		$engagement['code_engagement_thematique'] = $engagementThematique->code_engagement_thematique;
		$engagement['nom_engagement_thematique'] = $engagementThematique->nom_engagement_thematique;
		return $engagement;
	}
	
	/*Liste de toutes les thématiques*/
	public static function tousLesEngagements(){
		$engagementTab=array();
		$engagements = EngagementThematique::listerEngagementsThematiques();
		foreach($engagements as $engagement){
			$engagementTab[]=FctEngagementThematique::formeEngagementThematiqueArray($engagement);
		}
		return $engagementTab;
	}
}
?>