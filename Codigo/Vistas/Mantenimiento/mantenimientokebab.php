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
        <div class="cuadricula-kebabs">
            
            <!-- Tarjeta para crear un nuevo kebab -->
            <a href="index.php?menu=crearKebab" class="tarjeta-crear-kebab" style = "text-decoration: none;">
                <div class="contenedor-crear">
                    <span class="icono-mas">+</span>
                    <p>Crear Kebab</p>
                </div>
            </a>

            <!-- Tarjeta de kebab -->
            <div class="tarjeta-kebab">
                <img src="path/to/kebab-image.jpg" alt="Kebab" class="imagen-kebab">
                <div class="informacion-kebab">
                    <h3>Nombre del Kebab</h3>
                    <p>Ingredientes: Pan, carne, lechuga, tomate, cebolla</p>
                </div>
                <div class="grupo-botones">
                    <a href="index.php?menu=modificarKebab" class="modificarKebab">
                    <button class="boton boton-modificar">Modificar</button>
                    </a>
                    <button class="boton boton-borrar">Borrar</button>
                </div>
            </div>
        </div>
    </div>

    
</body>
</html>
