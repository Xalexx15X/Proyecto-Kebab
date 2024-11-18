<link rel="stylesheet" href="./css/CssMantenimientoAlergeno.css">
<body>
    <div class="contenedor">
        <h1 class="titulo-centrado">Ingredientes Disponibles</h1>
        <div class="cuadricula-alergeno"> <!-- Corregido de "cuadricula-Ingredientes" -->

            <!-- Tarjeta para crear un nuevo ingrediente -->
            <a href="index.php?menu=crearAlergeno" class="tarjeta-crear-alergeno" style="text-decoration: none;">
                <div class="contenedor-crear">
                    <span class="icono-mas">+</span>
                    <p>Crear Alergenos</p>
                </div>
            </a>

            <!-- Tarjeta de alergeno -->
            <div class="tarjeta-alergeno"> 
                <img src="path/to/alergeno-image.jpg" alt="Alergeno" class="imagen-alergeno">
                <div class="informacion-alergeno">
                    <h3>Nombre del Alergeno</h3>
                </div>
                <div class="grupo-botones">
                    <a href="index.php?menu=modificarAlergeno" class="modificarAlergeno">
                        <button class="boton boton-modificar">Modificar</button>
                    </a>
                    <button class="boton boton-borrar">Borrar</button>
                </div>
            </div>
        </div>
    </div>
</body>

