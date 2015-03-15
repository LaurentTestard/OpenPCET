<?php

use RedBean_Facade as R;

class ApiBudget {
	public static function addHttpRequest($app) {
		
		/*Ajout d'un nouveau budget à une action*/		
		$app->post('/budget/ajout/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$tableauParametres = json_decode($app->request->getBody(), true);
			$budgetTotal=$tableauParametres['budget_total']+$tableauParametres['commcomm'];
			$budget = Budget::ajoutBudget($tableauParametres['annee'],$tableauParametres['idaction'],$tableauParametres['renseigner'],$tableauParametres['commcomm'],$tableauParametres['budget_consomme'],$tableauParametres['commentaire'],$budgetTotal);
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($budget);
			return;
		});
		
		/*Récupération des phases de départ d'une action*/
		$app->get('/budgets/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$budgets = Budget::listerBudgets($idAction);
			$budgetsAEncoder = array();			
			foreach ($budgets as $budget){
				//Remplissage du tableau de l'action courante pour encodage
				$budgetAEncoder=array();
				$budgetAEncoder['id']=$budget->id;
				$budgetAEncoder['annee']=$budget->annee;
				$budgetAEncoder['idaction']=$budget->idaction;
				$budgetAEncoder['renseigner']=$budget->renseigner;
				$budgetAEncoder['commcomm']=$budget->commcomm;
				$budgetAEncoder['budget_consomme']=$budget->budget_consomme;
				$budgetAEncoder['budget_total']=$budget->budget_total;
				$budgetAEncoder['commentaire']=$budget->commentaire;
				$budgetsAEncoder[]=$budgetAEncoder;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($budgetsAEncoder);
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
