<link rel="stylesheet" href="./css/CssMantenimientoKebab.css">
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

