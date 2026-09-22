document.addEventListener("DOMContentLoaded", function () {

    const formulario = document.getElementById("formCambiarContrasena");

    if (!formulario) {
        return;
    }


    // Mismo estilo de SweetAlert que Agenda
    const swalBaseConfig = {
        buttonsStyling: false,

        didOpen: (popup) => {
            popup.style.maxHeight = "calc(100vh - 3rem)";
            popup.style.margin = "auto";
        }
    };


    formulario.addEventListener("submit", async function (event) {

        // Siempre detenemos el envío tradicional
        event.preventDefault();

        const errores = [];

        const contrasenaActual = document
            .getElementById("contrasena_actual")
            .value;

        const nuevaContrasena = document
            .getElementById("nueva_contrasena")
            .value;

        const confirmarContrasena = document
            .getElementById("confirmar_contrasena")
            .value;


        // Contraseña actual
        if (contrasenaActual === "") {

            errores.push(
                "Debes ingresar tu contraseña actual."
            );
        }


        // Nueva contraseña
        if (nuevaContrasena === "") {

            errores.push(
                "Debes ingresar una nueva contraseña."
            );

        } else if (nuevaContrasena.length < 8) {

            errores.push(
                "La nueva contraseña debe tener al menos 8 caracteres."
            );
        }


        // Confirmar contraseña
        if (confirmarContrasena === "") {

            errores.push(
                "Debes confirmar la nueva contraseña."
            );

        } else if (nuevaContrasena !== confirmarContrasena) {

            errores.push(
                "Las contraseñas no coinciden."
            );
        }


        // Evitar usar la misma contraseña
        if (
            contrasenaActual !== "" &&
            nuevaContrasena !== "" &&
            contrasenaActual === nuevaContrasena
        ) {

            errores.push(
                "La nueva contraseña no puede ser igual a la actual."
            );
        }


        // Errores frontend
        if (errores.length > 0) {

            Swal.fire({
                ...swalBaseConfig,

                icon: "warning",
                title: "Revisa tus datos",

                html: errores
                    .map(error =>
                        `<div style="text-align:left; margin-bottom:8px;">
                            ${error}
                        </div>`
                    )
                    .join(""),

                confirmButtonText: "Aceptar",

                customClass: {
                    popup: "modal-content p-4 text-white swal-cambiar-contrasena",
                    actions: "w-100 m-0 mt-3",
                    confirmButton: "btn btn-primary w-100"
                }
            });

            return;
        }


        // ==========================
        // AJAX
        // ==========================

        const datosFormulario = new FormData(formulario);

        try {

            const respuesta = await fetch(
                formulario.action,
                {
                    method: "POST",
                    body: datosFormulario
                }
            );

            const data = await respuesta.json();


            // Error devuelto por el backend
            if (!respuesta.ok || !data.success) {

                Swal.fire({
                    ...swalBaseConfig,

                    icon: "error",
                    title: "No se pudo cambiar la contraseña",

                    html: data.mensaje
                        .split(/\r?\n/)
                        .filter(error => error.trim() !== "")
                        .map(error =>
                            `<div style="text-align:left; margin-bottom:8px;">
                                ${error}
                            </div>`
                        )
                        .join(""),

                    confirmButtonText: "Aceptar",

                    customClass: {
                        popup: "modal-content p-4 text-white swal-cambiar-contrasena",
                        actions: "w-100 m-0 mt-3",
                        confirmButton: "btn btn-primary w-100"
                    }
                });

                return;
            }


            // Todo salió correctamente
            await Swal.fire({
                ...swalBaseConfig,

                icon: "success",
                title: "¡Contraseña actualizada!",
                text: data.mensaje,
                confirmButtonText: "Entendido",

                customClass: {
                    popup: "modal-content p-4 text-white swal-cambiar-contrasena",
                    actions: "w-100 m-0 mt-3",
                    confirmButton: "btn btn-primary w-100"
                }
            });


            // Limpiamos los campos después de cambiarla
            formulario.reset();


        } catch (error) {

            console.error(
                "Error al cambiar contraseña:",
                error
            );

            Swal.fire({
                ...swalBaseConfig,

                icon: "error",
                title: "Error",
                text: "Ocurrió un error al comunicarse con el servidor.",
                confirmButtonText: "Aceptar",

                customClass: {
                    popup: "modal-content p-4 text-white swal-cambiar-contrasena",
                    actions: "w-100 m-0 mt-3",
                    confirmButton: "btn btn-primary w-100"
                }
            });

        }

    });

});