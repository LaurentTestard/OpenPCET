 <?php
use RedBean_Facade as R;

class PhaseTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
 		R::nuke();
 		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		$phase = Phase::creerPhase("nom de la phase", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		$phase1 = Phase::creerPhase("nom de la phase1", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		$phase2 = Phase::creerPhase("nom de la phase2", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		Action::addPhase($action, $phase);
 		Action::addPhase($action, $phase1);
 		Action::addPhase($action, $phase2);
	}

	public function testRecupererPhaseFail(){
		$phase = Phase::recupererPhase(4);
 		$this->assertEquals(null,$phase);
 	}
		
	public function testRecupererPhaseOk(){
		$phase = Phase::recupererPhase(1);
		$this->assertEquals("nom de la phase",$phase->nom_phase);
	}
	
	public function testChercherPhaseOk(){
		$phases = Phase::chercherPhases("nom de la phase1");
		$this->assertEquals(1, count($phases));
	}
	
	public function testChercherPhaseFail(){
		$phase = Phase::chercherPhases("Document2");
		$this->assertEquals(0, count($phase));
	}
	
	public function testRenommerPhaseFail(){
		$phase = Phase::modifierPhase(4, "nom de la phase2", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$this->assertEquals(null, $phase);
	}
	
	public function testRenommerPhaseOk(){
		$phase = Phase::modifierPhase(1, "nom de la phase2", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$this->assertEquals("nom de la phase2", $phase->nom_phase);
	}
	
	public function testRecupererActionOk(){
		$phase = Phase::recupererPhase(1);
		$this->assertEquals("C1", $phase->action->code_action);
	}
	
	public function testSuppressionPhaseFail(){
		$retour = Phase::supprimerPhase(4);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionPhaseOK(){
		$retour = Phase::supprimerPhase(1);
		$this->assertEquals("OK", $retour);
	}
	
	public function testOK(){
		$phase = Phase::recupererPhase(1);
		$this->assertEquals(null, $phase);
	}

	public function testListerPhaseActionsJSON(){
		$phases = FctPhase::listerPhasesAction("C1");
	}
	
	public function testRenommerPhaseControlleur(){
		$phase = FctPhase::modifierPhaseAction(2, "nouveau nom", "ceci est un commentaire","22-03-2014", "22-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$this->assertEquals("nouveau nom", $phase['nom_phase']);
	}
	
	public function testListerPhaseAction(){
		$phases = FctPhase::listerPhasesAction("C1");
	}
}
?>
