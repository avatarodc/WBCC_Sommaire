<?php
/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 11/3/2019
 * Time: 11:12 AM
 */

class HtmlProvider
{
    /************************************************************************************************/
    public static function start_page()
    {
        echo '<div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">';
    }
    public static function end_page()
    {
        echo '
                </div></div></div></div>';
    }
    public static function header_page($title="Index",$size=4, $subtitle='Voici un exemple de legende')
    {
        echo '<div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-4">
                        <div class="page-header-title">
                            <div class="d-inline">
                                <h'.$size.'>'.$title.'</h'.$size.'>
                                <span>'.$subtitle .'</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="page-header-breadcrumb">
                            <ul class="breadcrumb-title">
                            ';
    }
    public static function header_page_end()
    {
        echo '</ul>
        </div>
    </div>
</div>
</div>';
    }
    /*************************************************************************************************/
    /**
     * @param $titre
     * @param $btn
     * @param $couleur
     * @return string
     */
    public static function viewTitleBar($titre,$btn, $couleurFond, $couleurText="black", $route="")
    {
        return "
            <div class='card $couleurFond text-center z-depth-2'>
                <div class='card-body'>
                    <div class='row'>
                        <div class='col col-sm-12'>
                            <h2 class='text-center text-$couleurText'> $titre </h2>
                        </div>
                        <div class='col col-sm-6 text-right'>
                            <a href='$route' class='btn btn-info btn-out-dashed'>
                                $btn
                            </a>
                        </div>
                    </div>
                </div>
            </div>
";
    }

    public static function showHistoriques($historiques)
    {
        $html = "";
        $html = "
        <div class='card strpied-tabled-with-hover'>
            <div class='card-header'>
                <div class='row'>
                    <div class='col-md-12'>
                        <span class='font-weight-bold'>Historique</span>
                    </div>
                </div>
                
            </div>
            <div class='card-body'>
            <hr>

                <div class='table-responsive-sm' style='height: 300px; overflow:scroll'>
                    <table id='example1' class='' width='100%'>
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Date&Heure</th>
                            </tr>
                        </thead>
                        <tbody>";

                                foreach ($historiques as $h) {
                                    $h->dateAction = date('d-m-Y', strtotime($h->dateAction)) . ' à ' . date('H:i', strtotime($h->heureAction)) ;
                                    $html .= "<tr>";
                                    $html .= self::td_printer(['action', 'dateAction'], $h);
                                    $html .= "</tr>";
                                }
                        $html.= "</tbody>
                    </table>
                </div>
            </div>
        </div>
        ";
        return $html;
    }

    public static function viewTitleBarWithRole($titre,$btn, $couleurFond, $couleurText="black", $route="",$access)
    {
        $coul = 'success';
        $textBtn = 'Public';
        $prive = '';
        if ($access==1){
            $coul = 'danger';
            $textBtn = 'Privé';
        }
        $type = "submit";
        if (strtolower($_SESSION['connectedUser']->libelleRole)!="admin"){
            $type = "button";
            $prive = 'hidden';
        }
        $hidden = $btn ? '' : 'hidden';

        return "

            <form action='' method='POST'>
                <div class='card $couleurFond text-center z-depth-2'>
                    <div class='card-body'>
                        <div class='row'>
                            <div class='col col-sm-12 text-center'>
                                <span class='text-center text-$couleurText h3'> 
                                     $titre
                                </span>
                                     <button $prive type='$type' name='btnPrive' class='btn btn-sm btn-$coul text-dark mb-3'>$textBtn</button>
                            </div>
                            
                            <div class='col col-sm-4 text-right' $hidden>
                                <a href='$route' class='btn btn-info btn-out-dashed'>
                                    $btn
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
";
    }

    public static function tr_printer($culumnNames, $data){
        $html = "";
        foreach ($data as $d){
            $html .= "<tr>";
            $html .= self::td_printer($culumnNames, $d);
            $html .= "</tr>";
        }

        return $html;
    }

    public static function td_printer($culumnNames, $data){
        $html = "";

        foreach ($culumnNames as $col){
            $html .= "<td  style=''>".$data->$col."</td>";
        }

        return $html;
    }

    public static function pageStart(){
        echo "
            <div class='pcoded-content'>
                <div class='pcoded-inner-content'>
                    <div class='main-body'>
                        <div class='page-wrapper'>
        ";
    }

    public static function pageEnd(){
        echo "
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

}