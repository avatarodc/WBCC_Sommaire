<?php
// $hidden = (isset($etape) && $etape != "") ? "" : "hidden";
// $hiddenFinal = (isset($etape) && $etape != "") ? "hidden" : "";
$active = "red";
?>

<!-- ======= Avantages Section ======= -->

<div class="section-title">
    <div class="row">
        <div class="col-md-6">
            <h2><span>

                    <i class="fa fa-regular fa-folder-open" style="color: #c00000"></i>
                </span> GESTION DES PROJETS</h2>
        </div>
        <div class="col-md-6">
            <div class="float-right mt-0 mb-3">
                <a type="button" rel="tooltip" title="Ajouter" href="<?= linkto('GestionInterne', 'projet') ?>"
                    class="btn btn btn-sm btn-red  ml-1">
                    <i class="fas fa-plus" style="color: #ffffff"></i>
                    Ajouter un projet
                </a>
                <a hidden type="button" rel="tooltip" title="Paramétrer"
                    href="<?= linkto('GestionInterne', 'parametrageSubvention') ?>"
                    class="btn btn btn-sm btn-red  ml-1">
                    <i class="fas fa-cog" style="color: #ffffff"></i>
                    Paramétrage
                </a>
            </div>
        </div>
    </div>

</div>

<!-- DataTales Example -->
<div class="card shadow mb-4 col-md-12 ">
    <input type="hidden" name="URLROOT" id="URLROOT" value="<?= URLROOT ?>">
    <div class="card-header bg-secondary text-white">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center font-weight-bold" id="titre"> <?= $titre . " (" . sizeof($projets) . ")" ?>
                </h2>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable16" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($projets as $projet) {
                        $i++;
                    ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $projet->nomProjet ?></td>
                            <td><?= $projet->descriptionProjet ?></td>
                            <td><?= $projet->createDate ?></td>

                            <td style="text-align : center">
                                <a type="button" rel="tooltip" title="Modifier"
                                    href="<?= linkto('GestionInterne', 'projet', $projet->idProjet) ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit text-white"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm delete-project"
                                    data-id="<?= $projet->idProjet ?>" data-projet-nom="<?= $projet->nomProjet ?>"
                                    data-toggle="tooltip" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
        </div>
        </td>
        </tr>
    <?php    }
    ?>
    </tbody>
    </table>
    </div>
</div>
</div>


<!-- Modal de suppression -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" role="dialog" aria-labelledby="deleteProjectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-white border-0">
                <h5 class="modal-title text-danger w-100 text-center" id="deleteProjectModalLabel">
                    Supprimer le projet <span id="projectNumberToDelete"></span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <h4 class="font-weight-bold mb-4">ATTENTION !</h4>
                <p>Cette action est irréversible. En confirmant, toutes les données associées à ce projet seront
                    définitivement supprimées et ne pourront pas être récupérées.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" id="confirmDelete" class="btn btn-danger px-4">
                    Confirmer suppression
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= URLROOT ?>/public/assets/js/projet/projet.js"></script>