<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssMantenimientoKebab.css">
    <title>Mantenimiento de Kebabs</title>
</head>
<body>
    <div class="contenedor">
        <h1 class="titulo-centrado">Kebabs Disponibles</h1>

        <!-- Contenedor donde se generarán las tarjetas de los kebabs -->
        <div class="cuadricula-kebabs">
            <!-- Tarjeta para crear un nuevo kebab -->
            <a href="index.php?menu=crearKebab" class="tarjeta-crear-kebab" style="text-decoration: none;">
                <div class="contenedor-crear">
                    <span class="icono-mas">+</span>
                    <p>Crear Kebab</p>
                </div>
            </a>
            <!-- Aquí se generarán las tarjetas de los kebabs mediante JavaScript -->
        </div>
    </div>

    <script src="./Js/mantenimientoKebab.js"></script>
</body>
</html>
