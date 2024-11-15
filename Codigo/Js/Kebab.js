// Al cargar la página
window.addEventListener('load', function () {
    cargarIngredientes();

    const btnCrearKebab = document.querySelector('.btn-1'); // Botón "Crear"
    const btnBorrar = document.querySelector('.btn-2'); // Botón "Borrar"
    const inputFotoKebab = document.getElementById('fotoKebab');

    btnCrearKebab.addEventListener('click', crearKebab);
    btnBorrar.addEventListener('click', borrarCampos);
    inputFotoKebab.addEventListener('change', mostrarVistaPrevia);

    configurarDragAndDrop();
});

// Variables globales
let ingredientes = [];
let ingredientesSeleccionados = []; // Array para almacenar los IDs de los ingredientes seleccionados

// Cargar ingredientes desde la API
function cargarIngredientes() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes', {
        method: 'GET'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al recoger los datos');
            }
            return response.json();
        })
        .then(json => {
            ingredientes = Array.isArray(json) ? json : json.data || [];
            if (!Array.isArray(ingredientes)) {
                throw new Error('El formato de datos no es el esperado (array de ingredientes)');
            }

            // Mostrar ingredientes en la tabla "Elegir"
            mostrarIngredientes();
        })
        .catch(error => {
            console.error('Error al cargar ingredientes:', error);
        });
}

// Mostrar todos los ingredientes en la tabla "Elegir"
function mostrarIngredientes() {
    const tablaElegir = document.getElementById('ingredientes-elegir');

    // Limpiar la tabla de ingredientes elegibles
    tablaElegir.innerHTML = '';

    // Añadir todos los ingredientes a la tabla de "Elegir"
    ingredientes.forEach(ing => {
        const elem = crearElementoIngrediente(ing);
        tablaElegir.appendChild(elem);
    });
}

// Crear elemento visual para un ingrediente
function crearElementoIngrediente(ingrediente) {
    const elem = document.createElement('div');
    elem.classList.add('ingrediente');
    elem.draggable = true;  // Todos los ingredientes serán arrastrables
    elem.textContent = ingrediente.nombre;
    elem.dataset.id = ingrediente.id_ingrediente;
    elem.dataset.tipo = ingrediente.tipo;
    elem.dataset.precio = ingrediente.precio;

    // Eventos para drag and drop
    elem.addEventListener('dragstart', dragStart);
    elem.addEventListener('dragend', dragEnd);

    return elem;
}

// Configurar drag and drop para ingredientes
function configurarDragAndDrop() {
    const dragElems = document.querySelectorAll('.ingrediente'); // Seleccionar elementos de ingredientes
    const dropZones = [
        document.getElementById('ingredientes-kebab'),
        document.getElementById('ingredientes-elegir')
    ]; // Zonas de arrastre

    dragElems.forEach(elem => {
        elem.addEventListener('dragstart', dragStart);
    });

    dropZones.forEach(zone => {
        zone.addEventListener('dragover', dragOver);
        zone.addEventListener('drop', drop);
    });
}

function dragStart(event) {
    event.dataTransfer.setData('text/plain', event.target.dataset.id);
    event.target.classList.add('dragging');
}

function dragEnd(event) {
    event.target.classList.remove('dragging');
}

function dragOver(event) {
    event.preventDefault();
}

function drop(event) {
    event.preventDefault();
    const id = event.dataTransfer.getData('text/plain');
    const ingredienteElem = document.querySelector(`[data-id="${id}"]`);
    event.currentTarget.appendChild(ingredienteElem);

    // Actualizar la selección de ingredientes
    const targetTable = event.currentTarget;
    const idIndex = ingredientesSeleccionados.indexOf(id);
    if (targetTable.id === 'ingredientes-kebab' && idIndex === -1) {
        ingredientesSeleccionados.push(id);
    } else if (targetTable.id === 'ingredientes-elegir' && idIndex !== -1) {
        ingredientesSeleccionados.splice(idIndex, 1);
    }

    // Actualizar el precio recomendado
    calcularPrecioRecomendado();
}

// Calcular precio recomendado
function calcularPrecioRecomendado() {
    const tablaKebab = document.getElementById('ingredientes-kebab');
    const ingredientesEnKebab = Array.from(tablaKebab.children);

    const precioTotal = ingredientesEnKebab.reduce((total, ing) => {
        return total + parseFloat(ing.dataset.precio || 0);
    }, 0);

    // Actualizar el campo de precio recomendado
    const precioRecomendadoField = document.getElementById('precioRecomendado');
    precioRecomendadoField.value = precioTotal.toFixed(2);
}

// Mostrar vista previa de la imagen
function mostrarVistaPrevia(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function (e) {
        const previewContainer = document.querySelector('.preview-container');
        previewContainer.style.backgroundImage = `url(${e.target.result})`;
        previewContainer.style.backgroundSize = 'cover';
        previewContainer.querySelector('span').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

// Borrar campos
function borrarCampos() {
    document.getElementById('nombreKebab').value = '';
    document.getElementById('precioKebab').value = '';
    document.getElementById('descripcionKebab').value = '';
    document.getElementById('precioRecomendado').value = '';
    document.querySelector('.preview-container').style.backgroundImage = 'none';
    document.querySelector('.preview-container span').style.display = 'block';

    const tablaKebab = document.getElementById('ingredientes-kebab');
    const tablaElegir = document.getElementById('ingredientes-elegir');
    ingredientesSeleccionados = []; // Limpiar el array de ingredientes seleccionados

    // Mover todos los ingredientes de vuelta a la tabla "Elegir"
    const ingredientesEnKebab = Array.from(tablaKebab.children);
    ingredientesEnKebab.forEach(ing => {
        tablaElegir.appendChild(ing);
    });

    cargarIngredientes(); // Recargar las tablas
}

// Crear Kebab
function crearKebab() {
    const nombre = document.getElementById('nombreKebab').value.trim();
    const precio = parseFloat(document.getElementById('precioRecomendado').value.trim());
    const descripcion = document.getElementById('descripcionKebab').value.trim();
    const fotoFile = document.getElementById('fotoKebab').files[0];

    // Validación: verificar si hay al menos un ingrediente seleccionado
    if (!nombre || !precio || !descripcion || !fotoFile || ingredientesSeleccionados.length === 0) {
        alert('Por favor, elige al menos un ingrediente.');
        return;
    }

    const reader = new FileReader();
    reader.onloadend = function () {
        const fotoBase64 = reader.result.split(',')[1];

        const kebab = {
            nombre,
            foto: fotoBase64,
            precio_min: precio,
            descripcion,
            ingredientes: ingredientesSeleccionados, // Pasar el array de IDs de los ingredientes seleccionados
        };

        fetch('http://localhost/ProyectoKebab/codigo/index.php?route=kebabs', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(kebab),
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al crear el kebab');
                }
                return response.json();
            })
            .then(data => {
                alert('Kebab creado con éxito.');
                borrarCampos();
            })
            .catch(error => {
                console.error('Error al crear kebab:', error);
                alert('Hubo un problema al crear el kebab.');
            });
    };

    reader.readAsDataURL(fotoFile);
}
