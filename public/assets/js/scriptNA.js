
var iE = 0;
var iP = 0;
var iPh = 0;
var np = 0;
var iPdoc = 0;
var libellePiece = "";
var choixPiece = "";
var idPiece = "";
var libelleEquipement = "";
var choixEquipement = "";
var idEquipement = "";
var equipement = "";
var tabEquipements = [];
var tabPieces = [];
var documents = [];
var documentsCni = [];
var documentsCA = [];
var documentsContrat = [];

var libelleBien = "";
var choixBien = "";
var idBien = "";
var iB = 0;

var statut = "Copropriétaire";
var indiceTabEquip = 0;
var srcPhoto = "";
var srcVideo = "";
var srcPhotoDefaut = "https://recherchedefuite.wbcc.fr/public/images/defaut.png";
var commentaire = "";
var indexCompteur = "";
var photoCompteur = "";

function onClickChoixBien(choix) {
    choixBien = choix.split('*')[1];
    idBien = choix.split('*')[0];

    if (choixBien === "Autres") {
        document.getElementById("autreBien").style.display = "block";
    } else {
        document.getElementById("autreBien").style.display = "none";
    }
}

function onClickAddBien() {

    if (choixBien === "Autres") {
        libelleBien = document.getElementById("libelleAutreBien").value;
    } else {
        libelleBien = choixBien;
    }
    if (libelleBien.length === 0) {
        alert("Veuillez choisir un bien !")
    } else {
        nb = 0;
        var elements = document.querySelectorAll('[id^="bien"]');
        if (elements.length > 0) {
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].innerText.includes(libelleBien))
                    nb++;
            }
        }
        libelleBien = libelleBien + "-" + (nb + 1);
        addBien(idBien);
    }
    document.getElementById("libelleAutreBien").value = "";
}

function addBien(idPiece) {
    iB += 1;
    const divBien = $('#accordionBien');
    divBien.append(`
    <div class="card mt-2" id="bien${iB}">
    <input type="text" hidden name="biens[]" value="${libelleBien}">
        <div class="card-header" id="exItemBien${iB}Header" style="background: lightgrey">
            <div class="row">
                <h5 class="mb-0 col-md-11 col-9">
                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#exItemBien${iB}" aria-expanded="false" aria-controls="exItemBien${iB}"><i class="fa fa-couch text-warning"> </i> <span class="classBien">${libelleBien}</span></button>
                </h5>
                <div class="col-md-1 col-1">
                    <button class="btn btn-sm btn-danger mt-1" type="button" onclick="deleteBien(bien${iB},${idBien},'${libelleBien}')"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div id="exItemBien${iB}" class="collapse" aria-labelledby="exItemBien${iB}Header" data-parent="#accordionBien">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <label class="text-danger"> 1-Commentaire
                            <label>
                    </div>
                    <div class="col-12">
                        <Textarea name="commentaireBien${iB}" id="commentaireBien${iB}"
                            class="form-control"></Textarea>
                    </div>
                    
                 </div>
            </div>
        </div>
    </div> 
`);

    // Ajout des equipements dans la liste déroulante
    // idChoixEq = "choixEquipement" + iP;
    // $.each(data, (i, eq) => {
    //     $('#' + idChoixEq).append(`<option value="${eq.idEq}*${eq.libelleEq}*${idApp}*${idPiece}"> 
    //                        ${eq.libelleEq} 
    //                   </option>`);

    // });
}

function deleteBien(id, idBien, libelleValue) {

    const divBien = document.getElementById('accordionBien');
    divBien.removeChild(id);
    nb = 0;
    var elements = document.querySelectorAll('[id^="bien"]');
    if (elements.length > 0) {
        libelle1 = libelleValue.split("-")[0];
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].innerText.includes(libelle1)) {
                nb++;
                var obj = document.getElementById(elements[i].getAttribute('id')).getElementsByClassName('classBien')[0];
                obj.innerText = libelle1 + '-' + nb;

            }
        }
    }

}

