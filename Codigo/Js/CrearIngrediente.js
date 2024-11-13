// Función para obtener los alérgenos disponibles
const fetchAlergenosDisponibles = async () => {
    try {
        // Realiza la solicitud a la API de alérgenos (cambia la URL a la correcta)
        const response = await fetch('http://localhost/tu_ruta/api/alergenos');  // Asegúrate de cambiar la URL
        if (!response.ok) {
            throw new Error('Error al obtener los alérgenos: ' + response.statusText);
        }

        // Verifica si la respuesta es JSON
        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON. Tipo de contenido: ' + contentType);
        }

        // Parsear la respuesta a JSON
        const data = await response.json();

        // Llamar a la función para mostrar los alérgenos en la tabla
        mostrarAlergenosEnTabla(data);
    } catch (error) {
        console.error("Error al cargar los alérgenos:", error);
        // Mostrar un mensaje de error al usuario si algo salió mal
        alert("Hubo un error al cargar los alérgenos.");
    }
};

// Función para mostrar los alérgenos en la tabla
const mostrarAlergenosEnTabla = (alergenos) => {
    const alergenosContainer = document.getElementById('ingredientes-elegir');
    alergenosContainer.innerHTML = ""; // Limpiar la tabla antes de cargar nuevos datos

    // Recorrer los alérgenos y agregar cada uno a la tabla
    alergenos.forEach((alergeno) => {
        const row = document.createElement('div');
        row.classList.add('table-row');

        const cell = document.createElement('div');
        cell.classList.add('table-cell');
        cell.textContent = alergeno.nombre; // Asumiendo que 'nombre' es la propiedad a mostrar

        row.appendChild(cell);
        alergenosContainer.appendChild(row);
    });
};

// Función para obtener y mostrar los ingredientes
const fetchIngredientesDisponibles = async () => {
    try {
        // Realiza la solicitud a la API de ingredientes (cambia la URL a la correcta)
        const response = await fetch('http://localhost/tu_ruta/api/ingredientes');  // Asegúrate de cambiar la URL
        if (!response.ok) {
            throw new Error('Error al obtener los ingredientes: ' + response.statusText);
        }

        // Verifica si la respuesta es JSON
        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('La respuesta no es JSON. Tipo de contenido: ' + contentType);
        }

        // Parsear la respuesta a JSON
        const data = await response.json();

        // Llamar a la función para mostrar los ingredientes en la tabla
        mostrarIngredientesEnTabla(data);
    } catch (error) {
        console.error("Error al cargar los ingredientes:", error);
        // Mostrar un mensaje de error al usuario si algo salió mal
        alert("Hubo un error al cargar los ingredientes.");
    }
};

// Función para mostrar los ingredientes en la tabla
const mostrarIngredientesEnTabla = (ingredientes) => {
    const ingredientesContainer = document.getElementById('ingredientes-elegir');
    ingredientesContainer.innerHTML = ""; // Limpiar la tabla antes de cargar nuevos datos

    // Recorrer los ingredientes y agregar cada uno a la tabla
    ingredientes.forEach((ingrediente) => {
        const row = document.createElement('div');
        row.classList.add('table-row');

        const cell = document.createElement('div');
        cell.classList.add('table-cell');
        cell.textContent = ingrediente.nombre; // Asumiendo que 'nombre' es la propiedad a mostrar

        row.appendChild(cell);
        ingredientesContainer.appendChild(row);
    });
};

// Función para crear el ingrediente
const crearIngrediente = async () => {
    const nombre = document.getElementById('nombreIngrediente').value;
    const foto = document.getElementById('fotoIngrediente').files[0];
    const precio = document.getElementById('precioIngrediente').value;
    const descripcion = document.getElementById('descripcionIngrediente').value;
    const alergenos = [];  // Suponemos que los alérgenos se agregan a partir de las filas seleccionadas

    // Recopilamos los alérgenos seleccionados (esto puede ser diferente dependiendo de la lógica)
    document.querySelectorAll('.alergeno-seleccionado').forEach((checkbox) => {
        if (checkbox.checked) {
            alergenos.push(checkbox.value);
        }
    });

    // Crear el objeto para enviar a la API
    const nuevoIngrediente = {
        nombre: nombre,
        foto: foto ? URL.createObjectURL(foto) : null,
        precio: precio,
        descripcion: descripcion,
        alergenos: alergenos
    };

    try {
        const response = await fetch('http://localhost/tu_ruta/api/ingredientes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(nuevoIngrediente)
        });

        if (!response.ok) {
            throw new Error('Error al crear el ingrediente: ' + response.statusText);
        }

        const data = await response.json();
        if (data.success) {
            alert("Ingrediente creado correctamente.");
        } else {
            alert("Error al crear el ingrediente: " + data.error);
        }
    } catch (error) {
        console.error("Error al crear el ingrediente:", error);
        alert("Hubo un error al crear el ingrediente.");
    }
};

// Función para limpiar los campos
const limpiarCampos = () => {
    document.getElementById('nombreIngrediente').value = "";
    document.getElementById('fotoIngrediente').value = "";
    document.getElementById('precioIngrediente').value = "";
    document.getElementById('descripcionIngrediente').value = "";
    document.querySelectorAll('.alergeno-seleccionado').forEach((checkbox) => checkbox.checked = false);
};

// Evento para el botón de crear ingrediente
document.getElementById('crearIngredienteBtn').addEventListener('click', crearIngrediente);

// Evento para el botón de borrar campos
document.getElementById('borrarCamposBtn').addEventListener('click', limpiarCampos);

// Cargar los alérgenos disponibles al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    fetchAlergenosDisponibles();
    fetchIngredientesDisponibles();
});
