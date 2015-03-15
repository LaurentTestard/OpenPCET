<?php
use RedBean_Facade as R;
 
class PartenaireTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
 		$partenaire1 = Partenaire::creerPartenaire("ADEME");
 		$partenaire2 = Partenaire::creerPartenaire("ADEME et region");
 		$partenaire3 = Partenaire::creerPartenaire("SRCAE");
 		
 		$action1 = Action::creerAction("C1","nomAction1",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		
	}
	public function testListerPartenairesEchec(){
		$partenaires = Partenaire::listerPartenaires();
		$this->assertNotEquals(1,count($partenaires));
	}
	
	public function testListerPartenairesOk(){
		$partenaires = Partenaire::listerPartenaires();
		$this->assertEquals(3,count($partenaires));
	}
 	 	
 	public function testRecupererPartenaireEchec(){
 		$partenaire = Partenaire::recupererPartenaire(124);
 		$this->assertEquals(null,$partenaire);
 	}
 
	public function testRecupererPartenaireOk(){
		$partenaire = Partenaire::recupererPartenaire(1);
		$this->assertEquals("ADEME",$partenaire->nom_partenaire);
	}

	public function testChercherPartenaireEchec(){
		$partenaires = Partenaire::chercherPartenaires("Valais Excellence");
		$this->assertEquals(0,count($partenaires));
	}
	
	public function testChercherPartenaireOk(){
		$partenaires = Partenaire::chercherPartenaires("ADEME");
		$this->assertEquals(2,count($partenaires));
	}	
	

	public function testRenommerPartenaireEchec(){
		$partenaire = Partenaire::renommerPartenaire(25, "AD et Co");
		$this->assertEquals(null, $partenaire);
	}
	
	public function testRenommerPartenaireOk(){
		$partenaire = Partenaire::renommerPartenaire(1, "ADEME et associé");
		$this->assertEquals("ADEME et associé", $partenaire->nom_partenaire);
	}
	
	public function testSuppressionPartenaireEchec(){
		$retour = Partenaire::supprimerPartenaire(25);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionPartenaireOK(){
		$retour = Partenaire::supprimerPartenaire(2);
		$this->assertEquals("OK", $retour);
	}
	
	public function testAjouterActionOK(){
		$partenaire = Partenaire::recupererPartenaire(1);
		$action = Action::getAction("C1");
		
		Partenaire::ajouterAction($partenaire, $action);	
		$this->assertEquals("C1", $partenaire->sharedAction[1]->code_action);
	}	
	
	public function testAjouterActionEchec(){
		$partenaire = Partenaire::recupererPartenaire(1);
		$action = Action::getAction("C1");
		
		Partenaire::ajouterAction($partenaire, $action);	
		$this->assertNotEquals("C2", $partenaire->sharedAction[1]->code_action);
	}
} 

?>