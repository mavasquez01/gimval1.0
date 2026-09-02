document.addEventListener("DOMContentLoaded", function () {

    // Mostrar alertas que vienen del backend
    if (typeof alertaContrasenaBackend !== "undefined" && alertaContrasenaBackend) {

        Swal.fire({
            icon: alertaContrasenaBackend.icon,
            title: alertaContrasenaBackend.title,

            html: alertaContrasenaBackend.text
                .split(/\r?\n/)
                .filter(error => error.trim() !== "")
                .map(error =>
                    `<div style="text-align:left; margin-bottom:8px;">${error}</div>`
                )
                .join(""),

            background: "#0d0d0d",
            color: "#ffffff",
            confirmButtonText: "ENTENDIDO",
            width: "360px",

            customClass: {
                popup: "valkyria-alert",
                title: "valkyria-alert-title",
                confirmButton: "valkyria-alert-button"
            }
        });
    }


    const formulario = document.getElementById("formCambiarContrasena");

    if (!formulario) {
        return;
    }


    formulario.addEventListener("submit", function (event) {

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
            errores.push("Debes ingresar tu contraseña actual.");
        }


        // Nueva contraseña
        if (nuevaContrasena === "") {

            errores.push("Debes ingresar una nueva contraseña.");

        } else if (nuevaContrasena.length < 8) {

            errores.push(
                "La nueva contraseña debe tener al menos 8 caracteres."
            );
        }


        // Confirmar contraseña
        if (confirmarContrasena === "") {

            errores.push("Debes confirmar la nueva contraseña.");

        } else if (nuevaContrasena !== confirmarContrasena) {

            errores.push("Las contraseñas no coinciden.");
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


        // Si existen errores, detenemos el submit
        if (errores.length > 0) {

            event.preventDefault();

            const listaErrores = errores
                .map(error =>
                    `<div style="text-align:left; margin-bottom:8px;">${error}</div>`
                )
                .join("");

            Swal.fire({
                icon: "warning",
                title: "Revisa tus datos",
                html: listaErrores,
                background: "#0d0d0d",
                color: "#ffffff",
                confirmButtonText: "ENTENDIDO",
                width: "360px",

                customClass: {
                    popup: "valkyria-alert",
                    title: "valkyria-alert-title",
                    confirmButton: "valkyria-alert-button"
                }
            });

            return;
        }

    });

});