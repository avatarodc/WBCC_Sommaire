// Gestionnaire de documents pour les sections
const DocumentHandler = (function () {
    // Variables d'état
    let state = {
        selectedFile: null,
        selectedFileType: null, // 'local' ou 'RYM'
        activeSectionId: null
    };

    // Initialisation
    function init() {
        bindEvents();
    }

    // Liaison des événements
    function bindEvents() {
        // Gestionnaire pour le fichier local
        $('#localFileInput').on('change', handleLocalFileSelection);

        // Gestionnaire pour le changement d'onglet
        $('#ryuFileTab').on('shown.bs.tab', loadRyuFiles);

        // Gestionnaire pour la validation
        $('#validateFileSelection').on('click', handleFileValidation);
    }

    // Ouvre le modal de sélection de fichier
    function openFileModal(sectionId) {
        state.activeSectionId = sectionId;
        resetSelection();
        $('#fileSelectionModal').modal('show');
    }

    // Réinitialise la sélection
    function resetSelection() {
        state.selectedFile = null;
        state.selectedFileType = null;
        $('#validateFileSelection').prop('disabled', true);
        $('#localFileInput').val('');
        $('#ryuFileResults').empty();
        $('#ryuLoadingSpinner').addClass('d-none');
        $('#ryuEmptyState').addClass('d-none');
    }

    // Charger les fichiers RYM
    function loadRyuFiles() {
        $('#ryuLoadingSpinner').removeClass('d-none');
        $('#ryuEmptyState').addClass('d-none');
        $('#ryuFileResults').empty();

        $.ajax({
            url: CONFIG.routes.sectionDocument.getAllDocuments,
            method: 'GET',
            success: function (response) {
                $('#ryuLoadingSpinner').addClass('d-none');

                if (!response.documents || response.documents.length === 0) {
                    $('#ryuEmptyState').removeClass('d-none');
                    return;
                }

                displayDocuments(response.documents);
            },
            error: function () {
                $('#ryuLoadingSpinner').addClass('d-none');
                $('#ryuEmptyState').removeClass('d-none')
                    .html('<div class="text-danger">Erreur lors du chargement des documents</div>');
            }
        });
    }

    // Afficher les documents dans le tableau
    function displayDocuments(documents) {
        const html = documents.map(doc => `
            <tr>
                <td>${doc.titre}</td>
                <td><span class="badge badge-secondary">${doc.type.toUpperCase()}</span></td>
                <td>${new Date(doc.dateCreation).toLocaleDateString()}</td>
                <td>${doc.auteur || '-'}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary select-rym-file" 
                            data-id="${doc.id}" 
                            data-name="${doc.titre}">
                        <i class="fas fa-check mr-1"></i>Sélectionner
                    </button>
                </td>
            </tr>
        `).join('');

        $('#ryuFileResults').html(html);

        // Ajouter les gestionnaires d'événements pour la sélection
        $('.select-rym-file').on('click', function (e) {
            e.preventDefault();
            const button = $(this);
            selectRYMFile(button.data('id'), button.data('name'));

            // Mise à jour visuelle
            $('.select-rym-file').removeClass('btn-primary').addClass('btn-outline-primary');
            button.removeClass('btn-outline-primary').addClass('btn-primary');

            $('tr').removeClass('table-active');
            button.closest('tr').addClass('table-active');
        });
    }

    // Gestion de la sélection de fichier local
    function handleLocalFileSelection(e) {
        const file = e.target.files[0];
        if (file) {
            state.selectedFile = file;
            state.selectedFileType = 'local';
            $('#validateFileSelection').prop('disabled', false);
        } else {
            state.selectedFile = null;
            state.selectedFileType = null;
            $('#validateFileSelection').prop('disabled', true);
        }
    }

    // Sélection d'un fichier RYM
    function selectRYMFile(fileId, fileName) {
        state.selectedFile = { id: fileId, name: fileName };
        state.selectedFileType = 'RYM';
        $('#validateFileSelection').prop('disabled', false);
    }

    // Validation de la sélection
    async function handleFileValidation() {
        if (!state.selectedFile || !state.selectedFileType || !state.activeSectionId) {
            showError('Veuillez sélectionner un fichier');
            return;
        }

        // Désactiver le bouton pendant le traitement
        $('#validateFileSelection')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Traitement en cours...');

        try {
            let result;
            if (state.selectedFileType === 'local') {
                result = await uploadLocalFile();
            } else {
                result = await linkRYMFile();
            }

            if (result.success) {
                // Fermer le modal
                $('#fileSelectionModal').modal('hide');
                showSuccess(result.message);

                // Rafraîchir la liste des documents si nécessaire
                // Vous pouvez ajouter ici une fonction pour mettre à jour l'affichage
                reloadSectionDocuments(state.activeSectionId);
            } else {
                showError(result.error || 'Une erreur est survenue');
            }
        } catch (error) {
            showError('Une erreur est survenue lors du traitement');
            console.error('Error:', error);
        } finally {
            // Réactiver le bouton
            $('#validateFileSelection')
                .prop('disabled', false)
                .html('Valider la sélection');
        }
    }

    // Upload d'un fichier local
    async function uploadLocalFile() {
        const formData = new FormData();
        formData.append('file', state.selectedFile);
        formData.append('sectionId', state.activeSectionId);

        try {
            const response = await $.ajax({
                url: CONFIG.routes.sectionDocument.uploadDocument,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false
            });

            return response;
        } catch (error) {
            console.error('Upload error:', error);
            throw error;
        }
    }

    // Liaison d'un fichier RYM
    async function linkRYMFile() {
        try {
            const response = await $.ajax({
                url: CONFIG.routes.sectionDocument.linkDocument,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    sectionId: state.activeSectionId,
                    documentId: state.selectedFile.id
                })
            });

            return response;
        } catch (error) {
            console.error('Link error:', error);
            throw error;
        }
    }

    // Recharger les documents d'une section
    async function reloadSectionDocuments(sectionId) {
        try {
            const response = await $.ajax({
                url: `${CONFIG.routes.sectionDocument.getDocuments}/${sectionId}`,
                method: 'GET'
            });

            if (response.success) {
                // Mettre à jour l'interface utilisateur avec les nouveaux documents
                updateSectionDocumentsUI(response.documents);
            }
        } catch (error) {
            console.error('Error reloading documents:', error);
        }
    }


    // Mettre à jour l'interface utilisateur des documents
    function updateSectionDocumentsUI(documents) {
        const documentsContainer = $('#section-documents-list');

        if (documents.length > 0) {
            const html = documents.map(doc => `
                <div class="document-item list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file mr-2"></i>
                        <span>${doc.nom}</span>
                        <small class="text-muted ml-2">${new Date(doc.dateCreation).toLocaleDateString()}</small>
                    </div>
                    <div>
                        <a href="${CONFIG.URLROOT}/projet/annexe/${doc.url}" 
                           class="btn btn-sm btn-outline-primary" 
                           target="_blank">
                            <i class="fas fa-eye mr-1"></i>Visualiser
                        </a>
                    </div>
                </div>
            `).join('');

            documentsContainer.html(`
                <div class="list-group">
                    ${html}
                </div>
            `);
        } else {
            documentsContainer.html(`
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    Aucun document associé à cette section
                </div>
            `);
        }
    }

    // Messages
    function showSuccess(message) {
        // Vous pouvez personnaliser l'affichage des messages de succès
        if (window.Toastify) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#28a745"
            }).showToast();
        } else {
            alert(message);
        }
    }

    function showError(message) {
        // Vous pouvez personnaliser l'affichage des messages d'erreur
        if (window.Toastify) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545"
            }).showToast();
        } else {
            alert('Erreur: ' + message);
        }
    }

    // API publique
    return {
        init: init,
        openFileModal: openFileModal,
        loadSectionDocuments: loadSectionDocuments
    };
})();


