<?php
$active = "red";
?>
<!-- ======= Avantages Section ======= -->
<div class="section-title mb-0">
    <h2><button onclick="document.location.href='<?= URLROOT ?>/GestionInterne/indexSubvention'"><i
                class="fas fa-fw fa-arrow-left" style="color: #c00000"></i></button> <span><i
                class="fas fa-fw fa-warehouse" style="color: #c00000"></i></span> gestion subvention</h2>
</div>

<div class="row mt-0">
    <div class="<?= $subvention ? "col-md-6" : "col-md-12" ?> text-left m-0 p-0">
        <div class="row  mt-0 p-0">
            <fieldset>
                <legend class=" text-center legend font-weight-bold text-uppercase"><i
                        class="icofont-info-circle my-1"></i>1-Subvention</legend>
                <form class="mt-0 p-0" id="msform" method="POST"
                    action="<?= linkTo("GestionInterne", "saveSubvention") ?>">
                    <div class="col-md-12 px-0 mt-0">
                        <input type='text' id='idUtilisateur' class='form-control'
                            value='<?= $_SESSION['connectedUser']->idUtilisateur ?>' hidden>
                        <input type='text' id='auteur' class='form-control'
                            value='<?= $_SESSION['connectedUser']->fullName ?>' hidden>
                        <input type='text' id='numeroAuteur' class='form-control'
                            value='<?= $_SESSION['connectedUser']->numeroContact ?>' hidden>
                        <input type="hidden" name="URLROOT" id="URLROOT" value="<?= URLROOT ?>">
                        <div class="row text-left mt-0">
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Titre subvention</label>
                                </div>
                                <input required type="text"
                                    value="<?= ($subvention) ?  "$subvention->idSubvention" : "0" ?>"
                                    name="idSubvention" class="form-control" id="idSubvention" hidden>
                                <div class="col-md-12">
                                    <input required type="text"
                                        value="<?= ($subvention) ?  "$subvention->titreSubvention" : "" ?>"
                                        name="titreSubvention" class="form-control" id="titreSubvention">
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Nature des Travaux</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex  justify-content-center">
                                        <div class=' col-4 text-left'>
                                            <input
                                                <?= ($subvention && $subvention->natureTravaux != null && str_contains($subvention->natureTravaux, "Collectif")) ?  "checked" : "" ?>
                                                type='checkbox' value='Collectif' name='natureTravaux[]'
                                                class="natureTravaux"> <label> Collectif </label>
                                        </div>
                                        <div class='col-4 text-left'>
                                            <input
                                                <?= ($subvention && $subvention->natureTravaux != null && str_contains($subvention->natureTravaux, "Privatif")) ?  "checked" : "" ?>
                                                type='checkbox' value="Privatif" name='natureTravaux[]'
                                                class="natureTravaux">
                                            <label>Privatif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Nature des Aides</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex  justify-content-center">
                                        <div class='col-4 text-left'>
                                            <input
                                                <?= ($subvention && $subvention->natureAide != null && str_contains($subvention->natureAide, "Collectif")) ?  "checked" : "" ?>
                                                type='checkbox' value='Collectif' name='natureAide[]'
                                                class="natureAide"> <label> Collectif </label>
                                        </div>
                                        <div class='col-4 text-left'>
                                            <input
                                                <?= ($subvention && $subvention->natureAide != null && str_contains($subvention->natureAide, "Privatif")) ?  "checked" : "" ?>
                                                type='checkbox' value="Privatif" name='natureAide[]' class="natureAide">
                                            <label>Privatif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Montant Plafond</label>
                                </div>
                                <div class="col-md-12">
                                    <input required type="text"
                                        value="<?= ($subvention) ?  "$subvention->montantSubvention" : "" ?>"
                                        name="montantSubvention" class="form-control" id="montantSubvention">
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Taux de Commission</label>
                                </div>
                                <div class="col-md-12">
                                    <input required type="number" min="0"
                                        value="<?= ($subvention) ?  "$subvention->taux" : "" ?>" name="taux"
                                        class="form-control" id="taux">
                                </div>
                            </div>
                            <div class="col-md-12 mb-1">
                                <div class="col-md-12">
                                    <label class="font-weight-bold" for="">Organisme</label>
                                </div>
                                <div class="col-md-12">
                                    <select required name="idOrganisme" class="form-control" id="idOrganisme">
                                        <option value="">--- Veuillez choisir ---</option>
                                        <?php
                                        foreach ($organimes as $key => $value) {
                                        ?>
                                            <option value="<?= $value->idCompany ?>"
                                                <?= ($subvention) && $subvention->idOrganisme == $value->idCompany ? "selected" : ""  ?>>
                                                <?= $value->name ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 mb-0 p-0">
                            <div class="col text-center">
                                <input name="valider" class="btn btn btn-md text-white" type="submit"
                                    style="background-color: darkgreen;" value="Enregistrer" />
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
    <div class="col-md-6 text-left m-0 p-0" <?= $subvention ? "" : "hidden" ?>>
        <div class="row mt-0 p-0">
            <fieldset style="height : 554.5px">
                <legend class="text-center legend font-weight-bold text-uppercase mb-0"><i
                        class="icofont-info-circle"></i>2-Liste des documents requis (<?= sizeof($documents) ?>)
                    <button style="background-color:  darkblue;" onclick="onClickDocument()" type="button" rel="tooltip"
                        title="Ajouter" class="btn btn btn-sm  ml-1 text-white">
                        <i class="fas fa-plus" style="color: #ffffff"></i>

                    </button>
                </legend>
                <!-- DataTales Example -->
                <div class="table-responsive mb-4 mt-1">
                    <table class="mt-1 p-0 table table-bordered" id="dataTable11" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>nom</th>
                                <th>Etat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($documents as $key => $doc) {
                                $i++;
                            ?>
                                <tr class="p-0 m-0">
                                    <td><?= $i ?></td>
                                    <td><?= $doc->libelleDocumentRequis ?></td>
                                    <td><?= $doc->etatDocumentRequisSubvention == "1" ? "Obligatoire" : "Facultatif" ?></td>
                                    <td style="text-align : center">
                                        <button hidden type="button" rel="tooltip" title="Editer"
                                            onclick="onClickDocument(<?= $doc->idDocumentRequis  ?>)" value=""
                                            class="btn btn-sm btn-warning btn-simple btn-link" data-toggle="modal"
                                            data-target="#modalDocument">
                                            <i class="fas fa-edit" style="color: #ffffff"></i>
                                        </button>
                                        <button type="button" rel="tooltip" title="Supprimer"
                                            onclick='onClickDelete(<?= $doc->idDocumentRequis  ?>, "document")'
                                            class="btn btn-sm btn-danger btn-simple btn-link">
                                            <i class="fas fa-trash" style="color: #ffffff"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php    }
                            ?>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<!-- MODAL CRITERE -->
<div class="row mt-1" <?= $subvention ? "" : "hidden" ?>>
    <div class="col-md-12 text-left m-0 p-0">
        <div class="row mt-0 p-0">
            <fieldset>
                <legend class="text-center legend font-weight-bold text-uppercase"><i
                        class="icofont-info-circle"></i>3-Liste des critéres (<?= sizeof($criteres) ?>) <button
                        onclick="onClickCritere(0)" type="button" rel="tooltip" title="Ajouter"
                        style="background-color:  darkblue;" class="btn btn btn-sm text-white my-1  ml-1"
                        data-toggle="modal" data-target="#modalCritere">
                        <i class="fas fa-plus" style="color: #ffffff"></i>
                    </button></legend>
                <div class="col-md-12">
                    <!-- DataTales Example -->
                    <?php
                    $i = 0;
                    foreach ($criteres as $key => $critere) {
                        $i++;
                    ?>
                        <div class="row mb-3 p-0">
                            <fieldset class="m-0">
                                <legend class="legend font-weight-bold text-uppercase bg-secondary ">
                                    <div class="row col-md-12">
                                        <div class="col-md-6">
                                            <i class="icofont-info-circle my-1"></i>CRITERE N°<?= $i ?>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" rel="tooltip" title="Supprimer"
                                                onclick='onClickDelete(<?= $critere->idCritere  ?>, "critere" , "", <?= $i ?>)'
                                                class="float-right btn btn-sm btn-danger btn-simple my-1">
                                                <i class="fas fa-trash" style="color: #ffffff"></i> Supprimer
                                            </button>
                                        </div>
                                    </div>
                                </legend>
                                <div class="row  col-md-12 mt-0">
                                    <div class="col-md-8  modal-content p-2 mt-0">
                                        <div class="row">
                                            <input hidden type="text" min="0"
                                                value="<?= ($critere) ?  "$critere->idCritere" : "" ?>" name="idCritere"
                                                class="form-control" id="idCritere<?= $i ?>">
                                            <div class="col-md-5 ">
                                                <div class="col-md-12">
                                                    <label class="font-weight-bold" for="">Type de Valeur</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <select id="typeValeurCritere<?= $i ?>" name="typeValeurCritere"
                                                        class="form-control">
                                                        <option value="Pourcentage"
                                                            <?= ($critere && $critere->typeValeurCritere == "Pourcentage") ?  "selected" : "" ?>>
                                                            Pourcentage</option>
                                                        <option value="Montant"
                                                            <?= ($critere && $critere->typeValeurCritere == "Montant") ?  "selected" : "" ?>>
                                                            Montant</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="col-md-12">
                                                    <label class="font-weight-bold" for="">Valeur</label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input required type="number" min="0"
                                                        value="<?= ($critere) ?  "$critere->valeurCritere" : "" ?>"
                                                        name="valeurCritere" class="form-control"
                                                        id="valeurCritere<?= $i ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-2">
                                                <button onclick="saveCritere(<?= $i ?>)"
                                                    style="background-color:  darkblue;" type="button" rel="tooltip"
                                                    title="Ajouter"
                                                    class="form-control float-left btn btn-sm text-white mt-4">
                                                    <i class="fas fa-save" style="color: #ffffff"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 m-0 p-0">
                                        <div class=" float-right mt-5">
                                            <button onclick="onClickCondition(<?= $critere->idCritere ?>, <?= $i ?>)"
                                                type="button" style="background-color:  darkblue;" rel="tooltip"
                                                title="Ajouter" class="float-left btn btn-sm text-white">
                                                <i class="fas fa-plus" style="color: #ffffff"></i> Ajouter une condition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1" <?= sizeof($critere->conditions) != 0 ? "" : "hidden" ?>>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Libelle</th>
                                                    <th>Operateur</th>
                                                    <th>Valeur</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $j = 0;
                                                foreach ($critere->conditions as $key => $art) {
                                                    $j++;
                                                ?>
                                                    <tr>
                                                        <td><?= $j ?></td>
                                                        <td><?= $art->libelleTypeCondition ?></td>
                                                        <td><?= $art->operateur ?></td>
                                                        <td><?= $art->valeur ?></td>
                                                        <td style="text-align : center">
                                                            <button type="button" rel="tooltip" title="Supprimer"
                                                                onclick='onClickDelete(<?= $art->idCondition  ?>, "condition", <?= $critere->idCritere ?>,  <?= $i ?>)'
                                                                class="btn btn-sm btn-danger btn-simple btn-link">
                                                                <i class="fas fa-trash" style="color: #ffffff"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mt-4 text-center col-md-12"
                                    <?= sizeof($critere->conditions) != 0 ? "hidden" : "" ?>>
                                    <h5 class="text-danger text-center font-weight-bold">Aucune condition pour ce critére
                                    </h5>
                                </div>
                            </fieldset>
                        </div>
                    <?php  }
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>

<!-- NEW CRITERE Modal-->
<div class="modal fade" id="modalCritere" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title font-weight-bold text-white">Ajout d'un nouveau critére</h5>
                <button class="close text-white btn" type="button" data-dismiss="modal" aria-label="Close"
                    style='font-size:28px; color:white; border: none !important; padding: 0px;'>
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input hidden type="number" min="0" value="0" name="valeurCritere" class="form-control"
                        id="idCritere0">
                    <div class="col-md-6 ">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="">Type de Valeur</label>
                        </div>
                        <div class="col-md-12">
                            <select class="form-control" id="typeValeurCritere0" name="typeValeurCritere"
                                class="form-control">
                                <option value="Pourcentage">
                                    Pourcentage</option>
                                <option value="Montant">
                                    Montant</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="">Valeur</label>
                        </div>
                        <div class="col-md-12">
                            <input type="number" min="0" value="" name="valeurCritere" class="form-control"
                                id="valeurCritere0">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="saveCritere(0)" class="btn btn-success">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- Condition Modal-->
<div class="modal fade" id="modalCondition" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title font-weight-bold text-white" id="titreCondition">Ajout d'une Condition</h5>
                <button class="close text-white btn" type="button" data-dismiss="modal" aria-label="Close"
                    style='font-size:28px; color:white; border: none !important; padding: 0px;'>
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-0">
                    <div class="col-md-12 text-left ">
                        <div class="card ">
                            <div class="col-md-12 mx-0">
                                <!-- progressbar -->
                                <div class="row register-form mt-0">
                                    <fieldset>
                                        <legend class="text-center legend font-weight-bold text-uppercase"
                                            style="width: 100vh;"><i class="icofont-info-circle"></i></legend>
                                        <div class="row">
                                            <div class="col-md-4 mb-1">
                                                <div class="col-md-12">
                                                    <label for="">Libelle <small class="text-danger">*</small></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <select onchange="onChangeCondition()" name="typeCondition"
                                                        class="form-control" id="typeCondition">
                                                        <option value="">--- Veuillez choisir ---</option>
                                                        <?php
                                                        foreach ($typeConditions as $key => $value) {
                                                        ?>
                                                            <option value="<?= $value->idTypeCondition ?>">
                                                                <?= $value->libelleTypeCondition ?></option>
                                                        <?php }
                                                        ?>
                                                        <option value="Autre">Autre</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <div class="col-md-12">
                                                    <label for="">Opérateur <small class="text-danger">*</small></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <select name="operateurCondtion" class="form-control"
                                                        id="operateurCondtion">
                                                        <option value="">--- Veuillez choisir ---</option>
                                                        <?php
                                                        foreach ($operateurs as $key => $value) {
                                                        ?>
                                                            <option
                                                                value="<?= $value['signe'] . ";" . $value['libelleOperateur'] ?>">
                                                                <?= $value['libelleOperateur'] ?></option>
                                                        <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-1">
                                                <div class="col-md-12">
                                                    <label for="">Valeur <small class="text-danger">*</small></label>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="text" name="valeurCondition" class="form-control"
                                                        id="valeurCondition">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-1" id="divAutreCondition" hidden>
                                                <div class="col-md-12">
                                                    <input type="text" name="autreTypeCondition" class="form-control"
                                                        id="autreTypeCondition">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="saveCondition()" class="btn btn-success">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- SUPPRESSION SUBVENTION Modal-->
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #E74A3B">
                <h5 class="modal-title font-weight-bold text-white">Suppression</h5>
                <button class="close text-white btn" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div>
                <div class="modal-body">
                    <h3 class="modal-title font-weight-bold text-danger text-center" id="textDelete"> Voulez-vous
                        supprimer ?</h3>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Non</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DOCUMENT -->
<div class="modal fade" id="modalDocument" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title font-weight-bold text-white">AJOUT DOCUMENTS REQUIS</h5>
                <button class="close text-white btn" type="button" data-dismiss="modal" aria-label="Close"
                    style='font-size:28px; color:white; border: none !important; padding: 0px;'>
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>nom</th>
                                <th>Etat</th>
                                <th>Commentaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($allDocuments as $key => $doc) {
                                $i++;
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="checkDoc" name="checkDoc"
                                            value="<?= $doc->idDocumentRequis ?>">
                                    </td>
                                    <td><?= $i ?></td>
                                    <td><?= $doc->libelleDocumentRequis ?></td>
                                    <td class="d-flex ">
                                        <div class="col-md-6">
                                            <input type="radio" class="etat<?= $doc->idDocumentRequis ?>"
                                                name="etat<?= $doc->idDocumentRequis ?>" value="1" checked>
                                            <label>Obligatoire</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="radio" class="etat<?= $doc->idDocumentRequis ?>"
                                                name="etat<?= $doc->idDocumentRequis ?>" value="0">
                                            <label>Facultatif</label>
                                        </div>
                                    </td>
                                    <td><?= $doc->commentaire ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" <?= sizeof($allDocuments) != 0 ? "" : " hidden" ?>>
                <button type="button" onclick="saveDocument()" class="btn btn-success">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- modal de chargement -->
<div class="modal fade" id="loadingModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-danger" style="width: 5vw; height: 10vh;">
                </div>
                <br><br><br>
                <h3>Chargement...</h3>
            </div>
        </div>
    </div>
</div>

<!-- modal success -->
<div class="modal fade" id="successOperation" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 id="msgSuccess" class="" style="color:green">Email envoyé !!</h3>
                <button onclick="" id="buttonConfirmContact" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- modal Error -->
<div class="modal fade" id="errorOperation" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 id="msgError" class="" style="color:red">Email envoyé !!</h3>
                <button onclick="" id="buttonConfirmContact" class="btn btn-danger" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= URLROOT ?>/public/assets/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript">
    const URLROOT = document.getElementById("URLROOT").value;
    var idEmp = 0;
    var actionCritere = "";
    let idCritere = "";
    let idDocument = "";
    let typeDelete = "";
    let idCondition = "";
    //CONDITION
    function onClickCondition(id, indexCritere) {
        idCritere = id;
        $("#titreCondition").text(
            "Ajout d'une condition au critere N°" + indexCritere
        );
        $('#modalCondition').modal('show');
    }

    function onChangeCondition() {
        let typeCondition = document.getElementById("typeCondition").value;
        if (typeCondition == "Autre") {
            document.getElementById("divAutreCondition").removeAttribute("hidden");
        } else {
            document.getElementById("divAutreCondition").setAttribute("hidden", "hidden");
        }
    }

    function saveCondition() {
        var idSubvention = document.getElementById('idSubvention').value;
        var typeCondition = document.getElementById('typeCondition').value;
        var operateurCondtion = document.getElementById('operateurCondtion').value;
        var valeurCondition = document.getElementById('valeurCondition').value;
        var autreTypeCondition = document.getElementById('autreTypeCondition').value;
        if (typeCondition != "" && valeurCondition != "" && typeCondition != "" && (typeCondition != "Autre" || (
                typeCondition ==
                "Autre" && autreTypeCondition != ""))) {
            console.log(typeCondition);

            //SAVE
            $.ajax({
                url: '<?= URLROOT . "/public/json/critere.php?action=saveCondition" ?>',
                method: 'POST',
                data: JSON.stringify({
                    idSubvention: idSubvention,
                    idCritere: idCritere,
                    idCondition: '0',
                    idTypeConditionF: typeCondition,
                    autreTypeCondition: autreTypeCondition,
                    valeurCondition: valeurCondition,
                    operateur: operateurCondtion.split(';')[1],
                    signeOperateur: operateurCondtion.split(';')[0],
                    idAuteur: '<?= $_SESSION["nomUser"]->idUtilisateur ?>'
                }),
                dataType: "JSON",
                beforeSend: function() {
                    $('#loadingModal').modal('show');
                },
                success: function(response) {
                    console.log("save critere");
                    console.log(response);
                    if (response == "1") {
                        setTimeout(() => {
                            $('#modalCondition').modal('hide');
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        location.reload();
                    }
                },
                error: function(response) {
                    console.log("ERROR");
                    console.log(response);
                    setTimeout(() => {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                    $("#msgError").text(
                        "Erreur d'enregistrement !");
                    $('#errorOperation').modal('show');
                },
                complete: function() {
                    setTimeout(() => {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                },
            });

        } else {
            $("#msgError").text(
                "Tous les champs sont obligatoires !");
            $('#errorOperation').modal('show');
        }
    }

    function deleteCondition() {
        $.ajax({
            url: '<?= URLROOT . "/public/json/critere.php?action=deleteConditionCritere" ?>',
            method: 'POST',
            data: JSON.stringify({
                idCondition: idCondition,
                idCritere: idCritere
            }),
            dataType: "JSON",
            beforeSend: function() {
                $('#loadingModal').modal('show');
            },
            success: function(response) {
                // console.log("delete condition");
                // console.log(response);
                if (response == "1") {
                    setTimeout(() => {
                        $('#modalDelete').modal('hide');
                        $('#loadingModal').modal('hide');
                    }, 1000);

                    location.reload();
                }
            },
            error: function(response) {
                console.log("ERROR");
                console.log(response);
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
                $("#msgError").text(
                    "Erreur de suppression !");
                $('#errorOperation').modal('show');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
            },
        });
    }



    //critere
    function onClickCritere(id) {
        if (id == 0) {
            actionCritere = "add";
            loadDataCritere(null, true);
        } else {
            actionCritere = "edit"
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `${URLROOT}/public/json/critere.php?action=find&id=${id}`,
                success: function(data) {
                    console.log(data);
                    if (data != undefined && data != null && data != "false") {
                        loadDataCritere(data, false);
                    } else {
                        $("#msgError").text(
                            "Impossible de charger les infos, contacter l'administrateur !"
                        );
                        $('#errorOperation').modal('show');
                    }
                },
                error: function(jqXHR, error, errorThrown) {
                    console.log("error");
                    console.log(jqXHR.responseText);
                    $("#msgError").text(
                        "Impossible de charger les infos, contacter l'administrateur !"
                    );
                    $('#errorOperation').modal('show');
                }
            });
        }
    }

    function loadDataCritere(data = null, readOnly = false) {
        if (data != null) {
            document.getElementById("idCritere").value = data['idCritere'];
            document.getElementById("typeCondition").value = data['idTypeConditionF'];
            document.getElementById("operateurCondtion").value = data['signeOperateur'] + ";" + data['operateur'];
            document.getElementById("valeurCondition").value = data['valeur'];
        } else {
            document.getElementById("idCritere").value = "";
            document.getElementById("typeCondition").value = "";
            document.getElementById("operateurCondtion").value = "";
            document.getElementById("valeurCondition").value = "";
        }
    }

    function saveCritere(index) {
        var idSubvention = document.getElementById('idSubvention').value;
        var idCritere = document.getElementById('idCritere' + index).value;
        var valeurCritere = document.getElementById('valeurCritere' + index).value;
        var typeValeurCritere = document.getElementById('typeValeurCritere' + index).value;
        if (valeurCritere != "" && typeValeurCritere != "") {
            //SAVE
            $.ajax({
                url: '<?= URLROOT . "/public/json/critere.php?action=saveCritere" ?>',
                method: 'POST',
                data: JSON.stringify({
                    idSubvention: idSubvention,
                    idCritere: idCritere,
                    valeurCritere: valeurCritere,
                    typeValeurCritere: typeValeurCritere,
                    idAuteur: '<?= $_SESSION["nomUser"]->idUtilisateur ?>'
                }),
                dataType: "JSON",
                beforeSend: function() {
                    $('#loadingModal').modal('show');
                },
                success: function(response) {
                    console.log("save critere");
                    console.log(response);
                    if (response == "1") {
                        setTimeout(() => {
                            $('#modalCondition').modal('hide');
                            $('#loadingModal').modal('hide');
                        }, 1000);
                        location.reload();
                    }
                },
                error: function(response) {
                    console.log("ERROR");
                    console.log(response);
                    setTimeout(() => {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                    $("#msgError").text(
                        "Erreur d'enregistrement !");
                    $('#errorOperation').modal('show');
                },
                complete: function() {
                    setTimeout(() => {
                        $('#loadingModal').modal('hide');
                    }, 1000);
                },
            });
        } else {
            $("#msgError").text(
                "Tous les champs sont obligatoires !");
            $('#errorOperation').modal('show');
        }
    }

    function deleteCritere() {
        var idSubvention = document.getElementById('idSubvention').value;
        $.ajax({
            url: '<?= URLROOT . "/public/json/critere.php?action=deleteCritereSubvention" ?>',
            method: 'POST',
            data: JSON.stringify({
                idSubvention: idSubvention,
                idCritere: idCritere
            }),
            dataType: "JSON",
            beforeSend: function() {
                $('#loadingModal').modal('show');
            },
            success: function(response) {
                // console.log("delete critere");
                // console.log(response);
                if (response == "1") {
                    setTimeout(() => {
                        $('#modalDelete').modal('hide');
                        $('#loadingModal').modal('hide');
                    }, 1000);

                    location.reload();
                }

            },
            error: function(response) {
                console.log("ERROR");
                console.log(response);
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
                $("#msgError").text(
                    "Erreur de suppression !");
                $('#errorOperation').modal('show');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
            },
        });
    }
    //DOCUMENT
    function onClickDocument() {
        $('#modalDocument').modal('show');
    }

    function saveDocument() {
        var idSubvention = document.getElementById('idSubvention').value;
        var docs = document.getElementsByName('checkDoc');
        let tabDocSub = [];
        for (let index = 0; index < docs.length; index++) {
            if (docs[index].checked) {
                let elt = {
                    "idSubvention": idSubvention,
                    "idDocumentRequis": docs[index].value,
                    "etat": $(".etat" + docs[index].value + ":radio:checked").val()
                }
                tabDocSub.push(elt);
            }
        }

        $.ajax({
            url: '<?= URLROOT . "/public/json/subvention.php?action=saveDocumentRequisSubvention" ?>',
            method: 'POST',
            data: JSON.stringify(tabDocSub),
            dataType: "JSON",
            beforeSend: function() {
                $('#loadingModal').modal('show');
            },
            success: function(response) {
                console.log("save documents");
                console.log(response);
                if (response == "1") {
                    setTimeout(() => {
                        $('#modalDocument').modal('hide');
                        $('#loadingModal').modal('hide');
                    }, 1000);

                    location.reload();
                }
            },
            error: function(response) {
                console.log("ERROR");
                console.log(response);
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
                $("#msgError").text(
                    "Erreur d'enregistrement !");
                $('#errorOperation').modal('show');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
            },
        });
    }

    function onClickDelete(id, type, idCrit = "", index = "") {
        typeDelete = type;
        if (typeDelete == 'critere') {
            idCritere = id
            $("#textDelete").text(
                "Voulez-vous supprimer le critére N°" + index + " ?");
        } else {
            if (typeDelete == "document") {
                $("#textDelete").text(
                    "Voulez-vous supprimer ce document ?");
                idDocument = id
            } else {
                if (typeDelete == "condition") {
                    $("#textDelete").text(
                        "Voulez-vous supprimer cette condition du critére N°" + index + " ?");
                    idCondition = id
                    idCritere = idCrit
                }
            }
        }
        $('#modalDelete').modal('show');
    }

    function confirmDelete() {
        console.log(typeDelete)
        if (typeDelete == "critere") {
            deleteCritere()
        } else {
            if (typeDelete == "document") {
                deleteDocument()
            } else {
                if (typeDelete == "condition") {
                    deleteCondition()
                }
            }
        }
    }



    function deleteDocument() {
        var idSubvention = document.getElementById('idSubvention').value;
        $.ajax({
            url: '<?= URLROOT . "/public/json/subvention.php?action=deleteDocumentSubvention" ?>',
            method: 'POST',
            data: JSON.stringify({
                idSubvention: idSubvention,
                idDocumentRequis: idDocument
            }),
            dataType: "JSON",
            beforeSend: function() {
                $('#loadingModal').modal('show');
            },
            success: function(response) {
                // console.log("delete critere");
                // console.log(response);
                if (response == "1") {
                    setTimeout(() => {
                        $('#modalDelete').modal('hide');
                        $('#loadingModal').modal('hide');
                    }, 1000);

                    location.reload();
                }

            },
            error: function(response) {
                console.log("ERROR");
                console.log(response);
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
                $("#msgError").text(
                    "Erreur de suppression !");
                $('#errorOperation').modal('show');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loadingModal').modal('hide');
                }, 1000);
            },
        });
    }
</script>