<?php


class UtilisateurCtrl extends Controller
{
    public function __construct()
    {
        //Role::accessiblePar("administrateur", "administrateur");
        $this->roleModel = $this->model('Roles');
        $this->userModel = $this->model('Utilisateur');
        $this->personnelModel = $this->model('Personnel');
        $this->companyModel = $this->model('Company');
        $this->contactModel = $this->model('Contact');
        $this->historiqueModel = $this->model('Historique');
        $this->siteModel = $this->model('Site');
    }


    public function index()
    {
        $this->redirectToMethod("Utilisateur", "profil");
    }
    public function agenda()
    {
        $user = Role::connectedUser();
        $contact = $this->personnelModel->findById($user->idUtilisateur);
        $personnels = $this->userModel->getUsersByType("wbcc");
        $data = [
            "nomLien" => "agenda",
            "user" => $contact,
            "personnels" => $personnels,
        ];
        $this->view('utilisateur/' . __FUNCTION__, $data);
    }

    public function configuration($idUser)
    {
        $user = $this->userModel->findUserById($idUser);
        if ($user) {
            $tabJoursCoche = ($user->jourTravail != null && $user->jourTravail != "") ? explode(';', $user->jourTravail) : [];
            $tabHeureCoche = ($user->horaireTravail != null && $user->horaireTravail != "") ? explode(';', $user->horaireTravail) : [];
            $data = [
                "tabHoraires" => [["jour" => "Lundi", "heureDebut" => "09:00", "heureFin" => "18:00"], ["jour" => "Mardi", "heureDebut" => "09:00", "heureFin" => "18:00"], ["jour" => "Mercredi", "heureDebut" => "09:00", "heureFin" => "18:00"], ["jour" => "Jeudi", "heureDebut" => "09:00", "heureFin" => "18:00"], ["jour" => "Vendredi", "heureDebut" => "09:00", "heureFin" => "18:00"], ["jour" => "Samedi", "heureDebut" => "10:00", "heureFin" => "15:00"]],
                "tabJoursCoche" => $tabJoursCoche,
                "tabHeureCoche" => $tabHeureCoche,
                "commercial" => $user,
            ];
            $this->view('utilisateur/' . __FUNCTION__, $data);
        } else {
            $this->redirectToMethod('Utilisateur', 'users');
        }
    }

    public function saveConfig()
    {
        extract($_POST);
        if ($typeIntervention == "zone") {
            $departement = "";
        } else {
            if ($typeIntervention == "departement") {
                $adresse = "";
                $codePostal = "";
                $ville = "";
            } else {
                $departement = "";
                $adresse = "";
                $codePostal = "";
                $ville = "";
            }
        }
        $tabJ = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
        $jours = isset($tabJourCoche) && sizeof($tabJourCoche) != 0 ? implode(";", $tabJourCoche) : "";
        $tabHoraire = [];

        $i = 0;
        if (isset($tabJourCoche)) {
            foreach ($tabJourCoche as $jourC) {
                $index = array_search($jourC, $tabJ);
                if ($index !== false) {
                    $tabHoraire[] = $tabHD[$index] . "-" . $tabHF[$index];
                }
            }
        }

        $horaire = sizeof($tabHoraire) != 0 ? implode(";", $tabHoraire) : "";
        if ($this->userModel->saveConfig($jours, $horaire, $marge, $codePostal, $ville, $commentaire, $adresse, $idUser, $departement, $moyenTransport)) {
            $this->redirectToMethod('Utilisateur', 'configuration', $idUser);
        }
    }

    public function profil($id = 0)
    {
        $message = "";
        if ($id == 1) {
            $message = "Les deux mots de passe ne correspondent pas !";
        } else {
            if ($id == 2) {
                $message = "Ancien mot de passe incorrect !";
            } else {
                if ($id == 3) {
                    $message = "Modification de mot de passe effectué avec succés !";
                }
            }
        }
        $user = Role::connectedUser();
        $contact = $this->personnelModel->findById($user->idUtilisateur);
        $data = [
            "nomLien" => "profil",
            "user" => $contact,
            "message" => $message
        ];
        $this->view('utilisateur/' . __FUNCTION__, $data);
    }

