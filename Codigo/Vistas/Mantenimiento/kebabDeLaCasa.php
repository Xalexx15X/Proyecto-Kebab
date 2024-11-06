<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssKebabDeLaCasa.css">
    <title>Mantenimiento de Kebabs</title>
</head>
<body>
    <div class="contenedor">
        <h1 class="titulo-centrado">Kebabs Disponibles</h1>
        <div class="cuadricula-kebabs">
            <!-- Tarjeta de kebab (se genera dinámicamente por cada kebab en la base de datos) -->
            <div class="tarjeta-kebab">
                <img src="path/to/kebab-image.jpg" alt="Kebab" class="imagen-kebab">
                <div class="informacion-kebab">
                    <h3>Nombre del Kebab</h3>
                    <p>Ingredientes: Pan, carne, lechuga, tomate, cebolla</p>
                </div>
                <div class="grupo-botones">
                    <button class="boton boton-agregar-carrito" onclick="agregarAlCarrito()">Agregar al Carrito</button>
                    <button class="boton boton-ver-mas" onclick="alternarDescripcion(this)">Ver más</button>
                </div>
                <p class="descripcion-kebab" style="display: none;">
                    Descripción detallada del kebab. Este kebab contiene ingredientes frescos...
                </p>
            </div>
        </div>
    </div>

    <script>
        // Función para añadir al carrito
        function agregarAlCarrito() {
            alert("Kebab agregado al carrito.");
        }

        // Función para mostrar/ocultar descripción
        function alternarDescripcion(boton) {
            const descripcion = boton.parentElement.nextElementSibling;
            if (descripcion.style.display === "none") {
                descripcion.style.display = "block";
                boton.textContent = "Ver menos";
            } else {
                descripcion.style.display = "none";
                boton.textContent = "Ver más";
            }
        }
    </script>
</body>
</html>

