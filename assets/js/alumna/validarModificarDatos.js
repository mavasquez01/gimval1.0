document.addEventListener("DOMContentLoaded", function () {

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

});