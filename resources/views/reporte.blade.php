<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
function ComprobarAcceso(){
    if (!isset($_SESSION['Nombre_Usuario'])) {
        CerrarSesion();
    }
}

function CerrarSesion(){
    session_destroy();
    header('Location: /login'); 
    exit();
}
ComprobarAcceso();


$Titulo = '';
$Urgencia = 'Baja';
$Tipo = 'Aparatos Electrónicos';
$Lugar = 'Áreas administraticas';
$Descripcion = '';
$Estatus = 'redactando';
$Rol = 'Reportante';
$Reportante = '';

if(isset($incidencia)){
    $Titulo = $_SESSION['Nombre_Reporte'];
    $Urgencia = $_SESSION['Urgencia'];
    $Tipo = $_SESSION['Tipo_Incidencia'];
    $Lugar = $_SESSION['Lugar_Incidencia'];
    $Descripcion = $_SESSION['Descripcion_Incidencia'];
    $Estatus = $_SESSION['Estatus'];
    $Rol = $_SESSION['Rol'];
    $Reportante = $_SESSION['Reportante'];
}

$Incidencia = [
    'Nombre_Reporte' => $Titulo,
    'Urgencia' => $Urgencia,
    'Tipo_Incidencia' => $Tipo,
    'Lugar_Incidencia' => $Lugar,
    'Descripcion_Incidencia' => $Descripcion,
    'Estatus' => $Estatus,
    'Rol' => $_SESSION['Rol'],
    'Reportante' => $Reportante,
];
?>

<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/reporte.css')}}">
    <script src="{{asset('js/reporte.js')}}"></script>
    <title>Reportar incidencia</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
    <div class = "Reporte">
        <form method="POST" action="{{ route('reportar') }}">
        @csrf
        <div id="cajaTexto">
            <label for="Nombre_Reporte">Titulo de incidencia:</label>
            <input type="text" maxlength="25" minlength="5" name="Nombre_Reporte" id="titulo" placeholder="Título de incidencia." required>
            
            <label for="Urgencia">Nivel de urgencia:</label>
            <select name="Urgencia" value="{{$Urgencia}}" id="urgencia">
                <option value="Baja">Baja</option>
                <option value="Moderada">Moderada</option>
                <option value="Urgente">Urgente</option>
                <option value="Actual">Actual</option>
            </select>

            <label for="Status" id="statusLabel">Estado:</label>
            <select name="Status" id="status">
                <option value="Revisado">Revisado</option>
                <option value="Rechazado">Rechazado</option>
                <option value="Resuelto">Resuelto</option>
            </select>

            <label for="Tipo_Incidencia">Tipo de incidencia:</label>
            <select name="Tipo_Incidencia" id="tipo">
                <option value="Aparatos Electrónicos">Aparatos Electrónicos</option>
                <option value="Cableado">Cableado</option>
                <option value="Comportamiento">Comportamiento</option>
                <option value="Iluminación">Iluminación</option>
                <option value="Limpieza">Limpieza</option>
                <option value="Mantenimiento">Mantenimiento</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Sonido">Sonido</option>
                <option value="Suministro Eléctrico">Suministro Eléctrico</option>
                <option value="Ventilación">Ventilación</option>
            </select>

            <label for="Lugar_Incidencia">Lugar de incidencia:</label>
            <select name="Lugar_Incidencia" id="lugar">
                <option value="Áreas administrativas">Áreas administrativas</option>
                <option value="Aula de capacitación A">Aula de capacitación A</option>
                <option value="Aula de capacitación B">Aula de capacitación B</option>
                <option value="Aulas">Aulas</option>
                <option value="Auditorio">Auditorio</option>
                <option value="Baños">Baños</option>
                <option value="Centro de cómputo">Centro de cómputo</option>
                <option value="Cubículo de maestros">Cubículo de maestros</option>
                <option value="Dirección">Dirección</option>
                <option value="Posgrado">Posgrado</option>
                <option value="Sala audiovisual">Sala audiovisual</option>
                <option value="Servicios profesionales">Servicios profesionales</option>
                <option value="Taller de arquitectura">Taller de arquitectura</option>
                <option value="Taller de redes">Taller de redes</option>
            </select>
            
            <label for="Descripcion_Incidencia">Descripción:</label>
            <textarea maxlength="500" minlength="10" rows="10" cols="50" id="descripcion" name="Descripcion_Incidencia" placeholder="Descripción de incidencia." required></textarea>
            <div class = "botones">
                <input type="submit" onclick="habilitar()" value="Enviar" id="Enviar"/>
            </div>
            
        </div>
    </form>

    <a href="{{ route('menu') }}"><input type="submit" value="Volver" id="Volver"/></a>

    <form method="POST" action="{{ route('eliminar') }}">
    @csrf
        <input type="submit" value="Eliminar" id="Eliminar"/>
    </form>

    <h4 name="Estatus" id="estatus">estatus: </h4>
    <h4 name="Reportante" id="reportante"></h4>
    </div>
    <div id="escondido">{{implode('(|)', $Incidencia)}}</div>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>