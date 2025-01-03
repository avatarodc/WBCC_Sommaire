<?php
require_once "../../fpdf183/fpdf.php";

class Sommaire extends FPDF {
    protected $sommaire=array();
    protected $numeroter=false;
    protected $numeroterFooter=false;
    protected $_numPageNum=1;
    protected $links=array();

    function AddPage($orientation='', $size='', $rotation=0) {
        parent::AddPage($orientation,$size,$rotation);

        if($this->numeroter)
            $this->_numPageNum++;
    }

    function startPageNums() {
        $this->numeroter=true;
        $this->numeroterFooter=true;
    }

    function stopPageNums() {
        $this->numeroter=false;
    }

    function numPageNo() {
        return $this->_numPageNum;
    }

    function EntrerDonnee($txt, $level=0, $y=0) {
        $this->sommaire[]=array('t'=>$txt,'l'=>$level,'p'=>$this->numPageNo());
    }

    function insertSommaire( $location=2, $label='Sommaire', $labelSize=20, $entrySize=10, $tocfont='Arial') {
        
        $this->stopPageNums();
        $this->AddPage();
        $start=$this->page;

        $this->SetFont($tocfont,'B',$labelSize);
        $this->Cell(0,5,$label,0,1,'C');
        $this->Ln(10);

        foreach($this->sommaire as $t) {

            //Offset
            $level=$t['l'];
            if($level>0)
                $this->Cell($level*8);
            $weight='';
            if($level==0)
                $weight='B';
            $str=$t['t'];
            $this->SetFont($tocfont,$weight,$entrySize);
            $strsize=$this->GetStringWidth($str);
            $this->Cell($strsize+2, $this->FontSize+2, $str, 0, 0, 'L', false);

            // Remplir avec des points
            $this->SetFont($tocfont,'',$entrySize);
            $PageCellSize=$this->GetStringWidth($t['p'])+2;
            $w=$this->w-$this->lMargin-$this->rMargin-$PageCellSize-($level*8)-($strsize+2);
            $nb=$w/$this->GetStringWidth('.');
            $dots=str_repeat('.',(int)$nb);
            $this->Cell($w, $this->FontSize+2, $dots, 0, 0, 'R', false);

            // Numero de Page
            $this->Cell($PageCellSize, $this->FontSize+2, $t['p'], 0, 1, 'R', false);
        }

        // Grab it and move to selected location
        $n=$this->page;
        $n_sommaire = $n - $start + 1;
        $last = array();

        // store sommaire pages
        for($i = $start;$i <= $n;$i++)
            $last[]=$this->pages[$i];

        //move pages
        for($i=$start-1;$i>=$location-1;$i--)
            $this->pages[$i+$n_sommaire]=$this->pages[$i+1];

        //Put sommaire pages at insert point
        for($i = 0;$i < $n_sommaire;$i++)
            $this->pages[$location + $i]=$last[$i];

        // Supprimer les pages originales du sommaire
        for($i = $n + 1; $i > $n - $n_sommaire; $i--) {
            unset($this->pages[$i]);
        }
        $this->page = count($this->pages);
    }

    function Footer() {
        if(!$this->numeroterFooter)
            return;
        //Go to 1.5 cm from bottom
        $this->SetY(-15);
        //Select Arial italic 8
        $this->SetFont('Arial','I',8);
        $this->Cell(0,7,$this->numPageNo(),0,0,'R'); 
        if(!$this->numeroter)
            $this->numeroterFooter=false;
    }
}