<?php

/**
 * @param $number
 * @return string
 */
require_once 'Database.php';

/**
 * Cette fonction permet de rendre un champs DISABLED pour tous les autres role excepté celui passé en parametres
 * @param $role
 * @return string
 */
function editableBy($role)
{
    return strtoupper(Role::getRole()) == strtoupper($role) ? '' : 'disabled';
}

/**
 * @param $date : d-m-Y
 * @param $format : d|dd|m|y|dmy|dm|my
 * @return false|mixed|string
 */
function my_dateEnFrancais($date, $format)
{

    $dd = date($date);
    $jour = array("Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi");

    $mois = array("", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
    $format = strtolower($format);
    if ($format == 'd') {
        $datefr = $jour[date("w", strtotime($dd))];
    } elseif ($format == 'dd') {
        $datefr = $jour[date("w", strtotime($dd))] . " " . date("d", strtotime($dd));
    } elseif ($format == 'm') {
        $datefr = $mois[date("n", strtotime($dd))];
    } elseif ($format == 'y') {
        $datefr = date("Y", strtotime($dd));
    } elseif ($format == 'dmy') {
        $datefr = $jour[date("w", strtotime($dd))] . " " . date("d", strtotime($dd)) . " " . $mois[date("n", strtotime($dd))] . " " . date("Y", strtotime($dd));
    } elseif ($format == 'dmy2') {
        $datefr =  date("d", strtotime($dd)) . " " . $mois[date("n", strtotime($dd))] . " " . date("Y", strtotime($dd));
    } elseif ($format == 'dm') {
        $datefr = $jour[date("w", strtotime($dd))] . " " . date("d", strtotime($dd)) . " " . $mois[date("n", strtotime($dd))];
    } elseif ($format == 'my') {
        $datefr = $mois[date("n", strtotime($dd))] . " " . date("Y", strtotime($dd));
    } else {
        $datefr = "Format incorrect";
    }

    return $datefr;
}

function my_formatNumber($number)
{
    return number_format($number, 0, '', '.');
}

function chargerPhoto($photo)
{
    if (file_exists(URLPHOTO . 'profil/' . $photo)) {
        return URLROOT . '/public/img/profil/' . $photo;
    } else {
        return URLROOT . '/public/img/profil/default.jpg';
    }
}

function chargerLogo($photo)
{
    if (file_exists(URLPHOTO . '/' . $photo)) {
        return URLROOT . '/public/img/' . $photo;
    } else {
        return URLROOT . '/public/img/logo.jpg';
    }
}

function linkTo($controller = 'personnel', $method = '', $param1 = '', $param2 = '')
{
    if (empty($method))
        return URLROOT . '/' . $controller;
    elseif (empty($param1))
        return URLROOT . '/' . $controller . '/' . $method;
    elseif (empty($param2))
        return URLROOT . '/' . $controller . '/' . $method . '/' . $param1;
    else
        return URLROOT . '/' . $controller . '/' . $method . '/' . $param1 . '/' . $param2;
}

function getVille() {}
function getNationalites()
{
    return "<option value='Afghane'>Afghane (Afghanistan)</option>
                                    <option value='Albanaise'>Albanaise (Albanie)</option>
                                    <option value='Algérienne'>Algérienne (Algérie)</option>
                                    <option value='Allemande'>Allemande (Allemagne)</option>
                                    <option value='Americaine'>Americaine (États-Unis)</option>
                                    <option value='Andorrane'>Andorrane (Andorre)</option>
                                    <option value='Angolaise'>Angolaise (Angola)</option>
                                    <option value='Antiguaise-et-Barbudienne'>Antiguaise-et-Barbudienne (Antigua-et-Barbuda)</option>
                                    <option value='Argentine'>Argentine (Argentine)</option>
                                    <option value='Armenienne'>Armenienne (Arménie)</option>
                                    <option value='Australienne'>Australienne (Australie)</option>
                                    <option value='Autrichienne'>Autrichienne (Autriche)</option>
                                    <option value='Azerbaïdjanaise'>Azerbaïdjanaise (Azerbaïdjan)</option>
                                    <option value='Bahamienne'>Bahamienne (Bahamas)</option>
                                    <option value='Bahreinienne'>Bahreinienne (Bahreïn)</option>
                                    <option value='Bangladaise'>Bangladaise (Bangladesh)</option>
                                    <option value='Barbadienne'>Barbadienne (Barbade)</option>
                                    <option value='Belge'>Belge (Belgique)</option>
                                    <option value='Belizienne'>Belizienne (Belize)</option>
                                    <option value='Béninoise'>Béninoise (Bénin)</option>
                                    <option value='Bhoutanaise'>Bhoutanaise (Bhoutan)</option>
                                    <option value='Biélorusse'>Biélorusse (Biélorussie)</option>
                                    <option value='Birmane'>Birmane (Birmanie)</option>
                                    <option value='Bissau-Guinéenne'>Bissau-Guinéenne (Guinée-Bissau)</option>
                                    <option value='Bolivienne'>Bolivienne (Bolivie)</option>
                                    <option value='Bosnienne'>Bosnienne (Bosnie-Herzégovine)</option>
                                    <option value='Botswanaise'>Botswanaise (Botswana)</option>
                                    <option value='Brésilienne'>Brésilienne (Brésil)</option>
                                    <option value='Britannique'>Britannique (Royaume-Uni)</option>
                                    <option value='Brunéienne'>Brunéienne (Brunéi)</option>
                                    <option value='Bulgare'>Bulgare (Bulgarie)</option>
                                    <option value='Burkinabée'>Burkinabée (Burkina)</option>
                                    <option value='Burundaise'>Burundaise (Burundi)</option>
                                    <option value='Cambodgienne'>Cambodgienne (Cambodge)</option>
                                    <option value='Camerounaise'>Camerounaise (Cameroun)</option>
                                    <option value='Canadienne'>Canadienne (Canada)</option>
                                    <option value='Cap-verdienne'>Cap-verdienne (Cap-Vert)</option>
                                    <option value='Centrafricaine'>Centrafricaine (Centrafrique)</option>
                                    <option value='Chilienne'>Chilienne (Chili)</option>
                                    <option value='Chinoise'>Chinoise (Chine)</option>
                                    <option value='Chypriote'>Chypriote (Chypre)</option>
                                    <option value='Colombienne'>Colombienne (Colombie)</option>
                                    <option value='Comorienne'>Comorienne (Comores)</option>
                                    <option value='Congolaise'>Congolaise (Congo-Brazzaville)</option>
                                    <option value='Congolaise'>Congolaise (Congo-Kinshasa)</option>
                                    <option value='Cookienne'>Cookienne (Îles Cook)</option>
                                    <option value='Costaricaine'>Costaricaine (Costa Rica)</option>
                                    <option value='Croate'>Croate (Croatie)</option>
                                    <option value='Cubaine'>Cubaine (Cuba)</option>
                                    <option value='Danoise'>Danoise (Danemark)</option>
                                    <option value='Djiboutienne'>Djiboutienne (Djibouti)</option>
                                    <option value='Dominicaine'>Dominicaine (République dominicaine)</option>
                                    <option value='Dominiquaise'>Dominiquaise (Dominique)</option>
                                    <option value='Égyptienne'>Égyptienne (Égypte)</option>
                                    <option value='Émirienne'>Émirienne (Émirats arabes unis)</option>
                                    <option value='Équato-guineenne'>Équato-guineenne (Guinée équatoriale)</option>
                                    <option value='Équatorienne'>Équatorienne (Équateur)</option>
                                    <option value='Érythréenne'>Érythréenne (Érythrée)</option>
                                    <option value='Espagnole'>Espagnole (Espagne)</option>
                                    <option value='Est-timoraise'>Est-timoraise (Timor-Leste)</option>
                                    <option value='Estonienne'>Estonienne (Estonie)</option>
                                    <option value='Éthiopienne'>Éthiopienne (Éthiopie)</option>
                                    <option value='Fidjienne'>Fidjienne (Fidji)</option>
                                    <option value='Finlandaise'>Finlandaise (Finlande)</option>
                                    <option value='Française'>Française (France)</option>
                                    <option value='Gabonaise'>Gabonaise (Gabon)</option>
                                    <option value='Gambienne'>Gambienne (Gambie)</option>
                                    <option value='Georgienne'>Georgienne (Géorgie)</option>
                                    <option value='Ghanéenne'>Ghanéenne (Ghana)</option>
                                    <option value='Grenadienne'>Grenadienne (Grenade)</option>
                                    <option value='Guatémaltèque'>Guatémaltèque (Guatemala)</option>
                                    <option value='Guinéenne'>Guinéenne (Guinée)</option>
                                    <option value='Guyanienne'>Guyanienne (Guyana)</option>
                                    <option value='Haïtienne'>Haïtienne (Haïti)</option>
                                    <option value='Hellénique'>Hellénique (Grèce)</option>
                                    <option value='Hondurienne'>Hondurienne (Honduras)</option>
                                    <option value='Hongroise'>Hongroise (Hongrie)</option>
                                    <option value='Indienne'>Indienne (Inde)</option>
                                    <option value='Indonésienne'>Indonésienne (Indonésie)</option>
                                    <option value='Irakienne'>Irakienne (Iraq)</option>
                                    <option value='Iranienne'>Iranienne (Iran)</option>
                                    <option value='Irlandaise'>Irlandaise (Irlande)</option>
                                    <option value='Islandaise'>Islandaise (Islande)</option>
                                    <option value='Israélienne'>Israélienne (Israël)</option>
                                    <option value='Italienne'>Italienne (Italie)</option>
                                    <option value='Ivoirienne'>Ivoirienne (Côte d'Ivoire)</option>
                                    <option value='Jamaïcaine'>Jamaïcaine (Jamaïque)</option>
                                    <option value='Japonaise'>Japonaise (Japon)</option>
                                    <option value='Jordanienne'>Jordanienne (Jordanie)</option>
                                    <option value='Kazakhstanaise'>Kazakhstanaise (Kazakhstan)</option>
                                    <option value='Kenyane'>Kenyane (Kenya)</option>
                                    <option value='Kirghize'>Kirghize (Kirghizistan)</option>
                                    <option value='Kiribatienne'>Kiribatienne (Kiribati)</option>
                                    <option value='Kittitienne'>Kittitienne et Névicienne (Saint-Christophe-et-Niévès)</option>
                                    <option value='Koweïtienne'>Koweïtienne (Koweït)</option>
                                    <option value='Laotienne'>Laotienne (Laos)</option>
                                    <option value='Lesothane'>Lesothane (Lesotho)</option>
                                    <option value='Lettone'>Lettone (Lettonie)</option>
                                    <option value='Libanaise'>Libanaise (Liban)</option>
                                    <option value='Libérienne'>Libérienne (Libéria)</option>
                                    <option value='Libyenne'>Libyenne (Libye)</option>
                                    <option value='Liechtensteinoise'>Liechtensteinoise (Liechtenstein)</option>
                                    <option value='Lituanienne'>Lituanienne (Lituanie)</option>
                                    <option value='Luxembourgeoise'>Luxembourgeoise (Luxembourg)</option>
                                    <option value='Macédonienne'>Macédonienne (Macédoine)</option>
                                    <option value='Malaisienne'>Malaisienne (Malaisie)</option>
                                    <option value='Malawienne'>Malawienne (Malawi)</option>
                                    <option value='Maldivienne'>Maldivienne (Maldives)</option>
                                    <option value='Malgache'>Malgache (Madagascar)</option>
                                    <option value='Maliennes'>Maliennes (Mali)</option>
                                    <option value='Maltaise'>Maltaise (Malte)</option>
                                    <option value='Marocaine'>Marocaine (Maroc)</option>
                                    <option value='Marshallaise'>Marshallaise (Îles Marshall)</option>
                                    <option value='Mauricienne'>Mauricienne (Maurice)</option>
                                    <option value='Mauritanienne'>Mauritanienne (Mauritanie)</option>
                                    <option value='Mexicaine'>Mexicaine (Mexique)</option>
                                    <option value='Micronésienne'>Micronésienne (Micronésie)</option>
                                    <option value='Moldave'>Moldave (Moldovie)</option>
                                    <option value='Monegasque'>Monegasque (Monaco)</option>
                                    <option value='Mongole'>Mongole (Mongolie)</option>
                                    <option value='Monténégrine'>Monténégrine (Monténégro)</option>
                                    <option value='Mozambicaine'>Mozambicaine (Mozambique)</option>
                                    <option value='Namibienne'>Namibienne (Namibie)</option>
                                    <option value='Nauruane'>Nauruane (Nauru)</option>
                                    <option value='Néerlandaise'>Néerlandaise (Pays-Bas)</option>
                                    <option value='Néo-Zélandaise'>Néo-Zélandaise (Nouvelle-Zélande)</option>
                                    <option value='Népalaise'>Népalaise (Népal)</option>
                                    <option value='Nicaraguayenne'>Nicaraguayenne (Nicaragua)</option>
                                    <option value='Nigériane'>Nigériane (Nigéria)</option>
                                    <option value='Nigérienne'>Nigérienne (Niger)</option>
                                    <option value='Niuéenne'>Niuéenne (Niue)</option>
                                    <option value='Nord-coréenne'>Nord-coréenne (Corée du Nord)</option>
                                    <option value='Norvégienne'>Norvégienne (Norvège)</option>
                                    <option value='Omanaise'>Omanaise (Oman)</option>
                                    <option value='Ougandaise'>Ougandaise (Ouganda)</option>
                                    <option value='Ouzbéke'>Ouzbéke (Ouzbékistan)</option>
                                    <option value='Pakistanaise'>Pakistanaise (Pakistan)</option>
                                    <option value='Palaosienne'>Palaosienne (Palaos)</option>
                                    <option value='Palestinienne'>Palestinienne (Palestine)</option>
                                    <option value='Panaméenne'>Panaméenne (Panama)</option>
                                    <option value='Papouane-Néo-Guinéenne'>Papouane-Néo-Guinéenne (Papouasie-Nouvelle-Guinée)</option>
                                    <option value='Paraguayenne'>Paraguayenne (Paraguay)</option>
                                    <option value='Péruvienne'>Péruvienne (Pérou)</option>
                                    <option value='Philippine'>Philippine (Philippines)</option>
                                    <option value='Polonaise'>Polonaise (Pologne)</option>
                                    <option value='Portugaise'>Portugaise (Portugal)</option>
                                    <option value='Qatarienne'>Qatarienne (Qatar)</option>
                                    <option value='Roumaine'>Roumaine (Roumanie)</option>
                                    <option value='Russe'>Russe (Russie)</option>
                                    <option value='Rwandaise'>Rwandaise (Rwanda)</option>
                                    <option value='Saint-Lucienne'>Saint-Lucienne (Sainte-Lucie)</option>
                                    <option value='Saint-Marinaise'>Saint-Marinaise (Saint-Marin)</option>
                                    <option value='Saint-Vincentaise et Grenadine'>Saint-Vincentaise et Grenadine (Saint-Vincent-et-les Grenadines)</option>
                                    <option value='Salomonaise'>Salomonaise (Îles Salomon)</option>
                                    <option value='Salvadorienne'>Salvadorienne (Salvador)</option>
                                    <option value='Samoane'>Samoane (Samoa)</option>
                                    <option value='Santoméenne'>Santoméenne (Sao Tomé-et-Principe)</option>
                                    <option value='Saoudienne'>Saoudienne (Arabie saoudite)</option>
                                    <option value='Sénégalaise' selected>Sénégalaise (Sénégal)</option>
                                    <option value='Serbe'>Serbe (Serbie)</option>
                                    <option value='Seychelloise'>Seychelloise (Seychelles)</option>
                                    <option value='Sierra-Léonaise'>Sierra-Léonaise (Sierra Leone)</option>
                                    <option value='Singapourienne'>Singapourienne (Singapour)</option>
                                    <option value='Slovaque'>Slovaque (Slovaquie)</option>
                                    <option value='Slovène'>Slovène (Slovénie)</option>
                                    <option value='Somalienne'>Somalienne (Somalie)</option>
                                    <option value='Soudanaise'>Soudanaise (Soudan)</option>
                                    <option value='Sri-Lankaise'>Sri-Lankaise (Sri Lanka)</option>
                                    <option value='Sud-Africaine'>Sud-Africaine (Afrique du Sud)</option>
                                    <option value='Sud-Coréenne'>Sud-Coréenne (Corée du Sud)</option>
                                    <option value='Sud-Soudanaise'>Sud-Soudanaise (Soudan du Sud)</option>
                                    <option value='Suédoise'>Suédoise (Suède)</option>
                                    <option value='Suisse'>Suisse (Suisse)</option>
                                    <option value='Surinamaise'>Surinamaise (Suriname)</option>
                                    <option value='Swazie'>Swazie (Swaziland)</option>
                                    <option value='Syrienne'>Syrienne (Syrie)</option>
                                    <option value='Tadjike'>Tadjike (Tadjikistan)</option>
                                    <option value='Tanzanienne'>Tanzanienne (Tanzanie)</option>
                                    <option value='Tchadienne'>Tchadienne (Tchad)</option>
                                    <option value='Tchèque'>Tchèque (Tchéquie)</option>
                                    <option value='Thaïlandaise'>Thaïlandaise (Thaïlande)</option>
                                    <option value='Togolaise'>Togolaise (Togo)</option>
                                    <option value='Tonguienne'>Tonguienne (Tonga)</option>
                                    <option value='Trinidadienne'>Trinidadienne (Trinité-et-Tobago)</option>
                                    <option value='Tunisienne'>Tunisienne (Tunisie)</option>
                                    <option value='Turkmène'>Turkmène (Turkménistan)</option>
                                    <option value='Turque'>Turque (Turquie)</option>
                                    <option value='Tuvaluane'>Tuvaluane (Tuvalu)</option>
                                    <option value='Ukrainienne'>Ukrainienne (Ukraine)</option>
                                    <option value='Uruguayenne'>Uruguayenne (Uruguay)</option>
                                    <option value='Vanuatuane'>Vanuatuane (Vanuatu)</option>
                                    <option value='Vaticane'>Vaticane (Vatican)</option>
                                    <option value='Vénézuélienne'>Vénézuélienne (Venezuela)</option>
                                    <option value='Vietnamienne'>Vietnamienne (Viêt Nam)</option>
                                    <option value='Yéménite'>Yéménite (Yémen)</option>
                                    <option value='Zambienne'>Zambienne (Zambie)</option>
                                    <option value='Zimbabwéenne'>Zimbabwéenne (Zimbabwe)</option>
";
}

function convertirFormat($photo, $nom)
{
    if ($photo != "") {
        $b64 =  explode(",",  $photo)[1];
        $bin = base64_decode($b64);
        $im = imageCreateFromString($bin);

        if (!$im) {

            die('Base64 value is not a valid image');
            return "0";
        }

        $img_file = "../public/img/documents/$nom.png";

        imagepng($im, $img_file, 0);
        return $nom;
    }
    return "0";
}


function imprimer($nomFichier)
{
    return URLROOT . '/public/pdf/' . $nomFichier . '.php';
}

function downloadFile($nomFichier)
{
    $filepath = "https://extranet.infocopro.fr/documents/$nomFichier";
    $chemin = "../public/documents/$nomFichier";
    //  echo $filepath;
    if (file_exists($chemin)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream;charset=utf-8');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: no-cache');
        header('Content-Length: ' . filesize($filepath));
        ob_clean();
        flush(); // Flush system output buffer
        readfile($filepath);
        return true;
    }
    return false;
}

function deleteFileFromFolder($nomFichier)
{
    $chemin = "../public/documents/$nomFichier";

    if (file_exists($chemin)) {
        unlink($chemin);
        return true;
    }

    return false;
}

function getForm($tab = [])
{
    $tab = empty($tab) ? $_POST : $tab;
    $table = [];
    foreach ($tab as $k => $val) {
        //$table[$k] = trim(htmlspecialchars($val));
        $table[$k] = trim(str_replace('\'', '\\\'', htmlspecialchars($val)));
    }
    return $table;
}

function isEmptyOnTab($tab): bool
{
    foreach ($tab as $k => $v) {
        if (empty($v)) {
            return true;
        }
    }
    return false;
}

function dateEnAge($date)
{
    $duree = nbreJours($date);
    $text = "";
    if ($duree['annees'] != 0) {
        echo $duree['annees'];
        if ($duree['annees'] == 1) {
            $text .= ' an ';
        } else {
            $text .= ' ans ';
        }
    }
    if ($duree['mois'] != 0) {
        $text .= $duree['mois'] . " mois ";
    }
    if ($duree['jours'] != 0) {
        $text .= $duree['jours'];
        if ($duree['jours'] == 1) {
            $text .= ' jour';
        } else {
            $text .= ' jours';
        }
    }
    return $text;
}

function nbreJours($d)
{
    $dateDuJour = new DateTime(date("Y-m-d H:i:s"));
    $dateEntree = new DateTime(date("Y-m-d H:i:s", strtotime($d)));
    $diff = $dateEntree->diff($dateDuJour);
    $dureesejour = $diff->format('%a');
    $ans = floor($dureesejour / 365);
    $mois = floor(($dureesejour % 365) / 30);
    $jours = floor(($dureesejour % 365) % 30);
    $dat['annees'] = $ans;
    $dat['mois'] = $mois;
    $dat['jours'] = $jours;
    return $dat;
}

function isEmptyTable($table)
{
    $sql = "SELECT * FROM $table";
    $db = new Database();
    $db->query($sql);
    return sizeof($db->resultSet()) > 0;
}
//
function existValueTable($nomTable, $col, $value)
{
    $db = new Database();
    $value = addcslashes($value, "'");

    $sql = "SELECT * FROM $nomTable WHERE  $col='{$value}'";
    $db->query($sql);
    return sizeof($db->resultSet()) > 0;
}
//REDIRECTION
function redirectToPage($controller, $method = "index", $params = "")
{
    if (empty($params))
        header("Location:" . URLROOT . "/" . $controller . '/' . $method);
    else
        header("Location:" . URLROOT . "/" . $controller . '/' . $method . "/" . $params);
}

//MENU DYNAMIQUE


//redirect If not Connect
function redirectToConnection()
{
    !Role::isLogged() ?  header("Location:" . URLROOT) : '';
}

//Routes privées
function addPrivateRoute()
{
    if (isset($_POST['btnPrive'])) {
        //Mettre les liens dans un tableau
        $tabLiens = explode(",", $_SESSION["connectedUser"]->liens);

        //Recuperer la route actuelle
        $url = explode("/", $_GET['url']);
        $methode = (isset($url[1])) ? $url[1] : 'index';
        $url = ucfirst($url[0] . "/" . $methode);
        //Verifier si la route est deja dans les liens sinon l'ajouter
        if (!in_array($url, $tabLiens)) {
            if (empty($_SESSION["connectedUser"]->liens)) {
                $_SESSION["connectedUser"]->liens = $url;
            } else {
                $_SESSION["connectedUser"]->liens .= ',' . $url;
            }
        }
    }
}

function getRouteAccess()
{
    //Mettre les liens dans un tableau
    $tabLiens = explode(",", $_SESSION["connectedUser"]->liens);
    //Recuperer la route actuelle
    $url = explode("/", $_GET['url']);
    $methode = (isset($url[1])) ? $url[1] : 'index';
    $url = ucfirst($url[0] . "/" . $methode);
    //Verifier si la route est deja dans les liens sinon l'ajouter
    return in_array($url, $tabLiens);
}

function moisEnChaine($numero)
{
    switch ($numero) {
        case 1:
            return "Janvier";
        case 2:
            return "Février";
        case 3:
            return "Mars";
        case 4:
            return "Avril";
        case 5:
            return "Mai";
        case 6:
            return "Juin";
        case 7:
            return "Juillet";
        case 8:
            return "Août";
        case 9:
            return "Septembre";
        case 10:
            return "Octobre";
        case 11:
            return "Novembre";
        case 12:
            return "Décembre";
    }
}

function inputHiddenForUrlRoot()
{
    echo '
        <input type="hidden" id="URLROOT" value="' . URLROOT . '">
    ';
}

function my_formatNumberEspace($number)
{
    return number_format($number, 0, '', ' ');
}

function getAddress($latitude, $longitude, $cp = "")
{
    //google map api url
    $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyAD7UPLSEoDFpHmYAtLgRi05gkFQaikiMc";

    // send http request
    if ($cp == "") {
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);
        $address = $json->results[0]->formatted_address;
        return $address;
    } else {
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);
        $address = $json->results[0];
        return $address;
    }
}

