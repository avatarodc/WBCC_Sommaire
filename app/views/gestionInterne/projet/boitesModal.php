<!-- modal incident -->
<div class="modal fade" id="modalIncident" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h2 class="text-center font-weight-bold">Déclarer un incident</h2>
            </div>
            <div class="modal-body">
                <form class="form">
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" name="" id="dateIncident" readonly class="form-control"
                            value="<?= date('d-m-Y H:i') ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Auteur</label>
                        <input type="text" name="" id="auteurIncident" readonly class="form-control"
                            value="<?= $_SESSION['connectedUser']->prenomContact . ' ' . $_SESSION['connectedUser']->nomContact ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Raison de l'incident </label>
                        <textarea name="" id="incidentText" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="offset-6 col-md-2">
                    <button class="btn btn-danger" data-dismiss="modal">Annuler</button>
                </div>
                <div class="offset-1 col-md-2">
                    <button class="btn btn-success" onclick="declarerIncident()">Valider</button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- modal Confirmation CLOTURE ACTIVITE -->
<div class="modal fade" id="modalConfirmClotureActivity" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-md bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <input name="action" value="" id="typeActivity" hidden>
                <h3 class="text-black font-weight-bold" id="textCloture">Voulez-vous clôturer l'activité ?</h3>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-danger" data-dismiss="modal">Non</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="onConfirmClotureActivity()">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal note -->
<div class="modal fade" id="viewNote" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h2 class="text-center font-weight-bold">Note</h2>
            </div>
            <input type="text" id="idNote" readonly hidden class="form-control" value="">
            <input type="text" id="actionNote" readonly hidden class="form-control" value="">
            <div class="modal-body">
                <form class="form">
                    <div class="form-group">
                        <label for="">Date</label>
                        <input type="text" name="" id="dateNote" readonly class="form-control"
                            value="<?= date('d-m-Y H:i') ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Auteur</label>
                        <input type="text" name="" id="auteurNote" readonly class="form-control"
                            value="<?= $_SESSION['connectedUser']->prenomContact . ' ' . $_SESSION['connectedUser']->nomContact ?>">
                    </div>
                    <div class="form-group">
                        <label for="">Note</label>
                        <textarea name="" id="noteText" cols="30" rows="10" readonly class="form-control"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" id="actionForNote">
                <button onclick='' class='btn btn-danger' data-dismiss='modal'>Annuler</button>
                <button id="btnSaveNote" onclick="saveNote('note')" class='btn btn-success'>Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<!-- modal question NOTE -->
<div class="modal fade" id="questionPublicationNote" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <input hidden readonly id="publieNote" value="">
            <div class="modal-body text-center">
                <h3 id="msgPublicationNote" class="text-danger font-weight-bold">Voulez-vous Publier cette note ?</h3>
            </div>
            <div class="modal-footer">
                <div class="offset-8 col-md-1">
                    <button class="btn btn-danger" id="nonPublicationNote" data-dismiss="modal">Non</button>
                </div>
                <div class="offset-1 col-md-1">
                    <button class="btn btn-success" id="okPublicationNote" onclick="confirmPublierNote()">Oui</button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- modal de confirmation -->
<div>
    <div class="modal fade modal-center" id="successOperation" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg bg-white">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 id="msgSuccess" class="" style="color:green">Email envoyé !!</h3>
                    <button onclick="" id="buttonConfirmContact" class="btn btn-success"
                        data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal ERROR -->
<div>
    <div class="modal fade modal-center" id="errorOperation" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg bg-white">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h3 id="msgError" class="" style="color:red">Email envoyé !!</h3>
                    <button onclick="" class="btn btn-danger" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal de chargement -->
<div class="modal fade" id="loadingModal" data-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-danger" style="width: 5vw; height: 10vh;">
                </div>
                <br><br><br>
                <h3 id="msgLoading">Génération de délégation en cours...</h3>
            </div>
        </div>
    </div>
</div>

<!-- modal Alert copy -->
<div class="modal fade" id="modalAlertCopy" data-dismiss="modal" tabindex="-1">
    <div class="modal-dialog modal-md bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="alert alert-success" role="alert">
                    Numéro de téléphone copié !
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal Confirmation REPROGRAMMER ACTIVITY -->
<div class="modal fade" id="modalConfirmReprogrammerActivity" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-md bg-white">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="text-center text-black font-weight-bold" id="textReprogrammer">Voulez-vous clôturer
                    l'activité ?</h3>
                <div class="row">
                    <div class="col-md-8">
                        <div>
                            <label for="">Nouvelle Date</label>
                        </div>
                        <div>
                            <input name="action" value="" id="typeActivity" hidden>
                            <input name="action" value="" id="codeActivity" hidden>
                            <input value="<?= $activityRVTravaux ? $activityRVTravaux->startTime : '' ?>"
                                id="dateActivityLA" hidden>
                            <input class="form-control" type="date" name="dateNewActivity" id="dateNewActivity"
                                value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <label for="">Heure</label>
                        </div>
                        <div>
                            <input class="form-control" type="time" name="heureNewActivity" id="heureNewActivity"
                                value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div>
                            <label for="">Commentaire</label>
                        </div>
                        <div>
                            <textarea rows="5" class="form-control" id="commentaireNewActivity"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-danger" data-dismiss="modal">Non</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success" onclick="onConfirmProgrammerActivity()">Oui</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- les modal pour ajout du contact sinistre -->
