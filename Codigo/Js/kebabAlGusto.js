document.addEventListener('DOMContentLoaded', function () {
    // API URLs
    const apiURLIngredientes = 'http://localhost/ProyectoKebab/codigo/index.php?route=ingredientes';

    // Función para crear el div de cada ingrediente
    function crearIngredienteDiv(ingrediente) {
        const ingredienteDiv = document.createElement('div');
        ingredienteDiv.classList.add('ingrediente');
        ingredienteDiv.setAttribute('data-id', ingrediente.id_ingredientes);
        ingredienteDiv.setAttribute('data-alergenos', JSON.stringify(ingrediente.alergenos));
        ingredienteDiv.textContent = `${ingrediente.nombre}`;
        ingredienteDiv.draggable = true;

        // Evento al arrastrar
        ingredienteDiv.addEventListener('dragstart', function (e) {
            e.dataTransfer.setData('text/plain', JSON.stringify(ingrediente));
        });

        return ingredienteDiv;
    }

    // Función para actualizar el precio y los alérgenos
    function actualizarPrecioYAlergenos(ingrediente, operacion) {
        const precioRecomendado = document.getElementById('precioRecomendado');
        const descripcionKebab = document.getElementById('descripcionKebab');

        let precioActual = parseFloat(precioRecomendado.value || 0);
        
        if (operacion === 'sumar') {
            precioRecomendado.value = (precioActual + parseFloat(ingrediente.precio) * 1.15).toFixed(2); // Añadimos el 15%
        } else if (operacion === 'restar') {
            precioRecomendado.value = (precioActual - parseFloat(ingrediente.precio) * 1.15).toFixed(2); // Restamos el 15%
        }

        // Actualizar alérgenos
        let alergenosList = descripcionKebab.value.replace('Alergenos del Kebab: ', '').split(', ');
        if (operacion === 'sumar') {
            alergenosList = alergenosList.concat(ingrediente.alergenos.map(a => a.nombre));
        } else {
            alergenosList = alergenosList.filter(alergeno => !ingrediente.alergenos.map(a => a.nombre).includes(alergeno));
        }
        descripcionKebab.value = `Alergenos del Kebab: ${[...new Set(alergenosList)].join(', ')}`;
    }

    // Obtener ingredientes y mostrar
    fetch(apiURLIngredientes)
        .then(response => response.json())
        .then(data => {
            const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');
            data.forEach(ingrediente => {
                const ingredienteDiv = crearIngredienteDiv(ingrediente);
                ingredientesElegirContainer.appendChild(ingredienteDiv);
            });
        });

    // Manejo del arrastre y caída
    const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
    const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

    // Permitir "drop" en los ingredientes del kebab
    ingredientesKebabContainer.addEventListener('dragover', function (e) {
        e.preventDefault();
    });

    // Agregar ingrediente al kebab
    ingredientesKebabContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));

        // Verificar si ya está en el kebab
        if ([...ingredientesKebabContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            alert('El ingrediente ya está añadido.');
            return;
        }

        const ingredienteDiv = crearIngredienteDiv(ingrediente);
        ingredientesKebabContainer.appendChild(ingredienteDiv);

        // Eliminar de la lista de ingredientes disponibles
        const ingredienteElegir = [...ingredientesElegirContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteElegir) {
            ingredientesElegirContainer.removeChild(ingredienteElegir);
        }

        // Actualizar el precio y los alérgenos
        actualizarPrecioYAlergenos(ingrediente, 'sumar');
    });

    // Permitir "drop" de vuelta a ingredientes disponibles
    ingredientesElegirContainer.addEventListener('dragover', function (e) {
        e.preventDefault();
    });

    // Remover ingrediente del kebab
    ingredientesElegirContainer.addEventListener('drop', function (e) {
        e.preventDefault();
        const ingrediente = JSON.parse(e.dataTransfer.getData('text/plain'));

        // Verificar si ya está en la lista de ingredientes disponibles
        if ([...ingredientesElegirContainer.children].some(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString())) {
            return;
        }

        // Volver a agregar el ingrediente a la lista de ingredientes disponibles
        const ingredienteDiv = crearIngredienteDiv(ingrediente);
        ingredientesElegirContainer.appendChild(ingredienteDiv);

        // Eliminar de los ingredientes del kebab
        const ingredienteKebab = [...ingredientesKebabContainer.children].find(div => div.getAttribute('data-id') === ingrediente.id_ingredientes.toString());
        if (ingredienteKebab) {
            ingredientesKebabContainer.removeChild(ingredienteKebab);
            // Restar el precio y los alérgenos
            actualizarPrecioYAlergenos(ingrediente, 'restar');
        }
    });

    document.querySelector('.btn-1').addEventListener('click', function () {
        const precioRecomendado = parseFloat(document.getElementById('precioRecomendado').value) || 0; // Convertir a número
        const descripcionKebab = document.getElementById('descripcionKebab').value; // Descripción
        const ingredientes = Array.from(document.getElementById('ingredientes-kebab').children).map(ingrediente => ({
            id: parseInt(ingrediente.getAttribute('data-id')), // ID del ingrediente
            nombre: ingrediente.textContent.trim() // Nombre del ingrediente
        }));
    
        // Crear el objeto kebab
        const kebab = {
            nombre: 'Kebab al gusto', // Nombre fijo
            precio: parseFloat(precioRecomendado.toFixed(2)), // Precio numérico redondeado, pero guardado como número
            descripcion: descripcionKebab || 'Sin descripción', // Aseguramos que haya una descripción
            ingredientes: ingredientes.map(ing => ing.nombre) // Lista de nombres de ingredientes
        };
    
        // Guardar en localStorage
        let carrito = JSON.parse(localStorage.getItem('carrito')) || []; // Obtener carrito existente
        carrito.push(kebab); // Añadir el kebab
        localStorage.setItem('carrito', JSON.stringify(carrito)); // Guardar actualizado
    
        // Mostrar mensaje de éxito
        alert('Kebab al gusto añadido al carrito.');
    
        // Console log para depuración
        console.log('Carrito en localStorage:', JSON.parse(localStorage.getItem('carrito')));
    

    // Mostrar mensaje de éxito
    alert('Kebab al gusto añadido al carrito.');

    // Console log para depuración
    console.log('Carrito en localStorage:', JSON.parse(localStorage.getItem('carrito')));
    });

    // Función para limpiar los campos (Borrar)
    function limpiarCampos() {
        const precioRecomendado = document.getElementById('precioRecomendado');
        const descripcionKebab = document.getElementById('descripcionKebab');
        const ingredientesKebabContainer = document.getElementById('ingredientes-kebab');
        const ingredientesElegirContainer = document.getElementById('ingredientes-elegir');

        // Restablecer precio a 0.00
        precioRecomendado.value = '0.00'; 

        // Restablecer descripción
        descripcionKebab.value = 'Alergenos del Kebab:'; 

        // Limpiar ingredientes del kebab y devolver a la lista de ingredientes disponibles
        [...ingredientesKebabContainer.children].forEach(ingrediente => {
            ingredientesKebabContainer.removeChild(ingrediente);
            ingredientesElegirContainer.appendChild(ingrediente);
            // Restar el precio de cada ingrediente removido
            const ingredienteData = JSON.parse(ingrediente.getAttribute('data-id'));
            actualizarPrecioYAlergenos(ingredienteData, 'restar');
        });
    }
    // Evento para borrar
    document.querySelector('.btn-2').addEventListener('click', limpiarCampos);

});
