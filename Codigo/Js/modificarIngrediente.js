window.addEventListener('load', function () {
    // 
    const idIngrediente = new URLSearchParams(window.location.search).get('id_ingrediente');
    if (idIngrediente) {
        cargarIngrediente(idIngrediente);
    } else {
        console.error('No se encontró el id_ingrediente en la URL.');
    }

    const btnModificarIngrediente = document.getElementById('modificarIngredienteBtn');
    const btnBorrarCampos = document.getElementById('borrarCamposBtn');
    const inputFotoIngrediente = document.getElementById('fotoIngrediente');

    btnModificarIngrediente.addEventListener('click', modificarIngrediente);
    btnBorrarCampos.addEventListener('click', borrarCampos);
    inputFotoIngrediente.addEventListener('change', mostrarVistaPrevia);

    cargarAlergenosDisponibles(); // Cargar alérgenos disponibles

    configurarDragAndDrop();
});

const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
const apiURLAlergenos = 'http://localhost/ProyectoKebab/codigo/index.php?route=alergenos'; // URL para los alérgenos

function cargarIngrediente(idIngrediente) {
    fetch(`${apiURLIngredientes}&id_ingrediente=${idIngrediente}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const ingrediente = data.ingrediente;
            document.getElementById('nombreIngrediente').value = ingrediente.nombre || '';
            document.getElementById('precioIngrediente').value = ingrediente.precio || '';
            document.getElementById('descripcionIngrediente').value = ingrediente.descripcion || '';
            cargarFotoIngrediente(ingrediente.foto);
            cargarAlergenosIngrediente(ingrediente.alergenos);
        } else {
            console.error('No se pudo cargar el ingrediente.');
        }
    })
    .catch(error => {
        console.error('Error al cargar el ingrediente:', error);
    });
}

function cargarFotoIngrediente(fotoBase64) {
    const previewContainer = document.querySelector('.preview-container span');
    if (fotoBase64) {
        previewContainer.innerHTML = `<img src="data:image/jpeg;base64,${fotoBase64}" alt="Foto Ingrediente" class="imagen-prevista">`;
    } else {
        previewContainer.innerHTML = 'Subir o arrastrar imagen aquí';
    }
}

function cargarAlergenosIngrediente(alergenos) {
    const alergenosContainer = document.getElementById('alergenos-ingrediente');
    alergenosContainer.innerHTML = ''; // Limpiar contenedor antes de agregar los alérgenos del ingrediente

    if (Array.isArray(alergenos) && alergenos.length > 0) {
        alergenos.forEach(alergeno => {
            const alergenoElem = document.createElement('div');
            alergenoElem.classList.add('alergeno');
            alergenoElem.textContent = alergeno.nombre;
            alergenoElem.dataset.id = alergeno.id; // Asociar el ID del alérgeno
            alergenosContainer.appendChild(alergenoElem);
        });
    } else {
        alergenosContainer.textContent = 'Sin alérgenos asignados';
    }
}

function cargarAlergenosDisponibles() {
    fetch(apiURLAlergenos, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(json => {
        const alergenosContainer = document.getElementById('alergenos-a-elegir');
        alergenosContainer.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos alérgenos

        json.forEach(alergeno => {
            const alergenoElem = document.createElement('div');
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

function modificarIngrediente() {
    // Obtener valores de los campos
    const nombre = document.getElementById('nombreIngrediente').value.trim();
    const precio = parseFloat(document.getElementById('precioIngrediente').value.trim());
    const descripcion = document.getElementById('descripcionIngrediente').value.trim();
    const fotoFile = document.getElementById('fotoIngrediente').files[0];
    const alergenos = Array.from(document.getElementById('alergenos-ingrediente').children).map(elem => parseInt(elem.dataset.id));

    if (fotoFile) {
        const reader = new FileReader();
        reader.onloadend = function () {
            const fotoBase64 = reader.result.split(',')[1];
            const ingrediente = {
                nombre,
                precio,
                descripcion,
                foto: fotoBase64,
                alergenos
            };
            actualizarIngrediente(ingrediente);
        };
        reader.readAsDataURL(fotoFile);
    } else {
        const ingrediente = {
            nombre,
            precio,
            descripcion,
            alergenos
        };
        actualizarIngrediente(ingrediente);
    }
}

function actualizarIngrediente(ingrediente) {
    const idIngrediente = new URLSearchParams(window.location.search).get('id_ingrediente');
    fetch(`${apiURLIngredientes}&id_ingrediente=${idIngrediente}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(ingrediente)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ingrediente modificado con éxito.');
            window.location.href = 'index.php?menu=mantenimientoIngrediente';
        } else {
            alert('Error al modificar el ingrediente.');
        }
    })
    .catch(error => {
        console.error('Error al actualizar el ingrediente:', error);
    });
}

function borrarCampos() {
    document.getElementById('nombreIngrediente').value = '';
    document.getElementById('precioIngrediente').value = '';
    document.getElementById('descripcionIngrediente').value = '';
    document.getElementById('fotoIngrediente').value = '';
    document.querySelector('.preview-container').style.backgroundImage = 'none';
    document.querySelector('.preview-container span').style.display = 'block';
    document.getElementById('alergenos-ingrediente').innerHTML = '';
    cargarAlergenosDisponibles();
}

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

function configurarDragAndDrop() {
    const dragElems = document.querySelectorAll('.alergeno');
    const dropZones = [document.getElementById('alergenos-ingrediente'), document.getElementById('alergenos-a-elegir')];

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
