<?php

class Immeuble extends Model
{

    public function saveImmeubleComplet(
        $idImmeuble,
        $nomImmeuble,
        $adresse,
        $codePostal,
        $ville,
        $departement,
        $region,
        $typeImmeuble,
        $nomDO,
        $idDO,
        $nomGardien,
        $idGardien,
        $nomChefSecteur,
        $idChefSecteur,
        $nomCompagnieAssurance,
        $idCompagnieAssurance,
        $nomCourtier,
        $idCourtier,
        $numPolice,
        $dateEffetContrat,
        $dateEcheanceContrat,
        $commentaire,
        $digicode1 = '',
        $digicode2 = '',
        $interphone = '',
        $nbBatiment = '',
        $libelleBatiment = '',
        $photo = '',
        $idUser
    ) {

        $codeWBCC = "";
        if ($idImmeuble != null && $idImmeuble != "" && $idImmeuble != "0") {
            $this->db->query("UPDATE wbcc_immeuble  SET  typeImmeuble=:typeImmeuble, adresse=:adresse, codePostal=:codePostal, ville=:ville, nomDO=:nomDO, idDO=:idDO, editDate=:editDate,  nomImmeubleSyndic=:nomImmeubleSyndic, departement=:departement, region=:region, pays=:pays, numPolice=:numPolice, dateEffetContrat=:dateEffetContrat, dateEcheanceContrat=:dateEcheanceContrat, commentaire=:commentaire, nomGardien=:nomGardien, nomCourtier=:nomCourtier, nomCompagnieAssurance=:nomCompagnieAssurance, idChefSecteur=:idChefSecteur, nomChefSecteur=:nomChefSecteur, idGardien=:idGardien, idCourtier=:idCourtier, idCompagnieAssurance=:idCompagnieAssurance, idUserF=:idUserF WHERE idImmeuble=$idImmeuble");
        } else {
            $numeroImmeuble = "IMM" . date("Y-m-d H:i:s") . $idUser;
            $this->db->query("INSERT INTO wbcc_immeuble (numeroImmeuble, codeImmeuble, typeImmeuble, adresse, codePostal, ville, nomDO, idDO, createDate, editDate, codeWBCC, nomImmeubleSyndic,  departement, region, pays, numPolice, dateEffetContrat, dateEcheanceContrat, commentaire, nomGardien, nomCourtier, nomCompagnieAssurance, idChefSecteur, nomChefSecteur, idGardien, idCourtier, idCompagnieAssurance,idUserF) 
           VALUES (:numeroImmeuble, :codeImmeuble, :typeImmeuble, :adresse, :codePostal, :ville, :nomDO, :idDO, :createDate, :editDate, :codeImmeuble, :nomImmeubleSyndic, :departement, :region, :pays, :numPolice, :dateEffetContrat, :dateEcheanceContrat, :commentaire, :nomGardien, :nomCourtier, :nomCompagnieAssurance, :idChefSecteur, :nomChefSecteur, :idGardien, :idCourtier, :idCompagnieAssurance,:idUserF)");
            $this->db->bind("createDate", date("Y-m-d H:i:s"), null);
            $this->db->bind("numeroImmeuble", $numeroImmeuble, null);
            $this->db->bind("codeImmeuble", $codeWBCC, null);
        }
        $this->db->bind("typeImmeuble", $typeImmeuble, null);
        $this->db->bind("adresse", $adresse, null);
        $this->db->bind("codePostal", $codePostal, null);
        $this->db->bind("ville", $ville, null);
        $this->db->bind("nomDO", $nomDO, null);
        $this->db->bind("idDO", $idDO  == "0" || $idDO == "" ? null : $idDO, null);
        $this->db->bind("editDate", date("Y-m-d H:i:s"), null);
        $this->db->bind("nomImmeubleSyndic", $nomImmeuble, null);
        $this->db->bind("departement", $departement, null);
        $this->db->bind("region", $region, null);
        $this->db->bind("pays", "FRANCE", null);
        $this->db->bind("numPolice", $numPolice, null);
        $this->db->bind("dateEffetContrat", $dateEffetContrat, null);
        $this->db->bind("dateEcheanceContrat", $dateEcheanceContrat, null);
        $this->db->bind("commentaire", $commentaire, null);
        $this->db->bind("nomGardien", $nomGardien, null);
        $this->db->bind("nomCourtier", $nomCourtier, null);
        $this->db->bind("nomCompagnieAssurance", $nomCompagnieAssurance, null);
        $this->db->bind("idChefSecteur", $idChefSecteur == "0" || $idChefSecteur == "" ? null : $idChefSecteur, null);
        $this->db->bind("nomChefSecteur", $nomChefSecteur, null);
        $this->db->bind("idGardien", $idGardien == "0" || $idGardien == "" ? null : $idGardien, null);
        $this->db->bind("idCourtier", $idCourtier == "0" || $idCourtier == "" ? null : $idCourtier, null);
        $this->db->bind("idCompagnieAssurance", $idCompagnieAssurance == "0" || $idCompagnieAssurance == "" ? null : $idCompagnieAssurance, null);
        $this->db->bind("idUserF", $idUser, null);
        if ($this->db->execute()) {
            if ($idImmeuble != null && $idImmeuble != "" && $idImmeuble != "0") {
                $immeuble = $this->findImmeubleById($idImmeuble);
                $idImmeuble = $immeuble->idImmeuble;
                //LINK OP FOR IMMEUBLE WITH INFOS IMMEUBLES
                $this->db->query("SELECT * FROM wbcc_opportunity WHERE idImmeuble = $idImmeuble AND status='Open' ");
                $ops =  $this->db->resultSet();
                foreach ($ops as $key => $op) {
                    //GET OLD REFERENT / GARDIEN / COMPAGNIE / COURTIER / GESTIONNAIRE DO
                    $idDOOld = $op->idGestionnaireAppImm;
                    $idReferentOld = $op->idReferentDo;
                    $idCieOld = $op->idComMRIF;
                    $idCouOld = $op->idCouMRIF;


                    if ($idGardien != null && $idGardien != "" && $idGardien != "0") {
                        //update Contact
                        $this->db->query("UPDATE wbcc_contact  SET  statutContact=:statut WHERE idContact=$idGardien");
                        $this->db->bind("statut", "GARDIEN", null);
                        $this->db->execute();

                        $this->db->query("SELECT * FROM wbcc_contact_opportunity WHERE idContactF=:idGardien AND idOpportunityF=:idOpportunity LIMIT 1");
                        $this->db->bind("idGardien", $idGardien, null);
                        $this->db->bind("idOpportunity", $op->idOpportunity, null);
                        $gardien = $this->db->single();
                        if ($gardien) {
                        } else {
                            $this->db->query("INSERT INTO wbcc_contact_opportunity (idContactF, idOpportunityF) VALUES ( :idContactF, :idOpportunityF)");
                            $this->db->bind("idContactF", $idGardien, null);
                            $this->db->bind("idOpportunityF", $op->idOpportunity, null);
                            $this->db->execute();
                        }
                    }

                    if ($idChefSecteur != null && $idChefSecteur != "" && $idChefSecteur != "0") {
                        //update Contact
                        $this->db->query("UPDATE wbcc_contact  SET  statutContact=:statut WHERE idContact=$idChefSecteur");
                        $this->db->bind("statut", "CHEF DE SECTEUR", null);
                        $this->db->execute();

                        $this->db->query("SELECT * FROM wbcc_contact_opportunity WHERE idContactF=:idChefSecteur AND idOpportunityF=:idOpportunity LIMIT 1");
                        $this->db->bind("idChefSecteur", $idChefSecteur, null);
                        $this->db->bind("idOpportunity", $op->idOpportunity, null);
                        $gardien = $this->db->single();
                        if ($gardien) {
                        } else {
                            $this->db->query("INSERT INTO wbcc_contact_opportunity (idContactF, idOpportunityF) VALUES ( :idContactF, :idOpportunityF)");
                            $this->db->bind("idContactF", $idChefSecteur, null);
                            $this->db->bind("idOpportunityF", $op->idOpportunity, null);
                            $this->db->execute();
                        }
                    }

                    //UPDATE OP
                    $req = "";
                    if ($op->typeSinistre == "Partie commune exclusive") {
                        $req = " , policeMRI = :policeMRI, denominationComMRI=:denominationComMRI, idComMRIF=:idComMRIF, idCouMRIF=:idCouMRIF, denominationCouMRI=:denominationCouMRI  ";
                    }
                    $this->db->query("UPDATE wbcc_opportunity SET   idGestionnaireAppImm=:idGestionnaireAppImm, nomGestionnaireAppImm = :nomGestionnaireAppImm, 	idReferentDo=:idReferentDo, nomReferentDo=:nomReferentDo $req  WHERE idOpportunity = $op->idOpportunity");
                    $this->db->bind("idGestionnaireAppImm", $idDO != null && $idDO != "" && $idDO != "0" ? $idDO : null, null);
                    $this->db->bind("nomGestionnaireAppImm", $nomDO, null);
                    $this->db->bind("nomReferentDo", $nomChefSecteur, null);
                    $this->db->bind("idReferentDo", $idChefSecteur ==  "" || $idChefSecteur == "0" ? null : $idChefSecteur, null);

                    if ($op->typeSinistre == "Partie commune exclusive") {
                        $this->db->bind("policeMRI", $numPolice, null);
                        $this->db->bind("denominationComMRI", $nomCompagnieAssurance, null);
                        $this->db->bind("idComMRIF", $idCompagnieAssurance ==  "" || $idCompagnieAssurance == "0" ? null : $idCompagnieAssurance, null);
                        $this->db->bind("idCouMRIF", $idCourtier ==  "" || $idCourtier == "0" ? null : $idCourtier, null);
                        $this->db->bind("denominationCouMRI", $nomCourtier, null);
                    }
                    if ($this->db->execute()) {
                        if ($idDO != null && $idDO != "" && $idDO != "0") {
                            $this->db->query("SELECT * FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity LIMIT 1");
                            $this->db->bind("idCompanyF", $idDO, null);
                            $this->db->bind("idOpportunity", $op->idOpportunity, null);
                            $gardien = $this->db->single();
                            if ($gardien) {
                            } else {
                                $this->db->query("INSERT INTO wbcc_company_opportunity (idCompanyF, idOpportunityF) VALUES ( :idCompanyF, :idOpportunityF)");
                                $this->db->bind("idCompanyF", $idDO, null);
                                $this->db->bind("idOpportunityF", $op->idOpportunity, null);
                                $this->db->execute();
                            }
                        }

                        // IF traitemnt gardien chef-secteur

                        if ($op->typeSinistre == "Partie commune exclusive") {
                            if ($idCompagnieAssurance != null && $idCompagnieAssurance != "" && $idCompagnieAssurance != "0") {
                                $this->db->query("SELECT * FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity LIMIT 1");
                                $this->db->bind("idCompanyF", $idCompagnieAssurance, null);
                                $this->db->bind("idOpportunity", $op->idOpportunity, null);
                                $gardien = $this->db->single();
                                if ($gardien) {
                                } else {
                                    $this->db->query("INSERT INTO wbcc_company_opportunity (idCompanyF, idOpportunityF) VALUES ( :idCompanyF, :idOpportunityF)");
                                    $this->db->bind("idCompanyF", $idCompagnieAssurance, null);
                                    $this->db->bind("idOpportunityF", $op->idOpportunity, null);
                                    $this->db->execute();
                                }
                            }

                            if ($idCourtier != null && $idCourtier != "" && $idCourtier != "0") {
                                $this->db->query("SELECT * FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity LIMIT 1");
                                $this->db->bind("idCompanyF", $idCourtier, null);
                                $this->db->bind("idOpportunity", $op->idOpportunity, null);
                                $gardien = $this->db->single();
                                if ($gardien) {
                                } else {
                                    $this->db->query("INSERT INTO wbcc_company_opportunity (idCompanyF, idOpportunityF) VALUES ( :idCompanyF, :idOpportunityF)");
                                    $this->db->bind("idCompanyF", $idCourtier, null);
                                    $this->db->bind("idOpportunityF", $op->idOpportunity, null);
                                    $this->db->execute();
                                }
                            }
                        }

                        //GET OLD REFERENT / GARDIEN / COMPAGNIE / COURTIER / GESTIONNAIRE DO
                        if ($idDOOld != null && $idDOOld != "" && $idDOOld != "0" && $idDOOld != $idDO) {
                            $this->db->query("DELETE FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity");
                            $this->db->bind("idCompanyF", $idDOOld, null);
                            $this->db->bind("idOpportunity", $op->idOpportunity, null);
                            $this->db->execute();
                        }

                        if ($idCieOld != null && $idCieOld != "" && $idCieOld != "0" && $idCieOld != $idCompagnieAssurance) {
                            $this->db->query("DELETE FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity");
                            $this->db->bind("idCompanyF", $idCieOld, null);
                            $this->db->bind("idOpportunity", $op->idOpportunity, null);
                            $this->db->execute();
                        }

                        if ($idCouOld != null && $idCouOld != "" && $idCouOld != "0" && $idCouOld != $idCourtier) {
                            $this->db->query("DELETE FROM wbcc_company_opportunity WHERE idCompanyF=:idCompanyF AND idOpportunityF=:idOpportunity");
                            $this->db->bind("idCompanyF", $idCouOld, null);
                            $this->db->bind("idOpportunity", $op->idOpportunity, null);
                            $this->db->execute();
                        }

                        if ($idReferentOld != null && $idReferentOld != "" && $idReferentOld != "0" && $idReferentOld != $idChefSecteur) {
                            $this->db->query("DELETE FROM wbcc_contact_opportunity WHERE idContactF=:idContactF AND idOpportunityF=:idOpportunity");
                            $this->db->bind("idContactF", $idReferentOld, null);
                            $this->db->bind("idOpportunity", $op->idOpportunity, null);
                            $this->db->execute();
                        }
                    }
                }
            } else {
                $immeuble = $this->findByNumero($numeroImmeuble);
            }
            $this->db->query("UPDATE wbcc_appartement SET nomImmeubleSyndic = :nomImmeubleSyndic, adresse = :adresse1, codePostal = :codePostal, ville = :ville, departement = :departement, region = :region WHERE idImmeubleF  =  $immeuble->idImmeuble");
            $this->db->bind("nomImmeubleSyndic", $nomImmeuble, null);
            $this->db->bind("adresse1", $adresse, null);
            $this->db->bind("codePostal", $codePostal, null);
            $this->db->bind("ville", $ville, null);
            $this->db->bind("departement", $departement, null);
            $this->db->bind("region", $region, null);
            $this->db->execute();
            //UPDATE INFOS ASSURANCES FOR IMMEUBLE HLM
            if ($typeImmeuble == "HLM") {
                $this->db->query("SELECT * FROM wbcc_immeuble WHERE idDO = $idDO OR nomDO=:nomDO ");
                $this->db->bind("nomDO", $nomDO, null);
                $ims =  $this->db->resultSet();
                foreach ($ims as $key => $im) {
                    $this->db->query("UPDATE wbcc_immeuble SET numPolice = :numPolice, dateEffetContrat=:dateEffetContrat,
                        dateEcheanceContrat = :dateEcheanceContrat, nomCompagnieAssurance=:nomCompagnieAssurance, nomCourtier=:nomCourtier,
                        idCourtier = :idCourtier, idCompagnieAssurance=:idCompagnieAssurance WHERE idImmeuble = $im->idImmeuble");
                    $this->db->bind("numPolice", $numPolice, null);
                    $this->db->bind("dateEffetContrat", $dateEffetContrat, null);
                    $this->db->bind("dateEcheanceContrat", $dateEcheanceContrat, null);
                    $this->db->bind("nomCompagnieAssurance", $nomCompagnieAssurance, null);
                    $this->db->bind("nomCourtier", $nomCourtier, null);
                    $this->db->bind("idCourtier", $idCourtier == "" || $idCourtier == "0" ? null : $idCourtier, null);
                    $this->db->bind("idCompagnieAssurance", $idCompagnieAssurance ==  "" || $idCompagnieAssurance == "0" ? null : $idCompagnieAssurance, null);
                    $this->db->execute();
                }
            }

            return $immeuble;
        } else {
            return "0";
        }
    }

    public function  findImmeubleById($id)
    {
        $this->db->query("SELECT * FROM wbcc_immeuble
        WHERE idImmeuble = $id");
        return $this->db->single();
    }
    public function  getAllImmeubles()
    {
        $this->db->query("SELECT * FROM wbcc_immeuble ORDER BY idImmeuble DESC ");
        return $this->db->resultSet();
    }

    public function findImmeubleByAdresse($adresse)
    {
        $this->db->query("SELECT * FROM wbcc_immeuble  WHERE lower(adresse)=lower('$adresse') LIMIT 1");
        return $this->db->single();
    }

    public function  getImmeubleByCompany($id)
    {
        $this->db->query("SELECT * FROM wbcc_immeuble, wbcc_company_immeuble 
        WHERE idImmeuble = idImmeubleF
        AND idCompanyF = $id");
        return $this->db->resultSet();
    }

    public function  getImmeubleByOpportunity($id)
    {
        $this->db->query("SELECT * FROM wbcc_immeuble, wbcc_opportunity_immeuble 
        WHERE idImmeuble = idImmeubleF
        AND idOpportunityF = $id");
        return $this->db->resultSet();
    }

    public function addImmeuble(
        $numero,
        $nomImmeuble,
        $adresse1,
        $codePostal,
        $ville,
        $departement,
        $region,
        $digicode1,
        $digicode2,
        $interphone,
        $nbBatiment,
        $libelleBatiment
    ) {
        $this->db->query("
           INSERT INTO wbcc_immeuble 
           (numeroImmeuble, nomImmeubleSyndic, adresse, codePostal, 
           ville, departement, region, digicode1, digicode2, nomInterphone, nbreBatiment, 
           libelleBatiment) 
           VALUES ('$numero','$nomImmeuble', '$adresse1', 
            '$codePostal', '$ville', '$departement', '$region', '$digicode1', '$digicode2',
            '$interphone', '$nbBatiment', '$libelleBatiment'
           )");
        if ($this->db->execute()) {
            $immeuble = $this->findByNumero($numero);
            return $immeuble->idImmeuble;
        } else {
            return "0";
        }
    }

    public function updateImmeuble(
        $idImmeuble,
        $nomImmeuble,
        $adresse1,
        $codePostal,
        $ville,
        $departement,
        $region,
        $digicode1,
        $digicode2,
        $interphone,
        $nbBatiment,
        $libelleBatiment
    ) {
        $this->db->query("
            UPDATE wbcc_immeuble SET nomImmeubleSyndic = :nomImmeuble, adresse=:adresse1,
            codePostal = :codePostal, ville=:ville, departement=:departement,
            region = :region, digicode1=:digicode1, digicode2=:digicode2, 
            nomInterphone = :interphone, nbreBatiment=:nbBatiment, libelleBatiment=:libelleBatiment, editDate=:editDate
            WHERE idImmeuble = :idImmeuble");
        $this->db->bind("nomImmeuble", $nomImmeuble, null);
        $this->db->bind("adresse1", $adresse1, null);
        $this->db->bind("codePostal", $codePostal, null);
        $this->db->bind("ville", $ville, null);
        $this->db->bind("departement", $departement, null);
        $this->db->bind("region", $region, null);
        $this->db->bind("digicode1", $digicode1, null);
        $this->db->bind("digicode2", $digicode2, null);
        $this->db->bind("interphone", $interphone, null);
        $this->db->bind("nbBatiment", $nbBatiment, null);
        $this->db->bind("libelleBatiment", $libelleBatiment, null);
        $this->db->bind("idImmeuble", $idImmeuble, null);
        $this->db->bind("editDate", date('Y-m-d H:i:s'), null);
        $this->db->execute();
    }

    public function deleteContact($id)
    {
        $this->db->query("
            UPDATE wbcc_contact SET etatContact = 0
            WHERE idContact = $id");
        $this->db->execute();
    }

    public function findByNumero($numero)
    {
        $this->db->query("SELECT * FROM wbcc_immeuble WHERE numeroImmeuble = $numero");
        if ($this->db->single()) {
            return $this->db->single();
        } else {
            return null;
        }
    }


    public function insertCompanyImmeuble($idCompany, $idImmeuble)
    {
        $this->db->query("
           INSERT INTO wbcc_company_immeuble 
           (idCompanyF, idImmeubleF) 
           VALUES ($idCompany,$idImmeuble)");
        $this->db->execute();
    }

    public function insertOpportunityImmeuble($idOpp, $idImmeuble)
    {
        //UPDATE OPPORTUNITY
        $this->db->query("UPDATE wbcc_opportunity SET idImmeuble = :idImmeuble WHERE idOpportunity = :idOpp");
        $this->db->bind("idImmeuble", $idImmeuble, null);
        $this->db->bind("idOpp", $idOpp, null);
        $this->db->execute();

        $this->db->query("DELETE FROM wbcc_opportunity_immeuble WHERE  idOpportunityF = :idOpp");
        $this->db->bind("idOpp", $idOpp, null);
        $this->db->execute();

        $this->db->query("INSERT INTO wbcc_opportunity_immeuble  (idOpportunityF, idImmeubleF) VALUES (:idOpp,:idImmeuble)");
        $this->db->bind("idImmeuble", $idImmeuble, null);
        $this->db->bind("idOpp", $idOpp, null);
        $this->db->execute();
    }

    public function deleteImmeubleToOpportunity($id, $idOp)
    {
        $this->db->query("
            DELETE FROM wbcc_opportunity_immeuble
            WHERE idImmeubleF =:id AND idOpportunityF =:idOp");

        $this->db->bind("idOp", $idOp, null);
        $this->db->bind("id", $id, null);
        $this->db->execute();
    }
}
