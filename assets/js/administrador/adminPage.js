(() => {
    'use strict';

    // BASE_URL debe estar definida en la vista (ver nota) y apuntar a site_url()
    const url = (ruta) => BASE_URL.replace(/\/+$/, '') + '/' + ruta;

    const porId = (id) => document.getElementById(id);

    // Escapa texto que viene del servidor antes de meterlo en innerHTML
    const esc = (texto) =>
        String(texto ?? '').replace(/[&<>"']/g, (c) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
        }[c]));

    // ── Configuración: una entrada = una tarjeta ─────────────────
    const TARJETAS = [
        {
            id: 'resumenAlumnas',
            campo: 'alumnas_activas',
            titulo: 'Alumnas<br>Activas',
            enlace: 'administrador/gestionUsers',
            boton: 'Ver alumnas',
        },
        {
            id: 'resumenProfesores',
            campo: 'profesores_activos',
            titulo: 'Profesores<br>Activos',
            enlace: 'administrador/gestionUsers?tab=profesores',
            boton: 'Ver profesores',
        },
        {
            id: 'resumenClases',
            campo: 'clases_hoy',
            titulo: 'Clases<br>Hoy',
            enlace: 'administrador/horarios',
            boton: 'Ver clases',
        },
        {
            id: 'resumenAlertas',
            campo: 'alertas_planes',
            titulo: 'Alertas de<br>Planes',
            enlace: 'administrador/gestionUsers',
            boton: 'Ver alertas',
        },
    ];

    // ── Render ───────────────────────────────────────────────────
    function pintarTarjeta(cfg, valor) {
        const contenedor = porId(cfg.id);
        if (!contenedor) return;

        contenedor.innerHTML = `
            <p class="text-white mb-2">${cfg.titulo}</p>

            <h1 class="fw-bold text-white mb-3">${Number(valor) || 0}</h1>

            <a href="${esc(url(cfg.enlace))}" class="btn btn-primary btn-sm px-3">
                ${cfg.boton}
            </a>
        `;
    }

    function pintarErrorTarjeta(cfg) {
        const contenedor = porId(cfg.id);
        if (!contenedor) return;

        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p class="mb-0">No se pudo cargar.</p>
            </div>
        `;
    }

    function etiquetaFecha(fecha, hoy) {
        if (fecha === hoy) return 'Hoy';

        const [anio, mes, dia] = String(fecha).split('-');
        return `${dia}/${mes}`;
    }

    function pintarClases(clases, hoy) {
        const contenedor = porId('proximasClases');
        if (!contenedor) return;

        const titulo = `
            <h2 class="text-center fw-bold text-white mb-4">
                Próximas Clases
            </h2>
        `;

        if (!clases || clases.length === 0) {
            contenedor.innerHTML = titulo + `
                <div class="text-center text-white py-4">
                    <p>No hay clases próximas</p>
                </div>
            `;
            return;
        }

        contenedor.innerHTML = titulo + clases.map((clase) => `
            <div class="schedule-card mb-3">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h4 class="fw-bold text-white mb-0">
                            ${esc(String(clase.hora_inicio).slice(0, 5))}
                        </h4>
                        <small class="text-white">
                            ${esc(etiquetaFecha(clase.fecha, hoy))}
                        </small>
                    </div>

                    <span class="text-white">
                        Grupal - ${esc(clase.nombre_profesor ?? 'Sin profesor')}
                    </span>

                </div>
            </div>
        `).join('');
    }

    function pintarErrorClases() {
        const contenedor = porId('proximasClases');
        if (!contenedor) return;

        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>No se pudieron cargar las próximas clases.</p>
            </div>
        `;
    }

    // ── Carga: una sola petición para todo el panel ──────────────
    async function cargarPanel() {
        try {
            const respuesta = await fetch(url('administrador/resumenPanel'), {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });

            if (!respuesta.ok) {
                throw new Error(`HTTP ${respuesta.status}`);
            }

            const data = await respuesta.json();

            if (!data.success) {
                throw new Error('El servidor respondió sin éxito');
            }

            TARJETAS.forEach((cfg) => pintarTarjeta(cfg, data[cfg.campo]));
            pintarClases(data.proximas_clases, data.hoy);
        } catch (error) {
            console.error('Error al cargar el panel:', error);
            TARJETAS.forEach(pintarErrorTarjeta);
            pintarErrorClases();
        }
    }

    document.addEventListener('DOMContentLoaded', cargarPanel);
})();