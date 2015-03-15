<?php

use RedBean_Facade as R;
use Symfony\Component\Validator\Constraints\Date;
class FctAction {
	
	/*Création d'une nouvelle action*/
	public static function ajoutAction($action) {
		$nouvelleaction = Action::creerAction($action->code_action,$action->nom_action,$action->est_attenuation,$action->est_adaptation,$action->est_communication,$action->est_formation,$action->est_appui_technique,$action->est_appui_financier,$action->contexte_action,$action->objectifs_quantitatifs,$action->gains_ges,$action->gains_energie,$action->gains_co2,$action->maitrise_ouvrage,$action->referents_associes,$action->budget_total,$action->budget_consomme);
		/*Association de l'action avec un bjectif stratégique*/
		$objs=ObjectifStrategique::recupererObjsByNom($action->objectifstrategique);
		$ex=ObjectifStrategique::addAction($objs,$nouvelleaction);
	}
	
	/*Verrification du responsable d'une action*/
	public static function estResponsable($utilisateur,$action){
		foreach($action->sharedUtilisateur as $utilisateurIn){
			if($utilisateurIn->id == $utilisateur->id){
				return true;
			}
		}
		return false;
	}
	
	/*???? Rôle de cette fonction à définir ????*/
	public static function ajoutAttributEdit($idUtilisateur,$idAction,$tableau){
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		$action = Action::getActionById($idAction);
		if(Utilisateur::estChefDeProjetParUtilisateur($utilisateur) || FctAction::estResponsable($utilisateur,$action)){
			$tableau['editable']=true;
			return $tableau;
		}
		$tableau['editable']=false;
		return $tableau;
	}
	
	/*Liste de toutes les actions*/
	public static function listerActions() {
		$actions = Action::getAllAction();
		
		$actionsAEncoder = array();
		
		foreach ($actions as $action){
			//Recuperation des phases de l'action courante
			$phases = $action->ownPhase;
			//Calcul des dates de debut, de fin et l'avancement de l'action
			$dateDebutCalculee= FctAction::calculerDateDeDebut($phases);
			$dateFinCalculee =  FctAction::calculerDateDeFin($phases);
			$avancementCalcule =  FctAction::calculerAvancement($phases);
			//Remplissage du tableau de l'action courante pour encodage
			$actionAEncoder=array();
			$actionAEncoder['id']=$action->id;
			$actionAEncoder['code_action']=$action->code_action;
			$actionAEncoder['nom_action']=$action->nom_action;
			// Gestion des mots clefs attaches a l'action
			$lesmotsclefs = $action->sharedMotclef;
			$mcAEnvoyer=array();
			foreach ($lesmotsclefs as $lemotclef){
				$mcAEnvoyer[]=FctMotClef::formeMotClefArray($lemotclef);
			}
			$actionAEncoder['mots_clefs']=$mcAEnvoyer;
			
			
			// Objectif strategique
			$actionAEncoder['objectif_strategique']=$action->objectifstrategique->nom_objectif_strategique;
			// Engagement Thematique
			$obj_strat = ObjectifStrategique::recupererObjectifStrategique($action->objectifstrategique->id);
			$actionAEncoder['engagement_thematique']=$obj_strat->engagementthematique->nom_engagement_thematique;
			
			$actionAEncoder['date_debut']=$dateDebutCalculee;
			$actionAEncoder['date_fin']=$dateFinCalculee;
			$actionAEncoder['avancement']=$avancementCalcule;
			$actionsAEncoder[]=$actionAEncoder;
		}
		
		return $actionsAEncoder;	
	}
	
