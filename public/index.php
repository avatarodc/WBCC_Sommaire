<?php
// $timeout = 60 * 8;

// //Set the maxlifetime of the session
// ini_set("session.gc_maxlifetime", $timeout);
ob_start();
session_start();

if (!isset($_SESSION['redirect_url']) && !strstr($_SERVER['REQUEST_URI'], "Home/login") && !strstr($_SERVER['REQUEST_URI'], "Home/connexion")) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
}

require_once "../app/bootstrap.php";
require APPROOT . '/views/inc/header.php';
include_once APPROOT . '/views/inc/navigation.php';
?>

<!-- Main layout -->


<div class="container-fluid">
    <?php Render::body(); ?>
</div>


<!-- Main layout -->

<?php
include_once APPROOT . '/views/inc/footer.php';
ob_end_flush();
?>