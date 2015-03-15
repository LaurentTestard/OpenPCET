<?php
 

use RedBean_Facade as R;
class IndicateurTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
 		R::nuke();
 		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
 		$obj = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif1");
 		Action::addObjectifenjeu($action, $obj);
 		$obj2 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif2");
 		Action::addObjectifenjeu($action, $obj2);
 		$obj3 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif3");
 		Action::addObjectifenjeu($action, $obj3);
 		$ind = Indicateur::creerIndicateurAction("nomIndicateur1", "100", "250", "descriptionIndicateur1");
 		$ind2 = Indicateur::creerIndicateurAction("nomIndicateur2", "100", "2050", "descriptionIndicateur2");
 		$ind3 = Indicateur::creerIndicateurAction("nomIndicateur3", "10", "50", "descriptionIndicateur3");
 		$ind4 = Indicateur::creerIndicateurAction("nomIndicateur4", "10", "50", "descriptionIndicateur4");
 		ObjectifEnjeu::addIndicateur($obj, $ind);
 		ObjectifEnjeu::addIndicateur($obj2, $ind2);
 		ObjectifEnjeu::addIndicateur($obj2, $ind3);
    }
    
	public function testRecupererIndicateurFail(){
		$indicateur = Indicateur::recupererIndicateur(20);
		$this->assertEquals(null,$indicateur);
	}
	
	public function testRecupererIndicateurOk(){
		$indicateur = Indicateur::recupererIndicateur(1);
		$this->assertEquals("nomIndicateur1",$indicateur->nom_indicateur);
	}
	
	public function testChercherIndicateurOk(){
		$indicateurs = Indicateur::chercherIndicateurs("nomIndicateur1");
		$this->assertEquals(1, count($indicateurs));
	}
	
	public function testChercherIndicateurFail(){
		$indicateurs = Indicateur::chercherIndicateurs("nomIndicateur10");
		$this->assertEquals(0, count($indicateurs));
	}
	
	public function testRenommerIndicateurFail(){
		$indicateur = Indicateur::renommerIndicateur(6, "NouveaunomIndicateur1");
		$this->assertEquals(null, $indicateur);
	}
	
	public function testRenommerIndicateurOk(){
		$indicateur = Indicateur::renommerIndicateur(1, "NouveaunomIndicateur2");
		$this->assertEquals("NouveaunomIndicateur2", $indicateur->nom_indicateur);
	}
	
	public function testSuppressionIndicateurFail(){
		$retour = Indicateur::supprimerIndicateur(5);
		$this->assertEquals(null, $retour);
	}
	
	public function testSuppressionIndicateurOK(){
		$retour = Indicateur::supprimerIndicateur(4);
		$this->assertEquals("OK", $retour);
	}
	
	public function testOK(){
		$indicateur = Indicateur::recupererIndicateur(5);
		$this->assertEquals(null, $indicateur);
	}
	
	
}
