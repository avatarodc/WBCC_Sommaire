<?php
// Au début de votre contrôleur ou vue
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
if (!isset($projet) || !is_object($projet)) {
    echo '<div class="alert alert-danger">Erreur: Données du projet invalides</div>';
    return;
}
$idProjet = $projet->idProjet;
$hasSommaire = isset($sommaire) && is_object($sommaire) && !empty($sommaire->idSommaire);
?>

<!-- Variables PHP pour JavaScript -->
<script>
    const CONFIG = {
        projetId: <?php echo $projet->idProjet; ?>,
        sommaire: <?php echo $hasSommaire ? json_encode($sommaire) : 'null'; ?>,
        hasSommaire: <?php echo $hasSommaire ? 'true' : 'false'; ?>,
        URLROOT: '<?= URLROOT ?>',
        routes: {
            section: {
                getSectionsBySommaire: '<?= linkTo("Section", "getSectionsBySommaire") ?>',
                add: '<?= linkTo("Section", "add") ?>',
                delete: '<?= linkTo("Section", "delete") ?>',
                updateMultiple: '<?= linkTo("Section", "updateMultiple") ?>'
            },
            sommaire: {
                getAll: '<?= linkTo("Sommaire", "getAll") ?>'
            }
        }
    };



    // function loadSommaires() {
    //     fetch(CONFIG.routes.sommaire.getAll)
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log('Tous les sommaires :', data);
    //         })
    //         .catch(error => {
    //             console.error('Erreur lors de la récupération des sommaires:', error);
    //         });
    // }

    // // Appeler la fonction au chargement
    // document.addEventListener('DOMContentLoaded', loadSommaires);
</script>





<div class="container-fluid" id="sommaire-container">
    <legend class="text-center legend font-weight-bold text-uppercase" style="margin-top: 2rem;">
        <i class="icofont-info-circle my-1"></i>2-Sommaire
    </legend>
    <div class="row mb-4 mt-4" id="sommaire-row">
        <div class="col-12">

            <?php if (!$hasSommaire): ?>
                <div class="alert alert-info">
                    <p>Aucun sommaire n'existe pour ce projet.</p>
                    <button class="btn btn-primary" onclick="showCreateSommaireModal(event)">
                        Créer un sommaire
                    </button>
                </div>
            <?php else: ?>
                <div class="row">
                    <!-- Sidebar -->
                    <div class="col-md-3 p-0">
                        <div class="sidebar custom-sidebar">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Sommaire</h5>
                                <button class="btn btn-sm btn-red" onclick="addMainSection()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div id="sections-tree"></div>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="col-md-9">
                        <div class="main-content">
                            <div class="project-info mb-4">
                                <h2>Titre du sommaire : <?php echo htmlspecialchars($sommaire->titreSommaire); ?></h2>
                            </div>

                            <div id="section-content">
                                <div class="empty-state">
                                    <i class="fas fa-arrow-left"></i>
                                    <p>Sélectionnez une section dans le menu de gauche</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<!-- Modal création sommaire -->
<div class="modal fade" id="createSommaireModal">
    <div class="modal-dialog modal-lg"> <!-- Changé en modal-lg pour plus de largeur -->
        <div class="modal-content">
            <div class="modal-header bg-red text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i>Créer un sommaire
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form class="mt-0 p-0" id="msform" method="POST" action="<?= linkTo("Sommaire", "add") ?>">
                <div class="modal-body p-4">
                    <!-- Options de création -->
                    <div class="sommaire-options mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="card sommaire-option h-100" data-type="empty">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="sommaireVide" name="sommaireType" value="empty" class="custom-control-input" checked>
                                            <label class="custom-control-label" for="sommaireVide">
                                                <h6 class="mb-2"><i class="fas fa-file mr-2"></i>Sommaire vide</h6>
                                                <p class="text-muted mb-0 small">Créer un nouveau sommaire sans contenu</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card sommaire-option h-100" data-type="existing">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="sommaireExistant" name="sommaireType" value="existing" class="custom-control-input">
                                            <label class="custom-control-label" for="sommaireExistant">
                                                <h6 class="mb-2"><i class="fas fa-copy mr-2"></i>Sommaire existant</h6>
                                                <p class="text-muted mb-0 small">Copier un sommaire existant</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Champs du formulaire -->
                    <div class="form-group">
                        <label class="font-weight-bold">Titre du sommaire</label>
                        <input type="text" class="form-control form-control-lg" name="titreSommaire" required
                            placeholder="Entrez le titre du sommaire">
                    </div>

                    <!-- Liste des sommaires (caché par défaut) -->
                    <div class="form-group" id="sommaireSourceGroup" style="display: none;">
                        <label class="font-weight-bold">Sélectionner le sommaire à copier</label>
                        <div class="sommaires-list">
                            <!-- <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="searchSommaire"
                                    placeholder="Rechercher un sommaire...">
                            </div> -->
                            <div class="list-wrapper" style="max-height: 300px; overflow-y: auto;">
                                <div id="sommairesList" class="list-group">
                                    <!-- Les sommaires seront ajoutés ici dynamiquement -->
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="sommaireSource" id="sommaireSource" value="">
                    </div>

                    <input type="hidden" name="idProjetF" value="<?php echo $projet->idProjet; ?>">
                    <input type="hidden" name="numeroSommaire" value="1">
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check mr-2"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal création section -->
<div class="modal fade" id="createSectionModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-red text-white border-0">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle mr-2"></i>Créer une section
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="m-0" id="createSectionForm" onsubmit="return false;">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">Titre de la section</label>
                        <input type="text" class="form-control form-control-lg border-primary"
                            name="titreSection" placeholder="Entrez le titre de la section" required>
                    </div>
                    <input type="hidden" name="idSommaireF" value="<?php echo $sommaire->idSommaire; ?>">
                    <input type="hidden" name="idSection_parentF" id="parentSectionId">
                    <input type="hidden" name="numeroSection" id="numeroSection">
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-check mr-2"></i>Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal confirmation de modifications -->
<div class="modal fade" id="saveConfirmationModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Les modifications ont été enregistrées avec succès.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal confirmation de suppression -->
<div class="modal fade" id="deleteSectionModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette section ? Cette action supprimera également toutes les sous-sections.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
            </div>
        </div>
    </div>
