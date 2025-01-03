<?php

class SectionCtrl extends Controller
{
    private $sectionModel;
    private  $documentModel;

    public function __construct()
    {
        $this->sectionModel = $this->model('Section');
        $this->documentModel = $this->model('Document');
    }

    public function index($sommaireId = null)
    {
        if ($sommaireId) {
            $sections = $this->sectionModel->getSectionsBySommaire($sommaireId);
        } else {
            $sections = $this->sectionModel->getAll();
        }

        $this->view('sections/index', [
            'sections' => $sections,
            'sommaireId' => $sommaireId,
            'title' => 'Liste des sections'
        ]);
    }



    public function getSectionsBySommaire()
    {
        ob_clean();
        $this->view = false;

        header('Content-Type: application/json');

        if (!isset($_GET['idSommaire'])) {
            echo json_encode(['error' => 'ID Sommaire manquant']);
            exit;
        }

        $idSommaire = $_GET['idSommaire'];

        try {
            // Récupérer les sections
            $sections = $this->sectionModel->getSectionsBySommaire($idSommaire);

            // Récupérer le sommaire
            $sommaireModel = $this->model('Sommaire');
            $sommaire = $sommaireModel->findBy('idSommaire', $idSommaire);

            echo json_encode([
                'success' => true,
                'sections' => $sections ?? [],
                'titreSommaire' => $sommaire ? $sommaire->titreSommaire : ''
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'trace' => debug_backtrace()
            ]);
        }
        exit();
    }


    public function show($id)
    {
        $section = $this->sectionModel->findBy('idSection', $id);
        if ($section) {
            // Récupérer les informations de la section parente si elle existe
            $sectionWithParent = $this->sectionModel->getSectionWithParent($id);
            // Récupérer les sous-sections
            $childSections = $this->sectionModel->getChildSections($id);

            $this->view('sections/show', [
                'section' => $section,
                'sectionWithParent' => $sectionWithParent,
                'childSections' => $childSections,
                'title' => 'Détails de la section'
            ]);
        } else {
            $this->redirectToMethod('sections');
        }
    }


