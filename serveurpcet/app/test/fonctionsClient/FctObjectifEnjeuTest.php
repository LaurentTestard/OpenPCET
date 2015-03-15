<?php
use RedBean_Facade as R;

class FctObjectifEnjeuTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass()
	{
		R::nuke();
		
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
		$action2 = Action::creerAction("C2","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
			
		$obj = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif1");
 		Action::addObjectifenjeu($action, $obj);
 		$obj2 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif2");
 		Action::addObjectifenjeu($action2, $obj2);
 		$obj3 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif3");
 		Action::addObjectifenjeu($action, $obj3);
 		
 		//FctIndicateur::ajouterIndicateur(1, 1, "nomIndicateur5", "10", "50", "descriptionIndicateur4", "2014-03-18", "typeModif5");
	}
	
	public function testListerObjectifsParActionOK(){
		$objectifsAction = FctObjectifEnjeu::listerObjectifsEnjeuxIdAction(1);
		$this->assertEquals(2,count($objectifsAction));
		//echo "Objectifs d'une action:".var_dump($objectifsAction);
				
	}
	
	public function testListerObjectifsParActionEchec(){
		$objectifsAction = FctObjectifEnjeu::listerObjectifsEnjeuxIdAction(10);
		$this->assertEquals(Null,$objectifsAction);
			
	}
}
?>
