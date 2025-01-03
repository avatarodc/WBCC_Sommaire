<?php
require_once "../../../app/libraries/Database.php";
require_once "../../../app/libraries/Utils.php";
require_once "../../../app/libraries/Model.php";
require_once "../../../app/models/Section.php";
require_once "pdf.php";
require_once "word.php";

$idProjet = $_GET['idProjet'];
$idImmeuble = $_GET['idImmeuble'];
$sectionModel = new Section();

$projet = findItemByColumn("wbcc_projet", "idProjet", $idProjet);
$immeuble =  findItemByColumn("wbcc_immeuble", "idImmeuble", $idImmeuble);
$sommaire =  findItemByColumn("wbcc_sommaire", "idProjetF", $idProjet);
$sections =  $sectionModel->getSectionsBySommaire($sommaire->idSommaire);

// Rediger Projet
$projetPdf = new ProjetPdf($projet);
$projetWord = new Word();

//Numéro de page
$projetPdf->AliasNbPages();

$projetPdf->setMargins(20, 20, 25, 20);
// PAGE DE GARDE
$projetPdf->AddPage();
$projetPdf->PageDeGarde($projet, $immeuble);

// CONTENU DOCUMENT PROJET AVEC TOUTES LES SECTIONS
$projetPdf->AddPage();
$projetPdf->startPageNums();
$projetPdf->ajouterSectionsRecursives($projet, $sections);

// ADD SOMMAIRE
$projetPdf->insertSommaire(2);

header('Content-type: application/pdf');

//SAVE COMPTE RENDU
$nom = "PROJET_$projet->idProjet.pdf";
$projetPdf->Output("../../../public/documents/projet/projet_export/$nom", 'F');
$nom = str_replace('"', "", $nom);
echo json_encode($nom);

// $file = "$nom";
// $file = str_replace('"', "", $file);

// $word = __DIR__ . "/$projet->nomProjet.doc";

// $file2 = $projetWord->getWord($file, $word);

// echo json_encode($file2);
// $pdf->Output($file, 'I');