function onClickConfirm() {
    const URLROOT = $('#URLROOT').val();
    // Recupérations de données

    //Relevé Compteur
    index = document.getElementById("indexCompteur").value;
    numeroCompteur = document.getElementById("numeroCompteur").value;
    photoCompt = "";
    nomCopro = "";
    prenomCopro = "";
    telCopro = "";
    emailCopro = "";
    if (document.getElementById("photoCompteurCapture"))
        photoCompt = document.getElementById("photoCompteurCapture").getAttribute('src');

    if (statut != "Copropriétaire Occupant" && statut != "Copropriétaire Non Occupant" && statut != "Copropriétaire") {
        nomCopro = document.getElementById("nomCopro").value;
        prenomCopro = document.getElementById("prenomCopro").value;
        telCopro = document.getElementById("telCopro").value;
        emailCopro = document.getElementById("emailCopro").value;
    }

    this.updateCompteurApp(index, numeroCompteur, photoCompt, idApp, nomCopro, prenomCopro, telCopro, emailCopro);
    //fin releve Compteur   

    //Recensement
    listPhotosCni = "";
    listPhotosCA = "";
    listPhotosContrat = "";

    if (statut.includes("Copropriétaire") || statut == "Locataire") {
        //Cni 
        if (document.getElementById("divDocument1").children.length > 0) {
            listPhotosCni = documentsCni.join("-");
        }
        //Contrat assurance
        if (document.getElementById("divDocument2").children.length > 0) {
            listPhotosCA = documentsCA.join("-");
        }
        //Contrat
        if (document.getElementById("divDocument3").children.length > 0) {
            listPhotosContrat = documentsContrat.join("-");
        }
    }
    this.updateStatutContact(statut, idContact, listPhotosCni, listPhotosCA, listPhotosContrat);

    //Fin Recensement

    //Recherche

    // Ajouter les équipements dans la BD
    for (let i = 0; i < tabEquipements.length; i++) {
        element = tabEquipements[i].split("*");
        idEquipF = element[0];
        idAppF = element[2];
        idPieceF = element[3].split("_")[0];
        idDivAccordion = element[3].split("_")[2];
        // console.log(idPieceF, idAppF);
        divExampleAccordion = document.getElementById("exampleAccordion" + idDivAccordion);
        listPhotos = divExampleAccordion.getElementsByClassName("listPhotos");
        // console.log(listPhotos);
        tabPhotos = [];
        tabCommentaires = [];
        tabCommentaireStr = "";
        tabPhotosStr = "";
        if (listPhotos.length != 0) {
            divListPhotos = listPhotos[0];
            photos = divListPhotos.getElementsByTagName("img");
            commentaires = divListPhotos.getElementsByClassName("inpurComment");
            for (let i = 0; i < photos.length; i++) {
                const element = photos[i];
                tabPhotos.push(element.getAttribute("src"));
            }
            for (let i = 0; i < commentaires.length; i++) {
                const element = commentaires[i];
                tabCommentaires.push(element);
            }
            tabPhotosStr = tabPhotos.join(' ');
            tabCommentaireStr = tabCommentaires.join('{');
        }
        console.table(tabCommentaires)
        addBD(idEquipF, idApp, idPieceF, tabPhotosStr, tabCommentaireStr);
    }
    //
    //UPDATE RDV
    this.updateRDV(idRDV);
    window.location.href = URLROOT + "/Home/index";
}

function addBD(idEquipF, idApp, idPieceF, tabPhoto, tabComments) {
    const URLROOT = $('#URLROOT').val();

    $.ajax({
        type: "POST",
        url: `${URLROOT}/public/json/insertEquipementPiece.php`,
        data: {
            idEq: idEquipF,
            idApp: idApp,
            idPiece: idPieceF,
            tabStr: tabPhoto,
            commentsStr: tabComments
        },
        dataType: "JSON",
        success: function (data) {
            if (data == "1") {
                console.log("OK BD")
            } else {
                console.log("KO BD")
            }
        }
    });
}

function updateRDV(idRDV) {
    const URLROOT = $('#URLROOT').val();

    $.ajax({
        type: "POST",
        url: `${URLROOT}/public/json/updateRDV.php`,
        data: {
            id: idRDV
        },
        dataType: "JSON",
        success: function (data) {
            if (data == "1") {
                console.log("OK BD")
            } else {
                console.log("KO BD")
            }
        }
    });
}

function updateStatutContact(statut, idContact, doc1, doc2, doc3) {
    const URLROOT = $('#URLROOT').val();

    $.ajax({
        type: "POST",
        url: `${URLROOT}/public/json/updateStatutContact.php`,
        data: {
            id: idContact,
            statut: statut,
            documents1: doc1,
            documents2: doc2,
            documents3: doc3,
        },
        dataType: "JSON",
        success: function (data) {
            if (data == "1") {
                console.log("OK BD")
            } else {
                console.log("KO BD")
            }
        }
    });
}

