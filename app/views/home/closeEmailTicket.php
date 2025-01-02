<link rel="stylesheet" type="text/css" href="<?= URLROOT ?>/assets/ticket/vendor/libs/multiselect/css/style.css">
<link rel="stylesheet" type="text/css" href="<?= URLROOT ?>/assets/ticket/vendor/libs/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?= URLROOT ?>/assets/ticket/css/ticket.css" />
<link rel="stylesheet" type="text/css" href="<?= URLROOT ?>/assets/ticket/css/tenue-reunion.css"/>


<script src="<?= URLROOT ?>/assets/ticket/vendor/js/bootstrap.js"></script>
<script src="<?= URLROOT ?>/assets/ticket/vendor/libs/jquery/jquery.js"></script>
<script src="<?= URLROOT ?>/assets/ticket/js/loader.js"></script>
<script src="<?= URLROOT ?>/assets/ticket/vendor/libs/tinymce/tiny.js" referrerpolicy="origin"></script>

<?php
    function randomCode() {
        $alphabet = '0123456789';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1; 
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }
?>

<style>
    table, td, div, h1, p {
    font-family: Arial, sans-serif;
    }
    @media screen and (max-width: 1030px) { 
    .unsub {
        display: block;
        padding: 8px;
        margin-top: 14px;
        border-radius: 6px;
        background-color: #555555;
        text-decoration: none !important;
        font-weight: bold;
    }
    .col-lge {
        max-width: 100% !important;
    }
    }
    @media screen and (min-width: 531px) {
    .col-sml {
        max-width: 27% !important;
    }
    .col-lge {
        max-width: 73% !important;
    }
    }
</style>


<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-12 mb-12">
            <div class="card" style="padding: 5px; background: white; border: 1px solid #8f8f8f;">
                <div class="card-body" style="height: 90vh;">
                    
                    <div style="background: white; ">
                    <div style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em; background: #c00000; padding: 20px; color: white;">
                        Fermeture de ticket N° <?= $data['ticket']->numero  ?>
                    </div>
                    <?php
                        if($data['ticket']->statut != 2){
                            ?>
                            <br>
                            <span style=""> Type de ticket :</span> <span style="font-weight: bold;">E-mail</span><br>
                            <span style=""> Objet :</span> <span id="objetModalClusure" style="font-weight: bold;"><img src="<?= URLROOT ?>/public/images/loader-image.gif" alt class="rounded-circle" style="width: 25px;" /></span>
                            <hr>

                            <form style="display: block; border: none;" id="fromCloseTicketEmail"  method="POST" action="<?= linkTo('Ticket', 'closeTicketEmail',  $data['ticket']->idTicket) ?>" novalidate>
                                <?php
                                    if(isset($NeverGiveUp)){
                                        ?>
                                        <span style="">Code de varification:</span>
                                        <br>
                                        <input type="number" id="verificationNumbre" name="verificationNumbre" placeholder="Code à 6 chiffres" value="" required class="form-control">
                                        <div style="text-align: left; color: red; font-size: 12px; display: none;" id="verificationNumbreMessage">
                                            &nbsp;&nbsp;&nbsp; Ce champs est obligatoire
                                        </div>
                                        <br>
                                        <?php
                                    }
                                ?>

                                <p style="color: green; font-weight: bold;">
                                    Vous êtes sur le point de fermer ce ticket.
                                </p>

                                <span style="">Auteur:</span>
                                <input type="text" name="identiteAuteurCloture" id="identiteAuteurCloture" class="form-control" readonly>
                                <br>
                                
                                <span style="">Commentaire:</span>
                                <textarea
                                    required
                                    placeholder="Ecrivez ici..."
                                    class="form-control"
                                    id="commentaireCloture"
                                    name="commentaire"
                                    rows="5"
                                ></textarea>

                               
                                <br><br>
                                <button type="submit" name="closeTicketEmail" class="btn btn-success form-control" style="border-radius: 0px;">Valider</button>
                            </form>
                            <?php
                        }
                        else{
                            ?>
                            <div style="text-align: center;">
                                <img src="<?= URLROOT ?>/public/images/ok.PNG" alt class="rounded-circle" style="width: 100px;" />
                                <br><br>
                                <p style="color: red; font-weight: bold;">
                                    Ce ticket est déjà fermé, merci !
                                </p>
                                <?php
                                    if(! isset($_SESSION['isConnected'])){
                                        ?>
                                        <a href="<?= URLROOT.'/Home/login' ?>" class="btn btn-primary">Me connecter </a>
                                        <?php
                                    }
                                ?>
                            </div>
                            <?php
                        }
                    ?>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="loaderModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <img src="<?= URLROOT ?>/public/images/loader-image.gif" alt class="rounded-circle" style="width: 100px;" />
                    <br><br>
                    <p style="color: red; font-weight: bold;">
                        Exécution en cours...
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="loaderModal2" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <img src="<?= URLROOT ?>/public/images/loader-image.gif" alt class="rounded-circle" style="width: 100px;" />
                    <br><br>
                    <p style="color: red; font-weight: bold; text-align: center;">
                        Veillez patienter...
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="sucessMessageModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <img src="<?= URLROOT ?>/public/images/ok.PNG" alt class="rounded-circle" style="width: 100px;" />
                    <br><br>
                    <p style="color: red; font-weight: bold;" id="sucessMessageP">
                        
                    </p>
                </div>
            </div>
            <hr>
            <div class="modal-footer">
                <a href="javascript:void(0)" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Ok</a>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="errorMessageModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message</h5>
            </div>
            <div class="modal-body">
                <div style="text-align: center;">
                    <img src="<?= URLROOT ?>/public/images/non.PNG" alt class="rounded-circle" style="width: 100px;" />
                    <br><br>
                    <p style="color: red; font-weight: bold;" id="errorMessageP">
                        
                    </p>
                </div>
            </div>
            <hr>
            <div class="modal-footer">
                <a href="javascript:void(0)" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Ok</a>
            </div>
        </form>
    </div>
