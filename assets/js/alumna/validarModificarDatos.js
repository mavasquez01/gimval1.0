document.addEventListener("DOMContentLoaded", function () {

    // ALERTAS DEL BACKEND
    if (typeof alertaDatosBackend !== "undefined" && alertaDatosBackend) {

        Swal.fire({
            icon: alertaDatosBackend.icon,
            title: alertaDatosBackend.title,

            html: alertaDatosBackend.text
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


    // VALIDACIÓN FRONTEND

    const formulario = document.getElementById("formModificarDatos");

    if (!formulario) {
        return;
    }

    const nombre = document.getElementById("nombre");
    const apellido = document.getElementById("apellido");
    const correo = document.getElementById("correo");
    const telefono = document.getElementById("telefono");
    const fechaNacimiento = document.getElementById("fechaNacimiento");

    const regexNombre = /^[A-Za-zÁÉÍÓÚáéíóúÑñÜü ]+$/;
    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexTelefono = /^\+?[0-9]{8,15}$/;


    formulario.addEventListener("submit", function (e) {

        const errores = [];

        // Nombre
        if (nombre.value.trim() === "") {
            errores.push("Debes ingresar tu nombre.");

        } else if (nombre.value.trim().length < 2) {
            errores.push("El nombre debe tener al menos 2 caracteres.");

        } else if (nombre.value.trim().length > 60) {
            errores.push("El nombre no puede superar los 60 caracteres.");

        } else if (!regexNombre.test(nombre.value.trim())) {
            errores.push("El nombre solo puede contener letras y espacios.");
        }


        // Apellido
        if (apellido.value.trim() === "") {
            errores.push("Debes ingresar tu apellido.");

        } else if (apellido.value.trim().length < 2) {
            errores.push("El apellido debe tener al menos 2 caracteres.");

        } else if (apellido.value.trim().length > 60) {
            errores.push("El apellido no puede superar los 60 caracteres.");

        } else if (!regexNombre.test(apellido.value.trim())) {
            errores.push("El apellido solo puede contener letras y espacios.");
        }


        // Correo
        if (correo.value.trim() === "") {
            errores.push("Debes ingresar tu correo electrónico.");

        } else if (!regexCorreo.test(correo.value.trim())) {
            errores.push("Debes ingresar un correo electrónico válido.");
        }


        // Teléfono
        if (telefono.value.trim() === "") {
            errores.push("Debes ingresar tu teléfono.");

        } else if (!regexTelefono.test(telefono.value.trim())) {
            errores.push("El teléfono debe contener entre 8 y 15 números.");
        }


        // Fecha nacimiento
        if (fechaNacimiento.value === "") {

            errores.push("Debes ingresar tu fecha de nacimiento.");

        } else {

            const fechaIngresada = new Date(
                fechaNacimiento.value + "T00:00:00"
            );

            const hoy = new Date();

            if (fechaIngresada > hoy) {

                errores.push(
                    "La fecha de nacimiento no puede ser futura."
                );

            } else {

                let edad =
                    hoy.getFullYear() - fechaIngresada.getFullYear();

                const diferenciaMes =
                    hoy.getMonth() - fechaIngresada.getMonth();

                if (
                    diferenciaMes < 0 ||
                    (
                        diferenciaMes === 0 &&
                        hoy.getDate() < fechaIngresada.getDate()
                    )
                ) {
                    edad--;
                }

                if (edad < 14 || edad > 100) {
                    errores.push(
                        "La fecha de nacimiento ingresada no es válida."
                    );
                }
            }
        }


        // Si hay errores detenemos el formulario
        if (errores.length > 0) {

            e.preventDefault();

            Swal.fire({
                icon: "warning",
                title: "Revisa tus datos",

                html: errores
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

    });

});