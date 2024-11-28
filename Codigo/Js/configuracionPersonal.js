// cuando el dom esté completamente cargado, ejecuta la función principal
document.addEventListener("DOMContentLoaded", function () {
    // recupero los datos del usuario almacenados en el localstorage
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    // si no hay datos de usuario, muestro un mensaje y detengo la ejecución
    if (!usuarioSesion) {
        alert("No se encontró información del usuario en sesión.");
        return; // salgo de la funcion ya que no hay sesion activa 
    }

    //cargo los datos iniciales del usuario y sus direcciones
    cargarDatosUsuario(usuarioSesion);
    cargarDirecciones(usuarioSesion);

    // obtengo referencias a botones y campos del DOM
    const botonCrearDireccion = document.querySelector(".crear-direccion"); // Botón para crear dirección
    const botonGuardarDireccion = document.getElementById("guardar-direccion"); // Botón para guardar dirección
    const botonGuardarUsuario = document.querySelector(".guardar"); // Botón para guardar datos del usuario
    const inputSubirFoto = document.getElementById("foto-perfil"); // Input para subir una foto de perfil

    // asigno los eventos a cada elemento si existe
    if (botonCrearDireccion) botonCrearDireccion.addEventListener("click", mostrarFormularioDireccion);
    if (botonGuardarDireccion) botonGuardarDireccion.addEventListener("click", guardarDireccion);
    if (botonGuardarUsuario) botonGuardarUsuario.addEventListener("click", guardarDatosUsuario);
    if (inputSubirFoto) inputSubirFoto.addEventListener("change", mostrarVistaPrevia);
});

// defino las urls base para las peticiones relacionadas con direcciones y usuarios
const apiURLDireccion = 'http://localhost/ProyectoKebab/codigo/index.php?route=direccion';
const apiURLUsarios = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios';

// funcion para cargar los datos del usuario en el formulario
function cargarDatosUsuario(usuario) {
    // primero relleno los campos del formulario con los datos del usuario
    document.getElementById("nombre-cuenta").value = usuario.nombre;
    document.getElementById("contrasena").value = usuario.contrasena;
    document.getElementById("telefono").value = usuario.telefono;
    document.getElementById("email").value = usuario.correo;

    // ahora configuro la vista previa de la foto de perfil
    const previewContainer = document.querySelector(".contenedor-previa"); // busco el contenedor de la vista previa
    if (previewContainer) {
        if (usuario.foto) { // si hay una foto guardada
            previewContainer.style.backgroundImage = `url(${usuario.foto})`; // muestro la foto como fondo
            previewContainer.style.backgroundSize = "cover"; // ajusto la imagen para que cubra el contenedor
            const span = previewContainer.querySelector("span");
            if (span) span.style.display = "none";  // oculto el texto del contenedor si hay una foto
        }
    }
}

// funcion para mostrar la vista previa de la imagen seleccionada
function mostrarVistaPrevia(event) {
    // obtengo el archivo seleccionado
    const file = event.target.files[0];
    if (!file) return; // si no selecciono nada, no hago nada 

    // ahora creo un lector de archivos para procesar la imagen
    const reader = new FileReader();
    reader.onload = function (e) {
        // mostro la imagen en el contenedor de vista previa
        const previewContainer = document.querySelector(".contenedor-previa");// busco el contenedor de la vista previa
        if (previewContainer) { // si existe
            previewContainer.style.backgroundImage = `url(${e.target.result})`; // muestro la imagen como fondo
            previewContainer.style.backgroundSize = "cover"; // ajusto la imagen para que cubra el contenedor
            const span = previewContainer.querySelector("span");   // busco el texto del contenedor
            if (span) span.style.display = "none";  // oculto el texto del contenedor si hay una foto   

            // actualizo la foto en los datos del usuario en el localstorage
            const usuarioSesion = JSON.parse(localStorage.getItem("usuario")); // recupero los datos del usuario
            usuarioSesion.foto = e.target.result; // guardo la imagen en formato Base64
            localStorage.setItem("usuario", JSON.stringify(usuarioSesion)); // sobrescribo el localstorage con los nuevos datos
        }
    };
    reader.readAsDataURL(file); // convierto la imagen seleccionada en Base64
}

