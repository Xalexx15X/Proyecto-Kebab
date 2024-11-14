document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const ingredienteId = urlParams.get('id_ingrediente');  // Obtener el ID desde la URL

    // Si hay un id_ingrediente en la URL, ejecutamos el código de modificación
    if (ingredienteId) {
        cargarIngredienteParaModificar(ingredienteId);
    } else {
        // Si no hay id_ingrediente, cargamos la lista de ingredientes
        cargarIngredientes();
    }
});

// Cargar los ingredientes en la página principal
function cargarIngredientes() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                mostrarIngredientes(data);
            } else {
                console.error("La respuesta no es un array de ingredientes:", data);
            }
        })
        .catch(error => {
            console.error("Error al cargar los ingredientes:", error);
        });
}

function mostrarIngredientes(ingredientes) {
    const contenedor = document.querySelector('.cuadricula-ingrediente');

    // Limpiar el contenedor antes de agregar nuevos ingredientes, pero dejar la tarjeta de crear ingrediente
    const tarjetaCrearIngrediente = document.querySelector('.tarjeta-crear-ingrediente');

    // Si la tarjeta de crear ingrediente no existe, la creamos
    if (!tarjetaCrearIngrediente) {
        const tarjetaCrear = document.createElement('a');
        tarjetaCrear.href = 'index.php?menu=crearIngrediente';
        tarjetaCrear.classList.add('tarjeta-crear-ingrediente');
        tarjetaCrear.style.textDecoration = 'none';

        tarjetaCrear.innerHTML = `
            <div class="contenedor-crear">
                <span class="icono-mas">+</span>
                <p>Crear Ingrediente</p>
            </div>
        `;

        contenedor.appendChild(tarjetaCrear);  // Añadimos la tarjeta de crear al contenedor
    }

    // Limpiar el contenedor de los ingredientes, pero dejamos la tarjeta de creación intacta
    const ingredientesContenedor = contenedor.querySelectorAll('.tarjeta-ingrediente');
    ingredientesContenedor.forEach(tarjeta => tarjeta.remove());  // Eliminar solo las tarjetas de ingredientes, no la de crear

    ingredientes.forEach(ingrediente => {
        const tarjeta = document.createElement('div');
        tarjeta.classList.add('tarjeta-ingrediente');
        tarjeta.setAttribute('data-id', ingrediente.id_ingredientes);

        // Si el array de alérgenos está presente, se muestra correctamente
        const alergenos = ingrediente.alergenos.length > 0 ? ingrediente.alergenos.map(a => a.nombre).join(', ') : 'No se especificaron alérgenos';

        // Crear la estructura HTML para cada ingrediente
        tarjeta.innerHTML = `
            <img src="data:image/jpeg;base64,${ingrediente.foto}" alt="Ingrediente" class="imagen-ingrediente">
            <div class="informacion-ingrediente">
                <h3>${ingrediente.nombre}</h3>
                <p>Alergenos: ${alergenos}</p> <!-- Usamos join() para unir los alérgenos -->
            </div>
            <div class="grupo-botones">
                <a href="index.php?menu=modificarIngrediente" class="modificarIngrediente">
                    <button class="boton boton-modificar">Modificar</button>
                </a>
                <button class="boton boton-borrar" onclick="eliminarIngrediente(${ingrediente.id_ingredientes})">Borrar</button>
            </div>
        `;

        contenedor.appendChild(tarjeta); // Agregar la tarjeta al contenedor
    });
}

function eliminarIngrediente(id) {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_ingrediente: id })  // Enviar el ID del ingrediente a eliminar
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) { // Verifica si el servidor responde indicando éxito
            console.log("Ingrediente eliminado");
            cargarIngredientes(); // Recargar la lista de ingredientes después de la eliminación
        } else {
            console.error("Error al eliminar el ingrediente:", data);
        }
    })
    .catch(error => {
        console.error("Error al eliminar ingrediente", error);
    });
}

