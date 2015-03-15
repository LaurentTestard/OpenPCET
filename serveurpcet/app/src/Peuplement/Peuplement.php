<?php
use RedBean_Facade as R;
/**
 * Recherche un texte dans une feuille Excel et en retourne la cellule 
 * @author Pierrick Jeanjean
 * @param $feuilleExcel, la feuille Excel dans lequel on effectue la recherche
 * @param $recherche, le texte recherchÃ©
 * @return coordonnÃ©es de la cellule contenant le texte
**/
function rechercherTexteDansWorksheet($feuilleExcel,$recherche){
	
	$derniereColonneAvecValeurs = $feuilleExcel->getHighestColumn(); // RÃ©cupÃ©ration de la derniÃ¨re colonne oÃ¹ des valeurs sont prÃ©sentes
	$derniereLigneAvecValeurs = $feuilleExcel->getHighestRow(); // RÃ©cupÃ©ration de la derniÃ¨re ligne oÃ¹ des valeurs sont insÃ©rÃ©es
	
	$indexDerniereColonneAvecValeurs = PHPExcel_Cell::columnIndexFromString($derniereColonneAvecValeurs); // Index de la derniÃ¨re colonne oÃ¹ des valeurs sont prÃ©sentes
	
	$tableauValeurs = array();
	
	for($i=0;$i<$indexDerniereColonneAvecValeurs;$i++){ // Pour chaque colonne jusqu'Ã  la derniÃ¨re colonne contenant des valeurs
		$colonneCourante = PHPExcel_Cell::stringFromColumnIndex($i);
		for($j=1;$j<$derniereLigneAvecValeurs;$j++){ // Parcours des lignes jusqu'Ã  la derniÃ¨re ligne contenant des valeurs
			$tableauValeurs[$colonneCourante.$j] = $feuilleExcel->getCellByColumnAndRow($i,$j)->getCalculatedValue();
		}
	}
	
	$recherche = str_replace("'", '', $recherche);
	$recherche = str_replace(" ",'',$recherche);
	
	foreach($tableauValeurs as $cellule => $valeur){
		
		$valeur = str_replace("'",'',$valeur);
		$valeur = str_replace(" ", '', $valeur);		
		if (strcmp($valeur,$recherche) == 0){
			return $cellule;
		}
	}
	
	return false;

}

function obtenirCelluleVoisine($feuilleExcel,$celluleOrigne,$direction,$nombreCellulesDecalage){
	
	$coordonneesCellule = "";
	
	if($direction == "HAUT"){
		
		$colonneCelluleOrigine = $celluleOrigne->getColumn();
		
		$ligneCelluleOrigine = $celluleOrigne->getRow();
		$ligneCelluleOrigine = $ligneCelluleOrigine-(1*$nombreCellulesDecalage);
		
		$coordonneesCellule = $colonneCelluleOrigine.$ligneCelluleOrigine;
		
	} elseif($direction == "DROITE"){
				
		$colonneCelluleOrigine = $celluleOrigne->getColumn();
		$indexColonneCelluleOrigine = PHPExcel_Cell::columnIndexFromString($colonneCelluleOrigine);
		if($nombreCellulesDecalage != 1){
			$indexColonneCelluleOrigine = $indexColonneCelluleOrigine+(1*$nombreCellulesDecalage)-1;
		}
		$colonneCelluleOrigine = PHPExcel_Cell::stringFromColumnIndex($indexColonneCelluleOrigine);
		
		$ligneCelluleOrigine = $celluleOrigne->getRow();
		
		$coordonneesCellule = $colonneCelluleOrigine.$ligneCelluleOrigine;		
		
	} elseif($direction == "BAS"){
		
		$colonneCelluleOrigine = $celluleOrigne->getColumn();
		
		$ligneCelluleOrigine = $celluleOrigne->getRow();
		$ligneCelluleOrigine = $ligneCelluleOrigine+(1*$nombreCellulesDecalage);
		
		$coordonneesCellule = $colonneCelluleOrigine.$ligneCelluleOrigine;
		
	} else {
		
		$colonneCelluleOrigine = $celluleOrigne->getColumn();
		$indexColonneCelluleOrigine = PHPExcel_Cell::columnIndexFromString($colonneCelluleOrigine);
		$indexColonneCelluleOrigine = $indexColonneCelluleOrigine-(1*$nombreCellulesDecalage);
		$colonneCelluleOrigine = PHPExcel_Cell::stringFromColumnIndex($indexColonneCelluleOrigine);
		
		$ligneCelluleOrigine = $celluleOrigne->getRow();
		
		$coordonneesCellule = $colonneCelluleOrigine.$ligneCelluleOrigine;	
	}
	
	return $feuilleExcel->getCell($coordonneesCellule);	
}

function constructionLogin($prenom,$nom){
	$login = "";
	$prenom = str_replace(' ','',strtolower($prenom));
	$nom = str_replace(' ','',strtolower($nom));
	
	if($prenom === "" || $nom === "")
		return $login;

	$nom_login = "";
	if(strlen($nom) > 6){
		$nom_login = substr($nom, 0, 7);
	} else {
		$nom_login = $nom;
	}
	
	$prenom_login = $prenom[0];
	
	$login = str_replace(' ','',$nom_login.$prenom_login);
	
	return $login;
	
}

class Peuplement {
	
