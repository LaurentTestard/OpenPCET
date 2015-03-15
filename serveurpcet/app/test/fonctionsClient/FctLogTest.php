<?php
use RedBean_Facade as R;
class FctLogTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
		R::nuke();		
 		
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gr�sivaudan","anne.foret@gmail.com","6532","0474568790");
		$action = Action::creerAction("C1.1","nomAction1",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		
		Log::creerLog(date('Y-m-d H:i:s'), "Ajout d'un log", "Foreta", "C1.1", "Ajout d'un enregistrement "." Nouvelle valeur");
		Log::creerLog(date('Y-m-d H:i:s'), "Ajout d'une action", "Foreta", "C1.2", "Ajout d'un enregistrement "." Nouvelle valeur");
		Log::creerLog(date('Y-m-d H:i:s'), "Ajout d'un user", "Fora", "C1.1", "Ajout d'un enregistrement "." Nouvelle valeur");
 		}
 		
 	public function testListerTous(){
 		$logs = FctLog::listerLogs();
  		$this->assertEquals(3, count($logs));
 	}
 	
 	public function testListerLogsActionEchec(){
 		$logs = FctLog::listerLogsParAction(454);
 		$this->assertEquals(0, count($logs));
 	}
 	
 	public function testListerLogsActionSucces(){
 		$logs = FctLog::listerLogsParAction(1);
 		$this->assertEquals(2, count($logs));
 	}
}
 ?>