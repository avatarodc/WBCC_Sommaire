<?php

/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 11/12/2019
 * Time: 5:23 PM
 */

class Utilisateur extends Model
{

    public function getUsersBySite($id)
    {
        $this->db->query("SELECT * FROM wbcc_contact c, wbcc_utilisateur u, wbcc_roles r WHERE c.idContact = u.idContactF AND u.role = r.idRole AND idSiteF=$id ");
        return $this->db->resultSet();
    }

    public function saveConfig($jours, $horaire, $marge, $cpZone, $ville, $commentaire, $adresse, $idUser, $departement, $moyen)
    {

        $this->db->query("UPDATE wbcc_utilisateur set jourTravail=:jour, horaireTravail=:horaire, cpZoneRV = :cpZoneRV, margeTravail=:margeTravail, villeZoneRV=:villeZoneRV , commentaireConfig=:com, adresseZoneRV=:adresse, codeDepartement=:codeDepartement, moyenTransport=:moyenTransport WHERE idUtilisateur=:id ");

        $this->db->bind("id", $idUser, null);
        $this->db->bind("jour", $jours, null);
        $this->db->bind("horaire", $horaire, null);
        $this->db->bind("cpZoneRV", $cpZone, null);
        $this->db->bind("margeTravail", $marge, null);
        $this->db->bind("adresse", $adresse, null);
        $this->db->bind("villeZoneRV", $ville, null);
        $this->db->bind("com", $commentaire, null);
        $this->db->bind("codeDepartement", $departement, null);
        $this->db->bind("moyenTransport", $moyen, null);

        return $this->db->execute();
    }

    public function findUserByIdContact($id)
    {
        $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c WHERE idContactF=$id
        AND u.role = r.idRole
        AND u.idContactF= c.idContact LIMIT 1");
        return $this->db->single();
    }

    public function findUserById($id)
    {
        $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c WHERE idUtilisateur=$id
        AND u.role = r.idRole
        AND u.idContactF= c.idContact LIMIT 1");
        return $this->db->single();
    }

