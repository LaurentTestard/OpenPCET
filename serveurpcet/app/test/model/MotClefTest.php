<?php
use RedBean_Facade as R;

class MotClefTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
 		MotClef::creerMotClef("Mot_clef_1");
	}
	
 	public function testRecupererMotClefFail(){
 		$motClef = MotClef::recupererMotClef(2);
 		$this->assertEquals(null,$motClef);
 	}
		
	public function testRecupererMotClefOk(){
		$motClef = MotClef::recupererMotClef(1);
		$this->assertEquals("Mot_clef_1",$motClef->nom_mot_clef);
	}
	
	public function testLierMotClefActionFail() {
		$action = Action::creerAction("Test", "Nom action", true, true, true, false, true, false, "2014-03-12", "contexte", "objectif quantitatif", 3, 5, 100, "MOA", "referent", 100000, 5000, "crAction");
		$motClef1 = MotClef::recupererMotClef(2);
		$this->assertEquals(null, $motClef1);
	}
	 
	public function testLierMotClefActionOk() {
		$action = Action::creerAction("Test", "Nom action", true, true, true, false, true, false, "2014-03-12", "contexte", "objectif quantitatif", 3, 5, 100, "MOA", "referent", 100000, 5000, "crAction");
		$motClef1 = MotClef::recupererMotClef(1);
		$motClef = MotClef::lierMotClefAction($motClef1, $action);
		foreach ($motClef->sharedAction as $action1){
			$this->assertEquals("Nom action", $action1->nom_action);
		}
	}
	
	public function testChercherMotClefOk(){
		$motsClefs = MotClef::chercherMotClef("Mot_clef_1");
		$this->assertEquals(1, count($motsClefs));
	}
	
	public function testChercherMotClefFail(){
		$motClef = MotClef::chercherMotClef("Mot clef 2");
		$this->assertEquals(0, count($motClef));
	}
	
	public function testRenommerMotClefFail(){
		$motCle = MotClef::renommerMotClef(2, "Mot clef 2");
		$this->assertEquals(null, $motCle);
	}
	
	public function testRenommerMotClefOk(){
		$motCle = MotClef::renommerMotClef(1, "Mot clef 2");
		$this->assertEquals("Mot clef 2", $motCle->nom_mot_clef);
	}
	
	public function testSuppressionMotClefFail(){
		$retour = MotClef::supprimerMotClef(2);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionMotClefOK(){
		$retour = MotClef::supprimerMotClef(1);
		$this->assertEquals("OK", $retour);
	}
	
	public function testOK(){
		$motClef = MotClef::recupererMotClef(1);
		$this->assertEquals(null, $motClef);
	}

} 
?>

