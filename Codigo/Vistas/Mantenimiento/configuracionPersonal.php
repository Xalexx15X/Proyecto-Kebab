<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración Personal</title>
    <link rel="stylesheet" href="./css/CssConfiguracion.css">
</head>
<body>
    <h1>Configuración Personal</h1>
    <div class="contenedor">
        <div class="secciones">
            <!-- Sección de Datos Personales -->
            <div class="datos-personales">
                <h2>Ver o Cambiar los Datos Personales</h2>
                <form>
                    <label for="nombre-cuenta">Nombre de cuenta:</label>
                    <input type="text" id="nombre-cuenta" name="nombre-cuenta" placeholder="alexx200" required>

                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="12345" required>

                    <!-- Subida de Foto -->
                    <div class="subir-foto">
                        <label for="foto-perfil">Foto:</label>
                        <div class="contenedor-previa" onclick="document.getElementById('foto-perfil').click()">
                            <span>Subir o arrastrar imagen aquí</span>
                            <input type="file" id="foto-perfil" class="input-archivo" accept="image/*" style="display: none;">
                        </div>
                    </div>

                    <label for="telefono">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="633284407" required>

                    <label for="Email">Correo Electronico:</label>
                    <input type="text" id="email" name="email" placeholder="ejempol@gmail.com">
                </form>
            </div>

            <!-- Sección de Dirección -->
            <div class="direccion">
                <h2>Ver o Cambiar la Dirección</h2>
                
                <!-- Tabla de direcciones del usuario -->
                <div class="contenedor-tabla">
                    <div class="cabecera-tabla">Direcciones</div>
                    <div class="cuerpo-tabla" id="direcciones-usuario">
                        <!-- Las direcciones se agregarán aquí -->
                    </div>
                    <div class="botones-accion">
                        <button class="btn crear-direccion">Crear Dirección</button>
                    </div>
                </div>
                                
                
                <!-- Formulario de edición/creación de dirección -->
                <form id="formulario-direccion" style="display: none;">
                    <label for="nombre-calle">Nombre de la calle:</label>
                    <input type="text" id="nombre-calle" name="nombre-calle" placeholder="Juan Sebastian ElCano" required>

                    <label for="numero-calle">Número de la calle:</label>
                    <input type="number" id="numero-calle" name="numero-calle" placeholder="Nº 9" required>

                    <label for="tipo-casa">Tipo de casa:</label>
                    <input type="text" id="tipo-casa" name="tipo-casa" placeholder="Piso" required>

                    <label for="numero-piso">Número de piso:</label>
                    <input type="number" id="numero-piso" name="numero-piso" placeholder="3">

                    <label for="letra-apartamento">Letra del apartamento:</label>
                    <input type="text" id="letra-apartamento" name="letra-apartamento" placeholder="B">
                    <button type="submit" id="guardar-direccion">Guardar Dirección</button>
                </form>
            </div>
        </div>

        <div class="botones-accion">
            <button class="btn guardar">Guardar</button>
            <button class="btn borrar">Borrar</button>
            <a href="index.php?menu=inicio" class="configuracion-personal">
                <button class="btn salir">Salir</button>
            </a>
        </div>
    </div>

    <script src="./Js/configuracionPersonal.js"></script>
</body>
</html>
