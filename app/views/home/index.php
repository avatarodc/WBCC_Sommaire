<?php
redirectToConnection();
$logo = ($do) ? (($do->categorieDO == "Collectivité Publique") ? "logo_WBCC.png" : "LOGO_SOS_SINISTRE.jpg") : "logo_WBCC.png";
$image1 =  ($do) ? (($do->categorieDO == "Collectivité Publique") ? "img-travaux.jpg" : "Ecosystem_SOSinistre.jpg") : "img-travaux.jpg";
?>

<!-- Page Heading -->
<section id="accueil" class="services mb-0 pb-0">
    <div class="container" data-aos="fade-up">
        <marquee>
            <h1 style="color: #c00000" class=" text-center my-auto mb-3 font-weight-bold">
                <?= ($do) ? (($do->categorieDO == "Collectivité Publique") ? "Bienvenue sur votre Extranet chez WBCC" : "Bienvenue sur votre Extranet 'SOS SINISTRE' chez WBCC") : "Bienvenue sur votre Extranet chez WBCC" ?>
            </h1>
        </marquee>
        <!-- <div class="col-lg-12 text-center pb-2 d-flex align-items-center">
            <img class=" position-relative" src="<?= URLROOT . '/images/img-travaux.jpg' ?>" alt=""
                style="width:100%; height:400px">
            <div class="im w-100  col-lg-10 col-md-10 col-sm-10 col-xs-10 ">
                <h1 class="text-white text-center my-auto mb-3 font-weight-bold">Bienvenue dans l'extranet WBCC</h1>
                <div class="text-white text-justify">
                    <span>
                    </span>
                </div>
                <button class="btn-lg my-auto mb-5"
                    style="border-radius:80px;background-color: #fff; border: 2px solid #c00000; color: #c00000;">Suivez
                    vos dossiers et missions en temps réel</button>
            </div>
        </div> -->
        <!-- <div class="section-title">
            <h2> <span>
                    <i class="icofont-expand" style="color: #c00000"></i>
                </span>NOS PARTENAIRES</h2>
        </div> -->
        <div class="col text-center pb-2 d-flex align-items-center">
            <div id="carouselExampleIndicators" class="col carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block" src="<?= URLROOT . '/images/' . $image1 ?>" alt="First slide" width="1000px" height="500px">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block" src="<?= URLROOT . '/images/' . $logo ?>" alt="Second slide" width="1000px" height="500px">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

    </div>
</section>
<!-- ======= Avantages Section ======= -->
<section id="avantages" class="services">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2> <span>
                    <i class="icofont-expand" style="color: #c00000"></i>
                </span>AVANTAGES</h2>
            <p style="text-align:justify">
                Collaborer avec WBCC ASSISTANCE offre plusieurs avantages dont :
            </p>
        </div>
        <div class="section-block mt-0">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12">
                    <img height="100%" class="item-content-box item-block image img-fluid" data-at="image" alt="" src="<?= URLROOT . '/images/' . $logo ?>" data-src="<?= URLROOT . '/images/' . $logo  ?>" data-retina-src="<?= URLROOT . '/images/' . $logo  ?>" srcset="<?= URLROOT . '/images/' . $logo  ?> 2x">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 text-justify">
                    <?php
                    foreach ($avantages as $a) {
                    ?>
                        <div class="row mb-4">
                            <span class="col-1 ml-4"><i class="fas fa-2x fa-arrow-circle-right" style="color: #c00000"></i></span>
                            <span class="col ml-4">
                                <h4><?= $a->libelleAvantage ?></h4>
                            </span>
                        </div>
                    <?php    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End Avantages Section -->