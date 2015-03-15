<?php
use RedBean_Facade as R;

/*Cette classe gère les phases initiales des fiches actions.
 * Cette classe diffère de la classe phase, car les phases initiales d'une action peuvent évoluées dans le temps.
 *Il est important de garder une trace des phases de départ 
 * */
class PhaseDep {
	
	/*Cette classe manipule les éléments des tables suivantes : phasedep, fiche et phase*/
	public static $nameTable = "phasedep";
	public static $nameTable2 = "fiche";
	public static $nameTable3 = "phase";
	
	/*Code mort ?!*/
	public static function creerFiche($nom) {
	
		$document = R::dispense(PhaseDep::$nameTable2);
		$document->nom_fiche = $nom;
		R::store($document);
	
		return $document;
	}
	
	/*Création d'une phase initiale*/
	public static function creerPhasedep($nomPhasedep,$commentairesPhasedep,$dateDebutPrevuephd,$dateFinPrevuephd,$descriptionPhasedep,$ordrePhasedep,$servicesPorteursphd,$actionid){
		$phasedep = R::dispense(Phasedep::$nameTable);
		$phasedep->nom_phasedep = $nomPhasedep;
		$phasedep->commentaires_phasedep = $commentairesPhasedep;
		$phasedep->date_debut_prevuephd = $dateDebutPrevuephd;
		$phasedep->date_fin_prevuephd = $dateFinPrevuephd;
		$phasedep->description_phasedep = $descriptionPhasedep;
		$phasedep->ordre_phasedep = $ordrePhasedep;
		$phasedep->services_porteursphd = $servicesPorteursphd;
		$phasedep->action_id=$actionid;
		R::store($phasedep);
		
		/*Quand une phase initiale est créée, une phase actuelle est aussi créée*/
		$phase = R::dispense(PhaseDep::$nameTable3);
		$phase->nom_phase = $nomPhasedep;
		$phase->commentaires_phase = $commentairesPhasedep;
		$phase->date_debut_prevue = $dateDebutPrevuephd;
		$phase->date_fin_prevue = $dateFinPrevuephd;
		/*Les dates réelles de début et de fin prennent la valeur null comme ces éléments n'existent pas pour les phases initiales*/
		$phase->date_debut_reelle = null;
		$phase->date_fin_reelle = null;
		$phase->description_phase = $descriptionPhasedep;
		$phase->ordre_phase = $ordrePhasedep;
		$phase->services_porteurs = $servicesPorteursphd;
		/*La pondération et l'vanacement d'une phase sont null au départ comme ces éléments n'existent pas pour les phases initiales*/
		$phase->ponderation_phase = null;
		$phase->avancement_phase = null;
		$phase->action_id=$actionid;
		R::store($phase);
		
		return $phasedep;
	}
	
	/*Liste de toutes les phases de départ de la BD*/
	public static function listerPhasesdep() {
		$phasesdep = R::findAll(Phasedep::$nameTable, "ORDER BY nom_phasedep ASC");
		return $phasesdep;
	}
	
	/*Fonction pour la récupération des informations des phases de départ*/
	public static function recupererPhasedep($idPhasedep) {
		$phasedep = R::findOne(Phasedep::$nameTable, " id = ? ", array($idPhasedep));
		if($phasedep == null)
			return null;
		return $phasedep;
	}
		
	public static function chercherPhasesdep($nomPhasedep) {
		$nom = "%".$nomPhasedep."%";
		$phasedep = R::find(Phasedep::$nameTable, " nom_phasedep LIKE ? ", array($nom));
		if($phasedep == null)
			return null;
		return $phasedep;
	}
	
	/*Modification d'une phase de départ*/
	public static function modifierPhasedep($tabParametres){
			$phasedep = PhaseDep::recupererPhasedep($tabParametres['id']);
			if($phasedep == null)
				return null;
			//decaspulation du tableau des nouvelles valeurs
			$nouveauNom = $tabParametres['nom_phasedep'];
			$nouveauCommentaire = $tabParametres['commentaires_phasedep'];
			$nouvelleDateDebutP = $tabParametres['date_debut_prevuephd'];
			$nouvelleDateFinP = $tabParametres['date_fin_prevuephd'];
			$nouvelleDescription = $tabParametres['description_phasedep'];
			$nouvelOrdre = $tabParametres['ordre_phasedep'];
			$nouveauxServicesPorteurs = $tabParametres['services_porteursphd'];
			
			$phasedep->nom_phasedep = $nouveauNom;
			$phasedep->commentaires_phasedep = $nouveauCommentaire;
			$phasedep->date_debut_prevuephd = $nouvelleDateDebutP;
			$phasedep->date_fin_prevuephd = $nouvelleDateFinP;
			$phasedep->description_phasedep = $nouvelleDescription;
			$phasedep->ordre_phasedep = $nouvelOrdre;
			$phasedep->services_porteursphd = $nouveauxServicesPorteurs;
			R::store($phasedep);
			
			return $phasedep;
		}

	/*Suppression d'un phase initiale*/
	public static function supprimerPhasedep($idPhasedep){
		$phasedep = Phasedep::recupererPhasedep($idPhasedep);
		if($phasedep == null)
			return null;
		R::trash($phasedep);
		return "OK";
	}
	
	/*Décrémentation de l'ordre d'une phase*/
	public static function decrementerOrdrePhasedep($phasedep){
		$ordrePrecedent = $phasedep->ordre_phasedep;
		$phasedep->ordre_phasedep = $ordrePrecedent - 1;
		R::store($phasedep);
		return $phasedep;
	}
	
	/*Incrémentation de l'ordre d'une phase*/
	public static function incrementerOrdrePhasedep($phasedep){
		$ordrePrecedent = $phasedep->ordre_phasedep;
		$phasedep->ordre_phasedep = $ordrePrecedent + 1;
		R::store($phasedep);
		return $phasedep;
	}
}
?>