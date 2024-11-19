document.addEventListener('DOMContentLoaded', function () {
    const idKebab = obtenerIdKebab(); // Obtener el ID del kebab desde la URL
    if (idKebab) {
        cargarDatosKebab(idKebab); // Cargar los datos del kebab específico
    }

    // Cargar todos los ingredientes disponibles
    cargarIngredientesDisponibles();
});

// Función para obtener el ID del kebab desde la URL
function obtenerIdKebab() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id_kebab');
}

// Función para cargar los datos del kebab desde la API
function cargarDatosKebab(id) {
    fetch(`http://localhost/ProyectoKebab/codigo/index.php?route=kebabs&id_kebab=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                mostrarDatosKebab(data); // Mostrar los datos en el formulario
            } else {
                console.error("No se encontraron datos para el kebab:", data);
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos del kebab:", error);
        });
}

// Función para rellenar el formulario con los datos del kebab
function mostrarDatosKebab(kebab) {
    // Rellenar campos básicos
    document.querySelector('#nombreKebab').value = kebab.nombre;
    document.querySelector('#descripcionKebab').value = kebab.descripcion;
    document.querySelector('#precioKebab').value = kebab.precio || 0;

    // Calcular el precio recomendado sumando los ingredientes
    let precioRecomendado = kebab.precio || 0;
    if (Array.isArray(kebab.ingredientes)) {
        kebab.ingredientes.forEach(ingrediente => {
            precioRecomendado += ingrediente.precio; // Suponiendo que cada ingrediente tiene un precio
        });
    }
    document.querySelector('#precioRecomendado').value = precioRecomendado.toFixed(2);

    // Manejar imagen del kebab
    if (kebab.foto) {
        const imagen = document.querySelector('.preview-container');
        imagen.style.backgroundImage = `url('data:image/jpeg;base64,${kebab.foto}')`;
        imagen.style.backgroundSize = 'cover';
        imagen.style.backgroundPosition = 'center';
        document.querySelector('.preview-container span').style.display = 'none'; // Ocultar el texto "Subir o arrastrar imagen aquí"
    }

    // Rellenar ingredientes del kebab
    if (Array.isArray(kebab.ingredientes)) {
        const ingredientesKebab = document.querySelector('#ingredientes-kebab');
        ingredientesKebab.innerHTML = ''; // Limpiar la lista actual

        kebab.ingredientes.forEach(ingrediente => {
            const div = crearIngredienteDiv(ingrediente);  // Crear div para cada ingrediente
            ingredientesKebab.appendChild(div);
            // Permitir que el ingrediente ya agregado también sea movido de vuelta
            div.setAttribute('draggable', 'true');
            div.addEventListener('dragstart', function (e) {
                e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));
            });
            // Actualizar el precio y los alérgenos con el ingrediente
            actualizarPrecioYAlergenos(ingrediente, 'sumar');
        });
    }
}

// Función para cargar los ingredientes disponibles
function cargarIngredientesDisponibles() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes') // URL de la API para obtener ingredientes
        .then(response => response.json())
        .then(data => {
            const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

            // Obtener los ingredientes ya seleccionados en el kebab
            const ingredientesDelKebab = Array.from(ingredientesKebabContainer.children).map(div => div.getAttribute('data-id'));

            // Mostrar solo los ingredientes que no están en el kebab
            data.forEach(ingrediente => {
                if (!ingredientesDelKebab.includes(ingrediente.id_ingredientes.toString())) {
                    const ingredienteDiv = crearIngredienteDiv(ingrediente);
                    // Permitir el arrastre de ingredientes
                    ingredienteDiv.setAttribute('draggable', 'true');
                    ingredienteDiv.addEventListener('dragstart', function (e) {
                        e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));
                    });
                    ingredientesElegirContainer.appendChild(ingredienteDiv);
                }
            });
        })
        .catch(error => {
            console.error("Error al cargar los ingredientes:", error);
        });
}

// Crear el div para un ingrediente
function crearIngredienteDiv(ingrediente) {
    const div = document.createElement('div');
    div.classList.add('ingrediente-item');
    div.textContent = `${ingrediente.nombre} - $${ingrediente.precio.toFixed(2)}`;
    div.setAttribute('data-id', ingrediente.id_ingredientes);
    return div;
}

// Actualizar el precio y alérgenos del kebab
function actualizarPrecioYAlergenos(ingrediente, accion) {
    const precioKebab = document.querySelector('#precioKebab');
    let precioActual = parseFloat(precioKebab.value);

    if (accion === 'sumar') {
        precioActual += ingrediente.precio;
    } else if (accion === 'restar') {
        precioActual -= ingrediente.precio;
    }

    precioKebab.value = precioActual.toFixed(2);
}

// Manejo del "dragover" y "drop" para la zona de ingredientes del kebab
const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

// Habilitar el "dragover" para la zona de ingredientes del kebab (donde se sueltan los ingredientes)
ingredientesKebabContainer.addEventListener('dragover', function (e) {
    e.preventDefault();  // Permite el "drop"
});

// Manejo del "drop" de ingredientes en el kebab
ingredientesKebabContainer.addEventListener('drop', function (e) {
    e.preventDefault();
    const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // Obtengo el ingrediente arrastrado

    // Verificar si el ingrediente ya está en el kebab
    if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
        alert('El ingrediente ya está añadido.');
        return;
    }

    // Agregar el ingrediente al kebab
    const ingredienteDiv = crearIngredienteDiv(ingrediente);
    ingredientesKebabContainer.appendChild(ingredienteDiv);

    // Removerlo de la lista de ingredientes disponibles
    const ingredienteElegir = [...ingredientesElegirContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
    if (ingredienteElegir) {
        ingredientesElegirContainer.removeChild(ingredienteElegir);
    }

    // Actualizar el precio y los alérgenos
    actualizarPrecioYAlergenos(ingrediente, 'sumar');
});

// Habilitar el "dragover" para la zona de ingredientes disponibles
ingredientesElegirContainer.addEventListener('dragover', function (e) {
    e.preventDefault();  // Permite el "drop"
});

// Manejo del "drop" de ingredientes en la zona de ingredientes disponibles
ingredientesElegirContainer.addEventListener('drop', function (e) {
    e.preventDefault();
    const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // Obtengo el ingrediente arrastrado

    // Verificar si el ingrediente ya está en la lista de ingredientes disponibles
    if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
        return;
    }

    // Agregar el ingrediente de nuevo a la lista de ingredientes disponibles
    const ingredienteDiv = crearIngredienteDiv(ingrediente);
    ingredientesElegirContainer.appendChild(ingredienteDiv);

    // Removerlo de los ingredientes del kebab
    const ingredienteKebab = [...ingredientesKebabContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
    if (ingredienteKebab) {
        ingredientesKebabContainer.removeChild(ingredienteKebab);
        // Actualizar el precio y los alérgenos
        actualizarPrecioYAlergenos(ingrediente, 'restar');
    }
});