// funcion para guardar los datos actualizados del usuario
function guardarDatosUsuario() {
    // recupero los datos del usuario en el localstorage
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));
    // obtengo los valores de los campos del formulario
    const nombre = document.getElementById("nombre-cuenta").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const correo = document.getElementById("email").value.trim();

    // validar que el numero de telefono sea español
    const telefonoValido = /^[67]\d{8}$/.test(telefono); // valida que comience con 6/7 y tenga 9 digitos
    if (!telefonoValido) {
        alert("El número de teléfono debe ser español, tener 9 dígitos y comenzar por 6 o 7."); 
        return; // Si no es válido, salimos de la función
    }

    // valido que el correo electrónico sea válido y termine en .com
    const correoValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[cC][oO][mM]$/.test(correo); // valido que comience con letras, seguidos de letras, números, puntos, guiones y @
    if (!correoValido) {
        alert("El correo electrónico debe ser válido y terminar en .com.");
        return; // si no es válido, salgo de la funcion
    }

    // creo un objeto con los datos actualizados, como solo voy a actualizar los que hay en el formulario los demas lo dejo igual que en la sesion
    const usuarioActualizado = {
        id: usuarioSesion.id_usuario,
        nombre: nombre,
        contrasena: contrasena,
        carrito: usuarioSesion.carrito,
        monedero: usuarioSesion.monedero,
        foto: usuarioSesion.foto,
        telefono: telefono,
        ubicacion: usuarioSesion.ubicacion,
        correo: correo,
        tipo: usuarioSesion.tipo,
    };

    // realizo la peticion ajax para actualizar los datos
    fetch(`${apiURLUsarios}`, { // usando la url para actualizar los datos
        method: "PUT", // le digo el metodo que quiero usar
        headers: { "Content-Type": "application/json" }, // le digo que lo que voy a enviar en el body es json
        body: JSON.stringify(usuarioActualizado), // convierto el objeto en texto json
    })
        .then(response => response.json()) // ahora segun lo que me responda el servidor proceso la respuesta como json
        .then(data => {
            if (data.success) { // si la respuesta indica que el servidor respondio correctamente
                alert("Datos guardados correctamente."); // muestro un mensaje de confirmacion
                localStorage.setItem("usuario", JSON.stringify(usuarioActualizado)); // actualizo el localstorage con los nuevos datos
            } else {
                console.error("Error al guardar los datos del usuario:", data); 
            }
        })
        .catch(error => {
            console.error("Error al guardar los datos del usuario:", error); 
        });
}

// funcion para cargar las direcciones del usuario desde el servidor
function cargarDirecciones() {
    const usuario = JSON.parse(localStorage.getItem("usuario")); // recupero el usuario del localstorage

    if (!usuario) { // si no hay datos del usuario en el localstorage, muestro un mensaje y detengo la ejecución
        console.error("No se encontró información del usuario en localStorage."); 
        return; 
    }

    if (!usuario.id_usuario) { // si el usuario no tiene un id válido muestro un mensaje y detengo la ejecución
        console.error("El usuario no tiene un ID válido:", usuario);
        return; 
    }

    // ahora hacemos una peticion ajax para obtener las direcciones del usuario
    fetch(`${apiURLDireccion}&id_usuario=${usuario.id_usuario}`, { 
        method: 'POST', // usando el metodo POST
        headers: { 'Content-Type': 'application/json' }, // lee digo que lo que voy a enviar en el body es json
        body: JSON.stringify({}) // envio un body vacio
    })
        .then(response => response.json()) // ahora segun lo que me responda el servidor proceso la respuesta como json
        .then(json => { // si la respuesta es válida
            const direccionesContainer = document.getElementById('direcciones-usuario'); // busco el contenedor de direcciones
            direccionesContainer.innerHTML = ''; // limpio el contenedor antes de meter algo nuevo

            if (json && Array.isArray(json) && json.length > 0) { // si la respuesta es válida y tiene al menos una dirección y lo meto en un array 
                json.forEach(direccion => { // recorro el array de direcciones
                    // creo un elemento para cada dirección
                    const direccionElem = document.createElement('div'); // creo un div para cada dirección
                    direccionElem.classList.add('direccion'); // le asigno la clase 'direccion'
                    direccionElem.innerHTML = `
                        <div>${direccion.direccion}</div>
                        <div>${direccion.estado}</div>
                        <div>
                            <button class="btn eliminar" onclick="eliminarDireccion(${direccion.id_direccion})">Eliminar</button>
                        </div>
                    `; // muestro el nombre y el estado de la direccion y le meto el boton para eliminarla
                    direccionesContainer.appendChild(direccionElem); // lo agrego al contenedor
                });
            } else {
                direccionesContainer.innerHTML = "<p>No tienes direcciones guardadas.</p>"; // muestro mensaje si no hay direcciones
            }
        })
        .catch(error => {
            console.error('Error al cargar las direcciones:', error); // si no es válido muestro un mensaje de error
        });
}