function updateCompteurApp(index, numero, photo, app, nom, prenom, tel, email) {
    const URLROOT = $('#URLROOT').val();

    $.ajax({
        type: "POST",
        url: `${URLROOT}/public/json/updateCompteurApp.php`,
        data: {
            index: index,
            numero: numero,
            photo: photo,
            idApp: app,
            nomCopro: nom,
            prenomCopro: prenom,
            telCopro: tel,
            emailCopro: email
        },
        dataType: "JSON",
        success: function (data) {
            if (data == "1") {
                console.log("OK BD")
            } else {
                console.log("KO BD")
            }
        }
    });
}

function numPhoto(id) {
    np = id;
}

function onClickAddPiece() {

    if (choixPiece === "Autres") {
        libellePiece = document.getElementById("libelleAutrePiece").value;
    } else {
        libellePiece = choixPiece;
    }
    if (libellePiece.length === 0) {
        alert("Veuillez choisir une pièce !")
    } else {
        nb = 0;
        var elements = document.querySelectorAll('[id^="piece"]');
        if (elements.length > 0) {
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].innerText.includes(libellePiece))
                    nb++;
            }
        }
        libellePiece = libellePiece + "-" + (nb + 1);
        addPiece(idPiece);
    }
    document.getElementById("libelleAutrePiece").value = "";
}

function onClickChoixPiece(choix) {
    choixPiece = choix.split('*')[1];
    idPiece = choix.split('*')[0];

    if (choixPiece === "Autres") {
        document.getElementById("autrePiece").style.display = "block";
    } else {
        document.getElementById("autrePiece").style.display = "none";
    }
}

function onClickChoixEquipement(id) {
    var choix = document.getElementById("choixEquipement" + id).value;
    equipement = choix;
    choixEquipement = choix.split('*')[1];
    idEquipement = choix.split('*')[0];
    if (choixEquipement === "Autres") {
        document.getElementById("autreEquipement" + id).style.display = "block";
    } else {
        document.getElementById("autreEquipement" + id).style.display = "none";
    }
}

function onClickChoixTypeFuite(choix, id) {

    if (choix === "Autres") {
        document.getElementById("autreTypeFuite" + id).style.display = "block";
        document.getElementById("divFuiteAlimentation" + id).style.display = "none";
        document.getElementById("divFuiteEvacuation" + id).style.display = "none";
    } else {
        document.getElementById("autreTypeFuite" + id).style.display = "none";
        if (choix === "Fuite d'alimentation") {
            document.getElementById("divFuiteAlimentation" + id).style.display = "block";
            document.getElementById("divFuiteEvacuation" + id).style.display = "none";

        } else {
            document.getElementById("divFuiteAlimentation" + id).style.display = "none";
            document.getElementById("divFuiteEvacuation" + id).style.display = "block";
        }
    }
}

function onClickChoixLibelleFuite(choix, type, id) {
    //var choix = document.getElementById("choixTypeFuite" + id).value;
    if (type === "Fuite alimentation") {
        if (choix.checked) {
            document.getElementById("autreFuiteAlimentation" + id).style.display = "block";
        } else {
            document.getElementById("autreFuiteAlimentation" + id).style.display = "none";
        }
    } else if (type === "Fuite évacuation") {
        if (choix.checked) {
            document.getElementById("autreFuiteEvacuation" + id).style.display = "block";
        } else {
            document.getElementById("autreFuiteEvacuation" + id).style.display = "none";
        }
    }
}

function onClickAddEquipement(id) {

    if (choixEquipement === "Autres") {
        libelleEquipement = document.getElementById("libelleAutreEquipement" + id).value;
    } else {
        libelleEquipement = choixEquipement;
    }
    if (libelleEquipement.length === 0) {
        alert("Veuillez choisir un équipement !")
    } else {
        nb = 0;
        var elements = document.getElementById('exampleAccordion' + id).querySelectorAll('[id^="equipement"]');
        if (elements.length > 0) {
            for (var i = 0; i < elements.length; i++) {
                if (elements[i].innerText.includes(libelleEquipement))
                    nb++;
            }
        }
        libelleEquipement = libelleEquipement + "-" + (nb + 1);
        addEquipement(id, indiceTabEquip);
        document.getElementById("libelleAutreEquipement" + id).value = "";
        tabEquipements[indiceTabEquip] = equipement + "_" + indiceTabEquip + "_" + id;
        // console.table(tabEquipements)
        indiceTabEquip++;
    }

}

