let prox_clases = [];
async function cargarDatos(){
    const contenedor_alumnas = document.getElementById("resumenAlumnas");
    const contenedor_profesoras = document.getElementById("resumenProfesores");
    const contenedor_clases = document.getElementById("resumenClases");
    const contenedor_alertas = document.getElementById("resumenAlertas");
    const contener_prox = document.getElementById("proximasClases");

    try {
        const response = await fetch(BASE_URL + "admin/resumen_alumnas");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor_alumnas.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudo cargar el resumen de alumnas.</p>
                </div>
            `;
            return;
        } else {
            contenedor_alumnas.innerHTML = `
                        <p class="text-white mb-2">
                            Alumnas<br>Activas
                        </p>

                        <h1 class="fw-bold text-white mb-3" >
                            ${data}
                        </h1>

                        <a href="${BASE_URL}administrador/gestionUser" class="btn btn-primary btn-sm px-3">
                            Ver alumnas
                        </a>
            `
        }
    } catch (error) {
        console.error("Error al cargar el resumen de alumnas:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las alumnas registradas.</p>
            </div>
        `;
    }

    try {
        const response = await fetch(BASE_URL + "admin/resumen_profesores");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor_profesoras.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudo cargar el resumen de profesores.</p>
                </div>
            `;
            return;
        } else {
            contenedor_profesoras.innerHTML = `
                        <p class="text-white mb-2">
                            Profesores<br>Activos
                        </p>

                        <h1 class="fw-bold text-white mb-3">
                            ${data}
                        </h1>

                        <a href="${BASE_URL}administrador/gestionUser" class="btn btn-primary btn-sm px-3">
                            Ver profesores
                        </a>
            `
        }
    } catch (error) {
        console.error("Error al cargar el resumen de profesoras:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las profesoras registradas.</p>
            </div>
        `;
    }

    try {
        const response = await fetch(BASE_URL + "admin/resumen_clases");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor_clases.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudo cargar el resumen de clases.</p>
                </div>
            `;
            return;
        } else {
            contenedor_clases.innerHTML = `
                        <p class="text-white mb-2">
                            Clases<br>Hoy
                        </p>

                        <h1 class="fw-bold text-white mb-3">
                            ${data}
                        </h1>

                        <a href="${BASE_URL}administrador/horarios" class="btn btn-primary btn-sm px-3" >
                            Ver clases
                        </a>
            `
        }
    } catch (error) {
        console.error("Error al cargar el resumen de clases:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las clases registradas.</p>
            </div>
        `;
    }

    try {
        const response = await fetch(BASE_URL + "admin/resumen_alertas");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contenedor_alertas.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudo cargar el resumen de alertas.</p>
                </div>
            `;
            return;
        } else {
            contenedor_alertas.innerHTML = `
                         <p class="text-white mb-2">
                            Alertas de<br>Planes
                        </p>

                        <h1 class="fw-bold text-white mb-3">
                            ${data}
                        </h1>

                        <a href="${BASE_URL}administrador/gestionUser?alerta=1" class="btn btn-primary btn-sm px-3">
                            Ver alertas
                        </a>
            `
        }
    } catch (error) {
        console.error("Error al cargar el resumen de clases:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las clases registradas.</p>
            </div>
        `;
    }

    try {
        const response = await fetch(BASE_URL + "admin/prox_clases");

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (!data.success) {
            contener_prox.innerHTML = `
                <div class="text-center text-white py-4">
                    <p>No se pudieronc cargar las próximas clases.</p>
                </div>
            `;
            return;
        } 

        prox_clases = data.clases || [];
    } catch (error) {
        console.error("Error al cargar el resumen de clases:", error);
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>Error al cargar las clases registradas.</p>
            </div>
        `;
    }
}

function renderizarClases(clases) {
    const contenedor = document.getElementById("proximasClases");

    if (!clases || clases.length === 0) {
        contenedor.innerHTML = `
            <div class="text-center text-white py-4">
                <p>No hay clases próximas</p>
            </div>
        `;
        return;
    }

    const ahora = new Date();
    let cardsHtml = `<h2 class="text-center fw-bold text-white mb-4">
                Próximas Clases
            </h2>`;

    clases.forEach(function (clase) {
        cardsHtml += `
            <div class="schedule-card mb-3">

                <div class="d-flex justify-content-between align-items-center">

                    <h4 class="fw-bold text-white mb-0">
                        ${clase.hora_inicio}
                    </h4>

                    <span class="text-white">
                        Grupal - ${clase.nombre_profesor}
                    </span>

                </div>

            </div>
        `;
    });

    contenedor.innerHTML = cardsHtml;
}

document.addEventListener("DOMContentLoaded", function () {
    cargarDatos();
    renderizarClases(prox_clases);
});