function getAddressExact($adresse)
{
    //google map api url
    $url = "https://maps.google.com/maps/api/geocode/json?address=$adresse&key=AIzaSyAD7UPLSEoDFpHmYAtLgRi05gkFQaikiMc";
    $geocode = file_get_contents($url);
    return $geocode;
    $json = json_decode($geocode);
    $address = "";
    if ($json->results != null && $json->results->length != 0) {
        $address = $json->results[0]->formatted_address;
    }
    return $address;
}

function convertDateMysqlFormat($date, $type = '')
{
    $date = trim($date);
    $heure = (isset(explode(' ', $date)[1])) ? " " . (explode(' ', $date)[1]) : "";
    $date2 = (isset(explode(' ', $date)[0])) ? (explode(' ', $date)[0]) : "";
    $t = [];
    if (str_contains($date, "-")) {
        $t = explode("-", $date2);
        if (strlen($t[0]) == 2) {
            return $t[2] . "-" . $t[1] . "-" . $t[0] . ($type != "" ? $heure : "");
        } else {
            return $date;
        }
    } else {
        if (str_contains($date, "/")) {

            $t = explode("/", $date2);

            if (strlen(trim($t[0])) == 2) {

                return trim($t[2]) . "-" . trim($t[1]) . "-" . trim($t[0]) . ($type != "" ? $heure : "");
            } else {
                return $date;
            }
        } else {
            return $date;
        }
    }
}

