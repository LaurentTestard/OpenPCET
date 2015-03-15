<?php
use RedBean_Facade as R;
class ActionActionTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();

 		$engagement_thematique  = EngagementThematique::creerEngagementThematique("C", "nom engagement thematique");
		$obj_strat = ObjectifStrategique::creerObjectifStrategique("C1", "nom de l'objectif strat�gique", $engagement_thematique);
		$actionPere = Action::creerAction("C1Pere","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
		$actionFils1 = Action::creerAction("C1Fils1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
		$actionFils2 = Action::creerAction("C1Fils2","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50,$obj_strat);
	
		//passage par l'ajout à l'aide des identifiants et non des objets
		ActionAction::ajouterActionFils($actionPere->id, $actionFils1->id);
		ActionAction::ajouterActionFils($actionPere->id, $actionFils2->id);
		ActionAction::ajouterActionFils($actionFils1->id, $actionFils2->id);
		
		ActionAction::ajouterActionFils($actionPere->id, $actionFils1->id);
		ActionAction::ajouterActionFils($actionFils2->id, $actionFils2->id);
		ActionAction::ajouterActionFils($actionFils2->id, $actionFils1->id);
	}
	

	public function testListerActionFilsEchec(){
		$actionActions = ActionAction::listerFils(1);
		$this->assertNotEquals(3,count($actionActions));
		}
	
	public function testListerActionFilsOk(){
		$actionActions = ActionAction::listerFils(2);
		$this->assertEquals(2,count($actionActions));
		}
		
	public function testRecupererActionActionEchec(){
		$actionAction = ActionAction::recupererActionAction(124);
		$this->assertEquals(null,$actionAction);
		}
		
	public function testRecupererActionActionOk(){
		$actionAction = ActionAction::recupererActionAction(1);
		$this->assertEquals(1,$actionAction->id_action_pere);
		}
	
	public function testRecupererActionActionParIdEchec(){
		$actionAction = ActionAction::recupererActionActionParIdAction(12, 38);
		$this->assertEquals(null,$actionAction);
		}
		
	public function testRecupererActionActionParIdOk(){
		$actionAction = ActionAction::recupererActionActionParIdAction(1,2);
		$this->assertEquals(1,$actionAction->id_action_pere);
		}
			
	public function testEffacerActionActionOK(){
		ActionAction::supprimerActionAction(2);
		
		$actionActions = ActionAction::listerFils(1);
		$this->assertEquals(1,count($actionActions));
		}
} 

?>

 
