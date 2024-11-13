<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="./css/CssRegistro.css">
    <script src="./Js/manejoHeaders.js"></script>
</head>
<body>
    <h1>Registro</h1>
    <div class="container">
        <div class="parent">
            <!-- Sección de Datos Personales -->
            <div class="div1">
                <h2>Datos Personales</h2>
                <form>
                    <label for="nombre-cuenta">Nombre de cuenta:</label>
                    <input type="text" id="nombre-cuenta" name="nombre-cuenta" placeholder="Introduce tu nombre de cuenta">

                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Introduce tu contraseña">

                    <!-- Subida de Foto -->
                    <div class="form-group">
                        <label for="fotoKebab">Foto:</label>
                        <div class="preview-container" onclick="document.getElementById('fotoKebab').click()">
                            <span>Subir o arrastrar imagen aquí</span>
                            <input type="file" id="fotoKebab" class="form-control-file" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="Introduce tu teléfono">

                    <label for="Email">Correo Electronico:</label>
                    <input type="text" id="email" name="email" placeholder="Introduce un email: ejempol@gmail.com">
                </form>
            </div>

            <!-- Sección de Dirección -->
            <div class="div2">
                <h2>Dirección</h2>
                <form>
                    <label for="nombre-calle">Nombre de la calle:</label>
                    <input type="text" id="nombre-calle" name="nombre-calle" placeholder="Introduce el nombre de tu calle">

                    <label for="numero-calle">Número de la calle:</label>
                    <input type="number" id="numero-calle" name="numero-calle" placeholder="Introduce el número">

                    <label for="tipo-casa">Tipo de casa:</label>
                    <input type="text" id="tipo-casa" name="tipo-casa" placeholder="Apartamento, casa, etc.">

                    <label for="numero-piso">Número de piso:</label>
                    <input type="number" id="numero-piso" name="numero-piso" placeholder="Introduce el número de piso">

                    <label for="letra-apartamento">Letra del apartamento:</label>
                    <input type="text" id="letra-apartamento" name="letra-apartamento" placeholder="Ejemplo: A, B, C">
                </form>
            </div>
        </div>

        <!-- Botón Registrarse -->
        <div class="registrarse">
            <button type="submit">Registrarse</button>
        </div>
    </div>
   
</body>
</html>
