<?php
use RedBean_Facade as R;

class Action {
	
/*Nom de la table de la base de données sur laquelle cette classe va travailler*/	
public static $nameTable = "action";
	
	//Fonctions pour créer une action dans la BD
	public static function creerAction($codeAction,$nomAction,$estAttenuation,$estAdaptation,$estCommunication,$estFormation,$estAppuiTechnique,$estAppuiFinancier,$contexteAction,$objectifsQuantitatifs,$gainsGES,$gainsEnergie,$gainsCO2,$maitriseOuvrage,$referentsAssocies,$budgetTotal,$budgetConsomme){
		return Action::creerActionP($codeAction,$nomAction,$estAttenuation,$estAdaptation,$estCommunication,$estFormation,$estAppuiTechnique,$estAppuiFinancier, date('Y-m-d H:i:s')  ,$contexteAction,$objectifsQuantitatifs,$gainsGES,$gainsEnergie,$gainsCO2,$maitriseOuvrage,$referentsAssocies,$budgetTotal,$budgetConsomme);
	}
	
	private static function creerActionP($codeAction,$nomAction,$estAttenuation,$estAdaptation,$estCommunication,$estFormation,$estAppuiTechnique,$estAppuiFinancier,$dateMiseAJour,$contexteAction,$objectifsQuantitatifs,$gainsGES,$gainsEnergie,$gainsCO2,$maitriseOuvrage,$referentsAssocies,$budgetTotal,$budgetConsomme){
		$action = R::dispense(Action::$nameTable);
		$action->code_action= $codeAction;
		$action->nom_action = $nomAction;
		$action->est_attenuation = $estAttenuation;
		$action->est_adaptation = $estAdaptation;
		$action->est_communication = $estCommunication;
		$action->est_formation = $estFormation;
		$action->est_appui_technique = $estAppuiTechnique;
		$action->est_appui_financier = $estAppuiFinancier;
		$action->date_mise_a_jour = $dateMiseAJour;
		$action->contexte_action = $contexteAction;
		$action->objectifs_quantitatifs=$objectifsQuantitatifs;
		$action->gains_ges = $gainsGES;
		$action->gains_energie = $gainsEnergie;
		$action->gains_co2 = $gainsCO2;
		$action->maitrise_ouvrage=$maitriseOuvrage;
		$action->referents_associes = $referentsAssocies;
		$action->budget_total = $budgetTotal;
		$action->budget_consomme = $budgetConsomme;
		
		$idAction = R::store($action);
		return $action;
	}
	
	// ------ METHODES RELATIVES A LA CLASSE -------
	public static function getActionById($idAction){
		$action = R::findOne(Action::$nameTable, ' id = ?  ', array($idAction));
		return $action;
	}
	
	public static function getAction($codeAction){
		$action = R::findOne(Action::$nameTable, ' code_action = ?  ', array($codeAction));
		return $action;		
	}
	
	public static function getAllAction(){
		$actions = R::findAll(Action::$nameTable, "ORDER BY code_action ASC");
		return $actions;
	}
	
