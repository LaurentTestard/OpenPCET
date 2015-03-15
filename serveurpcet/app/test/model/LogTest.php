<?php
 

use RedBean_Facade as R;
class LogTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
 		R::nuke();
 		$engagement_thematique  = EngagementThematique::creerEngagementThematique("C", "nom engagement th�matique");
 		$obj_strat = ObjectifStrategique::creerObjectifStrategique("C1", "nom de l'objectif strat�gique", $engagement_thematique);
 		$action1 = Action::creerAction("C1.1","nomAction1",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
 		$action2 = Action::creerAction("C2.1","nomAction2",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);	
 		$utilisateur1 = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gr�sivaudan","anne.foret@gmail.com","6532","0474568790");
 		$utilisateur2 = Utilisateur::creerUtilisateur("Fa","A","F", md5('admin'), "chef de projet","communaut� de commune Gr�sivaudan","anne.foret@gmail.com","6532","0474568790");
 		Log::creerLog(date('Y-m-d H:i:s'),"typemodification1", "Foreta", "C1.1", 'description1');
 		Log::creerLog(date('Y-m-d H:i:s'),"typemodification2", "Fa", "C1.1", 'description2');
 		
 		
    }
    
	public function testGetWin(){
		$action = Action::getAction("C1.1");
		$logs = Log::getLogsParCodeAction($action);
		$this->assertEquals('description1',$logs[1]->description_modification);
		$this->assertEquals('description2',$logs[2]->description_modification);
	}
	
	public function testGetFail(){
		$action = Action::getAction("C2.1");
		$log = Log::getLogsParCodeAction($action);
		$this->assertEquals(array(),$log);
	}
	
	public function testGetParUtilisateur(){
		$utilisateur = Utilisateur::authentification("Foreta", md5('admin'));
		$logs = Log::getLogsParUtilisateur($utilisateur);
		$this->assertEquals(1,count($logs));
	}
	
	public function testGetAllNotifications(){
		$logs = Log::getLogs();
		$this->assertEquals(2,count($logs));
	}
}
