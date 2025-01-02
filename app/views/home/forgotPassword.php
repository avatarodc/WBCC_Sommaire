<div class="container register">
    <div class="row" <?= $message == "3" ? "hidden" : "" ?>>
        <div class="col-md-2 register-left">
            <img src="<?= URLROOT . '/images/logo_WBCC.png' ?>" alt="">
            <h1>Extranet WBCC</h1>
        </div>
        <div class="col-md-10 register-right">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <form method="POST" action="<?= linkTo('Home', 'forgotPassword', 'login') ?>">
                            <div class="register-form">
                                <fieldset>
                                    <h6 class="text-center text-danger alert alert-danger"
                                        <?= $message == "" || $message == "3" ? "hidden" : "" ?>><?= $message ?></h6>
                                    <h6 class="text-center"><strong>RECUPERATION DE MOT DE PASSE</strong></h6>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Email <small class="text-danger">*</small></label>
                                            <input type="text" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="row float-right">
                                        <div class="col-md-12 form-group">
                                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold ">
                                                Envoyer</button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Code de validation -->
    <div class="row" <?= $message == "3" ? "" : "hidden" ?>>
        <div class="col-md-2 register-left">
            <img src="<?= URLROOT . '/images/logo_WBCC.png' ?>" alt="">
            <h1>Extranet WBCC</h1>
        </div>
        <div class="col-md-10 register-right">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class="register-form">
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Un mail vous a été envoyé à l'adresse suivante :
                                        (<?= $email ?>) !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                class="fit-image"> </div>
                                    </div>
                                    <div class="row justify-content-center mt-2">
                                        <div class="col-12 text-center">
                                            <h6>Veuillez vérifier votre boîte email pour réinitialiser votre mot de
                                                passe !</h6>
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
</div>