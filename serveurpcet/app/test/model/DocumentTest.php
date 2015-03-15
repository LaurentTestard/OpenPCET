<?php
use RedBean_Facade as R;

class DocumentTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
 		Action::creerAction("Test", "Nom action", true, true, true, false, true, false, "2014-03-12", "contexte", "objectif quantitatif", 3, 5, 100, "MOA", "referent", 100000, 5000, "crAction");
 		$document = Document::creerDocument("Document1");
	}
	
 	public function testRecupererDocumentFail(){
 		$document = Document::recupererDocument(2);
 		$this->assertEquals(null,$document);
 	}
		
	public function testRecupererDocumentOk(){
		$document = Document::recupererDocument(1);
		$this->assertEquals("Document1",$document->nom_document);
	}
	
	public function testChercherDocumentOk(){
		$documents = Document::chercherDocuments("Document1");
		$this->assertEquals(1, count($documents));
	}
	
	public function testChercherDocumentFail(){
		$documents = Document::chercherDocuments("Document2");
		$this->assertEquals(0, count($documents));
	}
	
	public function testRenommerDocumentFail(){
		$document = Document::renommerDocument(2, "Document1");
		$this->assertEquals(null, $document);
	}
	
	public function testRenommerDocumentOk(){
		$document = Document::renommerDocument(1, "Document2");
		$this->assertEquals("Document2", $document->nom_document);
	}
	
	public function testSuppressionDocumentFail(){
		$retour = Document::supprimerDocument(2);
		$this->assertEquals(null, $retour);
	}
	
// 	public function testSuppressionDocumentOK(){
// 		$retour = Document::supprimerDocument(1);
// 		$this->assertEquals("OK", $retour);
// 	}
	
// 	public function testOK(){
// 		$document = Document::recupererDocument(1);
// 		$this->assertEquals(null, $document);
// 	}
} 

?>
