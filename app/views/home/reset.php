<div class="container register">
    <div class="row">
        <div class="col-md-2 register-left">
            <img src="<?= URLROOT . '/images/logo_WBCC.png' ?>" alt="">
            <h1>Extranet WBCC</h1>
        </div>
        <div class="col-md-10 register-right">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <form method="post" action="<?= linkTo('Home', 'resetPassword') ?>"
                            enctype="multipart/form-data">
                            <fieldset>
                                <input type="hidden" name="email" value="<?= $email ?>">
                                <h6 class="text-center"><strong>Réinitialiser mot de passe</strong></h6>
                                <hr>
                                <div class="row alert alert-danger" <?= (isset($msg) && $msg != "") ? "" : "hidden" ?>>
                                    <?php
                                    if (isset($msg)) {
                                        if ($msg == "1") {
                                            echo "Les deux mots de passe ne sont pas identiques !";
                                        } else {
                                            echo "Mot de passe invalide !";
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mt-1">
                                            <div class="col-md-5">
                                                <label for="">Nouveau mot de passe <small
                                                        class="text-danger">*</small></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input required type="password" id="nouveauMdp" name="nouveauMdp"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-5">
                                                <label for="">Confirmation mot de passe <small
                                                        class="text-danger">*</small></label>
                                            </div>
                                            <div class="col-md-7">
                                                <input required type="password" id="cMdp" name="cMdp"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="row float-right">
                                <div class="col-md-12 form-group">
                                    <button type="submit" class="btn btn-danger btn-sm font-weight-bold ">
                                        Valider</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>