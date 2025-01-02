<?php
header('Access-Control-Allow-Origin: *');

require_once "../../app/config/config.php";
require_once "../../app/libraries/Database.php";

$db = new Database();

if (isset($_GET['action'])) {

    $action = $_GET['action'];
    if ($action == 'saveDocumentRequisSubvention') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $ok = 1;
        foreach ($_POST as $key => $doc) {
            extract($doc);
            $db->query("SELECT * FROM wbcc_document_requis_subvention WHERE idDocumentRequisF =:idDocumentRequisF AND idSubventionF =:idSubventionF");
            $db->bind("idDocumentRequisF", $idDocumentRequis, null);
            $db->bind("idSubventionF", $idSubvention, null);
            if ($db->single() == false) {
                $db->query("INSERT INTO wbcc_document_requis_subvention (idDocumentRequisF, idSubventionF, etatDocumentRequisSubvention) VALUES ( :idDocumentRequisF, :idSubventionF, :etat)");
                $db->bind("idDocumentRequisF", $idDocumentRequis, null);
                $db->bind("idSubventionF", $idSubvention, null);
                $db->bind("etat", $etat, null);
                if ($db->execute()) {
                } else {
                    $ok = 0;
                }
            }
        }
        echo json_encode("$ok");
    }

    if ($action == "deleteDocumentSubvention") {
        $_POST = json_decode(file_get_contents('php://input'), true);
        extract($_POST);
        $db->query("DELETE FROM  wbcc_document_requis_subvention WHERE idDocumentRequisF =$idDocumentRequis AND idSubventionF=$idSubvention ");
        if ($db->execute()) {
            echo json_encode("1");
        } else {
            echo json_encode("0");
        }
    }

    if ($action == 'add') {
        $form = json_decode(file_get_contents('php://input'), true);
        extract($form);
        //   echo json_encode($form);
        $filesCNI  = ConvertTabPhotoToString($fileDatasCNI_Demandeur, 'cni_dmd');
        $filesCarteHandicap = ConvertTabPhotoToString($fileDatasCarteHandicap_D, 'carte_handicap');
        $filesSecurite = ConvertTabPhotoToString($fileDatasSecuriteSoc, 'securite_sociale');
        $filesPrestation = ConvertTabPhotoToString($fileDatasPrestationCAF, 'prestation');
        $filesRevenuAnnuel = ConvertTabPhotoToString($fileDatasRevenuAnnuel, 'revenu_annuel');
        $filesRevenuFiscalN1 = ConvertTabPhotoToString($fileDatasRevenuFiscalN1, 'revenu_fiscalN1');
        $filesRevenuFiscalN2 = ConvertTabPhotoToString($fileDatasRevenuFiscalN2, 'revenu_fiscalN2');


        $numero = "";
        $photosCNI = ConvertTabPhotoToString($fileDatasCNI_Demandeur, 'Demandeur');

        //Insérer demandeur
        if ($idDemandeur == '') {
            $numero = "DMD_" . date("dmYhis");

            $db->query("INSERT INTO wbcc_contact(numeroContact,nomContact,prenomContact,telContact,emailContact,adresseContact,codePostalContact, villeContact, statutContact, etatContact,civiliteContact,copieCNI ) VALUES (:numero, :nom, :prenom, :tel, :email, :adresse, :codePostal, :villeContact, :statut, :etat, :civilite, :copieCNI)");
            $db->bind("numero", $numero, null);
            $db->bind("nom", $nomDemandeur, null);
            $db->bind("prenom", $prenomDemandeur, null);
            $db->bind("tel", $telDemandeur, null);
            $db->bind("email", $emailDemandeur, null);
            $db->bind("adresse", $adresseDemandeur, null);
            $db->bind("statut", $selectedStatutDemandeur, null);
            $db->bind("etat", "1", null);
            $db->bind("civilite", $civiliteDemandeur, null);
            $db->bind("copieCNI", $photosCNI, null);
            $db->bind("codePostal", $CPDemandeur, null);
            $db->bind("villeContact", $villeDemandeur, null);
        } else {
            $numero = $numeroDemandeur;
            $db->query("UPDATE wbcc_contact SET nomContact = :nom, prenomContact = :prenom,telContact = :tel, emailContact =:email, adresseContact = :adresse,statutContact = :statut, :etat, civiliteContact= :civilite, copieCNI = :copieCNI, codePostalContact =:codePostal, villeContact =:villeContact");
            $db->bind("nom", $nomDemandeur, null);
            $db->bind("prenom", $prenomDemandeur, null);
            $db->bind("tel", $telDemandeur, null);
            $db->bind("email", $emailDemandeur, null);
            $db->bind("adresse", $adresseDemandeur, null);
            $db->bind("statut", $selectedStatutDemandeur, null);
            $db->bind("civilite", $civiliteDemandeur, null);
            $db->bind("copieCNI", $photosCNI, null);
            $db->bind("codePostal", $CPDemandeur, null);
            $db->bind("villeContact", $villeDemandeur, null);
        }



        if ($db->execute()) {
            //Recupérer demandeur inséré
            $contact = findContactByNumero($numero);
            if ($contact != null) {
                $natureHandicapDemandeur = sizeof($tabHandicapCoches) != 0 ? implode(";", $tabHandicapCoches) : '';

                $libellePrestationFamiliale = sizeof($tabPrestationCoches) != 0 ? implode(";", $tabPrestationCoches) : '';
                $datePrestationFamiliale = sizeof($datePrestations) != 0 ? implode(";", $datePrestations) : '';
                $montantPrestationFamiliale = sizeof($montantPrestations) != 0 ? implode(";", $montantPrestations) : '';

                $libelleAideFamiliale = sizeof($tabAideFamilialesCoches) != 0 ? implode(";", $tabAideFamilialesCoches) : '';
                $dateAideFamiliale = sizeof($dateAideFamiliales) != 0 ? implode(";", $dateAideFamiliales) : '';
                $montantAideFamiliale = sizeof($montantAideFamiliales) != 0 ? implode(";", $montantAideFamiliales) : '';

                $libellePrestationChomage = sizeof($tabPrestationChomageCoches) != 0 ? implode(";", $tabPrestationChomageCoches) : '';
                $datePrestationChomage = sizeof($datePrestationChomages) != 0 ? implode(";", $datePrestationChomages) : '';
                $montantPrestationChomage = sizeof($montantPrestationChomages) != 0 ? implode(";", $montantPrestationChomages) : '';

                $libellePrestationRetraite = sizeof($tabPrestationRetraiteCoches) != 0 ? implode(";", $tabPrestationRetraiteCoches) : '';
                $datePrestationRetraite = sizeof($datePrestationRetraites) != 0 ? implode(";", $datePrestationRetraites) : '';
                $montantPrestationRetraite = sizeof($montantPrestationRetraites) != 0 ? implode(";", $montantPrestationRetraites) : '';



                //Insérer questionnaire demandeur
                $db->query("INSERT INTO wbcc_questionnaire(R_SitHandicapPersonne,dateSitHandicapPersonne,natureHandicapPersonne,repCarteInvaliditePersonne,dateValiditeHandicapPersonne,organismeCarteHandicappersonne,RJ_CarteHandicapPersonne, commentCarteHandicapPersonne,numeroSecuriteSocialePersonne,cleSecuriteSocialePersonne,RJ_SecuriteSocialePersonne,commentSecuriteSocialePersonne,selectedTypeSecuritePersonne,repPrestationCAFPersonne,libellePrestationFamilialePersonne,datePrestationFamilialePersonne,montantPrestationFamilialePersonne,RJ_AttestationCAFPersonne,commentAttestationCAFPersonne,repActiviteDPersonne,selectedActiviteDPersonne,posteOccupeDPersonne,entrepriseDPersonne,dateDebutContratDPersonne,dateChomagePersonne,selectedTypeChomagePersonne,autreLibelleActivitePersonne,autreTypeChomagePersonne,mntRevenuAnnuelDPersonne,RJ_RevenuAnnuelPersonne,commentRevenuAnnuelPersonne,selectedTypeRevenuAnnuelPersonne,selectedAllocationDPersonne,R_AssuranceRetraitePersonne,rep_PSDPersonne,rep_eligibilite_PSDPersonne,rep_APAPersonne,rep_eligibilite_APAPersonne,rep_ACTPPersonne,rep_eligibilite_ACTPPersonne,rep_PCHPersonne,rep_eligibilite_PCHPersonne,rep_MTPPersonne,rep_eligibilite_MTPPersonne,RepAidePrestFamilialePersonne,libelleAideFamilialePersonne,dateAideFamilialePersonne,montantAideFamilialePersonne,numeroFiscalPersonne,repDeclarationRevenuPersonne,refAvisImpositionPersonne,revenuFiscalN1Personne,RJ_RevenuFiscalN1Personne,commentRevenuFiscalN1Personne,revenuFiscalN2Personne,RJ_RevenuFiscalN2Personne, commentRevenuFiscalN2Personne, idContactF,filesCNI,filesCarteHandicap, filesSecurite, filesPrestation, filesRevenuAnnuel, filesRevenuFiscalN1, filesRevenuFiscalN2,selectedStatutDmd,autreStatutDmd,adresseDmd,typeBienA_renover,anneeConstruction,surfaceLogement,typeResidenceDmd,autreLieu,nbAutreResidence,autreAdresseResidence,repConventionLogement,dateConvention,typeTravauxIsolation,autreTypeTravauxIsolation,typeTravauxChauffage,autreTypeTravauxChauffage,typeTravauxPompe,autreTypeTravauxPompe,typeTravauxChauffe,autreTypeTravauxChauffe,repResterResidence,repDecoLogement,dateConstat,repTravauxEntames,repAideTravaux,libelleAideTravaux,mntAideTravaux,repAideANAH,libelleAideANAH,mntAideANAH,repBeneficePTZ,repSoumissionLogement,repPretTravaux,selectedTypeTravaux,niveauDegradation,libellePrestationChomage,datePrestationChomage,montantPrestationChomage,libellePrestationRetraite,datePrestationRetraite,montantPrestationRetraite, secteurActivite, formeJuridique, datePSD, montantPSD, dateAPA, montantAPA, dateACTP, montantACTP, datePCH, montantPCH, dateMTP, montantMTP) VALUES 
                (:R_SitHandicapPersonne,:dateSitHandicapPersonne,:natureHandicapPersonne,:repCarteInvaliditePersonne,:dateValiditeHandicapPersonne,:organismeCarteHandicappersonne,:RJ_CarteHandicapPersonne,:commentCarteHandicapPersonne,:numeroSecuriteSocialePersonne,:cleSecuriteSocialePersonne,:RJ_SecuriteSocialePersonne,:commentSecuriteSocialePersonne,:selectedTypeSecuritePersonne,:repPrestationCAFPersonne,:libellePrestationFamilialePersonne,:datePrestationFamilialePersonne,:montantPrestationFamilialePersonne,:RJ_AttestationCAFPersonne,:commentAttestationCAFPersonne,:repActiviteDPersonne,:selectedActiviteDPersonne,:posteOccupeDPersonne,:entrepriseDPersonne,:dateDebutContratDPersonne,:dateChomagePersonne,:selectedTypeChomagePersonne,:autreLibelleActivitePersonne,:autreTypeChomagePersonne,:mntRevenuAnnuelDPersonne,:RJ_RevenuAnnuelPersonne,:commentRevenuAnnuelPersonne,:selectedTypeRevenuAnnuelPersonne,:selectedAllocationDPersonne,:R_AssuranceRetraitePersonne,:rep_PSDPersonne,:rep_eligibilite_PSDPersonne,:rep_APAPersonne,:rep_eligibilite_APAPersonne,:rep_ACTPPersonne,:rep_eligibilite_ACTPPersonne,:rep_PCHPersonne,:rep_eligibilite_PCHPersonne,:rep_MTPPersonne,:rep_eligibilite_MTPPersonne,:RepAidePrestFamilialePersonne,:libelleAideFamilialePersonne,:dateAideFamilialePersonne,:montantAideFamilialePersonne,:numeroFiscalPersonne,:repDeclarationRevenuPersonne,:refAvisImpositionPersonne,:revenuFiscalN1Personne,:RJ_RevenuFiscalN1Personne,:commentRevenuFiscalN1Personne,:revenuFiscalN2Personne,:RJ_RevenuFiscalN2Personne,:commentRevenuFiscalN2Personne, :idContactF, :filesCNI, :filesCarteHandicap, :filesSecurite, :filesPrestation, :filesRevenuAnnuel, :filesRevenuFiscalN1, :filesRevenuFiscalN2,:selectedStatutDmd,:autreStatutDmd,:adresseDmd,:typeBienA_renover,:anneeConstruction,:surfaceLogement,:typeResidenceDmd,:autreLieu,:nbAutreResidence,:autreAdresseResidence,:repConventionLogement,:dateConvention,:typeTravauxIsolation,:autreTypeTravauxIsolation,:typeTravauxChauffage,:autreTypeTravauxChauffage,:typeTravauxPompe,:autreTypeTravauxPompe,:typeTravauxChauffe,:autreTypeTravauxChauffe,:repResterResidence,:repDecoLogement,:dateConstat,:repTravauxEntames,:repAideTravaux,:libelleAideTravaux,:mntAideTravaux,:repAideANAH,:libelleAideANAH,:mntAideANAH,:repBeneficePTZ,:repSoumissionLogement,:repPretTravaux,:selectedTypeTravaux,:niveauDegradation,:libellePrestationChomage,:datePrestationChomage,:montantPrestationChomage,:libellePrestationRetraite,:datePrestationRetraite,:montantPrestationRetraite, :secteurActivite, :formeJuridique, :datePSD, :montantPSD, :dateAPA, :montantAPA, :dateACTP, :montantACTP, :datePCH, :montantPCH, :dateMTP, :montantMTP)");



                $db->bind("idContactF", $contact->idContact, null);
                $db->bind("R_SitHandicapPersonne", $repSitHandicap, null);
                $db->bind("dateSitHandicapPersonne", $dateSitHandicapDemandeur, null);
                $db->bind("natureHandicapPersonne", $natureHandicapDemandeur, null);
                $db->bind("repCarteInvaliditePersonne", $repCarteInvalidite, null);
                $db->bind("dateValiditeHandicapPersonne", $dateValiditeHandicap, null);
                $db->bind("organismeCarteHandicappersonne", $organismeCarteHandicap, null);
                $db->bind("RJ_CarteHandicapPersonne", $RJ_CarteHandicap, null);
                $db->bind("commentCarteHandicapPersonne", $commentCarteHandicap, null);
                $db->bind("numeroSecuriteSocialePersonne", $numeroSecuriteSociale, null);
                $db->bind("cleSecuriteSocialePersonne", $cleSecuriteSociale, null);
                $db->bind("RJ_SecuriteSocialePersonne", $RJ_SecuriteSociale, null);
                $db->bind("commentSecuriteSocialePersonne", $commentSecuriteSociale, null);
                $db->bind("selectedTypeSecuritePersonne", $selectedTypeSecurite, null);
                $db->bind("repPrestationCAFPersonne", $repPrestationCAF, null);
                $db->bind("libellePrestationFamilialePersonne", $libellePrestationFamiliale, null);
                $db->bind("datePrestationFamilialePersonne", $datePrestationFamiliale, null);
                $db->bind("montantPrestationFamilialePersonne", $montantPrestationFamiliale, null);
                $db->bind("RJ_AttestationCAFPersonne", $RJ_AttestationCAF, null);
                $db->bind("commentAttestationCAFPersonne", $commentAttestationCAF, null);
                $db->bind("repActiviteDPersonne", $repActiviteD, null);
                $db->bind("selectedActiviteDPersonne", $selectedActiviteD, null);
                $db->bind("posteOccupeDPersonne", $posteOccupeD, null);
                $db->bind("entrepriseDPersonne", $entrepriseD, null);
                $db->bind("dateDebutContratDPersonne", $dateDebutContratD, null);
                $db->bind("dateChomagePersonne", $dateChomage, null);
                $db->bind("selectedTypeChomagePersonne", $selectedTypeChomage, null);
                $db->bind("autreLibelleActivitePersonne", $autreLibelleActivite, null);
                $db->bind("autreTypeChomagePersonne", $autreTypeChomage, null);
                $db->bind("mntRevenuAnnuelDPersonne", $mntRevenuAnnuelD, null);
                $db->bind("RJ_RevenuAnnuelPersonne", $RJ_RevenuAnnuel, null);
                $db->bind("commentRevenuAnnuelPersonne", $commentRevenuAnnuel, null);
                $db->bind("selectedTypeRevenuAnnuelPersonne", $selectedTypeRevenuAnnuel, null);
                $db->bind("selectedAllocationDPersonne", $selectedAllocationD, null);
                $db->bind("R_AssuranceRetraitePersonne", $R_AssuranceRetraite, null);
                $db->bind("rep_PSDPersonne", $rep_PSD, null);
                $db->bind("rep_eligibilite_PSDPersonne", $rep_eligibilite_PSD, null);
                $db->bind("rep_APAPersonne", $rep_APA, null);
                $db->bind("rep_eligibilite_APAPersonne", $rep_eligibilite_APA, null);
                $db->bind("rep_ACTPPersonne", $rep_ACTP, null);
                $db->bind("rep_eligibilite_ACTPPersonne", $rep_eligibilite_ACTP, null);
                $db->bind("rep_PCHPersonne", $rep_PCH, null);
                $db->bind("rep_eligibilite_PCHPersonne", $rep_eligibilite_PCH, null);
                $db->bind("rep_MTPPersonne", $rep_MTP, null);
                $db->bind("rep_eligibilite_MTPPersonne", $rep_eligibilite_MTP, null);
                $db->bind("RepAidePrestFamilialePersonne", $RepAidePrestFamiliale, null);
                $db->bind("libelleAideFamilialePersonne", $libelleAideFamiliale, null);
                $db->bind("dateAideFamilialePersonne", $dateAideFamiliale, null);
                $db->bind("montantAideFamilialePersonne", $montantAideFamiliale, null);
                $db->bind("numeroFiscalPersonne", $numeroFiscal, null);
                $db->bind("repDeclarationRevenuPersonne", $repDeclarationRevenu, null);
                $db->bind("refAvisImpositionPersonne", $refAvisImposition, null);
                $db->bind("revenuFiscalN1Personne", $revenuFiscalN1, null);
                $db->bind("RJ_RevenuFiscalN1Personne", $RJ_RevenuFiscalN1, null);
                $db->bind("commentRevenuFiscalN1Personne", $commentRevenuFiscalN1, null);
                $db->bind("revenuFiscalN2Personne", $revenuFiscalN2, null);
                $db->bind("RJ_RevenuFiscalN2Personne", $RJ_RevenuFiscalN2, null);
                $db->bind("commentRevenuFiscalN2Personne", $commentRevenuFiscalN2, null);
                $db->bind("filesCNI", $filesCNI, null);
                $db->bind("filesCarteHandicap", $filesCarteHandicap, null);
                $db->bind("filesSecurite", $filesSecurite, null);
                $db->bind("filesPrestation", $filesPrestation, null);
                $db->bind("filesRevenuAnnuel", $filesRevenuAnnuel, null);
                $db->bind("filesRevenuFiscalN1", $filesRevenuFiscalN1, null);
                $db->bind("filesRevenuFiscalN2", $filesRevenuFiscalN2, null);
                $db->bind("selectedStatutDmd", $selectedStatutDemandeur, null);
                $db->bind("adresseDmd", $adresseDemandeur, null);
                $db->bind("autreStatutDmd", $autreStatutDemandeur, null);
                $db->bind("typeBienA_renover", $typeBienA_renover, null);
                $db->bind("anneeConstruction", $anneeConstruction, null);
                $db->bind("surfaceLogement", $surfaceLogement, null);
                $db->bind("typeResidenceDmd", $typeResidence, null);
                $db->bind("autreLieu", $repAutreLieuResidence, null);
                $db->bind("nbAutreResidence", $nbAutreLieuResidence, null);
                $db->bind("autreAdresseResidence", $autreAdresseResidence, null);
                $db->bind("repConventionLogement", $repConventionLogement, null);
                $db->bind("dateConvention", $dateConventionLogement, null);
                $db->bind("typeTravauxIsolation", $selectedPrecisionTypeTravauxIsolation, null);
                $db->bind("autreTypeTravauxIsolation", $autreTypePrecisionIsolation, null);
                $db->bind("typeTravauxChauffage", $selectedPrecisionTypeTravauxChauffage, null);
                $db->bind("autreTypeTravauxChauffage", $autreTypePrecisionChauffage, null);
                $db->bind("typeTravauxPompe", $selectedPrecisionTypeTravauxPompe, null);
                $db->bind("autreTypeTravauxPompe", $autreTypePrecisionPompe, null);
                $db->bind("typeTravauxChauffe", $selectedPrecisionTypeTravauxChauffe, null);
                $db->bind("autreTypeTravauxChauffe",  $autreTypePrecisionChauffe, null);
                $db->bind("repResterResidence",  $R_resteResidence, null);
                $db->bind("repDecoLogement", $R_decoLogement, null);
                $db->bind("dateConstat", $dateConstat, null);
                $db->bind("repTravauxEntames", $R_travauxEntames, null);
                $db->bind("repAideTravaux", $repAideTravaux, null);
                $db->bind("libelleAideTravaux", $libelleAideTravaux, null);
                $db->bind("mntAideTravaux", $mntAideTravaux, null);
                $db->bind("repAideANAH", $repAideANAH, null);
                $db->bind("libelleAideANAH", $libelleAideANAH, null);
                $db->bind("mntAideANAH", $mntAideANAH, null);
                $db->bind("repBeneficePTZ", $R_beneficePTZ, null);
                $db->bind("repSoumissionLogement", $R_soumissionLogment, null);
                $db->bind("repPretTravaux", $R_pretTravaux, null);
                $db->bind("selectedTypeTravaux", $selectedTypeTravaux, null);
                $db->bind("niveauDegradation", $selectedNiveauDegradation, null);
                $db->bind("libellePrestationChomage", $libellePrestationChomage, null);
                $db->bind("datePrestationChomage", $datePrestationChomage, null);
                $db->bind("montantPrestationChomage", $montantPrestationChomage, null);
                $db->bind("libellePrestationRetraite", $libellePrestationRetraite, null);
                $db->bind("datePrestationRetraite", $datePrestationRetraite, null);
                $db->bind("montantPrestationRetraite", $montantPrestationRetraite, null);
                $db->bind("secteurActivite", $secteurActiviteD, null);
                $db->bind("formeJuridique", $formeJuridiqueD, null);
                $db->bind("datePSD", $datePSD, null);
                $db->bind("montantPSD", $montantPSD, null);
                $db->bind("dateAPA", $dateAPA, null);
                $db->bind("montantAPA", $montantAPA, null);
                $db->bind("dateACTP", $dateACTP, null);
                $db->bind("montantACTP", $montantACTP, null);
                $db->bind("datePCH", $datePCH, null);
                $db->bind("montantPCH", $montantPCH, null);
                $db->bind("dateMTP", $dateMTP, null);
                $db->bind("montantMTP", $montantMTP, null);

                //echo json_encode($form);die;
                if ($db->execute()) {
                    if (sizeof($tabPersonnes) != 0) {
                        $ok = false;
                        $in = 0;
                        foreach ($tabPersonnes as $personne) {

                            //extract($personne);
                            $filesCNI  = ConvertTabPhotoToString($personne['fileDatasCNI_Personne'], 'cni_occ');
                            $filesJustificatifOcc  = ConvertTabPhotoToString($personne['fileDatasPersonne_Doc'], 'justificatif_occ');
                            $filesCarteHandicap = ConvertTabPhotoToString($personne['fileDatasCarteHandicap_P'], 'carte_handicap_occ');
                            $filesSecurite = ConvertTabPhotoToString($personne['fileDatasSecuriteSoc_Personne'], 'securite_sociale_occ');
                            $filesPrestation = ConvertTabPhotoToString($personne['fileDatasPrestationCAF_Personne'], 'prestation_occ');
                            $filesRevenuAnnuel = ConvertTabPhotoToString($personne['fileDatasRevenuAnnuel_Personne'], 'revenu_annuel_occ');
                            $filesRevenuFiscalN1 = ConvertTabPhotoToString($personne['fileDatasRevenuFiscalN1Personne'], 'revenu_fiscalN1_occ');
                            $filesRevenuFiscalN2 = ConvertTabPhotoToString($personne['fileDatasRevenuFiscalN2Personne'], 'revenu_fiscalN2_occ');
                            $in++;

                            $numero = "OCC$in_" . date("dmYhis");
                            $photosCNI = $filesCNI;

                            //Insérer demandeur
                            $db->query("INSERT INTO wbcc_contact(numeroContact,nomContact,prenomContact,lienParente,age,fiscalementCharge,statutContact, etatContact,copieCNI,fileJustificatifOcc,idContactFContact) VALUES (:numero, :nom, :prenom, :lien, :age, :fiscalementCharge, :statut, :etat, :copieCNI,:fileJustificatifOcc,:idContactFContact)");

                            $db->bind("numero", $numero, null);
                            $db->bind("nom", $personne['nom'], null);
                            $db->bind("prenom", $personne['prenom'], null);
                            $db->bind("lien", $personne['lienParente'], null);
                            $db->bind("age", $personne['age'], null);
                            $db->bind("fiscalementCharge", $personne['RJ_chargeFiscalePersonne'], null);
                            $db->bind("statut", "habitant secondaire", null);
                            $db->bind("etat", "1", null);
                            $db->bind("idContactFContact", $contact->idContact, null);
                            $db->bind("fileJustificatifOcc", $filesJustificatifOcc, null);
                            $db->bind("copieCNI", $photosCNI, null);


                            if ($db->execute()) {
                                //Recupérer occupant inséré
                                $occupant = findContactByNumero($numero);

                                if ($occupant != null) {
                                    //Insérer questionnaire occupant
                                    $db->query("INSERT INTO wbcc_questionnaire(R_SitHandicapPersonne,dateSitHandicapPersonne,natureHandicapPersonne,repCarteInvaliditePersonne,dateValiditeHandicapPersonne,organismeCarteHandicappersonne,RJ_CarteHandicapPersonne, commentCarteHandicapPersonne,numeroSecuriteSocialePersonne,cleSecuriteSocialePersonne,RJ_SecuriteSocialePersonne,commentSecuriteSocialePersonne,selectedTypeSecuritePersonne,repPrestationCAFPersonne,libellePrestationFamilialePersonne,datePrestationFamilialePersonne,montantPrestationFamilialePersonne,RJ_AttestationCAFPersonne,commentAttestationCAFPersonne,repActiviteDPersonne,selectedActiviteDPersonne,posteOccupeDPersonne,entrepriseDPersonne,dateDebutContratDPersonne,dateChomagePersonne,selectedTypeChomagePersonne,autreLibelleActivitePersonne,autreTypeChomagePersonne,mntRevenuAnnuelDPersonne,RJ_RevenuAnnuelPersonne,commentRevenuAnnuelPersonne,selectedTypeRevenuAnnuelPersonne,selectedAllocationDPersonne,R_AssuranceRetraitePersonne,rep_PSDPersonne,rep_eligibilite_PSDPersonne,rep_APAPersonne,rep_eligibilite_APAPersonne,rep_ACTPPersonne,rep_eligibilite_ACTPPersonne,rep_PCHPersonne,rep_eligibilite_PCHPersonne,rep_MTPPersonne,rep_eligibilite_MTPPersonne,RepAidePrestFamilialePersonne,libelleAideFamilialePersonne,dateAideFamilialePersonne,montantAideFamilialePersonne,numeroFiscalPersonne,repDeclarationRevenuPersonne,refAvisImpositionPersonne,revenuFiscalN1Personne,RJ_RevenuFiscalN1Personne,commentRevenuFiscalN1Personne,revenuFiscalN2Personne,RJ_RevenuFiscalN2Personne, commentRevenuFiscalN2Personne, idContactF,filesCNI,filesCarteHandicap, filesSecurite, filesPrestation, filesRevenuAnnuel, filesRevenuFiscalN1, filesRevenuFiscalN2) VALUES 
                                    (:R_SitHandicapPersonne,:dateSitHandicapPersonne,:natureHandicapPersonne,:repCarteInvaliditePersonne,:dateValiditeHandicapPersonne,:organismeCarteHandicappersonne,:RJ_CarteHandicapPersonne,:commentCarteHandicapPersonne,:numeroSecuriteSocialePersonne,:cleSecuriteSocialePersonne,:RJ_SecuriteSocialePersonne,:commentSecuriteSocialePersonne,:selectedTypeSecuritePersonne,:repPrestationCAFPersonne,:libellePrestationFamilialePersonne,:datePrestationFamilialePersonne,:montantPrestationFamilialePersonne,:RJ_AttestationCAFPersonne,:commentAttestationCAFPersonne,:repActiviteDPersonne,:selectedActiviteDPersonne,:posteOccupeDPersonne,:entrepriseDPersonne,:dateDebutContratDPersonne,:dateChomagePersonne,:selectedTypeChomagePersonne,:autreLibelleActivitePersonne,:autreTypeChomagePersonne,:mntRevenuAnnuelDPersonne,:RJ_RevenuAnnuelPersonne,:commentRevenuAnnuelPersonne,:selectedTypeRevenuAnnuelPersonne,:selectedAllocationDPersonne,:R_AssuranceRetraitePersonne,:rep_PSDPersonne,:rep_eligibilite_PSDPersonne,:rep_APAPersonne,:rep_eligibilite_APAPersonne,:rep_ACTPPersonne,:rep_eligibilite_ACTPPersonne,:rep_PCHPersonne,:rep_eligibilite_PCHPersonne,:rep_MTPPersonne,:rep_eligibilite_MTPPersonne,:RepAidePrestFamilialePersonne,:libelleAideFamilialePersonne,:dateAideFamilialePersonne,:montantAideFamilialePersonne,:numeroFiscalPersonne,:repDeclarationRevenuPersonne,:refAvisImpositionPersonne,:revenuFiscalN1Personne,:RJ_RevenuFiscalN1Personne,:commentRevenuFiscalN1Personne,:revenuFiscalN2Personne,:RJ_RevenuFiscalN2Personne,:commentRevenuFiscalN2Personne, :idContactF, :filesCNI, :filesCarteHandicap, :filesSecurite, :filesPrestation, :filesRevenuAnnuel, :filesRevenuFiscalN1, :filesRevenuFiscalN2)");


                                    $db->bind("idContactF", $occupant->idContact, null);
                                    $db->bind("R_SitHandicapPersonne", $personne['R_SitHandicapPersonne'], null);
                                    $db->bind("dateSitHandicapPersonne", $personne['dateSitHandicapPersonne'], null);
                                    $db->bind("natureHandicapPersonne", $personne['natureHandicapPersonne'], null);
                                    $db->bind("repCarteInvaliditePersonne", $personne['repCarteInvaliditePersonne'], null);
                                    $db->bind("dateValiditeHandicapPersonne", $personne['dateValiditeHandicapPersonne'], null);
                                    $db->bind("organismeCarteHandicappersonne", $personne['organismeCarteHandicappersonne'], null);
                                    $db->bind("RJ_CarteHandicapPersonne", $personne['RJ_CarteHandicapPersonne'], null);
                                    $db->bind("commentCarteHandicapPersonne", $personne['commentCarteHandicapPersonne'], null);
                                    $db->bind("numeroSecuriteSocialePersonne", $personne['numeroSecuriteSocialePersonne'], null);
                                    $db->bind("cleSecuriteSocialePersonne", $personne['cleSecuriteSocialePersonne'], null);
                                    $db->bind("RJ_SecuriteSocialePersonne", $personne['RJ_SecuriteSocialePersonne'], null);
                                    $db->bind("commentSecuriteSocialePersonne", $personne['commentSecuriteSocialePersonne'], null);
                                    $db->bind("selectedTypeSecuritePersonne", $personne['selectedTypeSecuritePersonne'], null);
                                    $db->bind("repPrestationCAFPersonne", $personne['repPrestationCAFPersonne'], null);
                                    $db->bind("libellePrestationFamilialePersonne", $personne['libellePrestationFamilialePersonne'], null);
                                    $db->bind("datePrestationFamilialePersonne", $personne['datePrestationFamilialePersonne'], null);
                                    $db->bind("montantPrestationFamilialePersonne", $personne['montantPrestationFamilialePersonne'], null);
                                    $db->bind("RJ_AttestationCAFPersonne", $personne['RJ_AttestationCAFPersonne'], null);
                                    $db->bind("commentAttestationCAFPersonne", $personne['commentAttestationCAFPersonne'], null);
                                    $db->bind("repActiviteDPersonne", $personne['repActiviteDPersonne'], null);
                                    $db->bind("selectedActiviteDPersonne", $personne['selectedActiviteDPersonne'], null);
                                    $db->bind("posteOccupeDPersonne", $personne['posteOccupeDPersonne'], null);
                                    $db->bind("entrepriseDPersonne", $personne['entrepriseDPersonne'], null);
                                    $db->bind("dateDebutContratDPersonne", $personne['dateDebutContratDPersonne'], null);
                                    $db->bind("dateChomagePersonne", $personne['dateChomagePersonne'], null);
                                    $db->bind("selectedTypeChomagePersonne", $personne['selectedTypeChomagePersonne'], null);
                                    $db->bind("autreLibelleActivitePersonne", $personne['autreLibelleActivitePersonne'], null);
                                    $db->bind("autreTypeChomagePersonne", $personne['autreTypeChomagePersonne'], null);
                                    $db->bind("mntRevenuAnnuelDPersonne", $personne['mntRevenuAnnuelDPersonne'], null);
                                    $db->bind("RJ_RevenuAnnuelPersonne", $personne['RJ_RevenuAnnuelPersonne'], null);
                                    $db->bind("commentRevenuAnnuelPersonne", $personne['commentRevenuAnnuelPersonne'], null);
                                    $db->bind("selectedTypeRevenuAnnuelPersonne", $personne['selectedTypeRevenuAnnuelPersonne'], null);
                                    $db->bind("selectedAllocationDPersonne", $personne['selectedAllocationDPersonne'], null);
                                    $db->bind("R_AssuranceRetraitePersonne", $personne['R_AssuranceRetraitePersonne'], null);
                                    $db->bind("rep_PSDPersonne", $personne['rep_PSDPersonne'], null);
                                    $db->bind("rep_eligibilite_PSDPersonne", $personne['rep_eligibilite_PSDPersonne'], null);
                                    $db->bind("rep_APAPersonne", $personne['rep_APAPersonne'], null);
                                    $db->bind("rep_eligibilite_APAPersonne", $personne['rep_eligibilite_APAPersonne'], null);
                                    $db->bind("rep_ACTPPersonne", $personne['rep_ACTPPersonne'], null);
                                    $db->bind("rep_eligibilite_ACTPPersonne", $personne['rep_eligibilite_ACTPPersonne'], null);
                                    $db->bind("rep_PCHPersonne", $personne['rep_PCHPersonne'], null);
                                    $db->bind("rep_eligibilite_PCHPersonne", $personne['rep_eligibilite_PCHPersonne'], null);
                                    $db->bind("rep_MTPPersonne", $personne['rep_MTPPersonne'], null);
                                    $db->bind("rep_eligibilite_MTPPersonne", $personne['rep_eligibilite_MTPPersonne'], null);
                                    $db->bind("RepAidePrestFamilialePersonne", $personne['RepAidePrestFamilialePersonne'], null);
                                    $db->bind("libelleAideFamilialePersonne", $personne['libelleAideFamilialePersonne'], null);
                                    $db->bind("dateAideFamilialePersonne", $personne['dateAideFamilialePersonne'], null);
                                    $db->bind("montantAideFamilialePersonne", $personne['montantAideFamilialePersonne'], null);
                                    $db->bind("numeroFiscalPersonne", $personne['numeroFiscalPersonne'], null);
                                    $db->bind("repDeclarationRevenuPersonne", $personne['repDeclarationRevenuPersonne'], null);
                                    $db->bind("refAvisImpositionPersonne", $personne['refAvisImpositionPersonne'], null);
                                    $db->bind("revenuFiscalN1Personne", $personne['revenuFiscalN1Personne'], null);
                                    $db->bind("RJ_RevenuFiscalN1Personne", $personne['RJ_RevenuFiscalN1Personne'], null);
                                    $db->bind("commentRevenuFiscalN1Personne", $personne['commentRevenuFiscalN1Personne'], null);
                                    $db->bind("revenuFiscalN2Personne", $personne['revenuFiscalN2Personne'], null);
                                    $db->bind("RJ_RevenuFiscalN2Personne", $personne['RJ_RevenuFiscalN2Personne'], null);
                                    $db->bind("commentRevenuFiscalN2Personne", $personne['commentRevenuFiscalN2Personne'], null);
                                    $db->bind("filesCNI", $filesCNI, null);
                                    $db->bind("filesCarteHandicap", $filesCarteHandicap, null);
                                    $db->bind("filesSecurite", $filesSecurite, null);
                                    $db->bind("filesPrestation", $filesPrestation, null);
                                    $db->bind("filesRevenuAnnuel", $filesRevenuAnnuel, null);
                                    $db->bind("filesRevenuFiscalN1", $filesRevenuFiscalN1, null);
                                    $db->bind("filesRevenuFiscalN2", $filesRevenuFiscalN2, null);

                                    if ($db->execute()) {
                                        $ok = true;
                                    }
                                }
                            }
                        }
                        if ($ok) {
                            echo json_encode("Personnes & questionnaires ajoutés");
                        } else {
                            echo json_encode("Erreur ajout Questionnaire occupant");
                        }
                    }
                } else {
                    echo json_encode("Erreur ajout Questionnaire");
                }
            }
        }
    }

    if ($action == "findDocumentRequisByID") {
        $id = $_GET['id'];
        $db->query("SELECT * FROM wbcc_document_requis WHERE idDocumentRequis  = $id");
        $data = $db->single();
        echo json_encode($data);
    }

    if ($action == 'saveDocumentRequis') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $ok = 1;
        extract($_POST);
        if ($idDocumentRequis != null  && $idDocumentRequis != "" && $idDocumentRequis != "0") {
            $db->query("UPDATE wbcc_document_requis  SET libelleDocumentRequis=:libelleDocumentRequis, commentaire=:commentaire, editDate=:editDate, idAuteur=:idAuteur WHERE idDocumentRequis=:idDocumentRequis");
            $db->bind("idDocumentRequis", $idDocumentRequis, null);
            $db->bind("editDate", date("Y-m-d H:i:s"), null);
        } else {
            $db->query("INSERT INTO wbcc_document_requis ( libelleDocumentRequis, commentaire, idAuteur) VALUES ( :libelleDocumentRequis, :commentaire, :idAuteur)");
        }
        $db->bind("libelleDocumentRequis", $libelleDocumentRequis, null);
        $db->bind("commentaire", $commentaireDocumentRequis, null);
        $db->bind("idAuteur", $idAuteur, null);
        if ($db->execute()) {
            $ok = 1;
        } else {
            $ok = 0;
        }
        echo json_encode("$ok");
    }
}


function ConvertTabPhotoToString($fileDatas, $type = "photo")
{
    $photos = [];
    $i = 0;
    if (sizeof($fileDatas) != 0) {
        foreach ($fileDatas as $key => $file) {
            if ($file != "") {
                $bin = base64_decode($file);
                $im = imageCreateFromString($bin);
                if (!$im) {
                    echo json_encode("0");
                }
                $nom = "$type" . "_$key" . date("dmYHis") . ".png";
                $img_file = "../img/documents/$nom";
                $photos[$i] = $nom;
                $i++;
                imagepng($im, $img_file, 0);
            }
        }
    }
    return sizeof($photos) != 0 ? implode(";", $photos) : '';
}

function findContactByNumero($numero)
{
    $db = new Database();
    $db->query("SELECT * FROM wbcc_contact WHERE numeroContact = :numero");
    $db->bind("numero", $numero, null);
    return $db->single();
}
