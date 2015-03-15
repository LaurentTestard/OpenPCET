<?php
use RedBean_Facade as R;

class FctObjectifStrategique {
	
	/*Représentation d'un objectif stratégique sous frme de tableau*/
	public static function formeObjectifStrategiqueArray($objectif){
		$objectifTab = array();
		$objectifTab['id']=$objectif->id;
		$objectifTab['code_objectif_strategique']=$objectif->code_objectif_strategique;
		$objectifTab['nom_objectif_strategique']=$objectif->nom_objectif_strategique;
		$objectifTab['engagementthematique_id']=$objectif->engagementthematique_id;
		
		/*if($objectifStrategique->engagementthematique){
			$objectifTab['engagementthematique']=FctEngagementThematique::formeEngagementThematiqueArray($objectifStrategique->engagementthematique);
		}*/
		return $objectifTab;
	}
	
	/*Liste de tous les objectifs stratégiques*/
	public static function tousLesObjectifs(){
		$objectifTab=array();
		$objectifs = ObjectifStrategique::listerObjectifsStrategiques();
		foreach($objectifs as $objectif){
			$objectifTab[]=FctObjectifStrategique::formeObjectifStrategiqueArray($objectif);
		}
		return $objectifTab;
	}
	
	/*Liste des objectifs stratégiques d'une thématique*/
	public static function getObjsparEngagement($id){
		$objectifTab=array();
		$objectifs = ObjectifStrategique::listerObjectifsStrategiquesParEngagement($id);
		foreach($objectifs as $objectif){
			$objectifTab[]=FctObjectifStrategique::formeObjectifStrategiqueArray($objectif);
		}
		return $objectifTab;
	}
	
	/*Association d'une action à un objectif stratégique*/
	public static function lierActionObjs($idaction,$idobj){
		$action=Action::getActionById($idaction);
		$objectifstrat=ObjectifStrategique::recupererObjectifStrategique($idobj);
		$res=ObjectifStrategique::addAction($objectifstrat,$action);
		
		return $res;
	}
	
	/*Association d'une action à un objectif stratégique avec code de l'action*/
	public static function lierActionObjsCAction($codeaction,$nomobj){
		$action=Action::getAction($codeaction);
		$objectifstrat=ObjectifStrategique::recupererObjsByNom($nomobj);
		$res=ObjectifStrategique::addAction($objectifstrat,$action);
	
		return $res;
	}
	
}
?>