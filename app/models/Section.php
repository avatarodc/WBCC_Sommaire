<?php


class Section extends Model
{
    private $table = "wbcc_section";

    public $idSection;
    public $titreSection;
    public $numeroSection;
    public $contenuSection;
    public $idSommaireF;
    public $idSection_parentF;


    public function create($data)
    {
        // Vérifiez que les données requises sont présentes
        if (empty($data['titreSection']) || empty($data['idSommaireF'])) {
            return false;
        }

        // Utilisez des valeurs par défaut si certaines données ne sont pas fournies
        $data['contenuSection'] = $data['contenuSection'] ?? '';
        $data['idSection_parentF'] = $data['idSection_parentF'] ?? null;

        // Création de la requête SQL
        $this->db->query("INSERT INTO {$this->table} 
                         (titreSection, numeroSection, contenuSection, 
                         idSommaireF, idSection_parentF) 
                         VALUES 
                         (:titreSection, :numeroSection, :contenuSection, 
                         :idSommaireF, :idSection_parentF)");

        // Bind des valeurs 
        $this->db->bind(':titreSection', $data['titreSection']);
        $this->db->bind(':numeroSection', $data['numeroSection']);
        $this->db->bind(':contenuSection', $data['contenuSection']);
        $this->db->bind(':idSommaireF', $data['idSommaireF']);
        $this->db->bind(':idSection_parentF', $data['idSection_parentF']);

        // Exécution de la requête
        return $this->db->execute();
    }


    // Execute the query
    public function findSommaireById($sommaireId)
    {
        $this->db->query("SELECT * FROM wbcc_sommaire WHERE idSommaire = :idSommaire");
        $this->db->bind(':idSommaire', $sommaireId);
        return $this->db->single();
    }





    public function countSubSections($parentId)
    {
        $this->db->query("SELECT COUNT(*) as count 
                      FROM {$this->table} 
                      WHERE idSection_parentF = :parentId");
        $this->db->bind(':parentId', $parentId);
        $result = $this->db->single();
        return $result ? $result->count : 0;
    }

    public function getLastSectionNumber($sommaireId, $parentId = null)
    {
        $query = "SELECT MAX(CAST(numeroSection AS UNSIGNED)) as last_number 
              FROM {$this->table} 
              WHERE idSommaireF = :sommaireId";

        // Si un parent est spécifié, cherchez les sous-sections de ce parent
        if ($parentId !== null) {
            $query .= " AND idSection_parentF = :parentId";
        } else {
            // Sinon, cherchez les sections principales
            $query .= " AND (idSection_parentF IS NULL OR idSection_parentF = 0)";
        }

        $this->db->query($query);
        $this->db->bind(':sommaireId', $sommaireId);

        if ($parentId !== null) {
            $this->db->bind(':parentId', $parentId);
        }

        $result = $this->db->single();

        // Retourne 0 si aucune section n'existe encore
        return $result ? (int)$result->last_number : 0;
    }

    public function updateSection($id, $data)
    {
        try {
            $query = "UPDATE {$this->table} SET 
                      titreSection = :titreSection,
                      numeroSection = :numeroSection,
                      contenuSection = :contenuSection
                      WHERE idSection = :idSection";

            $this->db->query($query);

            $this->db->bind(':titreSection', $data['titreSection']);
            $this->db->bind(':numeroSection', $data['numeroSection']);
            $this->db->bind(':contenuSection', $data['contenuSection']);
            $this->db->bind(':idSection', $id);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Erreur dans updateSection: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteWithChildren($id)
    {
        try {
            error_log("Début de deleteWithChildren pour l'ID: " . $id);

            // D'abord supprimer tous les enfants
            $this->db->query("SELECT idSection FROM {$this->table} WHERE idSection_parentF = :parentId");
            $this->db->bind(':parentId', $id);
            $children = $this->db->resultSet();

            // Supprimer récursivement chaque enfant
            foreach ($children as $child) {
                $this->deleteWithChildren($child->idSection);
            }

            // Enfin, supprimer la section elle-même
            $this->db->query("DELETE FROM {$this->table} WHERE idSection = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->execute();

            error_log("Suppression réussie pour l'ID: " . $id);
            return $result;
        } catch (Exception $e) {
            error_log("Erreur dans deleteWithChildren: " . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            $this->db->query("DELETE FROM {$this->table} WHERE idSection = :id");
            $this->db->bind(':id', $id);
            $result = $this->db->execute();

            if (!$result) {
                error_log("Échec de la suppression pour l'ID: " . $id);
            }

            return $result;
        } catch (Exception $e) {
            error_log("Erreur dans delete: " . $e->getMessage());
            throw $e;
        }
    }

    public function getSectionWithParent($id)
    {
        return $this->select("s.*, sp.titreSection as parentTitle")
            ->join("s, wbcc_section sp", "sp")
            ->where("s.idSection_parentF = sp.idSection")
            ->and("s.idSection = $id")
            ->doQuery();
    }


    public function updateMultiple($sections)
    {
        try {
            foreach ($sections as $section) {
                error_log("Mise à jour de la section ID: " . $section['idSection']);

                $data = [
                    'titreSection' => $section['titreSection'],
                    'contenuSection' => $section['contenuSection'],
                    'numeroSection' => $section['numeroSection']
                ];

                if (!$this->updateSection($section['idSection'], $data)) {
                    error_log("Échec de la mise à jour pour la section ID: " . $section['idSection']);
                    return false;
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Erreur dans updateMultiple: " . $e->getMessage());
            return false;
        }
    }
    public function getSectionsBySommaire($sommaireId)
    {
        try {
            $this->db->query("SELECT * FROM {$this->table} WHERE idSommaireF = :sommaireId ORDER BY numeroSection");
            $this->db->bind(':sommaireId', $sommaireId);
            $result = $this->db->resultSet();
            return $result;
        } catch (Exception $e) {
            error_log("Erreur dans getSectionsBySommaire: " . $e->getMessage());
            throw $e;
        }
    }
    public function getChildSections($parentId)
    {
        return $this->select()
            ->where("idSection_parentF = $parentId")
            ->doQuery();
    }
}
