<?php

/**
 * Created by PhpStorm.
 * User: hp
 * Date: 26/12/2019
 * Time: 09:00
 */

class Parametres extends Model
{

    public function getListeDeroulante($tableName)
    {
        $this->db->query("
        SELECT * FROM $tableName 
        ");
        return $this->db->resultSet();
    }

    public function getParametres()
    {
        $this->db->query("
        SELECT * FROM wbcc_parametres LIMIT 1
        ");
        return $this->db->single();
    }

    public function updateParametre($nomColonne, $val)
    {
        $this->db->query("
            UPDATE wbcc_parametres
            SET $nomColonne=$val
        ");
        return $this->db->execute();
    }

    public function updateLiens()
    {
        $liens = $_SESSION['connectedUser']->liens;
        $this->db->query("
            UPDATE wbcc_parametres
            SET liens='$liens'
        ");
        return $this->db->execute();
    }



    public function updateUsersLiens($idRole, $route)
    {
        $liens = $_SESSION['connectedUser']->liens;
        $this->db->query("
            UPDATE eic_roles
            SET accessibilite='$route'
            WHERE idRole=$idRole
        ");
        return $this->db->execute();
    }

    public function savePdf($mntArgPoche, $seuil)
    {
        if ($mntArgPoche >= 0 && $seuil >= 0) {
            $this->db->query("UPDATE eic_parametres SET montantSeuilArgPoche=$seuil, mntArgPocheDefaut=$mntArgPoche");
            $this->db->execute();
        }
    }

    public function setNumeroTicket()
    {
        $this->db->query("
        SELECT * FROM wbcc_parametre LIMIT 1
        ");
        return $this->db->single();
    }

    public function getGestionnaireByCode($code)
    {
        $this->db->query("SELECT * FROM wbcc_contact c, wbcc_utilisateur u  WHERE idContact = idContactF AND matricule='$code' LIMIT 1");
        return $this->db->single();
    }
}