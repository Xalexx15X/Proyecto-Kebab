<link rel="stylesheet" href="./css/CssModificarAlergeno.css">
<body>
    <div class="container">
        <h1 class="text-center">Crear Alergeno</h1>

        <div class="parent">
            <!-- Div1: Formulario de creación de ingrediente -->
            <div class="div1">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombreAlergeno">Nombre:</label>
                    <input type="text" id="nombreAlergeno" placeholder="Introduce el nombre del alergeno">
                </div>

                <!-- Subida de Foto -->
                <div class="form-group">
                    <label for="fotoAlergeno">Foto:</label>
                    <div class="preview-container" onclick="document.getElementById('fotoAlergeno').click()">
                        <span>Subir imagen aquí</span>
                        <input type="file" id="fotoAlergeno" class="form-control-file" accept="image/*" style="display: none;">
                    </div>
                </div>
            </div>

            <!-- Div4: Botones de acción -->
            <div class="div4">
                <div class="div-botones">
                    <button class="btn btn-1" id="crearIngredienteBtn">modificar</button>
                    <button class="btn btn-2" id="borrarCamposBtn">Borrar</button>
                    <a href="index.php?menu=mantenimientoAlergeno" class="mantenimientoAlergeno">
                        <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

