<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssLogin.css">
    <title>Login</title>
</head>
<body>
    <div class="w-50 p-3 container">
        <div class="login-form">
            <form id="loginForm" novalidate>
                <h2 class="text-center">Identifícate</h2>
                <div class="form-group">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña">
                </div>
                <div class="form-group">
                    <button type="button" id="btnLogin" class="btn btn-primary btn-block">Iniciar Sesión</button>
                </div>
                <div class="clearfix">
                    <label class="pull-left checkbox-inline">
                        <input type="checkbox" name="recuerdame"> Recuérdame
                    </label>
                </div>
            </form>
            <p class="text-center"><a href="index.php?menu=registro">Crear una Cuenta</a></p>
        </div>
    </div>
</body>
</html>
