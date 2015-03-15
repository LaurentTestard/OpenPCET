<?php
 

use RedBean_Facade as R;
class UtilisateurTest extends PHPUnit_Framework_TestCase {
	
    public static function setUpBeforeClass()
    {
        R::nuke();
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
		$utilisateur2 = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), "chef de projet","communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
		
		$crAction = CrAction::creerCrAction('descriptioncraction2', true);
		Utilisateur::addCrAction($utilisateur, $crAction);
    }
    
public function testAuthentificationLoginFail(){
		$user = Utilisateur::authentification("Foret", md5("aa"));
		$this->assertEquals(null,$user);
	}
	
	public function testAuthentificationPasswordFail(){
		$user = Utilisateur::authentification("Foreta", md5("admi"));
		$this->assertEquals(null,$user);
	}
	
	public function testAuthentificationOk(){
		$user = Utilisateur::authentification("Foreta", md5("admin"));
		$this->assertEquals("Foreta",$user->login_utilisateur);
	}
	
	public function testRecCr(){
		$user = Utilisateur::getUtilisateur("Foreta");
		$this->assertEquals("Foreta",$user->login_utilisateur);
		$this->assertEquals(1,count($user->ownCraction));
	}
	
	public function testGetAllUtilisateur(){
		$users = FctUtilisateur::getAllUtilisateur("Foreta");
	}
	
	public function testAjoutUtilisateur(){
		$utilisateur = new stdClass();
		$utilisateur->login_utilisateur = "riknundoo";
		$utilisateur->prenom_utilisateur= "rik";
		$utilisateur->nom_utilisateur= "nun";
		$utilisateur->mot_de_passe= "pass";
		$utilisateur->role_utilisateur= "visiteur";
		$utilisateur->organisation= "IMAG";
		$utilisateur->email= "riknundoo@gmail.com";
		$utilisateur->tel_interne= "6545";
		$utilisateur->tel_standard= "0648227248";
		$user = FctUtilisateur::ajoutUtilisateur($utilisateur);
	}

}
