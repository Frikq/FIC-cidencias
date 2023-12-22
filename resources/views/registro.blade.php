<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('css/registro.css')}}" rel="stylesheet" type="text/css">
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
            <form method="POST">
                 @csrf
                <div id="cajaTexto">
                    <label for="usuarioCorreo">Correo Institucional:</label>
                    <input type="text" minlength="18" maxlength="50" class="form-control" id="usuarioCorreo" placeholder="correo de electrónico" name="Correo_Institucional" required>
                    <label for="usuarioNombre">Usuario:</label>
                    <input type="text" minlength="4" maxlength="20" class="form-control" id="usuarioNombre" placeholder="nombre de usuario" name="Nombre_Usuario" required>
                    <label for="usuarioContrasena">Contraseña:</label>
                    <input type="password" minlength="8" maxlength="20" class="form-control" id="usuarioContrasena" placeholder="contraseña" name="Contrasena" required>
                    <label for="ConUsuarioContrasena">Confirmar contraseña:</label>
                    <input type="password" minlength="8" maxlength="20" class="form-control" id="ConUsuarioContrasena" placeholder="contraseña" name="ConContrasena" required>
                </div>

                <div id="inicioBotones">
                    <button onclick="Inicio()">Iniciar Sesión</button>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </div>
            </form>
    </div>
</body>
</html>