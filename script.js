const galeria = document.getElementById("galeria");

function subir(){

    const nombre = document.getElementById("nombre").value;
    const edad = document.getElementById("edad").value;
    const fecha = document.getElementById("fecha").value;
    const archivo = document.getElementById("foto").files[0];

    if(!archivo){
        alert("Selecciona una foto");
        return;
    }

    const lector = new FileReader();
    lector.onload = function(e){
        const tarjeta = document.createElement("div");
        tarjeta.innerHTML = `
            <img src="${e.target.result}">
            <h2>${nombre}</h2>
            <p>Edad: ${edad}</p>
            <p>Desapareció: ${fecha}</p>

            <button onclick="this.parentElement.remove()">
            Eliminar
            </button>
        `;
        galeria.appendChild(tarjeta);
    }
    lector.readAsDataURL(archivo);
}