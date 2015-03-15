<?php 

use RedBean_Facade as R;

class ApiActionDocument {
	
	public static function addHttpRequest($app) {

		/*R�cup�ration des documents li�s � une action*/
		$app->get('/action/documents/:id', function ($id) use ($app) {

			if(!isset($_SESSION['login']) || $_SESSION['login']==''){
				$app->response->status(401);
				$rep = array('success' => 'ko');
				echo json_encode($rep);
				return;
			}
			
			$docs=array();
			echo json_encode(FctActionDocument::documentsDAction($id));
			return;
		});
		
		return $app;		
	}
}
?>
