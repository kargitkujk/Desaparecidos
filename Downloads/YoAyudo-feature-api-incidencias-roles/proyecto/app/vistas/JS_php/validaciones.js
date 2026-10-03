document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formIncidencia");

    form.addEventListener("submit", function (e) {

        let errores = [];

        const laboratorio = form.laboratorio.value;
        const tipo = form.tipo.value;
        const descripcion = form.descripcion.value.trim();

        if (laboratorio === "") {
            errores.push("Debes seleccionar un laboratorio.");
        }

        if (tipo === "") {
            errores.push("Debes seleccionar un tipo de problema.");
        }

        if (descripcion.length < 10) {
            errores.push("La descripción debe tener al menos 10 caracteres.");
        }

        if (errores.length > 0) {
            e.preventDefault();

            alert("Errores:\n\n" + errores.join("\n"));
        }

    });

});