function getModules()
{
    $db = new Database();
    $db->query("SELECT * FROM wbcc_module");
    $modules = $db->resultSet();
    foreach ($modules as $key => $module) {
        $db->query("SELECT * FROM wbcc_sous_module WHERE idModuleF=$module->idModule");
        $module->sousModules = $db->resultSet();
    }
    return $modules;
}

function getModulesByRole($idRole)
{
    $db = new Database();
    $db->query("SELECT * FROM wbcc_role_sous_module WHERE idRoleF=$idRole");
    $rolesModules = $db->resultSet();
    return $rolesModules;
}


function getModulesByIdUserAndIdRole($user, $idRole = null)
{
    $db = new Database();
    $idRole = $user ? $user->role : $idRole;
    $db->query("SELECT * FROM wbcc_role_sous_module WHERE idRoleF=$idRole AND etatRoleSousModule  = 1");
    $rolesModules = $db->resultSet();
    $tab = [];
    $db->query("SELECT * FROM wbcc_module");
    $modules = $db->resultSet();

    foreach ($modules as $key => $module) {
        $db->query("SELECT * FROM wbcc_sous_module WHERE idModuleF=$module->idModule");
        $sousModules = $db->resultSet();
        $j = 0;
        $tabSM = [];
        foreach ($sousModules as $key => $sm) {
            $elts = array_filter($rolesModules, fn($value) => $value->idSousModuleF == $sm->idSousModule);
            if ($elts && sizeof($elts) > 0) {
                $j++;
                $tabSM[] = $sm;
            }
        }
        if ($j != 0) {
            $ifAdd = true;
            //SEARCH IF DIRIGEANT RESPONSABLE OU SALARIE
            if ($idRole == "13" || $idRole == "14" || $idRole == "15") {
                if ($module->idModule == 4) {
                    if ((isset($_SESSION['connectedUser']->typeCompany) && strtolower($_SESSION['connectedUser']->typeCompany) == "artisan")) {
                        $ifAdd = true;
                    } else {
                        $ifAdd = false;
                    }
                } else {
                    if ($module->idModule == 10) {
                        if ((isset($_SESSION['connectedUser']->typeCompany) && strtolower($_SESSION['connectedUser']->typeCompany) == "artisan")) {
                            $ifAdd = false;
                        } else {
                            $ifAdd = true;
                        }
                    }
                }
            }
            if ($ifAdd) {
                $module->sousModules = $tabSM;
                $tab[] = $module;
            }
        }
    }
    return $tab;
}

//FUNCTION RELATIVE AUX OP
function getEtapeForOP($idOP)
{
    $sql = "SELECT * FROM wbcc_activity a, wbcc_opportunity_activity oa, wbcc_activity_db adb WHERE  a.idActivity = oa.idActivityF AND a.isCleared='False' AND oa.idOpportunityF = $idOP AND a.codeActivity IS NOT NULL AND a.codeActivity != '' AND a.codeActivity = adb.codeActivity GROUP BY a.codeActivity ";
    $db = new Database();
    $db->query($sql);
    $datas = $db->resultSet();
    $etape = "En attente de : ";
    foreach ($datas as $key => $value) {
        if ($value->codeActivity == "24") {
            $rf = findItemByColumn("wbcc_recherche_fuite", "idOpportunityF", $idOP);
            if ($rf && $rf->demandeSignatureEnvoye == "1") {
                $etape .= "'En attente de signature du responsable',";
            } else {
                $etape .= "'$value->libelleActivity',";
            }
        } else {
            $etape .= "'$value->libelleActivity',";
        }
    }
    return $etape;
}