</div>



<script src="<?= URLROOT ?>/public/assets/js/projet/sommaire.js"></script>

<script>
    document.getElementById('msform').addEventListener('submit', function(event) {
        const sommaireType = document.querySelector('input[name="sommaireType"]:checked').value;

        if (sommaireType === 'existing') {
            const selectedSommaireId = document.getElementById('sommaireSource').value;

            if (!selectedSommaireId) {
                event.preventDefault();
                alert('Veuillez sélectionner un sommaire à copier');
                return;
            }
        }
    });
    // Fonction pour charger et afficher les sommaires
    function loadSommairesList() {
        fetch(CONFIG.routes.sommaire.getAll)
            .then(response => response.json())
            .then(data => {
                console.log('Données reçues:', data);

                const sommairesList = document.getElementById('sommairesList');
                sommairesList.innerHTML = '';

                if (data.success && data.sommaires) {
                    data.sommaires.forEach(sommaire => {
                        const item = document.createElement('div');
                        item.className = 'list-group-item';
                        item.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${sommaire.titreSommaire}</strong>
                                    <br>
                                    <small class="text-muted">Projet: ${sommaire.nomProjet}</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-red" onclick="selectSommaire(${sommaire.idSommaire})">
                                    Choisir
                                </button>
                            </div>
                        `;
                        sommairesList.appendChild(item);
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('sommairesList').innerHTML = `
                    <div class="alert alert-danger">
                        Erreur lors du chargement des sommaires
                    </div>
                `;
            });
    }

    // Fonction pour sélectionner un sommaire
    function selectSommaire(id) {
        const sommaireSource = document.getElementById('sommaireSource');
        sommaireSource.value = id;

        // Retirer la classe active de tous les éléments
        document.querySelectorAll('#sommairesList .list-group-item').forEach(item => {
            item.classList.remove('active');
            // Réinitialiser tous les boutons
            const btn = item.querySelector('.btn');
            if (btn) {
                btn.textContent = 'Choisir';
                btn.classList.remove('btn-success');
                btn.classList.add('btn-red');
            }
        });

        // Ajouter la classe active et modifier le bouton
        const selectedItem = event.target.closest('.list-group-item');
        selectedItem.classList.add('active');
        const btn = selectedItem.querySelector('.btn');
        if (btn) {
            btn.textContent = 'Sélectionné';
            btn.classList.remove('btn-red');
            btn.classList.add('btn-success');
        }

        console.log('Sommaire sélectionné:', id);
    }

    // Écouteur pour le changement de type de sommaire
    document.querySelectorAll('input[name="sommaireType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const sommaireSourceGroup = document.getElementById('sommaireSourceGroup');
            if (this.value === 'existing') {
                sommaireSourceGroup.style.display = 'block';
                loadSommairesList(); // Charger la liste quand "sommaire existant" est sélectionné
            } else {
                sommaireSourceGroup.style.display = 'none';
            }
        });
    });

    // Style pour la liste
    const additionalStyle = document.createElement('style');
    additionalStyle.textContent = `
        .list-group-item .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }
        .list-group-item.active {
            background-color: #f8f9fa;
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    `;
    document.head.appendChild(additionalStyle);
</script>