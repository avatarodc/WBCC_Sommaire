$(document).ready(function () {
    // Activation des tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Variables globales pour la suppression
    let projectIdToDelete = null;

    // Recherche dynamique
    $('#searchInput').on('input', function () {
        const searchText = $(this).val().toLowerCase();
        let visibleCount = 0;

        $('.project-card').each(function () {
            const card = $(this);
            const nom = card.find('.badge-danger').first().text().toLowerCase();
            const adresse = card.find('.card-body div:first .bg-light').text().toLowerCase();
            const description = card.find('.card-body div:eq(1) .bg-light').text().toLowerCase();

            const isVisible = nom.includes(searchText) ||
                adresse.includes(searchText) ||
                description.includes(searchText);

            card.toggle(isVisible);
            if (isVisible) visibleCount++;
        });

        $('#projectCount').text(visibleCount);
    });

    // Gestion du modal de suppression
    $('.delete-project').click(function (e) {
        e.preventDefault();
        projectIdToDelete = $(this).data('id');
        const projetNom = $(this).data('projet-nom');
        $('#projectNumberToDelete').text(projetNom);
        $('#deleteProjectModal').modal('show');
    });

    // Confirmation de suppression
    $('#confirmDelete').click(function () {
        if (projectIdToDelete) {
            $.ajax({
                url: $('#URLROOT').val() + '/GestionInterne/deleteProjetById/' + projectIdToDelete,
                type: 'POST',
                success: function () {
                    $('#deleteProjectModal').modal('hide');
                    // Redirection vers la page de liste des projets
                    window.location.href = $('#URLROOT').val() + '/GestionInterne/indexProjet';
                },
                error: function () {
                    $('#deleteProjectModal').modal('hide');
                    alert('Une erreur est survenue lors de la suppression du projet.');
                }
            });
        }
    });
});