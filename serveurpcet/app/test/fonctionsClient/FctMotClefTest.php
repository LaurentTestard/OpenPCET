<?php
use RedBean_Facade as R;

class FctMotClefTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass()
	{
		R::nuke();
		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		$motclef=MotClef::creerMotClef("Eau");
		$motclef1=MotClef::creerMotClef("Energie");
		$motclef2=MotClef::creerMotClef("Feu");
		$motclef3=MotClef::creerMotClef("Isolation");
		$motclef4=MotClef::creerMotClef("Chauffage");
			
		MotClef::lierMotClefAction($motclef, $action);
		MotClef::lierMotClefAction($motclef1, $action);
	}
	
	public function testAjoutMotClef(){
		$motclef = FctMotClef::ajoutNouveauMotClef("Logement");		
		$this->assertEquals("Logement",$motclef->nom_mot_clef);
	}
	
	public function testMotsClefsLies(){
		$action = Action::getAction("C1");
		$motsclefs = FctMotClef::recupererMotsClefsLies($action->id);
	}
	
	public function testMotsClefsNonLies(){
		$action = Action::getAction("C1");
		$motsclefs = FctMotClef::recupererMotsClefsNonLies($action->id);
	}
	
	public function testLieMotClefAction(){
		$action = Action::getAction("C1");
		$motclef = MotClef::recupererMotClef(3);
		$res = FctMotClef::lieMotClefAction($action->id, $motclef->id);
	}
	
	public function testDeLieMotClefAction(){
		$action = Action::getAction("C1");
		$motclef = MotClef::recupererMotClef(3);
		$res = FctMotClef::delieMotClefAction($action->id, $motclef->id);
	}
	
}
?>
