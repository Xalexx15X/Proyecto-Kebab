document.addEventListener("DOMContentLoaded", function () {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    if (!usuarioSesion) {
        alert("No se encontró información del usuario en sesión.");
        return;
    }

    // Cargar datos iniciales del usuario y direcciones
    cargarDatosUsuario(usuarioSesion);
    cargarDirecciones(usuarioSesion);

    // Asignar eventos a los botones y campos
    const botonCrearDireccion = document.querySelector(".crear-direccion");
    const botonGuardarDireccion = document.getElementById("guardar-direccion");
    const botonGuardarUsuario = document.querySelector(".guardar");
    const inputSubirFoto = document.getElementById("foto-perfil");

    if (botonCrearDireccion) botonCrearDireccion.addEventListener("click", mostrarFormularioDireccion);
    if (botonGuardarDireccion) botonGuardarDireccion.addEventListener("click", guardarDireccion);
    if (botonGuardarUsuario) botonGuardarUsuario.addEventListener("click", guardarDatosUsuario);
    if (inputSubirFoto) inputSubirFoto.addEventListener("change", mostrarVistaPrevia);
});

// Cargar los datos del usuario en el formulario
function cargarDatosUsuario(usuario) {
    document.getElementById("nombre-cuenta").value = usuario.nombre;
    document.getElementById("contrasena").value = usuario.contrasena;
    document.getElementById("telefono").value = usuario.telefono;
    document.getElementById("email").value = usuario.correo;

    // Mostrar imagen de perfil
    const previewContainer = document.querySelector(".contenedor-previa");
    if (previewContainer) {
        if (usuario.foto) {
            previewContainer.style.backgroundImage = `url(${usuario.foto})`;
            previewContainer.style.backgroundSize = "cover";
            const span = previewContainer.querySelector("span");
            if (span) span.style.display = "none";
        }
    }
}

// Mostrar vista previa de la imagen seleccionada
function mostrarVistaPrevia(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const previewContainer = document.querySelector(".contenedor-previa");
        if (previewContainer) {
            previewContainer.style.backgroundImage = `url(${e.target.result})`;
            previewContainer.style.backgroundSize = "cover";
            const span = previewContainer.querySelector("span");
            if (span) span.style.display = "none";

            // Guardar la imagen en localStorage
            const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));
            usuarioSesion.foto = e.target.result; // Base64
            localStorage.setItem("usuario", JSON.stringify(usuarioSesion));
        }
    };
    reader.readAsDataURL(file);
}

// Guardar los datos del usuario (actualización de los campos)
function guardarDatosUsuario() {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    const nombre = document.getElementById("nombre-cuenta").value;
    const contrasena = document.getElementById("contrasena").value;
    const telefono = document.getElementById("telefono").value;
    const correo = document.getElementById("email").value;

    // Se realiza el PUT solo con los datos modificados y manteniendo el resto igual
    fetch("http://localhost/ProyectoKebab/codigo/index.php?route=usuarios", {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            id: usuarioSesion.id_id_usuario,
            nombre: nombre,             // Solo el nombre actualizado
            contrasena: contrasena,         // Solo la contraseña actualizada
            carrito: usuarioSesion.carrito,      // Se mantiene igual
            monedero: usuarioSesion.monedero,    // Se mantiene igual
            foto: usuarioSesion.foto,            // Se mantiene igual
            telefono: telefono,           // Solo el teléfono actualizado
            ubicacion: usuarioSesion.ubicacion,  // Se mantiene igual
            correo: correo,             // Solo el correo actualizado
            tipo: usuarioSesion.tipo            // Se mantiene igual
        }),
    })
    .then(response => response.text())
    .then(data => {
        if (data.success) {
            alert("Datos guardados correctamente.");
            // Puedes realizar alguna otra acción aquí si es necesario
        } else {
            console.error("Error al guardar los datos del usuario:", data);
        }
    })
    .catch(error => {
        console.error("Error al guardar los datos del usuario:", error);
    });
}

