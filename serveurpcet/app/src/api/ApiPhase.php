<?php 

use RedBean_Facade as R;

class ApiPhase {
	
	public static function addHttpRequest($app) {
		
		// Supprimer une phase actuelle
		$app->delete('/pcaet/actions/phases/:idphase', function ($idPhase) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$idPhase = intval($idPhase);
			
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phase = Phase::recupererPhase($idPhase);
			$ordrePhase=intval($tableauParametres['ordre_phase']);
			$action = FctPhase::supprimerPhase($idPhase, $_SESSION['id'], $phase->action->code_action, $ordrePhase, date('Y-m-d H:i:s'), "Suppression d'une phase");
			if($action == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}				
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		// Ajouter une phase
		$app->post('/pcaet/actions/phases/ajout_phase/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phase = FctPhase::ajouterPhase($tableauParametres, $_SESSION['login']);
			if($phase == null){
				echo json_encode(array('success' => 'ko add phase'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			return;
		});
		
		// Visualiser une phase
		$app->get('/pcaet/actions/phases/details/:idaction/:idphase', function ($idAction, $idPhase) use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$phase = FctPhase::visualiserPhase($idAction, $idPhase);
			if($phase == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($phase);
			return;
		});
		
		// Modifier une phase
		$app->put('/pcaet/actions/phases/', function () use ($app){
			//Recuperation de l'ensemble des informations de l'action
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phase = FctPhase::modifierPhaseAction($tableauParametres, $_SESSION['login']);
			if ($phase == null){
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