    public function update()
    {
        $form = getForm($_POST);
        extract($form);
        $idContact = Role::connectedUser()->idContact;
        $email = Role::connectedUser()->login;
        $role = Role::connectedUser()->libelleRole;
        $do = $this->companyModel->findByContact($idContact);
        $nomCom = ($do) ?  $do->name : "";
        $this->personnelModel->updateContact(
            $idContact,
            $civilite,
            $nom,
            $prenom,
            $tel1,
            $tel2,
            $faxPhone,
            $email,
            $emailCollaboratif,
            $adresse1,
            $adresse2,
            $codePostal,
            $ville,
            $departement,
            $region,
            $porte,
            $batiment,
            $etage,
            $titre,
            $service,
            $role,
            $nomCom
        );

        $this->redirectToMethod("Utilisateur", "profil");
    }

    public function changePasse()
    {
        $user = Role::connectedUser();
        $form = getForm();
        extract($form);
        $message = "";
        if (sha1($oldPassword) == $user->mdp) {
            if ($password1 == $password2) {
                if ($this->userModel->changePass($user->idUtilisateur, $password1) == 1) {
                    $this->redirectToMethod("Home", "logout", 3);
                }
            } else {
                $this->redirectToMethod('Utilisateur', 'profil', 1);
            }
        } else {
            $this->redirectToMethod('Utilisateur', 'profil', 2);
        }
    }

    public function changeUserState($idUser, $oldState)
    {
        $this->userModel->changeUserState($idUser, $oldState);
        $this->redirectToMethod('Utilisateur', 'liste');
    }

