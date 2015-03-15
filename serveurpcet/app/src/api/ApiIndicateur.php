<?php 

use RedBean_Facade as R;

class ApiIndicateur {
	
	public static function addHttpRequest($app) {
		
		// Supprimer un indicateur
		$app->delete('/pcaet/actions/indicateurs/:idIndic', function ($idIndic) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$indicateur = FctIndicateur::supprimerIndicateur($idIndic, $_SESSION['login']);
			if($indicateur == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		
		// Ajouter un indicateur
		
		$app->post('/pcaet/actions/indicateurs/ajout_indicateur/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableauParametres = json_decode($app->request->getBody(), true);
			$indicateur = FctIndicateur::ajouterIndicateur($tableauParametres , $_SESSION['login']);
			if($indicateur == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			return;
		});
			
		
		// Modifier un indicateur		
		$app->put('/pcaet/actions/indicateurs/', function () use($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}	
			$tableauParametres = json_decode($app->request->getBody(), true);
						
			$indicateur = FctIndicateur::modifierIndicateur($tableauParametres , $_SESSION['login']);
			if ($indicateur == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			return;
		});
		
		return $app;
	}
}
?>