function closeActivityByOPAndRegarding($idOP, $opName, $regarding, $type, $auteur, $numeroAuteur, $idAuteur, $action, $noteText, $plainText, $idContactNotifier, $dateDebutRV = '', $numSinistre, $codeActivity, $coordonneeExpert = '', $resultatPC = '', $la = '', $modeReglement = '', $reglementImm = '', $dateReglementImm = '', $reglementDiff = '', $dateReglementDiff = '', $adresse = '', $idEnveloppe = '', $frtO = '', $dateFinRV = '', $idAuteurRV = '')
{
    $db = new Database();
    $activities = [];
    if ($idOP != null && $idOP != '' && $idOP != '0') {
        $op = findItemByColumn("wbcc_opportunity", "idOpportunity", $idOP);
        //gestionnaire
        $db->query("SELECT * FROM wbcc_contact, wbcc_utilisateur WHERE idContact= idContactF AND idUtilisateur=$op->gestionnaire LIMIT 1");
        $ges = $db->single();

        $db->query("SELECT d.* FROM `wbcc_devis`d , wbcc_opportunity_devis od WHERE d.idDevis = od.idDevisF AND od.idOpportunityF = $idOP AND d.devisFile != '' && d.devisFile IS NOT NULL LIMIT 1");
        $devis =  $db->single();

        $db->query("SELECT * FROM wbcc_opportunity_activity oa, wbcc_activity a WHERE a.idActivity=oa.idActivityF AND codeActivity = $codeActivity AND idOpportunityF=$idOP ");
        $activities = $db->resultSet();
    }

    if ($idEnveloppe != null && $idEnveloppe != '' && $idEnveloppe != '0') {
        $db->query("SELECT * FROM wbcc_enveloppe_activity oa, wbcc_activity a WHERE a.idActivity=oa.idActivityF AND codeActivity = $codeActivity AND idEnveloppeF=$idEnveloppe ");
        $activities = $db->resultSet();
    }

    $close = 0;
    $act = false;
    if (sizeof($activities)) {
        foreach ($activities as $key => $activity) {
            if (strtolower($activity->isCleared) == "false") {
                $close = 1;
                $act = $activity;
                $db->query("UPDATE wbcc_activity SET isCleared = 'True', editDate=:editDate, realisedBy=:realisedBy, idRealisedBy =:idRealisedBy WHERE idActivity = $activity->idActivity");
                $db->bind("editDate", date("Y-m-d H:i:s"), null);
                $db->bind("realisedBy", $auteur, null);
                $db->bind("idRealisedBy", $idAuteur, null);
                $db->execute();
            }
        }
    } else {
        //CREATE ACTIVITY AND CLOSES
        if ($idOP != null && $idOP != '' && $idOP != '0') {
            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . '-' .  $regarding, "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "True", "1", $codeActivity, "", $idAuteur, $auteur);
            $close = 1;
        }
        if ($idEnveloppe != null && $idEnveloppe != '' && $idEnveloppe != '0') {
            createNewActivity('0', '', $idAuteur, $auteur, '', $regarding, '', date('Y-m-d H:i'),  date('Y-m-d H:i'), 'Tâche à Faire', "True", '1', 38, $idEnveloppe, '');
            $close = 1;
        }
    }
    // if ($close == 1) 
    {
        //UPDATE OPPORTUNITY
        if ($codeActivity != "") {
            if ($idOP != null && $idOP != '' && $idOP != '0') {

                $realisation = "";
                $dateRealisation = "";
                $idAuteurRealisation = "";
                $etape = $op  && $op->etapeOp != null ? $op->etapeOp :  "";
                if ($codeActivity == "1") {
                    $realisation = "delegationSigne";
                    $dateRealisation = "dateSignatureDelegation";
                    $idAuteurRealisation = "idAuteurSignatureDelegation";
                    $etape = $etape != "" ? $etape : "Télé-Expertise + Prise de RDV RT";
                }
                if ($codeActivity == "2") {
                    $realisation = "teleExpertiseFaite";
                    $dateRealisation = "dateTeleExpertise";
                    $idAuteurRealisation = "idAuteurTeleExpertise";
                    $etape = $etape != "" ? $etape : "Télé-Expertise + Prise de RDV RT";
                }
                if ($codeActivity == "3") {
                    $realisation = "priseRvRT";
                    $dateRealisation = "datePriseRvRT";
                    $idAuteurRealisation = "idAuteurPriseRvRT";
                    $etape =  "En attente de FRT";
                }
                if ($codeActivity == "4") {
                    $realisation = "declarationCie";
                    $dateRealisation = "dateDeclarationCie";
                    $idAuteurRealisation = "idAuteurDeclarationCie";
                    $etape = "Relance Cie pour Numéro de Sinistre";
                }
                if ($codeActivity == "5") {
                    $realisation = "relanceCieNumSinistre";
                    $dateRealisation = "dateRelanceCieNumSinistre";
                    $idAuteurRealisation = "idAuteurRelanceCieNumSinistre";
                }

                if ($codeActivity == "6") {
                    $realisation = "frtFait";
                    $dateRealisation = "dateFrt";
                    $idAuteurRealisation = "idAuteurFrt";
                    $etape = $idAuteur == "9" || $idAuteur == "547" || $idAuteur == "596" ? "En Attente de Faire Devis" : "En Attente de Contrôle FRT";
                }
                if ($codeActivity == "7") {
                    $realisation = "devisFais";
                    $dateRealisation = "dateDevisFais";
                    $idAuteurRealisation = "idAuteurDevisFais";
                    $etape = "En Attente d'envoi de Devis";
                    if ($op && $op->type == "A.M.O.2") {
                        $etape = "En Attente de réglement";
                    }
                }
                if ($codeActivity == "8") {
                    $realisation = "controlDevis";
                    $dateRealisation = "dateControlDevis";
                    $idAuteurRealisation = "idAuteurControlDevis";
                    $etape = "En Attente d'envoi de Devis + Compte Rendu";
                }
                if ($codeActivity == "9") {
                    $realisation = "envoiDevis";
                    $dateRealisation = "dateEnvoiDevis";
                    $idAuteurRealisation = "idAuteurEnvoiDevis";
                    $etape = "En Attente de Prise en Charge de Devis par Cie";
                }
                if ($codeActivity == "10") {
                    $realisation = "priseEnCharge";
                    $dateRealisation = "datePriseEnCharge";
                    $idAuteurRealisation = "idAuteurPriseEnCharge";
                    $etape =  $resultatPC == 'non' ? "En Attente de RDV Expertise" : ($reglementImm == "1" ? "En attente d'encaissement de l'immédiat" : "En attente de RDV Travaux");
                }
                if ($codeActivity == "11") {
                    $realisation = "controleFRT";
                    $dateRealisation = "dateControleFRT";
                    $idAuteurRealisation = "idAuteurControleFRT";
                    $etape = "En Attente de Faire Devis";
                }

                if ($codeActivity == "16") {
                    $realisation = "faireRapportRT";
                    $dateRealisation = "dateFaireRapportRT";
                    $idAuteurRealisation = "IdAuteurFaireRapportRT";
                }
                if ($codeActivity == "17") {
                    $realisation = "controleRT";
                    $dateRealisation = "dateControleRT";
                    $idAuteurRealisation = "idAuteurControleRT";
                }
                if ($codeActivity == "21") {
                    $realisation = "relanceCiePaiementImmediat";
                    $dateRealisation = "dateRelanceCiePaiementImmediat";
                    $idAuteurRealisation = "idAuteurRelanceCiePaiementImmediat";
                    $etape =  "En attente d'encaissement de l'immédiat";
                }

                if ($codeActivity == "32") {
                    $realisation = "justificatifReparation";
                    $dateRealisation = "dateJustificatifReparation";
                    $idAuteurRealisation = "idAuteurJustificatifReparation";
                    $etape =  "En attente d'envoi de justificatif de réparation";
                }

                if ($codeActivity == "44") {
                    $realisation = "envoiConstatDDE";
                    $dateRealisation = "dateEnvoiConstatDDE";
                    $idAuteurRealisation = "idAuteurEnvoiConstatDDE";
                    $etape =  "En attente de Retour de la Cie";
                }

                if ($codeActivity == "42") {
                    $realisation = "envoiJustificatifRF";
                    $dateRealisation = "dateEnvoiJustificatifRF";
                    $idAuteurRealisation = "idAuteurEnvoiJustificatifRF";
                    $etape =  "En attente de Retour de la Cie";
                }

                if ($codeActivity == "43") {
                    $realisation = "retourExpertiseTraite";
                    $dateRealisation = "dateRetourExpertiseTraite";
                    $idAuteurRealisation = "idAuteurRetourExpertiseTraite";
                    $etape =  "En attente de relance pour le paiement de l'immédiat";
                }

                if ($codeActivity == "33") {
                    // $realisation = "retourExpertiseTraite";
                    // $dateRealisation = "dateRetourExpertiseTraite";
                    // $idAuteurRealisation = "idAuteurRetourExpertiseTraite";
                }

                if ($codeActivity == "27") {
                    // $realisation = "retourExpertiseTraite";
                    // $dateRealisation = "dateRetourExpertiseTraite";
                    // $idAuteurRealisation = "idAuteurRetourExpertiseTraite";
                }

                if ($codeActivity == "37") {
                    // $realisation = "retourExpertiseTraite";
                    // $dateRealisation = "dateRetourExpertiseTraite";
                    // $idAuteurRealisation = "idAuteurRetourExpertiseTraite";
                }

                if ($codeActivity == "31") {
                    // $realisation = "retourExpertiseTraite";
                    // $dateRealisation = "dateRetourExpertiseTraite";
                    // $idAuteurRealisation = "idAuteurRetourExpertiseTraite";
                }

                if ($codeActivity == "18") {
                    $realisation = "rvExpertiseFait";
                    $dateRealisation = "dateExpertise";
                    $idAuteurRealisation = "idAuteurExpertise";
                    $etape =  "En attente de Traitement du retour Expertise";
                }

                if ($codeActivity == "30") {
                    $realisation = "faireRechercheFuite";
                    $dateRealisation = "dateRechercheFuite";
                    $idAuteurRealisation = "idAuteurRechercheFuite ";
                }

                if ($codeActivity == "29") {
                    // $realisation = "faireRechercheFuite";
                    // $dateRealisation = "dateRechercheFuite";
                    // $idAuteurRealisation = "idAuteurRechercheFuite ";
                }

                if ($codeActivity == "23") {
                    $realisation = "priseRvTravaux";
                    $dateRealisation = "datePriseRvTravaux";
                    $idAuteurRealisation = "idAuteurPriseRvTravaux ";
                }

                if ($codeActivity == "45") {
                    $realisation = "faireRvTravaux";
                    $dateRealisation = "dateFaireRvTravaux";
                    $idAuteurRealisation = "idAuteurFaireRvTravaux ";
                }

                if ($codeActivity == "24") {
                    $realisation = "genererConstatDDE";
                    $dateRealisation = "dateGenererConstatDDE";
                    $idAuteurRealisation = "idAuteurGenererConstatDDE ";
                }
                if ($realisation != "" && $dateRealisation != "" &&  $idAuteurRealisation != "") {
                    $db->query("UPDATE wbcc_opportunity SET $realisation = 1, $dateRealisation=:date, $idAuteurRealisation=:auteur, etapeOp=:etapeOp  WHERE idOpportunity = $idOP");
                    $db->bind("auteur", $idAuteur, null);
                    $db->bind("date", date("Y-m-d H:i:s"), null);
                    $db->bind("etapeOp", $etape, null);
                    $db->execute();
                }
            }
        }
        // 

        {
            //CREATE HISTORIQUE
            if ($act) {
                createHistorique("Clôture Activité : $act->regarding", $auteur, $idAuteur, $idOP);
            }
            if ($codeActivity != "24") {
                createNote($idOP, $idAuteur, $auteur, ($noteText . ($codeActivity == 3 ? " / Rendez-vous pris pour le $dateDebutRV" : "")), ($noteText . ($codeActivity == 3 ? " / Rendez-vous pris pour le $dateDebutRV" : "")), ($codeActivity == "1" || $codeActivity == "2" || $codeActivity == "3" || $codeActivity == "4" || $codeActivity == "5"  ? 1 : 0), $codeActivity);
            }
            /*   //Notifier Note
            if ($idContactNotifier != "" && $idContactNotifier != null && $idAuteur != "518") {
                $contact = findItemByColumn("wbcc_contact", "idContact", $idContactNotifier);

                $r = new Role();
                $cc = ["gestion@wbcc.fr"];
                $subject = $opName . "/" . $auteur . " - Note Extranet";
                if ($contact) {
                    $to = $contact->emailContact;
                    if ($to != null && $to != "") {
                        $txt = "Bonjour $contact->civiliteContact $contact->prenomContact $contact->nomContact, <br> <br> 
                        <b>$auteur</b> de <b>WBCC Assistance</b> a ajouté une note : 
                        <br> ------------------------------------------------------------------------------
                        <br>$noteText<br>
                        ------------------------------------------------------------------------------<br>
                        <b>WBCC ASSISTANCE Extranet</b>
                        ";
                        if ($codeActivity != "6") {
                            $r::mailExtranetWithFiles($to, $subject, $txt, $cc, [], []);
                        }
                    }
                    // $to = "wbcc021@gmail.com";

                }
            } */

            $typeActivity =  "Tâche à faire";
            $today =  date("Y-m-d H:i:s");
            $auteurRV = false;
            //CREATE NEW ACTIVITY
            if ($codeActivity == "3") {
                $activity = findActivityByIdOP($idOP, 6);
                if ($activity) {
                } else {
                    //EXPERT
                    if ($idAuteurRV != null && $idAuteurRV != "" && $idAuteurRV != "0") {
                        $db->query("SELECT * FROM wbcc_contact, wbcc_utilisateur WHERE idContact= idContactF AND idUtilisateur=$idAuteurRV LIMIT 1");
                        $auteurRV = $db->single();
                    }

                    if ($dateDebutRV != null && $dateDebutRV != "") {
                        $dateRV = $dateDebutRV;
                        $heureDebut = "09:00";
                        $heureFin = "10:00";
                        if (str_contains($dateRV, " ")) {
                            $heureDebut = explode(" ", $dateRV)[1];
                        }
                        if (substr($dateDebutRV, 4, 1) == "-") {
                        } else {
                            $dateRV = substr($dateDebutRV, 6, 4) . "-"   . substr($dateDebutRV, 3, 2) . "-" . substr($dateDebutRV, 0, 2);
                        }

                        // $date = new DateTime($dateRV);
                        $dateFin = new DateTime($dateRV . " " . $heureDebut);
                        $heureFin = ($dateFin->modify("+30 minutes"))->format('H:i');
                        $heureDebut = str_replace(' ', '', $heureDebut);
                        createNewActivity($idOP, $opName, $auteurRV->idUtilisateur, $auteurRV->fullName, $auteurRV->numeroContact,  $opName . "-Faire Relevés Techniques", "",  "$dateRV $heureDebut", "$dateRV $heureFin", "Rendez-vous", 'False', "1", "6", $adresse);
                    } else {
                        createNewActivity($idOP, $opName, $auteurRV->idUtilisateur, $auteurRV->fullName, $auteurRV->numeroContact,   $opName . "-Faire Relevés Techniques", "",  $today, $today, "Rendez-vous", 'False', "1", "6", $adresse);
                    }
                }
            }

            //CREATE NEW ACTIVITY
            if ($codeActivity == "4") {
                if ($numSinistre == null || $numSinistre == "") {
                    $activity = findActivityByIdOP($idOP, 5);
                    if ($activity) {
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Relancer la compagnie pour numèro de sinistre", "",  $today, date("Y-m-d H:i:s", strtotime('+3 days')), $typeActivity, 'False', "0", "5");
                    }
                } else {
                    $realisation = "relanceCieNumSinistre";
                    $dateRealisation = "dateRelanceCieNumSinistre";
                    $idAuteurRealisation = "idAuteurRelanceCieNumSinistre";

                    $db->query("UPDATE wbcc_opportunity SET $realisation = 1, $dateRealisation=:date, $idAuteurRealisation=:auteur  WHERE idOpportunity = $idOP");
                    $db->bind("auteur", $idAuteur, null);
                    $db->bind("date", date("Y-m-d H:i:s"), null);
                    $db->execute();

                    $activity = findActivityByIdOP($idOP, 5);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'True',editDate=:editDate, realisedBy=:realisedBy, idRealisedBy =:idRealisedBy WHERE idActivity = $activity->idActivity");
                        $db->bind("editDate", date("Y-m-d H:i:s"), null);
                        $db->bind("realisedBy", $auteur, null);
                        $db->bind("idRealisedBy", $idAuteur, null);
                        $db->execute();
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Relancer la compagnie pour numèro de sinistre", "",  $today, date("Y-m-d H:i:s", strtotime('+3 days')), $typeActivity, 'True', "0", "5", "", $idAuteur, $auteur);
                    }
                }
            }

            //CREATE NEW ACTIVITY FD
            if ($codeActivity == "6") {
                $today =  date("Y-m-d H:i:s");
                //UPDATE RV
                $db->query("UPDATE wbcc_rendez_vous SET etatRV = :etatRV AND typeRV='RTP' WHERE idOpportunityF =:idOpportunityF");
                $db->bind("etatRV", 1, null);
                $db->bind("idOpportunityF", $idOP, null);
                $db->execute();

                //FREE AGENDA IF FUTURE
                updateAgenda($idOP, "RT");

                //NO NIVEAU DE CONTROLE SI HABY-HEND-HANNA OR FRT OUTLOOK
                if ($idAuteur == "9" || $idAuteur == "547" || $idAuteur == "596"  || $frtO == 1) {
                    $db->query("UPDATE wbcc_opportunity SET controleFRT=1, idAuteurControleFRT=$idAuteur, dateControleFRT='$today',  controleFRT2=1, idAuteurControleFRT2=$idAuteur, dateControleFRT2='$today', controleFRT3=1, idAuteurControleFRT3=$idAuteur, dateControleFRT3='$today'   WHERE idOpportunity = $idOP");
                    $db->execute();
                    $db->query("UPDATE wbcc_rendez_vous SET etatFRT = :etatFRT, commentaireControleFRT=:commentaireControleFRT, idAuteurControleFRT=:idAuteurControleFRT, dateControleFRT=:dateControleFRT  WHERE idOpportunityF = $idOP");
                    $db->bind("etatFRT", 1);
                    $db->bind("idAuteurControleFRT", $idAuteur);
                    $db->bind("dateControleFRT", date("Y-m-d H:i:s"));
                    $db->bind("commentaireControleFRT", "FRT VALIDEE");
                    //CONTROLE FRT
                    $activity = findActivityByIdOP($idOP, 11);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'True', editDate=:editDate, realisedBy=:realisedBy, idRealisedBy =:idRealisedBy WHERE idActivity = $activity->idActivity");
                        $db->bind("editDate", date("Y-m-d H:i:s"), null);
                        $db->bind("realisedBy", $auteur, null);
                        $db->bind("idRealisedBy", $idAuteur, null);
                        $db->execute();
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Contrôle FRT", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), "Tâche à faire", 'True', "0", "11", $idAuteur, $auteur);
                    }

                    //FAIRE DEVIS
                    $activity = findActivityByIdOP($idOP, 7);
                    if ($activity) {
                        // $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                        // $db->execute();
                    } else {
                        createNewActivity($idOP, $opName,  $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Faire Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), "Tâche à faire", 'False', "0", "7");
                    }
                } else {
                    $activity = findActivityByIdOP($idOP, 11);
                    if ($activity) {
                    } else {
                        createNewActivity($idOP, $opName,  $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Contrôle FRT", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), "Tâche à faire", 'False', "0", "11");
                    }
                }

                $activity = findActivityByIdOP($idOP, 4);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName,  $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Déclaration à la compagnie", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), "Tâche à faire", 'False', "0", "4");
                }
            }

            //CREATE NEW ACTIVITY CD
            if ($codeActivity == "7") {
                //CONTROLE DEVIS
                $db->query("UPDATE wbcc_opportunity SET controlDevis=1, dateControlDevis='$today', idAuteurControlDevis= $idAuteur WHERE idOpportunity = $idOP");
                $db->execute();
                $activity = findActivityByIdOP($idOP, 8);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "- Contrôle Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'True', "0", "8");
                }
                if ($op && $op->type == "A.M.O.2") {
                    //ED
                    $activity = findActivityByIdOP($idOP, 9);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "- Envoi Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'True', "0", "9");
                    }

                    //PC
                    $activity = findActivityByIdOP($idOP, 10);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "- Prise en Charge Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'True', "0", "10");
                    }

                    $db->query("UPDATE wbcc_opportunity SET envoiDevis=1, dateEnvoiDevis='$today', priseEnCharge=1 WHERE idOpportunity = $idOP");
                    $db->execute();
                } else {

                    //ED
                    $activity = findActivityByIdOP($idOP, 9);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "- Envoi Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "9");
                    }

                    //CREATE TACHE FAIRE COMPTE RENDU RT
                    $activity = findActivityByIdOP($idOP, 16);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName,  $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  "$opName - Faire Compte Rendu RT", "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "False", "0", "16");
                    }

                    //FRANCHISE
                    $activity = findActivityByIdOP($idOP, 25);
                    if ($activity) {
                        // $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                        // $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "- Analyse Police d'Assurance", "", $today, $today, $typeActivity, 'False', "0", "25");
                    }
                }

                $db->query("UPDATE wbcc_rendez_vous SET etatRV = :etatRV AND typeRV='RTP' WHERE idOpportunityF =:idOpportunityF");
                $db->bind("etatRV", 1, null);
                $db->bind("idOpportunityF", $idOP, null);
                $db->execute();
            }

            //CREATE NEW ACTIVITY ED
            if ($codeActivity == "8") {
                $activity = findActivityByIdOP($idOP, 9);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Envoi Devis", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "9");
                }
            }

            //CREATE NEW ACTIVITY PC
            if ($codeActivity == "9") {
                $activity = findActivityByIdOP($idOP, 10);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Relancer la compagnie pour la prise en charge", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "10");
                }
            }

            //CREATE NEW ACTIVITY RA
            if ($codeActivity == "10") {
                //
                if ($resultatPC == "non") {
                    $resultT19 = $coordonneeExpert == 'oui' ? 'True' : 'False';
                    $activity = findActivityByIdOP($idOP, 19);
                    if ($activity) {
                        $db->query("UPDATE wbcc_activity SET isCleared = '$resultT19' WHERE idActivity = $activity->idActivity");
                        $db->execute();
                    } else {
                        createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Appeler compagnie pour prendre Coordonnées Expert", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, $resultT19, "0", "19");
                    }
                    if ($resultT19 == 'True') {
                        $activity = findActivityByIdOP($idOP, 14);
                        if ($activity) {
                            $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                            $db->execute();
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Appeler le sinistré pour prendre RDV d'expertise", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "14");
                        }
                        $activity = findActivityByIdOP($idOP, 15);
                        if ($activity) {
                            $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                            $db->execute();
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Appeler CABINET d'EXPERT pour Prendre RDV Expertise", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "15");
                        }
                    }
                } else {
                    if ($la == "oui") {
                        $activity = findActivityByIdOP($idOP, 20);
                        if ($activity) {
                            $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                            $db->execute();
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Relance compagnie pour avoir lettre d’acceptation", "", $today, date("Y-m-d H:i:s", strtotime('+2 days')), $typeActivity, 'False', "0", "20");
                        }
                    }
                    //REGLEMENT
                    if ($reglementImm == "1") {
                        $activity = findActivityByIdOP($idOP, 21);
                        if ($activity) {
                            $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                            $db->execute();
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Relance compagnie pour paiement de l'immédiat", "", $dateReglementImm . " 09:00", $dateReglementImm . " 18:00", $typeActivity, 'False', "0", "21");
                        }

                        $activity = findActivityByIdOP($idOP, 27);
                        if ($activity) {
                            $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                            $db->execute();
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Règlement Immédiat", "", $dateReglementImm . " 09:00", $dateReglementImm . " 18:00", $typeActivity, 'False', "0", "27");
                        }
                    } else {
                        //CREATTION RDV RAVAUX SI REGLEMENT TOTALITE SUR FACTURE ACQUITTE
                        if ($modeReglement == "En une seule fois" && $reglementDiff == "1") {
                            $activity = findActivityByIdOP($idOP, 23);
                            if ($activity) {
                                $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                                $db->execute();
                            } else {
                                createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "-Prendre RDV Travaux", "", $dateReglementImm . " 09:00", $dateReglementImm . " 18:00", $typeActivity, 'False', "0", "23");
                            }
                        }
                    }
                }

                $activity = findActivityByIdOP($idOP, 12);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . "- Contester Refus de prise en charge du devis", "", date('Y-m-d H:i:s'), date("Y-m-d H:i:s"), "Tâcha à faire", 'True', "0", "12");
                }
            }

            //CREATE NEW ACTIVITY CONTROLE RT
            if ($codeActivity == "16") {
                $activity = findActivityByIdOP($idOP, 17);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Contrôler Compte Rendu RT", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'False', "0", "17");
                }
            }

            if ($codeActivity == "32") {
                $activity = findActivityByIdOP($idOP, 29);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                }

                $activity = findActivityByIdOP($idOP, 30);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Faire Recherche de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'True', "0", "30");
                }

                $activity = findActivityByIdOP($idOP, 33);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Réparation de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'True', "0", "33");
                }

                $activity = findActivityByIdOP($idOP, 42);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Envoi Justificatif de réparation de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'False', "0", "42");
                }
            }

            if ($codeActivity == "33") {
                $activity = findActivityByIdOP($idOP, 29);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                }

                $activity = findActivityByIdOP($idOP, 30);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Faire Recherche de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'True', "0", "30");
                }

                $activity = findActivityByIdOP($idOP, 33);
                if ($activity) {
                    if ($activity->isCleared == 'True') {
                        $activity2 = findActivityByIdOP($idOP, 42);
                        if ($activity2) {
                        } else {
                            createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Envoi Justificatif de réparation de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'False', "0", "42");
                        }
                    }
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Réparation de Fuite", "", $today, date("Y-m-d H:i:s", strtotime('+1 days')), $typeActivity, 'False', "0", "33");
                }
            }

            if ($codeActivity == "43") {
            }

            if ($codeActivity == "27") {
                //Tache Faire travaux
                $activity = findActivityByIdOP($idOP, 23);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, "518", "Compte WBCC", "5770501a-425d-4f50-b66a-016c2dbb2557", "$opName - En attente de Prise de RDV Travaux", "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "False", "0", "23");
                }
                //Close relance encaissement
                $activity = findActivityByIdOP($idOP, 21);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                    $db->query("UPDATE wbcc_opportunity SET relanceCiePaiementImmediat=1 WHERE idOpportunity=$idOP");
                    $db->execute();
                }
                //Close RELANCE PC
                $activity = findActivityByIdOP($idOP, 10);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();

                    $db->query("UPDATE wbcc_opportunity SET priseEnCharge=1 WHERE idOpportunity=$idOP");
                    $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $idAuteur, $auteur, '',   $opName . "-Relancer la compagnie pour prise en charge du devis", "",  date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à Faire", 'True', "0", "10");
                }
                //CLOSE RELANCE LA
                $activity = findActivityByIdOP($idOP, 20);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                    $db->query("UPDATE wbcc_opportunity SET relanceLettreAcceptation=1 WHERE idOpportunity=$idOP");
                    $db->execute();
                }
            }

            if ($codeActivity == "37") {
                //Tache Faire CLOTURE OP
                // $activity = findActivityByIdOP($idOP, 23);
                // if ($activity) {
                // } else {
                //     createNewActivity($idOP, $opName, "518", "Compte WBCC", "5770501a-425d-4f50-b66a-016c2dbb2557", "$opName - En attente de Prise de RDV Travaux", "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "False", "0", "23");
                // }
            }

            if ($codeActivity == "31") {
                //Tache Faire CLOTURE OP
                // $activity = findActivityByIdOP($idOP, 23);
                // if ($activity) {
                // } else {
                //     createNewActivity($idOP, $opName, "518", "Compte WBCC", "5770501a-425d-4f50-b66a-016c2dbb2557", "$opName - En attente de Prise de RDV Travaux", "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "False", "0", "23");
                // }
            }

            //Créer tache "Gestion suite expertise"
            if ($codeActivity == "18") {
                //UPDATE RV
                $db->query("UPDATE wbcc_rendez_vous SET etatRV=1 WHERE idOpportunityF= $idOP AND typeRV ='EXPERTISEP' ");
                $db->execute();
                updateAgenda($idOP, "EXPERTISEP");

                $activity =  findActivityByIdOP($idOP, 43);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Gestion Suite Expertise", "", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "Tâche à Faire", 'False', '0', 43);
                }
            }

            if ($codeActivity == "30") {
                $regarding = "Programmer Recherche de Fuite";
                $activity = findActivityByIdOP($idOP, 29);
                if ($activity) {
                    $db->query("UPDATE wbcc_activity SET isCleared = 'True' WHERE idActivity = $activity->idActivity");
                    $db->execute();
                }

                $regarding = "Demande de Réparation de Fuite";
                $activity = findActivityByIdOP($idOP, 33);
                if ($activity) {
                    // $db->query("UPDATE wbcc_activity SET isCleared = 'False' WHERE idActivity = $activity->idActivity");
                    // $db->execute();
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,  $opName . '-' .  $regarding, "", date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), "Tâche à faire", "False", "0", 33);
                }

                $activity =  findActivityByIdOP($idOP, 46);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Traitement Retour Recherche Fuite", "", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "Tâche à Faire", 'False', '0', 46);
                }
            }

            if ($codeActivity == "23") {
                $regarding = "Faire RDV TRAVAUX";
                $activity = findActivityByIdOP($idOP, 45);
                if ($activity) {
                } else {
                    //EXPERT
                    if ($idAuteurRV != null && $idAuteurRV != "" && $idAuteurRV != "0") {
                        $db->query("SELECT * FROM wbcc_contact, wbcc_utilisateur WHERE idContact= idContactF AND idUtilisateur=$idAuteurRV LIMIT 1");
                        $auteurRV = $db->single();
                    }

                    if ($dateDebutRV != null && $dateDebutRV != "" && $dateFinRV != "") {
                        $dateRV = explode(' ',  $dateDebutRV)[0];
                        $heureDebut = explode(' ',  $dateDebutRV)[1];
                        $dateFin = explode(' ',  $dateFinRV)[0];
                        $heureFin = explode(' ',  $dateFinRV)[1];

                        createNewActivity($idOP, $opName, $auteurRV->idUtilisateur, $auteurRV->fullName, $auteurRV->numeroContact,  $opName . "-$regarding", "",  "$dateRV $heureDebut", "$dateFin $heureFin", "Rendez-vous", 'False', "1", "45", $adresse);
                    } else {
                        createNewActivity($idOP, $opName, $auteurRV->idUtilisateur, $auteurRV->fullName, $auteurRV->numeroContact,   $opName . "-$regarding", "",  $today, $today, "Rendez-vous", 'False', "1", "45", $adresse);
                    }
                }
            }

            if ($codeActivity == "45") {
                $activity = findActivityByIdOP($idOP, 22);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Relancer Cie pour le paiement du différé", "", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "Tâche à Faire", 'False', '0', 22);
                }
            }

            if ($codeActivity == "24") {
                $activity = findActivityByIdOP($idOP, 44);
                if ($activity) {
                } else {
                    createNewActivity($idOP, $opName, $ges->idUtilisateur, $ges->fullName, $ges->numeroContact,   $opName . "-Envoyer Constat DDE", "", date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "Tâche à Faire", 'False', '0', 44);
                }
            }
        }
    }

    $etape = getEtapeForOP($idOP);
    $db->query("UPDATE wbcc_opportunity SET etapeOp=:etapeOp  WHERE idOpportunity = $idOP");
    $db->bind("etapeOp", $etape, null);
    try {
        $db->execute();
    } catch (\Throwable $th) {
    }
    return $close;
}

