window.addEventListener('load', function() {
    cargarAlergenos(); // cargo los alérgenos al inicio

    const btnCrearIngrediente = document.getElementById('crearIngredienteBtn'); // boton para crear ingrediente
    const btnBorrarCampos = document.getElementById('borrarCamposBtn'); // boton para borrar campos
    const inputFotoIngrediente = document.getElementById('fotoIngrediente'); // input para subir una foto de ingrediente

    btnCrearIngrediente.addEventListener('click', crearIngrediente); // configuro el evento al boton de crear ingrediente
    btnBorrarCampos.addEventListener('click', borrarCampos);  // configuro el evento al boton de borrar campos
    inputFotoIngrediente.addEventListener('change', mostrarVistaPrevia); // configuro el evento al input de subir foto

    configurarDragAndDrop(); // configura el drag and drop
});

const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
const apiURLAlergenos = 'http://localhost/ProyectoKebab/codigo/index.php?route=alergenos'; // URL para los alérgenos

// funcion para cargar los alérgenos
function cargarAlergenos() {
    fetch(apiURLAlergenos, { // hago la peticion ajax para obtener los alérgenos
        method: 'GET'  // uso el get
    })
    .then(response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
        if (!response.ok) { // si el servidor respondio con un error
            throw new Error('Error al Recoger los datos'); // lanzo un error
        }
        return response.json(); // proceso la respuesta como json
    })
    .then(json => { // si la respuesta es válida
        const alergenosContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles
        alergenosContainer.innerHTML = ''; // limpio el contenedor antes de meter algo nuevo
        json.forEach(alergeno => { // recorro el array de alérgenos
            var alergenoElem = document.createElement('div'); // creo un div para cada alérgeno
            alergenoElem.classList.add('alergeno'); // le asigno la clase 'alergeno'
            alergenoElem.draggable = true; // hago que el div sea arrastrable
            alergenoElem.textContent = alergeno.nombre; // muestro el nombre del alérgeno
            alergenoElem.dataset.id = alergeno.id_alergenos; // asigno el id del alérgeno
            alergenosContainer.appendChild(alergenoElem); // lo agrego al contenedor
        });

        configurarDragAndDrop(); // le meto el metodo para configurar el drag and drop
    })
    .catch(error => {
        console.error('Error al cargar alérgenos:', error);
    });
}

function crearIngrediente() { // funcion para crear un ingrediente
    // obtengo los valores de los campos
    const nombre = document.getElementById('nombreIngrediente').value.trim(); // nombre del ingrediente
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim()); // precio del ingrediente
    const fotoFile = document.getElementById('fotoIngrediente').files[0]; // foto del ingrediente
    const alergenos = Array.from(document.getElementById('alergenos-ingrediente').children).map(elem => parseInt(elem.dataset.id)); // recojo los ID de los alérgenos y lo meto en un array 

    const reader = new FileReader(); // creamos un lector de archivos para procesar la imagen
    reader.onloadend = function() { // cuando el lector termine de leer la imagen
        const fotoBase64 = reader.result.split(',')[1]; // extraemos solo el contenido base64

        // creo el objeto con los datos que recojo del formulario
        const ingrediente = { // creamos el objeto de ingrediente
            nombre: nombre, // nombre del ingrediente
            foto: fotoBase64, // foto del ingrediente
            precio: precio, // precio del ingrediente
            alergenos: alergenos // alérgenos del ingrediente
        };

        fetch(apiURLIngredientes, { // hago la peticion ajax para crear el ingrediente
            method: 'POST', // uso el metodo POST
            headers: { // le digo que lo que voy a enviar en el body es json
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ingrediente) // envio el ingrediente creado
        })
        .then(response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
            console.log("Código de estado de la respuesta:", response.status);
            borrarCampos(); // borro los campos del formulario
        })
        .then(data => {
            // si la respuesta indica que el servidor respondio correctamente
            console.log('Respuesta del servidor:', data);
            if (data.success) { // si la respuesta indica éxito
                alert('Ingrediente creado con éxito.'); // muestro un mensaje de confirmacion
                borrarCampos(); // borro los campos del formulario
            } else { // si no es éxito
                throw new Error(data.message || "Error desconocido en la creación del ingrediente."); // lanzo un error
            }
        });
    };
    reader.readAsDataURL(fotoFile); // convierto la imagen seleccionada en Base64
}

