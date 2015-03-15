<?php

use RedBean_Facade as R;

class ApiObjectifStrategique {
	public static function addHttpRequest($app) {
		
		
		/*Récupérer tous les objectifs stratégique*/
		$app->get('/objectifs', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			/*$objs_strats = array();
			$objs_strats['id']="111";
			$objs_strats['code_objectif_strategique']="T45";
			$objs_strats['nom_objectif_strategique']="Nom test";
			$objs_strats['engagementthematique_id']="18";*/
			echo json_encode(FctObjectifStrategique::tousLesObjectifs());
		});
		
		/*Récupérer toutes les thématiques*/
		$app->get('/engagements', function () use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				
				echo json_encode(FctEngagementThematique::tousLesEngagements());
		});
		
		/*Récupérer les objectifs stratégique d'une thématique*/
		$app->get('/engagements/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
		
			echo json_encode(FctObjectifStrategique::getObjsparEngagement($id));
		});
		
		/*Associer une action à un objectif stratégique*/
		$app->post('/objectif/lieraction/:idaction/:idobj', function ($idaction,$idobj) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctObjectifStrategique::lierActionObjs($idaction,$idobj);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
		});
		
			/*Associer une action à un objectif stratégique avec code Action*/
			$app->post('/objectif/lieractionObj/:codeaction/:nomobj', function ($codeaction,$nomobj) use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
					
				FctObjectifStrategique::lierActionObjsCAction($codeaction,$nomobj);
				$rep = array('success' => 'ok');
				echo json_encode($rep);
			});
			
		return $app;		
	}
	
}
?>