function findActivityByIdOP($idOP, $codeActivity)
{
    $db = new Database();
    $db->query("SELECT * FROM wbcc_activity, wbcc_opportunity_activity WHERE idActivity=idActivityF AND codeActivity=$codeActivity AND idOpportunityF = $idOP LIMIT 1");
    $activity = $db->single();
    return $activity;
}

function findItemByColumn($nomTable, $col, $value)
{
    $db = new Database();
    $db->query("SELECT * FROM $nomTable WHERE $col = :numero");
    $db->bind("numero", $value, null);
    $data = $db->single();
    return $data;
}

//CREATE NEW ACTIVITY
function createNewActivity($idOP, $opName, $idAuteur, $auteur, $numeroAuteur, $regarding, $detail, $start, $end, $typeActivity, $isCleared, $publie, $code = null, $adresse = '', $idRealisedBy = '', $realisedBy = '', $idEnveloppe = '', $numEnveloppe = '', $idCheque = '', $idEncaissement = '')
{
    $rand = rand(1000, 9999);
    $db = new Database();
    $today =  date("Y-m-d H:i:s");
    $numeroActivity = date("YmdHis") . "$code$idOP" . $idAuteur . $rand;
    $db->query("INSERT INTO wbcc_activity (numeroActivity, details,regarding, startTime, endTime, createDate, editDate, source, organizer, organizerGuid, isDeleted, activityType, isMailSend, idUtilisateurF, isCleared, publie, location, opName, codeActivity,numEnveloppe,  idRealisedBy,realisedBy) VALUES (:numeroActivity, :details, :regarding, :startTime, :endTime, :createDate, :editDate, :source, :organizer, :organizerGuid, :isDeleted, :activityType, :isMailSend, :idUtilisateurF, :isCleared, :publie, :adresse, :opName , :codeActivity,:numEnveloppe, :idRealisedBy, :realisedBy)");
    $db->bind("numeroActivity", $numeroActivity, null);
    $db->bind("details", $detail, null);
    $db->bind("regarding", $regarding, null);
    $db->bind("startTime", $start, null);
    $db->bind("endTime", $end, null);
    $db->bind("createDate", $today, null);
    $db->bind("editDate", $today, null);
    $db->bind("source", "EXTRA", null);
    $db->bind("organizer", $auteur, null);
    $db->bind("organizerGuid", $numeroAuteur, null);
    $db->bind("isDeleted", "0", null);
    $db->bind("activityType", $typeActivity, null);
    $db->bind("isMailSend", "0", null);
    $db->bind("idUtilisateurF", $idAuteur, null);
    $db->bind("isCleared", $isCleared, null);
    $db->bind("publie", $publie, null);
    $db->bind("adresse", $adresse, null);
    $db->bind("opName", $opName, null);
    $db->bind("codeActivity", $code, null);
    $db->bind("numEnveloppe", $numEnveloppe, null);
    $db->bind("realisedBy", $realisedBy, null);
    $db->bind("idRealisedBy", $idRealisedBy != null && $idRealisedBy != '' ? $idRealisedBy : null, null);
    $db->execute();

    $activity = findItemByColumn("wbcc_activity", "numeroActivity", $numeroActivity);

    if ($idOP != null && $idOP != '' && $idOP != '0') {
        $db->query("INSERT INTO wbcc_opportunity_activity ( idActivityF, idOpportunityF) VALUES (:idActivityF, :idOpportunityF)");
        $db->bind("idActivityF", $activity->idActivity, null);
        $db->bind("idOpportunityF", $idOP, null);
        $db->execute();
    }
    if ($idEnveloppe != null && $idEnveloppe != '' && $idEnveloppe != '0') {
        $db->query("INSERT INTO wbcc_enveloppe_activity ( idActivityF, idEnveloppeF) VALUES (:idActivityF, :idEnveloppeF)");
        $db->bind("idActivityF", $activity->idActivity, null);
        $db->bind("idEnveloppeF", $idEnveloppe, null);
        $db->execute();
    }
    if ($idCheque != null && $idCheque != '' && $idCheque != '0') {
        $db->query("INSERT INTO wbcc_cheque_activity ( idActivityF, idChequeF) VALUES (:idActivityF, :idCheque)");
        $db->bind("idActivityF", $activity->idActivity, null);
        $db->bind("idCheque", $idCheque, null);
        $db->execute();
    }
    if ($idEncaissement != null && $idEncaissement != '' && $idEncaissement != '0') {
        $db->query("INSERT INTO wbcc_encaissement_activity ( idActivityF, idEncaissementF) VALUES (:idActivityF, :idEncaissement)");
        $db->bind("idActivityF", $activity->idActivity, null);
        $db->bind("idEncaissement", $idEncaissement, null);
        $db->execute();
    }
}

