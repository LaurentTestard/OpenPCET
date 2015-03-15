<?php
use RedBean_Facade as R;
class FctCompteTest extends PHPUnit_Framework_TestCase {
	
	public static function setUpBeforeClass(){
 		R::nuke();
 		$utilisateur = Utilisateur::creerUtilisateur("login", "nom", "prenom", md5("test"), 1, "", "", "", "");
	}
	
	public function testChangerMotDePasseFail(){
		$user = Utilisateur::getUtilisateur("login");
		FctCompte::changerMotDePasse($user, "tes", "new");
		$userAuth = Utilisateur::authentification("login", md5("new"));
		$this->assertEquals(Null, $userAuth);
	}
	
	public function testChangerMotDePasseWin(){
		$user = Utilisateur::getUtilisateur("login");
		FctCompte::changerMotDePasse($user, "test", "new");
		$userAuth = Utilisateur::authentification("login", md5("new"));
		$this->assertEquals("login", $userAuth->login_utilisateur);
	}
}
?>