function addPiece(idPiece) {
    iP += 1;
    let id = 1;
    const divEquipement = $('#exampleAccordion');
    const URLROOT = $('#URLROOT').val();
    $.ajax({
        type: "POST",
        url: `${URLROOT}/public/json/equipement.php?action=getEquipementByPiece`,
        data: {
            idPiece: idPiece
        },
        dataType: "JSON",
        success: function (data) {
            console.log(data);
            if (data === '0') {
                // alert("Erreur")
            } else {


                divEquipement.append(`
                <div class="card mt-2" id="piece${iP}">
                    <input type="text" hidden name="pieces[]" value="${libellePiece}">
                    <div class="card-header" id="exItem${iP}Header" style="background: lightgrey">
                        <div class="row">
                        
                            <h5 class="mb-0 col-md-11 col-9">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#exItem${iP}" aria-expanded="false" aria-controls="exItem${iP}"><i class="fa fa-house-damage text-warning" "> </i> <span class="classPiece">${libellePiece}</span></button>
                            </h5>
                            <div class="col-md-1 col-1">
                                <button class="btn btn-sm btn-danger mt-1" type="button" onclick="deletePiece(piece${iP},${idPiece},'${libellePiece}')"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <div id="exItem${iP}" class="collapse" aria-labelledby="exItem${iP}Header" data-parent="#exampleAccordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="container">
                                    <div class="accordion" id="exampleAccordion${iP}">
                                        <div class="card mt-2" id="plafond${iP}">
                                            <div class="card-header" id="enfantPlafond${iP}Header">
                                                <div class="row">
                                                <div class="col-md-1 col-1">
                                                        <div class="row d-print-inline mt-2">
                                                            <div class="col-md-1 col-1"> <input type="checkbox" name="plafondCoche${iP}" id="fuiteTrouve1${iP}" onclick="onClickFuiteTrouve(1,${iP})"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-12">
                                                        <h6 class="mb-0 ">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#enfantPlafond${iP}" aria-expanded="false" aria-controls="enfantPlafond${iP}"><span class="classEquipement"> Plafond </span></button>
                                                        </h6>
                                                    </div> 
                                                </div>
                                            </div>

                                            <div id="enfantPlafond${iP}" class="collapse" aria-labelledby="enfantPlafond${iP}Header" data-parent="#exampleAccordion${iP}">
                                                <div class="card-body listPhotos" id="divPhotoList${iP}">
                                                    <div id="divReparation1${iP}" hidden>
                                                        <div class="row d-print-inline" >
                                                            <div class="row col-md-12">
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 1-Nature du revêtement
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input checked type="checkbox" value="Peinture"
                                                                        id="naturePlafond${iP}_0" name="naturePlafond${iP}[]">
                                                                    <label class="font-weight-bold"> Peinture</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Lambris"
                                                                        id="naturePlafond${iP}_1" name="naturePlafond${iP}[]">
                                                                    <label class="font-weight-bold"> Lambris</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Toile de verre"
                                                                        id="naturePlafond${iP}_2" name="naturePlafond${iP}[]">
                                                                    <label class="font-weight-bold"> Toile de verre</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Dalle de polystyrène"
                                                                        id="naturePlafond${iP}_3" name="naturePlafond${iP}[]">
                                                                    <label class="font-weight-bold"> Dalle de polystyrène</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 2-Commentaire
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <Textarea name="commentairePlafond${iP}" id="commentairePlafond${iP}"
                                                                        class="form-control"></Textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-2" id="sol${iP}">
                                            <div class="card-header" id="enfantSol${iP}Header">
                                                <div class="row">
                                                <div class="col-md-1 col-1">
                                                        <div class="row d-print-inline mt-2">
                                                            <div class="col-md-1 col-1"> <input type="checkbox" name="solCoche${iP}" id="fuiteTrouve2${iP}" onclick="onClickFuiteTrouve(2,${iP})"> </div>
                                                        </div>
                                                    
                                                    </div>
                                                    <div class="col-md-5 col-12">
                                                        <h6 class="mb-0 ">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#enfantSol${iP}" aria-expanded="false" aria-controls="enfantSol${iP}"><span class="classEquipement"> Sol </span></button>
                                                        </h6>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div id="enfantSol${iP}" class="collapse" aria-labelledby="enfantSol${iP}Header" data-parent="#exampleAccordion${iP}">
                                                <div class="card-body listPhotos" id="divPhotoList${iP}">
                                                    <div id="divReparation2${iP}" hidden>
                                                        <div class="row d-print-inline" >
                                                            <div class="row col-md-12">
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 1-Nature du revêtement
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input checked type="checkbox" value="Parquet"
                                                                        id="natureSol${iP}_0" name="natureSol${iP}[]">
                                                                    <label class="font-weight-bold"> Parquet</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Carrelage"
                                                                        id="natureSol${iP}_1" name="natureSol${iP}[]">
                                                                    <label class="font-weight-bold"> Carrelage</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="PVC"
                                                                        id="natureSol${iP}_2" name="natureSol${iP}[]">
                                                                    <label class="font-weight-bold"> PVC</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Lino"
                                                                        id="natureSol${iP}_3" name="natureSol${iP}[]">
                                                                    <label class="font-weight-bold"> Lino</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Moquette"
                                                                        id="natureSol${iP}_4" name="natureSol${iP}[]">
                                                                    <label class="font-weight-bold"> Moquette</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 2-Commentaire
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <Textarea name="commentaireSol${iP}" id="commentaireSol${iP}"
                                                                        class="form-control"></Textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-2" id="mur${iP}">
                                            <div class="card-header" id="enfantMur${iP}Header">
                                                <div class="row">
                                                <div class="col-md-1 col-1">
                                                        <div class="row d-print-inline mt-2">
                                                            <div class="col-md-1 col-1"> <input type="checkbox" name="murCoche${iP}" id="fuiteTrouve3${iP}" onclick="onClickFuiteTrouve(3,${iP})"> </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5 col-12">
                                                        <h6 class="mb-0 ">
                                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#enfantMur${iP}" aria-expanded="false" aria-controls="enfantMur${iP}"><span class="classEquipement"> Mur </span></button>
                                                        </h6>
                                                    </div> 
                                                </div>
                                            </div>
                                            <div id="enfantMur${iP}" class="collapse" aria-labelledby="enfantMur${iP}Header" data-parent="#exampleAccordion${iP}">
                                                <div class="card-body listPhotos" id="divPhotoList${iP}">
                                                    <div id="divReparation3${iP}" hidden>
                                                        <div class="row d-print-inline" >
                                                            <div class="row col-md-12">
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 1-Nature du revêtement
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input checked type="checkbox" value="Parquet"
                                                                        id="natureMur${iP}_0" name="natureMur${iP}[]">
                                                                    <label class="font-weight-bold"> Parquet</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="Carrelage"
                                                                        id="natureMur${iP}_1" name="natureMur${iP}[]">
                                                                    <label class="font-weight-bold"> Carrelage</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <input type="checkbox" value="PVC"
                                                                        id="natureMur${iP}_2" name="natureMur${iP}[]">
                                                                    <label class="font-weight-bold"> PVC</label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <label class="text-danger"> 2-Commentaire
                                                                        <label>
                                                                </div>
                                                                <div class="col-12">
                                                                    <Textarea name="commentaireMur${iP}" id="commentaireMur${iP}"
                                                                        class="form-control"></Textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                     </div>
                                </div>
                             </div>
                        </div>
                    </div>
                </div> 
            `);



            }
        }
    });
}

