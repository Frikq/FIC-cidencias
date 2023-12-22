<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
function ComprobarAcceso(){
    if (isset($_SESSION['Nombre_Usuario'])) {
        header('Location: /menu'); 
    }
}

ComprobarAcceso();

if($_SESSION['Rol'] != 'Administrador')
    header('Location: /menu'); ;
?>

<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('css/roles.css')}}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/login.js') }}"></script>
    
    <title>FIC-cidencias</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>

    <div class="AsignarRoles">
            <div id="encabezado">
                <h1>FIC-cidencias</h1>
                <img src="img/logo.png" alt="Logo" id="logo">
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="post">
               @csrf 
                <div id="cajaTexto">
                    <label for="usuarioCorreo">Correo Institucional:</label>
                    <input type="text" minlength="18" maxlength="50" class="form-control" id="usuarioCorreo" placeholder="correo de electrónico" name="Correo_Institucional" required>
                    <button type="submit" name="accion" value="buscar"class="btn btn-primary">Buscar Usuario</button>
                </div>
                <label for="Rol">Rol asignado:</label>
                <select name="Rol" id="Rol">
                    <option value="Reportante">Reportante</option>
                    <option value="Administrativo">Administrativo</option>
                    <option value="Intendencia">Intendencia</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Electricista">Electricista</option>
                    <option value="Baja">Baja</option>
                    </select>
                <div id="rolesBotones">
                    <button type="submit" name="accion" value="asignar" class="btn btn-primary">Asignar Rol</button>
                </div>
            </form>
            <a href="{{ route('menu') }}"><button class="btn btn-primary">Volver</button></a>
    </div>
</body>
</html>