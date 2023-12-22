var estado, bloqueado = false, titulo = "NOMBRE", urgencia = "Baja", tipo = "Cableado", lugar = "Auditorio", descripcion = "No sé, algo pasó. Vengan a ver que rollo.";
var usuarioRol = "reportante", estatus = "resuelto";
function cargarIncidencia() {
    document.getElementById("titulo").value = titulo;
    document.getElementById("urgencia").value = urgencia;
    document.getElementById("tipo").value = tipo;
    document.getElementById("lugar").value = lugar;
    document.getElementById("descripcion").value = descripcion;
    document.getElementById("estatus").innerHTML = "estatus: " + estatus;
}

function apagar(onoff){
    var elementosDeshabilitar = document.querySelectorAll("#cajaTexto input, #cajaTexto select, #cajaTexto textarea");
    for (var i = 0; i < elementosDeshabilitar.length; i++)
        elementosDeshabilitar[i].disabled = onoff;
}

function cargarEstado() {
    if(usuarioRol == "reportante"){
        switch (estatus) {
            case "redactando":
                document.getElementById("Eliminar").remove();
            break;
            case "pendiente":
                cargarIncidencia();
            break;
            case "revisado":
                cargarIncidencia();
                apagar(true);
                document.getElementById("Eliminar").disabled = true;
            break;
            case "resuelto":
                cargarIncidencia();
                apagar(true);
                document.getElementById("Eliminar").remove();
            break;
            default:
                break;
        }
    }
}




document.addEventListener("DOMContentLoaded", function () {
    cargarEstado();
});