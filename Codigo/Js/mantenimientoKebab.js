document.addEventListener('DOMContentLoaded', function () {
    cargarKebabs(); // Cargar los kebabs al inicio
});

// Función para cargar los kebabs desde la API
function cargarKebabs() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=kebabs')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                mostrarKebabs(data); // Llamar a la función para mostrar los kebabs
            } else {
                console.error("La respuesta no es un array de kebabs:", data);
            }
        })
        .catch(error => {
            console.error("Error al cargar los kebabs:", error);
        });
}

// Función para mostrar las tarjetas de los kebabs en la cuadrícula
function mostrarKebabs(kebabs) {
    const contenedor = document.querySelector('.cuadricula-kebabs');

    // Limpiar tarjetas de kebabs existentes, excepto la de "Crear Kebab"
    const tarjetasKebabs = contenedor.querySelectorAll('.tarjeta-kebab');
    tarjetasKebabs.forEach(tarjeta => tarjeta.remove());

    kebabs.forEach(kebab => {
        const tarjeta = document.createElement('div');
        tarjeta.classList.add('tarjeta-kebab');
        tarjeta.setAttribute('data-id', kebab.id_kebab);

        // Obtener nombres de ingredientes
        const ingredientes = kebab.ingredientes.map(ing => ing.nombre).join(', ');

        // Crear estructura HTML de la tarjeta
        tarjeta.innerHTML = `
            <img src="data:image/jpeg;base64,${kebab.foto}" alt="Kebab" class="imagen-kebab">
            <div class="informacion-kebab">
                <h3>${kebab.nombre}</h3>
                <p>Ingredientes: ${ingredientes}</p>
            </div>
            <div class="grupo-botones">
                <a href="index.php?menu=modificarKebab&id_kebab=${kebab.id_kebab}" class="modificarKebab">
                    <button class="boton boton-modificar">Modificar</button>
                </a>
                <button class="boton boton-borrar" onclick="eliminarKebab(${kebab.id_kebab})">Borrar</button>
            </div>
        `;

        contenedor.appendChild(tarjeta); // Agregar tarjeta al contenedor
    });
}


// Función para eliminar un kebab por su ID
function eliminarKebab(id) {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=kebabs', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id_kebab: id }) // Enviar el ID del kebab a eliminar
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) { // Verificar si la eliminación fue exitosa
                console.log("Kebab eliminado");
                cargarKebabs(); // Recargar la lista de kebabs después de la eliminación
            } else {
                console.error("Error al eliminar el kebab:", data);
            }
        })
        .catch(error => {
            console.error("Error al eliminar kebab", error);
        });
}