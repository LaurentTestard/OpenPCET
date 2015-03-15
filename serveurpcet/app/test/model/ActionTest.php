<?php
use RedBean_Facade as R;

class ActionTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
		$action = Action::creerAction("C1","nomAction",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		
		$document = Document::creerDocument("Document1");
		$utilisateur = Utilisateur::creerUtilisateur("jean","ja","jaque", md5('admin'), 1,"communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");

		$partenaire = Partenaire::creerPartenaire("pierre");
		$crAction = CrAction::creerCrAction("bien", true, $action);
		$objAction = ObjectifEnjeu::creerObjectifEnjeu("objectif1");
		$objstrategique = ObjectifStrategique::creerObjectifStrategique("CO", "NO");
 		$phase = Phase::creerPhase("nom de la phase", "ceci est un commentaire","22-03-2014", "28-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		$phase1 = Phase::creerPhase("nom de la phase1", "ceci est un commentaire","21-03-2014", "29-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		$phase2 = Phase::creerPhase("nom de la phase2", "ceci est un commentaire","24-03-2014", "30-03-2014", "22-03-2014", "22-03-2014", "ceci est une description", 1, "ceci est un service", 1,"en cours");
 		
 		ObjectifStrategique::addAction($objstrategique, $action);
	 	Partenaire::ajouterAction($partenaire, $action);
		$engage = EngagementThematique::creerEngagementThematique("C", "nomengagementth�matique");
		EngagementThematique::addObjectifStrategique($engage, $objstrategique);
		
		
	 	Action::addDocument($action,$document);
		Action::addUtilisateur($action,$utilisateur);
		Action::addPartenaire($action, $partenaire);
		Action::addCrAction($action, $crAction);
		Action::addObjectifenjeu($action, $objAction);
		Action::addPhase($action, $phase);
 		Action::addPhase($action, $phase1);
 		Action::addPhase($action, $phase2);
	}
	
	public function testgetActionRight(){
		$action = Action::getAction("C1");
		$this->assertEquals("nomAction",$action->nom_action);
	}
	
	public function testgetActionWrong(){
		$action = Action::getAction("C3");
		$this->assertEquals(null,$action);
	}
	
	public function testAddAction(){
		$action = Action::getAction("C1");
		$doc = Document::recupererDocumentByNom("Document1");

		$this->assertEquals(1,count($action->sharedDocument));
		$this->assertEquals(1,count($doc->sharedAction));
		$this->assertEquals("nomAction",$doc->sharedAction[1]->nom_action);
	}

	public function testAddUtilisateur(){
		$action = Action::getAction("C1");
		$utilisateur = Utilisateur::getUtilisateur("jean");
		$this->assertEquals(1,count($action->sharedUtilisateur));
		$this->assertEquals(1,count($utilisateur->sharedAction));
		$this->assertEquals("nomAction",$utilisateur->sharedAction[1]->nom_action);
	}

	public function testAddPartenaire(){
		$action = Action::getAction("C1");
		$partenaire = Partenaire::recupererPartenaire(1);
		$this->assertEquals(1,count($action->sharedPartenaire));
		$this->assertEquals(1,count($partenaire->sharedAction));
		$this->assertEquals("nomAction",$partenaire->sharedAction[1]->nom_action);
	}
	
	public function testAddCrAction(){
		$action = Action::getAction("C1");
		$this->assertEquals(1,count($action->ownCraction));
		$this->assertEquals("bien",$action->ownCraction[1]->description_cr_action);
	}
	
	public function testAddObjectifenjeu(){
		$action = Action::getAction("C1");
		$this->assertEquals(1,count($action->ownObjectifenjeu));
		$this->assertEquals("objectif1",$action->ownObjectifenjeu[1]->nom_objectif_enjeu);
		$this->assertEquals("C1",$action->ownObjectifenjeu[1]->action->code_action);
	}
	
	
	public function testAddPhase(){
		$action = Action::getAction("C1");
		$this->assertEquals(3,count($action->ownPhase));
	}
	
	public function testGetAllActions(){
		$actions = Action::getAllAction();
		$this->assertEquals(1,count($actions));
	}
	
	public function testListerActions(){
		$actions = FctAction::listerActions();
		$this->assertEquals(1, count($actions));
	}
	
	public function testListerActionsResponsable(){
		$actions = FctAction::listerActionsResponsable(1);
	}
	
	public function testDetailsAction(){
		$action = Action::getAction("C1");
		$actions = FctAction::infoActionComplete($action->id);
	}
	
	public function testEffacerPartenaireActionOk(){
		$action = Action::getAction("C1");
		$partenaire = Partenaire::recupererPartenaire(1);
		
		Action::deletePartenaire($action, $partenaire);

		$this->assertEquals(0,count($action->sharedPartenaire));
	}
}
?>