</div>


<script>

    function sendVerificationCodeColseTicketEmail(myMailAdress,objetEmail){
        $.ajax({
            url: '<?= URLROOT.'/public/json/ticket/Cron_Email.php?action=sendVerificationCodeColseTicketEmail' ?>',
            method: 'POST',
            data: {
                ticket_id: '<?= $data['ticket']->idTicket  ?>',
                myMailAdress: myMailAdress,
                objetEmail: objetEmail
            },
            success: function(response) {
                const obj = JSON.parse(response);
                console.log(obj);
            },
            complete:function(){
                $('#loaderModal2').modal('hide');
                document.getElementById('sucessMessageP').innerHTML = "Code de varification envoyé avec succès ! <br/> Merci de consulter votre boite e-mail : "+myMailAdress;
                $('#sucessMessageModal').modal('show');
            }
        });
    }
    
    function getConversation(){
        $.ajax({
            url: '<?= URLROOT.'/public/json/ticket/Cron_Email.php' ?>',
            method: 'GET',
            data: {
                action: 'getOriginalMail',
                ticket_id: '<?= $data['ticket']->idTicket  ?>',
            },
            success: function(response) {
                const obj = JSON.parse(response);
                document.getElementById('objetModalClusure').innerHTML = obj['objet'];
                var nomID=obj['from']['mail'] == '<?= IMAP_USERNAME ?>' ? obj['to']['personal']:obj['from']['personal'];
                nomID = nomID != false ? nomID:'';
                var mailID=obj['from']['mail'] == '<?= IMAP_USERNAME ?>' ? obj['to']['mail']:obj['from']['mail'];
     
                document.getElementById('identiteAuteurCloture').value = nomID+' ('+mailID+')';
                myMailAdress = obj['from']['mail'] == '<?= IMAP_USERNAME ?>' ? obj['to']['mail']:obj['from']['mail'];
                objetEmail = obj['objet'];
            },
            complete:function(){
                $('#loaderModal2').modal('hide');
            }
        });
    }


    $(document).ready(function(){
        <?php
            if($data['ticket']->statut != 2){
                ?>
                $('#loaderModal2').modal('show');
                getConversation();
                <?php
            }
        ?>
        
        <?php
            if(isset($_SESSION['message'])){
                ?>
                document.getElementById('sucessMessageP').innerHTML = "<?= $_SESSION['message'] ?>";
                $('#sucessMessageModal').modal('show');
                <?php
            }
            
            if(isset($_SESSION['messageError'])){
                ?>
                document.getElementById('errorMessageP').innerHTML = "<?= $_SESSION['messageError'] ?>";
                $('#errorMessageModal').modal('show');
                <?php
            }

            unset($_SESSION['message']);
            unset($_SESSION['messageError']);
        ?>
    });
    

</script>