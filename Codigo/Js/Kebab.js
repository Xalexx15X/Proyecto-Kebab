document.addEventListener('DOMContentLoaded', function () {
    // Defino las URLs para obtener los ingredientes y los kebabs desde la API
    const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
    const apiURLKebab = 'http://localhost/ProyectoKebab/codigo/index.php?route=kebabs'; // URL para kebabs

    // Función para crear el div de cada ingrediente
    function crearIngredienteDiv(ingrediente) {
        const ingredienteDiv = document.createElement('div'); // Creo un nuevo div para el ingrediente
        ingredienteDiv.classList.add('ingrediente');  // Le asigno la clase 'ingrediente'
        ingredienteDiv.setAttribute('data-id', ingrediente.id_ingredientes);  // Asigno el ID del ingrediente
        ingredienteDiv.setAttribute('data-precio', ingrediente.precio);  // Asigno el precio
        ingredienteDiv.setAttribute('data-alergenos', JSON.stringify(ingrediente.alergenos)); // Guardo los alérgenos como JSON
        ingredienteDiv.textContent = `${ingrediente.nombre} - ${ingrediente.precio + "€"}`; // Muestro el nombre y el precio
        ingredienteDiv.draggable = true;  // Hago que el div sea arrastrable

        // Al iniciar el arrastre, guardo los datos del ingrediente en el arrastre
        ingredienteDiv.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));  // Almaceno el ingrediente arrastrado
        });

        return ingredienteDiv;  // retorno el div 
    }

    // Función para actualizar el precio y los alérgenos cuando un ingrediente es agregado o eliminado
    function actualizarPrecioYAlergenos(ingrediente, operacion) {
        const precioRecomendado = document.getElementById('precioRecomendado');  // Obtengo el campo de precio recomendado
        const descripcionKebab = document.getElementById('descripcionKebab');  // Obtengo el campo de descripción de los alérgenos

        // Actualizo el precio basado en la operación (sumar o restar)
        const precioActual = parseFloat(precioRecomendado.value || 0);
        precioRecomendado.value = (operacion === 'sumar' ? precioActual + parseFloat(ingrediente.precio) : precioActual - parseFloat(ingrediente.precio)).toFixed(2);

        // Actualizo la lista de alérgenos
        let alergenosList = descripcionKebab.value.replace('Alergenos del Kebab: ', '').split(', ');  // Obtengo los alérgenos actuales
        if (operacion === 'sumar') {
            alergenosList = alergenosList.concat(ingrediente.alergenos.map(a => a.nombre));  // Si se suman, añado los nuevos alérgenos
        } else {
            alergenosList = alergenosList.filter(alergeno => !ingrediente.alergenos.map(a => a.nombre).includes(alergeno));  // Si se restan, elimino los alérgenos
        }
        descripcionKebab.value = `${[...new Set(alergenosList)].join(' ')}`;  // Actualizo la descripción con los alérgenos únicos
    }

    // Obtener todos los ingredientes y mostrarlos en la tabla de ingredientes disponibles
    fetch(`${apiURLIngredientes}`)
        .then(response => response.json())
        .then(data => {
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');
            data.forEach(ingrediente => {
                const ingredienteDiv = crearIngredienteDiv(ingrediente);  // Creo un div para cada ingrediente
                ingredientesElegirContainer.appendChild(ingredienteDiv);  // Lo agrego a la tabla de ingredientes disponibles
            });
        });

    // Manejo del "dragover" y "drop" para la zona de ingredientes del kebab
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

    // Permito que se pueda soltar un ingrediente sobre el área de ingredientes del kebab
    ingredientesKebabContainer.addEventListener('dragover', function (e) {
        e.preventDefault();  // Evito el comportamiento predeterminado para permitir el "drop"
    });

    // Cuando se suelta un ingrediente, lo agrego al kebab
    ingredientesKebabContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // Obtengo el ingrediente arrastrado

        // Verifico si ya existe ese ingrediente en el kebab, si es así no lo agrego de nuevo
        if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            alert('El ingrediente ya está añadido.');
            return;
        }

        // Si no existe, agrego el ingrediente al kebab
        const ingredienteDiv = crearIngredienteDiv(ingrediente);
        ingredientesKebabContainer.appendChild(ingredienteDiv);

        // Remuevo el ingrediente de la lista de ingredientes disponibles
        const ingredienteElegir = [...ingredientesElegirContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteElegir) {
            ingredientesElegirContainer.removeChild(ingredienteElegir);
        }

        // Actualizo el precio y los alérgenos
        actualizarPrecioYAlergenos(ingrediente, 'sumar');
    });

    // Manejo del "dragover" y "drop" para la zona de ingredientes disponibles
    ingredientesElegirContainer.addEventListener('dragover', function (e) {
        e.preventDefault();  // Permito el "drop"
    });

    // Cuando se suelta un ingrediente en los ingredientes disponibles, lo devuelvo a la lista
    ingredientesElegirContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // Obtengo el ingrediente arrastrado

        // Verifico si el ingrediente ya está en la lista de ingredientes disponibles
        if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            return;
        }

        // Lo agrego de nuevo a la lista de ingredientes disponibles
        const ingredienteDiv = crearIngredienteDiv(ingrediente);
        ingredientesElegirContainer.appendChild(ingredienteDiv);

        // Remuevo el ingrediente de la lista de ingredientes del kebab
        const ingredienteKebab = [...ingredientesKebabContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteKebab) {
            ingredientesKebabContainer.removeChild(ingredienteKebab);
            // Actualizo el precio y los alérgenos
            actualizarPrecioYAlergenos(ingrediente, 'restar');
        }
    });

    // Función para mostrar la imagen del kebab seleccionada por el usuario
    document.getElementById('fotoKebab').addEventListener('change', function (event) {
        const file = event.target.files[0];  // Obtengo el archivo seleccionado
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.querySelector(".preview-container");
            if (previewContainer) {
                previewContainer.style.backgroundImage = `url(${e.target.result})`;  // Muestra la imagen seleccionada como fondo
                previewContainer.style.backgroundSize = "cover";
                const span = previewContainer.querySelector("span");
                if (span) span.style.display = "none";  // Oculto el texto que dice "Selecciona una imagen"
            }
        };
        reader.readAsDataURL(file);  // Lee el archivo como una URL de datos
    });

    // Funcionalidad del botón "Crear" para enviar los datos del kebab
    document.querySelector('.btn-1').addEventListener('click', function () {
        const nombre = document.getElementById('nombreKebab').value;  // Obtengo el nombre del kebab
        const foto = document.getElementById('fotoKebab').files[0];  // Obtengo la foto seleccionada
        const precio = document.getElementById('precio').value;  // Obtengo el precio recomendado
        const descripcion = document.getElementById('descripcionKebab').value;  // Obtengo la descripción
        const ingredientes = Array.from(ingredientesKebabContainer.children).map(ingrediente => parseInt(ingrediente.getAttribute('data-id')));  // Obtengo los IDs de los ingredientes seleccionados
    
        // Si falta algún dato, muestro un mensaje de error
        if (!nombre || !foto || !precio || !descripcion || ingredientes.length === 0) {
            alert('Todos los campos son obligatorios.');
            return;
        }
    
        const reader = new FileReader();
        reader.onloadend = function () {  // Cambio a onloadend para usar split aquí
            const fotoBase64 = reader.result.split(',')[1];  // Extrae solo el contenido base64
            const kebab = {
                nombre: nombre,
                foto: fotoBase64,
                precio_min: parseFloat(precio),
                descripcion: descripcion,
                ingredientes: ingredientes
            };
    
            // Enviar el kebab a la API para crear el kebab
            fetch(apiURLKebab, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(kebab)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error en la respuesta: ${response.statusText}`);
                    }
                    return response.json(); // Convertir la respuesta a JSON si es válida
                })
                .then(data => {
                    alert('Kebab creado exitosamente.');
                    limpiarCampos(); // Limpio los campos después de la confirmación
                })
                .catch(error => {
                    console.error('Error al crear el kebab:', error);
                    alert('Hubo un error al crear el kebab.');
                });
        };
        reader.readAsDataURL(foto);  // Convierte la foto a base64
    });
    

// Función para limpiar los campos después de crear el kebab o al pulsar el botón
function limpiarCampos() {
    // Limpio todos los campos del formulario
    document.getElementById('nombreKebab').value = '';
    document.getElementById('fotoKebab').value = '';
    document.getElementById('precio').value = '';
    document.getElementById('precioRecomendado').value = '';
    document.getElementById('descripcionKebab').value = '';

    // Limpio la vista previa de la imagen
    const previewContainer = document.querySelector(".preview-container");
    if (previewContainer) {
        previewContainer.style.backgroundImage = ''; // Quita la imagen de fondo
        const span = previewContainer.querySelector("span");
        if (span) span.style.display = ''; // Vuelve a mostrar el texto inicial
    }

    // Muevo todos los ingredientes seleccionados de vuelta a la lista de disponibles
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

    const ingredientes = [...ingredientesKebabContainer.children];
    ingredientes.forEach(ingrediente => {
        ingredientesKebabContainer.removeChild(ingrediente);
        ingredientesElegirContainer.appendChild(ingrediente);
    });
}

    // Funcionalidad del botón "Borrar" para limpiar todos los campos y resetear la selección
    document.querySelector('.btn-2').addEventListener('click', limpiarCampos);

});
