<?php

class SectionCtrl extends Controller
{
    private $sectionModel;

    public function __construct()
    {
        $this->sectionModel = $this->model('Section');
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
}
