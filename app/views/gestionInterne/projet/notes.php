<div class="card">
    <div class="card-header bg-danger font-weight-bold text-white">
        <div class="row">
            <div class="col-md-10">
                6. NOTES
            </div>
            <div class="col-md-2">
                <button type="button" rel="tooltip" title="Rediger une note" onclick="ShowModalNote(0,'add')"
                    class="btn btn-sm btn-dark btn-simple btn-link ">
                    <i class="fa fa-plus" style="color: #ffffff"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body bg-white">
        <?php

        // if ($notes == null) {
        ?>
        <div class="font-weight-bold text-center text-danger">
            <span class="">Aucune Note !!</span>
        </div>
        <?php
        // } else {
        ?>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable12" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th></th>
                        <th>Note</th>
                        <th>Date</th>
                        <th>Auteur</th>
                    </tr>
                </thead>
                <!-- <tbody>
                        <?php
                        $i = 1;

                        foreach ($notes as $note) {
                            $plaintext = strlen($note->plainText) > 50 ? substr($note->plainText, 0, 50) . " ..." : $note->plainText;
                        ?>
                            <tr>
                                <td onclick="ShowModalNote('<?= $note->idNote ?>', 'edit')"
                                    <?= $note->publie == 0 ? "style='background-color:white'" : "style='background-color:green; color:white'" ?>>
                                    <?= $i++ ?></td>
                                <td class="text-center">
                                    <a type="button" rel="tooltip" title="Voir la note"
                                        onclick="ShowModalNote(<?= $note->idNote ?>, 'edit')"
                                        class="btn btn-sm btn-info btn-simple btn-link">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>

                                </td>
                                <td class="text-center">
                                    <a type="button" rel="tooltip"
                                        title="<?= $note->publie == 0 ? "Publier la note" : "Dépublier la note"  ?>"
                                        onclick="onClickPublierNote(<?= $note->publie == 0 ? 1 : 0  ?>, '<?= $note->idNote ?>')"
                                        class="btn btn-sm btn-info btn-simple btn-link">
                                        <i class="<?= $note->publie == 0 ? 'fa fa-unlock' : 'fa fa-lock' ?>"></i>
                                    </a>
                                </td>
                                <td onclick="ShowModalNote('<?= $note->idNote ?>', 'edit')">
                                    <?= $plaintext ?></td>
                                <td onclick="ShowModalNote('<?= $note->idNote ?>', 'edit')">
                                    <?= $note->dateNote ?></td>
                                <td onclick="ShowModalNote('<?= $note->idNote ?>', 'edit')">
                                    <?= $note->auteur ?></td>
                            </tr>
                        <?php    }
                        ?>
                    </tbody> -->
            </table>
        </div>
        <?php
        // }
        ?>
    </div>
</div>