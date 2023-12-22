$(document).ready(function() {
    $(".incidencia").click(function() {
        
        window.location.href = '/reporte/' + $(this).attr("id");
    });
});


document.addEventListener("DOMContentLoaded", function () {
    var rol = document.getElementById('rol');
    if(rol.innerHTML != 'Administrador'){
        document.getElementById('botonRoles').remove();
        rol.remove();
    }

    if(rol.innerHTML == 'Baja')
        document.getElementById('botonReporte').remove();
    
});