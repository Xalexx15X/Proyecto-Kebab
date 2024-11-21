window.addEventListener('load', function() {
    cargarAlergenos();

    const btnCrearIngrediente = document.getElementById('crearIngredienteBtn');
    const btnBorrarCampos = document.getElementById('borrarCamposBtn');
    const inputFotoIngrediente = document.getElementById('fotoIngrediente');

    btnCrearIngrediente.addEventListener('click', crearIngrediente);
    btnBorrarCampos.addEventListener('click', borrarCampos);
    inputFotoIngrediente.addEventListener('change', mostrarVistaPrevia);

    configurarDragAndDrop();
});

const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
const apiURLAlergenos = 'http://localhost/ProyectoKebab/codigo/index.php?route=alergenos'; // URL para los alérgenos

function cargarAlergenos() {
    fetch(apiURLAlergenos, {
        method: 'GET'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al Recoger los datos');
        }
        return response.json();
    })
    .then(json => {
        const alergenosContainer = document.getElementById('ingredientes-elegir');
        alergenosContainer.innerHTML = '';
        json.forEach(alergeno => {
            var alergenoElem = document.createElement('div');
            alergenoElem.classList.add('alergeno');
            alergenoElem.draggable = true;
            alergenoElem.textContent = alergeno.nombre;
            alergenoElem.dataset.id = alergeno.id_alergenos; 
            alergenosContainer.appendChild(alergenoElem);
        });

        configurarDragAndDrop();
    })
    .catch(error => {
        console.error('Error al cargar alérgenos:', error);
    });
}

function crearIngrediente() {
    // Obtener valores de los campos
    const nombre = document.getElementById('nombreIngrediente').value.trim();
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim());
    const fotoFile = document.getElementById('fotoIngrediente').files[0];
    const alergenos = Array.from(document.getElementById('alergenos-ingrediente').children).map(elem => parseInt(elem.dataset.id));

    const reader = new FileReader();
    reader.onloadend = function() {
        const fotoBase64 = reader.result.split(',')[1];

        // Crear el objeto de ingrediente
        const ingrediente = {
            nombre: nombre,
            foto: fotoBase64,
            precio: precio,
            alergenos: alergenos
        };

        // Enviar la solicitud de creación de ingrediente
        fetch(apiURLIngredientes, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ingrediente)
        })
        .then(response => {
            console.log("Código de estado de la respuesta:", response.status);
            borrarCampos();
            // Intentar parsear la respuesta como JSON
            return response.json().catch(() => {
                // Si el parseo falla, mostrar la respuesta como texto
                return response.text().then(text => {
                    throw new Error(`Respuesta no válida del servidor: ${text}`);
                    
                });
            });
        })
        .then(data => {
            // Verificar que el servidor realmente envió un JSON válido
            console.log('Respuesta del servidor:', data);
            if (data.success) {
                alert('Ingrediente creado con éxito.');
                borrarCampos();
            } else {
                throw new Error(data.message || "Error desconocido en la creación del ingrediente.");
            }
        });
    };
    reader.readAsDataURL(fotoFile);
}

function borrarCampos() {
    document.getElementById('nombreIngrediente').value = '';
    document.getElementById('precioIngrediente').value = '';
    document.getElementById('fotoIngrediente').value = '';
    document.querySelector('.preview-container').style.backgroundImage = 'none';
    document.querySelector('.preview-container span').style.display = 'block';
    document.getElementById('alergenos-ingrediente').innerHTML = '';
    cargarAlergenos();
}

function mostrarVistaPrevia(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.querySelector('.preview-container');
        previewContainer.style.backgroundImage = `url(${e.target.result})`;
        previewContainer.style.backgroundSize = 'cover'; 
        previewContainer.querySelector('span').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function validarCampos() {
    const nombre = document.getElementById('nombreIngrediente').value.trim();
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim());
    const foto = document.getElementById('fotoIngrediente').files[0];

    if (!nombre || !precio || !tipo || !foto) {
        alert('Todos los campos son obligatorios.');
        return false;
    }

    if (isNaN(precio) || precio <= 0) {
        alert('Por favor, introduce un precio válido.');
        return false;
    }

    return true;
}

function configurarDragAndDrop() {
    const dragElems = document.querySelectorAll('.alergeno');
    const dropZones = [document.getElementById('alergenos-ingrediente'), document.getElementById('ingredientes-elegir')];

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
}

function dragOver(event) {
    event.preventDefault();
}

function drop(event) {
    event.preventDefault();
    const id = event.dataTransfer.getData('text/plain');
    const alergenoElem = document.querySelector(`[data-id="${id}"]`);
    event.currentTarget.appendChild(alergenoElem);
}