	public static function populate(){
				
		R::nuke();
		R::trashAll(R::findAll( Utilisateur::$nameTable ));
	
		ini_set('memory_limit', '-1');
	
		// ------------------ Liste des fiches actions ------------------- //
	
		$listeFichesActions = array();
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('./FichesActionsPourBaseTest')) as $nomFicheAction){
			if(substr($nomFicheAction,-1)!='.'){ // suppression des fichiers . et .. liés pour un dossier
				$listeFichesActions[] = $nomFicheAction;
			}
		}
	
		$handle = fopen('./src/Peuplement/logImport.txt','w');
		ftruncate($handle,0);
		fclose($handle);
	
		$handle = fopen('./src/Peuplement/logImport.txt','a+'); // Témoin parsing
	
		$tableauLiensActions = array();
	
		// ----------------- Parsing des fichiers Excel ------------------ //
	
		foreach($listeFichesActions as $ficheAction){
	
			$typeFichier = PHPExcel_IOFactory::identify($ficheAction);
			$readerObjet = PHPExcel_IOFactory::createReader($typeFichier);
				
			if($readerObjet != NULL){ // Si le reader a bien été créé
					
				$readerObjet->setReadDataOnly(true);
				$objetPHPExcel = $readerObjet->load($ficheAction);
	
				fwrite($handle,"Parsing du fichier ".$ficheAction." en cours ... \n\n");
	
				// ------------- Parsing du fichier Excel courant ------------ //
	
				//Liste des informations à récupérer du fichier Excel courant
				$infos_fiche_action = array();
	
				// --------------- Titre de la fiche action -------------- //
	
				$infos_fiche_action['titre_fiche_action'] = "";
				$titre_fiche_action = $objetPHPExcel->getSheet(0)->getCell('C2')->getCalculatedValue();
				$infos_fiche_action['titre_fiche_action'] = trim($titre_fiche_action);
					
				// ---------------- Code de la fiche action -------------- //
	
				$infos_fiche_action['code_fiche_action'] = "";
				$code_fiche_action = $objetPHPExcel->getSheet(0)->getCell('V4')->getCalculatedValue();
				$infos_fiche_action['code_fiche_action'] = trim(str_replace(" ",'',$code_fiche_action));
	
				// ------- Objectif stratégique de la fiche action ------- //
	
				$infos_fiche_action['objectif_strategique_fiche_action'] = "";
				$objectif_strategique_fiche_action = $objetPHPExcel->getSheet(0)->getCell('C6')->getCalculatedValue();
				$infos_fiche_action['objectif_strategique_fiche_action'] = trim($objectif_strategique_fiche_action);
				if(!empty(explode('.',$infos_fiche_action['code_fiche_action'])[0])){
					$infos_fiche_action['code_objectif_strategique'] = explode('.',$infos_fiche_action['code_fiche_action'])[0];
				} else {
					fwrite($handle,"Récupération du code de l'objectif stratégique de la fiche action : ERREUR : Pour être récupéré correctement, le code de la fiche action doit être de la forme suivante : C1.1 \n");
				}
	
				// ------- Thématique concernée de la fiche action ------- //
	
				$infos_fiche_action['thematique_concernee_fiche_action'] = "";
				$thematique_concernee_fiche_action = $objetPHPExcel->getSheet(0)->getCell('K11')->getCalculatedValue();
				$infos_fiche_action['thematique_concernee_fiche_action'] = trim($thematique_concernee_fiche_action);
				$explode_code_fiche_action = explode('.',$infos_fiche_action['code_fiche_action']);
				if(!empty($explode_code_fiche_action)){
					$infos_fiche_action['code_thematique_concernee_fiche_action'] = substr($explode_code_fiche_action[0],0,-1);
				} else {
					fwrite($handle,"Récupération du code de l'engagement thématique de la fiche action : ERREUR : Pour être récupéré correctement, le code de la fiche action doit être de la forme suivante : C1.1 \n");
				}
	
				// ---- Lien fiche action avec d'autres fiches actions --- //
	
				$infos_fiche_action['lien_autres_actions'] = "";
				$lien_autres_actions = $objetPHPExcel->getSheet(0)->getCell('K12')->getCalculatedValue();
				$tab_lien_autres_actions = array();
				$explode_lien_autres_actions = explode(',', $lien_autres_actions);
				if(!empty($explode_lien_autres_actions)){
					if(isset($infos_fiche_action['code_fiche_action'])){
						foreach($explode_lien_autres_actions as $lien_action){
							$tab_lien_autres_actions[] = trim($lien_action);
						}
						$tableauLiensActions[] = array($infos_fiche_action['code_fiche_action'],$tab_lien_autres_actions);
					}
				} else {
					fwrite($handle,"Récupération des codes actions liés à la fiche action : ERREUR : Pour être récupéré, les codes des fiches actions doivent être de la forme suivante : C1.1,C1.2,C1.3 \n");
				}
	
	
	
				// ------------- Contexte de la fiche action ------------- //
	
				$infos_fiche_action['contexte_action'] = "";
				$valeur_recherchee = str_replace("'", '', "Contexte de l'action");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_contexte_action = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_contexte_action != false){
					$cellule_valeur_contexte_action = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_contexte_action), "DROITE",1);
					$contexte_action = $cellule_valeur_contexte_action->getCalculatedValue();
					$infos_fiche_action['contexte_action'] = trim($contexte_action);
				} else {
					fwrite($handle,"Récupération du contexte de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche du contexte de la fiche action doit contenir la valeur : Contexte de l'action \n");
				}
	
				// ------- Objectif quantitatif de la fiche action ------- //
	
				$infos_fiche_action['objectif_quantitatif_action'] = "";
				$valeur_recherchee = str_replace("'", '', "Objectif quantitatif de l'action");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_objectif_quantitatif_action = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_objectif_quantitatif_action != false){
					$cellule_valeur_objectif_quantitatif_action = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_objectif_quantitatif_action), "DROITE",1);
					$objectif_quantitatif_action = $cellule_valeur_objectif_quantitatif_action->getCalculatedValue();
					$infos_fiche_action['objectif_quantitatif_action'] = trim($objectif_quantitatif_action);
				} else {
					fwrite($handle,"Récupération de l'objectif quantitatif de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche de l'objectif quantitatif de la fiche action doit contenir la valeur : Objectif quantitatif de l'action \n");
				}
	
				// --------- Gains GES / CO2 de la fiche action ---------- //
	
				$infos_fiche_action['gains_ges_fiche_action'] = "";
				$infos_fiche_action['gains_co2_fiche_action'] = "";
				$valeur_recherchee = str_replace("'", '', "Gains GES");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_gains_ges = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_gains_ges != false){
					$cellule_valeur_gains_ges = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_gains_ges), "DROITE", 7);
					$gains_ges = $cellule_valeur_gains_ges->getCalculatedValue();
					if($infos_fiche_action['gains_ges_fiche_action'] !== ""){
						$infos_fiche_action['gains_ges_fiche_action'] = trim($gains_ges);
					}
				} else {
					$valeur_recherchee = str_replace("'",'',"Gains CO2");
					$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
					$coordonnees_cellule_label_gains_co2 = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
					if($coordonnees_cellule_label_gains_co2 != false){
						$cellule_valeur_gains_co2 = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_gains_co2), "DROITE", 7);
						$gains_co2 = $cellule_valeur_gains_co2->getCalculatedValue();
						if($infos_fiche_action['gains_co2_fiche_action'] !== ""){
							$infos_fiche_action['gains_co2_fiche_action'] = trim($gains_co2);
						}
					} else {
						fwrite($handle,"Récupération des gains GES ou CO2 de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche des gains GES ou CO2 doit contenir la valeur : Gains GES | Gains CO2 \n");
					}
				}
	
				// --------- Gains énergie de la fiche action ---------- //
	
				$infos_fiche_action['gains_energie_fiche_action'] = "";
				$valeur_recherchee = str_replace("'", '', "Gains Energie");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_gains_energie = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_gains_energie != false){
					$cellule_valeur_gains_energie = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_gains_energie), "DROITE", 7);
					$gains_energie = $cellule_valeur_gains_energie->getCalculatedValue();
					$infos_fiche_action['gains_energie_fiche_action'] = trim($gains_energie);
				} else  {
					fwrite($handle,"Récupération des gains éjnergie de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche des gains énergie doit contenir la valeur : Gains Energie \n");
				}
	
				// --------- Maîtrise ouvrage de la fiche action ---------- //
	
				$infos_fiche_action['maitrise_ouvrage_fiche_action'] = "";
				$valeur_recherchee = str_replace("'", '', "Maîtrise d'ouvrage");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_maitrise_ouvrage = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_maitrise_ouvrage != false){
					$cellule_valeur_maitrise_ouvrage = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_maitrise_ouvrage), "DROITE", 5);
					$maitrise_ouvrage = $cellule_valeur_maitrise_ouvrage->getCalculatedValue();
					$infos_fiche_action['maitrise_ouvrage_fiche_action'] = trim($maitrise_ouvrage);
				} else {
					fwrite($handle,"Récupération de la maîtrise d'ouvrage de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche de la maîtrise d'ouvrage doit contenir la valeur : Maîtrise d'ouvrage \n");
				}
	
				// ----- Partenaires potentiels de la fiche action ------- //
	
				$infos_fiche_action['partenaires_potentiels_fiche_action'] = "";
				$tab_partenaires_potentiels = array();
				$valeur_recherchee = str_replace("'", '', "Partenaires potentiels");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_partenaires_potentiels = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_partenaires_potentiels != false){
					$cellule_valeur_partenaires_potentiels = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_partenaires_potentiels), "DROITE", 3);
					$partenaires_potentiels = $cellule_valeur_partenaires_potentiels->getCalculatedValue();
					$explode_partenaires_potentiels = explode(',', $partenaires_potentiels);
					if(!empty($explode_partenaires_potentiels)){
						foreach($explode_partenaires_potentiels as $partenaire_potentiel){
							$tab_partenaires_potentiels[] = trim($partenaire_potentiel);
						}
						$infos_fiche_action['partenaires_potentiels_fiche_action'] = $tab_partenaires_potentiels;
					} else {
						fwrite($handle,"Récupération des partenaires potentiels de la fiche action : ERREUR : Pour être récupéré, les partenaires potentiels doivent être de la forme suivante : Partenaire1,Partenaire2,Partenaire3 \n");
					}
				} else {
					fwrite($handle,"Récupération des partenaires potentiels de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche des partenaires potentiels doit contenir la valeur : Partenaires potentiels \n");
				}
	
				// --- Direction pilote / référent de la fiche action ---- //
	
				$infos_fiche_action['direction_pilote_referent'] = "";
				$listeDirectionPiloteReferent = array();
				$valeur_recherchee = str_replace("'",'',"Direction pilote / Référent");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_direction_pilote_referent = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_direction_pilote_referent != false){
					$cellule_valeur_direction_pilote_referent = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_direction_pilote_referent), "DROITE",1);
					$direction_pilote_referent = $cellule_valeur_direction_pilote_referent->getCalculatedValue();
					$explode_direction_pilote_referent = explode(',',$direction_pilote_referent);
					if(!empty($explode_direction_pilote_referent)){
						foreach($explode_direction_pilote_referent as $dir_pilote_referent){
							$dirpil = array();
							$dirpil['direction_pilote'] = "";
							$dirpil['prenom_referent'] = "";
							$dirpil['nom_referent'] = "";
							$deuxieme_explode = explode('/',$dir_pilote_referent);
							if(!empty($deuxieme_explode)){
								$dirpil['direction_pilote'] = $deuxieme_explode[0];
								if(isset($deuxieme_explode[1])){
									$ref = $deuxieme_explode[1];
									$troisieme_explode = explode(' ',$ref);
									if(!empty($troisieme_explode)){
										$dirpil['prenom_referent'] = $troisieme_explode[0];
										$chaine_nom_referent = "";
										$indexNomRef = 1;
										while($indexNomRef < sizeof($troisieme_explode)){
											$chaine_nom_referent .= " ".$troisieme_explode[$indexNomRef];
											$indexNomRef++;
										}
										$dirpil['nom_referent'] = $chaine_nom_referent;
									}
								}
							}
								
							$listeDirectionPiloteReferent[] = $dirpil;
						}
						$infos_fiche_action['direction_pilote_referent'] = $listeDirectionPiloteReferent;
					}
						
				} else {
					fwrite($handle,"Récupération de la direction pilote et du référent de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche de la direction pilote et référent doit contenir la valeur : Direction pilote / Référent \n");
				}
	
