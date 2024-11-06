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
        <div class="cuadricula-ingrediente"> <!-- Corregido de "cuadricula-Ingredientes" -->

            <!-- Tarjeta para crear un nuevo ingrediente -->
            <a href="index.php?menu=crearIngrediente" class="tarjeta-crear-ingrediente" style="text-decoration: none;">
                <div class="contenedor-crear">
                    <span class="icono-mas">+</span>
                    <p>Crear Ingrediente</p>
                </div>
            </a>

            <!-- Tarjeta de ingrediente -->
            <div class="tarjeta-ingrediente"> <!-- Corregido de "tarjeta-kebab" -->
                <img src="path/to/ingrediente-image.jpg" alt="Ingrediente" class="imagen-ingrediente">
                <div class="informacion-ingrediente">
                    <h3>Nombre del Ingrediente</h3>
                    <p>Alergenos: trazas de huevo, lacteos, etc.</p> <!-- Cerrada la etiqueta <p> -->
                </div>
                <div class="grupo-botones">
                    <a href="index.php?menu=modificarIngrediente" class="modificarIngrediente">
                        <button class="boton boton-modificar">Modificar</button>
                    </a>
                    <button class="boton boton-borrar">Borrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
