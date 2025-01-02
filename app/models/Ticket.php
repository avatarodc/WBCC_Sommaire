<?php

/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 11/12/2019
 * Time: 5:23 PM
 */

require_once "Immeuble.php";
require_once "Contact.php";

class Ticket extends Model
{

    public function getTicketBayEmailId($EmailId)
    {
        $sql = "  SELECT a.*, b.idTicket as idParent, 
                b.numero as numeroParent
                FROM wbcc_ticket a 
                LEFT OUTER JOIN wbcc_ticket b ON(a.idTicketF=b.idTicket)
                WHERE a.EmailId='$EmailId'";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->single();
        return $result;
    }

    public function createTicket(
        $numeroTicket,
        $numero,
        $intitule,
        $priorite,
        $statut,
        $categorie,
        $commentaire,
        $DateReceivedEmail,
        $DateDebut,
        $DateFin,
        $DateReelDebut,
        $DateCreation,
        $DateCloture,
        $dateMAJ,
        $auteurCreation,
        $numeroAuteurCreation,
        $auteurCloture,
        $idAuteurCloture,
        $idTicketF,
        $numeroTicketF,
        $visibilite,
        $EmailId,
        $EmailSender,
        $idOpportunityF
    ) {

        /******** Récupérer les employé encharge durant cette période *********/
        if (explode(' ', $DateCreation)[1] > '18:00:00') {
            // date prochain
            $dateDebutProg = explode(' ', $DateCreation)[0] . ' 18:00:01';
            $timestamp = strtotime($dateDebutProg);
            $timestamp_future = $timestamp + (24 * 60 * 60) - 1;
            $dateFinProg = date("Y-m-d H:i:s", $timestamp_future);
            $programme = $this->findLineProgramme($dateDebutProg, $dateFinProg);
            $sousJascentPrincipal = ! empty($programme) ? $programme->employePrincipal : null;
            $sousJascentScondaire = ! empty($programme) ? $programme->employeSecondaire : null;
        } else {
            // cette date
            $dateFinProg = explode(' ', $DateCreation)[0] . ' 18:00:00';
            $timestamp = strtotime($dateFinProg);
            $timestamp_passe = $timestamp - (24 * 60 * 60) + 1;
            $dateDebutProg = date("Y-m-d H:i:s", $timestamp_passe);
            $programme = $this->findLineProgramme($dateDebutProg, $dateFinProg);
            $sousJascentPrincipal = ! empty($programme) ? $programme->employePrincipal : null;
            $sousJascentScondaire = ! empty($programme) ? $programme->employeSecondaire : null;
        }

        /******************* Fin récupération**************/
        $sql = "INSERT INTO wbcc_ticket(
            numeroTicket,
            numero,
            intitule,
            priorite,
            statut,
            categorie,
            commentaire,
            DateReceivedEmail,
            DateDebut,
            DateFin,
            DateReelDebut,
            DateCreation,
            DateCloture,
            dateMAJ,
            auteurCreation,
            numeroAuteurCreation,
            auteurCloture,
            idAuteurCloture,
            idTicketF,
            numeroTicketF,
            visibilite,
            EmailId,
            EmailSender,
            sousJascentPrincipal,
            sousJascentScondaire,
            idOpportunityF
            )
        VALUES (
            :numeroTicket,
            :numero,
            :intitule,
            :priorite,
            :statut,
            :categorie,
            :commentaire,
            :DateReceivedEmail,
            :DateDebut,
            :DateFin,
            :DateReelDebut,
            :DateCreation,
            :DateCloture,
            :dateMAJ,
            :auteurCreation,
            :numeroAuteurCreation,
            :auteurCloture,
            :idAuteurCloture,
            :idTicketF,
            :numeroTicketF,
            :visibilite,
            :EmailId,
            :EmailSender,
            :sousJascentPrincipal,
            :sousJascentScondaire,
            :idOpportunityF
            )";
        $this->db->query($sql);

        $this->db->bind("numeroTicket", $numeroTicket, null);
        $this->db->bind("numero", $numero, null);
        $this->db->bind("intitule", $intitule, null);
        $this->db->bind("priorite", $priorite, null);
        $this->db->bind("statut", $statut, null);
        $this->db->bind("categorie", $categorie, null);
        $this->db->bind("commentaire", $commentaire, null);
        $this->db->bind("DateReceivedEmail", $DateReceivedEmail, null);
        $this->db->bind("DateDebut", $DateDebut, null);
        $this->db->bind("DateFin", $DateFin, null);
        $this->db->bind("DateReelDebut", $DateReelDebut, null);
        $this->db->bind("DateCreation", $DateCreation, null);
        $this->db->bind("DateCloture", $DateCloture, null);
        $this->db->bind("dateMAJ", $dateMAJ, null);
        $this->db->bind("auteurCreation", $auteurCreation, null);
        $this->db->bind("numeroAuteurCreation", $numeroAuteurCreation, null);
        $this->db->bind("auteurCloture", $auteurCloture, null);
        $this->db->bind("idAuteurCloture", $idAuteurCloture, null);
        $this->db->bind("idTicketF", $idTicketF, null);
        $this->db->bind("numeroTicketF", $numeroTicketF, null);
        $this->db->bind("visibilite", $visibilite, null);
        $this->db->bind("EmailId", $EmailId, null);
        $this->db->bind("EmailSender", str_replace("&&slash&&", "'", $EmailSender), null);
        $this->db->bind("sousJascentPrincipal", $sousJascentPrincipal, null);
        $this->db->bind("sousJascentScondaire", $sousJascentScondaire, null);
        $this->db->bind("idOpportunityF", $idOpportunityF, null);

        $this->db->execute();

        $idTicket = $this->getTicketBayEmailId($EmailId)->idTicket;

