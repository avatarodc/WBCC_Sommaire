<?php

class Historique extends Model
{
    public function save($action)
    {

        $nomComplet = $_SESSION['connectedUser']->fullName;
        $idUtilisateurF = $_SESSION['connectedUser']->idUtilisateur;
        $this->db->query("INSERT INTO wbcc_historique(action, nomComplet, idUtilisateurF) 
                        VALUES('$action', '$nomComplet', $idUtilisateurF) ");
        if ($this->db->execute()) {
            return "1";
        }
        return "0";
    }

    public function getAllHistorique()
    {
        $this->db->query("SELECT * FROM wbcc_historique  ORDER BY dateAction DESC");

        return $this->db->resultSet();
    }

    public function getHistoriqueByUser($idUser)
    {
        $this->db->query("SELECT * FROM wbcc_historique WHERE idUtilisateurF = $idUser  ORDER BY dateAction DESC");

        return $this->db->resultSet();
    }
}
