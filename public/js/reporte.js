var incidencia;

function cargarIncidencia() {
    document.getElementById("titulo").value = incidencia['Nombre_Reporte'];
    document.getElementById("urgencia").value = incidencia['Urgencia'];
    document.getElementById("tipo").value = incidencia['Tipo_Incidencia'];
    document.getElementById("lugar").value = incidencia['Lugar_Incidencia'];
    document.getElementById("descripcion").value = incidencia['Descripcion_Incidencia'];
    document.getElementById("estatus").innerHTML = "estatus: " + incidencia['Estatus'];
    if(incidencia['Rol']!='Reportante')
        document.getElementById("reportante").innerHTML = "reportado por: " + incidencia['Reportante'];
}

function apagar(onoff){
    var elementosDeshabilitar = document.querySelectorAll("#cajaTexto input, #cajaTexto select, #cajaTexto textarea");
    for (var i = 0; i < elementosDeshabilitar.length; i++)
            elementosDeshabilitar[i].disabled = onoff;
    
    if(incidencia['Rol']!='Reportante')
        document.getElementById('status').disabled = false;

    if(incidencia['Rol']!='Reportante')
        document.getElementById('Enviar').disabled = false;
}

function habilitar(){
    var elementosDeshabilitar = document.querySelectorAll("#cajaTexto input, #cajaTexto select, #cajaTexto textarea");
    for (var i = 0; i < elementosDeshabilitar.length; i++)
        elementosDeshabilitar[i].disabled = false;
}

function cargarEstado() {
    if(incidencia['Rol'] == "Reportante"){
        document.getElementById("status").remove();
        document.getElementById("statusLabel").remove();
        switch (incidencia['Estatus']){
            case "Redactando":
                document.getElementById("Eliminar").remove();
            break;
            case "Pendiente":
                cargarIncidencia();
                document.getElementById("Enviar").value="Guardar";
            break;
            case "Revisado":
                cargarIncidencia();
                document.getElementById("Eliminar").disabled = true;
            break;
            case "Rechazado":
            case "Resuelto":
                cargarIncidencia();
                apagar(true);
                document.getElementById("Eliminar").remove();
            break;
            default:
                break;
        }
    }else if(incidencia['Rol'] == "Administrador"){
        switch (incidencia['Estatus']){
            case "Redactando":
                document.getElementById("Eliminar").remove();
            break;
            case "Pendiente":
                cargarIncidencia();
                document.getElementById("Enviar").value="Confirmar";
            break;
            case "Revisado":
                cargarIncidencia();
            break;    
            case "Rechazado":
            case "Resuelto":
                cargarIncidencia();
                apagar(true);
            break;
            default:
            break;
        }
    }else if(incidencia['Rol'] == "Intendencia" || incidencia['Rol'] == "Mantenimiento" || incidencia['Rol'] == "Electricista" || incidencia['Rol'] == "Administrativo"){
        switch (incidencia['Estatus']){
            case "Redactando":
                document.getElementById("Eliminar").remove();
            break;
            case "Pendiente":
                cargarIncidencia();
                document.getElementById("Enviar").value="Confirmar";
            break;
            case "Revisado":
                cargarIncidencia();
            break;    
            case "Rechazado":
            case "Resuelto":
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
    var partes = document.getElementById("escondido").innerText.split('(|)');

    // Crear un objeto en JavaScript
    incidencia = {
        'Nombre_Reporte': partes[0],
        'Urgencia': partes[1],
        'Tipo_Incidencia': partes[2],
        'Lugar_Incidencia': partes[3],
        'Descripcion_Incidencia': partes[4],
        'Estatus': partes[5],
        'Rol': partes[6],
        'Reportante': partes[7]
    };

    if(incidencia['Rol'] == 'Baja')
        window.location.href = "/menu";

    if(partes.length>3)
        cargarEstado();
});