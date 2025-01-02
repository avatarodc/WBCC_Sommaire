<!-- Navbar -->
<?php
$icon = 'fas fa-sign-out fa-1x';
$text = 'Connexion';
$lien = linkTo('Home', 'connexion');
if (Role::isLogged()) {
    $icon = 'fas fa-sign-out-alt fa-1x';
    $text = 'Déconnexion';
    $lien = linkTo('Home', 'logout');
}
$hidden = $_SESSION['connectedUser']->firstConnection == 0 ? '' : 'hidden';
if ((isset($_SESSION['connectedUser']->typeCompany) && $_SESSION['connectedUser']->typeCompany == "artisan") || $_SESSION['connectedUser']->isInterne == "1") {
    $hiddenAgenda = "";
} else {
    $hiddenAgenda = "hidden";
}
?>
<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-red topbar mb-2 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars" style="color: #f00000"></i>
    </button>
    <div class="input-group-append">
        <button onclick="copy()" class="btn btn-grey" type="button" title="Appeler WBCC">
            <i class="fas fa-phone-alt fa-md"></i>
        </button>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
        <li class="nav-item dropdown no-arrow d-sm-none">
            <!-- Dropdown - Messages -->
            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                            aria-label="Search" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>
        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" target="_blank" href="https://www.facebook.com/wbcc.fr/"
                id="messagesDropdown" role="button" aria-expanded="false">
                <i class="fab fa-facebook fa-fw"></i>
            </a>
        </li>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" target="_blank" href="https://www.instagram.com/wbcc_assistance/"
                id="messagesDropdown" role="button" aria-expanded="false">
                <i class="fab fa-instagram-square fa-fw"></i>
            </a>
        </li>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" target="_blank" href=" https://twitter.com/WBCC_Nanterre"
                id="messagesDropdown" role="button" aria-expanded="false">
                <i class="fab fa-twitter-square fa-fw"></i>
            </a>
        </li>
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" target="_blank"
                href="https://www.linkedin.com/company/worldbusiness-contact-center" id="messagesDropdown" role="button"
                aria-expanded="false">
                <i class="fab fa-linkedin fa-fw"></i>
            </a>
        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span
                    class="mr-2 d-none d-lg-block text-white-600 "><?= empty(Role::nomComplet()) ? '' : '' . Role::nomComplet() ?></span><br /><br>
                <span
                    class="mr-2 d-none d-sm-block text-white-100 "><?= isset($_SESSION['connectedUser'])  ? (!isset($_SESSION['connectedUser']->isInterne) || (isset($_SESSION['connectedUser']->isInterne) && $_SESSION['connectedUser']->isInterne == "1") ? (isset($_SESSION['connectedUser']->nomSite) ? "(" . $_SESSION['connectedUser']->nomSite . ")" : "") : (isset($user->nomCompany) ? "(" . ($_SESSION['connectedUser']->nomCompany) . ")" :  ""))  : "" ?></span>
                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= linkTo('Utilisateur', 'agenda') ?>" $hiddenAgenda>
                    <i class="fas fa-calendar fa-sm fa-fw mr-2 text-red-400"></i>
                    Mon Agenda
                </a>
                <a <?= isset($_SESSION['connectedUser']) && ($_SESSION['connectedUser']->isExpert == "1" || $_SESSION['connectedUser']->isCommercial == "1" || $_SESSION['connectedUser']->role == "1") ? "" : "hidden" ?>
                    class="dropdown-item"
                    href="<?= linkTo('Utilisateur', 'configuration', (isset($_SESSION['connectedUser']) ? $_SESSION['connectedUser']->idUtilisateur : "")) ?>">
                    <i class="fas fa-fw fa-user-cog mr-2 text-red-400"></i>
                    Configuration
                </a>
                <a class="dropdown-item" href="<?= linkTo('Utilisateur', 'profil') ?>">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-red-400"></i>
                    Profil
                </a>
                <a class="dropdown-item" href="<?= linkTo('Utilisateur', 'historique') ?>">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-red-400"></i>
                    Historique
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= $lien ?>">
                    <i class="<?= $icon ?> fa-sm fa-fw mr-2 text-red-400"></i>
                    <?= $text ?>
                </a>
            </div>
        </li>

    </ul>
</nav>
<div class="row col-md-12 alert alert-danger mt-0 text-center" <?= $hidden ?>>
    <span class="h6 text-uppercase"><i class="fa fa-exclamation-triangle"></i>
        Vous utilisez le mot de passe attribué par défaut. Veuillez le modifier pour plus de sécurité
        <a class="" href="<?= linkTo('Utilisateur', 'profil') ?>">
            <span class="text-lowercase text-primary">(Cliquez sur aide pour les informations de modification de mot de
                passe)</span>
        </a>
    </span>

</div>
<!-- End of Topbar -->