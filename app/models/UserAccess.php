<?php

/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 11/12/2019
 * Time: 5:23 PM
 */

class UserAccess extends Model
{
    public function findByLien($lien)
    {
        $this->db->query("
            SELECT * FROM wbcc_user_access WHERE lien=:lien");
        $this->db->bind('lien', $lien, null);
        return $this->db->single();
    }

    public function addLien($lien, $idUser, $nomUser, $date)
    {
        $this->db->query("INSERT INTO wbcc_user_access(lien,idUserF,nomUser,dateAccess) 
        VALUES (:lien,:idUserF,:nomUser, :dateAccess)");
        $this->db->bind('lien', $lien, null);
        $this->db->bind('idUserF', "$idUser", null);
        $this->db->bind('nomUser', "$nomUser", null);
        $this->db->bind('dateAccess', $date, null);
        return $this->db->execute();
    }


    public function deleteByUser($idUser)
    {
        $this->db->query("DELETE FROM wbcc_user_access WHERE idUserF = :idUserF");
        $this->db->bind('idUserF', $idUser, null);
        return $this->db->execute();
    }
}
