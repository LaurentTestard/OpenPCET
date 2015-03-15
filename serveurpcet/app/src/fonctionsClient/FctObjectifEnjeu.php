<?php
use RedBean_Facade as R;

class FctObjectifEnjeu {
	
	public static function formeObjectifEnJeuArray($objectifEnJeu){
		$objectifTab = array();
		$objectifTab['id']=$objectifEnJeu->id;
		$objectifTab['nom_objectif_enjeu']=$objectifEnJeu->nom_objectif_enjeu;

		
		//Objectifenjeu
		$indicateursRes = array();
		foreach($objectifEnJeu->ownIndicateur as $indicateur){
			$indicateursRes[]=FctIndicateur::formeIndicateurArray($indicateur);
		}
		$objectifTab['indicateur']=$indicateursRes;		
		
		return $objectifTab;
	}
	
	public static function listerObjectifsEnjeuxIdAction($idAction){
		$objectifsEnjeux = ObjectifEnjeu::getObjectifsEnjeuxByIdAction($idAction);
		$objectifsEnjeuxAEncoder = array();
		if($objectifsEnjeux == null)
			return $objectifsEnjeuxAEncoder;
		
	
		foreach ($objectifsEnjeux as $objectifEnjeu){
			$objectifEnjeuAEncoder=array();
			$objectifEnjeuAEncoder['id']=$objectifEnjeu->id;
			$objectifEnjeuAEncoder['nom_objectif_enjeu']=$objectifEnjeu->nom_objectif_enjeu;
			$objectifsEnjeuxAEncoder[]=$objectifEnjeuAEncoder;
		}
	
		return $objectifsEnjeuxAEncoder;
	}
	//TODO : a tester
	/* tous les objectifsEnjeux d'une fiche action*/
	public static function listerObjectifsEnjeuxAction($codeAction){
		$actions = Action::getAction($codeAction);
		$objectifsEnjeux = $actions->ownObjectif;
		
		$objectifsEnjeuxAEncoder = array();
		
		foreach ($objectifsEnjeux as $objectifEnjeu){
			$objectifEnjeuAEncoder=array();
			$objectifEnjeuAEncoder['id']=$objectifEnjeu->id;
			$objectifEnjeuAEncoder['nom_objectif_enjeu']=$objectifEnjeu->nom_objectif_enjeu;
			$objectifsEnjeuxAEncoder[]=$objectifEnjeuAEncoder;
		}
		
		return $objectifsEnjeuxAEncoder;
	}
	
	
	
	
	
	

}
?>