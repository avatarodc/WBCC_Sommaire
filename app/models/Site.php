<?php

class Site extends Model
{
    public function save($libelle)
    {
        $piece = $this->findBy("libellePiece", $libelle);
        if ($piece == null) {
            $this->db->query("INSERT INTO piece(libellePiece) VALUES ('$libelle')");
            if ($this->db->execute()) {
                $piece = $this->findBy("libellePiece", $libelle);
                return $piece->idPiece;
            }
            return 0;
        }
        return $piece->idPiece;
    }

    public function getAllSites($orderBy = '')
    {
        $this->db->query("SELECT * FROM wbcc_site  WHERE etatSite=1 ORDER BY nomSite ASC");
        return $this->db->resultSet();
    }

    public function findById($id)
    {
        $this->db->query("SELECT * FROM wbcc_site WHERE idSite=$id");
        return $this->db->single();
    }

    // Ajout de la fonction findByNomSite() A CHANGER POUR ESPOIR

    public function findByNomSite($nom)
    {
        $this->db->query("SELECT * FROM wbcc_site WHERE nomSite='$nom'");
        return $this->db->single();
    }
}