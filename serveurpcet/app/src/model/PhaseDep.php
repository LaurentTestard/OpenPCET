<?php
use RedBean_Facade as R;

/*Cette classe g�re les phases initiales des fiches actions.
 * Cette classe diff�re de la classe phase, car les phases initiales d'une action peuvent �volu�es dans le temps.
 *Il est important de garder une trace des phases de d�part 
 * */
class PhaseDep {
	
	/*Cette classe manipule les �l�ments des tables suivantes : phasedep, fiche et phase*/
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
	
	/*Cr�ation d'une phase initiale*/
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
		
		/*Quand une phase initiale est cr��e, une phase actuelle est aussi cr��e*/
		$phase = R::dispense(PhaseDep::$nameTable3);
		$phase->nom_phase = $nomPhasedep;
		$phase->commentaires_phase = $commentairesPhasedep;
		$phase->date_debut_prevue = $dateDebutPrevuephd;
		$phase->date_fin_prevue = $dateFinPrevuephd;
		/*Les dates r�elles de d�but et de fin prennent la valeur null comme ces �l�ments n'existent pas pour les phases initiales*/
		$phase->date_debut_reelle = null;
		$phase->date_fin_reelle = null;
		$phase->description_phase = $descriptionPhasedep;
		$phase->ordre_phase = $ordrePhasedep;
		$phase->services_porteurs = $servicesPorteursphd;
		/*La pond�ration et l'vanacement d'une phase sont null au d�part comme ces �l�ments n'existent pas pour les phases initiales*/
		$phase->ponderation_phase = null;
		$phase->avancement_phase = null;
		$phase->action_id=$actionid;
		R::store($phase);
		
		return $phasedep;
	}
	
	/*Liste de toutes les phases de d�part de la BD*/
	public static function listerPhasesdep() {
		$phasesdep = R::findAll(Phasedep::$nameTable, "ORDER BY nom_phasedep ASC");
		return $phasesdep;
	}
	
	/*Fonction pour la r�cup�ration des informations des phases de d�part*/
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
	
	/*Modification d'une phase de d�part*/
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
	
	/*D�cr�mentation de l'ordre d'une phase*/
	public static function decrementerOrdrePhasedep($phasedep){
		$ordrePrecedent = $phasedep->ordre_phasedep;
		$phasedep->ordre_phasedep = $ordrePrecedent - 1;
		R::store($phasedep);
		return $phasedep;
	}
	
	/*Incr�mentation de l'ordre d'une phase*/
	public static function incrementerOrdrePhasedep($phasedep){
		$ordrePrecedent = $phasedep->ordre_phasedep;
		$phasedep->ordre_phasedep = $ordrePrecedent + 1;
		R::store($phasedep);
		return $phasedep;
	}
}
?>