<?php
$logo = (true) ? 'LOGO_SOS_SINISTRE.jpg' : "logo_WBCC.png";
$user = $_SESSION["connectedUser"];
$role = $user->idRole;
$access = getModulesByIdUserAndIdRole($user);
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= linkTo("Home") ?>">
        <div class="sidebar-brand-icon mr-1">
            <img src="<?= URLROOT . '/images/' . $logo ?>" alt="" class="img-fluid">
        </div>
        <div>
            <div class="sidebar-brand-text mx-3 my-0">
                <div>
                    <h1><b>RYM</b></h1>
                    <span>
                        <small>BY WBCC</small>
                    </span>
                </div>
            </div>
        </div>
    </a>
    <br>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <?php
    if (sizeof($access) > 1) {
        foreach ($access as $key => $item) {
    ?>
            <li class="nav-item">
                <a class="nav-link collapsed text-white" href="#" data-toggle="collapse" data-target="#collapse<?= $key ?>"
                    aria-expanded="true" aria-controls="collapse<?= $key ?>">
                    <span class="font-weight-bold   "><?= strtoupper($item->nomModule) ?></span>
                </a>
                <div id="collapse<?= $key ?>" class="collapse p-0" aria-labelledby="heading<?= $key ?>"
                    data-parent="#accordionSidebar">
                    <div class="collapse-inner mt-0 ml-0">
                        <?php foreach ($item->sousModules as $d) {
                        ?>
                            <a class="collapse-item  text-white font-weight-bold ml-0 pl-2"
                                href="<?= linkTo($d->controller, $d->function) ?>"><i class="<?= $d->icon ?> ml-0"></i>
                                <span><?= $d->nomSousModule ?></span></a>

                        <?php } ?>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider my-0">
            <?php }
    } else {
        if (sizeof($access) == 1) {
            $item = $access[0];
            foreach ($item->sousModules as $d) {
            ?>
                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link" href="<?= linkTo($d->controller, $d->function) ?>">
                        <i class="<?= $d->icon ?>"></i>
                        <span><?= $d->nomSousModule ?></span></a>
                </li>

    <?php }
        }
    }
    ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
    <!-- Sidebar Message -->
    <div class="sidebar-card">

        <p class="text-center mb-2"><strong>WBCC EXTRANET</strong> facilite l'échange entre WBCC ASSISTANCE et ses
            collaborateur!</p>
        <a target="_blank" class="btn btn-grey btn-sm" href="https://www.wbcc.fr">wbcc.fr</a>
    </div>

</ul>
<!-- End of Sidebar -->