    public function bloquer($page = "")
    {
        $form = getForm($_POST);
        extract($form);
        if ($page == "copro") {
            $page = ($type == "") ? "Copro" : $type;
        }
        if (isset($idUtilisateur) && isset($etatUser)) {
            $idContact = $idUtilisateur;
            $isUser = $etatUser;
            $do = $this->companyModel->findByContact($idContact);
            $c = $this->personnelModel->findContactById($idContact);
            $signature = "";
            if ($page == "do" || $page == "particulier" || $page == "Copro" || $page == "Occupant") {
                $signature = "WBCC ASSISTANCE";
            } else {
                $signature = $do->name;
            }
            if ($isUser == "0") {
                //createUser
                // var_dump($c->statutContact);

                if ($page == "particulier") {
                    $idRole = 16;
                } else {
                    if ($page == "Copro") {
                        $idRole = 25;
                    } else {
                        if ($page == "Occupant") {
                            $idRole = 24;
                        } else {
                            if (strstr($c->statutContact, "DIRIGEANT")) {
                                $idRole = 13;
                            } else {
                                if (strstr($c->statutContact, "RESPONSABLE")) {
                                    $idRole = 14;
                                } else {
                                    $idRole = 15;
                                }
                            }
                        }
                    }
                }

                // var_dump($idRole);
                //die;
                $token = md5(uniqid(rand(), true));
                $param = $c->emailContact . "~" . $token;
                $pass = "";
                $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $i = 0;
                while ($i < 8) {
                    $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
                    if (!strstr($pass, $char)) {
                        $pass .= $char;
                        $i++;
                    }
                }

                $this->userModel->addUser("$c->emailContact", "$pass", "$c->emailContact", $idRole, $idContact, "$token");
                //MAIL inform create
                $subject = "Mail de confirmation de création de compte";
                $body = "Bonjour $c->civiliteContact $c->fullName,
                <br><br>Votre compte d'utilisateur vient d'être créé sur <b>WBCC ASSISTANCE EXTRANET : www.extranet.wbcc.fr </b><br><br>
              
                Pour terminer votre inscription, veuillez confirmer votre adresse e-mail en suivant ce lien : <br>
                <b><a href='" . URLROOT . "/Home/confirm/$param'>TERMINER VOTRE INSCRIPTION</a></b> <br><br>
                Merci de confirmer votre adresse e-mail, sans quoi votre inscription sera incomplète. <br><br>

                Information de connexion :<br>
                <u><b>Login</u> :</b> $c->emailContact<br>
                <u><b>Mot de passe</u> :</b> $pass

                <br><br><br>
                Veuillez ne pas répondre à ce message envoyé automatiquement pour votre information.  <br><br>
                Email envoyé depuis l'extranet de WBCC par <br>
                <b> " . Role::nomComplet() . "</b><br>
                <b>" . $signature . "</b>" .
                    "<br><br>Pour toutes question ou demande techniques, merci de contacter :
               <br><b>Tel : 09 80 08 44 84 
               <br>Email : supportdev@wbcc.fr</b>";
            } else {

                //deleteUser
                $this->userModel->deleteUser($idContact);
                //EMAIL
                $subject = "DESACTIVATION COMPTE D'UTILISATEUR";
                $body = "Bonjour $c->civiliteContact $c->fullName ,
                <br><br>Votre compte d'utilisateur est désactivé sur <b>WBCC ASSISTANCE EXTRANET
                </b>
                <br><br><br>Email envoyé depuis l'extranet de WBCC par <br>
                <b> " . Role::nomComplet() . "</b><br>
                <b>" . $signature . "</b>" .
                    "<br><br>Pour toutes question ou demande techniques, merci de contacter :
           <br><b>Tel : 09 80 08 44 84 
           <br>Email : supportdev@wbcc.fr</b>";
            }
            Role::mailOnServer($c->emailContact, $subject, $body);
            $this->personnelModel->changeUserState($idContact, $isUser);
        }
        if ($page == "do") {
            $this->redirectToMethod("Societe", "societe", $idCompany3);
        } else {
            if ($page == "particulier") {
                $this->redirectToMethod("DonneurOrdre", "index", "Particulier");
            } else {
                if ($page == "Copro") {
                    $this->redirectToMethod("Copro", "index", "Copro");
                } else {
                    if ($page == "Occupant") {
                        $this->redirectToMethod("Copro", "index", "Occupant");
                    } else {
                        $this->redirectToMethod("Personnel", "index");
                    }
                }
            }
        }
    }

