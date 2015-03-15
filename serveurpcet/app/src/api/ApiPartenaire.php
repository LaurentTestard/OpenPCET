<?php 

use RedBean_Facade as R;

class ApiPartenaire {
	
	public static function addHttpRequest($app) {
		
		/*Suppression d'un partenaire*/
		$app->delete('/partenaire/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			Partenaire::supprimerPartenaire($id);
			echo json_encode(array('success' => 'ok'));
			return;
		});

		/*Ajout d'un nouveau partenaire*/
		$app->post('/partenaire/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}	
			$tableauParametres = json_decode($app->request->getBody(), true);
			FctPartenaire::ajouterUnPartenaire($tableauParametres['id'], $tableauParametres['nom_partenaire'],$_SESSION['login']);
			echo json_encode(array('success' => 'ok'));
			return;
		});
	
		return $app;
	}
	
	
}
?>
