<div class="container register">
    <div class="row">
        <div class="col-md-2 register-left">
            <img src="<?= URLROOT . '/images/logo_WBCC.png' ?>" alt="">
            <h1>Extranet WBCC</h1>
        </div>
        <div class="col-md-10 register-right">
            <!-- MultiStep Form -->
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <form method="post" action="<?= linkTo('Home', 'validation') ?>">
                            <div class="register-form">
                                <fieldset>
                                    <?php if ($result == "erreur") { ?>
                                    <div class="card mt-0 bg-danger p-0 col-md-12 text-center text-white">
                                        <h5> Code invalide ! </h5>
                                    </div>
                                    <?php } ?>
                                    <h6 class="text-center"><strong>VALIDATION</strong></h6>
                                    <hr>
                                    <div class="input-group mb-3 mt-4 text-center">
                                        <small>Le code de vérification est envoyé à
                                            <code><?= $_SESSION['connectedUser']->email ?></code> (vérifiez votre spam
                                            en cas de non réception)</small>
                                    </div>

                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Code de validation <small class="text-danger">*</small></label>
                                            <input required maxlength="6" type="text" class="form-control" name="code">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group text-center">
                                            <button type="submit"
                                                class="btn btn-danger btn-sm font-weight-bold ">Valider</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="row text-center">
                                    <div class="col-md-12 ">
                                        <a class="text-info" target="_blank"
                                            href="https://wbcc.fr/mentions-legales">Mentions légales</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>