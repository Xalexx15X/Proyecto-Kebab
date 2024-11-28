// Escuchar el evento de carga de la página
window.addEventListener('load', function () {
    const btnRegistrar = document.querySelector('.registrarse button'); // obtengo el boton de registro
    btnRegistrar.addEventListener('click', registrarUsuario); // escucho el evento de click
});

const apiURLUsuario = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios'; // URL para los usuarios

function registrarUsuario() {
    // obtengo los valores del formulario y a los que no uso le doy valores ya definidos
    const nombre = document.getElementById('nombre-cuenta').value.trim();
    const contrasena = document.getElementById('contrasena').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const correo = document.getElementById('email').value.trim();
    const fotoFile = document.getElementById('fotoKebab').files[0];
    const ubicacion = "Desconocida";
    const carrito = { producto_id: 1, cantidad: 2 }; 
    const monedero = 0; // Monedero inicial

    // valido los campos obligatorios
    if (!nombre || !contrasena || !telefono || !correo || !fotoFile) {
        alert("Todos los campos son obligatorios.");
        return;
    }

    // valido el número de teléfono móvil
    const telefonoValido = /^[67]\d{8}$/.test(telefono);
    if (!telefonoValido) {
        alert("El número de teléfono debe ser español, tener 9 dígitos y comenzar por 6 o 7.");
        return;
    }

    // valido el correo electrónico debe tener @ y terminar en .com
    const correoValido = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[cC][oO][mM]$/.test(correo);
    if (!correoValido) {
        alert("El correo electrónico debe ser válido y terminar en .com.");
        return;
    }

    // leo la foto como base64
    const reader = new FileReader(); // creo un lector de archivos para procesar la imagen
    reader.onloadend = function () { // cuando el lector termine de leer la imagen
        const fotoBase64 = reader.result.split(',')[1]; // extraigo solo el contenido base64

        // creo el objeto con los datos que recojo del formulario
        const usuario = {
            nombre: nombre,
            contrasena: contrasena,
            carrito: carrito,
            monedero: monedero,
            foto: fotoBase64,
            telefono: telefono,
            ubicacion: ubicacion,
            correo: correo,
            tipo: "Cliente" // siempre será cliente por defecto
        };

        
        fetch(apiURLUsuario, {  // hago la peticion ajax para crear el usuario
            method: 'POST', // uso el metodo POST
            headers: { 
                'Content-Type': 'application/json'  // le digo que lo que voy a enviar en el body es json
            },
            body: JSON.stringify(usuario) // envio el usuario creado
        })
        .then(async response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
            if (response.ok) { // si la respuesta indica que el servidor respondio correctamente
                const data = await response.json(); // proceso la respuesta como json

                if (data.success) { // si la respuesta indica éxito
                    alert('Usuario creado con éxito.');

                    // guardo el usuario en localStorage como "logueado"
                    localStorage.setItem('usuario', JSON.stringify(usuario));

                    // limpio el formulario
                    borrarCampos();

                    window.location.href = "index.php"; // redirecciono al index 
                } else { // si no es éxito
                    throw new Error(data.message || "Error al crear el usuario."); // lanzo un error
                }
            } else { // si no es válido lanzo un error
                const errorData = await response.json(); // proceso la respuesta como json
                throw new Error(errorData.error || "Error desconocido."); // lanzo un error
            }
        })
        .catch(error => { // si no es válido lanzo un error
            console.error('Error al crear el usuario:', error.message); // lanzo un error
            alert('Hubo un problema al crear el usuario. Detalles: ' + error.message); // muestro un mensaje de error
        });
    };

    reader.readAsDataURL(fotoFile); // convierto la imagen seleccionada en Base64
}

// funcion para borrar los campos del formulario
function borrarCampos() { 
    document.getElementById('nombre-cuenta').value = ''; // borro el nombre del cuenta
    document.getElementById('contrasena').value = ''; // borro la contraseña
    document.getElementById('telefono').value = ''; // borro el telefono
    document.getElementById('email').value = ''; // borro el correo
    document.getElementById('fotoKebab').value = ''; // borro la foto
}

// funcion para mostrar la vista previa de la imagen seleccionada
function mostrarVistaPrevia(event) {
    const file = event.target.files[0]; // obtengo el archivo seleccionado

    if (file) { // si existe
        const reader = new FileReader(); // creo un lector de archivos para procesar la imagen
        reader.onload = function (e) { // cuando el lector termine de leer la imagen
            const previewContainer = document.querySelector('.preview-container'); // busco el contenedor de la vista previa
            previewContainer.style.backgroundImage = `url(${e.target.result})`; // muestro la imagen como fondo
            previewContainer.style.backgroundSize = 'cover'; // ajusto la imagen para que cubra todo el contenedor

            const span = previewContainer.querySelector('span'); // busco el texto del contenedor
            if (span) {
                span.style.display = 'none'; // oculto el texto que dice Subir o arrastrar imagen aquí
            }
        };
        reader.readAsDataURL(file); // convierto la imagen seleccionada en Base64
    }
}

document.getElementById('fotoKebab').addEventListener('change', mostrarVistaPrevia); // configura el evento al input de subir foto