    public function addCompte()
    {
        $form = getForm($_POST);
        extract($form);
        if (isset($email)) {
            $user = $this->userModel->findUserByEmail($email);
            if ($user) {
                $this->redirectToMethod("Home", "account", "eem");
            } else {
                $numero = "CON_" . date('ddmmYYYhis');
                if ($mdp != $cmdp) {
                    $this->redirectToMethod("Home", "account", "emp");
                } else {
                    if ($age < 18) {
                        $this->redirectToMethod("Home", "account", "edn");
                    } else {
                        $idStatut = "";
                        if ($typeCompte == "client") {
                            if ($categorie == "Particulier") {
                                $idStatut = "DONNEUR DORDRE;PARTICULIER";
                                $idRoleF = 16;
                            } else {
                                $idStatut = "SALARIE";
                                $idRoleF = 15;
                            }
                        } else {
                            $idStatut = "Candidat";
                        }
                        $contact = $this->contactModel->save($numero, $sexe, $prenom, $nom, $tel, $email,  $dateNaissance, $pseudoSkype, $telWhatsapp, $idStatut);
                        if ($contact) {
                            $token =  md5(uniqid(rand(), true));

                            $res = $this->userModel->addUser($email, $mdp, $email, $idRoleF, $contact->idContact, $token);
                            if ($res) {
                                if ($idRoleF != 17) {
                                    //Envoi mail
                                    $to = $email;
                                    $subject = "Mail de confirmation de création de compte";
                                    $param = $email . "~" . $token;
                                    $txt = "<p style='text-align:justify'>Bienvenue $sexe $prenom $nom à www.extranet.wbcc.fr, <br> <br> Félicitations, nous vous confirmons la création de votre compte. <br> <br>
                                            Pour terminer votre inscription, veuillez confirmer votre adresse e-mail en suivant ce lien : <br>
                                                <b><a href='" . URLROOT . "/Home/confirm/$param'>TERMINER VOTRE INSCRIPTION</a></b> <br><br>
                                                Merci de confirmer votre adresse e-mail, sans quoi votre inscription sera incomplète. <br><br>
                                                Veuillez ne pas répondre à ce message envoyé automatiquement pour votre information.  <br><br>
                                                <b>WBCC ASSISTANCE</b><br>" .
                                        "<br><br>Pour toutes question ou demande techniques, merci de contacter :
                                           <br><b>Tel : 09 80 08 44 84 
                                           <br>Email : supportdev@wbcc.fr</b>
                                                </p>";

                                    Role::mailOnServer($to, $subject, $txt);
                                    $msg = "Votre compte a été créé ! Inscription non terminée";

                                    $user = $this->userModel->findUserByEmail($email);
                                    if ($user) {
                                        if ($user->valideCompte != 0) {
                                            $this->redirectToMethod("Home", "index");
                                        }
                                    } else {
                                        $this->redirectToMethod("Home", "index");
                                    }
                                    $this->view("home/compteCree", ["email" => $email, "msg" => $msg]);
                                } else {
                                    $to = $email;
                                    $subject = "Mail de confirmation de création de compte";
                                    $param = $email . "~" . $token;
                                    $txt = "<p style='text-align:justify'>Bienvenue $sexe $prenom $nom à www.extranet.wbcc.fr, <br> <br> Félicitations, nous vous confirmons la création de votre compte. <br> <br>
                                                Merci d'avoir soumis une requête auprès de nos services. Nous avons bien pris en compte votre demande et sommes en train de travailler dessus pour vous répondre dans les meilleurs délais.         
                                                <br><br>
                                                Veuillez ne pas répondre à ce message envoyé automatiquement pour votre information.  <br><br>
                                                <b>WBCC ASSISTANCE</b><br>
                                               <br><br>Pour toutes question ou demande techniques, merci de contacter :
                                           <br><b>Tel : 09 80 08 44 84 
                                           <br>Email : supportdev@wbcc.fr</b>
                                                </p>
                                                ";

                                    Role::mailOnServer($to, $subject, $txt);
                                    $msg = "Votre compte a été créé et est en cours de validation !";
                                    $this->view("home/compteCree", ["email" => $email, "msg" => $msg]);
                                }
                            } else {
                                $this->redirectToMethod("Candidat", "compte", "ecu");
                            }
                        } else {
                            $this->redirectToMethod("Candidat", "compte", "ecc");
                        }
                    }
                }
            }
        } else {
            $this->redirectToMethod("Home", "account");
        }
    }

    public function users($type = "")
    {
        if (!isset($type) || $type == "") {
            $type = "wbcc";
        }
        $idContact = Role::connectedUser()->idUtilisateur;
        $personnels = $this->userModel->getUsersByType($type);
        $roles = $this->roleModel->getRolesByType($type);
        $sites = $this->siteModel->getAllSites();
        $data = [
            "sites" => $sites,
            "personnels" => $personnels,
            "roles" => $roles,
            "idContact" => $idContact,
            "titre" => "Liste des utilisateurs '" . strtoupper($type) . "'",
            "type" => $type
        ];
        $this->view('utilisateur/' . __FUNCTION__, $data);
    }

    public function delete()
    {
        $form = getForm($_POST);
        extract($form);
        $this->userModel->deleteUser($idContact2);
        $this->personnelModel->deleteContact($idContact2);
        $this->redirectToMethod("Utilisateur", "users");
    }


