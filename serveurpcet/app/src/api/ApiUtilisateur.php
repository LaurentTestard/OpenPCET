<?php

use RedBean_Facade as R;

class ApiUtilisateur {
	public static function addHttpRequest($app) {
		
		/*Récupérer tous les utilisateurs de la plateforme*/		
		$app->get('/utilisateur/list', function () use ($app) {
			
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$utilisateurs = FctUtilisateur::getAllUtilisateur($_SESSION['login']);
			if($utilisateurs==null){
				$app->response->status(401);
				$rep = array('success' => 'pasAcces');
				echo json_encode($rep);
				return;
			}
			
			echo json_encode($utilisateurs);

		});
		
		/*Récupération des informations de l'utilisateur connecté*/
		$app->get('/utilisateur', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctUtilisateur::getUtilisateur($_SESSION['login']);
			echo json_encode($rep);
			return;
		});
		/*Récupération d'un utilisateur*/
		$app->get('/utilisateur/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctUtilisateur::getUtilisateurParId($id);
			echo json_encode($rep);
			return;
		});
		
		/*Suppression d'un utilisateur*/
		$app->delete('/utilisateur/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$rep = FctUtilisateur::deleteUtilisateur($_SESSION['login'],$id);
			if($rep==null){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Ajout d'un nouvel utilisateur*/
		$app->put('/utilisateur/ajout', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$injson = json_decode($app->request->getBody());
			$rep = FctUtilisateur::ajoutUtilisateur($injson);
			if($rep==null){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		/*Modification des informations d'un utilisateur*/
		$app->post('/utilisateur/miseajour', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			$injson = json_decode($app->request->getBody());
		
			$rep = FctUtilisateur::miseAJourUtilisateur($injson);
			if($rep==null){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Récupération des actions non associées à un utilisateur*/
		$app->get('/utilisateur/actionnonlie/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctUtilisateur::getAllActionNonLie($id);
			echo json_encode($rep);
			return;
		});
		
		$app->get('/utilisateur/actionlie/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep=FctUtilisateur::getAllActionLie($id);
			echo json_encode($rep);
			return;
		});
		
		/*Association d'une action et un utilisateur*/
		$app->put('/utilisateur/lier/:idAction/:idUtilisateur', function ($idAction,$idUtilisateur) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctUtilisateur::lieUtilisateurAction($idUtilisateur, $idAction);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Suppression du lien entre une action et un utilisateur*/
		$app->put('/utilisateur/delier/:idAction/:idUtilisateur', function ($idAction,$idUtilisateur) use ($app) {
					$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctUtilisateur::delieUtilisateurAction($idUtilisateur, $idAction);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Réinitialisation du mot de passe d'un utilisateur*/
		$app->post('/utilisateur/resetpassword/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			$user = FctUtilisateur::resetPassword($id);
			if($user !=null)
			{
				$rep = array('success' => 'ok');
				echo json_encode($rep);
				return;
			}
			$rep = array('success' => 'mauvais id');
			echo json_encode($rep);
			return;
		});
			
		return $app;
	}
}
?>