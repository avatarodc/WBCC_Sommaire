<?php

class Company extends Model
{
    public function getCompaniesBySuperArtisan($idArtisanDevis)
    {
        $this->db->query("SELECT * FROM wbcc_company WHERE idArtisanDevisF = :idArtisanDevis ORDER BY idCompany DESC");
        $this->db->bind("idArtisanDevis", $idArtisanDevis, null);
        return $this->db->resultSet();
    }

    public function getCompaniesByIdStatut($idStatut)
    {
        $this->db->query("SELECT * FROM wbcc_company WHERE category LIKE '%$idStatut%' || sousCategorieDO LIKE '%$idStatut%' ORDER BY idCompany DESC");
        // $this->db->bind("idStatut", $idStatut, null);
        return $this->db->resultSet();
    }

    public function getAllCompanies()
    {
        $this->db->query("SELECT * FROM wbcc_company ORDER BY idCompany DESC");
        return $this->db->resultSet();
    }

    //FONCTION 2 USING BY ADDING DO
    public function updateCompany2(
        $idSociete,
        $nomSociete,
        $enseigneSociete,
        $telephoneSociete,
        $emailSociete,
        $siteWebSociete,
        $idStatut,
        $adresseSociete,
        $codePostalSociete,
        $villeSociete,
        $departementSociete,
        $regionSociete,
        $rcsSociete,
        $villeRcsSociete,
        $siretSociete,
        $codeNafSociete,
        $activiteSociete,
        $effectifSociete,
        $service,
        $categorieDO,
        $sousCategorieDO
    ) {
        $this->db->query("
            UPDATE wbcc_company SET name = :nomSociete, enseigne=:enseigneSociete, businessPhone=:telephoneSociete, 
            email=:emailSociete, webaddress=:siteWebSociete, businessLine1 = :adresseSociete, businessPostalCode = :codePostalSociete, 
            businessCity=:villeSociete, businessState=:departementSociete,region = :regionSociete, numeroRCS=:rcsSociete, 
            villeRCS=:villeRcsSociete, numeroSiret = :siretSociete, siccode = :codeNafSociete, secteur=:service, numEmployees=:effectifSociete, 
            businessCountryName='France',industry=:activiteSociete, category=:idStatut, categorieDO=:categorieDO, sousCategorieDO=:sousCategorieDO WHERE idCompany = $idSociete");
        $this->db->bind('nomSociete', $nomSociete, null);
        $this->db->bind('enseigneSociete', $enseigneSociete, null);
        $this->db->bind('telephoneSociete', $telephoneSociete, null);
        $this->db->bind('emailSociete', $emailSociete, null);
        $this->db->bind('siteWebSociete', $siteWebSociete, null);
        $this->db->bind('adresseSociete', $adresseSociete, null);
        $this->db->bind('codePostalSociete', $codePostalSociete, null);
        $this->db->bind('villeSociete', $villeSociete, null);
        $this->db->bind('departementSociete', $departementSociete, null);
        $this->db->bind('regionSociete', $regionSociete, null);
        $this->db->bind('rcsSociete', $rcsSociete, null);
        $this->db->bind('villeRcsSociete', $villeRcsSociete, null);
        $this->db->bind('siretSociete', $siretSociete, null);
        $this->db->bind('codeNafSociete', $codeNafSociete, null);
        $this->db->bind('service', $service, null);
        $this->db->bind('effectifSociete', $effectifSociete, null);
        $this->db->bind('activiteSociete', $activiteSociete, null);
        $this->db->bind('idStatut', $idStatut, null);
        $this->db->bind('categorieDO', $categorieDO, null);
        $this->db->bind('sousCategorieDO', $sousCategorieDO, null);
        $this->db->execute();
    }

    public function addCompany2(
        $numero,
        $nomSociete,
        $enseigneSociete,
        $telephoneSociete,
        $emailSociete,
        $siteWebSociete,
        $idStatut,
        $adresseSociete,
        $codePostalSociete,
        $departementSociete,
        $regionSociete,
        $villeSociete,
        $paysSociete,
        $rcsSociete,
        $villeRcsSociete,
        $siretSociete,
        $codeNafSociete,
        $activiteSociete,
        $effectifSociete,
        $categorieDO,
        $sousCategorieDO
    ) {
        $this->db->query("
           INSERT INTO wbcc_company 
           (numeroCompany, name, enseigne, businessPhone,email,
           webAddress, category, businessLine1, businessPostalCode, businessState, region, businessCity, 
           businessCountryName,numeroRCS,villeRCS,numeroSiret,siccode,industry,numEmployees, categorieDO, sousCategorieDO) 
           VALUES (:numero,:nomSociete, :enseigneSociete, :telephoneSociete,:emailSociete, :siteWebSociete, :idStatut, 
           :adresseSociete, :codePostalSociete, :departementSociete,:regionSociete, :villeSociete, 
           :paysSociete,:rcsSociete,:villeRcsSociete,:siretSociete,:codeNafSociete,:activiteSociete,:effectifSociete, :categorieDO, :sousCategorieDO)");
        $this->db->bind('numero', $numero, null);
        $this->db->bind('nomSociete', $nomSociete, null);
        $this->db->bind('enseigneSociete', $enseigneSociete, null);
        $this->db->bind('telephoneSociete', $telephoneSociete, null);
        $this->db->bind('emailSociete', $emailSociete, null);
        $this->db->bind('siteWebSociete', $siteWebSociete, null);
        $this->db->bind('idStatut', $idStatut, null);
        $this->db->bind('adresseSociete', $adresseSociete, null);
        $this->db->bind('codePostalSociete', $codePostalSociete, null);
        $this->db->bind('departementSociete', $departementSociete, null);
        $this->db->bind('regionSociete', $regionSociete, null);
        $this->db->bind('villeSociete', $villeSociete, null);
        $this->db->bind('paysSociete', $paysSociete, null);
        $this->db->bind('rcsSociete', $rcsSociete, null);
        $this->db->bind('villeRcsSociete', $villeRcsSociete, null);
        $this->db->bind('siretSociete', $siretSociete, null);
        $this->db->bind('codeNafSociete', $codeNafSociete, null);
        $this->db->bind('activiteSociete', $activiteSociete, null);
        $this->db->bind('effectifSociete', $effectifSociete, null);
        $this->db->bind('categorieDO', $categorieDO, null);
        $this->db->bind('sousCategorieDO', $sousCategorieDO, null);
        if ($this->db->execute()) {
            $comp = $this->findByNumero($numero);
            return $comp->idCompany;
        } else {
            return "0";
        }
    }

    public function findByContact($idContact)
    {
        $this->db->query("SELECT * FROM wbcc_contact_company, wbcc_company WHERE idCompanyF = idCompany AND idContactF = :idContact LIMIT 1");
        $this->db->bind("idContact", $idContact, null);
        return $this->db->single();
    }

    public function updateCompany(
        $idSociete,
        $nomSociete,
        $enseigneSociete,
        $telephoneSociete,
        $emailSociete,
        $siteWebSociete,
        $categorieSociete,
        $adresseSociete,
        $codePostalSociete,
        $villeSociete,
        $departementSociete,
        $regionSociete,
        $rcsSociete,
        $villeRcsSociete,
        $siretSociete,
        $codeNafSociete,
        $activiteSociete,
        $effectifSociete,
        $service
    ) {
        $this->db->query("
            UPDATE wbcc_company SET name = :nomSociete, enseigne=:enseigneSociete, businessPhone=:telephoneSociete,
             email=:emailSociete, webaddress=:siteWebSociete, businessLine1 = :adresseSociete, 
             businessPostalCode = :codePostalSociete, businessCity=:villeSociete, businessState=:departementSociete,
             region = :regionSociete, numeroRCS=:rcsSociete, villeRCS=:villeRcsSociete, numeroSiret = :siretSociete, 
             siccode = :codeNafSociete, secteur=:service, numEmployees=:effectifSociete, businessCountryName='France',
             industry=:activiteSociete, category=:categorieSociete WHERE idCompany = $idSociete");

        $this->db->bind('nomSociete', $nomSociete, null);
        $this->db->bind('enseigneSociete', $enseigneSociete, null);
        $this->db->bind('telephoneSociete', $telephoneSociete, null);
        $this->db->bind('emailSociete', $emailSociete, null);
        $this->db->bind('siteWebSociete', $siteWebSociete, null);
        $this->db->bind('adresseSociete', $adresseSociete, null);
        $this->db->bind('codePostalSociete', $codePostalSociete, null);
        $this->db->bind('villeSociete', $villeSociete, null);
        $this->db->bind('departementSociete', $departementSociete, null);
        $this->db->bind('regionSociete', $regionSociete, null);
        $this->db->bind('rcsSociete', $rcsSociete, null);
        $this->db->bind('villeRcsSociete', $villeRcsSociete, null);
        $this->db->bind('siretSociete', $siretSociete, null);
        $this->db->bind('codeNafSociete', $codeNafSociete, null);
        $this->db->bind('service', $service, null);
        $this->db->bind('effectifSociete', $effectifSociete, null);
        $this->db->bind('activiteSociete', $activiteSociete, null);
        $this->db->bind('categorieSociete', $categorieSociete, null);
        $this->db->execute();
    }

    public function addCompany(
        $numero,
        $nomSociete,
        $enseigneSociete,
        $telephoneSociete,
        $emailSociete,
        $siteWebSociete,
        $categorieSociete,
        $adresseSociete,
        $codePostalSociete,
        $departementSociete,
        $regionSociete,
        $villeSociete,
        $paysSociete,
        $rcsSociete,
        $villeRcsSociete,
        $siretSociete,
        $codeNafSociete,
        $activiteSociete,
        $effectifSociete
    ) {
        $this->db->query("
           INSERT INTO wbcc_company 
           (numeroCompany, name, enseigne, businessPhone,email,
           webAddress, category, businessLine1, businessPostalCode, businessState, region, businessCity, 
           businessCountryName,numeroRCS,villeRCS,numeroSiret,siccode,industry,numEmployees) 
           VALUES (:numero,:nomSociete, :enseigneSociete, :telephoneSociete,:emailSociete, 
           :siteWebSociete, :categorieSociete, :adresseSociete, :codePostalSociete, 
           :departementSociete,:regionSociete, :villeSociete, :paysSociete,:rcsSociete,
           :villeRcsSociete,:siretSociete,:codeNafSociete,:activiteSociete,:effectifSociete)");

        $this->db->bind('numero', $numero, null);
        $this->db->bind('nomSociete', $nomSociete, null);
        $this->db->bind('enseigneSociete', $enseigneSociete, null);
        $this->db->bind('telephoneSociete', $telephoneSociete, null);
        $this->db->bind('emailSociete', $emailSociete, null);
        $this->db->bind('siteWebSociete', $siteWebSociete, null);
        $this->db->bind('categorieSociete', $categorieSociete, null);
        $this->db->bind('adresseSociete', $adresseSociete, null);
        $this->db->bind('codePostalSociete', $codePostalSociete, null);
        $this->db->bind('departementSociete', $departementSociete, null);
        $this->db->bind('regionSociete', $regionSociete, null);
        $this->db->bind('villeSociete', $villeSociete, null);
        $this->db->bind('paysSociete', $paysSociete, null);
        $this->db->bind('rcsSociete', $rcsSociete, null);
        $this->db->bind('villeRcsSociete', $villeRcsSociete, null);
        $this->db->bind('siretSociete', $siretSociete, null);
        $this->db->bind('codeNafSociete', $codeNafSociete, null);
        $this->db->bind('activiteSociete', $activiteSociete, null);
        $this->db->bind('effectifSociete', $effectifSociete, null);

        if ($this->db->execute()) {
            $comp = $this->findByNumero($numero);
            return $comp->idCompany;
        } else {
            return "0";
        }
    }

    public function findByNumero($numero)
    {
        $this->db->query("SELECT * FROM wbcc_company WHERE numeroCompany = :numeroCompany LIMIT 1");
        $this->db->bind('numeroCompany', $numero, null);
        if ($this->db->single()) {
            return $this->db->single();
        } else {
            return null;
        }
    }

    public function findByName($name)
    {
        $this->db->query("SELECT * FROM wbcc_company WHERE name = :name LIMIT 1");
        $this->db->bind('name', $name, null);
        if ($this->db->single()) {
            return $this->db->single();
        } else {
            return null;
        }
    }

    public function findById($id)
    {
        $this->db->query("SELECT * FROM wbcc_company WHERE idCompany = :id");
        $this->db->bind('id', $id, null);
        if ($this->db->single()) {
            return $this->db->single();
        } else {
            return null;
        }
    }

    public function insertOpportunityCompany($idOpp, $idCompany)
    {
        if ($this->existCompanyOpportunity($idOpp, $idCompany) == null) {
            $this->db->query("
           INSERT INTO  wbcc_company_opportunity
           (idOpportunityF, idCompanyF) 
           VALUES ($idOpp,$idCompany)");
            $this->db->execute();
        }
    }


    public function existCompanyOpportunity($idOpp, $idCompany)
    {
        $this->db->query("SELECT * FROM wbcc_company_opportunity WHERE idCompanyF = $idCompany AND idOpportunityF = $idOpp");
        if ($this->db->single()) {
            return $this->db->single();
        } else {
            return null;
        }
    }

    public function deleteCompanyToOpportunity($id, $idOp)
    {
        $this->db->query("
            DELETE FROM wbcc_company_opportunity
            WHERE idCompanyF =:id AND idOpportunityF =:idOp");

        $this->db->bind("idOp", $idOp, null);
        $this->db->bind("id", $id, null);
        $this->db->execute();
    }

    public function getCompaniesByOpportunity($id, $idCompany = 0)
    {
        if ($idCompany == 0) {
            $this->db->query("SELECT * FROM wbcc_company, wbcc_company_opportunity
            WHERE idCompany = idCompanyF
            AND idOpportunityF = :idOpportunityF");
        } else {
            $this->db->query("SELECT * FROM wbcc_company, wbcc_company_opportunity
            WHERE idCompany != :idCompany AND idCompany = idCompanyF
            AND idOpportunityF = :idOpportunityF");
            $this->db->bind("idCompany", $idCompany, null);
        }
        $this->db->bind("idOpportunityF", $id, null);

        return $this->db->resultSet();
    }

    public function getCompaniesByOp($id)
    {
        $this->db->query("SELECT * FROM wbcc_company, wbcc_company_opportunity
        WHERE  idCompany = idCompanyF
        AND idOpportunityF = $id");
        return $this->db->resultSet();
    }

    public function getDO()
    {
        $this->db->query("SELECT * FROM wbcc_company
        WHERE LOWER(category) LIKE '%donneur%' OR LOWER(category) LIKE '%syndic%'");
        return $this->db->resultSet();
    }

    public function getDOByCategorie($categorie)
    {
        $this->db->query("SELECT * FROM wbcc_company
        WHERE  categorieDO LIKE '%$categorie%' AND LOWER(category) LIKE '%donneur%' ");
        return $this->db->resultSet();
    }

    public function getDOCommercial($idCommercial)
    {
        $this->db->query("SELECT * FROM wbcc_company, wbcc_contact_company
        WHERE idCompany=idCompanyF AND idContactF=$idCommercial AND (LOWER(category) LIKE '%donneur%' OR LOWER(category) LIKE '%syndic%' OR LOWER(category) LIKE '%agence%')");
        $data = $this->db->resultSet();
        $tab = [];
        foreach ($data as $key => $do) {
            $id = $do->idCompany;

            $this->db->query("SELECT * FROM wbcc_opportunity o, wbcc_contact_opportunity co, wbcc_company_opportunity cop 
            WHERE o.idOpportunity = co.idOpportunityF AND co.idContactF = $idCommercial AND cop.idCompanyF=$id AND cop.idOpportunityF = o.idOpportunity AND (status='Open' OR status='Inactive') AND demandeCloture=0 AND demandeValidation=1 ORDER BY name DESC");
            $ops = $this->db->resultSet();

            $tab[] = ["do" => $do, "nb" => sizeof($ops)];
        }
        return $tab;
    }

    public function deleteCompany($id)
    {
        $this->db->query("
            DELETE FROM wbcc_company
            WHERE idCompany =:id");
        $this->db->bind("id", $id, null);
        $this->db->execute();
    }
}
