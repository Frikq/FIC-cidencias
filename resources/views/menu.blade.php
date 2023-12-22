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

function tu($usuario){
    if($usuario == $_SESSION['Nombre_Usuario'] && $_SESSION['Rol'] != 'Reportante')
        return "<div class='tu'>(tú)</div>";
}

ComprobarAcceso();
//<div class="incidencia" id="0">Incendio<div class="urgencia urgencia-actual"></div></div>
?>

<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="{{asset('js/menu.js')}}"></script>
    
    <title>Menu Incidencias</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>
    <?php
    if($_SESSION['Rol']!='Reportante')
        echo "Estos son los reportes de los últimos 30 días.";
    ?>
    <div class = "cajaIncidencias">
        <?php
            if(isset($_SESSION['reportes'])){
                $reportes = $_SESSION['reportes'];
                echo '<div class="letreritos" id="L A1">          ⇓⇓Pendientes⇓⇓</div>';
                foreach($reportes as $reporte){
                    if($reporte->Estatus == 'Pendiente')
                    echo '<div class="incidencia" id="' . $reporte->Numero_Reporte . '">' . $reporte->Nombre_Reporte .tu($reporte->Nombre_Usuario).'<div class="urgencia ' . $reporte->Urgencia . '"></div></div>';
                }
                echo '<div class="letreritos" id="L A2">          ⇓⇓Reportes Revisados⇓⇓</div>';
                foreach($reportes as $reporte){
                    if($reporte->Estatus == 'Revisado')
                    echo '<div class="incidencia" id="' . $reporte->Numero_Reporte . '">' . $reporte->Nombre_Reporte .tu($reporte->Nombre_Usuario).'<div class="urgencia ' . $reporte->Urgencia . '"></div></div>';
                }
                echo '<div class="letreritos" id="L A3">          ⇓⇓Reportes Rechazados⇓⇓</div>';
                foreach($reportes as $reporte){
                    if($reporte->Estatus == 'Rechazado')
                    echo '<div class="incidencia" id="' . $reporte->Numero_Reporte . '">' . $reporte->Nombre_Reporte .tu($reporte->Nombre_Usuario).'<div class="urgencia ' . $reporte->Urgencia . '"></div></div>';
                }
                echo '<div class="letreritos" id="L A4">          ⇓⇓Reportes Resueltos⇓⇓</div>';
                foreach($reportes as $reporte){
                    if($reporte->Estatus == 'Resuelto')
                    echo '<div class="incidencia" id="' . $reporte->Numero_Reporte . '">' . $reporte->Nombre_Reporte .tu($reporte->Nombre_Usuario).'<div class="urgencia ' . $reporte->Urgencia . '"></div></div>';
                }

            }else{
                echo '<div class="letreritos" id="L A5">          Aún no has hecho ningún reporte.</div>';
            }
        ?>
    </div>
    <div id = "botonesMenu">
        <a href="{{ route('roles') }}"><button type="submit" id="botonRoles">Asignar Roles</button></a>
        <form method="POST" action="{{ route('cerrar') }}">
            @csrf
            <input type="hidden" name="accion" value="cerrarSesion">
            <button type="submit" id="botonCerrar">Cerrar Sesión</button>
        </form>
        <form method="POST" action="{{ route('reporte') }}">
            @csrf
            <input type="hidden" name="accion" value="nuevaIncidencia">
            <button type="submit" id="botonReporte">Nueva Incidencia</button>
        </form>
    </div>
</body>

<div class="escondido" id = "rol">{{$_SESSION['Rol']}}</div>
</html>