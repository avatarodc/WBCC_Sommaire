// Variables globales
let currentSommaireId = null;
let mockData = {
    titreSommaire: null,
    sections: []
};
let sommaires = [];
let activeSection = null;
// Initialisation au chargement du document
$(document).ready(function () {
    if (CONFIG.hasSommaire) {
        loadSectionsFromDatabase();
    }
});

// Chargement des sections depuis la base de données// Modification de la fonction loadSectionsFromDatabase
function loadSectionsFromDatabase() {
    if (!CONFIG.sommaire) {
        console.log('Sommaire is null');
        return;
    }

    $.ajax({
        url: CONFIG.routes.section.getSectionsBySommaire,
        method: 'GET',
        data: {
            idSommaire: CONFIG.sommaire.idSommaire
        },
        dataType: 'json',
        success: function (response) {
            if (response.sections) {
                // Mettre à jour les numéros avant l'affichage
                mockData.sections = updateAllSectionNumbers(response.sections);
                mockData.titreSommaire = response.titreSommaire;
                displaySectionsTree(mockData.sections);

                // Restaurer la section active si elle existe
                const activeSectionId = localStorage.getItem('activeSection');
                if (activeSectionId) {
                    const section = mockData.sections.find(s => s.idSection == activeSectionId);
                    if (section) {
                        showSection(activeSectionId);

                        // Si la section a des parents, ouvrir les sections parentes
                        let parentId = section.idSection_parentF;
                        while (parentId) {
                            const parentElement = $(`.section-item[data-section-id="${parentId}"]`);
                            const toggleElement = parentElement.find('.section-toggle i');
                            parentElement.children('.subsections').removeClass('collapsed');
                            toggleElement.removeClass('fa-chevron-right').addClass('fa-chevron-down');

                            const parentSection = mockData.sections.find(s => s.idSection == parentId);
                            parentId = parentSection ? parentSection.idSection_parentF : null;
                        }
                    }
                }
            } else {
                $('#sections-tree').html('<p class="text-muted text-center">Aucune section trouvée</p>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Erreur lors du chargement des sections:', error);
            $('#sections-tree').html(
                '<div class="alert alert-danger">Erreur lors du chargement des sections</div>'
            );
        }
    });
}

// Affichage des sections dans le menu déroulant
function displaySectionsTree(sections, parentId = null, level = 0) {
    const currentLevelSections = sections.filter(s => {
        if (parentId == null) {
            return !s.idSection_parentF;
        }
        return s.idSection_parentF == parentId;
    });

    let html = '';

    currentLevelSections.forEach((section, index) => {
        const hasSubsections = sections.some(s => s.idSection_parentF == section.idSection);

        html += `
            <div class="section-item" data-section-id="${section.idSection}">
                <div class="d-flex align-items-center">
                    ${hasSubsections ? `
                        <div class="section-toggle mr-2" onclick="toggleSubsections(event, this)">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    ` : '<div style="width: 20px;"></div>'}
                    <div class="flex-grow-1" onclick="showSection(${section.idSection})">
                        <span class="mr-2">${section.numeroSection}</span>
                        ${section.titreSection}
                    </div>
                    <div class="section-actions">
                        <button class="btn btn-sm btn-link" onclick="event.stopPropagation(); addSubSection(${section.idSection})" title="Ajouter une sous-section">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-danger" onclick="event.stopPropagation(); deleteSection(${section.idSection})" title="Supprimer la section">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                ${hasSubsections ? `
                    <div class="subsections">
                        ${displaySectionsTree(sections, section.idSection, level + 1)}
                    </div>
                ` : ''}
            </div>
        `;
    });

    if (level == 0) {
        $('#sections-tree').html(html || '<p class="text-muted text-center">Aucune section trouvée</p>');
    }
    return html;
}

// Fonction pour afficher ou masquer les sous-sections
function toggleSubsections(event, element) {
    event.stopPropagation();
    const icon = $(element).find('i');
    icon.toggleClass('fa-chevron-down fa-chevron-right');
    $(element).closest('.section-item').children('.subsections').toggleClass('collapsed');
}

function showSection(sectionId) {
    const section = mockData.sections.find(s => s.idSection == sectionId);
    if (!section) return;

    // Sauvegarder l'ID de la section active
    localStorage.setItem('activeSection', sectionId);
    // Mise à jour de l'UI
    $('.section-item').removeClass('active');
    $(`.section-item[data-section-id="${sectionId}"]`).addClass('active');

    const html = `
        <div class="section-content-area">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4>${section.numeroSection} - ${section.titreSection}</h4>
                </div>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="addSubSection(${section.idSection})">
                        <i class="fas fa-plus"></i> Sous-section
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteSection(${section.idSection})">
                        <i class="fas fa-trash"></i>
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="saveSection(${section.idSection})">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>Titre de la section</label>
                <input type="text" 
                       class="form-control section-title-input" 
                       id="section-title-${section.idSection}" 
                       value="${section.titreSection}"
                       data-section-id="${section.idSection}">
            </div>

            <div class="form-group">
                <label>Contenu de la section</label>
                <textarea class="form-control tinymce-editor" 
                          id="section-content-${section.idSection}"
                          data-section-id="${section.idSection}"
                          rows="3">${section.contenuSection || ''}</textarea>
            </div>
        </div>
    `;

    $('#section-content').html(html);
    initializeTinyMCE();

    // Ajouter les écouteurs d'événements pour la sauvegarde automatique
    $(`#section-title-${section.idSection}`).on('blur', function () {
        saveSection(section.idSection, true); // true indique une sauvegarde automatique
    });

    // Pour TinyMCE, on écoute l'événement de changement
    tinymce.get(`section-content-${section.idSection}`).on('blur', function () {
        saveSection(section.idSection, true); // true indique une sauvegarde automatique
    });
}




// Gestion du formulaire de création de section
$('#createSectionForm').on('submit', function (e) {
    e.preventDefault();

    const formData = {
        titreSection: $('input[name="titreSection"]').val().trim(),
        idSommaireF: $('input[name="idSommaireF"]').val(),
        idSection_parentF: $('#parentSectionId').val() || null,
        numeroSection: calculateSectionNumber($('#parentSectionId').val())
    };

    if (!formData.titreSection) {
        alert('Veuillez entrer un titre pour la section');
        return;
    }

    const saveButton = $(this).find('button[type="submit"]');
    const originalText = saveButton.html();
    saveButton.html('<i class="fas fa-spinner fa-spin"></i> Création...').prop('disabled', true);

    $.ajax({
        url: CONFIG.routes.section.add,
        method: 'POST',
        data: formData,
        success: function (response) {
            try {
                const jsonResponse = typeof response == 'string' ? JSON.parse(response) : response;
                if (jsonResponse.success || response == '') {
                    $('#createSectionModal').modal('hide');
                    loadSectionsFromDatabase();
                    $('input[name="titreSection"]').val('');
                } else {
                    console.error('Erreur:', jsonResponse.error || 'Erreur inconnue');
                    alert(jsonResponse.error || 'Erreur lors de la création de la section');
                }
            } catch (e) {
                $('#createSectionModal').modal('hide');
                loadSectionsFromDatabase();
                $('input[name="titreSection"]').val('');
            }
        },
        error: function (xhr, status, error) {
            console.error('Erreur lors de la création de la section:', error);
            if (xhr.status == 200) {
                $('#createSectionModal').modal('hide');
                loadSectionsFromDatabase();
                $('input[name="titreSection"]').val('');
            } else {
                alert('Une erreur est survenue lors de la création de la section');
            }
        },
        complete: function () {
            saveButton.html(originalText).prop('disabled', false);
        }
    });
});

// Fonctions de gestion des sections
/**
 * Affiche la modal de création d'un sommaire
 *
 * @param {Event} e
 */
function showCreateSommaireModal(e) {
    e.preventDefault();
    $('input[name="titreSommaire"]').val('');
    $('#createSommaireModal').modal('show');
}


/**
 * Ouvre la modal de création d'une section principale
 *
 * @see #createSectionModal
 */
function addMainSection() {
    $('#parentSectionId').val('');
    calculateSectionNumber(null);
    $('#createSectionModal').modal('show');
}


// Ouvre la modal de creation d'une sous-section
function addSubSection(parentId) {
    $('#parentSectionId').val(parentId);
    calculateSectionNumber(parentId);
    $('#createSectionModal').modal('show');
}


// Calcul du numero de section
function calculateSectionNumber(parentId) {
    const sections = mockData.sections;
    let numeroSection;

    if (!parentId) {
        // Pour une section principale
        const mainSections = sections.filter(s => !s.idSection_parentF);
        numeroSection = (mainSections.length + 1).toString();
    } else {
        // Pour une sous-section
        const parentSection = sections.find(s => parseInt(s.idSection) == parseInt(parentId));
        console.log(" parentSection", parentSection);
        const subSections = sections.filter(s => parseInt(s.idSection_parentF) == parseInt(parentId));
        numeroSection = `${parentSection.numeroSection}.${subSections.length + 1}`;
    }

    $('#numeroSection').val(numeroSection);
    return numeroSection;
}

function selectSection(sectionId) {
    const section = mockData.sections.find(s => s.idSection == sectionId);
    if (!section) return;

    // Mise à jour de l'UI
    $('.section-item').removeClass('active');
    $(`.section-item[data-section-id="${sectionId}"]`).addClass('active');

    // Affichage du contenu
    const contentHtml = `
        <div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>${section.numeroSection} - ${section.titreSection}</h4>
                <button class="btn btn-primary" onclick="saveSection(${sectionId})">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
            <div class="form-group">
                <label>Titre de la section</label>
                <input type="text" class="form-control" 
                       id="section-title-${sectionId}" 
                       value="${section.titreSection}">
            </div>
            <div class="form-group">
                <label>Contenu de la section</label>
                <textarea class="form-control tinymce-editor" 
                          id="section-content-${sectionId}">${section.contenuSection || ''}</textarea>
            </div>
        </div>
    `;

    $('#section-content').html(contentHtml);

    // Initialisation de TinyMCE
    tinymce.remove();
    initializeTinyMCE();
}


function saveSection(sectionId, isAutoSave = false) {
    const section = {
        idSection: sectionId,
        titreSection: $(`#section-title-${sectionId}`).val().trim(),
        contenuSection: tinymce.get(`section-content-${sectionId}`).getContent(),
        numeroSection: mockData.sections.find(s => s.idSection == sectionId).numeroSection
    };

    $.ajax({
        url: CONFIG.routes.section.updateMultiple,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            sections: [section]
        }),
        success: function (response) {
            if (response.success) {
                // Mettre à jour les données locales
                const sectionIndex = mockData.sections.findIndex(s => s.idSection == sectionId);
                if (sectionIndex !== -1) {
                    mockData.sections[sectionIndex] = { ...mockData.sections[sectionIndex], ...section };
                }

                // Rafraîchir l'arborescence
                displaySectionsTree(mockData.sections);

                // Afficher la confirmation seulement si ce n'est pas une sauvegarde automatique
                if (!isAutoSave) {
                    $('#saveConfirmationModal').modal('show');
                    setTimeout(function () {
                        $('#saveConfirmationModal').modal('hide');
                    }, 800);
                }
            } else {
                alert('Erreur lors de la sauvegarde: ' + (response.error || 'Erreur inconnue'));
            }
        },
        error: function (xhr, status, error) {
            alert('Erreur lors de la sauvegarde: ' + error);
        }
    });
}


