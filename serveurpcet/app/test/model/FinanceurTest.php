<?php
use RedBean_Facade as R;

class FinanceurTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		R::nuke();
 		$financeur1 = Financeur::creerFinanceur("Département du Rhône");
 		$financeur2 = Financeur::creerFinanceur("Région Rhône");
 		
 		$financeurAction1 = FinanceurAction::creerFinanceurAction(10000);
 		$financeurAction2 = FinanceurAction::creerFinanceurAction(45000);
	}
	
	public function testListerFinanceursEchec(){
		$financeurs = Financeur::listerFinanceurs();
		$this->assertNotEquals(1,count($financeurs));
	}
	
	public function testListerFinanceursOk(){
		$financeurs = Financeur::listerFinanceurs();
		$this->assertEquals(2,count($financeurs));
	}
 	 	
 	public function testRecupererFinanceurEchec(){
 		$financeur = Financeur::recupererFinanceur(124);
 		$this->assertEquals(null,$financeur);
 	}

 
	public function testRecupererFinanceurOk(){
			
		$financeur = Financeur::recupererFinanceur(1);
		$this->assertEquals("Département du Rhône",$financeur->nom_financeur);
	}

	public function testChercherFinanceurEchec(){
		$financeurs = Financeur::chercherFinanceurs("Paris");
		$this->assertEquals(0,count($financeurs));
	}
	
	public function testChercherFinanceurOk(){
		$financeurs = Financeur::chercherFinanceurs("Rhône");
		$this->assertEquals(2,count($financeurs));
	}	
	

	public function testRenommerFinanceurEchec(){
		$financeur = Financeur::renommerFinanceur(25, "Région Rhône Alpe");
		$this->assertEquals(null, $financeur);
	}
	
	public function testRenommerFinanceurOk(){
		$financeur = Financeur::renommerFinanceur(1, "Région Rhône Alpe");
		$this->assertEquals("Région Rhône Alpe", $financeur->nom_financeur);
	}
	
	public function testSuppressionFinanceurEchec(){
		$retour = Financeur::supprimerFinanceur(25);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionFinanceurOK(){
		$retour = Financeur::supprimerFinanceur(2);
		$this->assertEquals("OK", $retour);
	}
	
	public function testAjouterFinanceurActionOK(){
		$financeur = Financeur::recupererFinanceur(1);
		$financeurAction = FinanceurAction::recupererFinanceurAction(1);
		
		Financeur::ajouterFinanceurAction($financeur, $financeurAction);
		
		$this->assertEquals(10000, $financeur->ownFinanceuraction[1]->montant_ht);
	}	
	
	
	
	public function testAjouterFinanceurActionEchec(){
		$financeur = Financeur::recupererFinanceur(1);
		$financeurAction = FinanceurAction::recupererFinanceurAction(2);
	
		Financeur::ajouterFinanceurAction($financeur, $financeurAction);

		$this->assertNotEquals(33000, $financeur->ownFinanceuraction[2]->montant_ht);
	}

} 

?>