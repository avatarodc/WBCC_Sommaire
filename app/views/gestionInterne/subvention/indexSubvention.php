<?php
// $hidden = (isset($etape) && $etape != "") ? "" : "hidden";
// $hiddenFinal = (isset($etape) && $etape != "") ? "hidden" : "";
$active = "red";
?>

<!-- ======= Avantages Section ======= -->

<div class="section-title">
    <div class="row">
        <div class="col-md-6">
            <h2><span><i class="fas fa-fw fa-euro-sign" style="color: #c00000"></i></span> GESTION DES SUBVENTIONS</h2>
        </div>
        <div class="col-md-6">
            <div class="float-right mt-0 mb-3">
                <a type="button" rel="tooltip" title="Ajouter" href="<?= linkto('GestionInterne', 'subvention') ?>"
                    class="btn btn btn-sm btn-red  ml-1">
                    <i class="fas fa-plus" style="color: #ffffff"></i>
                    Ajouter une subvention
                </a>
                <a type="button" rel="tooltip" title="Paramétrer"
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
                <h2 class="text-center font-weight-bold" id="titre"> <?= $titre . " (" . sizeof($subventions) . ")" ?>
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
                        <th>Numèro</th>
                        <th>Titre</th>
                        <th>Nature des Travaux</th>
                        <th>Nature d'aide</th>
                        <th>Montant</th>
                        <th>Taux</th>
                        <th>etat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($subventions as $sub) {
                        $i++;
                    ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td>S<?= str_pad(($sub->idSubvention), 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= $sub->titreSubvention ?></td>
                            <td><?= $sub->natureTravaux ?></td>
                            <td><?= $sub->natureAide ?></td>
                            <td><?= $sub->montantSubvention ?></td>
                            <td><?= $sub->taux ?></td>
                            <td><?= $sub->etatSubvention == "0" ? "Inactif" : "Actif" ?></td>

                            <td style="text-align : center">
                                <a type="button" rel="tooltip" title="Modifier"
                                    href="<?= linkto('GestionInterne', 'subvention', $sub->idSubvention) ?>"
                                    class="btn btn btn-sm btn-warning  ml-1">
                                    <i class="fas fa-edit" style="color: #ffffff"></i>
                                </a>
                            </td>
                        </tr>
                    <?php    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">

</script>