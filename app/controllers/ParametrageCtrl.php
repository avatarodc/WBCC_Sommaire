<?php


class ParametrageCtrl extends Controller
{
    public function __construct(){
        Role::accessiblePar("administrateur","administrateur");
        $this->parametresModel = $this->model('Parametres');
        $this->roleModel = $this->model('Roles');

    }

    //Roles
    public function roles(){
        $roles = $this->roleModel->getAllByEtat();
        $data = [
            "title"                     => "Les Rôles",
            "roles"                     => $roles
        ];
        $this->view('roles/index',$data);
    }
    public function newRole(){
        //$situations = $this->roleModel->addRole();
        $roles = $this->roleModel->getAllByEtat();
        $data = [
            "title"                 => "Nouveau Rôle",
            "roles"                 => $roles
        ];
        $this->view('roles/'.__FUNCTION__,$data);
    }
    public function saveRoles(){
        $form = getForm();
        extract($form);
        $this->roleModel->saveRole($role,$ctrl);
        $this->redirectToMethod('Parametrage','roles');
    }

    public function changeRoleState($etat){
        $form = getForm();
        extract($form);
        $this->roleModel->changeRoleState($role,$etat);
        $this->redirectToMethod('Parametrage','roles');
    }

    public function updateRole(){
        $form = getForm();
        extract($form);
        $this->roleModel->updateRole($nomGroupe, $nomCtrl, $idGroupe);
        $this->redirectToMethod('Parametrage','roles');
    }
    
}