document.addEventListener('DOMContentLoaded', function () {
    const idKebab = obtenerIdKebab(); // Obtener el ID del kebab desde la URL
    if (idKebab) {
        cargarDatosKebab(idKebab); // Cargar los datos del kebab específico
    }

    // Cargar todos los ingredientes disponibles
    cargarIngredientesDisponibles();
});

// Variables globales
let precioBaseKebab = 0; // Precio base del kebab

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

    // Guardar y mostrar el precio base del kebab
    precioBaseKebab = kebab.precio || 0;
    document.querySelector('#precioKebab').value = precioBaseKebab.toFixed(2);

    // Manejar imagen del kebab
    if (kebab.foto) {
        const imagen = document.querySelector('.preview-container');
        imagen.style.backgroundImage = `url('data:image/jpeg;base64,${kebab.foto}')`;
        imagen.style.backgroundSize = 'cover';
        imagen.style.backgroundPosition = 'center';
        document.querySelector('.preview-container span').style.display = 'none'; // Ocultar texto "Subir o arrastrar imagen aquí"
    }

    // Rellenar ingredientes del kebab
    if (Array.isArray(kebab.ingredientes)) {
        const ingredientesKebab = document.querySelector('#ingredientes-kebab');
        ingredientesKebab.innerHTML = ''; // Limpiar la lista actual

        kebab.ingredientes.forEach(ingrediente => {
            const div = crearIngredienteDiv(ingrediente); // Crear div para cada ingrediente
            ingredientesKebab.appendChild(div);

            // Configurar arrastrar y soltar
            configurarArrastre(div, ingrediente);
        });
    }

    // Cargar ingredientes disponibles excluyendo los del kebab
    cargarIngredientesDisponibles(kebab.ingredientes.map(i => i.id_ingredientes));

    // Actualizar el precio recomendado
    actualizarPrecioRecomendado();
}

// Función para cargar los ingredientes disponibles
function cargarIngredientesDisponibles(ingredientesExcluidos = []) {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes') // URL de la API para obtener ingredientes
        .then(response => response.json())
        .then(data => {
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');
            ingredientesElegirContainer.innerHTML = ''; // Limpiar lista

            data.forEach(ingrediente => {
                // Agregar solo ingredientes que no están en el kebab
                if (!ingredientesExcluidos.includes(ingrediente.id_ingredientes)) {
                    const ingredienteDiv = crearIngredienteDiv(ingrediente);

                    // Configurar arrastre y soltar
                    configurarArrastre(ingredienteDiv, ingrediente);

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
    div.textContent = `${ingrediente.nombre} - €${ingrediente.precio.toFixed(2)}`;
    div.setAttribute('data-id', ingrediente.id_ingredientes);
    div.setAttribute('data-precio', ingrediente.precio);
    return div;
}

// Configurar arrastrar y soltar para un ingrediente
function configurarArrastre(div, ingrediente) {
    div.setAttribute('draggable', 'true');
    div.addEventListener('dragstart', function (e) {
        e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));
    });
}

// Actualizar el precio recomendado
function actualizarPrecioRecomendado() {
    let precioRecomendado = precioBaseKebab;
    const ingredientes = [...document.querySelector('#ingredientes-kebab').children];
    ingredientes.forEach(div => {
        const precioIngrediente = parseFloat(div.getAttribute('data-precio'));
        if (!isNaN(precioIngrediente)) {
            precioRecomendado += precioIngrediente;
        }
    });
    document.querySelector('#precioRecomendado').value = precioRecomendado.toFixed(2);
}

// Manejo del "dragover" y "drop" para las zonas de ingredientes
const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

// Permitir el "dragover" en ambas zonas
ingredientesKebabContainer.addEventListener('dragover', e => e.preventDefault());
ingredientesElegirContainer.addEventListener('dragover', e => e.preventDefault());

// Manejo del "drop" en el kebab
ingredientesKebabContainer.addEventListener('drop', function (e) {
    e.preventDefault();
    const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));

    // Verificar si el ingrediente ya está en el kebab
    if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
        alert('El ingrediente ya está añadido.');
        return;
    }

    const ingredienteDiv = crearIngredienteDiv(ingrediente);
    ingredientesKebabContainer.appendChild(ingredienteDiv);

    // Removerlo de la lista de ingredientes disponibles
    const ingredienteElegir = [...ingredientesElegirContainer.children]
        .find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
    if (ingredienteElegir) {
        ingredientesElegirContainer.removeChild(ingredienteElegir);
    }

    // Configurar arrastre y actualizar precios
    configurarArrastre(ingredienteDiv, ingrediente);
    actualizarPrecioRecomendado();
});

// Manejo del "drop" en los ingredientes disponibles
ingredientesElegirContainer.addEventListener('drop', function (e) {
    e.preventDefault();
    const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));

    // Verificar si el ingrediente ya está en la lista de ingredientes disponibles
    if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
        return;
    }

    const ingredienteDiv = crearIngredienteDiv(ingrediente);
    ingredientesElegirContainer.appendChild(ingredienteDiv);

    // Removerlo de los ingredientes del kebab
    const ingredienteKebab = [...ingredientesKebabContainer.children]
        .find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
    if (ingredienteKebab) {
        ingredientesKebabContainer.removeChild(ingredienteKebab);
    }

    // Configurar arrastre y actualizar precios
    configurarArrastre(ingredienteDiv, ingrediente);
    actualizarPrecioRecomendado();
});