// 				// ----------- Coordonnées de la fiche action ------------ //
					
				$infos_fiche_action['email'] = "";
				$infos_fiche_action['tel_standard'] = "";
				$infos_fiche_action['tel_interne'] = "";
				$tab_coordonnes = array();
				$valeur_recherchee = str_replace("'",'',"Coordonnées");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_coordonnees = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_coordonnees != false){
					$cellule_valeur_coordonnees = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_coordonnees), "DROITE",4);
					$coordonnees = $cellule_valeur_coordonnees->getCalculatedValue();
					$coordonnees = str_replace(" ",'',$coordonnees);
					$explode_coordonnees = explode(',', $coordonnees);
					if(!empty($explode_coordonnees) && sizeof($explode_coordonnees) == 3){
						if(strstr($explode_coordonnees[0],"@") == FALSE){
							fwrite($handle,"Récupération de l'adresse email des coordonnées de la fiche action : ERREUR : Pour êtré récupéré, l'adresse email des coordonnées doit contenir le caractère '@' \n");
						} else {
							$infos_fiche_action['email'] = $explode_coordonnees[0];
						}
						if(preg_match("/[0-9]*/", explode(':',$explode_coordonnees[1])[1]) == 0 ||
								preg_match("/[0-9]*/", explode(':',$explode_coordonnees[1])[1]) == FALSE){
							fwrite($handle,"Récupération du téléphone standard des coordonnées de la fiche action : ERREUR : Pour êtré récupéré, le téléphone standard des coordonnées doit contenir le caractère ':' avant le numéro de la ligne interne \n");
						} else {
							$infos_fiche_action['tel_standard'] = explode(':',$explode_coordonnees[1])[1];
						}
						if(preg_match("/[0-9]*/", explode(':',$explode_coordonnees[2])[1]) == 0 ||
								preg_match("/[0-9]*/", explode(':',$explode_coordonnees[2])[1]) == FALSE){
							fwrite($handle,"Récupération de la ligne interne des coordonnées de la fiche action : ERREUR : Pour êtré récupéré, le tél interne des coordonnées doit contenir le caractère ':' avant le numéro de la ligne interne \n");
						} else {
							$infos_fiche_action['tel_interne'] = explode(':',$explode_coordonnees[2])[1];
						}
					} else {
						fwrite($handle,"Récupération des coordonnées de la fiche action : ERREUR : Pour être récupéré, les coordonnées doivent être de la forme suivante : adresseEmail,telStandard,telInterne \n");
					}
				} else {
					fwrite($handle,"Récupération des coordonnées de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche des coordonnées doit contenir la valeur : Coordonnées \n");
				}
	
				// Directions,agents référents associés de la fiche action //
	
				$infos_fiche_action['directions_agents_referents_associes'] = "";
				$valeur_recherchee = str_replace("'", '', "Directions et/ou agents référents associés");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_directions_agents_referents_associes = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_directions_agents_referents_associes != FALSE){
					$cellule_valeur_directions_agents_referents_associes = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_directions_agents_referents_associes), "DROITE",1);
					$directions_agents_referents_associes = $cellule_valeur_directions_agents_referents_associes->getCalculatedValue();
					$infos_fiche_action['directions_agents_referents_associes'] = trim($directions_agents_referents_associes);
				} else {
					fwrite($handle,"Récupération des directions et agents référents associés de la fiche action : ERREUR : Pour être récupéré, la cellule se situant à gauche des directions et agents référents associés doit contenir la valeur : Directions et/ou agents référents associés \n");
				}
	
				// -------------- Phasage de la fiche action ------------- //
	
				$infos_fiche_action['phasage'] = "";
				$listePhases = array();
				$valeur_recherchee = str_replace("'",'',"Phases / étapes");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_entete_phases_etapes = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
				if($coordonnees_cellule_entete_phases_etapes != false){
					$cellule_valeur_index_phases_etapes = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_entete_phases_etapes), "GAUCHE",2), "BAS",1);
					$index = 1;
					while($cellule_valeur_index_phases_etapes->getCalculatedValue() != ""){
						$detailsPhase = array();
						$detailsPhase['numero'] = "";
						$detailsPhase['phases_etapes'] = "";
						$detailsPhase['services_porteurs_moyens_humains'] = "";
						$detailsPhase['date_debut'] = "";
						$detailsPhase['date_fin'] = "";
						$detailsPhase['avancement'] = "";
						$detailsPhase['numero'] = $cellule_valeur_index_phases_etapes->getCalculatedValue();
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Phases / étapes"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsPhase['phases_etapes'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
						} else {
							fwrite($handle,"Récupération de la phase/étape de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant la phase/étape doit contenir la valeur : Phases / étapes \n");
						}
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Services porteurs / moyens humains"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsPhase['services_porteurs_moyens_humains'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
						} else {
							fwrite($handle,"Récupération des services porteurs et moyens humains de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant les services porteurs et moyens humains doit contenir la valeur : Services porteurs / moyens humains \n");
						}
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Date de début"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsPhase['date_debut'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
							$detailsPhase['date_debut'] = PHPExcel_Style_NumberFormat::toFormattedString($detailsPhase['date_debut'],"YYYY-MM-DD");
						} else {
							fwrite($handle,"Récupération de la date de début de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant la date de début doit contenir la valeur : Date de début \n");
						}
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Date de fin"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsPhase['date_fin'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
							$detailsPhase['date_fin'] = PHPExcel_Style_NumberFormat::toFormattedString($detailsPhase['date_fin'],"YYYY-MM-DD");
						} else {
							fwrite($handle,"Récupération de la date de fin de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant la date de fin doit contenir la valeur : Date de fin \n");
						}
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Avancement"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsPhase['avancement'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
							switch($detailsPhase['avancement']){
								case "":
									$detailsPhase['avancement'] = "Non démarrée";
									break;
								case "réalisé":
									$detailsPhase['avancement'] = "Terminée";
									break;
								case "en cours":
									$detailsPhase['avancement'] = "En cours";
									break;
								case "en projet":
									$detailsPhase['avancement'] = "En projet";
									break;
								case "reporté / suspendu":
									$detailsPhase['avancement'] = "Suspendue";
								default:
									$detailsPhase['avancement'] = "Non démarrée";
									fwrite($handle,"Récupération de l'avancement de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : L'état d'avancement de cette phase doit être l'une des valeurs suivantes : en cours, en projet, réalisé ou reporté / suspendu \n");
							}
						} else {
							frwite($handle,"Récupération de l'avancement de la phase ".$detailsPhase['numero']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant l'avancement doit contenir la valeur : Avancement \n");
						}
						$listePhases[] = $detailsPhase;
						$index++;
						$cellule_valeur_index_phases_etapes = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $cellule_valeur_index_phases_etapes, "BAS",1);
					}
					$infos_fiche_action['phasage'] = $listePhases;
				} else {
					fwrite($handle,"Récupération des phases de la fiche action : ERREUR : Pour être récupéré, la première cellule en haut à gauche (entête Phases / étapes) doit contenir la valeur : Phases / étapes \n");
				}
	
				// -------- Coût prévisionnel de la fiche action --------- //
					
				$infos_fiche_action['cout_previsionnel_action'] = "";
				$listeFinanceurs = array();
				$valeur_recherchee = str_replace("'",'',"Financeur");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_entete_financeur = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
				if($coordonnees_cellule_entete_financeur != false){
					$cellule_valeur_index_financeur = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_entete_financeur), "BAS",1);
					$index = 1;
					while($cellule_valeur_index_financeur->getCalculatedValue() != ""){
						$detailsFinanceur = array();
						$detailsFinanceur['financeur'] = "";
						$detailsFinanceur['montant_financement_euros_ht'] = "";
						$detailsFinanceur['financeur'] = trim($cellule_valeur_index_financeur->getCalculatedValue());
						$valeur_recherchee = str_replace("'",'',str_replace(" ",'',"Montant financement € H.T"));
						$coordonnees_cellule_valeur_recherchee = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0), $valeur_recherchee);
						if($coordonnees_cellule_valeur_recherchee != false){
							$detailsFinanceur['montant_financement_euros_ht'] = trim(obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_valeur_recherchee), "BAS", $index)->getCalculatedValue());
						} else {
							fwrite($handle,"Récupération du montant (en euros) du financement ".$detailsFinanceur['financeur']." de la fiche action : ERREUR : Pour être récupéré, la cellule de l'entête concernant le montant (en euros) du financement doit contenir la valeur : Montant financement € H.T \n");
						}
						$listeFinanceurs[] = $detailsFinanceur;
						$index++;
						$cellule_valeur_index_financeur = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $cellule_valeur_index_financeur, "BAS",1);
					}
					$infos_fiche_action['cout_previsionnel_action'] = $listeFinanceurs;
				} else {
					fwrite($handle,"Récupération du coût prévisionnel de la fiche action : ERREUR : Pour être récupéré, la première cellule en haut à gauche (entête Financeur) doit contenir la valeur : Financeur \n");
				}
					
				// ---------- Budget total de la fiche action ------------ //
	
				$infos_fiche_action['budget_total_euros_ht'] = "";
				$valeur_recherchee = str_replace("'", '', "Budget total € H.T");
				$valeur_recherchee = str_replace(" ",'',$valeur_recherchee);
				$coordonnees_cellule_label_budget_total_euros_ht = rechercherTexteDansWorksheet($objetPHPExcel->getSheet(0),$valeur_recherchee);
				if($coordonnees_cellule_label_budget_total_euros_ht != false){
					$cellule_valeur_budget_total_euros_ht = obtenirCelluleVoisine($objetPHPExcel->getSheet(0), $objetPHPExcel->getSheet(0)->getCell($coordonnees_cellule_label_budget_total_euros_ht), "BAS",1);
					$budget_total_euros_ht = $cellule_valeur_budget_total_euros_ht->getCalculatedValue();
					$infos_fiche_action['budget_total_euros_ht'] = trim($budget_total_euros_ht);
				} else {
					fwrite($handle,"Récupération du budget total de la fiche action : ERREUR : Pour être récupéré,la cellule de l'entête concernant le budget total de la fiche action doit contenir la valeur : Budget total € H.T");
				}
	
				fwrite($handle,"\n");
	
				// ----------------- Insertion dans la BD ---------------- //
	
				// ----------------- Engagement thématique --------------- //
	
				if(isset($infos_fiche_action['code_thematique_concernee_fiche_action']) && isset($infos_fiche_action['thematique_concernee_fiche_action'])){
					$contenuTableEngagementThematique = EngagementThematique::listerEngagementsThematique();
					$objetEngagementThematique = null;
					foreach($contenuTableEngagementThematique as $engagementThematiqueTable){
						if($infos_fiche_action['code_thematique_concernee_fiche_action'] === $engagementThematiqueTable->code_engagement_thematique){
							$objetEngagementThematique = $engagementThematiqueTable;
							break;
						}
					}
					if($objetEngagementThematique == null){
						$objetEngagementThematique = EngagementThematique::creerEngagementThematique($infos_fiche_action['code_thematique_concernee_fiche_action'],$infos_fiche_action['thematique_concernee_fiche_action']);
					}
				}
	
				// ----------------- Thématique concernée ---------------- //
	
				if(isset($infos_fiche_action['thematique_concernee_fiche_action'])){
					$objetThematiqueConcernee = ThematiqueConcernee::creerThematiqueConcernee($infos_fiche_action['thematique_concernee_fiche_action']);
				}
	
				// ----------------- Objectif stratégique ---------------- //
	
				if(isset($infos_fiche_action['code_objectif_strategique']) && isset($infos_fiche_action['objectif_strategique_fiche_action'])){
					$contenuTableObjectifStrategique = ObjectifStrategique::listerObjectifStrategique();
					$objetObjectifStrategique = null;
					foreach($contenuTableObjectifStrategique as $objectifStrategiqueTable){
						if($infos_fiche_action['code_objectif_strategique'] === $objectifStrategiqueTable->code_objectif_strategique){
							$objetObjectifStrategique = $objectifStrategiqueTable;
							break;
						}
					}
					if($objetObjectifStrategique == null){
						$objetObjectifStrategique = ObjectifStrategique::creerObjectifStrategique($infos_fiche_action['code_objectif_strategique'],$infos_fiche_action['objectif_strategique_fiche_action']);
					}
				}
	
				// ----------------------- Phases ------------------------ //
	
				if(isset($infos_fiche_action['phasage'])){
					$listeObjetsPhasesBD = array();
					foreach($infos_fiche_action['phasage'] as $phase){
						if(isset($phase['phases_etapes']) && isset($phase['date_debut']) && isset($phase['date_fin']) && isset($phase['numero']) && isset($phase['services_porteurs_moyens_humains']) && isset($phase['avancement'])){
							$listeObjetsPhasesBD[] = Phase::creerPhase($phase['phases_etapes'],null,$phase['date_debut'],$phase['date_fin'],null,null,null,$phase['numero'],$phase['services_porteurs_moyens_humains'],null,$phase['avancement']);
						}
					}
				}
	
				// --------------------- Partenaires --------------------- //
	
				if(isset($infos_fiche_action['partenaires_potentiels_fiche_action'])){
					$contenuTablePartenaire = Partenaire::listerPartenaires();
					$listeObjetsPartenaires = array();
					$index = 0;
					foreach($infos_fiche_action['partenaires_potentiels_fiche_action'] as $partenairePotentiel){
						$listeObjetsPartenaires[$index] = null;
						foreach($contenuTablePartenaire as $partenaireTable){
							if($partenairePotentiel === $partenaireTable->nom_partenaire){
								$listeObjetsPartenaires[$index] = $partenaireTable;
								break;
							}
						}
						if($listeObjetsPartenaires[$index] == null){
							$listeObjetsPartenaires[$index] = Partenaire::creerPartenaire($partenairePotentiel);
						}
						$index++;
					}
				}
	
				// -------------------- Utilisateurs --------------------- //
				
				if(isset($infos_fiche_action['direction_pilote_referent'])){
					$contenuTableUtilisateur = Utilisateur::getAllUtilisateur();
					$listeObjetsUtilisateur = array();
					$indexUtilisateur = 0;
					foreach($infos_fiche_action['direction_pilote_referent'] as $utilisateur){
						$listeObjetsUtilisateur[$indexUtilisateur] = null;
						$loginUtilisateur = constructionLogin($utilisateur['prenom_referent'],$utilisateur['nom_referent']);
						echo $loginUtilisateur;
						if($loginUtilisateur === "")
							break;
						foreach($contenuTableUtilisateur as $utilisateurTable){
							if($loginUtilisateur === $utilisateurTable->login_utilisateur){
								$listeObjetsUtilisateur[$indexUtilisateur] = $utilisateurTable;
								break;
							}	
						}
						if($listeObjetsUtilisateur[$indexUtilisateur] == null){
							$listeObjetsUtilisateur[$indexUtilisateur] = Utilisateur::creerUtilisateur($loginUtilisateur,$utilisateur['nom_referent'],$utilisateur['prenom_referent'],md5($loginUtilisateur),null,$utilisateur['direction_pilote'],$infos_fiche_action['email'],$infos_fiche_action['tel_interne'],$infos_fiche_action['tel_standard']);
						}
					}
				}
	
				// --------------------- Financeurs ---------------------- //
	
				if(isset($infos_fiche_action['cout_previsionnel_action'])){
					$contenuTableFinanceur = Financeur::listerFinanceurs();
					$listeObjetsFinanceurs = array();
					$listeMontantsFinancements = array();
					$indexFinanceur = 0;
					foreach($infos_fiche_action['cout_previsionnel_action'] as $financeur){
						$listeMontantsFinancements[$indexFinanceur] = $financeur['montant_financement_euros_ht'];
						$listeObjetsFinanceurs[$indexFinanceur] = null;
						foreach($contenuTableFinanceur as $financeurTable){
							if($financeur['financeur'] === $financeurTable->nom_financeur){
								$listeObjetsFinanceurs[$indexFinanceur] = $financeurTable;
								break;
							}
						}
						if($listeObjetsFinanceurs[$indexFinanceur] == null){
							$listeObjetsFinanceurs[$indexFinanceur] = Financeur::creerFinanceur($financeur['financeur']);
						}
						$indexFinanceur++;
					}
				}
	
				// ----------------------- Action ------------------------ //
	
				if(isset($infos_fiche_action['code_fiche_action'])){
					if(Action::getAction($infos_fiche_action['code_fiche_action']) == null){
						if(isset($infos_fiche_action['titre_fiche_action']) && isset($infos_fiche_action['contexte_action']) && isset($infos_fiche_action['objectif_quantitatif_action']) && isset($infos_fiche_action['gains_ges_fiche_action']) && isset($infos_fiche_action['gains_energie_fiche_action']) && isset($infos_fiche_action['gains_co2_fiche_action']) && isset($infos_fiche_action['maitrise_ouvrage_fiche_action']) && isset($infos_fiche_action['directions_agents_referents_associes']) && isset($infos_fiche_action['budget_total_euros_ht'])){
							$objetAction = Action::creerAction($infos_fiche_action['code_fiche_action'],$infos_fiche_action['titre_fiche_action'],null,null,null,null,null,null,$infos_fiche_action['contexte_action'],$infos_fiche_action['objectif_quantitatif_action'],$infos_fiche_action['gains_ges_fiche_action'],$infos_fiche_action['gains_energie_fiche_action'],$infos_fiche_action['gains_co2_fiche_action'],$infos_fiche_action['maitrise_ouvrage_fiche_action'],$infos_fiche_action['directions_agents_referents_associes'],$infos_fiche_action['budget_total_euros_ht'],null);
						}
					} else {
						$objetAction = Action::getAction($infos_fiche_action['code_fiche_action']);
					}
				}
	
				// ----------------------- Liens ------------------------ //
	
				if(isset($objetEngagementThematique) && isset($objetObjectifStrategique) && isset($objetAction)){
					EngagementThematique::addObjectifStrategique($objetEngagementThematique,$objetObjectifStrategique);
					ObjectifStrategique::addAction($objetObjectifStrategique,$objetAction);
				}
				if(isset($objetThematiqueConcernee) && isset($objetAction)){
					ThematiqueConcernee::ajouterAction($objetThematiqueConcernee,$objetAction);
				}
				foreach($listeObjetsPartenaires as $objetPartenaire){
					if(isset($objetAction)){
						Action::addPartenaire($objetAction,$objetPartenaire);
					}
				}
				foreach($listeObjetsUtilisateur as $objetUtilisateur){
					if(isset($objetAction) && isset($objetUtilisateur)){
						Action::addUtilisateur($objetAction,$objetUtilisateur);
					}
				}
				$indexFinanceurAssocie = 0;
				foreach($listeMontantsFinancements as $montantFinancement){
					if(isset($objetAction)){
						$objetFinanceurAction = FinanceurAction::creerFinanceurAction($montantFinancement);
						Financeur::ajouterFinanceurAction($listeObjetsFinanceurs[$indexFinanceurAssocie], $objetFinanceurAction);
						Action::addFinanceurAction($objetAction, $objetFinanceurAction);
						$indexFinanceurAssocie++;
					}
				}
				foreach($listeObjetsPhasesBD as $phaseBD){
					if(isset($objetAction)){
						Action::addPhase($objetAction, $phaseBD);
					}
				}
			}
		}
	
		foreach($tableauLiensActions as $actionLiensActions){
			$actionPere = Action::getAction($actionLiensActions[0]);
			if($actionPere != null){
				$idActionPere = $actionPere->id;
				foreach($actionLiensActions[1] as $liensActions){
					$actionFils = Action::getAction($liensActions);
					if($actionFils != null){
						$idActionFils = $actionFils->id;
						ActionAction::ajouterActionFils($idActionPere,$idActionFils);
					}
				}
			}
		}
	
		fclose($handle);		
	}

