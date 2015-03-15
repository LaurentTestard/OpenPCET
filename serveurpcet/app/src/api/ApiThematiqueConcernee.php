<?php

use RedBean_Facade as R;

class ApiThematiqueConcernee {
	public static function addHttpRequest($app) {
		
		
		$app->get('/thematiques', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			echo json_encode(FctThematiqueConcernee::toutesLesThematiques());
		});
		
		$app->put('/thematiques/lierAction/:idAction/:idThematique', function ($idaction,$idthematique) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			FctThematiqueConcernee::lierActionThematique($idaction,$idthematique);
			$rep = array('success' => 'ok');
			echo json_encode($rep);
		});
		
		return $app;		
	}
	
	
	
	
}
?>
