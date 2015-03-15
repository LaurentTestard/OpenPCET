<?php
 use RedBean_Facade as R;
 
class ObjectifEnjeuTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
 		R::nuke();
 		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		$obj = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif1");
 		Action::addObjectifenjeu($action, $obj);
 		$obj2 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif2");
 		Action::addObjectifenjeu($action, $obj2);
 		$obj3 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif3");
 		Action::addObjectifenjeu($action, $obj3);
 		$ind = Indicateur::creerIndicateurAction("nomIndicateur1", "10", "50", "descriptionIndicateur1");
 		ObjectifEnjeu::addIndicateur($obj, $ind);
 		
    }
	
	public function testrelationIndiWin(){
		$objectifs = ObjectifEnjeu::getObjectifEnjeu("nomobjectif1");
		$this->assertEquals('nomobjectif1',$objectifs->nom_objectif_enjeu);
		$this->assertEquals(1,count($objectifs->ownIndicateur));
	}
	
	public function testRecupererObjectifFail(){
		$objectifEnjeu = ObjectifEnjeu::recupererObjectifEnjeu(45);
		$this->assertEquals(null,$objectifEnjeu);
	}
	
	public function testRecupererObjectifOk(){
		$objectifs = ObjectifEnjeu::getObjectifEnjeu("nomobjectif1");
		$objectifEnjeu = ObjectifEnjeu::recupererObjectifEnjeu($objectifs->id);
		$this->assertEquals("nomobjectif1",$objectifEnjeu->nom_objectif_enjeu);
	}
	
	public function testRecupererObjectifByIdActionFail(){
		$objectifEnjeu = ObjectifEnjeu::getObjectifsEnjeuxByIdAction(100);
		$this->assertEquals(array(),$objectifEnjeu);
	}
	
	public function testRecupererObjectifByIdActionOk(){
		$objectifs = ObjectifEnjeu::getObjectifEnjeu("nomobjectif1");
		$objectifEnjeu = ObjectifEnjeu::recupererObjectifEnjeu($objectifs->id);
		$this->assertEquals("nomobjectif1",$objectifEnjeu->nom_objectif_enjeu);
	}
	
	public function testRenommerObjectifFail(){
		$objectif = ObjectifEnjeu::renommerObjectifEnjeu(45, "nomobjectif100");
		$this->assertEquals(null, $objectif);
	}
	
	public function testChercherObjectifsEnjeuxFail(){
		$objectifs = ObjectifEnjeu::chercherObjectifsEnjeux("test");
		$this->assertEquals(0,count($objectifs));
	}
	
	public function testChercherObjectifsEnjeuxOk(){
		$objectifs = ObjectifEnjeu::chercherObjectifsEnjeux("nom");
		$this->assertEquals(3,count($objectifs));
	}
	
	public function testSuppressionObjectifFail(){
		$retour = ObjectifEnjeu::supprimerObjectifEnjeu(42);
		$this->assertEquals(null, $retour);
	}
	
	public function testRenommerObjectifOk(){
		$objectif = ObjectifEnjeu::renommerObjectifEnjeu(2, "nomobjectif10");
		$this->assertEquals("nomobjectif10", $objectif->nom_objectif_enjeu);
	}
	
	public function testSuppressionObjectifOK(){
		$retour = ObjectifEnjeu::supprimerObjectifEnjeu(3);
		$this->assertEquals("OK", $retour);
	}
	
	
}