    public function updateMultiple()
    {
        // Désactiver la vue
        $this->view = false;

        // Vider le buffer
        @ob_end_clean();

        // En-tête JSON
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        try {
            // Vérifier la méthode
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }

            // Récupérer les données
            $postData = json_decode(file_get_contents('php://input'), true);

            if (!isset($postData['sections']) || !is_array($postData['sections'])) {
                throw new Exception('Format de données invalide');
            }

            // Mettre à jour les sections
            if ($this->sectionModel->updateMultiple($postData['sections'])) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Sections mises à jour avec succès'
                ]);
            } else {
                throw new Exception('Erreur lors de la mise à jour des sections');
            }
        } catch (Exception $e) {
            error_log('Erreur dans updateMultiple: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }

        exit();
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $data = [
                'titreSection' => trim($_POST['titreSection']),
                'contenuSection' => trim($_POST['contenuSection'] ?? ''),
                'idSommaireF' => trim($_POST['idSommaireF']),
                'idSection_parentF' => !empty($_POST['idSection_parentF']) ? trim($_POST['idSection_parentF']) : null,
                'numeroSection' => trim($_POST['numeroSection'])
            ];

            if ($this->sectionModel->create($data)) {
                $_SESSION['success'] = "Section créée avec succès";
                $this->redirectToMethod('GestionInterne', 'projet', $data['idSommaireF']);
            } else {
                $_SESSION['error'] = "Erreur lors de la création de la section";
                $this->redirectToMethod('GestionInterne', 'projet', $data['idSommaireF']);
            }
        } else {
            $this->redirectToMethod('GestionInterne');
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traitement du formulaire
            $data = [
                'titreSection' => trim($_POST['titreSection']),
                'numeroSection' => trim($_POST['numeroSection']),
                'contenuSection' => trim($_POST['contenuSection']),
                'idSommaireF' => trim($_POST['idSommaireF']),
                'idSection_parentF' => !empty($_POST['idSection_parentF']) ? trim($_POST['idSection_parentF']) : null
            ];

            if ($this->sectionModel->updateSection($id, $data)) {
                $this->redirectToMethod('sections', 'show', $id);
            } else {
                $this->view('sections/edit', [
                    'data' => $data,
                    'error' => 'Une erreur est survenue lors de la modification de la section',
                    'title' => 'Modifier la section'
                ]);
            }
        } else {
            // Récupérer les données de la section
            $section = $this->sectionModel->findBy('idSection', $id);
            if ($section) {
                $this->view('sections/edit', [
                    'section' => $section,
                    'title' => 'Modifier la section'
                ]);
            } else {
                $this->redirectToMethod('sections');
            }
        }
    }

    public function delete($id)
    {
        error_log('Entrée dans la méthode delete');

        // // Désactiver complètement le rendu de vue
        // $this->view = null;
        // $this->layout = null; // Si vous avez une propriété layout

        // Vider le buffer de sortie
        @ob_end_clean();

        // Forcer les en-têtes
        if (!headers_sent()) {
            header('Content-Type: application/json');
            header('X-PHP-Response-Type: JSON');
        }

        try {
            error_log('ID de la section à supprimer: ' . $id);

            // Tenter la suppression sans vérification préalable
            if ($this->sectionModel->deleteWithChildren($id)) {
                error_log('Suppression réussie');
                $response = json_encode([
                    'success' => true,
                    'message' => 'Section supprimée avec succès'
                ]);
            } else {
                error_log('Échec de la suppression');
                $response = json_encode([
                    'success' => false,
                    'error' => 'Échec de la suppression'
                ]);
            }

            error_log('Réponse préparée: ' . $response);
            echo $response;
        } catch (Exception $e) {
            error_log('Exception lors de la suppression: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la suppression'
            ]);
        }
        error_log('Fin de la méthode delete');
        exit();
    }


    /**
     * Récupère tous les documents (pour la recherche RYM)
     */
    public function getAllDocuments()
    {
        $this->view = false;
        ob_clean();
        header('Content-Type: application/json');

        try {
            // Utiliser la nouvelle méthode
            $documents = $this->documentModel->getAllDocuments();

            // Formatter les documents pour l'API
            $formattedDocs = array_map(function ($doc) {
                return [
                    'id' => $doc->idDocument,
                    'titre' => $doc->nomDocument,
                    'type' => pathinfo($doc->urlDocument, PATHINFO_EXTENSION),
                    'dateCreation' => $doc->createDate,
                    'auteur' => $doc->auteur
                ];
            }, $documents);

            echo json_encode([
                'success' => true,
                'documents' => $formattedDocs
            ]);
        } catch (Exception $e) {
            error_log('Erreur lors de la récupération des documents: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => 'Erreur lors de la récupération des documents : ' . $e->getMessage()
            ]);
        }
        exit();
    }
    /**
     * Upload d'un nouveau document
     */
    public function uploadSectionDocument()
    {
        $this->view = false;
        ob_clean();
        header('Content-Type: application/json');

        try {
            // Vérification des données
            if (!isset($_FILES['file']) || !isset($_POST['sectionId'])) {
                throw new Exception('Données manquantes');
            }

            $file = $_FILES['file'];
            $sectionId = $_POST['sectionId'];

            // Vérifications basiques
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Erreur lors du téléchargement');
            }

            // Générer un numéro unique pour le document
            $idUser = Role::connectedUser()->idUtilisateur;

            $numeroDocument = uniqid('DOC' . $idUser . '_', true);

            // Utiliser URLROOT pour définir le chemin
            $uploadBaseDir = $_SERVER['DOCUMENT_ROOT'] . '/Extranet_WBCC-FR/public/projet/annexe/';

            // Créer le répertoire s'il n'existe pas
            if (!is_dir($uploadBaseDir)) {
                mkdir($uploadBaseDir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = $numeroDocument . '.' . $extension;
            $destination = $uploadBaseDir . $fileName;

            // Débogage
            error_log('Chemin de destination complet : ' . $destination);
            error_log('Fichier temporaire : ' . $file['tmp_name']);

            // Déplacer le fichier
            if (!move_uploaded_file($file['tmp_name'], $destination)) {
                // Log de l'erreur de déplacement
                error_log('Impossible de déplacer le fichier');
                throw new Exception('Erreur lors du déplacement du fichier');
            }

            // Sauvegarder dans la base de données
            $documentId = $this->documentModel->save(
                $numeroDocument,
                $file['name'],
                $fileName,
                isset($_SESSION['user']) ? $_SESSION['user']->nomUtilisateur : '',
                isset($_SESSION['user']) ? $_SESSION['user']->idUtilisateur : null,
                isset($_SESSION['user']) ? $_SESSION['user']->guidUtilisateur : ''
            );

            if (!$documentId) {
                throw new Exception('Erreur lors de l\'enregistrement du document');
            }

            // Lier le document à la section
            $this->sectionModel->linkDocument($sectionId, $documentId);

            echo json_encode([
                'success' => true,
                'message' => 'Document uploadé avec succès',
                'document' => [
                    'id' => $documentId,
                    'nom' => $file['name'],
                    'url' => URLROOT . '/projet/annexe/' . $fileName
                ]
            ]);
        } catch (Exception $e) {
            error_log('Erreur complète : ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit();
    }


    /**
     * Lie un document existant à une section
     */
    public function linkDocumentToSection()
    {
        $this->view = false;
        ob_clean();
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['sectionId']) || !isset($data['documentId'])) {
                error_log('Données manquantes : ' . print_r($data, true));
                throw new Exception('Données manquantes');
            }

            $sectionId = $data['sectionId'];
            $documentId = $data['documentId'];

            error_log("Tentative de liaison - Données reçues : " . print_r($data, true));

            $result = $this->sectionModel->linkDocument($sectionId, $documentId);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Document lié avec succès'
                ]);
            } else {
                error_log("Échec de la liaison du document");
                throw new Exception('Erreur lors de la liaison du document');
            }
        } catch (Exception $e) {
            error_log('Exception dans linkDocumentToSection : ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit();
    }

    /**
     * Récupère les documents liés à une section
     */
    public function getSectionDocuments($sectionId)
    {
        $this->view = false;
        ob_clean();
        header('Content-Type: application/json');

        try {
            $documents = $this->sectionModel->getDocuments($sectionId);

            $formattedDocs = array_map(function ($doc) {
                return [
                    'id' => $doc->idDocument,
                    'numero' => $doc->numeroDocument,
                    'nom' => $doc->nomDocument,
                    'url' => $doc->urlDocument,
                    'dateCreation' => $doc->createDate,
                    'auteur' => $doc->auteur
                ];
            }, $documents);

            echo json_encode([
                'success' => true,
                'documents' => $formattedDocs
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit();
    }
}
