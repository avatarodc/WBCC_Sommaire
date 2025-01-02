<?php
$active = "red";
?>
<!-- ======= Avantages Section ======= -->
<div class="section-title">
    <h2><button onclick="document.location.href='<?= URLROOT ?>/GestionInterne/indexSubvention'"><i
                class="fas fa-fw fa-arrow-left" style="color: #c00000"></i></button> <span><i
                class="fas fa-fw fa-warehouse" style="color: #c00000"></i></span> PARAMETRAGE DES SUBVENTIONS</h2>
</div>

<form id="msform" method="POST" action="<?= linkTo("GestionInterne", "saveSubvention") ?>">
    <input type='text' id='idUtilisateur' class='form-control' value='<?= $_SESSION['connectedUser']->idUtilisateur ?>'
        hidden>
    <input type='text' id='auteur' class='form-control' value='<?= $_SESSION['connectedUser']->fullName ?>' hidden>
    <input type='text' id='numeroAuteur' class='form-control' value='<?= $_SESSION['connectedUser']->numeroContact ?>'
        hidden>
    <input type="hidden" name="URLROOT" id="URLROOT" value="<?= URLROOT ?>">

</form>

<div class="row mt-3">
    <div class="col-md-12 text-left">
        <div class="col-md-12 mx-0">
            <div class="row register-form mt-0 p-0">
                <fieldset>
                    <legend class="text-center legend font-weight-bold text-uppercase"><i
                            class="icofont-info-circle"></i>Liste des documents requis</legend>
                    <div class="col-md-12">
                        <div class="row ml-1 mt-0">
                            <div class="float-left mt-0 mb-3">
                                <button onclick="onClickDocument(0)" type="button" rel="tooltip" title="Ajouter"
                                    class="btn btn btn-sm btn-primary  ml-1">
                                    <i class="fas fa-plus" style="color: #ffffff"></i>
                                    Ajouter un document
                                </button>
                            </div>
                        </div>
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nom</th>
                                                <th>Commentaire</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            foreach ($documents as $key => $doc) {
                                                $i++;
                                            ?>
                                                <tr>
                                                    <td><?= $i ?></td>
                                                    <td><?= $doc->libelleDocumentRequis ?></td>
                                                    <td><?= $doc->commentaire ?></td>
                                                    <td style="text-align : center">
                                                        <button type="button" rel="tooltip" title="Editer"
                                                            onclick="onClickDocument(<?= $doc->idDocumentRequis  ?>)"
                                                            value="" class="btn btn-sm btn-warning btn-simple btn-link">
                                                            <i class="fas fa-edit" style="color: #ffffff"></i>
                                                        </button>
                                                    </td>

                                                </tr>
                                            <?php    }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
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
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
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
<div class="modal fade" id="modalDocument" data-backdrop="" tabindex="-1" aria-hidden="true"
    style="overflow:scroll;background-color: rgba(255,255,255,0.5); backdrop-filter : blur(0px)">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h5 class="modal-title font-weight-bold text-white">DOCUMENTS REQUIS</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"
                    style='font-size:28px; color:red; border: none !important; padding: 0px;'>
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input hidden type="number" min="0" value="" class="form-control" id="idDocumentRequis">
                    <div class="col-md-12 ">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="">Nom du Document</label>
                        </div>
                        <div class="col-md-12">
                            <input type="text" value="" class="form-control" id="libelleDocumentRequis">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="">Commentaire</label>
                        </div>
                        <div class="col-md-12">
                            <Textarea class="form-control" id="commentaireDocumentRequis" rows="4">

                            </Textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
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

    //DOCUMENT
    function onClickDocument(id) {
        document.getElementById("idDocumentRequis").value = id;
        if (id == 0) {
            document.getElementById("libelleDocumentRequis").value = "";
            document.getElementById("commentaireDocumentRequis").value = "";
            $('#modalDocument').modal('show');
        } else {
            actionCritere = "edit"
            $.ajax({
                type: "GET",
                dataType: "JSON",
                url: `${URLROOT}/public/json/subvention.php?action=findDocumentRequisByID&id=${id}`,
                success: function(data) {
                    // console.log(data);
                    if (data != undefined && data != null && data != "false") {
                        document.getElementById("libelleDocumentRequis").value = data['libelleDocumentRequis'];
                        document.getElementById("commentaireDocumentRequis").value = data['commentaire'];
                        $('#modalDocument').modal('show');
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

    function saveDocument() {
        var idDocumentRequis = document.getElementById('idDocumentRequis').value;
        var libelleDocumentRequis = document.getElementById('libelleDocumentRequis').value;
        var commentaireDocumentRequis = document.getElementById('commentaireDocumentRequis').value;

        $.ajax({
            url: '<?= URLROOT . "/public/json/subvention.php?action=saveDocumentRequis" ?>',
            method: 'POST',
            data: JSON.stringify({
                idDocumentRequis: idDocumentRequis,
                libelleDocumentRequis: libelleDocumentRequis,
                commentaireDocumentRequis: commentaireDocumentRequis,
                idAuteur: '<?= $_SESSION["nomUser"]->idUtilisateur ?>'
            }),
            dataType: "JSON",
            beforeSend: function() {
                $('#loadingModal').modal('show');
            },
            success: function(response) {
                console.log("save documents");
                // console.log(response);
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

    function onClickDelete(id, type) {
        typeDelete = type;
        if (typeDelete == 'critere') {
            idCritere = id
            $("#textDelete").text(
                "Voulez-vous supprimer ce critére ?");
        } else {
            $("#textDelete").text(
                "Voulez-vous supprimer ce document ?");
            idDocument = id
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