	/*Fonction qui effectue les changements lorsqu'une action est modifiée*/
	public static function modifierAction($tabParamVerifies){
		// On decapsule le tableau et on met a jour les champs concernes
		$idAction= $tabParamVerifies['id'];
		$action = Action::getActionById($idAction);
		if($action == null)
			return null;
		
		if (isset($tabParamVerifies['code_action'])) {
			$codeAction = $tabParamVerifies['code_action'];
			$action->code_action = $codeAction;
		}
		if (isset($tabParamVerifies['nom_action'])) {
			$nomAction = $tabParamVerifies['nom_action'];
			$action->nom_action = $nomAction;
		}
		if (isset($tabParamVerifies['est_attenuation'])){
			$estAttenuation = $tabParamVerifies['est_attenuation'];
			$action->est_attenuation = $estAttenuation;
		}
		if (isset($tabParamVerifies['est_adaptation'])){
			$estAdaptation = $tabParamVerifies['est_adaptation'];
			$action->est_adaptation = $estAdaptation;
		}
		if (isset($tabParamVerifies['est_communication'])){
			$estCommunication = $tabParamVerifies['est_communication'];
			$action->est_communication = $estCommunication;
		}
		if (isset($tabParamVerifies['est_formation'])){
			$estFormation = $tabParamVerifies['est_formation'];
			$action->est_formation = $estFormation;
		}
		if (isset($tabParamVerifies['est_appui_technique'])){
			$estAppuiTechnique = $tabParamVerifies['est_appui_technique'];
			$action->est_appui_technique = $estAppuiTechnique;
		}
		if (isset($tabParamVerifies['est_appui_financier'])){
			$estAppuiFinancier = $tabParamVerifies['est_appui_financier'];
			$action->est_appui_financier = $estAppuiFinancier;
		}
		if (isset($tabParamVerifies['date_mise_a_jour'])){
			$dateMiseAJour = $tabParamVerifies['date_mise_a_jour'];
			$action->date_mise_a_jour = $dateMiseAJour;
		}
		if (isset($tabParamVerifies['contexte_action'])){
			$contexteAction = $tabParamVerifies['contexte_action'];
			$action->contexte_action = $contexteAction;
		}
		if (isset($tabParamVerifies['objectifs_quantitatifs'])){
			$objectifsQuantitatifsAction = $tabParamVerifies['objectifs_quantitatifs'];
			$action->objectifs_quantitatifs = $objectifsQuantitatifsAction;
		}
		if (isset($tabParamVerifies['gains_ges'])) {
			$gainsGES = $tabParamVerifies['gains_ges'];
			$action->gains_ges = $gainsGES;
		}
		if (isset($tabParamVerifies['gains_energie'])) {
			$gainsEnergie = $tabParamVerifies['gains_energie'];
			$action->gains_energie = $gainsEnergie;
		}
		if (isset($tabParamVerifies['gains_co2'])){
			$gainsCO2 = $tabParamVerifies['gains_co2'];
			$action->gains_co2 = $gainsCO2;
		}
		if (isset($tabParamVerifies['maitrise_ouvrage'])){
			$maitriseDouvrageAction = $tabParamVerifies['maitrise_ouvrage'];
			$action->maitrise_ouvrage = $maitriseDouvrageAction;
		}
		if (isset($tabParamVerifies['referents_associes'])){
			$referentsAssociesAction = $tabParamVerifies['referents_associes'];
			$action->referents_associes = $referentsAssociesAction;
		}
		//Mise a jour de l'objectif strategique de l'action
		if (isset($tabParamVerifies['objectifstrategique']['nom_objectif_strategique'])){
			$nomObjectifStrategique = $tabParamVerifies['objectifstrategique']['nom_objectif_strategique'];
			$obj_stratConcerne = $action->objectifstrategique;
			$new_obj_strat= ObjectifStrategique::renommerObjectifStrategique($obj_stratConcerne->id, $nomObjectifStrategique);
			$action->objectifstrategique = $new_obj_strat;
		}

		//Thematique concernee
		if (isset($tabParamVerifies['thematiqueconcernee']['nom_thematique_concernee'])){
			$nomThematiqueConcernee = $tabParamVerifies['thematiqueconcernee']['nom_thematique_concernee'];
			$themaConcernee = $action->thematiqueconcernee;
			$new_thema_concernee= ThematiqueConcernee::renommerThematiqueConcernee($themaConcernee->id, $nomThematiqueConcernee);
			$actionConcernee->thematiqueconcernee=$new_thema_concernee;
		}
		
		//Utilisateur
		if (isset($tabParamVerifies['utilisateurs'])){
			$utilisateurs = $tabParamVerifies['utilisateurs'];
			foreach ($utilisateurs as $utilisateur){
				$newUtilisateur = Utilisateur::recupererUtilisateur($utilisateur['id']);
				$newUtilisateur->nom_utilisateur = $utilisateur['nom_utilisateur'];
				$newUtilisateur->prenom_utilisateur = $utilisateur['prenom_utilisateur'];
				$newUtilisateur->role_utilisateur = $utilisateur['role_utilisateur'];
				$newUtilisateur->organisation = $utilisateur['organisation'];
				$newUtilisateur->email = $utilisateur['email'];
				$newUtilisateur->tel_interne = $utilisateur['tel_interne'];
				$newUtilisateur->tel_standard = $utilisateur['tel_standard'];
				R::Store($newUtilisateur);
			}
		}		
		R::store($action);
		return $action;
	}
	
