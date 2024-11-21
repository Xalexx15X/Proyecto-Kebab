document.addEventListener('DOMContentLoaded', function () {
    cargarKebabs(); // Cargar los kebabs disponibles al cargar la página
});

// Función para cargar los kebabs desde la API
function cargarKebabs() {
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=kebabs')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                mostrarKebabs(data); // Mostrar las tarjetas de kebabs
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

    kebabs.forEach((kebab, index) => {
        const tarjeta = document.createElement('div');
        tarjeta.classList.add('tarjeta-kebab');
        tarjeta.setAttribute('data-id', kebab.id_kebab);

        // Calcular el precio recomendado sumando los precios de los ingredientes
        let precioRecomendado = 0;
        if (Array.isArray(kebab.ingredientes)) {
            kebab.ingredientes.forEach(ingrediente => {
                precioRecomendado += ingrediente.precio;
            });
        }

        // Obtener nombres de ingredientes
        const ingredientes = kebab.ingredientes.map(ing => ing.nombre).join(', ');

        // Crear estructura HTML de la tarjeta
        tarjeta.innerHTML = `
            <img src="data:image/jpeg;base64,${kebab.foto}" alt="Kebab" class="imagen-kebab">
            <div class="informacion-kebab">
                <h3>${kebab.nombre}</h3>
                <p><strong>Ingredientes:</strong> ${ingredientes}</p>
                <p><strong>Alergenos Del Kebab:</strong> ${kebab.descripcion ? kebab.descripcion : 'No se especifica descripción.'}</p>
                <p><strong>Precio:</strong> €${precioRecomendado.toFixed(2)}</p>
            </div>
            <div class="grupo-botones">
                <button class="boton boton-agregar-carrito" id="btn-agregar-${index}">
                    Agregar al Carrito
                </button>
            </div>
        `;

        // Añadir evento para agregar al carrito
        tarjeta.querySelector(`#btn-agregar-${index}`).addEventListener('click', function () {
            agregarAlCarrito(kebab);
        });

        contenedor.appendChild(tarjeta); // Agregar tarjeta al contenedor
    });
}

// Función para agregar un kebab al carrito (local storage)
function agregarAlCarrito(kebab) {
    // Obtener el carrito actual del local storage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Calcular el precio total del kebab
    let precioTotal = 0;
    if (Array.isArray(kebab.ingredientes)) {
        kebab.ingredientes.forEach(ingrediente => {
            precioTotal += parseFloat(ingrediente.precio); // Aseguramos que sea numérico
        });
    }
    
    // Convertir el precio a entero o flotante según se prefiera
    const precioNumerico = parseFloat(precioTotal.toFixed(2)); // Redondeado a dos decimales como número

    // Crear un objeto con solo los campos requeridos
    const kebabSimplificado = {
        nombre: kebab.nombre,
        precio: precioNumerico, 
        descripcion: kebab.descripcion || 'No se especifica descripción.',
        ingredientes: kebab.ingredientes.map(ing => ing.nombre) // Solo los nombres de los ingredientes
    };

    // Agregar el kebab al carrito
    carrito.push(kebabSimplificado);

    // Guardar el carrito actualizado en el local storage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Mostrar mensaje de confirmación
    alert(`El kebab "${kebabSimplificado.nombre}" se ha añadido al carrito.`);

    // Log en la consola para depuración
    console.log('Carrito:', carrito);
}
