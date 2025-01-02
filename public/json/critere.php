<?php
header('Access-Control-Allow-Origin: *');
require_once "../../app/config/config.php";
require_once "../../app/libraries/Utils.php";
require_once "../../app/libraries/Database.php";

if (isset($_GET['action'])) {
    $db = new Database();

    $action = $_GET['action'];

    //CONDITION
    if ($action == "saveCondition") {
        $_POST = json_decode(file_get_contents('php://input'), true);
        extract($_POST);
        //SAVE TYPE CRITERE
        if ($idTypeConditionF == "Autre") {
            //SEARCH IF EXIST
            $typeCondition = findItemByColumn("wbcc_type_condition", "libelleTypeCondition", $autreTypeCondition);
            if ($typeCondition) {
                $idTypeConditionF = $typeCondition->idTypeCondition;
            } else {
                //INSERT
                $numeroTypeCondition = "TC" . date("dmYHis") . $idAuteur;
                $db->query("INSERT INTO wbcc_type_condition (numeroTypeCondition, libelleTypeCondition, idAuteur) VALUES (:numeroTypeCondition, :autreTypeCondition, :idAuteur)");
                $db->bind("numeroTypeCondition", $numeroTypeCondition, null);
                $db->bind("autreTypeCondition", $autreTypeCondition, null);
                $db->bind("idAuteur", $idAuteur, null);
                if ($db->execute()) {
                    $typeCondition = findItemByColumn("wbcc_type_condition", "numeroTypeCondition", $numeroTypeCondition);
                    if ($typeCondition) {
                        $idTypeConditionF = $typeCondition->idTypeCondition;
                    } else {
                        echo json_encode("0");
                    }
                } else {
                    echo json_encode("0");
                }
            }
        }
        if ($idTypeConditionF != "" && $idTypeConditionF != null && $idTypeConditionF != "0") {
            $numeroCondition = "C" . date("dmYHis") . $idAuteur;
            $condition = false;
            if ($idCondition != "" && $idCondition != null && $idCondition != "0") {
                //UPDATE
                $condition = findItemByColumn("wbcc_condition", "idCondition", $idCondition);
                $db->query("UPDATE SET wbcc_condition  idTypeConditionF=:idTypeConditionF, operateur=:operateur, signeOperateur=:signeOperateur, valeur=:valeur, editDate=:editDate, =:idAuteur WHERE idCondition=$idCondition");
            } else {
                //SEARCH IF CRITERE
                $db->query("SELECT * FROM wbcc_condition c WHERE idTypeConditionF = :idTypeConditionF AND signeOperateur=:signeOperateur AND valeur=:valeur LIMIT 1");
                $db->bind("idTypeConditionF", $idTypeConditionF, null);
                $db->bind("signeOperateur", "$signeOperateur", null);
                $db->bind("valeur", $valeurCondition, null);
                $condition = $db->single();
                if ($condition) {
                    $idCondition = $condition->idCondition;
                    //UPDATE
                    $db->query("UPDATE  wbcc_condition SET  idTypeConditionF=:idTypeConditionF, operateur=:operateur, signeOperateur=:signeOperateur, valeur=:valeur, editDate=:editDate, idAuteur=:idAuteur WHERE idCondition=$idCondition");
                } else {
                    //INSERT
                    $db->query("INSERT INTO wbcc_condition (numeroCondition, idTypeConditionF, operateur, signeOperateur, valeur, editDate, idAuteur) VALUES (:numeroCondition, :idTypeConditionF, :operateur, :signeOperateur, :valeur, :editDate, :idAuteur)");
                    $db->bind("numeroCondition", $numeroCondition, null);
                }
            }
            $db->bind("idTypeConditionF", $idTypeConditionF, null);
            $db->bind("operateur", $operateur, null);
            $db->bind("signeOperateur", "$signeOperateur", null);
            $db->bind("valeur", $valeurCondition, null);
            $db->bind("editDate", date("Y-m-d H:i:s"), null);
            $db->bind("idAuteur", $idAuteur, null);
            if ($db->execute()) {
                if ($condition) {
                } else {
                    $condition = findItemByColumn("wbcc_condition", "numeroCondition", $numeroCondition);
                }
                //LINK CRITERE - CONDITION
                if ($idCritere != null && $idCritere != "" && $idCritere != "0") {
                    $db->query("SELECT * FROM wbcc_condition_critere WHERE idConditionF = $condition->idCondition and idCritereF = $idCritere LIMIT 1");
                    $cs = $db->single();
                    if ($cs) {
                    } else {
                        $db->query("INSERT INTO wbcc_condition_critere (idConditionF, idCritereF) VALUES ($condition->idCondition, $idCritere)");
                        $db->execute();
                    }
                }
                echo json_encode("1");
            } else {
                echo json_encode("0");
            }
        } else {
            echo json_encode("0");
        }
    }

    if ($action == "deleteConditionCritere") {
        $_POST = json_decode(file_get_contents('php://input'), true);
        extract($_POST);
        $db->query("DELETE FROM  wbcc_condition_critere WHERE idCritereF=$idCritere AND idConditionF=$idCondition ");
        if ($db->execute()) {
            echo json_encode("1");
        } else {
            echo json_encode("0");
        }
    }

    //CRITERE
    if ($action == "find") {
        $id = $_GET['id'];
        $db->query("SELECT * FROM wbcc_type_critere tc, wbcc_critere c WHERE tc.idTypeCritere= c.idTypeCritereF AND c.idCritere = $id");
        $data = $db->single();
        echo json_encode($data);
    }

    if ($action == "saveCritere") {
        $_POST = json_decode(file_get_contents('php://input'), true);
        extract($_POST);
        $numeroCritere = "C" . date("dmYHis") . $idAuteur;
        $critere = false;
        if ($idCritere != "" && $idCritere != null && $idCritere != "0") {
            //UPDATE
            $critere = findItemByColumn("wbcc_critere", "idCritere", $idCritere);
            $db->query("UPDATE  wbcc_critere SET valeurCritere=:valeurCritere, typeValeurCritere=:typeValeurCritere, editDate=:editDate, idAuteur=:idAuteur WHERE idCritere=$idCritere");
        } else {
            // SEARCH IF CRITERE
            $db->query("SELECT * FROM wbcc_critere c WHERE valeurCritere = :valeurCritere AND typeValeurCritere=:typeValeurCritere LIMIT 1");
            $db->bind("valeurCritere", $valeurCritere, null);
            $db->bind("typeValeurCritere", $typeValeurCritere, null);
            $critere = $db->single();
            if ($critere) {
                $idCritere = $critere->idCritere;
                //UPDATE
                $db->query("UPDATE  wbcc_critere SET valeurCritere=:valeurCritere, typeValeurCritere=:typeValeurCritere, editDate=:editDate, idAuteur=:idAuteur WHERE idCritere=$idCritere");
            } else {
                //INSERT
                $db->query("INSERT INTO wbcc_critere (numeroCritere, valeurCritere, typeValeurCritere, editDate, idAuteur) VALUES (:numeroCritere, :valeurCritere, :typeValeurCritere, :editDate, :idAuteur)");
                $db->bind("numeroCritere", $numeroCritere, null);
            }
        }
        $db->bind("valeurCritere", $valeurCritere, null);
        $db->bind("typeValeurCritere", $typeValeurCritere, null);
        $db->bind("editDate", date("Y-m-d H:i:s"), null);
        $db->bind("idAuteur", $idAuteur, null);
        if ($db->execute()) {
            if ($critere) {
            } else {
                $critere = findItemByColumn("wbcc_critere", "numeroCritere", $numeroCritere);
            }
            //LINK SUBVENTION - CRITERE
            if (isset($idSubvention) && $idSubvention != null && $idSubvention != "" && $idSubvention != "0") {
                $db->query("SELECT * FROM wbcc_critere_subvention WHERE idCritereF = $critere->idCritere and idSubventionF = $idSubvention LIMIT 1");
                $cs = $db->single();
                if ($cs) {
                } else {
                    $db->query("INSERT INTO wbcc_critere_subvention (idCritereF, idSubventionF) VALUES ($critere->idCritere, $idSubvention)");
                    $db->execute();
                }
            }
            echo json_encode("1");
        } else {
            echo json_encode("0");
        }
    }


    if ($action == "deleteCritereSubvention") {
        $_POST = json_decode(file_get_contents('php://input'), true);
        extract($_POST);
        $db->query("DELETE FROM  wbcc_critere_subvention WHERE idCritereF=$idCritere AND idSubventionF=$idSubvention ");
        if ($db->execute()) {
            echo json_encode("1");
        } else {
            echo json_encode("0");
        }
    }
}