//CREATE NEW HISTORIQUE
function createHistorique($action, $auteur, $idAuteur, $idOP = '')
{
    $db = new Database();
    $db->query("INSERT INTO `wbcc_historique`(`action`, `nomComplet`, `dateAction`,  `idUtilisateurF`, idOpportunityF) VALUES (:action, :nomComplet, :dateAction, :idUtilisateurF, :idOpportunityF)");
    $db->bind("action",  $action, null);
    $db->bind("nomComplet", $auteur, null);
    $db->bind("idUtilisateurF", $idAuteur, null);
    $db->bind("dateAction", date("Y-m-d H:i:s"), null);
    $db->bind("idOpportunityF", $idOP == '' ? null : $idOP, null);
    $db->execute();
}

//CREATE NEW NOTE
function createNote($idOP = "", $idAuteur, $auteur, $noteText, $plainText, $publie, $codeActivity = "")
{
    $rand = rand(1000, 9999);
    $numeroNote = date("YmdHis") .  $idAuteur . "$idOP$codeActivity" . $rand;
    $db = new Database();
    $db->query("INSERT INTO wbcc_note (numeroNote, noteText, plainText, source, isPrivate, auteur, idUtilisateurF, publie) VALUES(:numeroNote, :noteText, :plainText, :source, :isPrivate, :auteur, :idUtilisateurF, :publie)");
    $db->bind("source", "EXTRA");
    $db->bind("auteur", $auteur);
    $db->bind("idUtilisateurF", $idAuteur);
    $db->bind("isPrivate", 0);
    $db->bind("numeroNote", $numeroNote);
    $db->bind("noteText",  $noteText);
    $db->bind("plainText",  $plainText);
    $db->bind("publie",  $publie);
    $db->execute();
    $idNote = findItemByColumn("wbcc_note", "numeroNote", $numeroNote)->idNote;
    if ($idOP != "") {
        $db->query("INSERT INTO wbcc_opportunity_note (idOpportunityF, idNoteF) VALUES($idOP,$idNote)");
        $db->execute();
    }
}

