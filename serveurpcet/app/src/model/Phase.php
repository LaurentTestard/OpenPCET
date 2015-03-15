<?php
use RedBean_Facade as R;

/*Cette classe gère les phases actuelles des actions dans la BD*/
class Phase {
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "phase";
	
	/*Création d'une phase dans la BD*/
	public static function creerPhase($nomPhase,$commentairesPhase,$dateDebutPrevue,$dateFinPrevue,$dateDebutReelle,$dateFinReelle,$descriptionPhase,$ordrePhase,$servicesPorteurs,$ponderationPhase,$avancementPhase){
		$phase = R::dispense(Phase::$nameTable);
		$phase->nom_phase = $nomPhase;
		$phase->commentaires_phase = $commentairesPhase;
		$phase->date_debut_prevue = date($dateDebutPrevue);
		$phase->date_fin_prevue = date($dateFinPrevue);
		/*Si les dates réelles de début et de fin sont null alors, elles prennent les valeurs des dates prévues*/
		if($dateDebutReelle==null){
			$phase->date_debut_reelle = date($dateDebutPrevue);
		}
		else{
			$phase->date_debut_reelle=$dateDebutReelle;
		}
		if($dateFinReelle==null){
			$phase->date_debut_reelle = date($dateFinPrevue);
		}
		else{
			$phase->date_fin_reelle = $dateFinReelle;
		}
		$phase->description_phase = $descriptionPhase;
		$phase->ordre_phase = $ordrePhase;
		$phase->services_porteurs = $servicesPorteurs;
		$phase->ponderation_phase = $ponderationPhase;
		$phase->avancement_phase = $avancementPhase;
		R::store($phase);
		return $phase;
	}
	
	/*Liste de toutes les phases de la BD*/
	public static function listerPhases() {
		$phases = R::findAll(Phase::$nameTable, "ORDER BY nom_phase ASC");
		return $phases;
	}
	
	/*Fonctions pour la récupération des informations des phases*/
	public static function recupererPhase($idPhase) {
		$phase = R::findOne(Phase::$nameTable, " id = ? ", array($idPhase));
		if($phase == null)
			return null;
		return $phase;
	}
		
	public static function chercherPhases($nomPhase) {
		$nom = "%".$nomPhase."%";
		$phase = R::find(Phase::$nameTable, " nom_phase LIKE ? ", array($nom));
		if($phase == null)
			return null;
		return $phase;
	}
	
	/*Modification d'un phase dans la BD*/
	public static function modifierPhase($tabParametres){
		$phase = Phase::recupererPhase($tabParametres['id']);
		if($phase == null)
			return null;
		//decaspulation du tableau des nouvelles valeurs
		$nouveauNom = $tabParametres['nom_phase'];
		$nouveauCommentaire = $tabParametres['commentaires_phase'];
		$nouvelleDateDebutP = $tabParametres['date_debut_prevue'];
		$nouvelleDateFinP = $tabParametres['date_fin_prevue'];
		$nouvelleDateDebutR = $tabParametres['date_debut_reelle'];
		$nouvelleDateFinR = $tabParametres['date_fin_reelle'];
		$nouvelleDescription = $tabParametres['description_phase'];
		$nouvelOrdre = $tabParametres['ordre_phase'];
		$nouveauxServicesPorteurs = $tabParametres['services_porteurs'];
		$nouvellePonderation = $tabParametres['ponderation_phase'];
		$nouvelAvancement = $tabParametres['avancement_phase'];
		
		$phase->nom_phase = $nouveauNom;
		$phase->commentaires_phase = $nouveauCommentaire;
		$phase->date_debut_prevue = $nouvelleDateDebutP;
		$phase->date_fin_prevue = $nouvelleDateFinP;
		$phase->date_debut_reelle = $nouvelleDateDebutR;
		$phase->date_fin_reelle = $nouvelleDateFinR;
		$phase->description_phase = $nouvelleDescription;
		$phase->ordre_phase = $nouvelOrdre;
		$phase->services_porteurs = $nouveauxServicesPorteurs;
		$phase->ponderation_phase = $nouvellePonderation;
		$phase->avancement_phase = $nouvelAvancement;
		R::store($phase);
		return $phase;
	}
	
	/*Suppression d'une phase*/
	public static function supprimerPhase($idPhase){
		$phase = Phase::recupererPhase($idPhase);
		if($phase == null)
			return null;
		R::trash($phase);
		return "OK";
	}
	
	/*Décrémentation de l'ordre d'une phase*/
	public static function decrementerOrdrePhase($phase){
		$ordrePrecedent = $phase->ordre_phase;
		$phase->ordre_phase = $ordrePrecedent - 1;
		R::store($phase);
		return $phase;
	}
	
	/*Incrémentation de l'ordre d'une phase*/
	public static function incrementerOrdrePhase($phase){
		$ordrePrecedent = $phase->ordre_phase;
		$phase->ordre_phase = $ordrePrecedent + 1;
		R::store($phase);
		return $phase;
	}
}
?>