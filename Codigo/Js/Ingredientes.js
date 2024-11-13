window.addEventListener('load', function() {
    // Cargar los alérgenos al cargar la página
    cargarAlergenos();

    // Programar los botones
    const btnCrearIngrediente = document.getElementById('crearIngredienteBtn');
    const btnBorrarCampos = document.getElementById('borrarCamposBtn');
    const inputFotoIngrediente = document.getElementById('fotoIngrediente');

    btnCrearIngrediente.addEventListener('click', crearIngrediente);
    btnBorrarCampos.addEventListener('click', borrarCampos);
    inputFotoIngrediente.addEventListener('change', mostrarVistaPrevia);

    // Configurar drag and drop
    configurarDragAndDrop();
});

function cargarAlergenos() {
    var peticion = new Request('http://localhost/ProyectoKebab/codigo/index.php?route=alergenos', {
        method: 'GET'
    });

    fetch(peticion)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
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
                alergenoElem.dataset.id = alergeno.id;
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
    const precio = document.getElementById('precioIngrediente').value.trim();
    const descripcion = document.getElementById('descripcionIngrediente').value.trim();
    const foto = document.getElementById('fotoIngrediente').files[0];
    const alergenos = Array.from(document.getElementById('alergenos-ingrediente').children)
        .map(elem => elem.dataset.id);

    var formulario = new FormData();
    formulario.append('nombre', nombre);
    formulario.append('precio', precio);
    formulario.append('descripcion', descripcion);
    formulario.append('foto', foto);
    formulario.append('alergenos', JSON.stringify(alergenos));

    var peticion = new Request('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes', {
        method: 'POST',
        body: formulario
    });

    fetch(peticion)
        .then(response => response.json())
        .then(data => {
            alert('Ingrediente creado con éxito.');
            borrarCampos();
        })
        .catch(error => {
            console.error('Error al crear ingrediente:', error);
            alert('Hubo un problema al crear el ingrediente.');
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
    cargarAlergenos();
}

function mostrarVistaPrevia(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewContainer = document.querySelector('.preview-container');
        previewContainer.style.backgroundImage = `url(${e.target.result})`;
        previewContainer.querySelector('span').style.display = 'none';
    };
    reader.readAsDataURL(file);
}

function validarCampos() {
    const nombre = document.getElementById('nombreIngrediente').value.trim();
    const precio = document.getElementById('precioIngrediente').value.trim();
    const descripcion = document.getElementById('descripcionIngrediente').value.trim();
    const foto = document.getElementById('fotoIngrediente').files[0];
    return nombre && precio && descripcion && foto;
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
