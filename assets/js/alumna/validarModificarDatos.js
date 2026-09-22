const swalBaseConfig = {
    buttonsStyling: false,

    didOpen: (popup) => {
        popup.style.maxHeight = "calc(100vh - 3rem)";
        popup.style.margin = "auto";
    }
};

document.addEventListener("DOMContentLoaded", function () {

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


    formulario.addEventListener("submit", async function (e) {
        // Ahora SIEMPRE detenemos el envío normal
        e.preventDefault();

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


        // Si existen errores frontend
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
                    popup: "modal-content p-4 text-white",
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

            const respuesta = await fetch(formulario.action, {
                method: "POST",
                body: datosFormulario
            });

            const data = await respuesta.json();


            // Si el backend devuelve un error
            if (!respuesta.ok || !data.success) {

                Swal.fire({
                    ...swalBaseConfig,

                    icon: "error",
                    title: "No se pudieron actualizar los datos",

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
                        popup: "modal-content p-4 text-white",
                        actions: "w-100 m-0 mt-3",
                        confirmButton: "btn btn-primary w-100"
                    }
                });

                return;
            }


            // Si se actualizó correctamente
            Swal.fire({
                ...swalBaseConfig,

                title: "¡Datos actualizados!",
                text: data.mensaje,
                icon: "success",
                confirmButtonText: "Entendido",

                customClass: {
                    popup: "modal-content p-4 text-white",
                    actions: "w-100 m-0 mt-3",
                    confirmButton: "btn btn-primary w-100"
                }
            });


        } catch (error) {

            console.error(error);

            Swal.fire({
                ...swalBaseConfig,

                title: "Error",
                text: "Ocurrió un error al comunicarse con el servidor.",
                icon: "error",
                confirmButtonText: "Aceptar",

                customClass: {
                    popup: "modal-content p-4 text-white",
                    actions: "w-100 m-0 mt-3",
                    confirmButton: "btn btn-primary w-100"
                }
            });
        }

    });

});