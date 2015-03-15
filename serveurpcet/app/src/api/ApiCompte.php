<?php

use RedBean_Facade as R;

class ApiCompte {
	public static function addHttpRequest($app) {
		
		/*Changement de mot de passe d'un utilisateur*/		
		$app->post('/compte/password', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$injson = json_decode($app->request->getBody());
			$user = Utilisateur::getUtilisateur($_SESSION['login']);
			FctCompte::changerMotDePasse($user, $injson->motdepasse_actuel, $injson->motdepasse_nouveau);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Modification des information d'un compte utilisateur*/
		$app->post('/compte/miseajour', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$injson = json_decode($app->request->getBody());
			$user = Utilisateur::getUtilisateur($_SESSION['login']);
			FctCompte::miseAJourUtilisateur($user, $injson);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		
		
		
		return $app;
	
	}
	
}
?>