<?php
require_once "../../../app/config/config.php";
require_once "../../../app/libraries/Utils.php";
require_once "../../fpdf183/fpdf.php";
require_once "../../../app/models/Section.php";
require_once "sommaire.php";
require_once "projet.php";

class ProjetPdf extends Sommaire
{
    public $sectionModel;
    protected $projet;

    function __construct($projet)
    {
        parent::__construct();
        $this->sectionModel = new Section();
        $this->projet = $projet;
    }

    // function Header() {
    //     // Logo
    //     $this->Image('public/images/logo.png', 10, 6, 30);
    //     // Police Arial gras 15
    //     $this->SetFont('Arial', 'B', 15);
    //     // Deplacement a droite
    //     $this->Cell(80);
    //     // Titre
    //     $this->Cell(30, 10, 'Title', 1, 0, 'C');
    //     // Saut de ligne
    //     $this->Ln(20);
    // }

    function Footer()
    {
        parent::Footer();
        // Positionnement à 1,5 cm du bas
        $this->SetY(-15);
        // Police Arial italique 8
        $this->SetFont('Arial', 'I', 8);
        // Nom Projet
        $this->Cell(0, 10, $this->projet->nomProjet, 0, 0, 'L');
    }

    function PageDeGarde($projet, $immeuble)
    {
        $photoImm = false;
        if ($immeuble && $immeuble->photoImmeuble != null && $immeuble->photoImmeuble !=  "" && file_exists("../../documents/immeuble/$immeuble->photoImmeuble")) {
            $photoImm = true;
        }

        if ($photoImm) {
            $this->SetY(35);
        } else {
            $this->SetY(($this->GetPageHeight() / 2) - 30);
        }

        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(255, 255, 255);
        $this->Cell(0, 25, "PROJET : " . $projet->nomProjet, 0, 0, 'C', true, '');
        $this->Ln(15);
        $this->Cell(0, 25, iconv('UTF-8', 'windows-1252', "$immeuble->adresse $immeuble->codePostal $immeuble->ville"), 0, 0, 'C', true, '');

        if ($photoImm) {
            $this->SetY(65);
        } else {
            $this->SetY(($this->GetPageHeight() / 2) + 5);
        }


        if ($photoImm) {
            $this->Image(URLROOT  . "/public/documents/immeuble/$immeuble->photoImmeuble", 15, 75, 180, 120);
            $this->SetY(200);
        }
    }

    function SectionTitre($num, $libelle, $fontSize = 18, $x = 0)
    {
        $niveau = 0;
        if ($num) {
            $niveau = substr_count($num, '.');
        }

        if ($niveau == 0) {
            $this->SetFont('Arial', 'B', 30);
            $this->SetTopMargin($this->GetPageHeight() / 2 - 30);
            $this->AddPage();
            $this->EntrerDonnee("Chapitre "  . $num . ' : ' . $libelle, $niveau, $this->GetY());
            $this->MultiCell(0, 30, "Chapitre " . $num . ' : ' . $libelle, 0, 'C');
            $this->SetTopMargin(20);
            $this->AddPage();
        } else {
            $this->setX($x);
            $this->SetFont('Arial', 'B', $fontSize);
            $this->EntrerDonnee($num . ' : ' . $libelle, $niveau, $this->GetY());
            $this->MultiCell(0, 6,  $num . ' : ' . $libelle, 0, 'L');
        }
    }

    function SectionContent($texte = '', $fontSize = 14, $x = 18)
    {
        $this->SetFont('Arial', '', $fontSize);
        $this->setX($x);
        $this->MultiCell(0, 6, $texte, 0, 'J');
        if ($texte != '') $this->Ln(1);
    }

    function ajouterSection($section, $fontSizeTitle = 16, $fontSizeContent = 11, $xTitle = 0, $xContent = 18)
    {
        $this->SectionTitre($section->numeroSection, $section->titreSection, $fontSizeTitle, $xTitle);
        if (trim($section->contenuSection) != "") {
            $this->SectionContent($section->contenuSection, $fontSizeContent, $xContent);
        }
    }

    // Fonction récursive pour gérer les sections
    function ajouterSectionsRecursives($projet, $sections, $niveau = 0)
    {
        $parametres = [
            0 => [16, 11, 00, 22],
            1 => [13, 11, 10, 22],
            2 => [12, 10, 14, 22],
            3 => [11, 10, 18, 22],
            4 => [11, 10, 22, 26]
        ];

        foreach ($sections as $section) {
            $params = $parametres[min($niveau, 4)];
            $this->ajouterSection($section, ...$params);
            $sous_sections = $this->sectionModel->getSectionsByParent($section->idSection);
            if (!empty($sous_sections)) {
                $this->ajouterSectionsRecursives($projet, $sous_sections, $niveau + 1);
            }
        }
    }
}
