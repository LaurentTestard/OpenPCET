<?php 

use RedBean_Facade as R;

class ApiACtionAction {
	
	public static function addHttpRequest($app) {
		
		/*Association de deux actions*/		
		$app->put('/action/lier/:idActionPere/:idActionFils', function ($idActionPere,$idActionFils) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctActionAction::lierActionPereFils($idActionPere, $idActionFils, $_SESSION['login']);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Récupération des actions qui ne sont pas associées à une action*/
		$app->get('/action/actionnonaction/:id',function ($id) use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				$tableau = FctActionAction::recupActionsnonliees($id);
				echo json_encode($tableau);
				return;
			});
		
		/*Suppression du lien entre deux actions*/
		$app->put('/action/delier/:idActionPere/:idActionFils', function ($idActionPere,$idActionFils) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			FctActionAction::delierActionPereFils($idActionPere, $idActionFils,$_SESSION['login']);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
			return;
		});
		
		/*Récupération des actions liées à une action*/
		$app->get('/action/actionaction/:action_id',function ($action_id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$rep = FctActionAction::recupActions($action_id);
			echo json_encode($rep);
			return;
		});
			
		
		return $app;
	}
}
?>
