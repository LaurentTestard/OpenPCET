<?php

use RedBean_Facade as R;
class CrActionTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
    	R::nuke();
 		$crAction1 = CrAction::creerCrAction("L'action se déroule en temps et en heure", true);
 		$crAction2 = CrAction::creerCrAction("L'action continue à se dérouler", true);
 		
 		$action = Action::creerAction("C1.1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
 		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
 		$utilisateur = Utilisateur::authentification("Foreta", md5('admin'));
 		
 		Action::addCrAction($action, $crAction1);
 		Action::addCrAction($action, $crAction2);
 		
 		Utilisateur::addCrAction($utilisateur, $crAction1);
 		Utilisateur::addCrAction($utilisateur, $crAction2);
 				 	
  	}

  	    
 	public function testGetWin(){
 		$action = Action::getAction("C1.1");
 		
 		$this->assertEquals("L'action se déroule en temps et en heure", $action->ownCraction[1]->description_cr_action);
 	}
	
	
 	public function testGetParUtilisateurOK(){
 		$utilisateur = Utilisateur::authentification("Foreta", md5('admin'));
 		
 		$this->assertEquals("L'action continue à se dérouler", $utilisateur->ownCraction[2]->description_cr_action);
 	}

}
