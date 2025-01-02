<?php

class Contact extends Model
{

    public function  getContactByGuidCompany($guid)
    {
        $this->db->query("SELECT * FROM wbcc_contact, wbcc_contact_company, wbcc_company WHERE idContact = idContactF AND idCompany=idCompanyF AND numeroCompany LIKE '%$guid%' ");
        // $this->db->bind(":guid", $guid, null);
        return $this->db->resultSet();
    }

    public function getAllContacts()
    {
        $this->db->query("SELECT * FROM wbcc_contact ORDER BY idContact DESC");
        return $this->db->resultSet();
    }

    public function getInterlocuteurCie($idOpportunity, $idCompany)
    {
        $this->db->query("SELECT * FROM `wbcc_contact` c WHERE c.idContact IN (SELECT idContactF FROM wbcc_contact_opportunity co WHERE co.idOpportunityF = $idOpportunity) AND c.idContact IN (SELECT idContactF FROM wbcc_contact_company cc WHERE cc.idCompanyF = '$idCompany') LIMIT 1");
        return $this->db->single();
    }

    public function  getPersonnelByCompany($id)
    {
        $this->db->query("SELECT * FROM wbcc_contact, wbcc_contact_company WHERE idContact = idContactF AND idCompanyF = $id ");
        return $this->db->resultSet();
    }

    //
    public function  getContactByOpportunity($id)
    {
        $this->db->query("SELECT * FROM wbcc_contact, wbcc_contact_opportunity WHERE idContact = idContactF AND idOpportunityF = $id ");
        return $this->db->resultSet();
    }

    public function save($numero, $sexe, $prenom, $nom, $tel, $email,  $dateNaissance, $pseudoSkype, $telWhatsapp, $idStatut)
    {
        if ($dateNaissance == "") {
            $dateNaissance = null;
        }
        $this->db->query("
          INSERT INTO wbcc_contact(numeroContact, civiliteContact, prenomContact, nomContact, fullName, telContact, emailContact, dateNaissance, skype, whatsapp, statutContact, referredBy, editDate, createDate, isUser, etatContact) VALUES (:numeroContact, :civilite, :firstName, :lastName, :fullName, :businessPhone, :businessEmail, :birthDate, :skype, :whatsapp, :category, :referredBy, :editDate, :createDate, :isUser, :etatContact)");

        $this->db->bind("numeroContact", $numero, null);
        $this->db->bind("civilite", $sexe, null);
        $this->db->bind("firstName", $prenom, null);
        $this->db->bind("lastName", $nom, null);
        $this->db->bind("fullName", $prenom . ' ' . $nom, null);
        $this->db->bind("businessPhone", $tel, null);
        $this->db->bind("businessEmail", $email, null);
        $this->db->bind("birthDate", $dateNaissance, null);
        $this->db->bind("skype", $pseudoSkype, null);
        $this->db->bind("whatsapp", $telWhatsapp, null);
        $this->db->bind("category", "$idStatut", null);
        $this->db->bind("referredBy", "Extranet WBCC", null);
        $this->db->bind("editDate", date('Y-m-d h:i:s'), null);
        $this->db->bind("createDate",  date('Y-m-d h:i:s'), null);
        $this->db->bind("isUser", 1, null);
        $this->db->bind("etatContact", 1, null);
        if ($this->db->execute()) {
            return $this->findByNumero($numero);
        }
        return 0;
    }


    public function findById($idContact)
    {
        $this->db->query("SELECT * FROM wbcc_contact WHERE idContact = $idContact");
        return $this->db->single();
    }

    public function findByNumero($numero)
    {
        $this->db->query("SELECT * FROM wbcc_contact WHERE numeroContact = '$numero'");
        return $this->db->single();
    }

    public function getExperiences($idCompte)
    {
        $this->db->query("SELECT * FROM ewbcc_experience WHERE idCompteF = $idCompte");
        return $this->db->resultSet();
    }

    public function getFormations($idCompte)
    {
        $this->db->query("SELECT * FROM ewbcc_formation WHERE idCompteF = $idCompte");
        return $this->db->resultSet();
    }

    public function getCompetences($idCompte)
    {
        $this->db->query("SELECT * FROM ewbcc_competence WHERE idCompteF = $idCompte");
        return $this->db->resultSet();
    }

    public function getLangues($idCompte)
    {
        $this->db->query("SELECT * FROM ewbcc_langue, ewbcc_compte_langue WHERE idCompteF = $idCompte AND idLangueF = idLangue");
        return $this->db->resultSet();
    }

    public function getDivers($idCompte)
    {
        $this->db->query("SELECT * FROM ewbcc_divers WHERE idCompteF = $idCompte");
        return $this->db->resultSet();
    }
    /***********   ESPOIR  ********* */


    public function getInterlocuteurExpertCie($idOpportunity)
    {
        $this->db->query("SELECT * FROM wbcc_contact c 
                            JOIN wbcc_opportunity op ON(c.idContact=op.idExpertCompanyF)
                            WHERE op.idOpportunity =$idOpportunity ");
        return $this->db->single();
    }
}