function addEquipement(id, indiceTab) {
    iE += 1;
    const divEquipement = $('#exampleAccordion' + id);
    divEquipement.append(`
                <div class="card mt-2" id="equipement${iE}">
                    <div class="card-header" id="exItemEnfant${iE}Header">
                        <div class="row">
                            <div class="col-md-5 col-12">
                                <h6 class="mb-0 ">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#exItemEnfant${iE}" aria-expanded="false" aria-controls="exItemEnfant${iE}"><i class="fa fa-circle text-success"> </i><span class="classEquipement"> ${libelleEquipement} </span></button>
                                </h6>
                            </div> 
                            <div class="col-md-4 col-12">
                                <div class="row d-print-inline mt-2">
                                    <div class="col-md-9 col-8 "> Fuite trouvée</div>
                                    <div class="col-md-3 col-4"> <input type="checkbox" name="fuiteTrouve${iE}" id="fuiteTrouve${iE}" onclick="onClickFuiteTrouve(${iE})"> </div>
                                </div>
                            
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="row d-print-inline">
                                    <div class="col-md-4 col-4">
                                        <button type="button" onclick="startMediaPhoto()" id="prendrePhotoFuite${iE}" data-toggle="modal" onclick="numPhoto(${iE})" data-target="#exampleModal" class="btn btn-sm btn-success mt-1 ml-1"><i class="fa fa-camera"></i></button>
                                    </div>
                                    <div class="col-md-4 col-4">
                                        <button type="button" data-toggle="modal" onclick="numPhoto(${iE})" data-target="#exampleModal1" class="btn btn-sm btn-success mt-1 ml-1"><i class="fa fa-video-camera"></i></button>
                                    </div>
                                    <div class="col-md-4 col-4">
                                        <button class="btn btn-sm btn-danger mt-1" type="button" onclick="deleteEquipement(equipement${iE},${id},${indiceTab},'${libelleEquipement}')"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="exItemEnfant${iE}" class="collapse" aria-labelledby="exItemEnfant${iE}Header" data-parent="#exampleAccordion${id}">
                        <div class="card-body listPhotos" id="divPhotoList${iE}">
                                        <div id="divReparation${iE}" hidden>
                                                 <div class="row d-print-inline" >
                                                    <div class="row col-md-12">
                                                        <div class="col-md-12 ">
                                                            <div class="row col-md-12 col-12">
                                                                <h4 class="">S'agit-il d'une fuite ?</h4>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="mail_box col-md-12 col-12 h6 font-weight-bold text-dark">
                                                                        <select class="form-control" onchange="onClickChoixTypeFuite(this.value,${iE})" id="choixTypeFuite${iE}">
                                                                            <option value="">---Veuillez choisir---</option>
                                                                            <option value="Fuite d'alimentation">Fuite d'alimentation</option>
                                                                            <option value="Fuite d'évacuation">Fuite d'évacuation</option>
                                                                            <option value="Autres">Autres</option>
                                                                        </select>
                                                                </div>
                                                                <div class="col-sm-12 col-12" id="autreTypeFuite${iE}" style="display: none">
                                                                    <input type="text" class="mail_text_1" placeholder="Saisir le type de fuite" name="text" id="">
                                                                 </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-12" id="divFuiteAlimentation${iE}" style="display: none">
                                                            <div class="row col-md-12 col-12">
                                                                <h4 class="">Libellé de la fuite d'alimentation</h4>
                                                            </div>
                                                            <div class="row ">
                                                                <div class="col-md-12 col-12 text-dark">
                                                                        <div class="form-row">
                                                                                <div class="form-group col-md-4 col-12">
                                                                                    <label>
                                                                                        Canalisation accessible<input type="checkbox">
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-group col-md-4 col-12">
                                                                                    <label>
                                                                                            Canalisation non accessible<input type="checkbox">
                                                                                        </label>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label>
                                                                                    Robinet Goutte à Goutte<input type="checkbox">
                                                                                        </label>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label>
                                                                                    Robinet filet d'eau<input type="checkbox">
                                                                                        </label>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label>
                                                                                        Chasse d'eau<input type="checkbox">
                                                                                        </label>
                                                                                </div>
                                                                                <div class="form-group col-md-4">
                                                                                    <label>
                                                                                        Autres<input type="checkbox" value="Autres" onclick="onClickChoixLibelleFuite(this,'Fuite alimentation',${iE})" id="choixLibelleFuiteAlimentation${iE}">
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-group col-md-12" id="autreFuiteAlimentation${iE}" style="display: none">
                                                                                            <label>Saisir le libellé de fuite</label>
                                                                                            <input type="text" class="form-control"  name="text" id="autreLibelleFuiteAlimentation${iE}">
                                                                                </div>
                                                                        </div>
                                                                        
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 col-12"  id="divFuiteEvacuation${iE}" style="display: none">
                                                            <div class="row col-md-12 col-12">
                                                                <h4 class="">libellé de la fuite</h4>
                                                            </div>
                                                          
                                                            <div class="row ">
                                                                <div class="col-md-12 col-12 text-dark">
                                                                       <div class="form-row">
                                                                            <div class="form-group col-md-4 col-12">
                                                                                <label>
                                                                                     Canalisation accessible<input type="checkbox">
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-group col-md-4 col-12">
                                                                                <label>
                                                                                        Canalisation non accessible<input type="checkbox">
                                                                                    </label>
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>
                                                                                        Equipement d'étanchéité<input type="checkbox">
                                                                                    </label>
                                                                            </div>
                                                                            <div class="form-group col-md-4">
                                                                                <label>
                                                                                     Autres<input type="checkbox" value="Autres" onclick="onClickChoixLibelleFuite(this,'Fuite évacuation',${iE})" id="choixLibelleFuiteEvacuation${iE}">
                                                                                </label>
                                                                            </div>
                                                                            <div class="form-group col-md-8" id="autreFuiteEvacuation${iE}" style="display: none">
                                                                                         <label>Saisir le libellé de fuite</label>
                                                                                        <input type="text" class="form-control"  name="text" id="autreLibelleFuiteEvacuation${iE}">
                                                                            </div>
                                                                       </div>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row d-print-inline mt-1" >
                                                    <div class="col-md-12 col-12">
                                                        <div class="row col-md-12 col-12">
                                                            <h4 class="">La réparation est-elle ?</h4>
                                                        </div>
                                                            <div class="row col-md-8 justify-content-center">
                                                                <div class="mail_box col-md-12 col-12 h6 text-dark">
                                                                    Gratuite<input type="radio" class="mt-0 mr-4" name="reparation" id="reparation1" value="Gratuite">
                                                                    Payante<input type="radio" class="mt-0" name="reparation" id="reparation2"  value="Payante">
                                                                </div>
                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                        </div>
                    </div>
                </div>
            `);
}