function freeAgendaByOPAndTypeRV($idOP, $type = "")
{
    $db = new Database();
    $date = date("Y-m-d");
    $db->query("DELETE FROM wbcc_evenement_agenda  WHERE dateEvenement > '$date' AND  idOpportunityF = $idOP AND type=:type ");
    $db->bind("type", $type, null);
    $db->execute();
}


function updateAgenda($idOP, $type = "")
{
    $db = new Database();
    $date = date("Y-m-d");
    $heureD = date("H:i");
    $heureF = date('H:i', strtotime($heureD . '+1 hour'));
    $db->query("SELECT *  FROM wbcc_evenement_agenda  WHERE dateEvenement > '$date' AND  idOpportunityF = $idOP AND type=:type LIMIT 1");
    $db->bind("type", $type, null);
    $event = $db->single();
    if ($event) {
        $db->query("UPDATE wbcc_evenement_agenda  SET dateEvenement = '$date', heureDebutEvenement='$heureD', heureFinEvenement='$heureF' WHERE  idEvenementAgenda=$event->idEvenementAgenda ");
        $db->execute();
    }
}

/******** ESPOIR ****** */
function jourEnChaine($numero)
{
    switch ($numero) {
        case 1:
            return "Lundi";
        case 2:
            return "Mardi";
        case 3:
            return "Mercredi";
        case 4:
            return "Jeudi";
        case 5:
            return "Vendredi";
        case 6:
            return "Samendi";
        case 7:
            return "Dimanche";
    }
}

