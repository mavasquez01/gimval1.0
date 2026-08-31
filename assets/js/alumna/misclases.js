// Estado global para la paginación
let todasLasClases = [];
let paginaActual = 1;
const clasesPorPagina = 3;

async function cargarMisClases() {
    const contenedor = document.getElementById("listaClases");
    const paginacion = document.getElementById("paginacionClases");

    try {
        const response = await fetch(BASE_URL + "alumna/obtener_mis_clases");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudieron cargar tus clases.</p>
                </div>
            `;
            if (paginacion) paginacion.innerHTML = "";
            return;
        }

        todasLasClases = data.clases || [];
        paginaActual = 1;
        mostrarPagina(paginaActual);

    } catch (error) {
        console.error("Error al cargar las clases:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las clases.</p>
            </div>
        `;
        if (paginacion) paginacion.innerHTML = "";
    }
}

function mostrarPagina(pagina) {
    paginaActual = pagina;
    const inicio = (paginaActual - 1) * clasesPorPagina;
    const fin = inicio + clasesPorPagina;
    const clasesPagina = todasLasClases.slice(inicio, fin);

    renderizarClases(clasesPagina);
    renderizarPaginacion();
}

function renderizarClases(clases) {
    const contenedor = document.getElementById("listaClases");

    if (!clases || clases.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>No tienes clases reservadas.</p>
            </div>
        `;
        return;
    }

    const ahora = new Date();
    let cardsHtml = "";

    clases.forEach(function(clase) {
        const hora = clase.hora_inicio ? clase.hora_inicio.substring(0, 5) : "";
        const fecha = new Date(clase.fecha + "T00:00");

        const fechaFormateada = fecha.toLocaleDateString("es-CL", {
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        // Validar si la clase es Próxima o Completada comparando fecha y hora actual
        const fechaHoraClase = new Date(`${clase.fecha}T${clase.hora_inicio || "00:00:00"}`);
        const esProxima = fechaHoraClase >= ahora;

        const badge = esProxima 
            ? `<span class="badge-proxima">PRÓXIMA</span>`
            : `<span class="badge-completada">COMPLETADA</span>`;

        // Validar si tiene rutina y asignar estilos con contorno naranjo
        const tieneRutina = clase.id_rutina && clase.id_rutina !== "0" && clase.id_rutina !== 0;
        const rutina = tieneRutina
            ? `<a href="rutina.html?id_rutina=${clase.id_rutina}" class="btn-rutina-outline text-decoration-none">VER RUTINA</a>`
            : `<span class="badge-sin-rutina-outline">SIN RUTINA</span>`;

        cardsHtml += `
            <div class="schedule-card mb-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="fw-bold text-white mb-2">${hora}</h4>
                        <p class="text-white mb-2">
                            ${clase.especialidad} - ${clase.nombre_profesor} ${clase.apellido_profesor}
                        </p>
                        <small class="text-pink">${fechaFormateada}</small>
                    </div>
                    <div class="d-flex flex-column justify-content-between align-items-end">
                        ${badge}
                        ${rutina}
                    </div>
                </div>
            </div>
        `;
    });

    contenedor.innerHTML = cardsHtml;
}

function renderizarPaginacion() {
    const paginacion = document.getElementById("paginacionClases");
    const totalPaginas = Math.ceil(todasLasClases.length / clasesPorPagina);

    if (totalPaginas <= 1) {
        paginacion.innerHTML = "";
        return;
    }

    let html = `<ul class="pagination pagination-sm m-0">`;

    html += `
        <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
            <button class="page-link" onclick="cambiarPagina(${paginaActual - 1})">&laquo;</button>
        </li>
    `;

    for (let i = 1; i <= totalPaginas; i++) {
        html += `
            <li class="page-item ${i === paginaActual ? 'active' : ''}">
                <button class="page-link" onclick="cambiarPagina(${i})">${i}</button>
            </li>
        `;
    }

    html += `
        <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
            <button class="page-link" onclick="cambiarPagina(${paginaActual + 1})">&raquo;</button>
        </li>
    `;

    html += `</ul>`;
    paginacion.innerHTML = html;
}

function cambiarPagina(nuevaPagina) {
    const totalPaginas = Math.ceil(todasLasClases.length / clasesPorPagina);
    if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas) {
        mostrarPagina(nuevaPagina);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    cargarMisClases();
});