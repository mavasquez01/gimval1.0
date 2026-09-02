(function () {
    let agendaCargada = false;
    const baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';

    function cargarAgenda(forzar) {
        if (agendaCargada && !forzar) return;

        const contTabs = document.getElementById("dias-tab");
        const contContent = document.getElementById("dias-tab-content");
        const textoSemana = document.getElementById("agenda-texto-semana");

        if (!contContent) return;

        contContent.innerHTML =
            '<p class="text-secondary text-center">Cargando horarios...</p>';

        fetch(baseUrl + "alumna/agendaJson")
            .then(function (res) {
                if (!res.ok) throw new Error("Error de red: " + res.status);
                return res.json();
            })
            .then(function (data) {
                if (textoSemana && data.texto_semana) {
                    textoSemana.textContent = data.texto_semana;
                }
                renderTabs(data.dias || [], contTabs, contContent);
                agendaCargada = true;
            })
            .catch(function (err) {
                contContent.innerHTML =
                    '<p class="text-danger text-center">No se pudo cargar el horario. Intenta de nuevo.</p>';
                console.error(err);
            });
    }

    function renderTabs(dias, contTabs, contContent) {
        if (!contTabs || !contContent) return;
        contTabs.innerHTML = "";
        contContent.innerHTML = "";

        dias.forEach(function (dia, index) {
            const li = document.createElement("li");
            li.className = "nav-item";
            li.innerHTML =
                '<button class="nav-link' +
                (index === 0 ? " active" : "") +
                '" ' +
                'id="' +
                dia.key +
                '-tab" data-bs-toggle="tab" data-bs-target="#' +
                dia.key +
                '-pane" type="button">' +
                "<small>" +
                dia.etiqueta +
                "</small><br>" +
                dia.numero +
                "</button>";
            contTabs.appendChild(li);

            const pane = document.createElement("div");
            pane.className = "tab-pane fade" + (index === 0 ? " show active" : "");
            pane.id = dia.key + "-pane";
            pane.setAttribute("role", "tabpanel");
            pane.innerHTML = renderBloques(dia.bloques);
            contContent.appendChild(pane);
        });
    }

    function renderBloques(bloques) {
        if (!bloques || !bloques.length) {
            return '<p class="text-secondary text-center">No hay clases</p>';
        }

        return bloques
            .map(function (b) {
                const esPasado = Boolean(b.pasado);
                const puedeCancelar = (typeof b.puede_cancelar !== 'undefined') ? Boolean(b.puede_cancelar) : true;
                const sinCupos = b.cupos_ocupados >= b.cupos_maximos && !b.reservado_por_mi;

                let etiquetaAccion = "AGENDAR";
                if (b.reservado_por_mi) {
                    etiquetaAccion = puedeCancelar ? "Cancelar" : "Reservado";
                } else if (esPasado) {
                    etiquetaAccion = "Fuera de Horario";
                } else if (sinCupos) {
                    etiquetaAccion = "Sin cupos";
                }

                const infoBloque =
                    "<div>" +
                    '<h5 class="text-white mb-1">' +
                    b.hora_inicio +
                    "</h5>" +
                    '<p class="text-white mb-1">' +
                    escapeHtml(b.especialidad) +
                    " - " +
                    escapeHtml(b.profesor_nombre) +
                    "</p>" +
                    '<small class="text-secondary">' +
                    b.fecha_texto +
                    "</small>" +
                    "</div>" +
                    '<div class="text-end">' +
                    '<p class="text-secondary mb-1">' +
                    b.cupos_ocupados +
                    "/" +
                    b.cupos_maximos +
                    "</p>" +
                    '<small class="text-secondary">' +
                    etiquetaAccion +
                    "</small>" +
                    "</div>";

                // Si está reservado por la alumna
                if (b.reservado_por_mi) {
                    if (puedeCancelar) {
                        return (
                            '<div class="schedule-card mb-3 clickable-card" role="button" tabindex="0" ' +
                            'onclick="cancelarReserva(' +
                            b.id_reserva +
                            ')" ' +
                            "onkeypress=\"if(event.key==='Enter')cancelarReserva(" +
                            b.id_reserva +
                            ')">' +
                            '<div class="d-flex justify-content-between align-items-center">' +
                            infoBloque +
                            "</div>" +
                            "</div>"
                        );
                    } else {
                        // Reservado pero fuera del tiempo límite de cancelación (< 1 hora)
                        return (
                            '<div class="schedule-card mb-3" style="opacity:.85; cursor: default;" title="Solo puedes cancelar hasta 1 hora antes">' +
                            '<div class="d-flex justify-content-between align-items-center">' +
                            infoBloque +
                            "</div>" +
                            "</div>"
                        );
                    }
                }

                // Tarjeta bloqueada por fecha/hora pasada
                if (esPasado) {
                    return (
                        '<div class="schedule-card mb-3" style="opacity:.5; cursor: not-allowed;">' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                        infoBloque +
                        "</div>" +
                        "</div>"
                    );
                }

                if (sinCupos) {
                    return (
                        '<div class="schedule-card mb-3" style="opacity:.6">' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                        infoBloque +
                        "</div>" +
                        "</div>"
                    );
                }

                return (
                    '<a href="#" class="text-decoration-none reservar-bloque" ' +
                    'data-id-bloque="' +
                    b.id_bloque +
                    '">' +
                    '<div class="schedule-card mb-3 clickable-card">' +
                    '<div class="d-flex justify-content-between align-items-center">' +
                    infoBloque +
                    "</div>" +
                    "</div>" +
                    "</a>"
                );
            })
            .join("");
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement("div");
        div.textContent = str;
        return div.innerHTML;
    }

    const swalBaseConfig = {
        buttonsStyling: false,
        didOpen: (popup) => {
            popup.style.maxHeight = "calc(100vh - 3rem)";
            popup.style.margin = "auto";
        }
    };

    window.cancelarReserva = function (idReserva) {
        if (!idReserva) return;
        Swal.fire({
            ...swalBaseConfig,
            title: "¿Cancelar reserva?",
            text: "¿Seguro que quieres cancelar esta clase?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, cancelar",
            cancelButtonText: "No, mantener",
            reverseButtons: true,
            customClass: {
                popup: "modal-content p-4 text-white",
                actions: "w-100 m-0 mt-3 d-flex gap-2",
                confirmButton: "btn btn-danger w-100",
                cancelButton: "btn btn-outline-secondary w-100"
            }
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch(baseUrl + "alumna/cancelarReserva", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_reserva=" + encodeURIComponent(idReserva),
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.success) {
                        Swal.fire({
                            ...swalBaseConfig,
                            title: "¡Reserva cancelada!",
                            text: data.mensaje || "La reserva se canceló correctamente.",
                            icon: "success",
                            confirmButtonText: "Aceptar",
                            customClass: {
                                popup: "modal-content p-4 text-white",
                                actions: "w-100 m-0 mt-3",
                                confirmButton: "btn btn-primary w-100"
                            }
                        }).then(function () {
                            cargarAgenda(true);
                        });
                    } else {
                        Swal.fire({
                            ...swalBaseConfig,
                            title: "No se pudo cancelar",
                            text: data.mensaje || "No se pudo cancelar la reserva.",
                            icon: "error",
                            confirmButtonText: "Aceptar",
                            customClass: {
                                popup: "modal-content p-4 text-white",
                                actions: "w-100 m-0 mt-3",
                                confirmButton: "btn btn-primary w-100"
                            }
                        });
                    }
                })
                .catch(function () {
                    Swal.fire({
                        ...swalBaseConfig,
                        title: "Error",
                        text: "Error de red al cancelar la reserva.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        customClass: {
                            popup: "modal-content p-4 text-white",
                            actions: "w-100 m-0 mt-3",
                            confirmButton: "btn btn-primary w-100"
                        }
                    });
                });
        });
    };

    document.addEventListener("click", function (event) {
        const tarjeta = event.target.closest(".reservar-bloque");
        if (!tarjeta) return;
        event.preventDefault();

        const idBloque = tarjeta.getAttribute("data-id-bloque");
        if (!idBloque) return;

        Swal.fire({
            ...swalBaseConfig,
            title: "¿Reservar esta clase?",
            text: "¿Estás seguro de que quieres inscribirte en esta clase?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sí, reservar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
            customClass: {
                popup: "modal-content p-4 text-white",
                actions: "w-100 m-0 mt-3 d-flex gap-2",
                confirmButton: "btn btn-primary w-100",
                cancelButton: "btn btn-outline-secondary w-100"
            }
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch(baseUrl + "alumna/crearReserva", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_bloque=" + encodeURIComponent(idBloque),
            })
                .then(function (res) { return res.json(); })
                .then(function (response) {
                    const esExitoso = (response.success === true) || (response.status === 'success');
                    const info = response.data || response;

                    if (esExitoso) {
                        const profesora = info.nombre || info.profesor_nombre || "—";

                        let fechaFormateada = "—";
                        if (info.fecha) {
                            const [yyyy, mm, dd] = info.fecha.split("-");
                            fechaFormateada = `${dd}-${mm}-${yyyy.slice(-2)}`;
                        }

                        const horaInicio = info.hora_inicio ? info.hora_inicio.substring(0, 5) : "";
                        const horaTermino = info.hora_termino ? info.hora_termino.substring(0, 5) : "";

                        const fechaHora = `${fechaFormateada} de ${horaInicio} a ${horaTermino}`;

                        Swal.fire({
                            ...swalBaseConfig,
                            title: "¡Te inscribiste correctamente!",
                            html: `Tu clase de <b class="text-white">Grupal</b> con <b class="text-white">${escapeHtml(profesora)}</b> quedó reservada para el <b class="text-white">${fechaHora}</b>.`,
                            icon: "success",
                            confirmButtonText: "Entendido",
                            customClass: {
                                popup: "modal-content p-4 text-white",
                                actions: "w-100 m-0 mt-3",
                                confirmButton: "btn btn-primary w-100"
                            }
                        }).then(() => {
                            cargarAgenda(true);
                        });
                    } else {
                        Swal.fire({
                            ...swalBaseConfig,
                            title: "No se pudo reservar",
                            text: response.mensaje || info.mensaje || "No se pudo realizar la reserva.",
                            icon: "error",
                            confirmButtonText: "Aceptar",
                            customClass: {
                                popup: "modal-content p-4 text-white",
                                actions: "w-100 m-0 mt-3",
                                confirmButton: "btn btn-primary w-100"
                            }
                        });
                    }
                })
                .catch(function () {
                    Swal.fire({
                        ...swalBaseConfig,
                        title: "Error",
                        text: "Error de red al intentar reservar la clase.",
                        icon: "error",
                        confirmButtonText: "Aceptar",
                        customClass: {
                            popup: "modal-content p-4 text-white",
                            actions: "w-100 m-0 mt-3",
                            confirmButton: "btn btn-primary w-100"
                        }
                    });
                });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const agendaTabBtn = document.getElementById("agenda-tab");
        const agendaPane = document.getElementById("agenda");

        if (agendaTabBtn) {
            agendaTabBtn.addEventListener("shown.bs.tab", function () {
                cargarAgenda(true);
            });
        }

        if (agendaPane && (agendaPane.classList.contains("active") || agendaPane.classList.contains("show"))) {
            cargarAgenda(false);
        }
    });
})();