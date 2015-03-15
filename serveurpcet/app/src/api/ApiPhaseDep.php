<?php 

use RedBean_Facade as R;

class ApiPhaseDep {
	
	public static function addHttpRequest($app) {
		
		// Supprimer une phase de départ
		$app->delete('/pcaet/actions/phasesdep/:idphasedep', function ($idPhasedep) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$idPhasedep = intval($idPhasedep);
			
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phasedep = Phasedep::recupererPhasedep($idPhasedep);
			$ordrePhasedep=intval($tableauParametres['ordre_phasedep']);
			$action = FctPhaseDep::supprimerPhasedep($idPhasedep, $_SESSION['id'], $phasedep->action->code_action, $ordrePhasedep, date('Y-m-d H:i:s'), "Suppression d'une phasedep");
			if($action == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}				
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode(array('success' => 'ok'));
			return;
		});
		
		// Ajouter une phase de départ
		$app->post('/pcaet/actions/phasesdep/ajout_phasedep', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phasedep = FctPhaseDep::ajouterPhasedep($tableauParametres, $_SESSION['login']);
			/*if($phasedep == null){
				echo json_encode(array('success' => 'ko add phasedep'));
				return;
			}*/
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($phasedep);
			return;
		});
		
		// Visualiser une phase de départ
		$app->get('/pcaet/actions/phasesdep/details/:idaction/:idphasedep', function ($idAction, $idPhasedep) use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$phasedep = FctPhaseDep::visualiserPhasedep($idAction, $idPhasedep);
			if($phasedep == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($phasedep);
			return;
		});
		
		// Modifier une phase de départ
		$app->put('/pcaet/actions/phasesdep/', function () use ($app){
			//Recuperation de l'ensemble des informations de l'action
			$tableauParametres = json_decode($app->request->getBody(), true);
			$phasedep = FctPhasedep::modifierPhasedepAction($tableauParametres, $_SESSION['login']);
			if ($phasedep == null){
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
