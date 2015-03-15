<?php
use RedBean_Facade as R;

/*Cette classe permet la gestion de traces*/
class Log {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "log";
	
	/*Création d'un log (ou d'une trace)*/
	public static function creerLog($dateModification,$typeModification,$loginUtilisateur,$codeAction,$descriptionModification){
		$log = R::dispense(Log::$nameTable);
		$log->date_modification = $dateModification;
		$log->type_modification = $typeModification;
		$log->description_modification = $descriptionModification;
		$log->login_utilisateur = $loginUtilisateur;
		$log->code_action = $codeAction;
		$idLog = R::store($log);
		return $log;
	}
	
	/*Suppréssion d'un log*/
	public static function supprimerLog($log){
		R::trash($log);
		return "ok";
	}
	
	/*Fonction pour la récupération de log*/
	public static function recupererLogParId($id) {
		$log = R::findOne(Log::$nameTable, " id = ? ", array($id));
		if($log == null)
			return null;
		return $log;
	}
	
	
	public static function getLogsParCodeAction($action){
		$logs = R::find(Log::$nameTable, ' code_action = ?  ', array($action->code_action));
		return $logs;
	}
	
	public static function getLogsParUtilisateur($utilisateur){
		$logs = R::find(Log::$nameTable, ' login_utilisateur = ?  ', array($utilisateur->login_utilisateur));
		return $logs;
	}
	
	public static function getLogs(){
		$logs = R::find(Log::$nameTable);
		return $logs;
	}
}
?>
