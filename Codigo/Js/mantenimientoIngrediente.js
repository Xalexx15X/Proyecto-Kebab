document.addEventListener('DOMContentLoaded', function() {
    // obtengo los parámetros de la url
    const urlParams = new URLSearchParams(window.location.search);
    const ingredienteId = urlParams.get('id_ingrediente');  // Obtener el ID desde la URL

    // si hay un id_ingrediente en la URL, ejecuto el código de modificación
    if (ingredienteId) {
        cargarIngredienteParaModificar(ingredienteId); // cargo el ingrediente para modificar
    } else {
        // si no hay id_ingrediente, cargo la lista de ingredientes
        cargarIngredientes();
    }
});

const urlApiIngrediente = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL de la api para los ingredientes

// funcion para cargar los ingredientes en la pagina principal
function cargarIngredientes() {
    fetch(urlApiIngrediente) // hago la peticion ajax para obtener los ingredientes
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            if (Array.isArray(data)) { // si es un array
                mostrarIngredientes(data); // mostramos los ingredientes
            } else { // si no es un array
                console.error("La respuesta no es un array de ingredientes:", data); // lanzo un error
            }
        })
        .catch(error => { // si no es válido lanzo un error
            console.error("Error al cargar los ingredientes:", error); // lanzo un error
        });
}

function mostrarIngredientes(ingredientes) { // funcion para mostrar los ingredientes
    const contenedor = document.querySelector('.cuadricula-ingrediente'); // busco el contenedor de ingredientes

    // limpio el contenedor de los ingredientes, pero dejamos la tarjeta de creacion intacta
    const ingredientesContenedor = contenedor.querySelectorAll('.tarjeta-ingrediente'); // busco las tarjetas de ingredientes
    ingredientesContenedor.forEach(tarjeta => tarjeta.remove());  // lo quito de la lista de ingredientes, no la de crear

    // ordeno los ingredientes por ID de forma descendente (los mas recientes primero)
    ingredientes.sort((a, b) => b.id_ingredientes - a.id_ingredientes);

    // creo las tarjetas de ingredientes
    ingredientes.forEach(ingrediente => {
        const tarjeta = document.createElement('div'); // creo un div para cada ingrediente
        tarjeta.classList.add('tarjeta-ingrediente'); // le asigno la clase tarjeta-ingrediente
        tarjeta.setAttribute('data-id', ingrediente.id_ingredientes); // asigno el id del ingrediente

        // si el array de alergenos esta presente, se muestra correctamente
        const alergenos = ingrediente.alergenos.length > 0 ? ingrediente.alergenos.map(a => a.nombre).join(', ') : 'No se especificaron alérgenos'; // uno los alérgenos con una coma y uso el join que lo que hace es unir los ingredientes

        // creo la estructura html para cada tarjeta de ingredientes con sus datos
        tarjeta.innerHTML = `
            <img src="data:image/jpeg;base64,${ingrediente.foto}" alt="Ingrediente" class="imagen-ingrediente">
            <div class="informacion-ingrediente">
                <h3>${ingrediente.nombre}</h3>
                <p>Alergenos: ${alergenos}</p> <!-- Usamos join() para unir los alérgenos -->
            </div>
            <div class="grupo-botones">
                <a href="index.php?menu=modificarIngrediente&id_ingrediente=${ingrediente.id_ingredientes}" class="modificarIngrediente">
                    <button class="boton boton-modificar">Modificar</button>
                </a>
                <button class="boton boton-borrar" onclick="eliminarIngrediente(${ingrediente.id_ingredientes})">Borrar</button>
            </div>
        `;

        contenedor.appendChild(tarjeta); // lo agrego al contenedor
    });
}

function eliminarIngrediente(id) {
    fetch(urlApiIngrediente, {
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