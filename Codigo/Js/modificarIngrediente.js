document.addEventListener('DOMContentLoaded', function() {
    const idIngrediente = new URLSearchParams(window.location.search).get('id_ingrediente');
    if (idIngrediente) {
        cargarIngrediente(idIngrediente);
    } else {
        console.error('No se encontró el id_ingrediente en la URL.');
    }
});

function cargarIngrediente(idIngrediente) {
    // Realizar la solicitud GET para obtener el ingrediente específico
    fetch(`http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes&id_ingrediente=${idIngrediente}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener el ingrediente');
            }
            return response.json();
        })
        .then(ingrediente => {
            // Aquí tienes los datos del ingrediente para usarlos en la interfaz
            console.log('Ingrediente:', ingrediente);
            // Completar los campos del formulario con los datos obtenidos
            document.getElementById('nombreIngrediente').value = ingrediente.nombre;
            document.getElementById('precioIngrediente').value = ingrediente.precio;
            document.getElementById('descripcionIngrediente').value = ingrediente.descripcion;

            // Cargar foto e ingredientes en otros campos de la interfaz
            cargarFotoIngrediente(ingrediente.foto);
            cargarAlergenosIngrediente(ingrediente.alergenos);
        })
        .catch(error => {
            console.error('Error al cargar el ingrediente:', error);
        });
}

function cargarFotoIngrediente(fotoBase64) {
    const previewContainer = document.querySelector('.preview-container span');
    
    // Mostrar la foto en el contenedor
    if (fotoBase64) {
        previewContainer.innerHTML = '<img src="data:image/jpeg;base64,' + fotoBase64 + '" alt="Foto Ingrediente" class="imagen-prevista">';
    } else {
        previewContainer.innerHTML = 'Subir o arrastrar imagen aquí';
    }
}

function cargarAlergenosIngrediente(alergenos) {
    const alergenosContainer = document.getElementById('alergenos-ingrediente');
    alergenosContainer.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos alérgenos

    alergenos.forEach(alergeno => {
        const alergenoElem = document.createElement('div');
        alergenoElem.classList.add('alergeno');
        alergenoElem.textContent = alergeno.nombre;
        alergenosContainer.appendChild(alergenoElem);
    });
}


function cargarFotoIngrediente(fotoBase64) {
    const previewContainer = document.querySelector('.preview-container span');
    
    // Mostrar la foto en el contenedor
    if (fotoBase64) {
        previewContainer.innerHTML = '<img src="data:image/jpeg;base64,' + fotoBase64 + '" alt="Foto Ingrediente" class="imagen-prevista">';
    } else {
        previewContainer.innerHTML = 'Subir o arrastrar imagen aquí';
    }
}

function cargarAlergenosIngrediente(alergenos) {
    const alergenosContainer = document.getElementById('alergenos-ingrediente');
    alergenosContainer.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos alérgenos

    alergenos.forEach(alergeno => {
        const alergenoElem = document.createElement('div');
        alergenoElem.classList.add('alergeno');
        alergenoElem.textContent = alergeno.nombre;
        alergenosContainer.appendChild(alergenoElem);
    });
}