<div class="modal fade" id="selectContactSinistre" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h2 class="text-center font-weight-bold">Choisissez le contact</h2>
                <button type="" onclick="showModalAddContact('contact')" id="" class="btn btn-info">Ajouter</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable3" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Nom </th>
                                <th>Prenom </th>
                                <th>Email</th>
                                <th>Telephone</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($tousContacts as $cnt) {
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="oneselection" name="checkSinitre"
                                            value="<?= $cnt->idContact ?>,<?= $cnt->numeroContact ?>">
                                    </td>
                                    <td><?= $i++ ?></td>
                                    <td><?= $cnt->nomContact ?></td>
                                    <td><?= $cnt->prenomContact ?></td>
                                    <td><?= $cnt->emailContact ?></td>
                                    <td><?= $cnt->telContact ?></td>
                                </tr>
                            <?php  }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="" onclick="" class="btn btn-danger" data-dismiss="modal">Annuler</button>
                <button type="" onclick="" id="buttonConfirmContact" class="btn btn-success">Valider</button>
            </div>
        </div>
    </div>
</div>

<!-- les modal pour ajout de immeuble -->
<div class="modal fade" id="selectImmeuble" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h2 class="text-center font-weight-bold">Choisissez un immeuble</h2>
                <button type="" onclick="showModalAddImmeuble()" id="" class="btn btn-info">Ajouter</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable5" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Code Immeuble </th>
                                <th>Adresse </th>
                                <th>Code Postal</th>
                                <th>Ville</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($allImmeubles as $imm) {
                            ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="oneselection" name="checkImmeuble"
                                            value="<?= $imm->idImmeuble ?>,<?= $imm->numeroImmeuble ?>,<?= $imm->codeWBCC ?>">
                                    </td>
                                    <td><?= $i++ ?></td>
                                    <td><?= $imm->codeWBCC ?></td>
                                    <td><?= $imm->adresse ?></td>
                                    <td><?= $imm->codePostal ?></td>
                                    <td><?= $imm->ville ?></td>
                                </tr>
                            <?php  }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="" onclick="" id="" class="btn btn-success" data-dismiss="modal">Annuler</button>
                <button type="" onclick="AddOrEditImmeuble('editImm')" id="" class="btn btn-success">Valider</button>
            </div>
        </div>
    </div>
</div>


<!-- modal ajout nouveau contact -->
<div class="modal fade" id="modalAddOrEditContact" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="card">
                <div class="card-header bg-secondary">
                    <div class="row">
                        <div class="col-md-7">
                            <h5 class="mt-2 text-white" id="exampleModalLabel">NOUVEAU CONTACT</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mt-0" id="msform">
                        <div class="modal-body mt-0">
                            <div class="row mt-0">
                                <div class="col-md-12 text-left ">
                                    <div class="card ">
                                        <div class="col-md-12 mx-0">
                                            <!-- progressbar -->
                                            <div class="row register-form mt-0">
                                                <fieldset>
                                                    <legend class="text-center legend font-weight-bold text-uppercase">
                                                        <i class="icofont-info-circle"></i>Carte de visite (1)
                                                    </legend>
                                                    <input type="hidden" name="idContact" id="idContactAdd" value="0">

                                                    <input type="hidden" name="idOpportunity"
                                                        value="<?= ($op) ? $op->idOpportunity : "" ?>">
                                                    <input type="hidden" name="URLROOT" id="URLROOT"
                                                        value="<?= URLROOT ?>">
                                                    <div class="row">
                                                        <div class="row ">
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Civilité </label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <select name="civilite" id="civilite"
                                                                        class="form-control">
                                                                        <option value="">-- Choisir --</option>
                                                                        <option value="M">Monsieur</option>
                                                                        <option value="Mme">Madame</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Prénom <small
                                                                            class="text-danger">*</small></label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="prenom"
                                                                        class="form-control" id="prenom">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Nom<small
                                                                            class="text-danger">*</small></label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="nom" class="form-control"
                                                                        id="nom">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Ligne Directe<small
                                                                            class="text-danger">*</small></label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="tel" name="tel1" class="form-control"
                                                                        id="tel1">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Portable</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="tel" name="tel2" class="form-control"
                                                                        id="tel2">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Ligne Standard</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="tel" name="tel3" class="form-control"
                                                                        id="tel3">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Email Personnel<small
                                                                            class="text-danger">*</small></label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="email" name="email"
                                                                        class="form-control" id="email">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Email Collaboratif</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="email" name="emailCollaboratif"
                                                                        class="form-control" id="emailCollaboratif">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Statut</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <select name="statut" id="statut"
                                                                        class="form-control">
                                                                        <option value="" disabled>-- Choisir --</option>
                                                                        <option value="Locataire" selected>
                                                                            Locataire</option>
                                                                        <option value="Copropriétaire Occupant">
                                                                            Copropriétaire Occupant</option>
                                                                        <option value="Copropriétaire Non Occupant">
                                                                            Copropriétaire Non Occupant</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 text-left">
                                    <div class="card ">
                                        <div class="col-md-12 mx-0">
                                            <!-- progressbar -->
                                            <div class="row register-form mt-0">
                                                <fieldset>
                                                    <legend class="text-center legend font-weight-bold text-uppercase">
                                                        <i class="icofont-location-pin"></i>Adresse (2)
                                                    </legend>
                                                    <div class="row">
                                                        <div class="row">
                                                            <div class="col-md-8 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Adresse </label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="adresse1"
                                                                        class="form-control" id="adresse1C">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Code Postal</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" maxlength="5"
                                                                        onchange="changePostalCodeC()" name="codePostal"
                                                                        class="form-control" id="codePostalC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Ville</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" readonly name="ville"
                                                                        class="form-control" id="villeC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Département</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" readonly name="departement"
                                                                        class="form-control" id="departementC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Région</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" readonly name="region"
                                                                        class="form-control" id="regionC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Numéro Porte</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="porte" class="form-control"
                                                                        id="porteC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Numéro Bâtiment</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="batiment"
                                                                        class="form-control" id="batimentC">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 mb-1">
                                                                <div class="col-md-12">
                                                                    <label for="">Numéro Etage</label>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <input type="text" name="etage" class="form-control"
                                                                        id="etageC">
                                                                </div>
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
                            <button class="btn btn-danger" type="button" data-dismiss="modal">Annuler</button>
                            <button class="btn btn-success" id="engCnt" type="button"
                                onclick="saveContactBD('')">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- modal Cloture OP -->
