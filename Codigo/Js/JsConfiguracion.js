// Direcciones iniciales del usuario
let direcciones = [
    { calle: "Avenida Siempre Viva", numero: "742", tipo: "Casa", piso: "1", letra: "A" },
    { calle: "Calle Falsa", numero: "123", tipo: "Apartamento", piso: "2", letra: "B" }
];

// Referencias a elementos HTML
const tablaDirecciones = document.getElementById("user-directions");
const formularioDireccion = document.getElementById("address-form");
const controlesEdicion = document.getElementById("edit-controls");
const botonEditar = document.getElementById("edit-button");
const botonBorrar = document.getElementById("delete-button");
const botonNuevaDireccion = document.getElementById("add-new-address");
const botonGuardarDireccion = document.getElementById("save-address");

let direccionSeleccionada = null; // Índice de dirección seleccionada para editar o borrar

// Función para mostrar direcciones en la tabla
function mostrarDirecciones() {
    tablaDirecciones.innerHTML = ""; // Limpiar la tabla de direcciones
    direcciones.forEach((direccion, index) => {
        const direccionDiv = document.createElement("div");
        direccionDiv.classList.add("direccion-item");
        direccionDiv.textContent = `${direccion.calle} ${direccion.numero}, ${direccion.tipo}, Piso: ${direccion.piso}, Letra: ${direccion.letra}`;
        
        // Evento de doble clic para mostrar botones de editar y borrar al lado de la dirección
        direccionDiv.addEventListener("dblclick", () => {
            direccionSeleccionada = index;
            mostrarControlesEdicion(direccionDiv);
        });

        tablaDirecciones.appendChild(direccionDiv);
    });

    // Mostrar botón de nueva dirección debajo de la tabla
    botonNuevaDireccion.style.display = "block";
}

// Función para mostrar botones de edición al lado de la dirección seleccionada
function mostrarControlesEdicion(direccionDiv) {
    controlesEdicion.style.display = "flex";
    controlesEdicion.style.position = "absolute";
    controlesEdicion.style.top = `${direccionDiv.offsetTop}px`;
    controlesEdicion.style.left = `${direccionDiv.offsetLeft + direccionDiv.offsetWidth + 10}px`;
}

// Función para abrir el formulario en modo de edición
function editarDireccion() {
    if (direccionSeleccionada !== null) {
        const direccion = direcciones[direccionSeleccionada];
        document.getElementById("nombre-calle").value = direccion.calle;
        document.getElementById("numero-calle").value = direccion.numero;
        document.getElementById("tipo-casa").value = direccion.tipo;
        document.getElementById("numero-piso").value = direccion.piso;
        document.getElementById("letra-apartamento").value = direccion.letra;
        
        formularioDireccion.style.display = "block";
    }
}

// Función para borrar la dirección seleccionada
function borrarDireccion() {
    if (direccionSeleccionada !== null) {
        direcciones.splice(direccionSeleccionada, 1);
        mostrarDirecciones();
        controlesEdicion.style.display = "none";
    }
}

// Función para agregar una nueva dirección (limpia el formulario)
function nuevaDireccion() {
    direccionSeleccionada = null;
    formularioDireccion.reset();
    formularioDireccion.style.display = "block";
}

// Función para guardar la dirección (nueva o editada)
function guardarDireccion(event) {
    event.preventDefault();

    const nuevaDireccion = {
        calle: document.getElementById("nombre-calle").value,
        numero: document.getElementById("numero-calle").value,
        tipo: document.getElementById("tipo-casa").value,
        piso: document.getElementById("numero-piso").value,
        letra: document.getElementById("letra-apartamento").value
    };

    if (direccionSeleccionada !== null) {
        // Editar dirección existente
        direcciones[direccionSeleccionada] = nuevaDireccion;
    } else {
        // Agregar nueva dirección
        direcciones.push(nuevaDireccion);
    }

    // Actualizar la tabla y ocultar controles y formulario
    mostrarDirecciones();
    controlesEdicion.style.display = "none";
    formularioDireccion.style.display = "none";
}

// Eventos de botones
botonEditar.addEventListener("click", editarDireccion);
botonBorrar.addEventListener("click", borrarDireccion);
botonNuevaDireccion.addEventListener("click", nuevaDireccion);
botonGuardarDireccion.addEventListener("click", guardarDireccion);

// Inicializar tabla de direcciones
mostrarDirecciones();