// Cargar direcciones desde el backend
// Función para cargar las direcciones del usuario
function cargarDirecciones() {
    // Recuperar el ID del usuario desde el localStorage
    const usuario = JSON.parse(localStorage.getItem("usuario"));
    if (!usuario || !usuario.id_usuario) {
        console.error("No se encontró el ID de usuario.");
        return;
    }

    // Construir la URL con el id_usuario como parámetro de consulta
    const url = `http://localhost/ProyectoKebab/codigo/index.php?route=direccion&id_usuario=${usuario.id_usuario}`;

    // Realizar la solicitud GET
    fetch(url, {
        method: 'GET',  // El método es GET, así que no hay body
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al cargar las direcciones');
        }
        return response.json();
    })
    .then(json => {
        const direccionesContainer = document.getElementById('direcciones-usuario');
        direccionesContainer.innerHTML = '';  // Limpiar el contenedor antes de mostrar nuevas direcciones

        // Verificar si la respuesta contiene direcciones
        if (json && Array.isArray(json) && json.length > 0) {
            json.forEach(direccion => {
                var direccionElem = document.createElement('div');
                direccionElem.classList.add('direccion');
                
                // Mostrar la dirección
                direccionElem.innerHTML = `
                    <div>${direccion.direccion}</div>
                    <div>${direccion.estado}</div>
                    <div>
                        <button class="btn editar" onclick="editarDireccion(${direccion.id_direccion})">Editar</button>
                        <button class="btn eliminar" onclick="eliminarDireccion(${direccion.id_direccion})">Eliminar</button>
                    </div>
                `;
                direccionesContainer.appendChild(direccionElem);
            });
        } else {
            direccionesContainer.innerHTML = "<p>No tienes direcciones guardadas.</p>";
        }
    })
    .catch(error => {
        console.error('Error al cargar las direcciones:', error);
    });
}


// Llamar a la función para cargar las direcciones cuando se cargue la página
window.onload = function() {
    cargarDirecciones();
};

// Función para editar una dirección (todavía no implementada)
function editarDireccion(id) {
    console.log("Editar dirección con ID:", id);
    // Aquí puedes agregar la lógica para editar la dirección
}

// Función para eliminar una dirección (todavía no implementada)
function eliminarDireccion(id) {
    console.log("Eliminar dirección con ID:", id);
    // Aquí puedes agregar la lógica para eliminar la dirección
}

// Mostrar el formulario para crear una dirección
function mostrarFormularioDireccion() {
    const formularioDireccion = document.getElementById("formulario-direccion");
    if (formularioDireccion) formularioDireccion.style.display = "block";
}

function guardarDireccion(event) {
    event.preventDefault();
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    // Verifica qué id está tomando el usuario
    console.log("ID del usuario en localStorage:", usuarioSesion.id);

    // Obtener los valores del formulario
    const nombreCalle = document.getElementById("nombre-calle").value.trim();
    const numeroCalle = document.getElementById("numero-calle").value.trim();
    const tipoCasa = document.getElementById("tipo-casa").value.trim();
    const numeroPiso = document.getElementById("numero-piso").value.trim();
    const letraApartamento = document.getElementById("letra-apartamento").value.trim();

    // Verifica si todos los campos están llenos antes de enviar
    if (!nombreCalle || !numeroCalle || !tipoCasa || !numeroPiso || !letraApartamento) {
        alert("Por favor, complete todos los campos.");
        return;
    }

    // Concatenar la dirección
    const direccion = `${nombreCalle} ${numeroCalle}, ${tipoCasa}, Piso: ${numeroPiso}, Letra: ${letraApartamento}`;
    const estado = "Activa";  // Esto se puede cambiar si el estado es otro

    // Hacemos la petición POST pasando el id de usuario desde localStorage
    fetch("http://localhost/ProyectoKebab/codigo/index.php?route=direccion", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            direccion, 
            estado, 
            id_usuario: usuarioSesion.id_usuario  // Usamos el id del usuario desde localStorage
        }),
    })
    .then(response => response.json())
    .then(data => {
        console.log("Respuesta del servidor:", data);  // Ver respuesta completa del servidor
        if (data.message) {
            alert("Dirección creada exitosamente.");
            const formularioDireccion = document.getElementById("formulario-direccion");
            if (formularioDireccion) formularioDireccion.style.display = "none";
            cargarDirecciones(usuarioSesion); // Actualizar direcciones
        } else {
            console.error("Error al guardar la dirección:", data);
        }
    })
    .catch(error => {
        console.error("Error al guardar dirección:", error);
    });
}


