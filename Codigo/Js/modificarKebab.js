// Cargar los datos del kebab al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    const idKebab = obtenerIdKebab(); // Obtener el ID del kebab de la URL
    if (idKebab) {
        cargarDatosKebab(idKebab); // Cargar los datos del kebab específico
    }
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
            const div = document.createElement('div');
            div.className = 'ingrediente-item';
            div.textContent = `${ingrediente.nombre} - $${ingrediente.precio.toFixed(2)}`;
            ingredientesKebab.appendChild(div);
        });
    }

    // Rellenar lista de ingredientes a elegir
    if (Array.isArray(kebab.ingredientes_disponibles)) {
        const ingredientesElegir = document.querySelector('#ingredientes-elegir');
        ingredientesElegir.innerHTML = ''; // Limpiar la lista actual

        kebab.ingredientes_disponibles.forEach(ingrediente => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = ingrediente.id;
            checkbox.className = 'checkbox-ingrediente';

            // Marcar los ingredientes ya seleccionados
            if (kebab.ingredientes.some(ing => ing.id === ingrediente.id)) {
                checkbox.checked = true;
            }

            const label = document.createElement('label');
            label.textContent = `${ingrediente.nombre} - $${ingrediente.precio.toFixed(2)}`;

            const container = document.createElement('div');
            container.className = 'checkbox-container';
            container.appendChild(checkbox);
            container.appendChild(label);

            ingredientesElegir.appendChild(container);
        });
    }
}

// Escuchar el envío del formulario de modificación
document.querySelector('.btn-1').addEventListener('click', function () {
    const idKebab = obtenerIdKebab();
    const formData = obtenerDatosFormulario(); // Obtener datos del formulario

    modificarKebab(idKebab, formData); // Llamar a la función para actualizar el kebab
});

// Función para obtener los datos del formulario
function obtenerDatosFormulario() {
    const nombre = document.querySelector('#nombreKebab').value;
    const descripcion = document.querySelector('#descripcionKebab').value;
    const precio = parseFloat(document.querySelector('#precioKebab').value);
    const ingredientes = Array.from(document.querySelectorAll('.checkbox-ingrediente:checked'))
        .map(checkbox => parseInt(checkbox.value));

    const fotoInput = document.querySelector('#fotoKebab');
    const foto = fotoInput.files[0] ? fotoInput.files[0] : null;

    return { nombre, descripcion, precio, ingredientes, foto };
}

// Función para modificar el kebab en la API
function modificarKebab(id, formData) {
    const formDataObj = new FormData();
    formDataObj.append('id_kebab', id);
    formDataObj.append('nombre', formData.nombre);
    formDataObj.append('descripcion', formData.descripcion);
    formDataObj.append('precio', formData.precio);

    formData.ingredientes.forEach(ingrediente => {
        formDataObj.append('ingredientes[]', ingrediente);
    });

    if (formData.foto) {
        formDataObj.append('foto', formData.foto); // Agregar la imagen solo si se sube una nueva
    }

    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=kebabs', {
        method: 'PUT',
        body: formDataObj
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Kebab modificado exitosamente");
                window.location.href = 'index.php?menu=listaKebabs'; // Redirigir a la lista de kebabs
            } else {
                console.error("Error al modificar el kebab:", data);
            }
        })
        .catch(error => {
            console.error("Error al enviar los datos de modificación:", error);
        });
}
