// Escuchar el evento de carga de la página
window.addEventListener('load', function () {
    const btnRegistrar = document.querySelector('.registrarse button');
    btnRegistrar.addEventListener('click', registrarUsuario);
});

function registrarUsuario(event) {
    event.preventDefault(); // Evitar recargar la página.

    // Obtener valores del formulario
    const nombre = document.getElementById('nombre-cuenta').value.trim();
    const contrasena = document.getElementById('contrasena').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const correo = document.getElementById('email').value.trim();
    const fotoFile = document.getElementById('fotoKebab').files[0];
    const ubicacion = "Desconocida";
    const carrito = { producto_id: 1, cantidad: 2 }; // Ejemplo de carrito
    const monedero = 0; // Monedero inicial

    // Validar campos obligatorios
    if (!nombre || !contrasena || !telefono || !correo || !fotoFile) {
        alert("Todos los campos son obligatorios.");
        return;
    }

    // Leer la foto como base64
    const reader = new FileReader();
    reader.onloadend = function () {
        const fotoBase64 = reader.result.split(',')[1];

        // Crear objeto de usuario
        const usuario = {
            nombre: nombre,
            contrasena: contrasena,
            carrito: carrito,
            monedero: monedero,
            foto: fotoBase64,
            telefono: telefono,
            ubicacion: ubicacion,
            correo: correo,
            tipo: "Cliente" // Siempre será cliente por defecto
        };

        // Enviar datos para crear el usuario
        fetch('http://localhost/ProyectoKebab/codigo/index.php?route=usuarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(usuario)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario creado con éxito.');

                // Guardar el usuario en localStorage como "logueado"
                localStorage.setItem('usuario', JSON.stringify(usuario)); 

                // Limpiar el formulario
                borrarCampos(); 

                // Redirigir al index (página principal)
                window.location.href = "index.php"; // Cambia la URL según sea necesario
            } else {
                throw new Error(data.message || "Error al crear el usuario.");
            }
        })
        .catch(error => {
            console.error('Error al crear el usuario:', error.message);
            alert('Hubo un problema al crear el usuario. Detalles: ' + error.message);
        });
    };

    reader.readAsDataURL(fotoFile); // Leer la foto como base64
}

function borrarCampos() {
    document.getElementById('nombre-cuenta').value = '';
    document.getElementById('contrasena').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('email').value = '';
    document.getElementById('fotoKebab').value = '';
}

function mostrarVistaPrevia(event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewContainer = document.querySelector('.preview-container');
            previewContainer.style.backgroundImage = `url(${e.target.result})`;
            previewContainer.style.backgroundSize = 'cover';
            previewContainer.style.backgroundPosition = 'center';

            const span = previewContainer.querySelector('span');
            if (span) {
                span.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById('fotoKebab').addEventListener('change', mostrarVistaPrevia);
