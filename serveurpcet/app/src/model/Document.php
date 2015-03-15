<?php
use RedBean_Facade as R;

class Document {
	
	/*Nom de la table de la base de données sur laquelle cette classe va travailler*/
	public static $nameTable = "document";
	
	/*Création de document dans la BD*/
	public static function creerDocument($nomDocument,$login) {
		$document = R::dispense(Document::$nameTable);
		$document->nom_document = $nomDocument;
		$document->type_document = "action";
		$document->proprietaire = $login;
		R::store($document);
		
		return $document;
	}
	
	/*Fonction qui enregistre le lien d'un document dans la BD*/
	public static function ajoutPath($document,$path) {
		$document->path = $path;
		R::store($document);
		return $document;
	}
	
	/*List de tous les documents*/
	public static function listerDocuments() {
		$documents = R::findall(Document::$nameTable, "ORDER BY nom_document ASC");
		return $documents;
	}
	
	/*Fonctions pour la récupération des informations d'un document à partir de la BD*/
	public static function recupererDocument($idDocument) {
		$document = R::findOne(Document::$nameTable, " id = ? ", array($idDocument));
		if($document == null)
			return null;
		return $document;
	}
	
	public static function recupererDocumentByNom($nameDocument) {
		$document = R::findOne(Document::$nameTable, " nom_document = ? ", array($nameDocument));
		if($document == null)
			return null;
		return $document;
	}
	
	public static function chercherDocuments($nomDocument) {
		$nom = "%".$nomDocument."%";
		$document = R::find(Document::$nameTable, " nom_document LIKE ? ", array($nom));
		if($document == null)
			return null;
		return $document;
	}
	
	/*Renommage d'un document*/
	public static function renommerDocument($idDocument, $nouveauNom){
		$document = Document::recupererDocument($idDocument);
		if($document == null)
			return null;
		$document->nom_document = $nouveauNom;
		R::store($document);
		return $document; 
	}
	
	/*Suppression d'un document*/
	public static function supprimerDocument($idDocument){
		$document = Document::recupererDocument($idDocument);
		if($document == null)
			return null;
		$deletefile = $document->path;
		if(file_exists($deletefile)){
			unlink($deletefile);
		}
		R::trash($document);
		return "ok";
	}
	
	/*Changement du type d'un document*/
	public static function changerTypeDoc($idDocument){
		$document = Document::recupererDocument($idDocument);
		if($document == null){
			return null;
		}
		$document->type_document = "info";
		/*
		if($document->type_document == "action"){
			$document->type_document = "info";
		}
		else{
			$document->type_document = "action";
		}*/
		R::store($document);
		return "ok";
	}
	
	/*Fonction identique à listerDocuments(). Code en double*/
	public static function getAllDocument(){
		return R::findAll(Document::$nameTable);
	}
	
	/*Récupération des documents qui ne sont pas liées à des actions*/
	public static function getDocumentNonLieAction($id){
		$documents = R::findAll(Document::$nameTable);
	
		$documentNonLie=array();
	
		foreach($documents as $document){
			$lie=false;
			foreach($document->sharedAction as $documentAction){
				if($id==$documentAction->id){
					$lie=true;
				}
			}
			if(!$lie){
				$documentNonLie[]=$document;
			}
		}
		return $documentNonLie;
	}
}
?>
