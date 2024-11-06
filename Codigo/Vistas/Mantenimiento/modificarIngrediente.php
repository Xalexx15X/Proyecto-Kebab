<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ingredientes</title>
    <link rel="stylesheet" href="./css/CssModificarIngredientes.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Modificar Ingredientes</h1>

        <div class="parent">
            <!-- Div1: Formulario de creación de kebab -->
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
                        <span>Subir o arrastrar imagen aquí</span>
                        <input type="file" id="fotoIngrediente" class="form-control-file" accept="image/*" style="display: none;">
                    </div>
                </div>

                <!-- Precio -->
                <div class="form-group">
                    <label for="precioIngrediente">Precio:</label>
                    <input type="text" id="precioIngrediente" placeholder="Introduce el precio">
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcionIngrediente">Descripción:</label>
                    <textarea id="descripcionIngrediente" rows="4" placeholder="Introduce una descripción"></textarea>
                </div>
            </div>

            <!-- Separador -->
            <div class="line-separator"></div>

            <!-- Div2: Ingredientes del Kebab -->
            <div class="div2">
                <div class="table-container">
                    <div class="table-header">
                        <div>Alergenos del Ingrediente</div>
                    </div>
                    <div class="table-body" id="alergenos-ingrediente">
                        <!-- Ejemplos de ingredientes en esta lista -->
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Tomate</div></div>
                        <div class="table-row"><div>Pepino</div></div>
                        <div class="table-row"><div>Hummus</div></div>
                        <div class="table-row"><div>Ensalada Mix</div></div>
                        <div class="table-row"><div>Zanahoria Rallada</div></div>
                        <div class="table-row"><div>Arroz</div></div>
                        <div class="table-row"><div>Aguacate</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Tomate</div></div>
                        <div class="table-row"><div>Pepino</div></div>
                        <div class="table-row"><div>Hummus</div></div>
                        <div class="table-row"><div>Ensalada Mix</div></div>
                        <div class="table-row"><div>Zanahoria Rallada</div></div>
                        <div class="table-row"><div>Arroz</div></div>
                        <div class="table-row"><div>Aguacate</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Tomate</div></div>
                        
                    </div>
                </div>
            </div>

            <!-- Div3: Ingredientes a Elegir -->
            <div class="div3">
                <div class="table-container">
                    <div class="table-header">
                        <div>Alergenos a Elegir</div>
                    </div>
                    <div class="table-body" id="alergenos-ingrediente">
                        <!-- Ejemplos de ingredientes disponibles para elegir -->
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Carne de Res</div></div>
                        <div class="table-row"><div>Carne de Cordero</div></div>
                        <div class="table-row"><div>Pepino</div></div>
                        <div class="table-row"><div>Hummus</div></div>
                        <div class="table-row"><div>Ensalada Mix</div></div>
                        <div class="table-row"><div>Zanahoria Rallada</div></div>
                        <div class="table-row"><div>Arroz</div></div>
                        <div class="table-row"><div>Aguacate</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Tomate</div></div>
                        <div class="table-row"><div>Pepino</div></div>
                        <div class="table-row"><div>Hummus</div></div>
                        <div class="table-row"><div>Ensalada Mix</div></div>
                        <div class="table-row"><div>Zanahoria Rallada</div></div>
                        <div class="table-row"><div>Arroz</div></div>
                        <div class="table-row"><div>Aguacate</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Tomate</div></div>
                    </div>
                </div>
            </div>

            <!-- Div4: Botones de acción -->
            <div class="div4">
                <div class="div-botones">
                    <button class="btn btn-1">Modificar</button>
                    <button class="btn btn-2">Borrar</button>
                    <a href="index.php?menu=mantenimientoKebab" class="mantenimientoKebab">
                    <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