function addPhoto() {
    if (srcPhoto !== "") {

        if (np === "doc-1" || np === "doc-2" || np === "doc-3") {
            iPdoc += 1;
            id = np.split('-')[1];
            const divEquipement = $('#divDocument' + id);
            divEquipement.append(`
                        <div class="row form-card col-md-10 mt-1" id="divPhotoDoc${iPdoc}">
                            <div class="row ">
                                <div class=""><img id="photoDoc${id}-${iPdoc}" src="${srcPhoto}"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 col-7"></div>
                                <div class="col-md-4 col-4">
                                    <button type="button" onclick="deletePhotoDocument(divPhotoDoc${iPdoc},${id},${iPdoc})" class="btn btn-danger btn-sm mt-1"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    
                    `);
            if (id === "1") {
                documentsCni.push(srcPhoto);
            }
            if (id === "2") {
                documentsCA.push(srcPhoto);

            }
            if (id === "3") {
                documentsContrat.push(srcPhoto);
            }
        } else {
            if (np === "photoCompteur") {
                const divEquipement = $('#divPhotoCompteur');
                divEquipement.empty();
                divEquipement.append(`
                            <div class="col-md-1">
                            </div>
                            <div class="form-card col-md-10">
                                <div class="about_img"><img id="photoCompteurCapture" src="${srcPhoto}"></div>
                            </div>
                            <div class="col-md-1">
                            </div>
                        `);
                photoCompteur = "compteur+" + srcPhoto;
            } else {
                iPh += 1;
                const divEquipement = $('#divPhotoList' + np);
                commentaire = document.getElementById("commentaire").value;
                divEquipement.append(`
                        <div class="row" id="divPhoto${iPh}">
                            <div class="form-card" >
                                <div class="row col-sm-12">
                                    <h5 class="font-weight-bold">PHOTO</h2>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 mt-2">
                                        <div class="about_img"><img id="photo${iPh}" src="${srcPhoto}"></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <input hidden type="text" class="inputCmtClass" value = "${commentaire}" id="inputComment${iPh}" >
                                        <textarea class="massage-bt" required onblur="onBlurComment(this,${iPh})" placeholder="Commentaire" id="commentaire${iPh}" name="commentaire${iPh}"> ${commentaire} </textarea>   
                                        <button type="button" onclick="deletePhoto(divPhoto${iPh},${np})" class="btn btn-danger btn-sm">Supprimer</button>
                                    </div>               
                                </div>
                            </div>
                        </div>
                        `);
            }

        }
        srcPhoto = "";
        document.getElementById("commentaire").value = "";
    }

    $('#exampleModal').modal('toggle');
    document.getElementById("photo").setAttribute("src", srcPhotoDefaut);
}

