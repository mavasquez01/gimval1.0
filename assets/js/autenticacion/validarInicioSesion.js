document.addEventListener("DOMContentLoaded", function () {
    const mensajeLogin = document.getElementById("mensajeLogin");
    // Correo o contraseña incorrecta
    if (mensajeLogin) {

    const icon = mensajeLogin.dataset.icon;
    const title = mensajeLogin.dataset.title;
    const text = mensajeLogin.dataset.text;

    if (title !== "") {

        Swal.fire({
            icon: icon,
            title: title,
            text: text
        });

    }
}

    const formulario = document.getElementById("formularioInicioSesion");
    const inputCorreo = document.getElementById("correo");
    const inputContrasena = document.getElementById("contrasena");

    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const regexContrasena = /^.{6,}$/;

    formulario.addEventListener("submit", function (event) {

        const correo = inputCorreo.value.trim();
        const contrasena = inputContrasena.value;

        // Validar campos vacíos
        if (correo === "" || contrasena === "") {

            event.preventDefault();

            Swal.fire({
                icon: "warning",
                title: "Campos incompletos",
                text: "Debes ingresar tu correo y contraseña."
            });

            return;
        }

        // Validar formato del correo
        if (!regexCorreo.test(correo)) {

            event.preventDefault();

            Swal.fire({
                icon: "error",
                title: "Correo inválido",
                text: "Ingresa un correo electrónico válido."
            });

            return;
        }

        // Validar contraseña
        if (!regexContrasena.test(contrasena)) {

            event.preventDefault();

            Swal.fire({
                icon: "error",
                title: "Contraseña inválida",
                text: "La contraseña debe tener al menos 6 caracteres."
            });

            return;
        }

    });

});