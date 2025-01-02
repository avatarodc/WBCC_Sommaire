<!-- Main Navigation -->
<div id="wrapper">
    <?php

    if (Role::isConnected()) {

        $icon = 'fa fa-sign-out fa-1x';
        $text = 'Déconnexion';
        $lien = linkTo('Home', 'logout');
    }
    ?>

    <?php Role::isConnected() ? include_once APPROOT . '/views/inc/sideBar.php' : '' ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <?php Role::isConnected() ? include_once APPROOT . '/views/inc/topBar.php' : '' ?>

            <!-- Main Navigation -->