<div class="modal fade" id="clotureOPModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3 class="text-danger font-weight-bold">Voulez-vous clôturer cette opportunité '<?= $op->name ?>' ?
                </h3>
                <div class="col-md-6 mt-2 offset-3">
                    <div>
                        <select class="form-control" aria-label="Default select example" name="typeClotureOP"
                            id="typeClotureOP">
                            <option value="Won" selected>Clôturée gagnée</option>
                            <option value="Lost">Clôturée Perdue</option>
                            <option value="Tranche 2">Tranche 2</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-10 offset-1 mt-3">
                    <textarea class="form-control" id="commentaireClotureOP" rows="5"
                        placeholder="commentaire..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="offset-8 col-md-1">
                    <button class="btn btn-danger" data-dismiss="modal">Non</button>
                </div>
                <div class="offset-1 col-md-1">
                    <button class="btn btn-success" onclick="onConfirmCloturerOP()">Oui</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOthersOp" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg bg-white">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h4 class="text-center font-weight-bold">Liste des opportunités en attente de déclaration de sinistre
                    avec la même
                    compagnie d'assurance'<?= $op->cie ? $op->cie->name : "" ?>' (<?= sizeof($otherOpWSameCie) ?>)</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable4" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>#</th>
                                <th>N° Dossier</th>
                                <th>DO</th>
                                <th>Gestionnaire Imm/App</th>
                                <th>Statut</th>
                                <th>Commercial</th>
                                <th>Type de dossier</th>
                                <th>Partie concernée</th>
                                <th>Date d'ouverture</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            if (sizeof($otherOpWSameCie) != 0) {
                                foreach ($otherOpWSameCie as $opp) {
                            ?>
                                    <tr style="background-color: <?= ($opp->demandeCloture == 1) ? 'lightgray' : '' ?>">
                                        <td style="text-align : center">
                                            <a target="_blank" type="button" rel="tooltip"
                                                title="Faire la relance pour prise en charge de devis"
                                                href="<?= linkto('Gestionnaire', 'fd', $opp->idOpportunity) ?>"
                                                class="btn btn-sm btn-info btn-simple btn-link">
                                                <i class="fas fa-folder-open" style="color: #ffffff"></i>
                                            </a>
                                        </td>
                                        <td><?= $i++ ?></td>
                                        <td><?= $opp->name ?></td>
                                        <td><?= $opp->contactClient ?></td>
                                        <td><?= $opp->nomGestionnaireAppImm ?></td>
                                        <td>
                                            <?= ($opp->status == 'Lost' ? 'Clôtué Perdu' : ($opp->status == 'Won' ? 'Clôturé gagné' : ($opp->status == 'Inactive' ? 'Inactif' : 'Ouvert'))) ?>
                                        </td>
                                        <td><?= $opp->commercial ?></td>
                                        <td><?= $opp->type ?></td>
                                        <td><?= $opp->typeSinistre ?></td>
                                        <td data-sort="<?= strtotime(str_replace('/', '-', $opp->createDate)) ?>">
                                            <?= date('d/m/Y', strtotime(str_replace('/', '-', $opp->createDate))) ?></td>
                                    </tr>
                            <?php
                                }
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="" onclick="" id="" class="btn btn-success" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>