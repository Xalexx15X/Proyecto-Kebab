<link rel="stylesheet" href="./css/CssCrearKebab.css">
<body>
    <div class="container">
        <h1 class="text-center">Kebab al gusto</h1>

        <div class="parent">
            <!-- Div1: Formulario de creación de kebab -->
            <div class="div1">
        
            <div class="form-group">
                    <label for="precioRecomendado">Precio Del Kebab</label>
                    <input type="text" id="precioRecomendado" placeholder="Calculado automáticamente">
            </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcionKebab">Descripción de los Alegenos</label>
                    <textarea id="descripcionKebab" rows="4" placeholder="Aqui podras ver los alergenos del kebab"></textarea>
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
                    <button class="btn btn-1">Añadir al Carrito</button>
                    <button class="btn btn-2">Borrar</button>
                    <a href="index.php?menu=inicio" class="mantenimientoKebab">
                        <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="./Js/kebabAlGusto.js"></script>
</body>

