<?php
// models/Sommaire.php
class Sommaire extends Model
{
    protected $table = "wbcc_sommaire";

    public $idSommaire;
    public $numeroSommaire;
    public $titreSommaire;
    public $idProjetF;


    public function getSommaireByProjet($idProjet)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE idProjetF = :idProjet");
        $this->db->bind(':idProjet', $idProjet);
        return $this->db->single();
    }
    public function create($data)
    {
        $this->db->query("INSERT INTO wbcc_sommaire (numeroSommaire, titreSommaire, idProjetF) 
                         VALUES (:numeroSommaire, :titreSommaire, :idProjetF)");

        $this->db->bind(':numeroSommaire', $data['numeroSommaire']);
        $this->db->bind(':titreSommaire', $data['titreSommaire']);
        $this->db->bind(':idProjetF', $data['idProjetF']);

        return $this->db->execute();
    }


    public function findBy($column, $value)
    {
        try {
            $this->db->query("SELECT * FROM wbcc_sommaire WHERE {$column} = :value");
            $this->db->bind(':value', $value);
            return $this->db->single();
        } catch (Exception $e) {
            error_log("Error in findBy: " . $e->getMessage());
            throw $e;
        }
    }


    public function updateSommaire($id, $data)
    {
        return $this->update()
            ->set("numeroSommaire", "'" . $data['numeroSommaire'] . "'")
            ->set("titreSommaire", "'" . $data['titreSommaire'] . "'")
            ->set("idProjetF", $data['idProjetF'])
            ->where("idSommaire = $id")
            ->doUpdate();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE idSommaire = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSommaireWithProjet($id)
    {
        return $this->select("s.*, p.titreProjet")
            ->join("s, projet p", "p")
            ->where("s.idProjetF = p.idProjet")
            ->and("s.idSommaire = $id")
            ->doQuery();
    }

    public function getAllSommairesWithProjects()
    {
        $this->db->query("SELECT s.*, p.nomProjet 
                          FROM {$this->table} s 
                          LEFT JOIN wbcc_projet p ON s.idProjetF = p.idProjet 
                          ORDER BY s.titreSommaire");
        return $this->db->resultSet();
    }

    public function copySommaireWithSections($sourceSommaireId, $destinationData)
    {
        try {
            // 1. Créer le nouveau sommaire
            $this->db->query("INSERT INTO wbcc_sommaire (numeroSommaire, titreSommaire, idProjetF) 
                 VALUES (:numeroSommaire, :titreSommaire, :idProjetF)");

            $this->db->bind(':numeroSommaire', $destinationData['numeroSommaire']);
            $this->db->bind(':titreSommaire', $destinationData['titreSommaire']);
            $this->db->bind(':idProjetF', $destinationData['idProjetF']);

            if (!$this->db->execute()) {
                throw new Exception("Erreur lors de la création du sommaire");
            }

            // Récupérer l'ID du nouveau sommaire créé
            $newSommaireId = $this->db->lastInsertId();

            // Mapping pour garder trace des anciens et nouveaux IDs de sections
            $sectionMapping = [];

            //  copier toutes les sections de niveau principal (sans parent)
            $this->db->query("SELECT * FROM wbcc_section 
                          WHERE idSommaireF = :sourceSommaireId 
                          AND (idSection_parentF IS NULL OR idSection_parentF = 0)
                          ORDER BY numeroSection");
            $this->db->bind(':sourceSommaireId', $sourceSommaireId);
            $mainSections = $this->db->resultSet();

            foreach ($mainSections as $section) {
                // Insérer la section principale
                $this->db->query("INSERT INTO wbcc_section 
            (titreSection, contenuSection, numeroSection, idSommaireF, idSection_parentF) 
            VALUES 
            (:titreSection, :contenuSection, :numeroSection, :idSommaireF, NULL)");

                $this->db->bind(':titreSection', $section->titreSection);
                $this->db->bind(':contenuSection', $section->contenuSection ?? '');
                $this->db->bind(':numeroSection', $section->numeroSection);
                $this->db->bind(':idSommaireF', $newSommaireId);

                $this->db->execute();
                $newSectionId = $this->db->lastInsertId();

                // Stocker la correspondance des IDs
                $sectionMapping[$section->idSection] = $newSectionId;
            }

            // Deuxième passe : copier toutes les sections avec parents
            $this->db->query("SELECT * FROM wbcc_section 
                          WHERE idSommaireF = :sourceSommaireId 
                          AND idSection_parentF IS NOT NULL 
                          ORDER BY numeroSection");
            $this->db->bind(':sourceSommaireId', $sourceSommaireId);
            $childSections = $this->db->resultSet();

            foreach ($childSections as $section) {
                // Trouver le nouvel ID du parent
                $newParentId = $sectionMapping[$section->idSection_parentF];

                // Insérer la section enfant
                $this->db->query("INSERT INTO wbcc_section 
            (titreSection, contenuSection, numeroSection, idSommaireF, idSection_parentF) 
            VALUES 
            (:titreSection, :contenuSection, :numeroSection, :idSommaireF, :idSection_parentF)");

                $this->db->bind(':titreSection', $section->titreSection);
                $this->db->bind(':contenuSection', $section->contenuSection ?? '');
                $this->db->bind(':numeroSection', $section->numeroSection);
                $this->db->bind(':idSommaireF', $newSommaireId);
                $this->db->bind(':idSection_parentF', $newParentId);

                $this->db->execute();
                $newSectionId = $this->db->lastInsertId();

                // Stocker la correspondance des IDs
                $sectionMapping[$section->idSection] = $newSectionId;
            }

            return $newSommaireId;
        } catch (Exception $e) {
            error_log("Erreur lors de la copie du sommaire avec sections: " . $e->getMessage());
            return false;
        }
    }
}
