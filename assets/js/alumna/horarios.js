(function () {
    let agendaCargada = false;

    function cargarAgenda() {
        if (agendaCargada) return;

        const contTabs = document.getElementById('dias-tab');
        const contContent = document.getElementById('dias-tab-content');
        const textoSemana = document.getElementById('agenda-texto-semana');

        contContent.innerHTML = '<p class="text-secondary text-center">Cargando horarios...</p>';

        fetch(BASE_URL + 'alumna/agendaJson')
            .then(function (res) {
                if (!res.ok) throw new Error('Error de red: ' + res.status);
                return res.json();
            })
            .then(function (data) {
                textoSemana.textContent = data.texto_semana;
                renderTabs(data.dias, contTabs, contContent);
                agendaCargada = true;
            })
            .catch(function (err) {
                contContent.innerHTML = '<p class="text-danger text-center">No se pudo cargar el horario. Intenta de nuevo.</p>';
                console.error(err);
            });
    }

    function renderTabs(dias, contTabs, contContent) {
        contTabs.innerHTML = '';
        contContent.innerHTML = '';

        dias.forEach(function (dia, index) {
            const li = document.createElement('li');
            li.className = 'nav-item';
            li.innerHTML =
                '<button class="nav-link' + (index === 0 ? ' active' : '') + '" ' +
                'id="' + dia.key + '-tab" data-bs-toggle="tab" data-bs-target="#' + dia.key + '-pane" type="button">' +
                '<small>' + dia.etiqueta + '</small><br>' + dia.numero +
                '</button>';
            contTabs.appendChild(li);

            const pane = document.createElement('div');
            pane.className = 'tab-pane fade' + (index === 0 ? ' show active' : '');
            pane.id = dia.key + '-pane';
            pane.setAttribute('role', 'tabpanel');
            pane.innerHTML = renderBloques(dia.bloques);
            contContent.appendChild(pane);
        });
    }

    function renderBloques(bloques) {
        if (!bloques.length) {
            return '<p class="text-secondary text-center">No hay clases</p>';
        }

        return bloques.map(function (b) {
            const sinCupos = b.cupos_ocupados >= b.cupos_maximos && !b.reservado_por_mi;
            let etiquetaAccion = 'AGENDAR';
            if (b.reservado_por_mi) etiquetaAccion = 'Cancelar →';
            else if (sinCupos) etiquetaAccion = 'Sin cupos';

            const atributosModal = (!b.reservado_por_mi && !sinCupos)
                ? 'data-bs-toggle="modal" data-bs-target="#confirmarAgendamientoModal" data-id-bloque="' + b.id_bloque + '"'
                : '';

            const href = b.reservado_por_mi
                ? BASE_URL + 'alumna/CancelarBloqye/' + b.id_bloque
                : '#';

            return (
                '<a href="' + href + '" class="text-decoration-none' + (sinCupos ? ' disabled' : '') + '" ' + atributosModal + '>' +
                    '<div class="schedule-card mb-3 clickable-card">' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                            '<div>' +
                                '<h5 class="text-white mb-1">' + b.hora_inicio + '</h5>' +
                                '<p class="text-white mb-1">' + escapeHtml(b.especialidad) + ' - ' + escapeHtml(b.profesor_nombre) + '</p>' +
                                '<small class="text-secondary">' + b.fecha_texto + '</small>' +
                            '</div>' +
                            '<div class="text-end">' +
                                '<p class="text-secondary mb-1">' + b.cupos_ocupados + '/' + b.cupos_maximos + '</p>' +
                                '<small class="text-secondary">' + etiquetaAccion + '</small>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</a>'
            );
        }).join('');
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const agendaTabBtn = document.getElementById('agenda-tab');
        if (agendaTabBtn) {
            agendaTabBtn.addEventListener('shown.bs.tab', cargarAgenda);
        }
    });
})();