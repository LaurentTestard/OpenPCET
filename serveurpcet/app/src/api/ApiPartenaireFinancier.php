<?php

use RedBean_Facade as R;

class ApiPartenaireFinancier {
	public static function addHttpRequest($app) {
		
		/*Ajout d'un nouveau partenaire Financier*/		
		$app->post('/partenaireFinancier/ajout/:idaction/:annee/:nomPart/:budgetPart', function ($idaction,$annee,$nomPart,$budgetPart) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			//$tableauParametres = json_decode($app->request->getBody(), true);
			$budget=Budget::recupererBudgetParActAnnee($idaction,$annee);
			$idBudget=$budget->id;
			$partenaireFin = PartenaireFinancier::ajoutPartenaireFin($nomPart,$budgetPart,$idBudget);
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			return;
		});
		
		
	
		/*Suppression d'un budget*/
		$app->delete('/budget/delete/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			json_encode(array("success"=>Budget::supprimerBudget($id)));
		});
		
		return $app;		
	}
}
?>