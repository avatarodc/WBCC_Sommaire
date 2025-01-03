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

    public function linkDocument($sectionId, $documentId)
    {
        try {
            // Logs détaillés
            error_log("Début de linkDocument");
            error_log("Section ID: $sectionId");
            error_log("Document ID: $documentId");

            // Vérification complète du document
            $this->db->query("SELECT * FROM wbcc_document WHERE idDocument = :documentId");
            $this->db->bind(':documentId', $documentId);
            $document = $this->db->single();

            // Log du résultat de recherche du document
            if (!$document) {
                error_log("ERREUR : Document introuvable");
                error_log("Requête : SELECT * FROM wbcc_document WHERE idDocument = $documentId");
                return false;
            }

            // Log des détails du document
            error_log("Détails du document : " . json_encode($document));

            // Vérification de la section
            $this->db->query("SELECT * FROM wbcc_section WHERE idSection = :sectionId");
            $this->db->bind(':sectionId', $sectionId);
            $section = $this->db->single();

            // Log du résultat de recherche de la section
            if (!$section) {
                error_log("ERREUR : Section introuvable");
                error_log("Requête : SELECT * FROM wbcc_section WHERE idSection = $sectionId");
                return false;
            }

            // Log des détails de la section
            error_log("Détails de la section : " . json_encode($section));

            // Vérification de l'existence du lien
            $this->db->query("SELECT COUNT(*) as count FROM wbcc_section_document 
                              WHERE idSectionF = :sectionId AND idDocumentF = :documentId");
            $this->db->bind(':sectionId', $sectionId);
            $this->db->bind(':documentId', $documentId);
            $linkExists = $this->db->single();

            // Log de l'existence du lien
            error_log("Nombre de liens existants : " . $linkExists->count);

            // Si le lien existe déjà, retourner true
            if ($linkExists->count > 0) {
                error_log("Lien déjà existant");
                return true;
            }

            // Insertion du lien
            $this->db->query("INSERT INTO wbcc_section_document 
                              (idSectionF, idDocumentF, numeroDocument) 
                              VALUES (:sectionId, :documentId, :numeroDocument)");

            $this->db->bind(':sectionId', $sectionId);
            $this->db->bind(':documentId', $documentId);
            $this->db->bind(':numeroDocument', $document->numeroDocument);

            $result = $this->db->execute();

            // Log du résultat de l'insertion
            if ($result) {
                error_log("Liaison réussie");
                return true;
            } else {
                error_log("ERREUR : Échec de l'insertion du lien");
                return false;
            }
        } catch (Exception $e) {
            // Log de l'exception
            error_log("EXCEPTION FATALE : " . $e->getMessage());
            error_log("Trace : " . $e->getTraceAsString());
            return false;
        }
    }

    // 
    public function getDocuments($sectionId)
    {
        $this->db->query("SELECT d.* 
                      FROM wbcc_document d
                      JOIN wbcc_section_document sd ON d.idDocument = sd.idDocumentF
                      WHERE sd.idSectionF = :sectionId 
                      AND d.etatDocument = 1
                      ORDER BY d.createDate DESC");

        $this->db->bind(':sectionId', $sectionId);

        return $this->db->resultSet();
    }

    
}