// Affichage des sections
function displaySections(sections, parentId = null, level = 0) {
    function extractNumbers(numeroSection) {
        return numeroSection.split('.').map(Number);
    }
    function compareNumeroSections(a, b) {
        const numbersA = extractNumbers(a.numeroSection);
        const numbersB = extractNumbers(b.numeroSection);

        for (let i = 0; i < Math.max(numbersA.length, numbersB.length); i++) {
            const numA = numbersA[i] || 0;
            const numB = numbersB[i] || 0;
            if (numA !== numB) {
                return numA - numB;
            }
        }
        return 0;
    }

    const currentLevelSections = sections
        .filter(s => {
            if (parentId == null) {
                return !s.idSection_parentF;
            }
            return s.idSection_parentF == parentId;
        })
        .sort(compareNumeroSections);

    let html = '';
    const padding = level * 20;

    currentLevelSections.forEach((section, index) => {
        let displayNumber;
        if (parentId == null) {
            displayNumber = (index + 1).toString();
        } else {
            const parentSection = sections.find(s => s.idSection == parentId);
            displayNumber = `${parentSection.numeroSection}.${index + 1}`;
        }

        section.numeroSection = displayNumber;
        const hasSubsections = sections.some(s => s.idSection_parentF == section.idSection);

        html += `
            <div class="section-item" data-section-id="${section.idSection}" 
                 style="margin-left: ${padding}px;" 
                 onclick="selectSection(${section.idSection})">
                <div class="d-flex align-items-center">
                    ${hasSubsections ? `
                        <div class="section-toggle mr-2" onclick="toggleSection(event, this)" title="Plier/Déplier">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    ` : `<div class="mr-4"></div>`}
                    <div class="section-number mr-2">${displayNumber}</div>
                    <div class="section-title flex-grow-1">${section.titreSection}</div>
                    <div class="section-actions">
                        <button class="btn btn-sm btn-link" onclick="event.stopPropagation(); addSubSection(${section.idSection})">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-link text-danger" onclick="event.stopPropagation(); deleteSection(${section.idSection})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="subsections">
                    ${displaySections(sections, section.idSection, level + 1)}
                </div>
            </div>
        `;
    });

    if (level == 0) {
        $('#sections-list').html(html || '<p class="text-muted text-center">Aucune section</p>');
    }
    return html;
}


