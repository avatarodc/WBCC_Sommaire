<?php

class Role
{

    public  static function mailDTGWithCC($to, $subject, $body, $cc, $tabFiles, $fileNames)
    {
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 4;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->isHTML(true);
        $mail->Host = 'ssl0.ovh.net';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'operationdtgpama@rhsr.fr';                 // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 25;                    // TCP port to connect to
        $mail->setFrom('operationdtgpama@rhsr.fr');
        $mail->FromName = 'OPERATION DTG PAMA';
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($to);     // Add a recipient
        $mail->addBCC("operationdtgpama@rhsr.fr");
        foreach ($cc as $c) {
            $mail->addCC($c);
        }

        foreach ($tabFiles as $key => $file) {
            //var_dump($_SERVER['DOCUMENT_ROOT'] . $file);
            //die;
            if (file_exists($_SERVER['DOCUMENT_ROOT']  . $file)) {
                $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . $file, $fileNames[$key]);
            }
        }
        // Add a recipient
        $mail->Subject = $subject;
        $mail->Body    = $body;
        return true;
        if ($mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            // die;
            return true;
        } else {
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }
    }

    // ENVOI MAIL
    public  static function mailGestion($to, $subject, $body)
    {
        //if (strtolower($to) != "r.levy@cosybreak.com") 
        {
            $mail = new PHPMailer();
            //$mail->SMTPDebug = 4;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'gestion@wbcc.fr';                 // SMTP username
            $mail->Password = ''; // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                    // TCP port to connect to
            $mail->setFrom('gestion@wbcc.fr');
            $mail->FromName = 'WBCC ASSISTANCE';
            $mail->CharSet = 'UTF-8';
            $mail->addAddress($to);     // Add a recipient
            $mail->addBCC("gestion@wbcc.fr");                               // Add a recipient
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            return true;
            if ($mail->send()) {
                //  echo 'Mailer Error: ' . $mail->ErrorInfo;
                return true;
            } else {
                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                return false;
            }
        }
        return false;
    }

    public  static function mailGestionWithFiles($to, $subject, $body, $tabFiles, $fileNames, $cc = [])
    {
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 4;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'gestion@wbcc.fr';                 // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                    // TCP port to connect to
        $mail->setFrom('gestion@wbcc.fr');
        $mail->FromName = 'WBCC ASSISTANCE';
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($to);     // Add a recipient
        // $mail->addBCC("gestion@wbcc.fr");                               // Add a recipient
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        foreach ($cc as $c) {
            if ($c != "" && $c !=  null) {
                $mail->addCC($c);
            }
        }

        foreach ($tabFiles as $key => $file) {
            if (file_exists($_SERVER['DOCUMENT_ROOT']  . $file)) {
                $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . $file, $fileNames[$key]);
            }
        }
        // return true;
        if ($mail->send()) {
            //  echo 'Mailer Error: ' . $mail->ErrorInfo;
            return true;
        } else {
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }
    }

    public  static function mailExtranetWithFiles($to, $subject, $body, $cc, $tabFiles, $fileNames)
    {
        // if (strtolower($to) != "r.levy@cosybreak.com") 
        {
            $mail = new PHPMailer();
            //  $mail->SMTPDebug = 4;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'extranet@wbcc.fr';                 // SMTP username
            $mail->Password = ''; // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                    // TCP port to connect to
            $mail->setFrom('extranet@wbcc.fr');
            $mail->FromName = 'EXTRANET WBCC ASSISTANCE';
            $mail->CharSet = 'UTF-8';
            $mail->addAddress($to);     // Add a recipient
            // $mail->addBCC("extranet@wbcc.fr");
            $mail->isHTML(true);

            foreach ($cc as $c) {
                $mail->addCC($c);
            }

            foreach ($tabFiles as $key => $file) {
                //var_dump($_SERVER['DOCUMENT_ROOT'] . $file);
                //die;
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/Extranet_WBCC-FR' . $file)) {
                    $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . '/Extranet_WBCC-FR' . $file, $fileNames[$key]);
                }
            }
            // Add a recipient
            $mail->Subject = $subject;
            $mail->Body    = $body;
            return true;
            if ($mail->send()) {
                //  echo 'Mailer Error: ' . $mail->ErrorInfo;
                return true;
            } else {
                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                return false;
            }
        }
        return false;
    }

    public  static function mailOnServer($to, $subject, $body)
    {
        // if (strtolower($to) != "r.levy@cosybreak.com")
        {
            $mail = new PHPMailer();
            //  $mail->SMTPDebug = 4;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'extranet@wbcc.fr';                 // SMTP username
            $mail->Password = ''; // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                    // TCP port to connect to
            $mail->setFrom('extranet@wbcc.fr');
            $mail->FromName = 'EXTRANET WBCC ASSISTANCE';
            $mail->CharSet = 'UTF-8';
            $mail->addAddress($to);     // Add a recipient
            // $mail->addBCC("extranet@wbcc.fr");                               // Add a recipient
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            // return true;
            if ($mail->send()) {
                //  echo 'Mailer Error: ' . $mail->ErrorInfo;
                return true;
            } else {
                // echo 'Mailer Error: ' . $mail->ErrorInfo;
                return false;
            }
        }
        return false;
    }

    public  static function mailRelationWithCC($to, $subject, $body, $cc, $tabFiles, $fileNames)
    {
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 4;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->isHTML(true);
        $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'relationclient@wbcc.fr';                 // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                    // TCP port to connect to
        $mail->setFrom('relationclient@wbcc.fr');
        $mail->FromName = 'RELATION WBCC ASSISTANCE';
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($to);     // Add a recipient
        $mail->addBCC("relationclient@wbcc.fr");
        foreach ($cc as $c) {
            $mail->addCC($c);
        }

        foreach ($tabFiles as $key => $file) {
            var_dump($_SERVER['DOCUMENT_ROOT'] . $file);
            die;
            if (file_exists($_SERVER['DOCUMENT_ROOT']  . $file)) {
                $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . $file, $fileNames[$key]);
            }
        }
        // Add a recipient
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if ($mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            // die;
            return true;
        } else {
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }
    }

    public  static function mailRelation($to, $subject, $body, $cc, $tabFiles)
    {
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 4;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->isHTML(true);
        $mail->Host = 'ex2.mail.ovh.net';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'relationclient@wbcc.fr';                 // SMTP username
        $mail->Password = ''; // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                    // TCP port to connect to
        $mail->setFrom('relationclient@wbcc.fr');
        $mail->FromName = 'RELATION WBCC ASSISTANCE';
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($to);     // Add a recipient
        $mail->addBCC("relationclient@wbcc.fr");
        if ($cc) {
            $mail->addCC($cc);
        }                              // Add a recipient

        foreach ($tabFiles as $file) {
            //var_dump($_SERVER['DOCUMENT_ROOT'] . $file);
            //die;
            if (file_exists($_SERVER['DOCUMENT_ROOT']  . $file)) {
                $mail->addAttachment($_SERVER['DOCUMENT_ROOT'] . $file, "OFFRE_WBCC.pdf");
            }
        }
        // Add a recipient
        $mail->Subject = $subject;
        $mail->Body    = $body;

        if ($mail->send()) {
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            // die;
            return true;
        } else {
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        }
    }

    /******* TICKETS *****/
    public  static function replayEmailTicket($to, $cc, $subject, $message_id, $body, $tabFiles)
    {
        // ENVOYER AVEC LE LIEN DE CLÔTURE DU TICKET
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = EXENGE_SERVEUR;
        $mail->SMTPAuth = true;
        $mail->Username = IMAP_USERNAME;
        $mail->Password = IMAP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(IMAP_USERNAME);
        //$mail->AddCC(NOREPLY_USERNAME);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        foreach ($to as $one_to) {
            $mail->addAddress($one_to);
        }

        $mail->addCustomHeader('In-Reply-To', $message_id);
        $mail->addCustomHeader('References', $message_id);

        $mail->Subject = $subject;
        $mail->Body    = $body[0];

        foreach ($tabFiles as $file) {
            if (file_exists($file[0])) {
                $mail->addAttachment($file[0], $file[1]);
            }
        }

        if ($mail->send()) {
            $response = true;
        } else {
            $response = false;
        }


        // ENVOYER LE MAIL AUX AUTRES CONTACTS SANS LE LIEN DE CLÔTURE DU TICKET


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = EXENGE_SERVEUR;
        $mail->SMTPAuth = true;
        $mail->Username = IMAP_USERNAME;
        $mail->Password = IMAP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(IMAP_USERNAME);
        //$mail->AddCC(NOREPLY_USERNAME);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->addAddress(IMAP_USERNAME);
        foreach ($cc as $one_cc) {
            $mail->AddCC($one_cc);
        }

        $mail->addCustomHeader('In-Reply-To', $message_id);
        $mail->addCustomHeader('References', $message_id);

        $mail->Subject = $subject;
        $mail->Body    = $body[1];

        foreach ($tabFiles as $file) {
            if (file_exists($file[0])) {
                $mail->addAttachment($file[0], $file[1]);
            }
        }

        if ($mail->send()) {
            return $mail->getLastMessageID();
        } else {
            return false;
        }
    }

    public  static function sendNewMail($to, $cc, $subject, $body, $tabFiles)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = EXENGE_SERVEUR;
        $mail->SMTPAuth = true;
        $mail->Username = IMAP_USERNAME;
        $mail->Password = IMAP_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(IMAP_USERNAME);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->AddCC(IMAP_USERNAME);

        foreach ($to as $one_to) {
            $mail->addAddress($one_to);
        }

        foreach ($cc as $one_cc) {
            $mail->AddCC($one_cc);
        }
        $mail->Subject = $subject;
        $mail->Body    = $body;

        foreach ($tabFiles as $file) {
            if (file_exists($file[0])) {
                $mail->addAttachment($file[0], $file[1]);
            }
        }

        if ($mail->send()) {
            return $mail->getLastMessageID();
        } else {
            return false;
        }
    }

    public  static function emailFromNoReply($to, $tabCC, $subject, $body, $tabFiles)
    {
        $mail = new PHPMailer();
        //$mail->SMTPDebug = 3;  
        $mail->isSMTP();
        $mail->Host = IMAP_SERVEUR;
        $mail->SMTPAuth = true;
        $mail->Username = NOREPLY_USERNAME;
        $mail->Password = NOREPLY_PASSWORD;
        $mail->SMTPSecure = 'tls';
        $mail->Port = NOREPLY_PORT;
        $mail->setFrom(NOREPLY_USERNAME);
        $mail->CharSet = 'UTF-8';
        $mail->addAddress($to);
        $mail->AddCC(NOREPLY_USERNAME);
        $mail->isHTML(true);

        $mail->Subject = $subject;
        //echo strlen($body); die();
        $mail->Body    = $body;

        foreach ($tabCC as $cc) {
            $mail->addCC($cc);
        }

        foreach ($tabFiles as $file) {
            if (file_exists($file)) {
                $mail->addAttachment($file);
            }
        }

        if ($mail->send()) {
            // echo 'Mailer ok: ' . $mail->ErrorInfo;
            /*echo 'ok';
              die();*/
            return true;
        } else {
            /*echo 'Mailer Error ko: ' . $mail->ErrorInfo;
              die();*/
            return false;
        }
    }

    public static function getAccessibleMethods()
    {
        $access = isset($_SESSION['connectedUser']) ? $_SESSION['connectedUser']->accessibilite : '';
        $tabAccess = explode(",", $access);
        return $tabAccess;
    }

    public static function getRole()
    {
        return $_SESSION['connectedUser']->libelleRole;
    }

    public static function getIdRole()
    {
        return $_SESSION['connectedUser']->idRole;
    }


    public static function privateMethode($method)
    {
        if (!self::isLogged()) {
            header("location:Home/connexion");
        } else {
            $access = $_SESSION['connectedUser']->accessibilite;
            $tabAccess = explode(",", $access);
        }
    }
    public static function accessiblePar($role, $admin = '')
    {
        if (!self::isLogged()) {
            header("location:Home/connexion");
        } else {
            if ($admin == "administrateur" && !(strtolower(Role::getRole()) == "administrateur")) {
                redirectToPage('Home', 'index');
            }
            if (!(strtolower(Role::getRole()) == $role || strtolower(Role::getRole()) == "administrateur" || strtolower(Role::getRole()) == "superviseur")) {
                redirectToPage('Home', 'index');
            }
        }
    }

    public static function isLogged()
    {
        return isset($_SESSION['connectedUser']);
    }

    public static function isVerified()
    {
        return $_SESSION['isVerified'];
    }

    public static function passwordChanged()
    {

        return $_SESSION['firstConnection'];
    }

    public static function isConnected()
    {
        if (isset($_SESSION['isConnected']))
            return $_SESSION['isConnected'];
        else
            return false;
    }

    public  static function connectedUser()
    {
        return self::isLogged() ? $_SESSION['connectedUser'] : null;
    }

    public  static function nomComplet()
    {
        $nom =  self::isLogged() ? $_SESSION['connectedUser']->prenomContact . " " . $_SESSION['connectedUser']->nomContact : "";
        return $nom;
    }

    public  static function nomCompanyUser()
    {
        $nom =  self::isLogged() ? $_SESSION['connectedUser']->companyName : "";
        return $nom;
    }
}
