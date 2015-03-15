<?php
use RedBean_Facade as R;

class ObjectifStrategiqueTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		R::nuke();
		$obj_strat = ObjectifStrategique::creerObjectifStrategique("C1", "nom de l'objectif strategique");
	}
	
	public function testGetObjectifStrategiqueRight(){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique(1);
		$this->assertEquals("C1",$obj_strat->code_objectif_strategique);
	}
	
	public function testGetObjectifStrategiqueWrong(){
		$obj_strat = ObjectifStrategique::recupererObjectifStrategique(2);
		$this->assertEquals(null,$obj_strat);
	}
}
?>