function addVideo() {

    if (srcVideo !== "") {
        iPh += 1;
        const divEquipement = $('#divPhotoList' + np);
        commentaire = document.getElementById("commentaire").value;
        divEquipement.append(`
                        <div class="row" id="divPhoto${iPh}">
                            <div class="form-card" >
                                <div class="row col-sm-12">
                                    <h5 class="font-weight-bold">VIDEO</h2>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4 mt-2">
                                        <div class="about_img"><video width="172" height="129" id="photo${iPh}" src="${srcVideo}" muted controls></video></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <input hidden type="text" class="inputCmtClass" value = "${commentaire}" id="inputComment${iPh}" >
                                        <textarea class="massage-bt" required onblur="onBlurComment(this,${iPh})" placeholder="Commentaire" id="commentaire${iPh}" name="commentaire${iPh}"> ${commentaire} </textarea>   
                                        <button type="button" onclick="deletePhoto(divPhoto${iPh},${np})" class="btn btn-danger btn-sm">Supprimer</button>
                                    </div>               
                                </div>
                            </div>
                        </div>
                        `);
        srcVideo = "";
        document.getElementById("commentaire").value = "";
    }

    $('#exampleModal1').modal('toggle');
    document.getElementById("recorded").setAttribute("src", null);
    document.getElementById("play").setAttribute("disabled", null);
    document.getElementById("download").setAttribute("disabled", null);
}
function onBlurComment(elt, id) {
    commentaire = elt.value;
    document.getElementById("inputComment" + id).value = commentaire;
}

