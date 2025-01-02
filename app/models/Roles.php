<?php

class Roles extends Model
{

    public function findByID($id)
    {
        $this->db->query("SELECT * FROM wbcc_roles WHERE idRole = $id");
        return $this->db->single();
    }

    public function findRoleByLink($link)
    {
        $this->db->query("SELECT * FROM wbcc_roles WHERE lower(link)=lower('$link')");
        return $this->db->single();
    }

    public function saveRole($libelle, $ctrl)
    {
        $this->db->query("
            SELECT * FROM wbcc_roles WHERE libelleRole='$libelle'
        ");
        $this->db->execute();
        if ($this->db->rowCount() == 0) {
            $this->db->query("
                INSERT INTO wbcc_roles(libelleRole,etatRole,accessibilite,link)
                VALUES ('$libelle',1,'','$ctrl')
            ");
            return $this->db->execute();
        }
    }

    public function changeRoleState($role, $etat)
    {
        $etat = $etat == -1 ? 0 : $etat;
        $this->db->query("
                UPDATE wbcc_roles SET etatRole=$etat WHERE libelleRole='$role'
        ");
        return $this->db->execute();
    }

    public function updateRole($nomG, $nomC, $id)
    {
        $this->db->query("
                UPDATE wbcc_roles SET libelleRole = '$nomG', link = '$nomC' WHERE idRole = $id");
        return $this->db->execute();
    }

    public function getAllByEtat($etat = 1)
    {
        $this->db->query("
        SELECT * FROM wbcc_roles ORDER BY libelleRole
        ");
        return $this->db->resultSet();
    }

    public function getRolesByType($type)
    {
        if ($type == "particulier") {
            $this->db->query("
            SELECT * FROM wbcc_roles WHERE idRole=16
            ");
        } else {
            $this->db->query("
            SELECT * FROM wbcc_roles WHERE idRole != 16 AND idRole != 15 AND idRole != 14 AND idRole !=13
            ");
        }

        return $this->db->resultSet();
    }
}
