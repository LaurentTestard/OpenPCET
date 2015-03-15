<?php
use RedBean_Facade as R;

class ThematiqueConcerneeTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
 		$thematique1 = ThematiqueConcernee::creerThematiqueConcernee("Biologie et Sciences");
 		$thematique2 = ThematiqueConcernee::creerThematiqueConcernee("Maths");
 		
 		$action1 = Action::creerAction("C1","nomAction1",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		$action2 = Action::creerAction("C2","nomAction2",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
	}
	

	public function testListerThematiquesConcerneesEchec(){
		$thematiquesConcernees = ThematiqueConcernee::listerThematiquesConcernees();
		$this->assertNotEquals(1,count($thematiquesConcernees));
	}
	
	public function testListerThematiquesConcerneesOk(){
		$thematiquesConcernees = ThematiqueConcernee::listerThematiquesConcernees();
		$this->assertEquals(2,count($thematiquesConcernees));
	}
	
	public function testRecupererThematiqueConcerneeEchec(){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee(124);
		$this->assertEquals(null,$thematiqueConcernee);
	}
	

	public function testRecupererThematiqueConcerneeOk(){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee(1);
		$this->assertEquals("Biologie et Sciences",$thematiqueConcernee->nom_thematique_concernee);
	}
	
	public function testChercherThematiqueConcerneeEchec(){
		$thematiquesConcernees = ThematiqueConcernee::chercherThematiquesConcernees("Français");
		$this->assertEquals(0,count($thematiquesConcernees));
	}
	
	public function testChercherThematiqueConcerneeOk(){
		$thematiquesConcernees = ThematiqueConcernee::chercherThematiquesConcernees(" et ");
		$this->assertEquals(1,count($thematiquesConcernees));
	}
	
	
	public function testRenommerThematiqueConcerneeEchec(){
		$thematiqueConcernee = ThematiqueConcernee::renommerThematiqueConcernee(25, "Alemand");
		$this->assertEquals(null, $thematiqueConcernee);
	}
	
	public function testRenommerThematiqueConcerneeOk(){
		$thematiqueConcernee = ThematiqueConcernee::renommerThematiqueConcernee(1, "Biologie et sciences de la vie");
		$this->assertEquals("Biologie et sciences de la vie", $thematiqueConcernee->nom_thematique_concernee);
	}
	

	public function testAjouterActionOK(){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee(1);
		$action = Action::getAction("C1");
	
		ThematiqueConcernee::ajouterAction($thematiqueConcernee, $action);
	
		$this->assertEquals("C1", $thematiqueConcernee->ownAction[1]->code_action);
		}
	

	public function testAjouterActionEchec(){
		$thematiqueConcernee = ThematiqueConcernee::recupererThematiqueConcernee(1);
		$action = Action::getAction("C2");
	
		ThematiqueConcernee::ajouterAction($thematiqueConcernee, $action);
	
		$this->assertNotEquals("Z8", $thematiqueConcernee->ownAction[2]->code_action);
		}
	
	public function testSuppressionThematiqueConcerneeEchec(){
		$retour = ThematiqueConcernee::supprimerThematiqueConcernee(25);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionThematiqueConcerneeOK(){
		$retour = ThematiqueConcernee::supprimerThematiqueConcernee(2);
		$this->assertEquals("OK", $retour);
	}
	
}
	
?>