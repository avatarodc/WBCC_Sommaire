<?php

class HomeCtrl extends Controller
{
    public function __construct()
    {
        $this->utilisateurModel = $this->model('Utilisateur');
        $this->parametresModel = $this->model('Parametres');
        $this->roleModel = $this->model('Roles');
        $this->historiqueModel = $this->model('Historique');
        $this->companyModel = $this->model('Company');
        $this->avantageModel = $this->model('Avantage');
        $this->TicketModel = $this->model('Ticket');
        $this->siteModel = $this->model('Site');
        date_default_timezone_set('Europe/Paris');
    }

    public function index()
    {
        // $this->view('adresse/adresse');
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
            $idContact = Role::connectedUser()->idContact;
            $do = $this->companyModel->findByContact($idContact);
            $avantages = [];
            if ($do) {
                $avantages = $this->avantageModel->getAvantageByCategorie($do->categorieDO);
            }
            $data = [

                "historiques" => $this->historiqueModel->getHistoriqueByUser($_SESSION['connectedUser']->idUtilisateur),
                "avantages" => $avantages,
                "do" => $do

            ];
            $this->view('home/' . __FUNCTION__, $data);
        } else {
            $this->redirectToMethod('Home', 'connexion');
        }
    }

    public function connexion($login = '')
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
            $this->redirectToMethod('Home', 'index');
        } else {
            if (empty($login)) {
                $this->view('home/' . __FUNCTION__, ['message' => '']);
            } else {
                $form = getForm($_POST);
                extract($form);
                $user = false;
                if (isset($username) && isset($password)) {
                    $user = $this->utilisateurModel->findUser($username, $password);
                }
                $roles = $this->roleModel->getAllByEtat();

                if ($user) {
                    if ($user->etatUser == 1) {
                        if ($user->token == null || $user->token == "") {
                            $params = $this->parametresModel->getParametres();
                            $_SESSION['nomUser'] = $user;
                            $_SESSION['connectedUser'] = $user;
                            $_SESSION['connectedUser']->nomSite = "";
                            $_SESSION['connectedUser']->idSite = "";
                            $site = false;
                            if ($user->idSiteF != null && $user->idSiteF != "") {
                                $site = $this->siteModel->findById($user->idSiteF);
                            }
                            if ($site) {
                                $_SESSION['connectedUser']->nomSite = $site->nomSite;
                                $_SESSION['connectedUser']->idSite = $site->idSite;
                            }
                            $_SESSION['parametres'] = $params;
                            $_SESSION['roles']    = $roles;
                            //FIND COMPANY AND TYPE
                            $company = $this->companyModel->findByContact($user->idContact);
                            $_SESSION['connectedUser']->typeCompany = "";
                            if ($company) {
                                $_SESSION['connectedUser']->idCompany = $company->idCompany;
                                $_SESSION['connectedUser']->typeCompany = $company->category;
                                $_SESSION['connectedUser']->nomCompany = $company->name;
                            }
                            if ($user->isVerified == 0) {
                                $_SESSION['isConnected'] = false;
                                $rand = rand(100000, 999999);
                                $date = date('Y-m-d H:i:s');
                                $_SESSION['code']    = $rand;
                                $_SESSION['dateGenerate']    = $date;
                                $_SESSION['firstConnection'] = false;
                                $username =  Role::nomComplet();
                                $_SESSION['isVerified']    = false;
                                $to = $_SESSION['nomUser']->email;
                                $subject = "Confirmation de connexion pour l'extranet WBCC";
                                $body = "Afin d'authentifier votre connexion avec votre compte d'utilisateur <b>" . $_SESSION['nomUser']->login . "</b>  sur l'Extranet de <b>WBCC ASSISTANCE</b>, veuillez entrer le code : <b>" . $rand . "</b> pour vous connecter.<br><br><br>Veuillez ne pas répondre à ce message envoyé automatiquement pour votre information.";

                                if (Role::mailOnServer($to, $subject, $body)) {
                                    $this->view('home/validation', ['result' => '']);
                                } else {
                                    $this->view('home/' . __FUNCTION__, ['message' => 'Veuillez réessayer ou contactez votre responsable !']);
                                }
                            } else {
                                //SAVE HISTORIQUE CONNEXION
                                $this->historiqueModel->save("Connexion");
                                $_SESSION['isConnected']    = true;
                                $_SESSION['firstConnection'] = false;
                                $username =  Role::nomComplet();
                                $_SESSION['isVerified']    = true;
                                if (isset($_SESSION['redirect_url'])) {
                                    $redirection = $_SESSION['redirect_url'];
                                    unset($_SESSION['redirect_url']);
                                    header("Location:" . $redirection);
                                } else {
                                    $this->redirectToMethod('Home', 'index');
                                }
                            }
                        } else {
                            $this->view('home/' . __FUNCTION__, ['message' => 'Un mail vous a été envoyé à l\'adresse (' . $user->login . ')pour la confirmation de la création de votre compte, Merci de terminer votre inscription pour pouvoir se connecter!']);
                        }
                    } else {
                        if ($user->etatUser == 2) {
                            $this->view('home/' . __FUNCTION__, ['message' => 'Votre demande de création de compte est en cours de traitement ! Nous vous revenons dans les meilleurs délais']);
                        } else {
                            $this->view('home/' . __FUNCTION__, ['message' => 'Vous êtes bloqué par l\'Administrateur !']);
                        }
                    }
                } else {
                    $this->view('home/' . __FUNCTION__, ['message' => 'Verifier vos informations d\'identification !']);
                }
            }
        }
    }

    public function erreurRole()
    {
        $this->view('home/' . __FUNCTION__, []);
    }

    public function logout()
    {
        if (isset($_SESSION) && isset($_SESSION['connectedUser'])) {
            $this->historiqueModel->save("Déconnexion");
            unset($_SESSION['connectedUser']);
            unset($_SESSION['usersRoles']);
            session_destroy();
        }


        $this->redirectToMethod('Home', 'connexion');
    }

    public function updateUserRoute()
    {
        $tab = $_SESSION['usersRoles'];
        foreach ($tab as $k => $v) {
            $liens = implode(",", $v);
            $this->parametresModel->updateUsersLiens($k, $liens);
        }
        $this->parametresModel->updateLiens();
        $this->redirectToMethod('Parametrage', 'roles');
    }

    public function  validation()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected'] && $_SESSION['isVerified'] != 0) {
            $this->redirectToMethod('Home', 'index');
        } else {
            $form = getForm($_POST);
            extract($form);
            if ($code == $_SESSION['code']) {
                $_SESSION['isVerified']    = true;
                $_SESSION['isConnected']    = true;
                $this->utilisateurModel->changeStateVerif($_SESSION['connectedUser']->idUtilisateur, 1);
                $this->redirectToMethod('Home', 'index');
                //  if( $_SESSION['firstConnection'] == true){
                //     $_SESSION['isConnected']    = true;
                //     $this->redirectToMethod('Home','index');
                //  }else{
                //     $this->view('home/password');
                //  }
            } else {
                $_SESSION['isVerified']    = false;
                $data = [
                    "result" => "erreur"
                ];
                $this->view('home/' . __FUNCTION__, $data);
            }
        }
    }

    public function changePassword()
    {
        if (isset($_SESSION['isConnected']) && $_SESSION['isConnected']) {
            $this->redirectToMethod('Home', 'index');
        } else {
            $form = getForm();
            extract($form);
            if ($password2 == $password3) {
                if (strlen($password2) >= 8) {
                    //Update password
                    if ($this->utilisateurModel->changePass($_SESSION['connectedUser']->idUtilisateur, "passer", $password2) == 1) {
                        $_SESSION['firstConnection'] = true;
                        $_SESSION['isConnected']    = true;
                        $this->redirectToMethod('Home', 'index');
                    } else {
                        $data = [
                            "message" => "Impossible de modifier le mot de passe !"
                        ];
                        $this->view('home/password', $data);
                    }
                } else {
                    $data = [
                        "message" => "8 caractéres requis !"
                    ];
                    $this->view('home/password', $data);
                }
            } else {
                $data = [
                    "message" => "Les deux mots de passe doivent être identiques !"
                ];
                $this->view('home/password', $data);
            }
        }
    }

    public function forgotPassword($login = "")
    {
        $msg = "";
        $mail = "";
        $data = ["message" => $msg, "email" => $mail];
        if (!Role::isLogged()) {
            if (empty($login)) {
                $this->view("home/" . __FUNCTION__, $data);
            } else {
                $form = getForm($_POST);
                extract($form);
                $user = $this->utilisateurModel->findUserByEmail($email);
                if ($user) {
                    //Envoyer un mail
                    if (isset($email) && $login == "login") {
                        $token = md5(uniqid(rand(), true));
                        //ADD TOKEN PASSWORD
                        $res = $this->utilisateurModel->addTokenPwd($email, $token);
                        if ($res) {
                            $param = $email . "~" . $token;
                            $to = $email;
                            $subject = "Recupération de mot de passe";

                            $txt = "Bonjour $user->civiliteContact $user->fullName, <br> <br> vous avez oublié votre mot de passe. <br> 
                            Veuillez cliquer sur ce lien pour le réinitialiser : <br>
                            <b><a href='" . URLROOT . "/Home/reset/$param'>Réinitialiser mot de passe ...</a></b> <br><br>
                            Veuillez ne pas répondre à ce message envoyé automatiquement pour votre information.
                            <br><br>
                            <b>WBCC ASSISTANCE</b><br>
                            Service Recrutement
                             ";
                            if (Role::mailOnServer($to, $subject, $txt)) {
                                $msg = "3";
                                $mail = $email;
                            } else {
                                //Erreur envoi du mail
                                $msg = "Erreur lors de l'envoi du mail, veuillez réessayer !";
                            }
                        } else {
                            $msg = "Mot de passe non réinitialisé, veuillez réessayer !";
                        }
                    }
                } else {
                    //Email incorrect
                    $msg = "L'email renseigné n'existe pas !";
                }
                $data = ["message" => $msg, "email" => $email];
                $this->view("home/" . __FUNCTION__, $data);
            }
        } else {
            $this->redirectToMethod("Home", "index");
        }
    }

    public function  reset($mailToken = '')
    {
        if (empty($mailToken)) {
            $this->view('home/' . __FUNCTION__);
        } else {
            $mail = explode("~", $mailToken)[0];
            $token = explode("~", $mailToken)[1];
            $user = $this->utilisateurModel->findUserByEmail($mail);
            if ($user) {
                $this->utilisateurModel->resetPassword($user->idUtilisateur, "1");
                $this->view('home/' . __FUNCTION__, ["email" => $mail]);
            } else {
            }
        }
    }

    public function resetPassword()
    {
        $form = getForm($_POST);
        extract($form);
        $msg = "";
        if ($nouveauMdp != $cMdp) {
            $msg = "1";
        } else {
            //Effectuer modification
            $user = $this->utilisateurModel->findUserByEmail($email);
            if (trim($nouveauMdp) != "") {
                $nouveauMdp = sha1($nouveauMdp);
                $res1 = $this->utilisateurModel->updatePassword($nouveauMdp, $user->idUtilisateur);
                if ($res1) {
                    $this->redirectToMethod("Home", "connexion");
                }
            } else {
                $msg = "2";
            }
        }
        $data = [
            "msg" => $msg,
            "email" => $email
        ];
        $this->view('home/reset', $data);
    }

    public function account($message = '')
    {
        if (Role::isLogged()) {
            $this->redirectToMethod("Home", "index");
        } else {
            $data = [
                "roles" => $this->roleModel->getAllByEtat(),
                'message' => $message
            ];

            $this->view('utilisateur/' . __FUNCTION__, $data);
        }
    }

    public function confirm($mailToken = '')
    {
        if (empty($mailToken)) {
            $this->view('home/' . __FUNCTION__);
        } else {
            $mail = explode("~", $mailToken)[0];
            $token = explode("~", $mailToken)[1];
            $user = $this->utilisateurModel->findUserByEmail($mail);
            $etat = 0;
            if ($user) {
                if ($user->idRole == "16" || $user->idRole == "17") {
                    $etat = 1;
                } else {
                    $etat = 1;
                }
                $this->utilisateurModel->updateConnexion($user->idUtilisateur, $etat);
                $this->redirectToMethod('Home', 'confirm');
            } else {
            }
        }
    }

    public function closeEmailTicket($idOpportunity = null)
    {
        if ($idOpportunity != null) {
            $usrlArray = explode('/', $_SERVER['REQUEST_URI']);
            $lengthUerlArray = count($usrlArray);
            $idOpportunity = $usrlArray[$lengthUerlArray - 1];
            $idOpportunity = $this->TicketModel->decrypetMessage($idOpportunity);
            $ticket = $this->TicketModel->getTicketBayId($idOpportunity);
            if (!empty($ticket)) {
                $this->view('home/' . __FUNCTION__, ['ticket' => $ticket]);
            } else {
                $this->redirectToMethod("Home", "index");
            }
        } else {
            $this->redirectToMethod("Home", "index");
        }
    }
}
