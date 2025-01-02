<?php
class SommaireCtrl extends Controller
{
    private $sommaireModel;

    public function __construct()
    {
        $this->sommaireModel = $this->model('Sommaire');
    }



    public function index()
    {
        $sommaires = $this->sommaireModel->getAll();
        $this->view('sommaires/index', [
            'sommaires' => $sommaires,
            'title' => 'Liste des sommaires'
        ]);
    }

    public function getAll()
    {
        $this->view = false;
        ob_clean();
        header('Content-Type: application/json');

        try {
            $sommaires = $this->sommaireModel->getAllSommairesWithProjects();
            echo json_encode([
                'success' => true,
                'sommaires' => array_map(function ($sommaire) {
                    return [
                        'idSommaire' => $sommaire->idSommaire,
                        'titreSommaire' => $sommaire->titreSommaire,
                        'nomProjet' => $sommaire->nomProjet
                    ];
                }, $sommaires)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit();
    }

    public function getAllProjets()
    {
        $projetModel = $this->model('Projet');
        $projets = $projetModel->getAllProjetsWithSommaire();

        // Renvoyer en JSON pour DataTable
        header('Content-Type: application/json');
        echo json_encode($projets);
        exit;
    }

    public function show($id)
    {
        $sommaire = $this->sommaireModel->findBy('idSommaire', $id);
        if ($sommaire) {
            // Récupérer les informations du projet associé
            $sommaireWithProjet = $this->sommaireModel->getSommaireWithProjet($id);
            $this->view('sommaires/show', [
                'sommaire' => $sommaire,
                'sommaireWithProjet' => $sommaireWithProjet,
                'title' => 'Détails du sommaire'
            ]);
        } else {
            $this->redirectToMethod('sommaires');
        }
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'numeroSommaire' => trim($_POST['numeroSommaire']),
                'titreSommaire' => trim($_POST['titreSommaire']),
                'idProjetF' => trim($_POST['idProjetF'])
            ];

            // Vérifier si c'est une copie d'un sommaire existant
            if (isset($_POST['sommaireType']) && $_POST['sommaireType'] === 'existing' && !empty($_POST['sommaireSource'])) {
                // Copier le sommaire avec ses sections
                $sourceSommaireId = $_POST['sommaireSource'];
                $newSommaireId = $this->sommaireModel->copySommaireWithSections($sourceSommaireId, $data);
                if ($newSommaireId) {
                    $_SESSION['success'] = "Sommaire et ses sections copiés avec succès";
                    $this->redirectToMethod('GestionInterne', 'projet', $data['idProjetF']);
                } else {
                    $_SESSION['error'] = "Erreur lors de la copie du sommaire";
                    $this->redirectToMethod('GestionInterne', 'projet', $data['idProjetF']);
                }
            } else {
                // Création d'un sommaire vide
                if ($this->sommaireModel->create($data)) {
                    $_SESSION['success'] = "Sommaire créé avec succès";
                    $this->redirectToMethod('GestionInterne', 'projet', $data['idProjetF']);
                } else {
                    $_SESSION['error'] = "Erreur lors de la création du sommaire";
                    $this->redirectToMethod('GestionInterne', 'projet', $data['idProjetF']);
                }
            }
        } else {
            $this->redirectToMethod('GestionInterne', 'projet');
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Traitement du formulaire
            $data = [
                'numeroSommaire' => trim($_POST['numeroSommaire']),
                'titreSommaire' => trim($_POST['titreSommaire']),
                'idProjetF' => trim($_POST['idProjetF'])
            ];

            if ($this->sommaireModel->updateSommaire($id, $data)) {
                $this->redirectToMethod('sommaires', 'show', $id);
            } else {
                $this->view('sommaires/edit', [
                    'data' => $data,
                    'error' => 'Une erreur est survenue lors de la modification du sommaire',
                    'title' => 'Modifier le sommaire'
                ]);
            }
        } else {
            // Récupérer les données du sommaire
            $sommaire = $this->sommaireModel->findBy('idSommaire', $id);
            if ($sommaire) {
                $this->view('sommaires/edit', [
                    'sommaire' => $sommaire,
                    'title' => 'Modifier le sommaire'
                ]);
            } else {
                $this->redirectToMethod('sommaires');
            }
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->sommaireModel->delete($id)) {
                $this->redirectToMethod('sommaires');
            } else {
                $this->redirectToMethod('sommaires', 'show', $id);
            }
        } else {
            $this->redirectToMethod('sommaires');
        }
    }
}
