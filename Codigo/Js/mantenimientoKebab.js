document.addEventListener('DOMContentLoaded', function () {
    cargarKebabs(); // cargo los kebabs al inicio
});

const urlApiKebabs = 'http://localhost/ProyectoKebab/codigo/index.php?route=kebabs'; // URL de la api para los kebabs

// funcion para cargar los kebabs desde la api
function cargarKebabs() {
    fetch(urlApiKebabs) // hago la peticion ajax para obtener los kebabs
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            if (Array.isArray(data)) { // si es un array
                mostrarKebabs(data); // mostramos los kebabs
            } else { // si no es un array
                console.error("La respuesta no es un array de kebabs:", data); // lanzo un error
            }
        })
        .catch(error => {
            console.error("Error al cargar los kebabs:", error); // lanzo un error
    });
}

// funcion para mostrar las tarjetas de los kebabs en la cuadricula
function mostrarKebabs(kebabs) { // funcion para mostrar las tarjetas de los kebabs en la cuadricula
    const contenedor = document.querySelector('.cuadricula-kebabs'); // busco el contenedor de las tarjetas
    // limpio las tarjetas de kebabs existentes, excepto la de "Crear Kebab"
    const tarjetasKebabs = contenedor.querySelectorAll('.tarjeta-kebab'); // busco las tarjetas de kebabs existentes
    tarjetasKebabs.forEach(tarjeta => tarjeta.remove()); // lo quito de la lista de tarjetas
 
    // ordeno los kebabs por ID de forma descendente (los mas recientes primero)
    kebabs.sort((a, b) => b.id_kebab - a.id_kebab);

    // creo las tarjetas de los kebabs
    kebabs.forEach(kebab => { // recorro el array de kebabs
        const tarjeta = document.createElement('div'); // creo un div para cada tarjeta
        tarjeta.classList.add('tarjeta-kebab'); // le asigno la clase tarjeta-kebab
        tarjeta.setAttribute('data-id', kebab.id_kebab); // asigno el id del kebab

        // obtengo los nombres de ingredientes
        const ingredientes = kebab.ingredientes.map(ing => ing.nombre).join(', ');

        // creo la estructura html de la tarjeta
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

        contenedor.appendChild(tarjeta); // lo agrego al contenedor
    });
}


// funcion para eliminar un kebab por su id
function eliminarKebab(id) {
    fetch(urlApiKebabs, { // hago la peticion ajax para eliminar el kebab
        method: 'DELETE', // uso el metodo DELETE
        headers: {
            'Content-Type': 'application/json' // le digo que lo que voy a enviar en el body es json
        },
        body: JSON.stringify({ id_kebab: id })  // envio el id del kebab a eliminar
    })
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            if (data.success) { // si la respuesta indica éxito
                console.log("Kebab eliminado"); 
                cargarKebabs(); // recargo la lista de kebabs despues de la eliminacion
            } else {
                console.error("Error al eliminar el kebab:", data); // lanzo un error
            }
        })
        .catch(error => {
            console.error("Error al eliminar kebab", error);
    });
    
}