<?php
use RedBean_Facade as R;

class FctLog {
	/*Lister toutes les traces des modifications*/
	public static function listerLogs(){

		$logs = Log::getLogs();
		
		$listeLogs = FctLog::creerTableauLogPourJson($logs);
	
		return $listeLogs;
	}
	
	/*Lister les traces des modifications par action*/
	public static function listerLogsParAction($idAction){
	
		$action = Action::getActionById($idAction);
		
		if($action == null){
			return;
		}
		$logs = Log::getLogsParCodeAction($action);
	
		$listeLogs = FctLog::creerTableauLogPourJson($logs);
	
		return $listeLogs;
	}
	
	/*Repr�sentation des traces sous forme de tableau*/
	private static function creerTableauLogPourJson($logs){
		$listeLogs = array();
		
		foreach($logs as $log){
			$listeLog=array();
			$listeLog['id']=$log->id;
			$listeLog['datecomplete_modification']=$log->date_modification;
			$listeLog['date_modification']=$log->date_modification;
			$listeLog['jour_modification']=date("d/m/Y",strtotime($log->date_modification));
			$listeLog['heure_modification']=date("H:i:s",strtotime($log->date_modification));
			$listeLog['type_modification']=$log->type_modification;
			$listeLog['code_action']=$log->code_action;
			$listeLog['description_modification']=$log->description_modification;
			$listeLog['login_utilisateur']=$log->login_utilisateur;
			
			$nomUtilisateur="";
			$prenomUtilisateur="";
			
			$utilisateur=Utilisateur::getUtilisateur($log->login_utilisateur);
			if($utilisateur != null){
				$nomUtilisateur=$utilisateur->nom_utilisateur;
				$prenomUtilisateur=$utilisateur->prenom_utilisateur;
				
			}
			
			$listeLog['nom_utilisateur']=$nomUtilisateur;
			$listeLog['prenom_utilisateur']=$prenomUtilisateur;


		
			//premier cas de tests ou l'utilisateur n'est plus dans le système
			/*if($utilisateur==null){
				$listeLog['nom_utilisateur']=$log->login_utilisateur;
				$listeLog['prenom_utilisateur']=$log->login_utilisateur;
			}
			else{
				$listeLog['nom_utilisateur']=$utilisateur->nom_utilisateur;
				$listeLog['prenom_utilisateur']=$utilisateur->prenom_utilisateur;
			}*/
		
			$listeLogs[]=$listeLog;
		}
		
		return $listeLogs;
	}
	
	/*Cr�ation de la description d'une trace*/
	public static function constructionDescriptionLog($newValeur){
		$newString = "Une valeur a été modifiée en ".$newValeur."\n";
		return $newString;
	}
	
	/*Suppression d'une trace*/
	public static function supprimerlog($id){
		$log=Log::recupererLogParId($id);
		Log::supprimerLog($log);
		return true;
	}
}
?>
