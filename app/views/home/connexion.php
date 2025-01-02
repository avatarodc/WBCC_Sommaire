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
                        <form method="post" action="<?= linkTo('Home', 'connexion', 'login') ?>">
                            <div class="register-form">
                                <fieldset>
                                    <?php if ($message != "") { ?>
                                    <div class="card mt-0 bg-danger p-0 col-md-12 text-center text-white">
                                        <h3> <?= $message ?> </h3>
                                    </div>
                                    <?php } ?>
                                    <h6 class="text-center"><strong>CONNEXION</strong></h6>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Login <small class="text-danger">*</small></label>
                                            <input type="text" class="form-control" name="username">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label>Password <small class="text-danger">*</small></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 form-group text-center">
                                            <button type="submit" class="btn btn-danger btn-sm font-weight-bold ">Se
                                                connecter</button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-12 form-group d-flex justify-content-center">
                                            <a href="<?= linkTo('Home', 'forgotPassword') ?>"
                                                style="color:blue;font-weight:bold;text-decoration:underline"
                                                class="h6">Mot de passe oublié ?</a>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="row float-right">
                                    <div class="col-md-12 form-group">
                                        <a href="<?= linkTo('Home', 'account', '') ?>"
                                            class=" btn btn-primary">S'inscrire</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <a href="https://wbcc.fr/mentions-legales"
                                            style="color:blue;font-weight:bold;text-decoration:underline"
                                            class="h6">Mentions légales</a>
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