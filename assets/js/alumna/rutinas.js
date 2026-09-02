let idRutinaActual = null;

const DIAS_SEMANA = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

function obtenerIdRutinaDeURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get("id_rutina");
}

async function cargarRutina() {
    const contenedor = document.getElementById("rutinaContenedor");
    idRutinaActual = obtenerIdRutinaDeURL();

    if (!idRutinaActual) {
        contenedor.innerHTML = `<div class="text-center text-white py-4"><p>Rutina no especificada.</p></div>`;
        return;
    }

    contenedor.innerHTML = `<div class="text-center text-white py-4">Cargando rutina...</div>`;

    try {
        const response = await fetch(BASE_URL + "alumna/obtener_rutina?id_rutina=" + encodeURIComponent(idRutinaActual));

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (!data.success) {
            contenedor.innerHTML = `<div class="text-center text-white py-4"><p>${data.message}</p></div>`;
            return;
        }

        renderizarRutina(data.rutina, data.ejercicios);

    } catch (error) {
        console.error("Error al cargar la rutina:", error);
        contenedor.innerHTML = `<div class="text-center text-white py-4"><p>Error al cargar la rutina.</p></div>`;
    }
}

function renderizarRutina(rutina, ejercicios) {
    const contenedor = document.getElementById("rutinaContenedor");

    // rutina.fecha viene como "YYYY-MM-DD HH:MM:SS" o similar
    const fecha = new Date(rutina.fecha.replace(" ", "T"));
    const nombreDia = DIAS_SEMANA[fecha.getDay()];

    let html = `
        <div class="d-flex justify-content-center mb-4">
            <h5 class="text-white mb-0 text-center">Rutina - ${nombreDia}</h5>
        </div>
    `;

    if (!ejercicios || ejercicios.length === 0) {
        html += `<div class="text-center text-white py-3"><p>No hay ejercicios cargados para esta rutina.</p></div>`;
    } else {
        ejercicios.forEach(function (ej) {
            const pesoActual = ej.peso_kg !== null && ej.peso_kg !== undefined ? ej.peso_kg : "";

            html += `
                <div class="schedule-card mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-white mb-2">${ej.nombre_ejercicio}</h5>
                            <p class="text-secondary mb-0">${ej.series} x ${ej.repeticiones}</p>
                        </div>
                        <div class="text-end">
                            <label class="form-label text-secondary mb-1">Peso (kg)</label>
                            <input type="number"
                                   class="form-control custom-input text-center routine-weight-input"
                                   value="${pesoActual}"
                                   data-id-ejercicio="${ej.id_ejercicio}">
                        </div>
                    </div>
                </div>
            `;
        });

        html += `
            <button class="btn btn-primary w-100" id="btnGuardarProgreso">
                Guardar progreso
            </button>
        `;
    }

    contenedor.innerHTML = html;

    const btnGuardar = document.getElementById("btnGuardarProgreso");
    if (btnGuardar) {
        btnGuardar.addEventListener("click", guardarProgreso);
    }
}

async function guardarProgreso() {
    const inputs = document.querySelectorAll(".routine-weight-input");
    const ejercicios = Array.from(inputs).map(input => ({
        id_ejercicio: input.dataset.idEjercicio,
        peso: parseFloat(input.value) || 0
    }));

    const btn = document.getElementById("btnGuardarProgreso");
    const textoOriginal = btn.textContent;
    btn.disabled = true;
    btn.textContent = "Guardando...";

    try {
        const response = await fetch(BASE_URL + "alumna/guardar_progreso", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ ejercicios })
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        if (data.success) {
            btn.textContent = "¡Guardado!";
            setTimeout(() => { btn.textContent = textoOriginal; btn.disabled = false; }, 1500);
        } else {
            throw new Error(data.message || "Error desconocido");
        }

    } catch (error) {
        console.error("Error al guardar progreso:", error);
        btn.textContent = "Error al guardar";
        setTimeout(() => { btn.textContent = textoOriginal; btn.disabled = false; }, 1500);
    }
}

document.addEventListener("DOMContentLoaded", cargarRutina);