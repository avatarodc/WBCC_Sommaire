<?php
date_default_timezone_set('Europe/Paris');
define('ENABLE_CONNEXION', true);
//Paramètres de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'extranet_wbcc_fr');
//
define('PORT', ':8080');
//Répértoire racine
define('APPROOT', dirname(dirname(__FILE__))); // : pour acceder au dossier app
//URL Racine
define('URLROOT', 'http://localhost' . PORT . '/Extranet_WBCC-FR'); //pour acceder au dossier public

define('URLROOT_GESTION_WBCC_CB', 'http://localhost' . PORT . '/gestion_wbcc_cb'); //pour acceder au dossier public

//Nom du site
define('SITENAME', 'WBCC-FR | Extranet');
//Repertoire des photo de profil
define('URLPHOTO', $_SERVER['DOCUMENT_ROOT'] . "/Extranet_WBCC-FR/public/img/");
//Repertoire des docments
define('URLDOCUMENT', $_SERVER['DOCUMENT_ROOT'] . "/Extranet_WBCC-FR/public/documents/");
//Footer
define('SSD', '© 2021 Copyright : WBCC ');
//Titre
define('TITRE_APP', 'EXTRANET INFO COPRO');
//Adresse & TEL APP
define('ADRESSE', '');
define('TELEPHONES', 'Tel: ');
define('ADRESSE_COMPLET', '');
define('EMAIL_CODIR', []);
define('ID_DO_TEST', 981);


/************** VARIABLES ESPOIR DEBUT ****************** */

define('EXENGE_SERVEUR', 'ex2.mail.ovh.net');
define('IMAP_PORT', 993);
define('IMAP_ENCRYPTION', 'ssl');
define('IMAP_VALIDATE_CERTIFICAT', true);
define('IMAP_PROTOCOL', 'imap');
define('IMAP_USERNAME', 'gestion@wbcc.fr');
define('IMAP_PASSWORD', '');

define('IMAP_SERVEUR', 'ssl0.ovh.net');
define('NOREPLY_USERNAME', 'no_reply@wbcc.fr');
define('NOREPLY_PASSWORD', '');
/*define('NOREPLY_USERNAME', 'noreply@wbcc.fr');
  define('NOREPLY_PASSWORD', '');*/
//define('NOREPLY_PORT', 465); 
define('NOREPLY_PORT', 25);

define('IMAP_BOITE_RECEPTION', 'INBOX');
define('IMAP_MESSAGES_ENVOYES', 'Éléments envoyés');
define('IMAP_BROUILLONS', 'Trash');

define('ENCRYPTY_KEY', '');
define('ENCRYPTY_IV', '');
define('ENCRYPTY_ALGORITHM', 'aes-256-cbc');
define('ENCRYPTY_REPLACE_SLASH', '--HS--ALS--'); // remplacer '/' par cette constante
define('ENCRYPTY_SECURE_VAL', 'CB');

define('SIGNATURE_EMAIL_LOGO', 'sinature-left-background.png');
define('SIGNATURE_EMAIL_CONTACT', 'gestion@wbcc.fr');
define('SIGNATURE_EMAIL_TEL', '(+33) 9 800 844 84');
define('SIGNATURE_EMAIL_COMPANY', 'Relais Habitat - Syndic de Redressement');
define('SIGNATURE_EMAIL_ADRESSE_POSTALE', '218, rue de Bellevue, 92700 Colombes');
define('SIGNATURE_EMAIL_WEB_SITE', '	www.sossinistre.fr');
define('SIGNATURE_EMAIL_FACEBOOK_ICON', 'facebook.png');
define('SIGNATURE_EMAIL_FACEBOOK_LINK', '');
define('SIGNATURE_EMAIL_INSTAGRAM_ICON', 'instagram-icon.png');
define('SIGNATURE_EMAIL_INSTAGRAM_LINK', 'https://www.instagram.com/sos_sinistre/');
define('SIGNATURE_EMAIL_TWEETER_ICON', 'x-icon.png');
define('SIGNATURE_EMAIL_TWEETER_LINK', 'https://twitter.com/SOS_sinistre');
define('SIGNATURE_EMAIL_LINKEDIN_ICON', 'linkedIn-icon.png');
define('SIGNATURE_EMAIL_LINKEDIN_LINK', 'https://www.linkedin.com/showcase/sos-sinistre-by-wbcc-assistance/about/');

define('SIGNATURE_EMAIL_PHONE_ICON', 'phone-icon.png');
define('SIGNATURE_EMAIL_EMAIL_ICON', 'email-icon.png');
define('SIGNATURE_EMAIL_LOCATION_ICON', 'location-icon.png');
define('SIGNATURE_EMAIL_WEBSITE_ICON', 'website-icon.png');

define('COMPANY_NAME', 'WBCC ASSISTANCE');

/************** VARIABLES ESPOIR FIN ****************** */
