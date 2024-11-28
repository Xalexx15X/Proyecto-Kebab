document.addEventListener('DOMContentLoaded', function () { 
    // Defino las URLs para obtener los ingredientes y los kebabs desde la API
    const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
    const apiURLKebab = 'http://localhost/ProyectoKebab/codigo/index.php?route=kebabs'; // URL para kebabs

    // función para crear el div de cada ingrediente
    function crearIngredienteDiv(ingrediente) {
        const ingredienteDiv = document.createElement('div'); // creo un nuevo div para el ingrediente
        ingredienteDiv.classList.add('ingrediente');  // le asigno la clase ingrediente
        ingredienteDiv.setAttribute('data-id', ingrediente.id_ingredientes);  // asigno el ID del ingrediente
        ingredienteDiv.setAttribute('data-precio', ingrediente.precio);  // asigno el precio
        ingredienteDiv.setAttribute('data-alergenos', JSON.stringify(ingrediente.alergenos)); // guardo los alérgenos como JSON
        ingredienteDiv.textContent = `${ingrediente.nombre} - ${ingrediente.precio + "€"}`; // muestro el nombre y el precio
        ingredienteDiv.draggable = true;  // hago que el div sea arrastrable

        // al iniciar el arrastre guardo los datos del ingrediente en el arrastre
        ingredienteDiv.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));  // almaceno el ingrediente arrastrado
        });

        return ingredienteDiv;  // retorno el div 
    }

    // función para actualizar el precio y los alérgenos cuando un ingrediente es agregado o eliminado
    function actualizarPrecioYAlergenos(ingrediente, operacion) {
        const precioRecomendado = document.getElementById('precioRecomendado');  // obtengo el campo de precio recomendado
        const descripcionKebab = document.getElementById('descripcionKebab');  // obtengo el campo de descripción de los alérgenos

        // actualizo el precio basado en la operación (sumar o restar)
        const precioActual = parseFloat(precioRecomendado.value || 0); // obtengo el precio actual
        precioRecomendado.value = (operacion === 'sumar' ? precioActual + parseFloat(ingrediente.precio) : precioActual - parseFloat(ingrediente.precio)).toFixed(2); // actualizo el precio

        // actualizo la lista de alérgenos
        let alergenosList = descripcionKebab.value.replace('Alergenos del Kebab: ', '').split(', ');  // obtengo los alérgenos actuales
        if (operacion === 'sumar') {
            alergenosList = alergenosList.concat(ingrediente.alergenos.map(a => a.nombre));  // si se suman añado los nuevos alérgenos
        } else {
            alergenosList = alergenosList.filter(alergeno => !ingrediente.alergenos.map(a => a.nombre).includes(alergeno));  // si se restan elimino los alérgenos
        }
        descripcionKebab.value = `${[...new Set(alergenosList)].join(' ')}`;  // actualizo la descripción con los alérgenos únicos
    }

    // obtengo todos los ingredientes y mostrarlos en la tabla de ingredientes disponibles
    fetch(`${apiURLIngredientes}`) // hago la peticion ajax para obtener los ingredientes
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles
            data.forEach(ingrediente => { // recorro el array de ingredientes
                const ingredienteDiv = crearIngredienteDiv(ingrediente);  // creo un div para cada ingrediente
                ingredientesElegirContainer.appendChild(ingredienteDiv);  // lo agrego a la tabla de ingredientes disponibles
            });
        });

    // Manejo del dragover y drop para la zona de ingredientes del kebab
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab'); // busco el contenedor de ingredientes del kebab
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');// busco el contenedor de ingredientes elegibles

    // permito que se pueda soltar un ingrediente sobre el área de ingredientes del kebab
    ingredientesKebabContainer.addEventListener('dragover', function (e) {
        e.preventDefault();  // evito el comportamiento predeterminado para permitir el "drop"
    });

    // cuando se suelta un ingrediente lo agrego al kebab
    ingredientesKebabContainer.addEventListener('drop', function (e) {
        e.preventDefault(); // prevengo el comportamiento por defecto del formulario
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // obtengo el ingrediente arrastrado

        // verifico si ya existe ese ingrediente en el kebab si es así no lo agrego de nuevo
        if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) { // verifico si ya existe
            alert('El ingrediente ya está añadido.'); // muestro un mensaje de error
            return;
        }

        // si no existe, agrego el ingrediente al kebab
        const ingredienteDiv = crearIngredienteDiv(ingrediente); // creo un div para cada ingrediente
        ingredientesKebabContainer.appendChild(ingredienteDiv); // lo agrego a la zona de ingredientes del kebab

        // quito el ingrediente de la lista de ingredientes disponibles
        const ingredienteElegir = [...ingredientesElegirContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString()); // busco el ingrediente en la lista de ingredientes elegibles
        if (ingredienteElegir) { // si existe
            ingredientesElegirContainer.removeChild(ingredienteElegir); // lo quito de la lista de ingredientes elegibles
        }

        // por ultimo actualizo el precio y los alérgenos
        actualizarPrecioYAlergenos(ingrediente, 'sumar');
    });

    // manejo del "dragover" y "drop" para la zona de ingredientes disponibles
    ingredientesElegirContainer.addEventListener('dragover', function (e) {
        e.preventDefault();  // permito el "drop"
    });

    // cuando se suelta un ingrediente en los ingredientes disponibles lo devuelvo a la lista
    ingredientesElegirContainer.addEventListener('drop', function (e) {
        e.preventDefault(); // prevengo el comportamiento por defecto del formulario
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // obtengo el ingrediente arrastrado

        // verifico si el ingrediente ya está en la lista de ingredientes disponibles
        if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) { // verifico si ya existe
            return;
        }

        // lo agrego de nuevo a la lista de ingredientes disponibles
        const ingredienteDiv = crearIngredienteDiv(ingrediente);
        ingredientesElegirContainer.appendChild(ingredienteDiv);

        // remuevo el ingrediente de la lista de ingredientes del kebab
        const ingredienteKebab = [...ingredientesKebabContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteKebab) { // si existe
            ingredientesKebabContainer.removeChild(ingredienteKebab); // lo quito de la lista de ingredientes del kebab
            // Actualizo el precio y los alérgenos
            actualizarPrecioYAlergenos(ingrediente, 'restar'); // actualizo el precio y los alérgenos
        }
    });

    // función para mostrar la imagen del kebab seleccionada por el usuario
    document.getElementById('fotoKebab').addEventListener('change', function (event) {
        const file = event.target.files[0];  // obtengo el archivo seleccionado
        if (!file) return;

        const reader = new FileReader(); // creo un lector de archivos para procesar la imagen
        reader.onload = function (e) { // cuando el lector termine de leer la imagen 
            const previewContainer = document.querySelector(".preview-container");  // busco el contenedor de la vista previa
            if (previewContainer) { // si existe
                previewContainer.style.backgroundImage = `url(${e.target.result})`;  // muestra la imagen seleccionada como fondo
                previewContainer.style.backgroundSize = "cover"; // ajusto la imagen para que cubra todo el contenedor
                const span = previewContainer.querySelector("span"); // busco el texto del contenedor
                if (span) span.style.display = "none";  // oculto el texto que dice "Selecciona una imagen"
            }
        };
        reader.readAsDataURL(file);  // convierto la imagen seleccionada en Base64
    });

    // funcionalidad del botón Crear para enviar los datos del kebab
    document.querySelector('.btn-1').addEventListener('click', function () { // cuando el botón se clickea
        const nombre = document.getElementById('nombreKebab').value;  // obtengo el nombre del kebab
        const foto = document.getElementById('fotoKebab').files[0];  // obtengo la foto seleccionada
        const precio = document.getElementById('precio').value;  // obtengo el precio recomendado
        const descripcion = document.getElementById('descripcionKebab').value;  // obtengo la descripción
        const ingredientes = Array.from(ingredientesKebabContainer.children).map(ingrediente => parseInt(ingrediente.getAttribute('data-id')));  // obtengo los ids de los ingredientes seleccionados
    
        // si falta algún dato ya que todos son obligatorios
        if (!nombre || !foto || !precio || !descripcion || ingredientes.length === 0) { // muestro un mensaje de error
            alert('Todos los campos son obligatorios.');
            return;
        }
    
        // creo el objeto con los datos que recojo del formulario
        const reader = new FileReader(); // creo un lector de archivos para procesar la imagen
        reader.onloadend = function () {  // cuando el lector termine de leer la imagen
            const fotoBase64 = reader.result.split(',')[1];  // extraigo solo el contenido base64
            const kebab = { // creo el objeto de kebab
                nombre: nombre, // nombre del kebab
                foto: fotoBase64, // foto del kebab
                precio_min: parseFloat(precio), // precio mínimo
                descripcion: descripcion, // descripción
                ingredientes: ingredientes // lista de ingredientes
            };
    
            fetch(apiURLKebab, { // hago la peticion ajax para crear el kebab
                method: 'POST', // uso el metodo POST
                headers: { 'Content-Type': 'application/json' }, // le digo que lo que voy a enviar en el body es json
                body: JSON.stringify(kebab) // envio el kebab creado
            }) 
                .then(response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
                    if (!response.ok) { // si el servidor respondio con un error
                        throw new Error(`Error en la respuesta: ${response.statusText}`); // lanzo un error
                    }
                    return response.json(); // proceso la respuesta como json
                })
                .then(data => { // si la respuesta es válida
                    alert('Kebab creado exitosamente.'); // muestro un mensaje de confirmacion
                    limpiarCampos(); // limpio los campos después de la confirmación
                })
                .catch(error => { // si no es válido lanzo un error
                    console.error('Error al crear el kebab:', error); 
                    alert('Hubo un error al crear el kebab.');
                });
        };
        reader.readAsDataURL(foto);  // convierto la imagen seleccionada en Base64
    });
    
