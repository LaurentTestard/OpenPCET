<?php 
use RedBean_Facade as R;

class ApiEnjeu {
	
	public static function addHttpRequest($app) {
		/*Ajout d'un objectif/enjeux*/
		$app->post('/enjeu/ajout/:enjeu', function ($enjeu) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$enj = ObjectifEnjeu::creerObjectifEnjeu($enjeu);
		
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($enj);
			return;
		});
		
		/*Récupération de l'ensemble des enjeux*/
		$app->get('/enjeux',function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			/*Appel à la fontion listerObjectifsEnjeux de la classe ObjectifEnjeu*/
			$enjeux = ObjectifEnjeu::listerObjectifsEnjeux();
			
			$enjeuxTab=array();
			foreach($enjeux as $enjeu){
				$enjeuTab=array();
				$enjeuTab['id']=$enjeu->id;
				$enjeuTab['nom_objectif_enjeu']=$enjeu->nom_objectif_enjeu;
				$enjeuxTab[]=$enjeuTab;
			}
			echo json_encode($enjeuxTab);
			return;
		});
		
		/*Ajout d'un indicateur*/
		$app->post('/indicateur/ajout/:nom/:enjeu', function ($nom,$enjeu) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$enj=ObjectifEnjeu::getObjectifEnjeu($enjeu);
			$id=$enj->id;				
			$ind = Indicateur::creerIndicateur($nom,$id);
		
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($ind);
			return;
		});
		
		/*Récupération de l'ensemble des indicateurs*/
		$app->get('/indicateurs',function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			/*Appel à la fontion listerIndicateurs de la classe Indicateur*/
			$indicateurs = Indicateur::listerIndicateurs();
				
			$indicateursTab=array();
			foreach($indicateurs as $indicateur){
				$indicateurTab=array();
				$indicateurTab['id']=$indicateur->id;
				$indicateurTab['nom_indicateur']=$indicateur->nom_indicateur;
				$indicateurTab['id_enjeu']=$indicateur->id_enjeu;
				$indicateursTab[]=$indicateurTab;
			}
			echo json_encode($indicateursTab);
			return;
		});
		
		
		/*Ajout d'un nouvel indicateur*/
		$app->post('/indicateurvaleur/ajout/', function () use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			$tableauParametres = json_decode($app->request->getBody(), true);
			$indicateurValeur = Indicateur::ajoutIndicateurAction($tableauParametres['nom'],$tableauParametres['valeur'],$tableauParametres['annee'],$tableauParametres['id_action']);
				
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			echo json_encode($indicateurValeur);
			return;
		});
		
		return $app;		
	}
	
	
}
?>
