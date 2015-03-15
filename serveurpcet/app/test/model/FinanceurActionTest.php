<?php
use RedBean_Facade as R;

class FinanceurActionTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		R::nuke();
 		$financeurAction1 = FinanceurAction::creerFinanceurAction(10000);
 		$financeurAction2 = FinanceurAction::creerFinanceurAction(25000);
	}
	
 	 	
 	public function testRecupererFinanceurActionEchec(){
 		$financeurAction = FinanceurAction::recupererFinanceurAction(124);
 		$this->assertEquals(null,$financeurAction);
 	}

 	public function testRecupererFinanceurActionOk(){
			
		$financeurAction = FinanceurAction::recupererFinanceurAction(1);
		$this->assertEquals(10000,$financeurAction->montant_ht);
	}

	public function testMettreAjourFinanceurActionEchec(){
		$financeurAction = FinanceurAction::mettreAjourFinanceurAction(25, 10251);
		$this->assertEquals(null, $financeurAction);
	}
	
	public function testMettreAjourFinanceurActionOk(){
		$financeurAction = FinanceurAction::mettreAjourFinanceurAction(1, 450000);
		$this->assertEquals(450000, $financeurAction->montant_ht);
	}
	
	public function testSuppressionFinanceurActionEchec(){
		$retour = FinanceurAction::supprimerFinanceurAction(25);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionFinanceurActionOK(){
		$retour = FinanceurAction::supprimerFinanceurAction(2);
		$this->assertEquals("OK", $retour);
	}

} 

?>