    public function saveUser()
    {
        $form = getForm($_POST);
        extract($form);
        $roleObj = $this->roleModel->findByID($role);
        $libelleRole = str_contains($roleObj->libelleRole, "Gestionnaire") ? "Gestionnaire WBCC" : (str_contains($roleObj->libelleRole, "Expert") ? "Expert WBCC" : (str_contains($roleObj->libelleRole, "Commercial") ? "Commercial WBCC" : ($roleObj->libelleRole)));
        if ($idContact != "0") {
            $this->personnelModel->updateContactUser(
                $idContact,
                $civilite,
                $nom,
                $prenom,
                $tel1,
                $email,
                $libelleRole
            );
            $this->userModel->updateUser($idContact, $email, $role, $idSite);
        } else {
            $numero = "CON_" . date('ddmmYYYhis');
            $contact = $this->contactModel->save($numero, $civilite, $prenom, $nom, $tel1, $email,  "", "", "", $libelleRole);
            if ($contact) {
                $token = md5(uniqid(rand(), true));
                $param = $email . "~" . $token;
                $pass = "";
                $possible = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $i = 0;
                while ($i < 8) {
                    $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
                    if (!strstr($pass, $char)) {
                        $pass .= $char;
                        $i++;
                    }
                }
                $res = $this->userModel->addUser($email, $pass, $email, $role, $contact->idContact, $token, $idSite, 1);
                if ($res) {
                    $subject = "Mail de confirmation de création de compte";
                    $body = "Bonjour $civilite $prenom $nom,
                    <br><br>Votre compte d'utilisateur vient d'être créé sur <b>WBCC ASSISTANCE EXTRANET</b><br><br>
                  
                    Pour terminer votre inscription, veuillez confirmer votre adresse e-mail en suivant ce lien : <br>
                    <b><a href='" . URLROOT . "/Home/confirm/$param'>TERMINER VOTRE INSCRIPTION</a></b> <br><br>
                    Merci de confirmer votre adresse e-mail, sans quoi votre compte est inactif. <br><br>
    
                    Information de connexion :<br> 
                    <u><b>Login</u> :</b> $email <br>
                    <u><b>Mot de passe</u> :</b> $pass <br><br>
                    
                    <b>
                    Ces identifiants peuvent être utilisés pour se connecter sur :<br>
                   <a href='https://www.extranet.wbcc.fr'>Extranet</a><br>
                   <a href='https://apps.apple.com/us/app/wbcc-assistance/id1631513137'>App Store(ios)</a><br>
                   <a href='https://play.google.com/store/apps/details?id=com.wbccassistmobile'>Play Store(android)</a><br>
                    </b>
    
                    <br><br><br>
                    Email envoyé depuis l'extranet de WBCC par <br>
                    <b> " . Role::nomComplet() . "</b><br>
                    <b>EXTRANET WBCC ASSISTANCE</b>"
                        .
                        "<br><br>Pour toutes question ou demande techniques, merci de contacter :
               <br><b>Tel : 09 80 08 44 84 
               <br>Email : supportdev@wbcc.fr</b>";
                    Role::mailOnServer($email, $subject, $body);
                }
            }
        }
        $this->redirectToMethod("Utilisateur", "users");
    }

    public function bloquerUser()
    {
        $form = getForm($_POST);
        extract($form);
        if (isset($idUtilisateur) && isset($etatUser)) {
            $idContact = $idUtilisateur;
            $isUser = $etatUser;
            $this->userModel->changeUserState($idContact, $isUser);
        }
        $this->redirectToMethod("Utilisateur", "users");
    }

    public function historique()
    {
        $idContact = Role::connectedUser()->idUtilisateur;
        $historiques = $this->historiqueModel->getHistoriqueByUser($idContact);
        $users =  [];
        if (Role::connectedUser()->libelleRole == "Administrateur" || Role::connectedUser()->libelleRole == "Manager" || Role::connectedUser()->isAdmin == "1") {
            $users = $this->userModel->getUsersByType('wbcc');
        } else {
            $users[] =  $this->userModel->findUserById($idContact);
        }
        $data = [
            "historiques" => $historiques,
            "titre" => "Historique : " . Role::connectedUser()->fullName,
            "users" => $users
        ];
        $this->view('utilisateur/' . __FUNCTION__, $data);
    }
}
