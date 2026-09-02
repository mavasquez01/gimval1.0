document.addEventListener("DOMContentLoaded", function () {


    if (alertaPerfilBackend) {

        Swal.fire({
            title: alertaPerfilBackend.title,
            html: alertaPerfilBackend.text
                .split(/\r?\n/)
                .filter(error => error.trim() !== "")
                .map(error => `<div style="text-align:left; margin-bottom:12px;">${error}</div>`)
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

    const formulario = document.getElementById("formularioPerfil");

    if (!formulario) {
        return;
    }

    function validarRut(rut) {

        rut = rut
            .replace(/\./g, "")
            .replace(/-/g, "")
            .toUpperCase();

        if (rut.length < 2) {
            return false;
        }

        const dv = rut.slice(-1);
        const numero = rut.slice(0, -1);

        if (!/^\d+$/.test(numero)) {
            return false;
        }

        let suma = 0;
        let multiplicador = 2;

        for (let i = numero.length - 1; i >= 0; i--) {

            suma += parseInt(numero[i]) * multiplicador;

            multiplicador++;

            if (multiplicador > 7) {
                multiplicador = 2;
            }
        }

        const resto = 11 - (suma % 11);

        let dvCalculado;

        if (resto === 11) {
            dvCalculado = "0";
        } else if (resto === 10) {
            dvCalculado = "K";
        } else {
            dvCalculado = resto.toString();
        }

        return dv === dvCalculado;
    }


    formulario.addEventListener("submit", function (event) {

        const errores = [];

        const rut = document.getElementById("rut").value.trim();
        const nombre = document.getElementById("nombre").value.trim();
        const apellido = document.getElementById("apellido").value.trim();
        const fechaNacimiento = document.getElementById("fecha_nacimiento").value;
        const telefono = document.getElementById("telefono").value.trim();


        // -------------------------
        // VALIDAR RUT
        // -------------------------

        if (rut === "") {

            errores.push("Debes ingresar tu RUT.");

        } else if (!validarRut(rut)) {

            errores.push("El RUT ingresado no es válido.");

        }


        // -------------------------
        // VALIDAR NOMBRE
        // -------------------------

        if (nombre === "") {

            errores.push("Debes ingresar tu nombre.");

        } else if (nombre.length < 2) {

            errores.push("El nombre debe tener al menos 2 caracteres.");

        }


        // -------------------------
        // VALIDAR APELLIDO
        // -------------------------

        if (apellido === "") {

            errores.push("Debes ingresar tu apellido.");

        } else if (apellido.length < 2) {

            errores.push("El apellido debe tener al menos 2 caracteres.");

        }


        // -------------------------
        // VALIDAR FECHA NACIMIENTO
        // -------------------------

        if (fechaNacimiento === "") {

            errores.push("Debes ingresar tu fecha de nacimiento.");

        }


        // -------------------------
        // VALIDAR TELÉFONO
        // -------------------------

        if (telefono === "") {

            errores.push("Debes ingresar tu teléfono.");

        } else {

            const regexTelefono = /^\+?[0-9]{8,15}$/;

            if (!regexTelefono.test(telefono)) {
                errores.push(
                    "El teléfono debe contener entre 8 y 15 números."
                );
            }
        }


        // -------------------------
        // MOSTRAR ERRORES
        // -------------------------

        if (errores.length > 0) {

            event.preventDefault();

            let listaErrores = "<ul style='text-align:left; margin:0; padding-left:20px;'>";

            errores.forEach(function (error) {

                listaErrores += `<li>${error}</li>`;

            });

            listaErrores += "</ul>";


            Swal.fire({

                title: "Revisa tus datos",

                html: listaErrores,

                icon: "warning",

                background: "#0d0d0d",

                color: "#ffffff",

                confirmButtonText: "ENTENDIDO",

                confirmButtonColor: "#ff0f7b",

                width: "360px",

                padding: "1.5rem",

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