// DEBUT NABILA

function convertMinutesToHours($minutes)
{
    $sign = ($minutes < 0) ? '-' : ''; // Détecter si c'est négatif
    $minutes = abs($minutes); // Utiliser la valeur absolue pour le calcul

    $hours = floor($minutes / 60); // Calculer les heures
    $remainingMinutes = $minutes % 60; // Calculer les minutes restantes

    // Construire le résultat en affichant seulement les parties pertinentes
    if ($hours > 0) {
        return $sign . $hours . ' heures ' . $remainingMinutes . ' minutes';
    } else {
        return $sign . $remainingMinutes . ' minutes';
    }
}

function getPeriodDates($selectedPeriod, $getParams)
{
    $todayDate = date('Y-m-d'); // Date d'aujourd'hui

    switch ($selectedPeriod) {
        case 'today':
            return [
                'startDate' => $todayDate,
                'endDate' => $todayDate,
                'previousStartDate' => date('Y-m-d', strtotime('-1 day', strtotime($todayDate))),
                'previousEndDate' => date('Y-m-d', strtotime('-1 day', strtotime($todayDate)))
            ];

        case 'custom':
            $startDate = isset($getParams['dateDebut1']) ? date('Y-m-d', strtotime($getParams['dateDebut1'])) : null;
            $endDate = isset($getParams['dateFin1']) ? date('Y-m-d', strtotime($getParams['dateFin1'])) : null;
            $previousStartDate = isset($getParams['dateDebut2']) ? date('Y-m-d', strtotime($getParams['dateDebut2'])) : null;
            $previousEndDate = isset($getParams['dateFin2']) ? date('Y-m-d', strtotime($getParams['dateFin2'])) : null;
            return compact('startDate', 'endDate', 'previousStartDate', 'previousEndDate');

        case 'semaine':
            return [
                'startDate' => date('Y-m-d', strtotime('monday this week')),
                'endDate' => date('Y-m-d', strtotime('sunday this week')),
                'previousStartDate' => date('Y-m-d', strtotime('monday last week')),
                'previousEndDate' => date('Y-m-d', strtotime('sunday last week'))
            ];

        case 'mois':
            $mois = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];

            // Dates du mois actuel
            $startDate = date('Y-m-01'); // Premier jour du mois actuel
            $endDate = date('Y-m-t'); // Dernier jour du mois actuel
            $currentMonth = date('n'); // Mois actuel
            $currentYear = date('Y'); // Année actuelle

            // Récupérer le nom du mois actuel
            $namemonth = $mois[$currentMonth] . ' ' . $currentYear;

            // Calcul des dates du mois précédent
            $previousMonth = $currentMonth - 1; // Mois précédent
            $previousYear = $currentYear; // Année du mois précédent

            if ($previousMonth < 1) { // Si on est en janvier
                $previousMonth = 12; // Décembre
                $previousYear--; // Année précédente
            }

            // Dates du mois précédent
            $previousStartDate = date('Y-m-01', mktime(0, 0, 0, $previousMonth, 1, $previousYear));
            $previousEndDate = date('Y-m-t', mktime(0, 0, 0, $previousMonth, 1, $previousYear));

            // Récupérer le nom du mois précédent
            $previousNamemonth = $mois[$previousMonth] . ' ' . $previousYear;

            return compact('startDate', 'endDate', 'previousStartDate', 'previousEndDate');

        case 'trimestre':
            $month = date('n');
            $trimesterStartMonth = (floor(($month - 1) / 3) * 3) + 1;
            $startDate = date('Y-' . str_pad($trimesterStartMonth, 2, '0', STR_PAD_LEFT) . '-01');
            $endDate = date('Y-' . str_pad($trimesterStartMonth + 2, 2, '0', STR_PAD_LEFT) . '-t');
            $previousTrimesterStartMonth = $trimesterStartMonth - 3;
            if ($previousTrimesterStartMonth < 1) {
                $previousTrimesterStartMonth += 12;
            }
            $previousStartDate = date('Y-' . str_pad($previousTrimesterStartMonth, 2, '0', STR_PAD_LEFT) . '-01');
            $previousEndDate = date('Y-' . str_pad($previousTrimesterStartMonth + 2, 2, '0', STR_PAD_LEFT) . '-t');
            return compact('startDate', 'endDate', 'previousStartDate', 'previousEndDate');

        case 'semestre':
            $month = date('n');
            $semesterStartMonth = ($month <= 6) ? 1 : 7;
            $startDate = date('Y-' . str_pad($semesterStartMonth, 2, '0', STR_PAD_LEFT) . '-01');
            $endDate = date('Y-' . str_pad($semesterStartMonth + 5, 2, '0', STR_PAD_LEFT) . '-t');
            $previousSemesterStartMonth = ($semesterStartMonth == 1) ? 7 : 1;
            $previousStartDate = date('Y-' . str_pad($previousSemesterStartMonth, 2, '0', STR_PAD_LEFT) . '-01');
            $previousEndDate = date('Y-' . str_pad($previousSemesterStartMonth + 5, 2, '0', STR_PAD_LEFT) . '-t');
            return compact('startDate', 'endDate', 'previousStartDate', 'previousEndDate');

        case 'annuel':
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
            $previousStartDate = date('Y-01-01', strtotime('-1 year'));
            $previousEndDate = date('Y-12-31', strtotime('-1 year'));
            return compact('startDate', 'endDate', 'previousStartDate', 'previousEndDate');

        default:
            return [];
    }
}

function filterPointages($pointages, $startDate, $endDate, &$retardTotal, &$absenceTotal)
{
    return array_filter($pointages, function ($pointage) use ($startDate, $endDate, &$retardTotal, &$absenceTotal) {
        $pointageDate = $pointage->datePointage;
        if ($pointageDate >= $startDate && $pointageDate <= $endDate) {
            $retardTotal += $pointage->nbMinuteRetard;
            $absenceTotal += $pointage->absent ? 1 : 0; // 1 if absent, otherwise 0
            return true; // Keep this pointage
        }
        return false; // Discard this pointage
    });
}

function filterPointagesByEmployeAndPeriod($selectedEmploye, $startDate, $endDate, $pointages, &$retardTotal, &$absenceTotal)
{
    foreach ($pointages as $pointage) {
        // Vérifier si le pointage correspond à l'employé sélectionné et est dans la plage de dates
        if ($pointage->fullName == $selectedEmploye && $pointage->datePointage >= $startDate && $pointage->datePointage <= $endDate) {
            // Calculer le retard et l'absence pour l'employé
            $retardTotal += $pointage->nbMinuteRetard; // Assurez-vous que la propriété "retard" existe dans l'objet pointage
            $absenceTotal += $pointage->absent ? 1 : 0;; // Assurez-vous que la propriété "absence" existe dans l'objet pointage
        }
    }
}

function formatPeriod($startDate, $endDate, $selectedPeriod, $isPrevious = false)
{
    $currentMonth = date('n');
    $currentYear = date('Y');

    switch ($selectedPeriod) {
        case 'today':
            return date('d-m-Y', strtotime($startDate));

        case 'semaine':
            return 'Semaine du ' . date('d-m-Y', strtotime($startDate)) . ' au ' . date('d-m-Y', strtotime($endDate));

        case 'mois':
            // Renvoie le mois actuel ou le mois précédent
            if ($isPrevious) {
                // Gestion du mois précédent
                $previousMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
                $previousYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
                return moisEnChaine($previousMonth) . ' ' . $previousYear;
            } else {
                return moisEnChaine($currentMonth) . ' ' . $currentYear;
            }

        case 'trimestre':
            // Calculer le trimestre actuel et précédent
            $currentTrimester = floor(($currentMonth - 1) / 3) + 1;
            if ($isPrevious) {
                $previousTrimester = $currentTrimester - 1;
                if ($previousTrimester < 1) {
                    $previousTrimester = 4; // Quatrième trimestre de l'année précédente
                    $previousYear = $currentYear - 1;
                } else {
                    $previousYear = $currentYear;
                }
                return 'Trimestre ' . $previousTrimester . ' ' . $previousYear;
            } else {
                return 'Trimestre ' . $currentTrimester . ' ' . $currentYear;
            }

        case 'semestre':
            // Calculer le semestre actuel et précédent
            $currentSemester = floor(($currentMonth - 1) / 6) + 1;
            if ($isPrevious) {
                $previousSemester = $currentSemester - 1;
                if ($previousSemester < 1) {
                    $previousSemester = 2; // Deuxième semestre de l'année précédente
                    $previousYear = $currentYear - 1;
                } else {
                    $previousYear = $currentYear;
                }
                return 'Semestre ' . $previousSemester . ' ' . $previousYear;
            } else {
                return 'Semestre ' . $currentSemester . ' ' . $currentYear;
            }

        case 'annuel':
            // Renvoie l'année actuelle ou précédente en fonction de $isPrevious
            return $isPrevious ? date('Y', strtotime('-1 year')) : date('Y');

        case 'custom':
            return 'Du ' . date('d-m-Y', strtotime($startDate)) . ' au ' . date('d-m-Y', strtotime($endDate));

        default:
            return 'Période inconnue';
    }
}

function date_quarter($month)
{
    if ($month <= 3) return 1;
    if ($month <= 6) return 2;
    if ($month <= 9) return 3;

    return 4;
}


// FIN NABILA