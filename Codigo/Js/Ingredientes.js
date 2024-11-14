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

function cargarAlergenos() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=alergenos', {
        method: 'GET'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(json => {
        console.log('Datos de alérgenos recibidos:', json);
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
    if (!validarCampos()) {
        alert('Todos los campos son obligatorios.');
        return;
    }

    const nombre = document.getElementById('nombreIngrediente').value.trim();
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim());
    const tipo = document.getElementById('tipo').value.trim();
    const fotoInput = document.getElementById('fotoIngrediente');
    const alergenos = Array.from(document.getElementById('alergenos-ingrediente').children)
        .map(elem => parseInt(elem.dataset.id));

    const file = fotoInput.files[0];
    const reader = new FileReader();
    reader.onloadend = function() {
        const base64Foto = reader.result.split(',')[1]; // Convertir la imagen a Base64 y eliminar el encabezado

        const ingrediente = {
            nombre: nombre,
            foto: base64Foto,
            precio: precio,
            tipo: tipo,
            alergenos: alergenos
        };

        fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ingrediente)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Respuesta del servidor:', data);
            alert('Ingrediente creado con éxito.');
            borrarCampos();
        })
        .catch(error => {
            console.error('Error al crear ingrediente:', error);
            alert('Hubo un problema al crear el ingrediente.');
        });
    };

    reader.readAsDataURL(file);
}

function borrarCampos() {
    document.getElementById('nombreIngrediente').value = '';
    document.getElementById('precioIngrediente').value = '';
    document.getElementById('tipo').value = '';
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
    const tipo = document.getElementById('tipo').value.trim();
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
