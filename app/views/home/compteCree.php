<?php
$hidden = ($msg == "Votre compte a été créé et est en cours de validation !") ? "hidden" : "";

?>
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
                                    <div class="form-card">
                                        <h2 class="fs-title text-center"><?= $msg ?></h2> <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3"> <img
                                                    src="https://img.icons8.com/color/96/000000/ok--v2.png"
                                                    class="fit-image"> </div>
                                        </div>
                                        <div <?= $hidden ?> class="row justify-content-center mt-2">
                                            <div class="col-12 text-center">
                                                <h6>Veuillez terminer votre inscription en confirmant la création du
                                                    compte à partir de votre boîte mail (<span
                                                        class="text-primary"><b><?= $email ?></b></span>) ! (Vérifiez
                                                    votre spam en cas de non-réception)</h6>
                                                <h5 hidden><a
                                                        href="<?= linkTo("Candidat", "renvoyerMailCreation", $email) ?>"
                                                        style="color:blue; font-weight : bold; text-decoration : underline">Renvoyer
                                                        le mail</a></h5>
                                            </div>
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
</div>