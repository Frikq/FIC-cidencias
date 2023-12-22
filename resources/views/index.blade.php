<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
function ComprobarAcceso(){
    if (isset($_SESSION['Nombre_Usuario'])) {
        header('Location: /menu'); 
    }
}

ComprobarAcceso();
?>

<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('css/index.css')}}" rel="stylesheet" type="text/css">
    <script src="{{ asset('js/login.js') }}"></script>
    
    <title>FIC-cidencias</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>

    <div class="InicioSesion">
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
                    
                    <label for="usuarioNombre">Usuario:</label>
                    <input type="text" minlength="4" maxlength="20" class="form-control" id="usuarioNombre" placeholder="nombre de usuario" name="Nombre_Usuario" required>
                    <label for="usuarioContrasena">Contraseña:</label>
                    <input type="password" minlength="8" maxlength="20" class="form-control" id="usuarioContrasena" placeholder="contraseña" name="Contrasena" required>

                </div>

                <div id="inicioBotones">
                    <button type="submit" name="iniciar"class="btn btn-primary">Iniciar Sesión</button>
                    <button type="button" onclick="window.location.href='/registro'">Registrar</button>
                </div>
            </form>
        <div id="olvideContrasena">
            Olvidé mi contraseña
        </div>
    </div>
</body>
</html>