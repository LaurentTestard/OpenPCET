<?php

use RedBean_Facade as R;

class ApiFiche {
	public static function addHttpRequest($app) {
		
		/*Création d'une fiche action*/
		$app->post('/pcaet/fiche/:code_action', function ($code_action) use ($app) {
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$fiche = Fiche::creerFiche($code_action.".pdf");
			$path = 'fichier/';
			$uploadfile = $path . $fiche->nom_fiche;
			
			Fiche::ajoutPath($fiche,$uploadfile);
				
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			
			$action=Action::getAction($code_action);
			if (file_exists($uploadfile)) {
				unlink($uploadfile);
			}
			$pdf=new FPDF('P','mm','A4');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(5);
			$pdf->Cell(50,10,'Code action : '.$action->code_action);
			$pdf->Ln(10);
			$chaine1=utf8_decode($action->nom_action);
			$pdf->MultiCell(0,5,'Nom action !  : '.$chaine1);
			$pdf->Ln(10);
			$pdf->Cell(5);
			$chaine2=utf8_decode($action->contexte_action);
			$pdf->MultiCell(0,5,'Contexte action : '.$chaine2);
			$pdf->Ln(10);
			
			$content = $pdf->Output($uploadfile,'F');
			
			$res = $app->response();
			$res['Content-Description'] = 'File Transfer';
			$res['Content-Type'] = 'application/octet-stream';
			$res['Content-Disposition'] ='attachment; filename=' . $fiche->nom_fiche;
			$res['Content-Transfer-Encoding'] = 'binary';
			$res['Expires'] = '0';
			$res['Cache-Control'] = 'must-revalidate';
			$res['Pragma'] = 'public';
			$res['Content-Length'] = filesize($uploadfile);
			readfile($uploadfile);

			$rep = array('success' => 'ok','file'=>$uploadfile);
			echo json_encode($rep);
			return;
		});
		
		
		/*Récupération de toutes les fiches sur la plateforme*/
		$app->get('/fiche/list', function () use ($app) {
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
			
				$fics=array();
				$fiches = Fiche::getAllFiche();
				foreach($fiches as $fiche){
					$fic=array();
					$fic['id']=$fiche->id;
					$fic['nom_fiche']=$fiche->nom_fiche;
					$fics[]=$fic;
				}
				echo json_encode($fics);
			});
		
		/*Exportation des fiches en pdf*/
		$app->post('/fiche/imprimer/', function () use ($app) {
			
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
			
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
				
			//rÃ©cupÃ©ration des paramÃ¨tres
			$tab1 = json_decode($app->request->getBody(), true);
			$tab2=array();
			foreach($tab1 as $k=>$v){
				if($k==$v){
					$tab2[]=$k;
				}
			}		
			
			FctFiche::imprimerFiches($tab2);
				
			echo json_encode(array('success' => 'ok'));
			
			return;
		});
		
		
		/*Exportation de toutes les fiches de la plateforme en pdf*/
		$app->post('/fiche/toutimprimer/', function () use ($app) {
				
			$app->response->header('Content-Type', 'application/json;charset=utf-8');
				
			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
		
			FctFiche::toutImprimer();
		
			echo json_encode(array('success' => 'ok'));
				
			return;
		});
		
	   /*Création de toutes les fiches sur le serveur*/
	   $app->post('/fiche/initfiches/', function () use ($app) {
					
				$app->response->header('Content-Type', 'application/json;charset=utf-8');
					
				if(!isset($_SESSION['login']) || $_SESSION['login']==''){
					$app->response->status(401);
					$rep = array('success' => 'ko');
					echo json_encode($rep);
					return;
				}
					
				FctFiche::creerToutesFiches();
			
				echo json_encode(array('success' => 'ok'));
					
				return;
			});
		
		
		/*Téléchargement d'une fiche*/
		$app->get('/fiche/:id', function ($id) use ($app) {
			
			$doc = Fiche::recupererFiche($id);
			
			
			if($doc!=null){
				$file = $doc->path;

				if(file_exists($file)){
										
					$res = $app->response();
					$res['Content-Description'] = 'File Transfer';
					$res['Content-Type'] = 'application/octet-stream';
					$res['Content-Disposition'] ='attachment; filename=' . $doc->nom_fiche;
					$res['Content-Transfer-Encoding'] = 'binary';
					$res['Expires'] = '0';
					$res['Cache-Control'] = 'must-revalidate';
					$res['Pragma'] = 'public';
					$res['Content-Length'] = filesize($file);
					readfile($file);
				}
			}
			return;
		});
		
		
		
		
		
		return $app;		
	}
}
?>
