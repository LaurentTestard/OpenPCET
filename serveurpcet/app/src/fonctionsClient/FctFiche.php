<?php
use RedBean_Facade as R;
require ('fpdf.php');

class FctFiche {
	
	/*Exportation d'une fiche actio  en format pdf*/
	public static function creerFiche($nom){
		
		/*Création d'une fiche dans la BD*/
		/*Le nom d'une fiche est le code de l'action suivit de l'extension .pdf*/
		$fiche = Fiche::creerFiche($nom.'.pdf');
		/*Les fiches se trouvent dans le répertoir fichiers*/
		$path = 'fichier/';
		$uploadfile = $path . $fiche->nom_fiche;
			
		/*Si le fichier n'existe pas sur le serveur, il est créé*/
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		
		/*Récupération des informations de l'action*/
		$action=Action::getAction($nom);
		/*Création d'un document pdf*/
		$pdf=new FPDF('P','mm','A4');
		/*Ajout des informations dans le document*/
		/*A revoir la mise en forme*/
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(5);
		$pdf->Cell(50,10,'Code action : '.$action->code_action);
		$pdf->Ln(10);
		$chaine1=utf8_decode($action->nom_action);
		$pdf->MultiCell(0,5,'Nom action : '.$chaine1);
		$pdf->Ln(10);
		$pdf->Cell(5);
		$chaine2=utf8_decode($action->contexte_action);
		$pdf->MultiCell(0,5,'Contexte action : '.$chaine2);
		$pdf->Ln(10);
		
		/*Finalisation de la création du document pdf*/
		$content = $pdf->Output($uploadfile,'F');
		return;
	}
	
	/*Création de toutes les fiches actions*/
	public static function creerToutesFiches(){
	
		$actions=array();
		$actions=Action::getAllAction();
		foreach($actions as $action){	
		
			$fiche = Fiche::creerFiche($action->code_action.'.pdf');
			$path = 'fichier/';
			$uploadfile = $path . $fiche->nom_fiche;
				
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
		
			$pdf=new FPDF('P','mm','A4');
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(5);
			$pdf->Cell(50,10,'Code action : '.$action->code_action);
			$pdf->Ln(10);
			$chaine1=utf8_decode($action->nom_action);
			$pdf->MultiCell(0,5,'Nom action : '.$chaine1);
			$pdf->Ln(10);
			$pdf->Cell(5);
			$chaine2=utf8_decode($action->contexte_action);
			$pdf->MultiCell(0,5,'Contexte action : '.$chaine2);
			$pdf->Ln(10);
		
			$content = $pdf->Output($uploadfile,'F');
		}
		return;
	}
	
	/*Fonction pour la création de plusieurs fiches en pdf dans un seul document*/
	public static function imprimerFiches($tab){
		$path = 'fichier/';
		$uploadfile = $path .'fiches.pdf';
			
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		
		$pdf=new FPDF('P','mm','A4');
		
		foreach($tab as $k=>$v){
			$nom=substr($tab[$k],0,-4);
			$action=Action::getAction($nom);
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',10);
			$pdf->Cell(5);
			$pdf->Cell(50,10,'Code action : '.$action->code_action);
			$pdf->Ln(10);
			$chaine1=utf8_decode($action->nom_action);
			$pdf->Cell(10,5,'Nom action : '.$chaine1);
		}
		clearstatcache();
		$content = $pdf->Output($uploadfile,'F');
		
		return;
	}
	
	
	/*Création d'un fcheir pdf avec toutes les fiches actions*/
	public static function toutImprimer(){
		$path = 'fichier/';
		$uploadfile = $path .'fiches.pdf';
		
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		
		$fiches=Fiche::getAllFiche();
		
		$pdf=new FPDF('P','mm','A4');
		foreach($fiches as $fiche){
				$nom=substr($fiche->nom_fiche,0,-4);
				$action=Action::getAction($nom);
				$pdf->AddPage();
				$pdf->SetFont('Arial','B',10);
				$pdf->Cell(5);
				$pdf->Cell(50,10,'Code action : '.$action->code_action);
				$pdf->Ln(10);
				$chaine1=utf8_decode($action->nom_action);
				$pdf->Cell(10,5,'Nom action : '.$chaine1);
		}
		clearstatcache();
		$content = $pdf->Output($uploadfile,'F');
				
		return;
	}
	
	
	/*Représentation des informations d'une fiche sous forme de tableau*/
	public static function formeFicheArray($fiche){
		$ficheTab = array();
		$ficehTab['id']=$fiche->id;
		$ficheTab['nom_fiche']=$fiche->nom_fiche;
		$ficheTab['path']=$fiche->path;
		return $ficheTab;
	}
	
	/*Suppression d'une fiche action*/
	public static function supprimerFiche($login,$idFiche) {
		Fiche::supprimerFiche($idFiche);
		return array('success' => 'ok');
	}	
	
}
?>