// Fonction pour initialiser TinyMCE
function initializeTinyMCE() {
    tinymce.remove();
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 400,
        branding: false,
        statusbar: false,
        paste_as_text: true,
        menubar: false,
        plugins: [
            'lists link image code table'
        ],
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });
}


// Fonction pour enregistrer toutes les modifications
// function saveAllSections() {
//     const updatedSections = [];
//     const processedIds = new Set();

//     $('.section-title-input, .section-content-input').each(function () {
//         const sectionId = parseInt($(this).data('section-id'));

//         if (!processedIds.has(sectionId)) {
//             const section = mockData.sections.find(s => s.idSection == sectionId);

//             if (section) {
//                 const titleInput = $(`.section-title-input[data-section-id="${sectionId}"]`);
//                 const contentEditor = tinymce.get($(`.section-content-input[data-section-id="${sectionId}"]`).attr('id'));
//                 const content = contentEditor ? contentEditor.getContent() : '';

//                 updatedSections.push({
//                     idSection: sectionId,
//                     titreSection: titleInput.val().trim(),
//                     contenuSection: content,
//                     numeroSection: section.numeroSection
//                 });

//                 processedIds.add(sectionId);
//             }
//         }
//     });

//     console.log('Données à envoyer:', updatedSections);

//     const saveButton = $('.btn-primary:contains("Enregistrer")');
//     const originalText = saveButton.html();
//     saveButton.html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
//     saveButton.prop('disabled', true);

