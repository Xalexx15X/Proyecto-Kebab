document.addEventListener('DOMContentLoaded', function () {
    cargarKebabs(); // cargar los kebabs al inicio
});

// defino la url base para las peticiones relacionadas con los kebabs
const apiURLKebabs = 'http://localhost/ProyectoKebab/codigo/index.php?route=kebabs'; // URL para los kebabs

// función para cargar los kebabs desde la API
function cargarKebabs() { 
    fetch(apiURLKebabs) // hago la peticion ajax para obtener los kebabs
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            if (Array.isArray(data)) { // si es un array
                mostrarKebabs(data); // mostramos las tarjetas de kebabs
            } else { // si no es un array
                console.error("La respuesta no es un array de kebabs:", data); // lanzo un error
            }
        })
        .catch(error => {  // si no es válido lanzo un error
            console.error("Error al cargar los kebabs:", error); // lanzo un error
        });
}

// función para mostrar las tarjetas de los kebabs en la cuadrícula
function mostrarKebabs(kebabs) {
    const contenedor = document.querySelector('.cuadricula-kebabs'); // busco el contenedor de las tarjetas

    // limpio tarjetas de kebabs existentes
    const tarjetasKebabs = contenedor.querySelectorAll('.tarjeta-kebab'); // busco las tarjetas de kebabs existentes
    tarjetasKebabs.forEach(tarjeta => tarjeta.remove()); // lo quito de la lista de tarjetas

    kebabs.forEach((kebab, index) => { // recorro el array de kebabs
        const tarjeta = document.createElement('div'); // creo un div para cada tarjeta
        tarjeta.classList.add('tarjeta-kebab'); // le asigno la clase tarjeta-kebab
        tarjeta.setAttribute('data-id', kebab.id_kebab); // asigno el id del kebab

        // calcular el precio recomendado sumando los precios de los ingredientes
        let precioRecomendado = 0; // inicializo el precio recomendado a 0
        if (Array.isArray(kebab.ingredientes)) { // si hay ingredientes
            kebab.ingredientes.forEach(ingrediente => { // recorro los ingredientes
                precioRecomendado += ingrediente.precio; // añado el precio a precioRecomendado
            });
        }

        // obtengo nombres de ingredientes
        const ingredientes = kebab.ingredientes.map(ing => ing.nombre).join(', '); /* los nombres de ingredientes se unen con una coma y uso el 
                                                                                    join que lo que hace es unir los ingredientes*/
        // crear estructura html de la tarjeta
        tarjeta.innerHTML = ` 
            <img src="data:image/jpeg;base64,${kebab.foto}" alt="Kebab" class="imagen-kebab"> 
            <div class="informacion-kebab">
                <h3>${kebab.nombre}</h3>
                <p><strong>Ingredientes:</strong> ${ingredientes}</p>
                <p><strong>Alergenos Del Kebab:</strong> ${kebab.descripcion ? kebab.descripcion : 'No se especifica descripción.'}</p>
                <p><strong>Precio:</strong> ${precioRecomendado.toFixed(2) + "€"}</p>
            </div>
            <div class="grupo-botones">
                <button class="boton boton-agregar-carrito" id="btn-agregar-${index}">
                    Agregar al Carrito
                </button>
            </div>
        `;

        // agrego el evento para agregar al carrito
        tarjeta.querySelector(`#btn-agregar-${index}`).addEventListener('click', function () { 
            agregarAlCarrito(kebab); // llamo a la función para agregar al carrito
        });

        contenedor.appendChild(tarjeta); // lo agrego al contenedor
    });
}

// función para agregar un kebab al carrito (local storage)
function agregarAlCarrito(kebab) { 
    // obtengo el carrito actual del local storage
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // calcular el precio total del kebab
    let precioTotal = 0; // inicializo el precio total a 0
    if (Array.isArray(kebab.ingredientes)) { // si hay ingredientes
        kebab.ingredientes.forEach(ingrediente => { // recorro los ingredientes
            precioTotal += parseFloat(ingrediente.precio); // añado el precio a precioTotal
        });
    }
    
    // convierto el precio a flotante 
    const precioNumerico = parseFloat(precioTotal.toFixed(2)); // redondeo a dos decimales como número

    // crear un objeto con solo los campos requeridos
    const kebabSimplificado = { // creamos el objeto de kebab
        nombre: kebab.nombre, // nombre del kebab
        precio: precioNumerico,  // precio numerico
        descripcion: kebab.descripcion || 'No se especifica descripción.', // recogo la descripción si existe si no digo que no existe 
        ingredientes: kebab.ingredientes.map(ing => ing.nombre) // solo los nombres de los ingredientes
    };

    // agrego el kebab al carrito
    carrito.push(kebabSimplificado);

    // guardo el carrito actualizado en el local storage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // muestro un mensaje de confirmacion
    alert(`El kebab "${kebabSimplificado.nombre}" se ha añadido al carrito.`);

    
    console.log('Carrito:', carrito);
}
