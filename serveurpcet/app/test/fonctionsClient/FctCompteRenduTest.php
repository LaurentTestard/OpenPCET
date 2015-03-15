<?php
use RedBean_Facade as R;
class FctCompteRenduTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
	
 		$action = Action::creerAction("C1.1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");

 		}
 		
 	public function testAjoutCompteRenduActionOK(){
 		$action = Action::getAction("C1.1");
 		 		
 		FctCompteRendu::ajouterCompteRendu($action->id, "Foreta", "L'action se déroule en temps et en heure", true);
		
 		$this->assertEquals("L'action se déroule en temps et en heure", $action->ownCraction[1]->description_cr_action);
 		}
 		
 	public function testAjoutCompteRenduActionEchecUtilisateur(){
 			$action = Action::getAction("C1.1");
 		
 			$crAction = FctCompteRendu::ajouterCompteRendu($action->id, "Yvan", "L'action se déroule en temps et en heure", true);
 		
 			$this->assertEquals(null, $crAction);
 		}
 	
 	public function testAjoutCompteRenduActionEchec(){
 			$utilisateur = Utilisateur::getUtilisateur("Foreta");
 				
 			$crAction = FctCompteRendu::ajouterCompteRendu(14, "Foreta", "L'action se déroule en temps et en heure", true);
 		
 			$this->assertEquals(Null, $crAction);
 		}
 		
 	public function testListerCrActions(){
 			$action = Action::getAction("C1.1");
 				
 			FctCompteRendu::ajouterCompteRendu($action->id, "Foreta", "L'action se déroule en temps et en heure", true);
 			FctCompteRendu::ajouterCompteRendu($action->id, "Foreta", "L'action continue à se dérouler en temps et en heure", true);
 			FctCompteRendu::ajouterCompteRendu($action->id, "Foreta", "L'action est finie", true);

 			$crActions = FctCompteRendu::listerComptesRendusParAction($action->id);
 		 			
 			$this->assertEquals(4, count($crActions));

 		}
 	
 	public function testListerCrActionsVide(){
		
 			$crActions = FctCompteRendu::listerComptesRendusParAction(141);
 		
 			$this->assertEquals(null, count($crActions));
 		}
}