	public static function modifierBudget($idAction, $nouveauBudgetTotal, $nouveauBudgetConsomme){
		$action = Action::getActionById($idAction);
		if($action == null)
			return null;
		$action->budget_total = $nouveauBudgetTotal;
		$action->budget_consomme = $nouveauBudgetConsomme;
		R::store($action);
		return $action;
	} 
	// ------ FIN: METHODES RELATIVES A LA CLASSE -------
	
	
	// ----------- RELATIONS ENTRE CLASSES --------------
	
	
	// RELATION DOCUMENT
	public static function deleteDocument($action,$document){
		$newDocument = array();
		foreach($action->sharedDocument as $documentIn){
			if($documentIn->id!=$document->id){
				$newDocument[]=$documentIn;
			}
		}
		$action->sharedDocument=$newDocument;
		R::store($action);
		return;
	}
	
	public static function addDocument($action,$document){
		$action->sharedDocument[]=$document;
		R::store($action);
		return;
	}
	
	public static function getDocumentNonLieDocument($document){
		$actions = R::findAll(Action::$nameTable);
		
		$actionNonLie=array();
		
		foreach($actions as $action){
			$lie=false;
			foreach($action->sharedDocument as $documentAction){
				if($document->id==$documentAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	
	// RELATION FICHE
	public static function deleteFiche($action,$fiche){
		$newFiche = array();
		foreach($action->sharedFiche as $ficheIn){
			if(!$ficheIn->id==$fiche->id){
				$newFiche[]=$ficheIn;
			}
		}
		$action->sharedFiche=$newFiche;
		R::store($action);
		return;
	}
	
	public static function addFiche($action,$fiche){
		$action->sharedFiche[]=$fiche;
		R::store($action);
		return;
	}
	
	// RELATION Utilisateur
	
	public static function deleteUtilisateur($action,$utilisateur){
		$newUtilisateur = array();
		foreach($action->sharedUtilisateur as $utilisateurIn){
			if(!$utilisateurIn->id==$utilisateur->id){
				$newUtilisateur[]=$utilisateurIn;
			}
		}
		$action->sharedUtilisateur=$newUtilisateur;
		R::store($action);
		return;
	}
	
	public static function addUtilisateur($action,$utilisateur){
		$action->sharedUtilisateur[]=$utilisateur;
		R::store($action);
		return;
	}
	
	public static function getParUtilisateurNonLie($utilisateur){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->sharedUtilisateur as $utilisateurAction){
				if($utilisateur->id==$utilisateurAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	// RELATION PARTENAIRE
	public static function addPartenaire($action,$partenaire){
		$action->sharedPartenaire[]=$partenaire;
		R::store($action);
		return;
	}
	
	public static function getParPartenaireNonLie($partenaire){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->sharedPartenaire as $partenaireAction){
				if($partenaire->id==$partenaireAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	// RELATION CR ACTION
	
	public static function deleteCrAction($action,$craction){
		$newCrAction = array();
		foreach($action->ownCraction as $cractionIn){
			if(!$cractionIn->id==$craction->id){
				$newCrAction[]=$cractionIn;
			}
		}
		$action->ownCraction=$newCrAction;
		R::store($action);
		return;
	}
	
	public static function addCrAction($action,$crAction){
		$action->ownCraction[]=$crAction;
		R::store($action);
		return;
	}
	
	public static function getParCrActionNonLie($crAction){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->ownCraction as $crActionAction){
				if($crAction->id==$crActionAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	//  Relation Objectifenjeu
	
	public static function deleteObjectifStrategique($action,$objectif){
		$newObjectif = array();
		foreach($action->ownObjectif as $objectif){
			if(!$objectif->id==$objectif->id){
				$newObjectif[]=$objectif;
			}
		}
		$action->ownObjectif=$newObjectif;
		R::store($action);
		return;
	}
	
	public static function addObjectifStrategique($action,$objectif){
		$action->ownObjectif[]=$objectif;
		R::store($action);
		return;
	}
	
	public static function getParObjectifStrategiqueNonLie($objectif){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->ownObjectif as $objectifAction){
				if($objectif->id==$objectifAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	//	RELATION PHASE
	
	public static function deletePhase($action,$phase){
		$newPhase = array();
		foreach($action->ownPhase as $phaseIn){
			if(!$phaseIn->id==$phase->id){
				$newPhase[]=$phaseIn;
			}
		}
		$action->ownPhase=$newPhase;
		R::store($action);
		return;
	}
	
	public static function addPhase($action,$phase){
		$action->ownPhase[]=$phase;
		R::store($action);
		return;
	}
	
	public static function getParPhaseNonLie($phase){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->ownPhase as $phaseAction){
				if($phase->id==$phaseAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	
	//	RELATION PHASEDEP
	
	public static function deletePhasedep($action,$phasedep){
		$newPhasedep = array();
		foreach($action->ownPhasedep as $phasedepIn){
			if(!$phasedepIn->id==$phasedep->id){
				$newPhasedep[]=$phasedepIn;
			}
		}
		$action->ownPhasedep=$newPhasedep;
		R::store($action);
		return;
	}
	
	public static function addPhasedep($id,$phasedep){
		$action=Action::getActionById($id);
		$action->ownPhasedep[]=$phasedep;
		R::store($action);
		return;
	}
	
	public static function getParPhasedepNonLie($phasedep){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->ownPhasedep as $phasedepAction){
				if($phasedep->id==$phasedepAction->id){
					$lie=true;
				}
				}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	//	RELATION FINANCEUR
	
	public static function deleteFinanceur($action,$financeuraction){
		$newFinanceur = array();
		foreach($action->ownFinanceuraction as $financeurIn){
			if(!$financeurIn->id==$financeuraction->id){
				$newFinanceur[]=$financeurIn;
			}
		}
		$action->ownFinanceuraction=$newFinanceur;
		R::store($action);
		return;
	}
	
	public static function addFinanceurAction($action,$financeurAction){
		$action->ownFinanceuraction[]=$financeurAction;
		R::store($action);
		return;
	}
	
	public static function getParFinanceurNonLie($financeurAction){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->ownFinanceuraction as $financeurActionAction){
				if($financeurAction->id==$financeurActionAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	// RELATION MOTCLEF
	
	public static function deleteMotClef($action,$motclef){
		unset($action->sharedMotclef[$motclef->id]);
		R::store($action);
		return;
	}
	
	public static function addMotClef($action,$motclef){
		$action->sharedMotclef[]=$motclef;
		R::store($action);
		return;
	}
	
	public static function getParMotClefNonLie($motclef){
		$actions = R::findAll(Action::$nameTable);
		$actionNonLie=array();
		foreach($actions as $action){
			$lie=false;
			foreach($action->sharedMotclef as $motclefAction){
				if($motclef->id==$motclefAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$actionNonLie[]=$action;
			}
		}
		return $actionNonLie;
	}
	
	

	public static function deletePartenaire($action,$partenaire){
		unset($action->sharedPartenaire[$partenaire->id] );
		R::store($action);
		return;
	}
	
	public static function deleteThematiqueConcernee($action){
		if($action->thematiqueconcernee!=null){
			$action->thematiqueconcernee!=null;
		}
		R::store($action);
	}
	
	public static function aDroitDeModification($action,$utilisateur){
		if(Utilisateur::estChefDeProjetParUtilisateur($utilisateur)){
			return true;
		}
		foreach($action->sharedUtilisateur as $utilisateurAction){
			if($utilisateur->id==$utilisateurAction->id){
				return true;
			}
		}
		return false;
	}
}
?>
