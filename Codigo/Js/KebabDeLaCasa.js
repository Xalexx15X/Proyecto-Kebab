document.addEventListener('DOMContentLoaded', function() {
    cargarKebabs(); // Llamar la función para cargar los kebabs
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

    // Limpiar tarjetas de kebabs existentes
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
                <button class="boton boton-agregar-carrito">Agregar al Carrito</button>
                <button class="boton boton-ver-mas" onclick="mostrarDescripcion(${kebab.id_kebab})">Ver más</button>
            </div>
            <p class="descripcion-kebab" id="descripcion-${kebab.id_kebab}" style="display: none;">
                ${kebab.descripcion ? kebab.descripcion : 'No se especifica descripción.'}
            </p>
        `;

        contenedor.appendChild(tarjeta); // Agregar tarjeta al contenedor
    });
}

// Función para mostrar la descripción del kebab al hacer clic en "Ver más"
function mostrarDescripcion(id_kebab) {
    const descripcion = document.getElementById(`descripcion-${id_kebab}`);

    // Alternar la visibilidad de la descripción
    if (descripcion.style.display === 'none') {
        descripcion.style.display = 'block';
    } else {
        descripcion.style.display = 'none';
    }
}
