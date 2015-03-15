<?php

use RedBean_Facade as R;

class ApiConnexion {
	public static function addHttpRequest($app) {
		
		/*Verification de l'identité d'un utilisateur avec son mot de passe et son login*/
		$app->get('/compte/authentification', function () use ($app) {
			$authUser = $app->request->headers('PHP_AUTH_USER');
			$authPass = $app->request->headers('PHP_AUTH_PW');
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			
			$user = Utilisateur::authentification($authUser, md5($authPass));
			
			if($user == null){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
			}
			else{
				$_SESSION['login']=$user->login_utilisateur;
				$_SESSION['id']=$user->id;
				$rep = array('success' => 'ok','login' => $user->login_utilisateur);
				echo json_encode($rep);
			}
		});
		
		/*Déconnexion de l'utilisateur*/
		$app->get('/compte/deconnexion', function () use ($app) {
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			$_SESSION['login']=null;
			session_destroy();
		});
		
		/*Récupération de toutes les informations d'un utilisateur*/
		$app->get('/compte/info', function () use ($app) {
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$user = FctUtilisateur::getUtilisateur($_SESSION['login']);
			echo json_encode($user);
			return;
		});
		
		
	}  
}
?>