// función para limpiar los campos después de crear el kebab o al pulsar el botón
function limpiarCampos() { 
    // limpio todos los campos del formulario 
    document.getElementById('nombreKebab').value = ''; // borro el nombre del kebab
    document.getElementById('fotoKebab').value = ''; // borro la foto del kebab
    document.getElementById('precio').value = ''; // borro el precio del kebab
    document.getElementById('precioRecomendado').value = ''; // borro el precio recomendado
    document.getElementById('descripcionKebab').value = ''; // borro la descripción

    // limpio la vista previa de la imagen 
    const previewContainer = document.querySelector(".preview-container"); // busco el contenedor de la vista previa
    if (previewContainer) { // si existe
        previewContainer.style.backgroundImage = ''; // quito la imagen de fondo
        const span = previewContainer.querySelector("span"); // busco el texto del contenedor
        if (span) span.style.display = ''; // vuelve a mostrar el texto inicial 
    }

    // muevo todos los ingredientes seleccionados de vuelta a la lista de disponibles
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab'); // busco el contenedor de ingredientes del kebab
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles

    const ingredientes = [...ingredientesKebabContainer.children]; // obtengo todos los ingredientes del kebab
    ingredientes.forEach(ingrediente => { // recorro el array de ingredientes
        ingredientesKebabContainer.removeChild(ingrediente); // lo quito de la lista de ingredientes del kebab
        ingredientesElegirContainer.appendChild(ingrediente); // lo agrego de nuevo a la lista de ingredientes elegibles
    });
}
    // funcionalidad del botón Borrar para limpiar todos los campos y resetear la selección
    document.querySelector('.btn-2').addEventListener('click', limpiarCampos); // cuando el botón se clickea

});
