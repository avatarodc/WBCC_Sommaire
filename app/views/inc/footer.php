</div>
<!-- Footer -->
<footer class="sticky-footer bg-grey">
    <div class="copyright text-center my-auto mt-4 p-3">
        <span>Copyright 2021 - WBCC ASSISTANCE IT &copy; . All rights reserved.</span>
    </div>
</footer>
<!-- End of Footer -->

</div>
</div>
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded " href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Module inactif</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Fonctionnalité Non Disponible !</div>

        </div>
    </div>
</div>



<!-- ESPOIR -->
<?php
$response = true;
if (isset($_GET['url'])) {
    $tab = explode('/', $_GET['url']);
    if (isset($tab[0]) && $tab[0] == 'Ticket' || ($tab[0] == 'Gestionnaire' && isset($tab[1]) && $tab[1] == 'prdvs')) {
        $response = false;
    }
}
if ($response == true) {
?>
    <script src="<?= URLROOT ?>/public/assets/vendor/jquery/jquery.min.js"></script>
<?php
}
?>
<!-- ESPOIR -->
<script src="<?= URLROOT ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= URLROOT ?>/public/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= URLROOT ?>/public/assets/js/sb-admin-2.min.js"></script>
<script src="<?= URLROOT ?>/public/assets/js/scriptNA.js"></script>
<!-- Page level plugins -->
<!-- Page level plugins -->
<script src="<?= URLROOT ?>/public/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= URLROOT ?>/public/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="<?= URLROOT ?>/public/assets/js/demo/datatables-demo.js"></script>
<script src="<?= URLROOT ?>/public/assets/js/tiny.js" referrerpolicy="origin"></script>

<!-- Espoir datepicker -->
<script type="text/javascript" src="<?= URLROOT ?>/assets/ticket/vendor/libs/select2/select2.js"></script>
<script type="text/javascript" src="<?= URLROOT ?>/assets/vendor/datepicker/jquery.timepicker.js"></script>
<script type="text/javascript"
    src="<?= URLROOT ?>/assets/vendor/datepicker/documentation-assets/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= URLROOT ?>/assets/vendor/datepair/datepair.js"></script>
<script type="text/javascript" src="<?= URLROOT ?>/assets/vendor/datepair/jquery.datepair.js"></script>
<!-- fIN Espoir datepicker -->

<!-- NABILA à ajouter début  -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<!-- NABILA à ajouter fin  -->

<!-- agenda -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<script>
    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: 'textarea#noteText',
        height: 500,
        content_css: '<?= URLROOT ?>/public/assets/css/skin.css',
        browser_spellcheck: true,
        language: "fr_FR"
    });

    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: 'textarea#emailText',
        height: 800,
        content_css: '<?= URLROOT ?>/public/assets/css/skin.css',
        browser_spellcheck: false,
        language: "fr_FR"
    });

    tinymce.init({
        menubar: false,
        statusbar: false,
        selector: 'textarea#emailTextLA',
        height: 800,
        content_css: '<?= URLROOT ?>/public/assets/css/skin.css',
        browser_spellcheck: false,
        language: "fr_FR"
    });
</script>