function deletePiece(id, idPiece, libelleValue) {

    const divPiece = document.getElementById('exampleAccordion');
    divPiece.removeChild(id);
    tabEquipements = tabEquipements.filter(item => item.split("*")[3].split("_")[0] != idPiece);
    console.table(tabEquipements)
    // tabEquipements = tabEquipements.filter(item => item.split("*")[3]);
    nb = 0;
    var elements = document.querySelectorAll('[id^="piece"]');
    if (elements.length > 0) {
        libelle1 = libelleValue.split("-")[0];
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].innerText.includes(libelle1)) {
                nb++;
                var obj = document.getElementById(elements[i].getAttribute('id')).getElementsByClassName('classPiece')[0];
                obj.innerText = libelle1 + '-' + nb;

            }
        }
    }

}

function deleteEquipement(id, idP, idTab, libelle) {
    const divEquipement = document.getElementById('exampleAccordion' + idP);
    divEquipement.removeChild(id);
    tabEquipements = tabEquipements.filter(item => item.split("_")[1] != idTab);
    //
    nb = 0;
    var elements = document.getElementById('exampleAccordion' + idP).querySelectorAll('[id^="equipement"]');
    if (elements.length > 0) {
        libelle = libelle.split("-")[0];
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].innerText.includes(libelle)) {
                nb++;
                var obj = document.getElementById(elements[i].getAttribute('id')).getElementsByClassName('classEquipement')[0];
                obj.innerText = libelle + '-' + nb;

            }
        }
    }

}

function deletePhoto(id, idP) {

    const divPhoto = document.getElementById('divPhotoList' + idP);
    divPhoto.removeChild(id);
}

function deletePhotoDocument(idPhoto, idDiv, numPhoto) {
    val = document.getElementById('photoDoc' + idDiv + "-" + numPhoto).getAttribute("src");
    if (idDiv === 1) {
        const index = documentsCni.indexOf(val);
        if (index > -1) {
            documentsCni.splice(index, 1);
        }
    }
    if (idDiv === 2) {
        const index = documentsCA.indexOf(val);
        if (index > -1) {
            documentsCA.splice(index, 1);
        }
    }
    if (idDiv === 3) {
        const index = documentsContrat.indexOf(val);
        if (index > -1) {
            documentsContrat.splice(index, 1);
        }
    }
    const divEquipement = document.getElementById('divDocument' + idDiv);
    divEquipement.removeChild(idPhoto);
}

function onClickTypeCopro() {
    if (document.getElementById('estCopro1').checked) {
        $statut = 'Copropriétaire';
        document.getElementById("divCoproprietaire").style.display = "none";
        document.getElementById("divDocument").style.display = "block";
        document.getElementById("lab1").style.display = "block";
        document.getElementById("lab2").style.display = "none";
        document.getElementById("divCopro").style.display = "block";
        document.getElementById("divNonCopro").style.display = "none";
    } else {
        $statut = 'Occupant';
        document.getElementById("divCoproprietaire").style.display = "block";
        document.getElementById("divCopro").style.display = "none";
        document.getElementById("divNonCopro").style.display = "block";
        document.getElementById("divDocument").style.display = "none";
    }
}

function onClickOcc() {
    if (document.getElementById('typeCopro1').checked) {
        statut = "Copropriétaire Occupant";
    }
    if (document.getElementById('typeCopro2').checked) {
        statut = "Copropriétaire Non Occupant";
    }
}


function onClickChoixTypeOccupation(choix) {
    if (choix === "Autres") {
        document.getElementById("autreTypeOccupation").style.display = "block";
    } else {
        document.getElementById("autreTypeOccupation").style.display = "none";
        statut = choix;
    }
    if (choix === "Locataire") {
        document.getElementById("divDocument").style.display = "block";
        document.getElementById("lab1").style.display = "none";
        document.getElementById("lab2").style.display = "block";
    } else {
        document.getElementById("divDocument").style.display = "none";

    }
}

function onBlurStatut() {
    statut = document.getElementById("autreTypeOccupation").value;
}

function onClickFuiteTrouve(i, id) {
    if (document.getElementById('fuiteTrouve' + i + id).checked) {
        document.getElementById("divReparation" + i + id).removeAttribute("hidden");

    } else {
        document.getElementById("divReparation" + i + id).setAttribute("hidden", "hidden");
    }
}