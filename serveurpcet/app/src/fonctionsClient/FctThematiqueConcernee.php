<?php
use RedBean_Facade as R;

class FctThematiqueConcernee {

	public static function formeThematiqueConcerneeArray($thematique){
		$tematiqueTab=array();
		$tematiqueTab['id']=$thematique->id;
		$tematiqueTab['nom_thematique_concernee']=$thematique->nom_thematique_concernee;
		return $tematiqueTab;
	}
	
	public static function toutesLesThematiques(){
		$thematiqueTab=array();
		$thematiques = ThematiqueConcernee::listerThematiquesConcernees();
		foreach($thematiques as $thematique){
			$thematiqueTab[]=FctThematiqueConcernee::formeThematiqueConcerneeArray($thematique);
		}
		return $thematiqueTab;
	}
	
	public static function lierActionThematique($idAction,$idThematique){
		$action = Action::getActionById($idAction);
		$thematique = ThematiqueConcernee::recupererThematiqueConcernee($idThematique);
// 		ThematiqueConcernee::
		Action::deleteThematiqueConcernee($action);
		ThematiqueConcernee::ajouterAction($thematique, $action);
		return ;
	}
}
?>