// llamo a la funcion para cargar las direcciones cuando se cargue la pagina
function eliminarDireccion(id) {
    // confirmo con el usuario antes de proceder a eliminar la direccion
    if (!confirm("¿Estás seguro de que deseas eliminar esta dirección?")) {
        return;     
    }

    // ahora hago la peticion ajax para eliminar la direccion
    fetch(`${apiURLDireccion}`, {
        method: 'DELETE', // usando el metodo DELETE
        headers: { 'Content-Type': 'application/json' }, // le digo que lo que voy a enviar en el body es json
        body: JSON.stringify({ id_direccion: id }) // envio el id de la direccion a eliminar
    })
        .then(response => response.json()) // ahora segun lo que me responda el servidor proceso la respuesta como json
        .then(data => { 
            if (data.success) { // si la respuesta indica que el servidor respondio correctamente
                alert("Dirección eliminada correctamente.");  // muestro un mensaje de confirmacion
                cargarDirecciones(); // recupero las direcciones para reflejar el cambio 
            } else {
                alert("Error al eliminar la dirección: " + data.error); //muestro el error si algo falla
            }
        })
        .catch(error => {
            console.error("Error al eliminar la dirección:", error); 
        });
}

// funcion para mostrar el formulario de creacion de una nueva direccion
function mostrarFormularioDireccion() {
    // mostro el formulario de direccion al usuario
    const formularioDireccion = document.getElementById("formulario-direccion"); // busco el formulario de direccion
    if (formularioDireccion) formularioDireccion.style.display = "block"; // Cambiamos su visibilidad a "block"
}

// funcion para guardar una nueva direccion
function guardarDireccion(event) {
    event.preventDefault(); // prevengo el comportamiento por defecto del formulario

    // recupero los datos del usuario en sesion desde el localstorage
    const usuarioSesion = JSON.parse(localStorage.getItem("usuario"));

    // obtengo los valores de los campos del formulario
    const nombreCalle = document.getElementById("nombre-calle").value.trim(); // nombre de la calle
    const numeroCalle = document.getElementById("numero-calle").value.trim(); // número de la calle
    const tipoCasa = document.getElementById("tipo-casa").value.trim(); // tipo de vivienda
    const numeroPiso = document.getElementById("numero-piso").value.trim(); // piso
    const letraApartamento = document.getElementById("letra-apartamento").value.trim(); // letra del apartamento

    // verifico que todos los campos esten completos
    if (!nombreCalle || !numeroCalle || !tipoCasa || !numeroPiso || !letraApartamento) {
        alert("Por favor, complete todos los campos."); 
        return; 
    }

    // construyo la direccion como un string concatenado
    const direccion = `${nombreCalle} ${numeroCalle}, ${tipoCasa}, Piso: ${numeroPiso}, Letra: ${letraApartamento}`;
    const estado = "Activa";  // estado predeterminado para las nuevas direcciones

    // ahora hago la peticion ajax para guardar la direccion
    fetch(`${apiURLDireccion}`, {
        method: "POST", // usando el metodo POST
        headers: { "Content-Type": "application/json" }, // le digo que lo que voy a enviar en el body es json
        body: JSON.stringify({
            direccion, // paso la direccion completa
            estado, // estado de la direccion
            id_usuario: usuarioSesion.id_usuario // id del usuario que crea la direccion
        }),
    })
        .then(response => response.json()) // ahora segun lo que me responda el servidor proceso la respuesta como json
        .then(data => {
            console.log("Respuesta del servidor:", data); // muestro la respuesta del servidor en consola
            if (data.message) { 
                alert("Dirección creada exitosamente."); // muestro un mensaje de confirmacion
                // oculto el formulario despues de crear la direccion
                const formularioDireccion = document.getElementById("formulario-direccion"); // busco el formulario de direccion
                if (formularioDireccion) formularioDireccion.style.display = "none"; // oculto el formulario
                cargarDirecciones(usuarioSesion);
            } else {
                console.error("Error al guardar la dirección:", data); 
            }
        })
        .catch(error => {
            console.error("Error al guardar dirección:", error); 
        });
}
