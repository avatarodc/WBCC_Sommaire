<?php

class Subvention extends Model
{
    public function getSubventions()
    {
        $this->db->query("SELECT * FROM wbcc_subvention");
        $res = $this->db->resultSet();
        return $res;
    }

    public function findSubventionByColumnValue($column, $value)
    {
        $this->db->query("SELECT * FROM wbcc_subvention WHERE $column = :value LIMIT 1");
        $this->db->bind("value", $value);
        $res = $this->db->single();
        return $res;
    }

    public function saveSubvention($idSubvention, $titreSubvention, $natureTravaux, $natureAide,  $montantSubvention, $taux, $idOrganisme, $idUser)
    {
        $numero = "SUB" . date("dmYHis") . $idUser;
        if ($idSubvention != null && $idSubvention != "" && $idSubvention != "0") {
            $this->db->query("UPDATE wbcc_subvention SET titreSubvention=:titreSubvention, montantSubvention=:montantSubvention, taux=:taux, natureTravaux=:natureTravaux, natureAide=:natureAide, editDate=:editDate, idAuteur=:idAuteur, idOrganisme=:idOrganisme WHERE idSubvention=:idSubvention ");
            $this->db->bind("idSubvention", $idSubvention);
        } else {
            $this->db->query("INSERT INTO wbcc_subvention(numeroSubvention, titreSubvention, montantSubvention, taux, natureTravaux, natureAide,createDate, editDate, idAuteur, idOrganisme) VALUES (:numeroSubvention, :titreSubvention, :montantSubvention, :taux, :natureTravaux, :natureAide, :createDate, :editDate, :idAuteur, :idOrganisme)");
            $this->db->bind("createDate", date("Y-m-d H:i:s"));
            $this->db->bind("numeroSubvention", $numero);
        }
        $this->db->bind("titreSubvention", $titreSubvention);
        $this->db->bind("montantSubvention", $montantSubvention);
        $this->db->bind("taux", $taux);
        $this->db->bind("natureTravaux", $natureTravaux);
        $this->db->bind("natureAide", $natureAide);
        $this->db->bind("editDate",  date("Y-m-d H:i:s"));
        $this->db->bind("idAuteur",  $idUser);
        $this->db->bind("idOrganisme",  $idOrganisme);
        if ($this->db->execute()) {
            $artisan = false;
            if ($idSubvention != null && $idSubvention != "" && $idSubvention != "0") {
                $artisan =  $this->findSubventionByColumnValue("idSubvention", $idSubvention);
            } else {
                $artisan =  $this->findSubventionByColumnValue("numeroSubvention", $numero);
            }
            return $artisan;
        } else {
            return false;
        }
    }

    public function getDocumentsRequisByIdSubvention($id)
    {
        $this->db->query("SELECT * FROM wbcc_document_requis d, wbcc_document_requis_subvention ds WHERE d.idDocumentRequis = ds.idDocumentRequisF  AND ds.idSubventionF = $id");
        $res = $this->db->resultSet();
        return $res;
    }

    public function getDocumentsRequisNotInSubvention($id)
    {
        $this->db->query("SELECT * FROM wbcc_document_requis WHERE idDocumentRequis NOT IN ( SELECT d.idDocumentRequis FROM wbcc_document_requis d, wbcc_document_requis_subvention ds WHERE d.idDocumentRequis = ds.idDocumentRequisF  AND ds.idSubventionF = $id)");
        $res = $this->db->resultSet();
        return $res;
    }

    public function getDocumentsRequis()
    {
        $this->db->query("SELECT * FROM wbcc_document_requis ");
        $res = $this->db->resultSet();
        return $res;
    }
}