        if ($categorie == 1) {
            $api_url = URLROOT . '/public/json/ticket/Cron_Email.php?action=notifEmailReceved&idTicket=' . $idTicket;
            file_get_contents($api_url);
            return $idTicket;
        } else {
            return $idTicket;
        }
    }

    public function saveEmail(
        $expediteur,
        $destinataire,
        $destinatairecc,
        $dateEmail,
        $objetEmail,
        $corpsEmail,
        $piecesJointesEmail,
        $entrantSortant,
        $idExpediteurInterne,
        $messageID,
        $nouveauSortant = 0
    ) {

        $sql = "INSERT INTO wbcc_ticket_email_svg(
            expediteur,
            destinataire,
            destinatairecc,
            dateEmail,
            objetEmail,
            corpsEmail,
            piecesJointesEmail,
            entrantSortant,
            nouveauSortant,
            idExpediteurInterne,
            messageID
            )
        VALUES (
            :expediteur,
            :destinataire,
            :destinatairecc,
            :dateEmail,
            :objetEmail,
            :corpsEmail,
            :piecesJointesEmail,
            :entrantSortant,
            :nouveauSortant,
            :idExpediteurInterne,
            :messageID
            )";
        $this->db->query($sql);

        $this->db->bind("expediteur", str_replace("&&slash&&", "'", $expediteur), null);
        $this->db->bind("destinataire", $destinataire, null);
        $this->db->bind("destinatairecc", $destinatairecc, null);
        $this->db->bind("dateEmail", $dateEmail, null);
        $this->db->bind("objetEmail", $objetEmail, null);
        $this->db->bind("corpsEmail", $corpsEmail, null);
        $this->db->bind("piecesJointesEmail", $piecesJointesEmail, null);
        $this->db->bind("entrantSortant", $entrantSortant, null);
        $this->db->bind("nouveauSortant", $nouveauSortant, null);
        $this->db->bind("idExpediteurInterne", $idExpediteurInterne, null);
        $this->db->bind("messageID", $messageID, null);

        return $this->db->execute();
    }


    public function getNextTicketNumber()
    {

        /*$nbTicket= $this->getListTicket('');
        $nString=count($nbTicket)+1;
        $num =  str_pad($nString, 9, '0', STR_PAD_LEFT);
        $numero='T'.$num;*/

        $sql = "SELECT * FROM wbcc_ticket ORDER BY idTicket DESC ";
        $this->db->query($sql);
        $result = $this->db->resultSet();
        if (! empty($result)) {
            $nbTicket = $result[0]->idTicket;
        } else {
            $nbTicket = 0;
        }

        $nString = $nbTicket + 1;
        $numero = 'T';
        if (strlen(strval($nString)) == 1) {
            $numero .= '00000' . strval($nString);
        } elseif (strlen(strval($nString)) == 2) {
            $numero .= '0000' . strval($nString);
        } elseif (strlen(strval($nString)) == 3) {
            $numero .= '000' . strval($nString);
        } elseif (strlen(strval($nString)) == 4) {
            $numero .= '00' . strval($nString);
        } elseif (strlen(strval($nString)) == 5) {
            $numero .= '0' . strval($nString);
        } else {
            $numero .= strval($nString);
        }

        //echo "Numéro : $nString <br/>";

        return $numero;
    }


    public function getListTicket($item)
    {
        $sql = "SELECT * FROM wbcc_ticket ORDER BY idTicket  DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getTicketBayNumber($numero)
    {
        $sql = " SELECT * FROM wbcc_ticket
                WHERE numero='$numero'";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->single();
        return $result;
    }

    public function changeStatusTicketEmail($idTicket, $statut, $auteurCloture, $idAuteurCloture, $commentaire, $DateCloture)
    {
        $sql = "UPDATE wbcc_ticket SET 
            statut = :statut, 
            DateCloture=:DateCloture,
            auteurCloture=:auteurCloture,
            idAuteurCloture=:idAuteurCloture,
            commentaire=:commentaire
            WHERE idTicket = $idTicket";
        $this->db->query($sql);
        $this->db->bind('statut', $statut, null);
        $this->db->bind('DateCloture', $DateCloture, null);
        $this->db->bind('auteurCloture', $auteurCloture, null);
        $this->db->bind('idAuteurCloture', $idAuteurCloture, null);
        $this->db->bind('commentaire', $commentaire, null);

        return $this->db->execute();
    }

    public function askCloseTicketEmail($idTicket, $statut, $idAuteurDemandeCloture, $commentaireDemandeCloture, $DateDemandeCloture, $isNoreply = 0, $isAnswered = 0)
    {
        $sql = "UPDATE wbcc_ticket SET 
            statut = :statut, 
            DateDemandeCloture=:DateDemandeCloture,
            idAuteurDemandeCloture=:idAuteurDemandeCloture,
            commentaireDemandeCloture=:commentaireDemandeCloture,
            isNoreply =:isNoreply,
            isAnswered =:isAnswered
            WHERE idTicket = $idTicket";
        $this->db->query($sql);
        $this->db->bind('statut', $statut, null);
        $this->db->bind('DateDemandeCloture', $DateDemandeCloture, null);
        $this->db->bind('idAuteurDemandeCloture', $idAuteurDemandeCloture, null);
        $this->db->bind('commentaireDemandeCloture', $commentaireDemandeCloture, null);
        $this->db->bind('isNoreply', $isNoreply, null);
        $this->db->bind('isAnswered', $isAnswered, null);

        return $this->db->execute();
    }


    public function newAnswer($dateMAJ, $EmailId, $messageNonLu)
    {
        $sql = "SELECT messageLu as number FROM wbcc_ticket 
                WHERE EmailId = '$EmailId' ";
        $this->db->query($sql);
        $res = $this->db->single();
        $nbMessage = ! empty($res) ? $messageNonLu - intval($res->number) : $messageNonLu;

        $df = new DateTime($dateMAJ);
        $DateFin = $df->add(new DateInterval('P1D'));
        $DateFin = $DateFin->format('Y-m-d H:i:s');

        $sql = "UPDATE wbcc_ticket SET 
                messageNonLu = '$nbMessage',
                DateFin = '$DateFin',
                dateMAJ = '$dateMAJ'
                WHERE EmailId = '$EmailId' ";
        $this->db->query($sql);
        return $this->db->execute();
    }

    public function getListTicketFiltre($item = '', $employeWbccColoture = '', $dateCreation  = '', $dateCreationOne  = '', $dateDebutCreation  = '', $dateFinCreation  = '', $dateCloture  = '', $dateClotureOne  = '', $dateDebutCloture  = '', $dateFinCloture  = '', $dateDebut = '', $dateDebutOne = '', $dateDebutDebut = '', $dateFinDebut = '', $dateFin = '', $dateFinOne = '', $dateDebutFin = '', $dateFinFin = '', $statut  = '', $categorie  = '', $depasse = 0, $traitement = '', $start = NULL, $lineNumber = NULL, $sousAstreinte = '', $idContactAffectF = '', $employeWbccDemClo = '', $idOpportunityF = '', $groupe = '', $idSite = 0, $idUtilisateur = 0, $numeroTicket = '', $numeroTicketOne = '', $numeroTicketDebut = '', $numeroTicketFin = '')
    {
        $sql = "SELECT a.*, z.idTicket as idParent, z.numero as numeroParent, k.idOpportunity, k.name, l.idContact as idContactAffec, 
                    l.nomContact as nomAffect, l.prenomContact as prenomAffect, m.prenomContact as prenomAuteurDemandeCloture, m.nomContact as nomAuteurDemandeCloture
                FROM wbcc_ticket a
                LEFT OUTER JOIN wbcc_ticket z ON(a.idTicketF=z.idTicket)
                LEFT OUTER JOIN wbcc_opportunity k ON(a.idOpportunityF=k.idOpportunity)
                LEFT OUTER JOIN wbcc_contact l ON(a.idContactAffectF=l.idContact)
                LEFT OUTER JOIN wbcc_contact m ON(a.idAuteurDemandeCloture=m.idContact)
            WHERE 1 ";

        if ($item != '') {
            $sql .= "
                AND ( a.numero LIKE '%$item%'
                OR a.intitule LIKE '%$item%'
                OR a.commentaire LIKE '%$item%'
                OR i.codeRHSR LIKE '%$item%' ) ";
        }

        if ($statut != '') {
            $sql .= " AND a.statut=$statut ";
        }
        if ($traitement != '') {
            if ($traitement == 0) { //  non traité
                $sql .= " AND a.statut=0 ";
            } elseif ($traitement == 5) { // Traité
                $sql .= " AND a.statut=1 AND a.isNoreply=0 ";
            } elseif ($traitement == 6) { // Traité
                $sql .= " AND a.statut=1 AND a.isNoreply=1 ";
            } elseif ($traitement == 1) { // Clôturé
                $sql .= " AND a.statut=2 ";
            } elseif ($traitement == 2) { // en attente
                $sql .= " AND a.statut=0 ";
            } elseif ($traitement == 3) { // en cours
                $sql .= " AND a.statut=1 ";
            } elseif ($traitement == 4) { // termine
                $sql .= " AND a.statut=2 ";
            }
        }

        /*if($employeWbccColoture !='' && $sousAstreinte==''){
            $sql.=" AND a.idAuteurCloture='$employeWbccColoture' ";
        }*/

        if ($employeWbccDemClo != '') {
            $sql .= " AND a.idAuteurDemandeCloture='$employeWbccDemClo' ";
        }

        if ($idContactAffectF != '') {
            if (strstr($idContactAffectF, '/')) {
                $idContactAffectFArray = explode('/', $idContactAffectF);
                $idContactAffectFVal = $idContactAffectFArray[0];
                if ($idContactAffectFVal != '') {
                    $sql .= " AND a.idContactAffectF='$idContactAffectFVal' ";
                }
                $sql .= " AND NOT ISNULL(a.idContactAffectF) AND a.idContactAffectF<>'' ";
            } else {
                $sql .= " AND a.idContactAffectF='$idContactAffectF' ";
            }
        }

        if ($categorie != '') {
            $sql .= " AND a.categorie=$categorie ";
        }

        if ($idOpportunityF != '') {
            $sql .= " AND a.idOpportunityF=$idOpportunityF ";
        }

        if ($depasse != 0) {
            if ($depasse == 1) {
                $dateNow = date('Y-m-d H:i:s');
                $sql .= " AND DATEDIFF(a.DateFin, '$dateNow') < 0 AND (a.statut = 1) ";
            } elseif ($depasse == 2) {
                $dateNow = date('Y-m-d H:i:s');
                $sql .= " AND DATEDIFF(a.DateDebut, '$dateNow') < 0 AND (a.statut = 0) ";
            }
        }

        if ($dateCreation != '') {
            if ($dateCreation == 0) {
                $now = date('Y-m-d');
                $sql .= " AND a.DateCreation LIKE '%$now%' ";
            } elseif ($dateCreation == 1) {
                if ($dateCreationOne != '') {
                    $sql .= " AND a.DateCreation LIKE '%$dateCreationOne%' ";
                }
            } elseif ($dateCreation == 2) {
                if ($dateDebutCreation != '' && $dateFinCreation != '') {
                    $sql .= " AND a.DateCreation >= '$dateDebutCreation' AND a.DateCreation <= '$dateFinCreation'";
                }
            }
        }

        if ($dateCloture != '') {
            if ($dateCloture == 0) {
                $now = date('Y-m-d');
                $sql .= " AND a.DateCloture LIKE '%$now%' ";
            } elseif ($dateCloture == 1) {
                if ($dateClotureOne != '') {
                    $sql .= " AND a.DateCloture LIKE '%$dateClotureOne%' ";
                }
            } elseif ($dateCloture == 2) {
                if ($dateDebutCloture != '' && $dateFinCloture != '') {
                    $sql .= " AND a.DateCloture >= '$dateDebutCloture' AND a.DateCloture <= '$dateFinCloture'";
                }
            }
        }

        if ($dateDebut != '') {
            if ($dateDebut == 0) {
                $now = date('Y-m-d');
                $sql .= " AND a.DateDebut LIKE '%$now%' ";
            } elseif ($dateDebut == 1) {
                if ($dateDebutOne != '') {
                    $sql .= " AND a.DateDebut LIKE '%$dateDebutOne%' ";
                }
            } elseif ($dateDebut == 2) {
                if ($dateDebutDebut != '' && $dateFinDebut != '') {
                    $sql .= " AND a.DateDebut >= '$dateDebutDebut' AND a.DateDebut <= '$dateFinDebut'";
                }
            }
        }

        if ($dateFin != '') {
            if ($dateFin == 0) {
                $now = date('Y-m-d');
                $sql .= " AND a.DateFin LIKE '%$now%' ";
            } elseif ($dateFin == 1) {
                if ($dateFinOne != '') {
                    $sql .= " AND a.DateFin LIKE '%$dateFinOne%' ";
                }
            } elseif ($dateFin == 2) {
                if ($dateDebutFin != '' && $dateFinFin != '') {
                    $sql .= " AND a.DateFin >= '$dateDebutFin' AND a.DateFin <= '$dateFinFin'";
                }
            }
        }

        if ($numeroTicket != '') {
            if ($numeroTicket == 1) {
                if ($numeroTicketOne != '') {
                    $sql .= " AND a.numero = '$numeroTicketOne' ";
                }
            } elseif ($numeroTicket == 2) {
                if ($numeroTicketDebut != '' && $numeroTicketFin != '') {
                    $sql .= " AND a.numero >= '$numeroTicketDebut' AND a.numero <= '$numeroTicketFin'";
                }
            }
        }

        if ($sousAstreinte != '') {
            $dateDebutSA = '';
            $dateFinSA = '';
            if ($dateCreation != '') {
                if ($dateCreation == 0) {
                    $dateDebutSA = date('Y-m-d 00:00:00');
                    $dateFinSA = date('Y-m-d 23:59:59');
                } elseif ($dateCreation == 1) {
                    if ($dateCreationOne != '') {
                        $dateDebutSA = "$dateCreationOne 00:00:00";
                        $dateFinSA = "$dateCreationOne 23:59:59";
                    }
                } elseif ($dateCreation == 2) {
                    if ($dateDebutCreation != '' && $dateFinCreation != '') {
                        $dateDebutSA = $dateDebutCreation;
                        $dateFinSA = $dateFinCreation;
                    }
                }
            }
            // SA=Sous Astreinte
            $PSAEmploye = $this->findLineProgrammeEmploye($employeWbccColoture, $dateDebutSA, $dateFinSA);
            if (! empty($PSAEmploye)) {
                $JSAEmploye = $PSAEmploye[0];
                //var_dump($PSAEmploye);  echo "<br/><br/>";
                $sql .= " AND ( ( a.DateCreation >= '$JSAEmploye->dateDebut' AND a.DateCreation <= '$JSAEmploye->dateFin' ) ";
                $cpt = 0;
                foreach ($PSAEmploye as $JSAEmploye) {
                    if ($cpt > 0) {
                        $sql .= " OR ( a.DateCreation >= '$JSAEmploye->dateDebut' AND a.DateCreation <= '$JSAEmploye->dateFin' ) ";
                    }
                    $cpt++;
                }
                $sql .= " ) ";
            } else {
                $sql .= " AND a.idTicket < 0 ";
            }
        }

        if ($groupe != '') {
            if ($groupe == 'me') {
                $sql .= " AND a.idOpportunityF IN ( SELECT aa.idOpportunity FROM wbcc_opportunity aa WHERE aa.gestionnaire=$idUtilisateur ) ";
            } elseif ($groupe == "site") {
                $sql .= " AND a.idOpportunityF IN ( SELECT idOpportunity FROM wbcc_opportunity aa WHERE aa.gestionnaire IN (SELECT ab.idUtilisateur FROM wbcc_utilisateur ab WHERE ab.idSiteF=$idSite) ) ";
            } elseif ($groupe == "noOp") {
                $sql .= " AND ( ISNULL(a.idOpportunityF) OR a.idOpportunityF='' ) ";
            }
        }

        $sql .= " ORDER BY a.dateMAJ DESC ";
        if (! is_null($start) && $start != '' &&  ! is_null($lineNumber) && $lineNumber != '') {
            $sql .= " LIMIT $start , $lineNumber ";
        }

        //echo $sql; die();
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getListTicketNoreply($categorie = '', $idContact = '', $dateDebut  = '', $dateFin  = '', $isNoreply = '')
    {
        $sql = "SELECT a.*, z.idTicket as idParent, z.numero as numeroParent, k.idOpportunity, k.name, l.idContact as idContactAffec, 
                    l.nomContact as nomAffect, l.prenomContact as prenomAffect, m.prenomContact as prenomAuteurDemandeCloture, m.nomContact as nomAuteurDemandeCloture
                FROM wbcc_ticket a
                LEFT OUTER JOIN wbcc_ticket z ON(a.idTicketF=z.idTicket)
                LEFT OUTER JOIN wbcc_opportunity k ON(a.idOpportunityF=k.idOpportunity)
                LEFT OUTER JOIN wbcc_contact l ON(a.idContactAffectF=l.idContact)
                LEFT OUTER JOIN wbcc_contact m ON(a.idAuteurDemandeCloture=m.idContact)
            WHERE 1 AND ( a.DateCreation >='$dateDebut' AND a.DateCreation <='$dateFin' ) 
                    /*AND ( a.DateDemandeCloture >='$dateDebut' AND a.DateDemandeCloture <='$dateFin' ) */
                    AND a.intitule NOT LIKE 'E-mail envoyé à%' ";

        $sql .= " AND a.statut=1 ";

        if ($isNoreply != '') {
            $sql .= " AND a.isNoreply=$isNoreply ";
            if ($isNoreply == 0) {
                $sql .= " AND a.isAnswered=0 ";
            }
        }

        if ($idContact != '') {
            $sql .= " AND a.idAuteurDemandeCloture='$idContact' ";
        }

        if ($categorie != '') {
            $sql .= " AND a.categorie=$categorie ";
        }

        $sql .= " ORDER BY a.dateMAJ DESC ";

        //echo $sql; die();
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function dashboardTicket($categorie = '', $dateDebut  = '', $dateFin  = '', $isNoreply = '', $statut = '', $isAnswered = '', $employeId = '')
    {
        $sql = "SELECT a.*, z.idTicket as idParent, z.numero as numeroParent, k.idOpportunity, k.name, l.idContact as idContactAffec, 
                    l.nomContact as nomAffect, l.prenomContact as prenomAffect, m.prenomContact as prenomAuteurDemandeCloture, m.nomContact as nomAuteurDemandeCloture
                FROM wbcc_ticket a
                LEFT OUTER JOIN wbcc_ticket z ON(a.idTicketF=z.idTicket)
                LEFT OUTER JOIN wbcc_opportunity k ON(a.idOpportunityF=k.idOpportunity)
                LEFT OUTER JOIN wbcc_contact l ON(a.idContactAffectF=l.idContact)
                LEFT OUTER JOIN wbcc_contact m ON(a.idAuteurDemandeCloture=m.idContact)
            WHERE 1 AND ( a.DateCreation >='$dateDebut' AND a.DateCreation <='$dateFin' )  ";


        if ($isNoreply != '') {
            $sql .= " AND a.isNoreply=$isNoreply  AND ( a.DateDemandeCloture >='$dateDebut' AND a.DateDemandeCloture <='$dateFin' )   ";
            if ($isNoreply == 0) {
                $sql .= " AND a.isAnswered=0 ";
            }
        }

        if ($categorie != '') {
            $sql .= " AND a.categorie=$categorie ";
        }

        if ($statut != '') {
            $sql .= " AND a.statut=$statut ";
        }

        if ($isAnswered != '') {
            $sql .= " AND a.isAnswered=$isAnswered AND ( a.DateDemandeCloture >='$dateDebut' AND a.DateDemandeCloture <='$dateFin' )   ";
        }

        if ($employeId != '') {
            $sql .= " AND a.idAuteurDemandeCloture=$employeId ";
        }

        $sql .= " ORDER BY a.dateMAJ DESC ";

        /*if($employeId==3109 && $isNoreply==1){
            echo $sql; die();
        }*/

        //echo $sql; die();
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getListEmailsFiltre($item = '', $entrantSortant = '', $nouveauSortant = '', $dateDeb = '', $dateFin = '', $idExpediteurInterne = '', $employeAstreinte = '')
    {
        $exclusEntrant = " SELECT b.idTicketEmailSvg  
            FROM wbcc_ticket_email_svg b 
            WHERE b.dateEmail >='$dateDeb' AND b.dateEmail <='$dateFin'
            AND (b.expediteur IN (SELECT c.senderEmailAdress FROM wbcc_ticket_contact_spamed c )
                OR b.objetEmail LIKE '%- Note Extranet%'
                OR b.expediteur LIKE '%@wbcc.fr%'
                ) 
        ";

        //echo $exclusEntrant; die();


        $sql = "SELECT a.*
                FROM wbcc_ticket_email_svg a
            WHERE 1 AND ( a.dateEmail >='$dateDeb' AND a.dateEmail <='$dateFin' )  ";
        if ($entrantSortant != '') {
            $sql .= " AND a.entrantSortant=$entrantSortant  ";
            if ($entrantSortant == 1) {
                $sql .= " AND a.idTicketEmailSvg NOT IN ( $exclusEntrant ) ";
            } elseif ($entrantSortant == 2) {
                $sql .= " AND a.idExpediteurInterne <> '' AND NOT ISNULL(idExpediteurInterne) ";
            }
        }

        if ($nouveauSortant != '') {
            $sql .= " AND a.nouveauSortant=$nouveauSortant ";
        }

        if ($idExpediteurInterne != '') {
            $sql .= " AND a.idExpediteurInterne=$idExpediteurInterne ";
        }

        /*if($entrantSortant == 1){
            echo $sql; die();
        }*/

        //echo $sql; die();
        $this->db->query($sql);
        return $this->db->resultSet();
    }


    public function getEmployeWbccTicket()
    {
        $sql = "SELECT * FROM wbcc_contact a
        JOIN wbcc_utilisateur b ON (a.idContact = b.idContactF)
        WHERE (role=1 OR role=2 OR role=8 OR role=3 OR role=25 OR isGestionnaire=1 ) 
ORDER BY a.nomContact, a.prenomContact  ASC";
        $this->db->query($sql);
        //echo $sql; die();
        return $this->db->resultSet();
    }

    public function checkAccessTicket($idContact)
    {
        $listEmploye = $this->getEmployeWbccTicket();
        $response = false;
        foreach ($listEmploye as $oneEmp) {
            if ($idContact == $oneEmp->idContact) {
                $response = true;
                break;
            }
        }
        if (! $response) {
            $sql = "SELECT * FROM wbcc_contact a
                    JOIN wbcc_utilisateur b ON (a.idContact = b.idContactF)
                    WHERE (b.isInterne = 1) AND a.idContact='$idContact' ";
            $this->db->query($sql);
            $res = $this->db->single();
            if (! empty($res)) {
                $response = true;
            }
        }
        return $response;
    }

    public function getTicketBayId($idTicket)
    {
        $sql = "  SELECT a.*, b.idTicket as idParent, 
                b.numero as numeroParent, k.idOpportunity, k.name, l.idContact as idContactAffec, 
                l.nomContact as nomAffect, l.prenomContact as prenomAffect, m.prenomContact as prenomAuteurDemandeCloture, m.nomContact as nomAuteurDemandeCloture
                FROM wbcc_ticket a 
                LEFT OUTER JOIN wbcc_ticket b ON(a.idTicketF=b.idTicket)
                LEFT OUTER JOIN wbcc_opportunity k ON(a.idOpportunityF=k.idOpportunity)
                LEFT OUTER JOIN wbcc_contact l ON(a.idContactAffectF=l.idContact)
                LEFT OUTER JOIN wbcc_contact m ON(a.idAuteurDemandeCloture=m.idContact)
                WHERE a.idTicket='$idTicket'";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->single();
        return $result;
    }

    public function getAllContact($item = '')
    {
        $sql = "SELECT * FROM wbcc_contact ORDER BY nomContact,prenomContact ASC  ";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getAllConpany($item = '')
    {
        $sql = " SELECT * FROM wbcc_company ORDER BY name ASC  ";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->resultSet();
        return $result;
    }

    public function getContactById($idContact)
    {
        $sql = " SELECT * FROM wbcc_contact WHERE idContact=$idContact";
        //echo $sql; die();
        $this->db->query($sql);
        $result = $this->db->single();
        return $result;
    }

    public function messageReaded($EmailId, $messageLu)
    {
        $sql = "UPDATE wbcc_ticket SET 
                messageNonLu = '0'
                WHERE EmailId = '$EmailId' ";
        $this->db->query($sql);
        $this->db->execute();
        //$messageLu-=1;
        $sql = "UPDATE wbcc_ticket SET 
                messageLu = '$messageLu'
                WHERE EmailId = '$EmailId' ";
        $this->db->query($sql);
        return $this->db->execute();
    }

    public function chekSpamedContact($email)
    {
        $sql = "SELECT * FROM wbcc_ticket_contact_spamed 
                WHERE senderEmailAdress=:Email AND etat=1";
        $this->db->query($sql);
        $this->db->bind('Email', str_replace("&&slash&&", "'", $email), null);
        $res = $this->db->single();
        if (! empty($res)) {
            return true;
        } else {
            return false;
        }
    }

    public function spammerContact($senderName, $senderEmailAdress, $auteurSpam, $idAuteurCloture)
    {
        $sql = "SELECT * FROM wbcc_ticket_contact_spamed 
                WHERE senderEmailAdress='$senderEmailAdress'";
        $this->db->query($sql);
        $res = $this->db->single();
        $spamedDate = date('Y-m-d H:i:s');
        if (! empty($res)) {
            $sql = "UPDATE wbcc_ticket_contact_spamed SET etat=1, spamedDate = NOW(), auteurSpam='$auteurSpam'
             WHERE senderEmailAdress='$senderEmailAdress'  ";
            $this->db->query($sql);
            $this->db->execute();
        } else {

            $sql = "INSERT INTO wbcc_ticket_contact_spamed(senderName,senderEmailAdress,spamedDate,etat,auteurSpam)
                        VALUES(:senderName,:senderEmailAdress,:spamedDate,:etat,:auteurSpam)";
            $this->db->query($sql);
            $this->db->bind('senderName', $senderName, null);
            $this->db->bind('senderEmailAdress', $senderEmailAdress, null);
            $this->db->bind('spamedDate', $spamedDate, null);
            $this->db->bind('etat', 1, null);
            $this->db->bind('auteurSpam', $auteurSpam, null);
            $this->db->execute();
        }

        $sql = "UPDATE wbcc_ticket SET statut=2, auteurCloture='$auteurSpam', idAuteurCloture='$idAuteurCloture', DateCloture='$spamedDate', commentaire= 'Le ticket a été fermé automatiquement en raison de spam du contact.'
             WHERE EmailSender='$senderEmailAdress' AND statut <>2 ";
        $this->db->query($sql);
        $this->db->execute();

        return true;
    }

    public function findLineProgramme($dateDebut, $dateFin)
    {
        $sql = "SELECT * FROM wbcc_ticket_programme
                WHERE dateDebut='$dateDebut' AND dateFin='$dateFin' ";
        $this->db->query($sql);
        $response = $this->db->single();

        return $response;
    }

    public function findLinesProgrammePeriode($dateDebut, $dateFin)
    {
        $sql = "SELECT * FROM wbcc_ticket_programme
                WHERE dateDebut>='$dateDebut' AND dateFin<='$dateFin' ";
        $this->db->query($sql);
        $response = $this->db->resultSet();

        return $response;
    }

    public function findLineProgrammeEmploye($idContact, $dateDebut = '', $dateFin = '')
    {
        $sql = "SELECT * FROM wbcc_ticket_programme
                WHERE employePrincipal='$idContact' ";
        if ($dateDebut != '') {
            $sql .= " AND dateDebut >= '$dateDebut' ";
        }

        if ($dateFin != '') {
            $sql .= " AND dateDebut <= '$dateFin' ";
        }
        $sql .= " ORDER BY dateDebut ASC ";
        $this->db->query($sql);
        $response = $this->db->resultSet();
        return $response;
    }

    public function getAllOpportunity()
    {
        $sql = "SELECT a.*, c.nomContact, c.prenomContact FROM wbcc_opportunity a
              LEFT OUTER JOIN wbcc_utilisateur b ON(b.matricule=a.gestionnaire)
              LEFT OUTER JOIN wbcc_contact c ON(c.idContact=b.idContactF)
              ORDER BY a.name DESC
            ";
        $this->db->query($sql);
        $response = $this->db->resultSet();
        return $response;
    }

    public function getEmployeAstreinte($date)
    {
        $dateFin = $date . ' 18:00:00';
        $timestamp = strtotime($dateFin);
        $timestampHier = $timestamp - 86400;
        $dateDebut = date("Y-m-d H:i:s", $timestampHier);

        $sql = "SELECT * FROM wbcc_ticket_programme 
              WHERE dateDebut>='$dateDebut' AND dateFin<='$dateFin' ";
        $this->db->query($sql);

        $response = $this->db->single();
        return ! empty($response) ? $response->employePrincipal : 0;
    }

    public function saveProgramme($dateDebut, $dateFin, $responsabilite, $employe, $auteur)
    {

        if ($responsabilite == 1) {
            $employePrincipal = $employe;
            $employeSecondaire = null;
        } else {
            $employePrincipal = null;
            $employeSecondaire = $employe;
        }
        $res = $this->findLineProgramme($dateDebut, $dateFin);

        if (! empty($res)) {
            if ($responsabilite == 1) {
                $sql = "UPDATE wbcc_ticket_programme SET employePrincipal=$employePrincipal, auteur = $auteur
                WHERE dateDebut='$dateDebut' AND dateFin='$dateFin' ";
            } else {
                $sql = "UPDATE wbcc_ticket_programme SET employeSecondaire=$employeSecondaire, auteur = $auteur
                WHERE dateDebut='$dateDebut' AND dateFin='$dateFin' ";
            }
            $this->db->query($sql);
            $this->db->execute();
        } else {
            $sql = "INSERT INTO wbcc_ticket_programme(dateDebut,dateFin,employePrincipal,employeSecondaire,auteur)
                        VALUES(:dateDebut,:dateFin,:employePrincipal,:employeSecondaire,:auteur)";
            $this->db->query($sql);
            $this->db->bind('dateDebut', $dateDebut, null);
            $this->db->bind('dateFin', $dateFin, null);
            $this->db->bind('employePrincipal', $employePrincipal, null);
            $this->db->bind('employeSecondaire', $employeSecondaire, null);
            $this->db->bind('auteur', $auteur, null);
            $this->db->execute();
        }
        $ticketToUpdateSousjascence = $this->getListTicketFiltre('', '', 2, '', $dateDebut, $dateFin, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, '', NULL, NULL, '', '', '', '', '', 0, 0);
        if ($responsabilite == 1) {
            foreach ($ticketToUpdateSousjascence as $tick) {
                $sql = "UPDATE wbcc_ticket SET sousJascentPrincipal='$employePrincipal'
                WHERE idTicket ='$tick->idTicket' ";
                $this->db->query($sql);
                $this->db->execute();
            }
        } else {
            foreach ($ticketToUpdateSousjascence as $tick) {
                $sql = "UPDATE wbcc_ticket SET sousJascentScondaire = '$employeSecondaire'
                WHERE idTicket ='$tick->idTicket' ";
                $this->db->query($sql);
                $this->db->execute();
            }
        }

        return true;
    }


    public function saveOpportunity($idTicket, $idOpportunityF)
    {
        $sql = "UPDATE wbcc_ticket SET idOpportunityF='$idOpportunityF'
        WHERE idTicket ='$idTicket' ";
        $this->db->query($sql);
        return $this->db->execute();
    }

    public function saveContactAffect($idTicket, $idContactAffectF)
    {
        $sql = "UPDATE wbcc_ticket SET idContactAffectF='$idContactAffectF'
        WHERE idTicket ='$idTicket' ";
        $this->db->query($sql);
        return $this->db->execute();
    }

    public function encryptMessage($message)
    {
        $encryptedMessage = openssl_encrypt(ENCRYPTY_SECURE_VAL . $message . ENCRYPTY_SECURE_VAL, ENCRYPTY_ALGORITHM, ENCRYPTY_KEY, 0, ENCRYPTY_IV);
        return str_replace('/', ENCRYPTY_REPLACE_SLASH, $encryptedMessage);
    }

    public function decrypetMessage($encryptedMessage)
    {
        $encryptedMessage = str_replace(ENCRYPTY_REPLACE_SLASH, '/', $encryptedMessage);
        $clear_vam = openssl_decrypt($encryptedMessage, ENCRYPTY_ALGORITHM, ENCRYPTY_KEY, 0, ENCRYPTY_IV);
        return substr($clear_vam, strlen(ENCRYPTY_SECURE_VAL), -strlen(ENCRYPTY_SECURE_VAL));
    }

    public function findContactByEmail($emailContact)
    {
        $sql = "SELECT * FROM wbcc_contact WHERE emailContact = :emailContact";
        $this->db->query($sql);
        $this->db->bind('emailContact', $emailContact, null);
        return $this->db->single();
    }

    public function findCompanyByEmail($email)
    {
        $sql = "SELECT * FROM wbcc_company WHERE email = 'email'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function fichExistContact($contact)
    {
        $sql = "  SELECT * FROM wbcc_contact
                WHERE (telContact = '" . $contact['telContact'] . "' AND (telContact <> '' OR telContact <> NULL OR telContact <> ' '))
                AND    (emailContact = '" . $contact['emailContact'] . "' AND (emailContact <> '' OR emailContact <> NULL OR emailContact <> ' '))
                AND    (nomContact = '" . $contact['nomContact'] . "' AND (nomContact <> '' OR nomContact <> NULL OR nomContact <> ' '))
                AND    (prenomContact = '" . $contact['prenomContact'] . "' AND (prenomContact <> '' OR prenomContact <> NULL OR prenomContact <> ' '))
               ";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function addContact($contact, $idAuteur)
    {
        $res = $this->fichExistContact($contact);
        if (empty($res)) {
            $numeroContact = 'CON' . date('dmYHis') . $idAuteur;
            $fullName = $contact['prenomContact'] . " " . $contact['nomContact'];
            $sql = " INSERT INTO wbcc_contact(
                numeroContact, 
                nomContact, 
                prenomContact, 
                fullName, 
                telContact, 
                emailContact, 
                adresseContact, 
                codePostalContact,   
                villeContact, 
                statutContact, 
                civiliteContact, 
                copieCNI, 
                copieCA, 
                copieTP, 
                commentaireCNI, 
                commentaireCA, 
                commentaireTP, 
                skype, 
                whatsapp
                ) 
            VALUES (
                :numeroContact, 
                :nomContact, 
                :prenomContact, 
                :fullName, 
                :telContact, 
                :emailContact, 
                :adresseContact, 
                :codePostalContact,   
                :villeContact, 
                :statutContact, 
                :civiliteContact, 
                :copieCNI, 
                :copieCA, 
                :copieTP, 
                :commentaireCNI, 
                :commentaireCA, 
                :commentaireTP, 
                :skype, 
                :whatsapp
            )";

            $this->db->query($sql);
            $this->db->bind('numeroContact', $numeroContact, null);
            $this->db->bind('nomContact', $contact['nomContact'], null);
            $this->db->bind('prenomContact', $contact['prenomContact'], null);
            $this->db->bind('fullName', $fullName, null);
            $this->db->bind('telContact', $contact['telContact'], null);
            $this->db->bind('emailContact', $contact['emailContact'], null);
            $this->db->bind('adresseContact', $contact['adresseContact'], null);
            $this->db->bind('codePostalContact', $contact['codePostalContact'], null);
            $this->db->bind('villeContact', $contact['villeContact'], null);
            $this->db->bind('statutContact', $contact['statutContact'], null);
            $this->db->bind('civiliteContact', $contact['civiliteContact'], null);
            $this->db->bind('copieCNI', $contact['copieCNI'], null);
            $this->db->bind('copieCA', $contact['copieCA'], null);
            $this->db->bind('copieTP', $contact['copieTP'], null);
            $this->db->bind('commentaireCNI', $contact['commentaireCNI'], null);
            $this->db->bind('commentaireCA', $contact['commentaireCA'], null);
            $this->db->bind('commentaireTP', $contact['commentaireTP'], null);
            $this->db->bind('skype', $contact['skype'], null);
            $this->db->bind('whatsapp', $contact['whatsapp'], null);
            $this->db->execute();

            $this->db->query("SELECT * FROM `wbcc_contact` WHERE numeroContact = '$numeroContact' ");
            $response = $this->db->single();
            return array(true, $response);
        } else {
            return array(false, $res[0]);
        }
    }

    public function saveDocOp($opportunity, $attachments, $createDate)
    {
        $index = 0;
        foreach ($attachments as $attachment) {
            if ($attachment->name != 'undefined') {
                $index++;
                $CDLateDate = explode(' ', $createDate)[0];
                $CDLateHeure = explode(' ', $createDate)[1];
                $CDLate = str_replace('-', '', $CDLateDate);
                $CD = substr($CDLate, 6, 2) . substr($CDLate, 4, 2) . substr($CDLate, 0, 4) . str_replace(':', '', $CDLateHeure);

                $attachmentName = $attachment->getName();
                $numeroDocument = "DOC" . $CD . $opportunity->idOpportunity . $index;
                $nomDocument = $opportunity->name . '_' . $attachmentName;
                $urlDocument = $opportunity->name . '_' . $attachmentName;

                $sql = " SELECT * FROM wbcc_document WHERE urlDocument=:urlDocument AND createDate=:createDate ";
                $this->db->query($sql);
                $this->db->bind('urlDocument', $nomDocument, null);
                $this->db->bind('createDate', $createDate, null);
                $line = $this->db->single();

                if (empty($line)) {

                    // 1 - suavegarder le fichier dans document OP
                    if (!file_exists("../../documents/opportunite/" . $nomDocument)) {
                        file_put_contents("../../documents/opportunite/" . $nomDocument, $attachment->getContent());
                    }

                    // 2- enregistrer la référence dans table document et retourner l'id de la ligne inséré
                    $this->db->query("INSERT INTO wbcc_document (numeroDocument, nomDocument,urlDocument, createDate,  source, publie) 
                                    VALUES (:numeroDocument, :nomDocument, :urlDocument, :createDate,  :source, :publie)");

                    $this->db->bind("numeroDocument", $numeroDocument, null);
                    $this->db->bind("nomDocument", $nomDocument, null);
                    $this->db->bind("urlDocument", $urlDocument, null);
                    $this->db->bind("createDate", $createDate, null);
                    $this->db->bind("source", 'EMAIL', null);
                    $this->db->bind("publie", 1, null);
                    $this->db->execute();

                    // 3- enregistrer dans la table domument OP

                    $this->db->query("SELECT * FROM wbcc_opportunity_document WHERE numeroOpportunityF =:numeroOpportunityF AND numeroDocumentF =:numeroDocumentF");
                    $this->db->bind("numeroOpportunityF", $opportunity->numeroOpportunity, null);
                    $this->db->bind("numeroDocumentF", $numeroDocument, null);
                    if ($this->db->single() == false) {
                        $document = $this->getItemByGuid("wbcc_document", "numeroDocument", $numeroDocument);
                        if ($opportunity  && $document) {
                            $this->db->query("SELECT * FROM wbcc_opportunity_document WHERE idOpportunityF =:idOpportunity AND idDocumentF =:idDocument");
                            $this->db->bind("idOpportunity", $opportunity->idOpportunity, null);
                            $this->db->bind("idDocument", $document->idDocument, null);
                            if ($this->db->single() == false) {
                                $this->db->query("INSERT INTO wbcc_opportunity_document (numeroOpportunityF, numeroDocumentF, idDocumentF, idOpportunityF) VALUES (:numeroOpportunityF, :numeroDocumentF,  :idDocumentF, :idOpportunityF)");
                                $this->db->bind("numeroDocumentF", $numeroDocument, null);
                                $this->db->bind("numeroOpportunityF", $opportunity->numeroOpportunity, null);
                                $this->db->bind("idDocumentF", $document->idDocument, null);
                                $this->db->bind("idOpportunityF", $opportunity->idOpportunity, null);
                                $this->db->execute();
                            }
                        }
                    }
                }
            }
        }
    }


    public function getItemByGuid($nomTable, $col, $value)
    {
        $this->db->query("SELECT * FROM $nomTable WHERE $col = :numero");
        $this->db->bind("numero", $value, null);
        $data = $this->db->single();
        return $data;
    }


    /********************** NEW **************************** */

    public function getSiteUser($idSite)
    {
        $sql = "SELECT * FROM wbcc_site WHERE idSite=:idSite ";
        $this->db->query($sql);
        $this->db->bind("idSite", $idSite, null);
        $data = $this->db->single();
        return $data;
    }

    public function getSites()
    {
        $sql = "SELECT * FROM wbcc_site WHERE etatSite=1 ORDER BY nomSite ";
        $this->db->query($sql);
        $data = $this->db->resultSet();
        return $data;
    }
}
