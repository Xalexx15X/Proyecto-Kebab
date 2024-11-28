document.addEventListener('DOMContentLoaded', function () { 
    
    // definio las url base para las peticiones relacionadas con ingredientes
    const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes'; // URL para ingredientes
    const precioRecomendado = document.getElementById('precioRecomendado'); // busco el campo de precio recomendado
    precioRecomendado.value = '4.00 €'; // precio base al cargar la pagina

    // función para crear el div de cada ingrediente
    function crearIngredienteDiv(ingrediente) { 
        const ingredienteDiv = document.createElement('div'); // creo un nuevo div para el ingrediente
        ingredienteDiv.classList.add('ingrediente');  // le asigno la clase ingrediente
        ingredienteDiv.setAttribute('data-id', ingrediente.id_ingredientes);  // asigno el id del ingrediente
        ingredienteDiv.setAttribute('data-alergenos', JSON.stringify(ingrediente.alergenos)); 
        ingredienteDiv.textContent = `${ingrediente.nombre}`; // muestro el nombre del ingrediente
        ingredienteDiv.draggable = true;  // hago que el div sea arrastrable

        // al iniciar el arrastre guardo los datos del ingrediente en el arrastre 
        ingredienteDiv.addEventListener('dragstart', function (e) { // cuando el arrastre se inicia
            e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));  // almaceno el ingrediente arrastrado
        }); 
        return ingredienteDiv;  // retorno el div
    }

    // función para actualizar el precio y los alérgenos
    function actualizarPrecioYAlergenos(ingrediente, operacion) { 
        const precioRecomendado = document.getElementById('precioRecomendado');  // obtengo el campo de precio recomendado
        const descripcionKebab = document.getElementById('descripcionKebab');  // obtengo el campo de descripción de los alérgenos

        // actualizo el precio base al cargar la pagina
        const precioBase = 4; // el precio base del kebab
        let precioActual = parseFloat(precioRecomendado.value || precioBase); // usamos el precio base si no hay precio actual

        // contar la cantidad de ingredientes en el kebab
        const cantidadIngredientes = document.getElementById('ingredientes-kebab').children.length;

        if (operacion === 'sumar') { // si se suman
            if (cantidadIngredientes <= 3) { // si no se cobran los primeros 3 ingredientes
                precioRecomendado.value = precioBase.toFixed(2); // solo el precio base para 3 ingredientes
            } else {
                // a partir del cuarto ingrediente, cobramos el precio + 15%
                precioRecomendado.value = (precioActual + parseFloat(ingrediente.precio) * 1.15).toFixed(2); // Añadimos el 15%
            }
        } else if (operacion === 'restar') { // si se restan
            if (cantidadIngredientes > 3) { 
                // a partir del cuarto ingrediente, restamos el precio + 15%
                precioRecomendado.value = (precioActual - parseFloat(ingrediente.precio) * 1.15).toFixed(2);
            } else {
                // si es uno de los primeros 3, no cambia el precio base
                precioRecomendado.value = precioBase.toFixed(2);
            }
        }

        // actualizar alérgenos
        let alergenosList = descripcionKebab.value.replace('Alergenos del Kebab: ', '').split(', '); // obtengo los alérgenos actuales
        if (operacion === 'sumar') { // si se suman añado los nuevos alérgenos
            alergenosList = alergenosList.concat(ingrediente.alergenos.map(a => a.nombre)); // si se suman añado los nuevos alérgenos concateno los nuevos alérgenos
        } else {
            alergenosList = alergenosList.filter(alergeno => !ingrediente.alergenos.map(a => a.nombre).includes(alergeno)); // si se restan elimino los alérgenos
        }
        descripcionKebab.value = `Alergenos del Kebab: ${[...new Set(alergenosList)].join(', ')}`; // actualizo la descripción con los alérgenos únicos
    }


    // obtengo ingredientes y mostrarlos
    fetch(apiURLIngredientes) // hago la peticion ajax para obtener los ingredientes
        .then(response => response.json()) // proceso la respuesta como json
        .then(data => { // si la respuesta es válida
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles
            data.forEach(ingrediente => { // recorro el array de ingredientes
                const ingredienteDiv = crearIngredienteDiv(ingrediente); // creo un div para cada ingrediente
                ingredientesElegirContainer.appendChild(ingredienteDiv); // lo agrego a la tabla de ingredientes disponibles
            });
        });

    // manejo el arrastre y el drop
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');  // busco el contenedor de ingredientes del kebab
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles

    // permito que se pueda soltar un ingrediente sobre el área de ingredientes del kebab
    ingredientesKebabContainer.addEventListener('dragover', function (e) {
        e.preventDefault();  // evito el comportamiento predeterminado para permitir el "drop"
    });

    // cuando se suelta un ingrediente lo agrego al kebab
    ingredientesKebabContainer.addEventListener('drop', function (e) {
        e.preventDefault(); // prevengo el comportamiento por defecto del formulario
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));  // obtengo el ingrediente arrastrado

        // verifico si ya existe ese ingrediente en el kebab si es así no lo agrego de nuevo
        if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            alert('El ingrediente ya está añadido.'); // muestro un mensaje de error
            return;
        }

        const ingredienteDiv = crearIngredienteDiv(ingrediente); // creo un div para cada ingrediente
        ingredientesKebabContainer.appendChild(ingredienteDiv); // lo agrego a la zona de ingredientes del kebab

        // quito el ingrediente de la lista de ingredientes disponibles
        const ingredienteElegir = [...ingredientesElegirContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteElegir) { // si existe
            ingredientesElegirContainer.removeChild(ingredienteElegir); // lo quito de la lista de ingredientes elegibles
        }

        // por ultimo actualizo el precio y los alérgenos
        actualizarPrecioYAlergenos(ingrediente, 'sumar');
    });

    // manejo del "dragover" y "drop" para la zona de ingredientes disponibles
    ingredientesElegirContainer.addEventListener('dragover', function (e) {
        e.preventDefault();
    });

    // cuando se suelta un ingrediente en los ingredientes disponibles lo devuelvo a la lista
    ingredientesElegirContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));

        // verifico si el ingrediente ya está en la lista de ingredientes disponibles
        if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            return;
        }

        // lo agrego de nuevo a la lista de ingredientes disponibles 
        const ingredienteDiv = crearIngredienteDiv(ingrediente); // creo un div para cada ingrediente
        ingredientesElegirContainer.appendChild(ingredienteDiv); // lo agrego a la tabla de ingredientes disponibles

        // quito el ingrediente de los ingredientes del kebab
        const ingredienteKebab = [...ingredientesKebabContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString()); // busco el ingrediente en la lista de ingredientes del kebab
        if (ingredienteKebab) { // si existe
            ingredientesKebabContainer.removeChild(ingredienteKebab); // lo quito de la lista de ingredientes del kebab
            // actualizo el precio y los alérgenos
            actualizarPrecioYAlergenos(ingrediente, 'restar'); 
        }
    });

    // funcionalidad del botón "Crear" para enviar los datos del kebab
    document.querySelector('.btn-1').addEventListener('click', function () {
        const precioRecomendado = parseFloat(document.getElementById('precioRecomendado').value) || 0; // recojo el precio recomendado
        const descripcionKebab = document.getElementById('descripcionKebab').value; // recojo la descripción
        const ingredientes = Array.from(document.getElementById('ingredientes-kebab').children).map(ingrediente => ({ // recojo los ingredientes del kebab
            id: parseInt(ingrediente.getAttribute('data-id')), // id del ingrediente
            nombre: ingrediente.textContent.trim()  // nombre del ingrediente
        }));
    
        // creo el objeto con los datos que recojo del formulario
        const kebab = {
            nombre: 'Kebab al gusto', // nombre fijo
            precio: parseFloat(precioRecomendado.toFixed(2)), // precio numérico redondeado pero guardado como número
            descripcion: descripcionKebab || 'Sin descripción', // aseguramos que haya una descripción
            ingredientes: ingredientes.map(ing => ing.nombre) // lista de nombres de ingredientes
        };
    
        // envio el kebab creado
        let carrito = JSON.parse(localStorage.getItem('carrito')) || []; // obtengo carrito existente
        carrito.push(kebab); // añadir el kebab
        localStorage.setItem('carrito', JSON.stringify(carrito)); // guardar actualizado
    
        // muestro un mensaje de confirmacion
        alert('Kebab al gusto añadido al carrito.');
    
   
        console.log('Carrito en localStorage:', JSON.parse(localStorage.getItem('carrito')));
    });
    

    // función para limpiar los campos (Borrar)
    function limpiarCampos() {
        const precioRecomendado = document.getElementById('precioRecomendado'); // busco el campo de precio recomendado
        const descripcionKebab = document.getElementById('descripcionKebab'); // busco el campo de descripción de los alérgenos
        const ingredientesKebabContainer = document.getElementById('ingredientes-kebab'); // busco el contenedor de ingredientes del kebab
        const ingredientesElegirContainer = document.getElementById('ingredientes-elegir'); // busco el contenedor de ingredientes elegibles

        // restablezco el precio 
        precioRecomendado.value = '4.00 €'; 

        // restablezco la descripción
        descripcionKebab.value = 'Alergenos del Kebab:'; 

        // limpio ingredientes del kebab y devolver a la lista de ingredientes disponibles
        [...ingredientesKebabContainer.children].forEach(ingrediente => {
            ingredientesKebabContainer.removeChild(ingrediente); // lo quito de la lista de ingredientes del kebab
            ingredientesElegirContainer.appendChild(ingrediente); // lo agrego a la lista de ingredientes elegibles
            // actualizo el precio de cada ingrediente removido
            const ingredienteData = JSON.parse(ingrediente.getAttribute('data-id')); // obtengo el id del ingrediente
            actualizarPrecioYAlergenos(ingredienteData, 'restar'); // actualizo el precio y los alérgenos
        });
    }

    // funcionalidad del botón "Borrar" para limpiar todos los campos y resetear la selección
    document.querySelector('.btn-2').addEventListener('click', limpiarCampos);

});
