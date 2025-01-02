<div style="background: #222636 !important;">

</div>
<div class="container h-100 mt-5">
    <div class="d-flex justify-content-center h-100">
        <div class="user_card">
            <div class="d-flex justify-content-center">
                <div class="brand_logo_container">
                    <img src="<?= URLROOT; ?>/images/logo.png" class="brand_logo" alt="Logo">
                </div>
            </div>
            <div class="d-flex justify-content-center form_container">
                <form method="post" action="<?= linkTo('Home', 'changePassword') ?>">
                    <div class="input-group mb-2 mt-0 text-center text-white">
						<label>Votre mot de passe doit être changé à la première connexion</label>
					</div>
                    <?php    if(isset($message) && $message != ""){?>
                        <div class="card mt-0 bg-danger p-0 col-md-12 text-center text-white">
                            <small> <?= $message ?> </small>
                        </div>
                    <?php } ?>
                    <small class="ml-4">Nouveau mot de passe</small>
                    <div class="input-group mb-2">
                        <div class="input-group-append mt-2">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" name="password2" class="form-control input_user" value="" placeholder="Nouveau mot de passe">
                    </div>
                    <small class="ml-4">Confirmation de mot de passe</small>
                    <div class="input-group mb-2">
                        <div class="input-group-append mt-2">
                            <span class="input-group-text"><i class="fa fa-key"></i></span>
                        </div>
                        <input type="password" name="password3" class="form-control input_pass" value="" placeholder="Confirmation de mot de passe">
                    </div>
                    <div class="d-flex justify-content-center mt-3 login_container">
                         <button type="submit" name="savePasse" class="btn login_btn">Valider</button>
                    </div>
                </form>
            </div>
    
            
        </div>
    </div>
</div>