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

const apiURLDireccion = 'http://localhost/ProyectoKebab/codigo/index.php?route=direccion'; // URL para usuario
const apiURLUsarios = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios'; // URL para direccion

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
// Guardar los datos del usuario (actualización de los campos)
function guardarDatosUsuario() {
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    const nombre = document.getElementById("nombre-cuenta").value;
    const contrasena = document.getElementById("contrasena").value;
    const telefono = document.getElementById("telefono").value;
    const correo = document.getElementById("email").value;

    // Crear el objeto con los datos actualizados
    const usuarioActualizado = {
    id: usuarioSesion.id_usuario,  // Cambiar si es necesario
    nombre: nombre,             
    contrasena: contrasena,         
    carrito: usuarioSesion.carrito,      
    monedero: usuarioSesion.monedero,    
    foto: usuarioSesion.foto,            
    telefono: telefono,           
    ubicacion: usuarioSesion.ubicacion,  
    correo: correo,             
    tipo: usuarioSesion.tipo   
    };

    // Realizar la petición PUT
    fetch(`${apiURLUsarios}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(usuarioActualizado),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Datos guardados correctamente.");

            // Actualizar el localStorage con los nuevos datos del usuario
            localStorage.setItem("usuario", JSON.stringify(usuarioActualizado));
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
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    if (!usuario) {
        console.error("No se encontró información del usuario en localStorage.");
        return;
    }

    if (!usuario.id) { // Verificar que el usuario tiene un ID válido
        console.error("El usuario no tiene un ID válido:", usuario);
        return;
    }

    // Realizar la solicitud POST con el id_usuario en la URL y un body vacío
    fetch(`${apiURLDireccion}&id_usuario=${usuario.id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la respuesta: ${response.status}`);
        }
        return response.json();
    })
    .then(json => {
        const direccionesContainer = document.getElementById('direcciones-usuario');
        direccionesContainer.innerHTML = ''; // Limpiar el contenedor antes de mostrar nuevas direcciones

        if (json && Array.isArray(json) && json.length > 0) {
            json.forEach(direccion => {
                const direccionElem = document.createElement('div');
                direccionElem.classList.add('direccion');
                
                direccionElem.innerHTML = `
                    <div>${direccion.direccion}</div>
                    <div>${direccion.estado}</div>
                    <div>
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
document.addEventListener("DOMContentLoaded", function () {
    cargarDirecciones();
});


// Llamar a la función para cargar las direcciones cuando se cargue la página
window.onload = function() {
    cargarDirecciones();
};


// Función para eliminar una dirección (DELETE)
function eliminarDireccion(id) {
    if (!confirm("¿Estás seguro de que deseas eliminar esta dirección?")) {
        return;
    }

    fetch(`${apiURLDireccion}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_direccion: id })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Dirección eliminada correctamente.");
                cargarDirecciones(); // Recargar las direcciones
            } else {
                alert("Error al eliminar la dirección: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error al eliminar la dirección:", error);
        });
}


// Mostrar el formulario para crear una dirección
function mostrarFormularioDireccion() {
    const formularioDireccion = document.getElementById("formulario-direccion");
    if (formularioDireccion) formularioDireccion.style.display = "block";
}

function guardarDireccion(event) {
    event.preventDefault();
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

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
    fetch(`${apiURLDireccion}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            direccion, 
            estado, 
            id_usuario: usuarioSesion.id  // Usamos el id del usuario desde localStorage
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