// funcion para borrar los campos del formulario
function borrarCampos() {
    document.getElementById('nombreIngrediente').value = '';
    document.getElementById('precioIngrediente').value = '';
    document.getElementById('fotoIngrediente').value = '';
    document.querySelector('.preview-container').style.backgroundImage = 'none';
    document.querySelector('.preview-container span').style.display = 'block';
    document.getElementById('alergenos-ingrediente').innerHTML = '';
    cargarAlergenos();
}

// funcion para mostrar la vista previa de la imagen seleccionada
function mostrarVistaPrevia(event) { 
    const file = event.target.files[0]; // obtengo el archivo seleccionado
    const reader = new FileReader(); // creamos un lector de archivos para procesar la imagen
    reader.onload = function(e) { // cuando el lector termine de leer la imagen
        const previewContainer = document.querySelector('.preview-container'); // busco el contenedor de la vista previa
        previewContainer.style.backgroundImage = `url(${e.target.result})`; // muestro la imagen como fondo
        previewContainer.style.backgroundSize = 'cover'; // ajusto la imagen para que cubra todo el contenedor
        previewContainer.querySelector('span').style.display = 'none'; // oculto el texto del contenedor
    };
    reader.readAsDataURL(file); // convierto la imagen seleccionada en Base64
}

// funcion para validar los campos del formulario
function validarCampos() { 
    const nombre = document.getElementById('nombreIngrediente').value.trim(); // nombre del ingrediente
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim()); // precio del ingrediente
    const foto = document.getElementById('fotoIngrediente').files[0]; // foto del ingrediente

    if (!nombre || !precio || !tipo || !foto) { // si faltan algunos campos ya que todos son obligatorios
        alert('Todos los campos son obligatorios.'); // muestro un mensaje de error
        return false;
    }

    if (isNaN(precio) || precio <= 0) { // si el precio no es válido
        alert('Por favor, introduce un precio válido.'); // muestro un mensaje de error
        return false; // si no es válido, salgo de la funcion
    }

    return true;
}

// funcion para configurar el drag and drop
function configurarDragAndDrop() { 

    // busco los elementos que pueden ser arrastrados y los elementos donde se pueden soltar 
    const dragElems = document.querySelectorAll('.alergeno'); 
    const dropZones = [document.getElementById('alergenos-ingrediente'), document.getElementById('ingredientes-elegir')]; 

    dragElems.forEach(elem => { // para cada elemento que pueda arrastrarse
        elem.addEventListener('dragstart', dragStart); // configura el evento de arrastre
    });

    dropZones.forEach(zone => { // para cada zona donde se puede soltar
        zone.addEventListener('dragover', dragOver); // configura el evento de arrastre
        zone.addEventListener('drop', drop); // configura el evento de soltar
    });
}

// funcion que hace el drag start del elemento arrastrable
function dragStart(event) { 
    event.dataTransfer.setData('text/plain', event.target.dataset.id); // envio el id del elemento arrastrado
}

// funcion que hace el drag over del elemento arrastrable
function dragOver(event) { 
    event.preventDefault(); 
}

// funcion que hace el drop del elemento arrastrable
function drop(event) {
    event.preventDefault(); // prevengo el comportamiento por defecto del formulario
    const id = event.dataTransfer.getData('text/plain'); // obtengo el id del elemento arrastrado
    const alergenoElem = document.querySelector(`[data-id="${id}"]`); // busco el elemento con el id
    event.currentTarget.appendChild(alergenoElem); // lo agrego al elemento donde se puede soltar
}