//     $.ajax({
//         url: CONFIG.routes.section.updateMultiple,
//         method: 'POST',
//         contentType: 'application/json',
//         data: JSON.stringify({
//             sections: updatedSections
//         }),
//         success: function (response) {
//             console.log('Réponse du serveur:', response);
//             if (response.success) {
//                 $('#saveConfirmationModal').modal('show');
//                 loadSectionsFromDatabase();
//             } else {
//                 $('#errorModal').find('.modal-body').text(response.error || 'Erreur inconnue');
//                 $('#errorModal').modal('show');
//             }
//         },
//         error: function (xhr, status, error) {s
//             console.error('Erreur AJAX:', {
//                 status: status,
//                 error: error,
//                 response: xhr.responseText
//             });
//             $('#errorModal').find('.modal-body').text('Erreur lors de l\'enregistrement des modifications: ' + error);
//             $('#errorModal').modal('show');
//         },
//         complete: function () {
//             saveButton.html(originalText);
//             saveButton.prop('disabled', false);
//         }
//     });
// }

// Fonction pour supprimer une section
function deleteSection(sectionId) {

    // Si la section actuellement active est celle à supprimer, on la supprime
    if (sectionId == localStorage.getItem('activeSection')) {
        localStorage.removeItem('activeSection');
    }

    $('#deleteSectionModal')
        .data('sectionId', sectionId) // Stocke l'ID de la section à supprimer
        .modal('show');
}
// Fonction pour confirmer la suppression
$('#confirmDeleteBtn').click(function () {
    const sectionId = $('#deleteSectionModal').data('sectionId');
    $('#deleteSectionModal').modal('hide');

    $.ajax({
        url: CONFIG.routes.section.delete + '/' + sectionId,
        method: 'POST',
        dataType: 'json',
        success: function (response) {
            if (!response || response.success) {
                // Supprimer la section et ses sous-sections des données locales
                function removeSection(sections, id) {
                    const subsToRemove = sections.filter(s => s.idSection_parentF == id);
                    subsToRemove.forEach(sub => removeSection(sections, sub.idSection));
                    const index = sections.findIndex(s => s.idSection == id);
                    if (index !== -1) sections.splice(index, 1);
                }

                removeSection(mockData.sections, sectionId);

                // Mettre à jour la numérotation et rafraîchir l'affichage
                mockData.sections = updateAllSectionNumbers(mockData.sections);
                displaySectionsTree(mockData.sections);

                // Vider le contenu de la section si elle était affichée
                if ($('#section-content').find(`[data-section-id="${sectionId}"]`).length) {
                    $('#section-content').empty();
                }
            } else {
                $('#errorModal').find('.modal-body').text(response.error || 'Une erreur est survenue lors de la suppression');
                $('#errorModal').modal('show');
            }
        },
        error: function (xhr, status, error) {
            let errorMessage = 'Une erreur est survenue lors de la suppression';
            if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.error) errorMessage = response.error;
                } catch (e) {
                    console.error("Erreur parsing JSON:", e);
                }
            }
            $('#errorModal').find('.modal-body').text(errorMessage);
            $('#errorModal').modal('show');
        }
    });
});


