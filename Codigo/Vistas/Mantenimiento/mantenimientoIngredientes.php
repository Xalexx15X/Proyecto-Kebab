<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssMantenimientoIngrediente.css">
    <title>Mantenimiento de Ingredientes</title>
</head>
<body>
    <div class="contenedor">
        <h1 class="titulo-centrado">Ingredientes Disponibles</h1>

        <!-- Contenedor donde se generarán las tarjetas de los ingredientes -->
        <div class="cuadricula-ingrediente">
            <!-- Tarjeta para crear un nuevo ingrediente -->
            <a href="index.php?menu=crearIngrediente" class="tarjeta-crear-ingrediente" style="text-decoration: none;">
                <div class="contenedor-crear">
                    <span class="icono-mas">+</span>
                    <p>Crear Ingrediente</p>
                </div>
            </a>
            <!-- Aquí se generarán las tarjetas de los ingredientes mediante JavaScript -->
        </div>
    </div>

    <script src="./Js/mantenimientoIngrediente.js"></script>
</body>
</html>
