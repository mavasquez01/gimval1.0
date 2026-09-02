document.addEventListener('DOMContentLoaded', function () {
    const btnVerHorarios = document.getElementById('btn-ver-horarios');
    const agendaTabBtn = document.getElementById('agenda-tab');

    if (btnVerHorarios && agendaTabBtn) {
        btnVerHorarios.addEventListener('click', function (e) {
            e.preventDefault();

            // Usa la API oficial de Bootstrap 5 para alternar la pestaña de forma limpia
            const tab = new bootstrap.Tab(agendaTabBtn);
            tab.show();
        });
    }
});