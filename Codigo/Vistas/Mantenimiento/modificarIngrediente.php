<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssModificarIngredientes.css">
    <title>Modificar Ingredientes</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Modificar Ingredientes</h2>

        <!-- Formulario de modificación de ingrediente -->
        <form id="formModificarIngrediente">
            <!-- ID o nombre del Kebab -->
            <div class="form-group">
                <label for="idKebab">ID o Nombre del Ingrediente:</label>
                <div class="input-group">
                    <input type="text" id="idKebab" class="form-control" placeholder="ID o Nombre del Kebab">
                </div>
            </div>

            <!-- Nombre del Ingrediente -->
            <div class="form-group">
                <label for="nombreIngrediente">Nombre:</label>
                <input type="text" id="nombreIngrediente" class="form-control" placeholder="Nombre del ingrediente">
            </div>

            <!-- Subida de Imagen -->
            <div class="form-group">
                <label for="fotoIngrediente">Foto:</label>
                <div class="preview-container">
                    <label for="fotoIngrediente" class="btn btn-outline-primary">Subir o arrastrar imagen aquí</label>
                    <input type="file" id="fotoIngrediente" class="form-control-file" accept="image/*" style="display: none;">
                </div>
            </div>

            <!-- Precio del Ingrediente -->
            <div class="form-group">
                <label for="precioIngrediente">Precio:</label>
                <input type="number" id="precioIngrediente" class="form-control" placeholder="Precio del ingrediente" min="0" step="0.01">
            </div>

            <!-- Descripción del Ingrediente -->
            <div class="form-group">
                <label for="descripcionIngrediente">Descripción:</label>
                <textarea id="descripcionIngrediente" class="form-control" rows="5" placeholder="Descripción del ingrediente"></textarea>
            </div>

            <!-- Alérgenos -->
            <div class="form-group">
                <label for="alergenos">Alérgenos:</label>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Alérgenos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="1">No hay alérgenos disponibles</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="button" class="btn btn-link" onclick="agregarAlergeno()">Añadir nuevo alérgeno</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="div-botones">
                <button class="btn btn-1">Crear</button>
                <button class="btn btn-2">Borrar</button>
                <button class="btn btn-3">Salir</button>
            </div>
        </form>
    </div>
</body>
</html>