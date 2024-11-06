<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Ingrediente</title>
    <link rel="stylesheet" href="./css/CssCrearIngredientes.css">
</head>
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
                        <span>Subir o arrastrar imagen aquí</span>
                        <input type="file" id="fotoIngrediente" class="form-control-file" accept="image/*" style="display: none;">
                    </div>
                </div>

                <!-- Precio -->
                <div class="form-group">
                    <label for="precioIngrediente">Precio:</label>
                    <input type="text" id="precioIngrediente" placeholder="Introduce el precio del ingrediente">
                </div>

                <!-- Descripción -->
                <div class="form-group">
                    <label for="descripcionIngrediente">Descripción:</label>
                    <textarea id="descripcionIngrediente" rows="4" placeholder="Introduce una descripción del ingrediente"></textarea>
                </div>
            </div>

            <!-- Separador -->
            <div class="line-separator"></div>

            <!-- Div2: Alergenos del ingrediente -->
            <div class="div2">
                <div class="table-container">
                    <div class="table-header">Alergenos del Ingrediente</div>
                    <div class="table-body" id="alergenos-ingrediente">
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                        <div class="table-row"><div>Lechuga</div></div>
                    </div>
                </div>
            </div>

            <!-- Div3: Alergenos a Elegir -->
            <div class="div3">
                <div class="table-container">
                    <div class="table-header">Alergenos a Elegir</div>
                    <div class="table-body" id="ingredientes-elegir">
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>
                        <div class="table-row"><div>Falafel</div></div>                       
                    </div>
                    <!-- Botón para crear alérgeno -->
                    <button class="create-allergen-btn" onclick="openAllergenModal()">Crear Alérgeno</button>
                </div>
            </div>

            <!-- Div4: Botones de acción -->
            <div class="div4">
                <div class="div-botones">
                    <button class="btn btn-1">Crear</button>
                    <button class="btn btn-2">Borrar</button>
                    <a href="index.php?menu=mantenimientoIngrediente" class="mantenimientoIngrediente">
                        <button class="btn btn-3">Salir</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear alérgenos -->
    <div id="allergenModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAllergenModal()">&times;</span>
            <h2>Crear Alérgeno</h2>
            <input type="text" id="newAllergenName" placeholder="Nombre del alérgeno">
            <button onclick="addAllergen()">Agregar Alérgeno</button>
        </div>
    </div>

</body>
</html>