    public function findUser($username, $pass)
    {
        $pass = sha1($pass);
        $this->db->query("
            SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c
            WHERE u.login='$username' 
            AND u.mdp='$pass'
            AND u.role = r.idRole
            AND u.idContactF=c.idContact
            LIMIT 1
        ");
        return $this->db->single();
    }
    //   -----------------------<|>
    public function getAll($orderBy = '')
    {
        $this->db->query("
            SELECT * FROM wbcc_contact c, wbcc_utilisateur u, wbcc_roles r
            WHERE c.idContact = u.idContactF AND u.role = r.idRole
        ");
        return $this->db->resultSet();
    }

    public function getUsersByType($type = "")
    {
        if ($type == 'particulier') {
            $this->db->query("
            SELECT * FROM wbcc_contact WHERE LOWER(statutContact) like '%particulier%'");
        } else {
            if ($type == 'Copro') {
                $this->db->query("
                SELECT * FROM wbcc_contact WHERE LOWER(statutContact) like '%copro%'");
            } else {
                if ($type == 'Occupant') {
                    $this->db->query("
                    SELECT * FROM wbcc_contact WHERE LOWER(statutContact) like '%locataire%' ");
                } else {
                    if ($type == "wbcc") {
                        $this->db->query("
                        SELECT * FROM wbcc_contact c, wbcc_utilisateur u, wbcc_roles r, wbcc_site s
                        WHERE c.idContact = u.idContactF AND u.idSiteF=s.idSite AND u.role = r.idRole AND isInterne=1 ORDER BY c.fullName ASC");
                    }
                }
            }
        }
        return $this->db->resultSet();
    }
    //   -----------------------<|>
    public function findUserByIdEmp($idEmp)
    {
        $this->db->query("
            SELECT * FROM  eic_employe e, eic_utilisateur u
            WHERE e.idEmp = u.idEmployeF
            AND e.idEmp = $idEmp
        ");
        return $this->db->single();
    }
    //   -----------------------<|>
    public function changeUserState($idUtilisateur, $oldState)
    {
        $oldState = ($oldState == 1) ? 0 : 1;
        $this->db->query("
            UPDATE wbcc_utilisateur
            SET etatUser = $oldState
            WHERE idUtilisateur = $idUtilisateur
        ");
        return $this->db->execute();
    }

    public function changeStateVerif($idUtilisateur, $state)
    {
        $this->db->query("
            UPDATE wbcc_utilisateur
            SET isVerified = $state
            WHERE idUtilisateur = $idUtilisateur
        ");
        return $this->db->execute();
    }

    //   -----------------------<|>
    public function addUser($login, $mdp, $email, $idRoleF, $idContact, $token, $idSite = null, $isInterne = 0)
    {
        $etat = 1;
        if ($idRoleF == 17) {
            $etat = 2;
        }
        $pass = sha1($mdp);
        $this->db->query("INSERT INTO wbcc_utilisateur(login,mdp,email,role,etatUser,firstConnection,idContactF, token, idSiteF, isInterne) 
        VALUES (:login,:pass,:email, :idRoleF, :etat, :firstConnection, :idContact, :token, :idSiteF, :isInterne)");
        $this->db->bind('login', "$login", null);
        $this->db->bind('pass', "$pass", null);
        $this->db->bind('email', "$email", null);
        $this->db->bind('idRoleF', $idRoleF, null);
        $this->db->bind('etat', $etat, null);
        $this->db->bind('firstConnection', '0', null);
        $this->db->bind('idContact', $idContact, null);
        $this->db->bind('token', $token, null);
        $this->db->bind('idSiteF', $idSite, null);
        $this->db->bind('isInterne', $isInterne, null);
        return $this->db->execute();
    }
    //   -----------------------<|>
    public function changeRole($idUser, $newRole)
    {
        $this->db->query("
            UPDATE eic_utilisateur
            SET role = '$newRole'
            WHERE idEmployeF = $idUser
        ");
        return $this->db->execute();
    }
    //   -----------------------<|>
    public function changePass($id, $pass)
    {
        $pass = sha1($pass);

        $this->db->query("
            UPDATE wbcc_utilisateur SET mdp='$pass', firstConnection=1 WHERE idUtilisateur=$id
        ");
        if ($this->db->execute()) {
            return 1;
        }
        return 0;
    }

    //   -----------------------<|>
    public function deleteUser($id)
    {
        $this->db->query("
            DELETE FROM wbcc_utilisateur WHERE idContactF = $id
        ");
        $this->db->execute();
    }

    //   -----------------------<|>
    public function updateUser($idContact, $email, $idRole, $idSite = null)
    {
        $this->db->query("UPDATE wbcc_utilisateur
            SET  email=:email, role=:idRole, idSiteF=:idSite
            WHERE idContactF = :idContact
        ");
        $this->db->bind("email", $email, null);
        $this->db->bind("idContact", $idContact, null);
        $this->db->bind("idRole", $idRole, null);
        $this->db->bind("idSite", $idSite, null);
        return $this->db->execute();
    }

    //   -------------------- <|>
    public function findUserByEmail($email)
    {
        $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c WHERE email='$email' 
         AND u.role = r.idRole
         AND u.idContactF= c.idContact LIMIT 1");
        return $this->db->single();
    }

    public function addTokenPwd($email, $token)
    {
        $this->db->query("
        UPDATE wbcc_utilisateur SET tokenPwd='$token' WHERE login='$email'");
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    public function resetPassword($idUser, $etat)
    {
        $this->db->query("UPDATE wbcc_utilisateur SET  tokenPwd='', mdp='wbccfr' WHERE idUtilisateur = $idUser");
        return $this->db->execute();
    }

    public function updatePassword($nouveauMdp, $idUser)
    {
        $this->db->query("
        UPDATE wbcc_utilisateur SET  mdp=:mdp WHERE idUtilisateur=$idUser");
        $this->db->bind("mdp", $nouveauMdp, null);
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    public function updateConnexion($idUser, $etat)
    {
        $this->db->query("UPDATE wbcc_utilisateur SET etatUser = $etat, firstConnection = 0, token='', valideCompte=1 WHERE idUtilisateur = $idUser");
        return $this->db->execute();
    }

    public function getUserByRole($role)
    {
        if ($_SESSION["connectedUser"]->libelleRole == "Manager de Site") {
            $idSite = $_SESSION["connectedUser"]->idSiteF;
            $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c, wbcc_site s  WHERE libelleRole='$role' 
                AND u.role = r.idRole
                AND u.idSiteF = s.idSite AND u.idSiteF = $idSite
                AND u.idContactF= c.idContact AND etatUser=1 ");
        } else {
            if ($_SESSION["connectedUser"]->typeCompany == "ARTISAN") {
                $idCompany = $_SESSION["connectedUser"]->idCompany;
                $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c, wbcc_contact_company cc WHERE (libelleRole='$role')
                AND u.role = r.idRole
                AND u.idContactF = cc.idContactF AND cc.idCompanyF = $idCompany
                AND u.idContactF= c.idContact AND etatUser=1 ");
            } else {
                $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c WHERE libelleRole='$role' 
                AND u.role = r.idRole
                AND u.idContactF= c.idContact AND etatUser=1 ");
            }
        }
        return $this->db->resultSet();
    }

    public function getUserByidsRoles($idRole1, $idRole2 = "", $type = "", $idSite = "")
    {
        $req = $idRole2 != "" ? " OR role=$idRole2 " :  "";
        if ($type == "expert") {
            $req .= " OR isExpert=1 ";
        }

        if ($type == "gestionnaire") {
            $req .= " OR role=3 OR role=25 OR isGestionnaire=1 ";
        }
        $req2 = "";

        if ($idSite != "") {
            $req2 .= " AND u.idSiteF = $idSite ";
        }

        if ($_SESSION["connectedUser"]->libelleRole == "Manager de Site") {
            $idSite = $_SESSION["connectedUser"]->idSiteF;
            $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c, wbcc_site s WHERE (role=$idRole1 $req ) $req2
            AND u.role = r.idRole
            AND u.idSiteF = s.idSite AND u.idSiteF = $idSite
            AND u.idContactF= c.idContact AND etatUser=1 ");
        } else {
            if ($_SESSION["connectedUser"]->typeCompany == "ARTISAN") {
                $idCompany = $_SESSION["connectedUser"]->idCompany;
                $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c, wbcc_contact_company cc WHERE (role=$idRole1 $req ) $req2
                AND u.role = r.idRole
                AND u.idContactF = cc.idContactF AND cc.idCompanyF = $idCompany
                AND u.idContactF= c.idContact AND etatUser=1 ");
            } else {
                $this->db->query("SELECT * FROM wbcc_utilisateur u, wbcc_roles r, wbcc_contact c WHERE (role=$idRole1 $req ) $req2
                AND u.role = r.idRole
                AND u.idContactF= c.idContact AND etatUser=1 ");
            }
        }
        return $this->db->resultSet();
    }
}