<script src="<?= URLROOT ?>/public/assets/js/projet/projet.js"></script>
</body>
<script>
    function copy() {
        window.location.href = "tel:+33980084484";
    }

    function callContact(tel) {
        let value = tel != null && tel != "" ? tel.replaceAll(" ", "") : "";
        value = (value.length >= 10 ? ("33" + value.substr(value.length - 9, 9)) : "33" + value);
        console.log(value);
        let role = "<?= isset($_SESSION['connectedUser']->role) ?  $_SESSION['connectedUser']->role : "" ?>";
        // alert("tel:+" + value);
        if (role == "3" || role == "25") {
            navigator.clipboard.writeText(value);
            $("#modalAlertCopy").modal("show");
            setTimeout(() => {
                $("#modalAlertCopy").modal("hide");
            }, 1000)
        } else {
            window.location.href = "tel:+" + value;
        }
    }

    function goBack() {
        document.referrer;
    }

    (function($) {
        $.fn.uploader = function(options) {
            var settings = $.extend({
                    MessageAreaText: "",
                    MessageAreaTextWithFiles: "",
                    DefaultErrorMessage: "Impossible d'ouvrir ce fichier.",
                    BadTypeErrorMessage: "Ce type de fichier n'est pas accepté.",
                    acceptedFileTypes: [
                        "pdf",
                        "doc",
                        "docx",
                        "xls",
                        "xlsx",
                        "png",
                        "jpg",
                        "jpeg",
                        "mp4"
                    ]
                },
                options
            );

            var uploadId = 1;
            //update the messaging
            $(".file-uploader__message-area p").text(
                options.MessageAreaText || settings.MessageAreaText
            );

            //create and add the file list and the hidden input list
            var fileList = $('<ul class="file-list"></ul>');
            var hiddenInputs = $('<div class="hidden-inputs hidden"></div>');
            $(".file-uploader__message-area").after(fileList);
            $(".file-list").after(hiddenInputs);

            //when choosing a file, add the name to the list and copy the file input into the hidden inputs
            $(".file-chooser__input").on("change", function() {
                var files = document.querySelector(".file-chooser__input").files;

                for (var i = 0; i < files.length; i++) {
                    console.log(files[i]);

                    var file = files[i];
                    var fileName = file.name.match(/([^\\\/]+)$/)[0];

                    //clear any error condition
                    $(".file-chooser").removeClass("error");
                    $(".error-message").remove();

                    //validate the file
                    var check = checkFile(fileName);
                    if (check === "valid") {
                        // move the 'real' one to hidden list
                        $(".hidden-inputs").append($(".file-chooser__input"));

                        //insert a clone after the hiddens (copy the event handlers too)
                        $(".file-chooser").append(
                            $(".file-chooser__input").clone({
                                withDataAndEvents: true
                            })
                        );

                        //add the name and a remove button to the file-list
                        $(".file-list").append(
                            `<li style="display: none;">
                        <div class="row">
                            <div class="col-md-5">
                                 <input type="text" required name="nom${uploadId}" class="form-control" placeholder="Nom du fichier">
                            </div>
                            <div class="col-md-7">
                                    <span class="file-list__name">${fileName}</span>
                                    <button class="removal-button" data-uploadid="${uploadId}"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                        </li>
                        `
                        );
                        $(".file-list").find("li:last").show(800);

                        //removal button handler
                        $(".removal-button").on("click", function(e) {
                            e.preventDefault();

                            //remove the corresponding hidden input
                            $(
                                '.hidden-inputs input[data-uploadid="' +
                                $(this).data("uploadid") +
                                '"]'
                            ).remove();

                            //remove the name from file-list that corresponds to the button clicked
                            $(this)
                                .parent()
                                .hide("puff")
                                .delay(10)
                                .queue(function() {
                                    $(this).remove();
                                });

                            //if the list is now empty, change the text back
                            if ($(".file-list li").length === 0) {
                                $(".file-uploader__message-area").text(
                                    options.MessageAreaText || settings.MessageAreaText
                                );
                            }
                        });

                        //so the event handler works on the new "real" one
                        $(".hidden-inputs .file-chooser__input")
                            .removeClass("file-chooser__input")
                            .attr("data-uploadId", uploadId);

                        //update the message area
                        $(".file-uploader__message-area").text(
                            options.MessageAreaTextWithFiles ||
                            settings.MessageAreaTextWithFiles
                        );

                        uploadId++;
                    } else {
                        //indicate that the file is not ok
                        $(".file-chooser").addClass("error");
                        var errorText =
                            options.DefaultErrorMessage || settings.DefaultErrorMessage;

                        if (check === "badFileName") {
                            errorText =
                                options.BadTypeErrorMessage || settings.BadTypeErrorMessage;
                        }

                        $(".file-chooser__input").after(
                            '<p class="error-message">' + errorText + "</p>"
                        );
                    }
                }
            });

            var checkFile = function(fileName) {
                var accepted = "invalid",
                    acceptedFileTypes =
                    this.acceptedFileTypes || settings.acceptedFileTypes,
                    regex;

                for (var i = 0; i < acceptedFileTypes.length; i++) {
                    regex = new RegExp("\\." + acceptedFileTypes[i] + "$", "i");

                    if (regex.test(fileName)) {
                        accepted = "valid";
                        break;
                    } else {
                        accepted = "badFileName";
                    }
                }

                return accepted;
            };
        };
    })($);

    // (function($) {
    //     $.fn.uploader = function(options) {
    //         var settings = $.extend({
    //                 MessageAreaText: "",
    //                 MessageAreaTextWithFiles: "",
    //                 DefaultErrorMessage: "Impossible d'ouvrir ce fichier.",
    //                 BadTypeErrorMessage: "Ce type de fichier n'est pas accepté.",
    //                 acceptedFileTypes: [
    //                     "pdf",
    //                     "doc",
    //                     "docx",
    //                     "xls",
    //                     "xlsx",
    //                     "png",
    //                     "jpg",
    //                     "jpeg"
    //                 ]
    //             },
    //             options
    //         );

    //         var uploadId = 1;
    //         //update the messaging
    //         $(".file-uploader__message-area p").text(
    //             options.MessageAreaText || settings.MessageAreaText
    //         );

    //         //create and add the file list and the hidden input list
    //         var fileList = $('<ul class="file-list"></ul>');
    //         var hiddenInputs = $('<div class="hidden-inputs hidden"></div>');
    //         $(".file-uploader__message-area").after(fileList);
    //         $(".file-list").after(hiddenInputs);

    //         //when choosing a file, add the name to the list and copy the file input into the hidden inputs
    //         $(".file-chooser__input").on("change", function() {
    //             var files = document.querySelector(".file-chooser__input").files;

    //             for (var i = 0; i < files.length; i++) {
    //                 console.log(files[i]);

    //                 var file = files[i];
    //                 var fileName = file.name.match(/([^\\\/]+)$/)[0];

    //                 //clear any error condition
    //                 $(".file-chooser").removeClass("error");
    //                 $(".error-message").remove();

    //                 //validate the file
    //                 var check = checkFile(fileName);
    //                 if (check === "valid") {
    //                     // move the 'real' one to hidden list
    //                     $(".hidden-inputs").append($(".file-chooser__input"));

    //                     //insert a clone after the hiddens (copy the event handlers too)
    //                     $(".file-chooser").append(
    //                         $(".file-chooser__input").clone({
    //                             withDataAndEvents: true
    //                         })
    //                     );

    //                     /*    <div class="col-md-5">
    //                                 <input type="text" required name="nom${uploadId}" class="form-control" placeholder="Nom du fichier">
    //                            </div> */

    //                     //add the name and a remove button to the file-list
    //                     $(".file-list").append(
    //                         `<li style="display: none;">
    //                     <div class="row">
    //                     <div class="col-md-5">
    //                             <input type="text" required name="nom${uploadId}" class="form-control" placeholder="Nom du fichier">
    //                        </div>
    //                         <div class="col-md-7">
    //                                 <span class="file-list__name">${fileName}</span>
    //                                 <button class="removal-button removal-button2" data-uploadid="${uploadId}"><i class="fas fa-trash"></i></button>
    //                         </div>
    //                     </div>
    //                     </li>
    //                     `
    //                     );
    //                     $(".file-list").find("li:last").show(800);

    //                     //removal button handler
    //                     $(".removal-button").on("click", function(e) {
    //                         e.preventDefault();

    //                         //remove the corresponding hidden input
    //                         $(
    //                             '.hidden-inputs input[data-uploadid="' +
    //                             $(this).data("uploadid") +
    //                             '"]'
    //                         ).remove();

    //                         //remove the name from file-list that corresponds to the button clicked
    //                         $(this)
    //                             .parent().parent().parent()
    //                             .hide("puff")
    //                             .delay(10)
    //                             .queue(function() {
    //                                 $(this).remove();
    //                             });

    //                         //if the list is now empty, change the text back
    //                         if ($(".file-list li").length === 0) {
    //                             $(".file-uploader__message-area").text(
    //                                 options.MessageAreaText || settings.MessageAreaText
    //                             );
    //                         }
    //                     });

    //                     //so the event handler works on the new "real" one
    //                     $(".hidden-inputs .file-chooser__input")
    //                         .removeClass("file-chooser__input")
    //                         .attr("data-uploadId", uploadId);

    //                     //update the message area
    //                     $(".file-uploader__message-area").text(
    //                         options.MessageAreaTextWithFiles ||
    //                         settings.MessageAreaTextWithFiles
    //                     );

    //                     uploadId++;

    //                     $(".file-chooser__input").after(
    //                         $(".file-chooser__input").val("")
    //                     );
    //                 } else {
    //                     //indicate that the file is not ok
    //                     $(".file-chooser").addClass("error");
    //                     var errorText =
    //                         options.DefaultErrorMessage || settings.DefaultErrorMessage;

    //                     if (check === "badFileName") {
    //                         errorText =
    //                             options.BadTypeErrorMessage || settings.BadTypeErrorMessage;
    //                     }

    //                     $(".file-chooser__input").after(
    //                         '<p class="error-message">' + errorText + "</p>"
    //                     );
    //                 }
    //             }
    //         });

    //         var checkFile = function(fileName) {
    //             var accepted = "invalid",
    //                 acceptedFileTypes =
    //                 this.acceptedFileTypes || settings.acceptedFileTypes,
    //                 regex;

    //             for (var i = 0; i < acceptedFileTypes.length; i++) {
    //                 regex = new RegExp("\\." + acceptedFileTypes[i] + "$", "i");

    //                 if (regex.test(fileName)) {
    //                     accepted = "valid";
    //                     break;
    //                 } else {
    //                     accepted = "badFileName";
    //                 }
    //             }

    //             return accepted;
    //         };
    //     };
    // })($);

    //init
    $(document).ready(function() {
        $(".number-input").keyup(function(e) {
            if ($(this).val().length >= 11)
                $(".call-button").addClass("show");
            if (e.which == 8)
                $(".call-button").removeClass("show");
        })

        $(".number-input").keypress(function(e) {
            if ($(".number-input").val() == 0) {
                $(".number-input").val('');
                $(".number-input").focus();
            }
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });


        $("[data-number]").on('click', function() {
            if ($(".number-input").val().length < 11) {
                var phoneNumber = $(".number-input").val() + $(this).data("number");
                $(".number-input").val(phoneNumber);
            }
            if ($(".number-input").val().length == 11)
                $(".call-button").addClass("show");
        });

        $(".delete").on('click', function() {
            var phoneNumber = $(".number-input").val().slice(0, -1);
            $(".number-input").val("");
            $(".number-input").val(phoneNumber);
            $(".call-button").removeClass("show");
        });
        $('#mySearchText').on('keyup', function() {
            table.search($('#mySearchText').val()).draw();
        });

        $(".fileUploader").uploader({
            MessageAreaText: ""
        });

    });

    $(document).ready(function() {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function() {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function() {
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(".submit").click(function() {
            return false;
        })

    });
</script>

</html>