	/*Liste des actions d'un utilisateur responsable d'action*/
	public static function listerActionsResponsable($idUtilisateur) {
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		$actions = $utilisateur->sharedAction;
		
		$actionsAEncoder = array();
		
		foreach ($actions as $action){
			//Recuperation des phases de l'action courante
			$phases = $action->ownPhase;
			//Calcul des dates de debut, de fin et l'avancement de l'action
			$dateDebutCalculee=  FctAction::calculerDateDeDebut($phases);
			$dateFinCalculee =  FctAction::calculerDateDeFin($phases);
			$avancementCalcule =  FctAction::calculerAvancement($phases);
			//Remplissage du tableau de l'action courante pour encodage
			$actionAEncoder=array();
			$actionAEncoder['id']=$action->id;
			$actionAEncoder['code_action']=$action->code_action;
			$actionAEncoder['nom_action']=$action->nom_action;
			// Gestion des mots clefs attaches a l'action
			$lesmotsclefs = $action->sharedMotclef;
			$mcAEnvoyer=array();
			foreach ($lesmotsclefs as $lemotclef){
				$mcAEnvoyer[]=FctMotClef::formeMotClefArray($lemotclef);
			}
			$actionAEncoder['mots_clefs']=$mcAEnvoyer;
			//Pas besoin d'afficher ces informations
			// Objectif strategique
			$actionAEncoder['objectif_strategique']=$action->objectifstrategique->nom_objectif_strategique;
			// Engagement Thematique
			$obj_strat = ObjectifStrategique::recupererObjectifStrategique($action->objectifstrategique->id);
			$actionAEncoder['engagement_thematique']=$obj_strat->engagementthematique->nom_engagement_thematique;
			$actionAEncoder['date_debut']=$dateDebutCalculee;
			$actionAEncoder['date_fin']=$dateFinCalculee;
			$actionAEncoder['avancement']=$avancementCalcule;
			$actionsAEncoder[]=$actionAEncoder;
		}
		
		return $actionsAEncoder;	
	}
	
	/*Liste les phases actuelles d'une action*/
	public static function listerPhasesAction($idAction){
		$action = Action::getActionById($idAction);
		$phases = array();
		foreach ($action->ownPhase as $phaseAction){
			$detailsPhase = array();
			$detailsPhase['id'] = $phaseAction->id;
			$detailsPhase['nom_phase'] = $phaseAction->nom_phase;
			$detailsPhase['commentaires_phase'] = $phaseAction->commentaires_phase;
			$detailsPhase['date_debut_prevue'] = $phaseAction->date_debut_prevue;
			$detailsPhase['date_fin_prevue'] = $phaseAction->date_fin_prevue;
			$detailsPhase['date_debut_reelle'] = $phaseAction->date_debut_reelle;
			$detailsPhase['date_fin_reelle'] = $phaseAction->date_fin_reelle;
			$detailsPhase['description_phase'] = $phaseAction->description_phase;
			$detailsPhase['ordre_phase'] = $phaseAction->ordre_phase;
			$detailsPhase['services_porteurs'] = $phaseAction->services_porteurs;
			$detailsPhase['ponderation_phase'] = $phaseAction->ponderation_phase;
			$detailsPhase['avancement_phase'] = $phaseAction->avancement_phase;
			$phases[] = $detailsPhase;
		}
		return $phases;
	}
	
	/*Liste les phases de départs d'une action*/
	public static function listerPhasesDepAction($idAction){
		$action = Action::getActionById($idAction);
		$phasesdep = array();
		foreach ($action->ownPhasedep as $phasedepAction){
			$detailsPhasedep = array();
			$detailsPhasedep['id'] = $phasedepAction->id;
			$detailsPhasedep['nom_phasedep'] = $phasedepAction->nom_phasedep;
			$detailsPhasedep['commentaires_phasedep'] = $phasedepAction->commentaires_phasedep;
			$detailsPhasedep['date_debut_prevuephd'] = $phasedepAction->date_debut_prevuephd;
			$detailsPhasedep['date_fin_prevuephd'] = $phasedepAction->date_fin_prevuephd;
			$detailsPhasedep['description_phasedep'] = $phasedepAction->description_phasedep;
			$detailsPhasedep['ordre_phasedep'] = $phasedepAction->ordre_phasedep;
			$detailsPhasedep['services_porteursphd'] = $phasedepAction->services_porteursphd;
			$phasesdep[] = $detailsPhasedep;
		}
		return $phasesdep;
	}
	
