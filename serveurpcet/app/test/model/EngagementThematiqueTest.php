<?php
use RedBean_Facade as R;
class EngagementThematiqueTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		R::nuke();
		$engage = EngagementThematique::creerEngagementThematique("C", "nomengagementth�matique");
		$object = ObjectifStrategique::creerObjectifStrategique("codeObj", "nomObj");
		EngagementThematique::addObjectifStrategique($engage, $object);
	}
	
	public function testGetEngagementThematiqueWin(){
		$engagement_thema = EngagementThematique::recupererEngagementThematique(1);
		$this->assertEquals("C",$engagement_thema->code_engagement_thematique);
	}
	
	public function testGetEngagementThematiqueFail(){
		$engagement_thema = EngagementThematique::recupererEngagementThematique(2);
		$this->assertEquals(null,$engagement_thema);
	}
	
	public function testGetEngagementThematiqueObjectifWin(){
		$engagement_thema = EngagementThematique::recupererEngagementThematique(1);
		$this->assertEquals(1,count($engagement_thema->ownObjectifstrategique));
	}
}
?>