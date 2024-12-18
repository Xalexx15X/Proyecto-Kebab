<link rel="stylesheet" href="./css/CssCrearIngredientes.css">
<body>
    <div class="container">
        <h1 class="text-center">Crear Ingrediente</h1>

        <div class="parent">
            <!-- Div1: Formulario de creación de ingrediente -->
            <div class="div1">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombreIngrediente">Nombre:</label>
                    <input type="text" id="nombreIngrediente" placeholder="Introduce el nombre del ingrediente">
                </div>

                <!-- Subida de Foto -->
                <div class="form-group">
                    <label for="fotoIngrediente">Foto:</label>
                    <div class="preview-container" onclick="document.getElementById('fotoIngrediente').click()">
                        <span>Subir imagen aquí</span>
                        <input type="file" id="fotoIngrediente" class="form-control-file" accept="image/*" style="display: none;">
                    </div>
                </div>

                <!-- Precio -->
                <div class="form-group">
                    <label for="precioIngrediente">Precio:</label>
                    <input type="text" id="precioIngrediente" placeholder="Introduce el precio del ingrediente">
                </div>
            </div>

            <!-- Separador -->
            <div class="line-separator"></div>

            <!-- Div2: Alergenos del ingrediente -->
            <div class="div2">
                <div class="table-container">
                    <div class="table-header">Alergenos del Ingrediente</div>
                    <div class="table-body" id="alergenos-ingrediente">
                        <!-- Alergenos se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </div>

            <!-- Div3: Alergenos a Elegir -->
            <div class="div3">
                <div class="table-container">
                    <div class="table-header">Alergenos a Elegir</div>
                    <div class="table-body" id="ingredientes-elegir">
                    </div>
                </div>
            </div>

            <!-- Div4: Botones de acción -->
            <div class="div4">
                <div class="div-botones">
                    <button class="btn btn-1" id="crearIngredienteBtn">Crear</button>
                    <button class="btn btn-2" id="borrarCamposBtn">Borrar</button>
                    <a href="index.php?menu=mantenimientoIngrediente" class="mantenimientoIngrediente">
                        <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="./Js/Ingredientes.js"></script>
</body>

