<?php

class Avantage extends Model
{

    public function getAvantageByCategorie($categorie)
    {
        $categorie = trim(str_replace('\'', '\\\'', htmlspecialchars($categorie)));
        $this->db->query("SELECT * FROM wbcc_avantage WHERE categorieDO = '$categorie'  ORDER BY idAvantage ASC");

        return $this->db->resultSet();
    }
}