// Fonction pour recalculer tous les numéros de sections
function updateAllSectionNumbers(sections) {
    // Trie les sections principales
    const mainSections = sections.filter(s => !s.idSection_parentF);
    mainSections.sort((a, b) => {
        const aNum = parseInt(a.numeroSection.split('.')[0]);
        const bNum = parseInt(b.numeroSection.split('.')[0]);
        return aNum - bNum;
    });

    // Met à jour les numéros des sections principales
    mainSections.forEach((section, index) => {
        section.numeroSection = (index + 1).toString();
        updateSubSectionNumbers(section, sections);
    });

    return sections;
}

// Fonction pour mettre à jour les numéros des sous-sections
function updateSubSectionNumbers(parentSection, allSections) {
    const subSections = allSections.filter(s => s.idSection_parentF == parentSection.idSection);
    subSections.sort((a, b) => {
        const aNumbers = a.numeroSection.split('.').map(Number);
        const bNumbers = b.numeroSection.split('.').map(Number);
        for (let i = 0; i < Math.max(aNumbers.length, bNumbers.length); i++) {
            if (aNumbers[i] !== bNumbers[i]) {
                return (aNumbers[i] || 0) - (bNumbers[i] || 0);
            }
        }
        return 0;
    });

    subSections.forEach((section, index) => {
        section.numeroSection = `${parentSection.numeroSection}.${index + 1}`;
        // Récursion pour les sous-sections plus profondes
        updateSubSectionNumbers(section, allSections);
    });
}