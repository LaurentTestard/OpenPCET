<?php
use RedBean_Facade as R;

class FctIndicateurTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass()
	{
		R::nuke();
		
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
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
 		//FctIndicateur::ajouterIndicateur(1, 1, "nomIndicateur5", "10", "50", "descriptionIndicateur4", "2014-03-18", "typeModif5");
	}
	
	public function testListerIndicateursParActionOK(){
		$indicateursAction = FctIndicateur::ListerIndicateurAction(1);	
	}
	

	
	public function testSupprimerIndicateurOK(){
		$tabParametres['id'] = 1;
		$indicateur = FctIndicateur::supprimerIndicateur($tabParametres);
		$this->assertEquals(1,$indicateur->id);
	}
	
	public function testSupprimerIndicateurEchec(){
		$indicateur = FctIndicateur::supprimerIndicateur(10, 1,  "2014-03-18", "typeModif5");
		$this->assertEquals(null,$indicateur);
	}
	

}
?>
