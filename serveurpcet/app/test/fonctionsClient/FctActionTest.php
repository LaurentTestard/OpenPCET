<?php
use RedBean_Facade as R;
class FctActionTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		RConf::confTest();
		R::nuke();
 		$document = Document::creerDocument("doc");
 		$motClef = MotClef::creerMotClef("mot clef");
 		$financeur = Financeur::creerFinanceur("financeur");
 		$partenaire = Partenaire::creerPartenaire("partenaire");
 		$thematique = ThematiqueConcernee::creerThematiqueConcernee("thematique");
 		$indicateur = Indicateur::creerIndicateurAction("indicateur", 1, 2, "description");
 		$objectifEnjeu = ObjectifEnjeu::creerObjectifEnjeu("objectif enjeu");
 		$phase = Phase::creerPhase("phase", "", "2014-03-17", "2014-03-18", "2014-03-17", "2014-03-18", "", 1, "", 0, "Non démarrée");
		$engagement_thematique  = EngagementThematique::creerEngagementThematique("C", "nom engagement thematique");
		$obj_strat = ObjectifStrategique::creerObjectifStrategique("C1", "nom de l'objectif strat�gique");
		$cr_action = CrAction::creerCrAction("test", true);
 		$action = Action::creerAction("C1.1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
 		$action2 = Action::creerAction("C1.2","nomAction2",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
 
 		$utilisateur = Utilisateur::creerUtilisateur("login", "nom", "prenom", md5("test"), "role", "", "", "", "");
 		
 		Action::addDocument($action, $document);
 		MotClef::lierMotClefAction($motClef, $action);
 		Action::addFinanceurAction($action, $financeur);
 		Action::addPartenaire($action, $partenaire);
 		ThematiqueConcernee::ajouterAction($thematique, $action);
 		ObjectifEnjeu::addIndicateur($objectifEnjeu, $indicateur);
 		Action::addObjectifenjeu($action, $objectifEnjeu);
 		Action::addPhase($action, $phase);
 		EngagementThematique::addObjectifStrategique($engagement_thematique, $obj_strat);
 		ObjectifStrategique::addAction($obj_strat, $action);
 		Action::addCrAction($action, $cr_action);
 		Action::addUtilisateur($action, $utilisateur);
 		ActionAction::ajouterActionFils($action->id, $action2->id);
	}
	
	public function testListerAction(){
		$actions = FctAction::listerActions();
		var_dump($actions);		
	}
	
	public function testConsulterAction_01(){
		// Simulation r�cup�ration partie cliente (l'id)
		$action = Action::getAction("C1.1");
 		$idAction = $action->id;
		$action = FctAction::infoActionComplete($idAction);
	}
	
// 	public function testModifierActionFail(){
// 		$utilisateur = Utilisateur::recupererUtilisateur(1);
// 		$action = FctAction::modifierAction(1, 2, "C1","nomAction",true,false,false,false,false,false, "2014-03-17", 'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,"2014-03-17","type");
// 		$this->assertEquals(null, $action);
// 	}
	
// 	public function testModifierActionOk(){
// 		$utilisateur = Utilisateur::recupererUtilisateur(1);
// 		$action = FctAction::modifierAction(1, 1, "C2","nomAction",true,false,false,false,false,false,"2014-03-17", 'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,"2014-03-17","type");
// 		$this->assertEquals("C2", $action['code_action']);
// 	}

	public function testListerPhasesOk(){
		$phases = FctAction::listerPhasesAction(1);
		$this->assertEquals(1, count($phases));
	}
	public function test(){
		$user = Utilisateur::getUtilisateur("login");
	}

	public function testListerEtat(){
		$etats = FctAction::listerEtatActions();
		$this->assertEquals(1, count($etats));
		$etat = $etats[0];
		$this->assertEquals("Non démarrée", $etat['etat']);
	}
	
	public function testModifierBudget(){
		$utilisateur = Utilisateur::recupererUtilisateur(1);
		$action = FctAction::modifierBudget(1, $utilisateur, 2000, 1000, "2014-03-19", "type", "description");
		$this->assertEquals(2000, $action['budget_total']);
		$this->assertEquals(1000, $action['budget_consomme']);
	}
}
?>
