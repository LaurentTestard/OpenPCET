<?php 
use RedBean_Facade as R;

class ApiAction {
	
	public static function addHttpRequest($app) {

		$app->put('/pcaet/actions/', function () use($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);	
				return;
			}
			//Recuperation de l'ensemble des informations de l'action
			$tableauParametres = json_decode($app->request->getBody(), true);
			//Verification des donnees modifiees pour modifier les champs concernes
			$tabParamVerifies = FctAction::verifModifAction($tableauParametres);
			$idUtilisateur = $_SESSION['id'];
			//Modification effective des champs concernes
			$action = FctAction::modifierAction($idUtilisateur,$tabParamVerifies);
			if ($action == null){
				echo json_encode(array('success' => 'ko'));
				return;
			}
			echo json_encode(array('success' => 'ok'));
			return;
		});

		/*R�cup�ration de l'ensemble des actions*/
		$app->get('/pcaet/actions',function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			/*Appel � la fontion listerActions de la classe FctAction*/
			$tableau = FctAction::listerActions();
			echo json_encode($tableau);
			return;
		});
		
		/*R�cup�ration des actions li�es � un responsable d'action*/
		$app->get('/pcaet/actions/:id',function($id) use($app) {
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableau = FctAction::listerActionsResponsable($id);
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($tableau);
			return;
		});
		
		/*R�cup�ration des phases actuelles d'une action*/
		$app->get('/pcaet/actions/phases/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$phases = FctAction::listerPhasesAction($idAction);
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($phases);
			return;
		});
		
		/*R�cup�ration des phases de d�part d'une action*/
		$app->get('/pcaet/actions/phasesdep/:idaction', function ($idAction) use ($app){
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				$phasesdep = FctAction::listerPhasesDepAction($idAction);
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				echo json_encode($phasesdep);
				return;
			});
		
		/*R�cup�ration des indiacteurs d'une action. A revoir !*/
		$app->get('/pcaet/actions/indicateurs/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$indicateurs = FctIndicateur::ListerIndicateurAction($idAction);
			echo json_encode($indicateurs);
			return;
		});
		
		/*R�cup�ration de l'objectif strat�gique d'une action*/
		$app->get('/pcaet/actions/objectifs/:idaction', function ($idAction) use ($app){
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$objectifs = FctObjectifEnjeu::listerObjectifsEnjeuxIdAction($idAction);
			echo json_encode($objectifs);
			return;
		});
		
		/*R�cup�ration de toutes les informations d'une action*/
		$app->get('/pcaet/action/:id',function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$tableau = FctAction::infoActionComplete($id);
			/*????*/
			$tableau = FctAction::ajoutAttributEdit($_SESSION['id'],$id,$tableau);
			echo json_encode($tableau);
			return;
		});
		
		/*R�cup�ration de l'avancement d'une action*/
		$app->get('/pcaet/actions/etats', function () use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$etats = FctAction::listerEtatActions();
			echo json_encode($etats);
			return;
		});
		
		/*R�cup�ration du budget d'une action. A revoir !*/
		$app->get('/pcaet/actions/budget/:id', function ($id) use ($app){
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$action = Action::getActionById($id);
			$actionTab=array();
			$actionTab['total']=$action->budget_total;
			$actionTab['consomme']=$action->budget_consomme;
			echo json_encode($actionTab);
			return;
		});
		
		/*Modification du budget d'une action. A revoir !*/
		$app->put('/pcaet/actions/budget/:id', function ($id) use ($app){
			
			$injson = json_decode($app->request->getBody());
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
		//$utilisateur = Utilisateur::recupererUtilisateur($_SESSION['id']);

			
			$action = FctAction::modifierBudget($id, $injson->total, $injson->consomme, $_SESSION['login']);
			if ($action != null){
				echo json_encode(array('success' => 'ok'));
				return;
			}
			echo json_encode(array('success' => 'ko'));
			return;
		});
		
		/*R�cup�ration des traces de modifications d'une action*/
		$app->get('/pcaet/actions/historique/:id', function ($id) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			$action = Action::getActionById($id);
			$modifications = FctLog::listerLogsParAction($action->id);
			echo json_encode($modifications);
			return;
		});
			
		/*Ajout d'une nouvelle action*/	
		$app->put('/action/ajout', function () use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				/*if(!Utilisateur::estChefDeProjet($_SESSION['login'])){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}*/
				$injson = json_decode($app->request->getBody());
				$rep = FctAction::ajoutAction($injson);
				if($rep==null){
					$app->response->status(401);
					$rep1 = array('success' => 'ko');
					echo json_encode($rep1);
					return;
				}
				$rep1 = array('success' => 'ok');
				echo json_encode($rep);
				return;
			});
			
			/*G�n�ration d'une fiche action en pdf*/
			$app->POST('/pcaet/fiche/genere/:code_action', function ($code_action) use ($app) {				
				
				$nom=$code_action.'.pdf';
				if (file_exists('/fichier/'.$nom)) {
					$idfic=Fiche::recupererFicheByNom($nom);
					Fiche::supprimerFiche($idfic);
				}
				FctFiche::creerFiche($code_action);
				return;
				});
			
			/*Envoi de mail*/
			$app->post('/serveurpcet/index.php/action/mail', function () use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				$injson = json_decode($app->request->getBody());
				$rep = FctAction::envMail($injson);
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
				
			/*R�cup�ration des documents qui ne sont pas li�s � une action*/
			$app->get('/actiondocumentnonlie/:id', function ($id) use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
				$rep = FctAction::getAllDocumentNonLie($id);
				echo json_encode($rep);
				return;
			});
		
		return $app;		
	}
	
	
}
?>
