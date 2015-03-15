<?php 
use RedBean_Facade as R;

class FctPhaseTest extends PHPUnit_Framework_TestCase{
	
	public static function setUpBeforeClass(){
		R::nuke();
		$phase = Phase::creerPhase("phase", "", "2014-03-17", "2014-03-18", "2014-03-17", "2014-03-18", "", 1, "", 0, "");
		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		$utilisateur = Utilisateur::creerUtilisateur("login", "nom", "prenom", md5("test"), "role", "", "", "", "");
		Action::addPhase($action, $phase);
	}
	
	public function testRecupererPhaseActionOk(){
		$phase = FctPhase::visualiserPhase(1, 1);
		$this->assertEquals("phase", $phase['nom_phase']);
	}
	
	public function testRecupererPhaseActionFail(){
		$phase = FctPhase::visualiserPhase(1, 2);
		$this->assertEquals(null, $phase);
	}
	
	public function testAjouterPhase() {
		$action = Action::getAction("C1");
		$idUtilisateur = Utilisateur::getUtilisateur("login");
		$phase = FctPhase::ajouterPhase($action->id, $idUtilisateur->id, "phaseTest", "", "2014-03-17", "2014-03-18", "2014-03-17", "2014-03-18", "", 1, "", 0, "", "description modif");
		$this->assertEquals("phaseTest", $phase['nom_phase']);
	}
}
?>