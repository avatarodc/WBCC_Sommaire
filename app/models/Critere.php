<?php

class Critere extends Model
{
    public function getCriteres()
    {
        $this->db->query("SELECT * FROM wbcc_critere ORDER BY idCritere DESC");
        $res = $this->db->resultSet();
        return $res;
    }

    public function getCriteresByIdSubvention($id)
    {
        $this->db->query("SELECT * FROM wbcc_critere c, wbcc_critere_subvention cs WHERE  c.idCritere = cs.idCritereF AND cs.idSubventionF = $id ORDER BY idCritere DESC");
        $res = $this->db->resultSet();
        foreach ($res as $key => $value) {
            $value->conditions = $this->getConditionByIdCritere($value->idCritere);
        }
        return $res;
    }


    public function getConditions()
    {
        $this->db->query("SELECT * FROM wbcc_condition");
        $res = $this->db->resultSet();
        return $res;
    }

    public function getConditionByIdCritere($id)
    {
        $this->db->query("SELECT * FROM wbcc_type_condition tc, wbcc_condition con, wbcc_condition_critere cr WHERE tc.idTypeCondition= con.idTypeConditionF AND con.idCondition = cr.idConditionF AND cr.idCritereF = $id");
        $res = $this->db->resultSet();
        return $res;
    }

    public function getTypeConditions()
    {
        $this->db->query("SELECT * FROM wbcc_type_condition");
        $res = $this->db->resultSet();
        return $res;
    }
}
