<!DOCTYPE html>
<html lang="es-mx">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/css/index.css">
    <script src="js/login.js"></script>
    <title>FIC-cidencias</title>
    <link rel="icon" href="img/logo.png" type="image/png">
</head>
<body>

    <div class="InicioSesion">
            <div id="encabezado">
                <h1>FIC-cidencias</h1>
                <img src="img/logo.png" alt="Logo" id="logo">
            </div>


            <div id="cajaTexto">
                <label for="usuarioNombre">Usuario:</label>
                <input type="text" id="usuarioNombre" placeholder="nombre de usuario">
                <label for="usuarioContrasena">Contraseña:</label>
                <input type="password" id="usuarioContrasena" placeholder="contraseña">
            </div>

            <div id="inicioBotones">
                <a href="MenuIncidencias.html"><button >Iniciar Sesión</button></a>
                <button onclick="feliz()">Registrar</button>
            </div>
        <div id="olvideContrasena">
            Olvidé mi contraseña
        </div>
    </div>
</body>
</html>