<link rel="stylesheet" href="./css/CssCrearKebab.css">
<body>
    <div class="container">
        <h1 class="text-center">Crear Kebab</h1>

        <div class="parent">
            <!-- Div1: Formulario de creación de kebab -->
            <div class="div1">
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombreKebab">Nombre:</label>
                    <input type="text" id="nombreKebab" placeholder="Introduce el nombre del kebab">
                </div>

                <!-- Subida de Foto -->
                <div class="form-group">
                    <label for="fotoKebab">Foto:</label>
                    <div class="preview-container" onclick="document.getElementById('fotoKebab').click()">
                        <span>Subir o arrastrar imagen aquí</span>
                        <input type="file" id="fotoKebab" class="form-control-file" accept="image/*" style="display: none;">
                    </div>
                </div>

                <!-- Precio Recomendado -->
                <div class="form-group">
                    <label for="precio">Precio Kebab:</label>
                    <input type="text" id="precio" placeholder="Precio Kebab">
                </div>

                <!-- Precio Recomendado -->
                <div class="form-group">
                    <label for="precioRecomendado">Precio Recomendado:</label>
                    <input type="text" id="precioRecomendado" placeholder="Calculado automáticamente">
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcionKebab">Descripción (Alérgenos):</label>
                    <textarea id="descripcionKebab" rows="4" placeholder="Los alérgenos aparecerán aquí automáticamente"></textarea>
                </div>
            </div>

            <!-- Separador -->
            <div class="line-separator"></div>

            <!-- Div2: Ingredientes del Kebab -->
            <div class="div2">
                <div class="table-container">
                    <div class="table-header">Ingredientes del Kebab</div>
                    <div class="table-body" id="ingredientes-kebab"></div>
                </div>
            </div>

            <!-- Div3: Ingredientes a Elegir -->
            <div class="div3">
                <div class="table-container">
                    <div class="table-header">Ingredientes a Elegir</div>
                    <div class="table-body" id="ingredientes-elegir"></div>
                </div>
            </div>

            <!-- Div4: Botones de acción -->
            <div class="div4">
                <div class="div-botones">
                    <button class="btn btn-1">Crear</button>
                    <button class="btn btn-2">Borrar</button>
                    <a href="index.php?menu=mantenimientoKebab" class="mantenimientoKebab">
                        <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="./Js/Kebab.js"></script>
</body>