// function loadSectionDocuments(sectionId) {
//     $('#section-documents-list').html(`
//         <div class="text-center py-3">
//             <i class="fas fa-spinner fa-spin fa-2x"></i>
//             <p class="text-muted">Chargement des documents...</p>
//         </div>
//     `);

//     $.ajax({
//         url: CONFIG.routes.sectionDocument.getDocuments + '/' + sectionId,
//         method: 'GET',
//         success: function (response) {
//             if (response.success && response.documents.length > 0) {
//                 const documentsHtml = response.documents.map(doc => `
//                     <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
//                         <div>
//                             <i class="fas fa-file mr-2"></i>
//                             <span class="font-weight-bold">${doc.nom}</span>
//                             <small class="text-muted ml-2">
//                                 ${new Date(doc.dateCreation).toLocaleDateString()}
//                             </small>
//                         </div>
//                         <div>
//                             <a href="${CONFIG.URLROOT}/projet/annexe/${doc.url}" 
//                                class="btn btn-sm btn-outline-primary" 
//                                target="_blank">
//                                 <i class="fas fa-eye mr-1"></i>Visualiser
//                             </a>
//                         </div>
//                     </div>
//                 `).join('');

//                 $('#section-documents-list').html(`
//                     <div class="list-group">
//                         ${documentsHtml}
//                     </div>
//                 `);
//             } else {
//                 $('#section-documents-list').html(`
//                     <div class="alert alert-info">
//                         <i class="fas fa-info-circle mr-2"></i>
//                         Aucun document associé à cette section
//                     </div>
//                 `);
//             }
//         },
//         error: function () {
//             $('#section-documents-list').html(`
//                 <div class="alert alert-danger">
//                     <i class="fas fa-exclamation-triangle mr-2"></i>
//                     Erreur lors du chargement des documents
//                 </div>
//             `);
//         }
//     });
// }


function loadSectionDocuments(sectionId) {
    $('#section-documents-list').html(`
        <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="text-muted">Chargement des documents...</p>
        </div>
    `);

    $.ajax({
        url: CONFIG.routes.sectionDocument.getDocuments + '/' + sectionId,
        method: 'GET',
        success: function (response) {
            if (response.success && response.documents.length > 0) {
                const documentsHtml = `
                    <table class="table table-hover table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Nom du fichier</th>
                                <th>Type</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${response.documents.map(doc => `
                                <tr>
                                    <td>
                                        <i class="fas fa-file mr-2"></i>
                                        ${doc.nom}
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            ${doc.url.split('.').pop().toUpperCase()}
                                        </span>
                                    </td>
                                    <td>
                                        ${new Date(doc.dateCreation).toLocaleDateString()}
                                    </td>
                                    <td>
                                        <a href="${CONFIG.URLROOT}/projet/annexe/${doc.url}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           target="_blank">
                                            <i class="fas fa-eye mr-1"></i>Visualiser
                                        </a>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                `;

                $('#section-documents-list').html(documentsHtml);
            } else {
                $('#section-documents-list').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        Aucun document associé à cette section
                    </div>
                `);
            }
        },
        error: function () {
            $('#section-documents-list').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Erreur lors du chargement des documents
                </div>
            `);
        }
    });
}

// Initialisation au chargement du document
$(document).ready(function () {
    DocumentHandler.init();
});