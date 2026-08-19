document.addEventListener("DOMContentLoaded", function () {
    const formularioInicioSesion = document.getElementById("formularioInicioSesion");
    const inputRut = document.getElementById("rut");
    const inputContrasena = document.getElementById("contrasena");

    formularioInicioSesion.addEventListener("submit", function (event) {
        event.preventDefault();

        const rut = inputRut.value.trim();
        const pass = inputContrasena.value.trim();

        let pagina = "";
        let rol = "";

        if (rut === "1234-5" && pass === "12345") {
            rol = "Administrador";
            pagina = "../administrador/panelAdmin.html";

        } else if (rut === "12345-6" && pass === "12345") {
            rol = "Alumno";
            pagina = "../alumna/panelAlumna.html"; 

        } else if (rut === "123456-7" && pass === "12345") {
            rol = "Profesor";
            pagina = "../profesor/panelProfesor_integrado.html";
        }

        if (rol !== "" && pagina !== "") {
            window.location.href = pagina;
        } else {
            alert("RUT o contraseña incorrectos");
        }
    });
});