	/*Modification du budget*/
	public static function modifierBudget($idAction, $nouveauBudgetTotal, $nouveauBudgetConsomme, $loginUtilisateur){
		$action = Action::getActionById($idAction);
		$utilisateur = Utilisateur::getUtilisateur($loginUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		$ancienBudgetTotal = $action->budget_total;
		$ancienBudgetConsomme = $action->budget_consomme;
		$nbModif = 0;
		if($ancienBudgetTotal != $nouveauBudgetTotal && $ancienBudgetConsomme != $nouveauBudgetConsomme){
			$nbModif = 2;
			$description = "Ancien budget total : ".$ancienBudgetTotal." - Nouveau budget total : ".$nouveauBudgetTotal." - Ancien budget consommÃ© : ".$ancienBudgetConsomme." - Nouveau budget consommÃ© : ".$nouveauBudgetConsomme;
		}elseif ($ancienBudgetTotal != $nouveauBudgetTotal && $ancienBudgetConsomme == $nouveauBudgetConsomme){
			$nbModif = 1;
			$description = "Ancien budget total : ".$ancienBudgetTotal." - Nouveau budget total : ".$nouveauBudgetTotal;
		}elseif ($ancienBudgetTotal == $nouveauBudgetTotal && $ancienBudgetConsomme != $nouveauBudgetConsomme){
			$nbModif = 1;
			$description = "Ancien budget consommÃ© : ".$ancienBudgetConsomme." - Nouveau budget consommÃ© : ".$nouveauBudgetConsomme;
		}
		if($nbModif != 0){
			$nouvelleAction = Action::modifierBudget($idAction, $nouveauBudgetTotal, $nouveauBudgetConsomme);
			if($nouvelleAction == null)
				return null;
			$dateModif = date('Y-m-d H:i:s');
			$typeModif = "Modification du budget de l'action";
			Log::creerLog($dateModif, $typeModif, $loginUtilisateur, $action->code_action, $description);
			return $nouvelleAction;
		}
		return $action;
	} 
	
	/*Récupération de toutes les informations d'une action*/
	public static function infoActionComplete($id) {

		$action = Action::getActionById($id);
		$actionRes = array();
		$actionRes['id']=$action->id;
		$actionRes['code_action']=$action->code_action;
		$actionRes['nom_action']=$action->nom_action;
		$actionRes['est_attenuation']=$action->est_attenuation;
		$actionRes['est_adaptation']=$action->est_adaptation;
		$actionRes['est_communication']=$action->est_communication;
		$actionRes['est_formation']=$action->est_formation;
		$actionRes['est_appui_technique']=$action->est_appui_technique;
		$actionRes['est_appui_financier']=$action->est_appui_financier;
		$actionRes['date_mise_a_jour']=$action->date_mise_a_jour;
		$actionRes['contexte_action']=$action->contexte_action;
		$actionRes['objectifs_quantitatifs']=$action->objectifs_quantitatifs;
		$actionRes['gains_ges']=$action->gains_ges;
		$actionRes['gains_energie']=$action->gains_energie;
		$actionRes['gains_co2']=$action->gains_co2;
		$actionRes['maitrise_ouvrage']=$action->maitrise_ouvrage;
		$actionRes['referents_associes']=$action->referents_associes;
		$actionRes['referents_associes']=$action->referents_associes;
		$actionRes['budget_total']=$action->budget_total;
		$actionRes['budget_consomme']=$action->budget_consomme;
		
		//Document
		$documentsRes = array();
		foreach($action->sharedDocument as $document){
			$documentRes=array();
			$documentRes['nom_document']=$document->nom_document;
			$documentRes['path']=$document->path;
			$documentsRes[]=$documentRes;
		}
		$actionRes['documents']=$documentsRes;
		
		
		//Fiche
		$fichesRes = array();
		foreach($action->sharedFiche as $fiche){
			$ficheRes=array();
			$ficheRes['nom_fiche']=$fiche->nom_fiche;
			$ficheRes['path']=$fiche->path;
			$ficheRes[]=$ficheRes;
		}
		$actionRes['fiches']=$fichesRes;
		
		//Utilisateur
		$utilisateursRes = array();
		foreach($action->sharedUtilisateur as $utilisateur){
			$utilisateursRes[]=FctUtilisateur::getUtilisateurParId($utilisateur->id);
		}
		$actionRes['utilisateurs']=$utilisateursRes;
		
		//Partenaire
		$partenairesRes = array();
		foreach($action->sharedPartenaire as $partenaire){
			$partenairesRes[]=FctPartenaire::formePartenaireArray($partenaire);
		}
		$actionRes['partenaires']=$partenairesRes;
		
		//Cr Action
		$crActionsRes = array();
		foreach($action->ownCraction as $crAction){
			$crActionsRes[]=FctCrAction::formeCrActionArray($crAction);
		}
		$actionRes['cractions']=$crActionsRes;
		
		//Objectifenjeu
		$objectifenjeusRes = array();
		foreach($action->ownObjectifenjeu as $objectifenjeu){
			$objectifenjeusRes[]=FctObjectifEnjeu::formeObjectifEnJeuArray($objectifenjeu);
		}
		$actionRes['objectifenjeu']=$objectifenjeusRes;
		
		//Phase
		$phasesRes = array();
		foreach($action->ownPhase as $phase){
			$phasesRes[]=FctPhase::formePhaseArray($phase);
		}
		$actionRes['phases']=$phasesRes;
		
		//Financeur
		$financeurActionsRes = array();
		foreach($action->ownFinanceuraction as $financeuraction){
			$financeurActionsRes[]=FctFinanceurAction::formeFinanceurActionArray($financeuraction);
		}
		$actionRes['financeursaction']=$financeurActionsRes;
		
		//Mot Clef
		$motclefsRes = array();
		foreach($action->sharedMotclef as $motclef){
			$motclefsRes[]=FctMotClef::formeMotClefArray($motclef);
		}
		$actionRes['motclefs']=$motclefsRes;
		
		//Objectif Strategique
		if($action->objectifstrategique!=null){
			$actionRes['objectifstrategique']['nom_objectif_strategique']=$action->objectifstrategique->nom_objectif_strategique;
			$engagement=EngagementThematique::recupererEngagementThematique($action->objectifstrategique->engagementthematique_id);
			$actionRes['objectifstrategique']['nom_engagement_thematique']=$engagement->nom_engagement_thematique;
		}
		
		/*//Engagement Thematique
		if($action->engagementthematique != null){
			$actionRes['engagementthematique']['nom_engagement_thematique']=$action->engagementthematique->nom_engagement_thematique;
		}*/
		
		//Thematique concernee
		if($action->thematiqueconcernee != null){
			$actionRes['thematiqueconcernee']['nom_thematique_concernee']=$action->thematiqueconcernee->nom_thematique_concernee;
		}
			
		return $actionRes;
	}
	
	//Verification des champs a modifier
	public static function verifModifAction($tableauParametres){
		$idActionConcernee= $tableauParametres['id'];
		$actionConcernee = Action::getActionById($idActionConcernee);
		$tabParamChanges=array();
		$tabParamChanges['id'] = $idActionConcernee;
		// Verification champs par champs
		if($tableauParametres['code_action'] != $actionConcernee->code_action)
			$tabParamChanges['code_action'] = $tableauParametres['code_action'];
		if ($tableauParametres['nom_action'] != $actionConcernee->nom_action)
			$tabParamChanges['nom_action'] = $tableauParametres['nom_action'];
		if ($tableauParametres['est_attenuation'] != $actionConcernee->est_attenuation)
			$tabParamChanges['est_attenuation'] = $tableauParametres['est_attenuation'];
		if ($tableauParametres['est_adaptation'] != $actionConcernee->est_adaptation)
			$tabParamChanges['est_adaptation'] = $tableauParametres['est_adaptation'];
		if ($tableauParametres['est_communication'] != $actionConcernee->est_communication)
			$tabParamChanges['est_communication'] = $tableauParametres['est_communication'];
		if ($tableauParametres['est_formation'] != $actionConcernee->est_formation)
			$tabParamChanges['est_formation'] = $tableauParametres['est_formation'];
		if ($tableauParametres['est_appui_technique'] != $actionConcernee->est_appui_technique)
			$tabParamChanges['est_appui_technique'] = $tableauParametres['est_appui_technique'];
		if ($tableauParametres['est_appui_financier'] != $actionConcernee->est_appui_financier)
			$tabParamChanges['est_appui_financier'] = $tableauParametres['est_appui_financier'];
		if ($tableauParametres['date_mise_a_jour'] != $actionConcernee->date_mise_a_jour)
			$tabParamChanges['date_mise_a_jour'] = $tableauParametres['date_mise_a_jour'];
		if ($tableauParametres['contexte_action'] != $actionConcernee->contexte_action)
			$tabParamChanges['contexte_action'] = $tableauParametres['contexte_action'];
		if ($tableauParametres['objectifs_quantitatifs'] != $actionConcernee->objectifs_quantitatifs)
			$tabParamChanges['objectifs_quantitatifs'] = $tableauParametres['objectifs_quantitatifs'];
		if ($tableauParametres['gains_ges'] != $actionConcernee->gains_ges)
			$tabParamChanges['gains_ges'] = $tableauParametres['gains_ges'];
		if ($tableauParametres['gains_energie'] != $actionConcernee->gains_energie)
			$tabParamChanges['gains_energie'] = $tableauParametres['gains_energie'];
		if ($tableauParametres['gains_co2'] != $actionConcernee->gains_co2)
			$tabParamChanges['gains_co2'] = $tableauParametres['gains_co2'];
		if ($tableauParametres['maitrise_ouvrage'] != $actionConcernee->maitrise_ouvrage)
			$tabParamChanges['maitrise_ouvrage'] = $tableauParametres['maitrise_ouvrage'];
		if ($tableauParametres['referents_associes'] != $actionConcernee->referents_associes)
			$tabParamChanges['referents_associes'] = $tableauParametres['referents_associes'];
		// Verification pour Objectif Strategique
		if(isset($tableauParametres['objectifstrategique']['nom_objectif_strategique'])){
			if ($tableauParametres['objectifstrategique']['nom_objectif_strategique'] != $actionConcernee->objectifstrategique->nom_objectif_strategique)
				$tabParamChanges['objectifstrategique']['nom_objectif_strategique'] = $tableauParametres['objectifstrategique']['nom_objectif_strategique'];			
		}	
	
		// Verification pour partenaires
		$lespartenaires = $actionConcernee->sharedPartenaire;
		$estModifie=false;
			// Pour chaque partenaire de l'action presente, on check si les partenaires ont changÃ©
		foreach($lespartenaires as $partenaireCourant){
			foreach($tableauParametres['partenaires'] as $partenaireNew){
				if ($partenaireNew['nom_partenaire'] != $partenaireCourant->nom_partenaire)
					$estModifie = true;
			}
		}
		if($estModifie)
			$tabParamChanges['partenaires'] = $tableauParametres['partenaires'];
		
		//Thematique Concernee
		if(isset($actionConcernee->thematiqueconcernee)){
			if ($tableauParametres['thematiqueconcernee']['nom_thematique_concernee'] != $actionConcernee->thematiqueconcernee->nom_thematique_concernee){
				$tabParamChanges['thematiqueconcernee']['nom_thematique_concernee'] = $tableauParametres['thematiqueconcernee']['nom_thematique_concernee'];
				
			}
		}
		
		//Engagement Thematique
		if(isset($actionConcernee->engagementthematique)){
			if ($tableauParametres['engagementthematique']['nom_engagement_thematique'] != $actionConcernee->engagementthematique->nom_engagement_thematique){
				$tabParamChanges['engagementthematique']['nom_engagement_thematique'] = $tableauParametres['engagementthematique']['nom_engagement_thematique'];
		
			}
		}
		
		//Utilisateurs
		$tabUtilisateursParam = $tableauParametres['utilisateurs'];
		$lesUtilisateurs= $actionConcernee->sharedUtilisateur;
		$estModifie=false;

		$tabParamChanges['utilisateurs'] = $tableauParametres['utilisateurs'];
			
		return $tabParamChanges;
	}
	
	//Modification des champs concernes
	public static function modifierAction($idUtilisateur,$tabParamVerifies){
		$idAction= $tabParamVerifies['id'];
		$action = Action::getActionById($idAction);
		if($action == null){
			return null;
		}
		$utilisateur = Utilisateur::recupererUtilisateur($idUtilisateur);
		if(!Action::aDroitDeModification($action,$utilisateur)){
			return null;
		}
		
		$action = Action::modifierAction($tabParamVerifies);	
		if($action == null)
			return null;	
		
		//TODO
		// Ajout dans un log de la modification
		$dateModif = date('Y-m-d H:i:s');
		$typeModif = "Modification de l'action";
		
		//$descriptionLog = FctLog::constructionDescriptionLog($newValeur);
		$descriptionLog = "description log";
		Log::creerLog($dateModif, $typeModif, $utilisateur->login_utilisateur, $action->code_action, $typeModif);
		return FctAction::infoActionComplete($action->id);
	}
	

	/*Récupération du budget d'une action. A revoir !*/
	public static function recupererBudgetAlloueAction($idAction) {
		$action = Action::getActionById($idAction);
		$financeurs = $action->
		
		$budgetAEncoder = array();
	
		foreach ($actions as $action){
			//Recuperation des phases de l'action courante
			$phases = $action->ownPhase;
			//Calcul des dates de debut, de fin et l'avancement de l'action
			$dateDebutCalculee= FctAction::calculerDateDeDebut($phases);
			$dateFinCalculee =  FctAction::calculerDateDeFin($phases);
			$avancementCalcule =  FctAction::calculerAvancement($phases);
			//Remplissage du tableau de l'action courante pour encodage
			$actionAEncoder=array();
			$actionAEncoder['id']=$action->id;
			$actionAEncoder['code_action']=$action->code_action;
			$actionAEncoder['nom_action']=$action->nom_action;
			// Gestion des mots clefs attaches a l'action
			$lesmotsclefs = $action->sharedMotclef;
			$mcAEnvoyer=array();
			foreach ($lesmotsclefs as $lemotclef){
				$mcAEnvoyer['mot_clef_id']=$lemotclef->id;
				$mcAEnvoyer['nom_mot_clef']=$lemotclef->nom_mot_clef;
			}
			$actionAEncoder['mots_clefs']=$mcAEnvoyer;
			// Objectif strategique
			$actionAEncoder['objectif_strategique']=$action->objectifstrategique->nom_objectif_strategique;
			// Engagement Thematique
			$obj_strat = ObjectifStrategique::recupererObjectifStrategique($action->objectifstrategique->id);
			$actionAEncoder['engagement_thematique']=$obj_strat->engagementthematique->nom_engagement_thematique;
				
			$actionAEncoder['date_debut']=$dateDebutCalculee;
			$actionAEncoder['date_fin']=$dateFinCalculee;
			$actionAEncoder['avancement']=$avancementCalcule;
			$actionsAEncoder[]=$actionAEncoder;
		}
	
		return $actionsAEncoder;
	}

	// ---- OUTILS A LA CLASSE -----
	/* Calcul la date de fin d'une action en fonction des dates de fin de ses phases*/
	public static function calculerDateDeFin($phases) {
		$dateFin = null;
		foreach($phases as $phase) {
			if(($dateFin == null || $dateFin <= $phase->date_fin_prevue)) {
				$dateFin = $phase->date_fin_prevue;
			}
		}
		if ($dateFin == null) {
			$dateFin = "Date Indisponible";
		}
		return $dateFin;
	}
	
	/*Calcul la date de dÃ©but d'une action en fonction des dates de dÃ©but de ses phases*/
	public static function calculerDateDeDebut($phases) {
		$dateDebut = null;
		foreach($phases as $phase) {
			if($dateDebut == null || $dateDebut > $phase->date_debut_prevue) {
				$dateDebut = $phase->date_debut_prevue;
			}
		}
		if ($dateDebut == null) {
			$dateDebut = "Date Indisponible";
		}
		return $dateDebut;
	}
	
	/*Calcul de l'avancement d'une action selon les etats de ses phases*/
	public static function calculerAvancement($phases) {
		$nbPhases = 0;
		// On considï¿½re que les phases qui n'ont pas d'ï¿½tat (null ou  vide "") sont non dï¿½marï¿½es
		$nbNonDemaree = 0;
		$nbSuspendues = 0;
		$nbEnCours = 0;
		$nbEnProjet = 0;
		$nbTermines = 0;
		
		// Calcul des nombre d'ï¿½tats par type d'ï¿½tat
		foreach($phases as $phase) {
			$nbPhases++;
			$etatCourant = $phase->avancement_phase;
			if ($etatCourant == null || $etatCourant == "" || $etatCourant == "Non dÃ©marrÃ©e") {
				$nbNonDemaree++;
			}
			elseif ($etatCourant == "Suspendue") {
				$nbSuspendues++;
			}
			elseif ($etatCourant == "En cours") {
				$nbEnCours++;
			}
			elseif ($etatCourant == "En projet") {
				$nbEnProjet++;
			}
			elseif ($etatCourant == "TerminÃ©e") {
				$nbTermines++;
			}
		}
		
		// Calcul de l'avancement
		if ($nbSuspendues == $nbPhases) {
			$etat = "Suspendue";
		}
		elseif ($nbSuspendues + $nbNonDemaree == $nbPhases) {
			$etat = "Non dÃ©marrÃ©e";
		}
		elseif ($nbSuspendues + $nbTermines == $nbPhases) {
			$etat = "TerminÃ©e";
		}
		elseif ($nbSuspendues + $nbEnProjet + $nbNonDemaree == $nbPhases) {
			$etat = "En projet";
		}
		else {
			$etat = "En cours";
		}
		return $etat;
	}
	// ---- FIN: OUTILS A LA CLASSE -----
	
	public static function listerEtatActions(){
		$actions = Action::getAllAction();
		$listeEtats = array();
		foreach ($actions as $action){
			$etatAction = array();
			$etatAction['id'] = $action->id;
			$etatAction['etat'] = FctAction::calculerAvancement($action->ownPhase);
			$listeEtats[] = $etatAction;
		}
		return $listeEtats;
	}
	
	/*Fonction pour l'envoi de mail. Ne fonctionne pas pour l'instant*/
	public static function envMail($info){
		
		$mail = new PHPMailer();
		
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = "smtp1.example.com;smtp2.example.com";  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		//$mail->Username = "jswan";  // SMTP username
		//$mail->Password = "secret"; // SMTP password
		
		$mail->From = "rnundoo@le-gresivaudan.fr";
		$mail->FromName = "Mailer";
		$mail->AddAddress("riknundoo@gmail.com", "Rikesh");
		
		
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		$mail->IsHTML(true);                                  // set email format to HTML
		
		$mail->Subject = "Here is the subject";
		$mail->Body    = "This is the HTML message body <b>in bold!</b>";
		$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		
		//mail($info.dest,$info.sujet,$info.message,"From: $info.exp \n");
		return;
	}
	
	/*Récupération de toutes les documents non associés à une action*/
	public static function getAllDocumentNonLie($idAction) {
	
		$documents = Document::getDocumentNonLieAction($idAction);
	
		$documentsRep=array();
		foreach($documents as $document){
			$documentsRep[]=FctDocument::formeDocumentArray($document);
		}
		return $documentsRep;
	}
	
}


?>