public static function ResetData(){
		R::nuke();
		R::trashAll(R::findAll( Utilisateur::$nameTable ));
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), 1,"communauté de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
		$utilisateur2 = Utilisateur::creerUtilisateur("Jeanjeap","Pierrick","Jeanjean", md5('admin'), 1,"communauté de commune Gresivaudan","p.jeanjean@gmail.com","6532","0474568790");
		
		$action = Action::creerAction("C1","nomAction1",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		$action2 = Action::creerAction("C2","nomAction2",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
		$action3 = Action::creerAction("C3","nomAction3",true,false,false,false,false,false,'context','objectif','$gainsGES','$gainsEnergie','$gainsCO2','$maitriseOuvrage','$referentsAssocies',100,50);
	
	
		$partenaire = Partenaire::creerPartenaire("pierre");
		$crAction = CrAction::creerCrAction("bien", true, $action);
		$objAction = ObjectifEnjeu::creerObjectifEnjeu("objectif1");
		$engagement_thematique  = EngagementThematique::creerEngagementThematique("C", "nom engagement thematique");
		
		
		$thematiqueConcernee = ThematiqueConcernee::creerThematiqueConcernee("energie");
		$thematiqueConcernee = ThematiqueConcernee::creerThematiqueConcernee("force verte");
		
		$objstrategique = ObjectifStrategique::creerObjectifStrategique("CO", "NO");
		$phase = Phase::creerPhase("nom de la phase", "ceci est un commentaire","2014-03-22", "2014-03-28", "2014-03-22", "2014-03-22", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$phase1 = Phase::creerPhase("nom de la phase1", "ceci est un commentaire","2014-03-21", "2014-03-29", "2014-03-22", "2014-03-22", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$phase2 = Phase::creerPhase("nom de la phase2", "ceci est un commentaire","2014-03-24", "2014-03-30", "2014-03-22", "2014-03-22", "ceci est une description", 1, "ceci est un service", 1,"en cours");
		$motclef = MotClef::creerMotClef("Mot_clef_1");
		
		$financeur1 = Financeur::creerFinanceur("Département du Rhône");
		$financeurAction1 = FinanceurAction::creerFinanceurAction(10000);
		
		EngagementThematique::addObjectifStrategique($engagement_thematique, $objstrategique);
		ObjectifStrategique::addAction($objstrategique, $action);
		ObjectifStrategique::addAction($objstrategique, $action2);
		ObjectifStrategique::addAction($objstrategique, $action3);
		Partenaire::ajouterAction($partenaire, $action);
		Action::addUtilisateur($action,$utilisateur);
		Action::addUtilisateur($action,$utilisateur2);
		Action::addPartenaire($action, $partenaire);
		Action::addCrAction($action, $crAction);
		Action::addObjectifenjeu($action, $objAction);
		Action::addPhase($action, $phase);
		Action::addPhase($action, $phase1);
		Action::addPhase($action, $phase2);
		Action::addMotClef($action, $motclef);
		
		Utilisateur::addCrAction($utilisateur, $crAction);
		
		ThematiqueConcernee::ajouterAction($thematiqueConcernee, $action);
		ThematiqueConcernee::ajouterAction($thematiqueConcernee, $action2);
		ThematiqueConcernee::ajouterAction($thematiqueConcernee, $action3);
	
		//ajout du montant financé sur l'action et le financeur concerné
		Action::addFinanceurAction($action, $financeurAction1);
		Financeur::ajouterFinanceurAction($financeur1, $financeurAction1);
		
		//Ajout d'une liaison entre l'action et les actions 2 et 3
		ActionAction::ajouterActionFils($action->id, $action2->id);
		ActionAction::ajouterActionFils($action->id, $action3->id);
		
		//Ajout des indicateurs et des objctifs
		$obj = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif1");
		Action::addObjectifenjeu($action, $obj);
		$obj2 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif2");
		Action::addObjectifenjeu($action, $obj2);
		$obj3 = ObjectifEnjeu::creerObjectifEnjeu("nomobjectif3");
		Action::addObjectifenjeu($action, $obj3);
		$ind = Indicateur::creerIndicateurAction("nomIndicateur1", "100", "250", "descriptionIndicateur1");
		$ind2 = Indicateur::creerIndicateurAction("nomIndicateur2", "100", "2050", "descriptionIndicateur2");
		$ind3 = Indicateur::creerIndicateurAction("nomIndicateur3", "10", "50", "descriptionIndicateur3");
		ObjectifEnjeu::addIndicateur($obj, $ind);
		ObjectifEnjeu::addIndicateur($obj2, $ind2);
		ObjectifEnjeu::addIndicateur($obj2, $ind3);
	
	}
	
	public static function ResetUser(){
		R::trashAll(R::findAll( Utilisateur::$nameTable ));
		$utilisateur = Utilisateur::creerUtilisateur("Foreta","Anne","Foret", md5('admin'), 1,"communaut� de commune Gresivaudan","anne.foret@gmail.com","6532","0474568790");
	}
	
	
}
?>
