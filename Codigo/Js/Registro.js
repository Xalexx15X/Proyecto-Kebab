window.addEventListener('load', function () {
    const btnRegistrar = document.querySelector('.registrarse button');
    btnRegistrar.addEventListener('click', registrarUsuario);

});

function registrarUsuario(event) {
    event.preventDefault(); // Evitar que el formulario recargue la página.

    // Obtener los valores del formulario.
    const nombre = document.getElementById('nombre-cuenta').value.trim();
    const contrasena = document.getElementById('contrasena').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
    const correo = document.getElementById('email').value.trim();
    const fotoFile = document.getElementById('fotoKebab').files[0];
    const ubicacion = "Desconocida";
    const carrito = { producto_id: 1, cantidad: 2 };
    const monedero = 0;

    // Validar campos obligatorios.
    if (!nombre || !contrasena || !telefono || !correo || !fotoFile) {
        alert("Todos los campos son obligatorios.");
        return;
    }

    // Leer la foto como base64.
    const reader = new FileReader();
    reader.onloadend = function () {
        const fotoBase64 = reader.result.split(',')[1];

        // Crear objeto del usuario.
        const usuario = {
            nombre: nombre,
            contrasena: contrasena,
            carrito: carrito,
            monedero: monedero,
            foto: fotoBase64,
            telefono: telefono,
            ubicacion: ubicacion,
            correo: correo,
            tipo: "Cliente"
        };

        // Enviar datos para crear usuario.
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
                console.log("Usuario creado exitosamente.");
                
                // Ahora, hacer un GET para obtener el id_usuario
                return fetch(`http://localhost/ProyectoKebab/codigo/index.php?route=usuarios&id=${data.id_usuario}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
            } else {
                throw new Error(data.message || "Error al crear el usuario.");
            }
        })
        .then(response => response.json())
        .then(data => {
            // Verificamos si la respuesta contiene el id del usuario
            if (data && data.id_usuario) {
                const usuarioId = data.id_usuario;
                console.log("ID del usuario recuperado:", usuarioId);
                // Crear la dirección con el ID del usuario
                crearDireccion(usuarioId);
            } else {
                throw new Error("No se pudo obtener el ID del usuario.");
            }
        })
        .catch(error => {
            console.error('Error:', error.message);
            alert('Hubo un problema al crear el usuario. Detalles: ' + error.message);
        });
    };

    reader.readAsDataURL(fotoFile); // Leer la foto como base64.
}

function crearDireccion(usuarioId) {
    // Obtener los valores de los campos de la dirección.
    const direccion = `${document.getElementById('nombre-calle').value} Nº${document.getElementById('numero-calle').value} ${document.getElementById('tipo-casa').value}, Piso ${document.getElementById('numero-piso').value}, Letra ${document.getElementById('letra-apartamento').value}`;
    const estado = "Activa";

    // Crear objeto con los datos de la dirección.
    const direccionData = {
        direccion: direccion,
        estado: estado,
        id_usuario: usuarioId // Asegúrate de pasar correctamente el ID del usuario
    };

    // Mostrar la dirección que vamos a enviar.
    console.log("Datos a enviar al servidor para crear la dirección:", direccionData);

    // Enviar datos a la API de direcciones.
    fetch('http://localhost/ProyectoKebab/codigo/index.php?route=direccion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(direccionData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert('Dirección creada con éxito.');
            borrarCampos(); // Limpiar los campos del formulario
        } else {
            throw new Error(data.message || "Error al crear la dirección.");
        }
    })
    .catch(error => {
        console.error('Error al crear la dirección:', error.message);
        alert('Hubo un problema al crear la dirección. Detalles: ' + error.message);
    });
}

function borrarCampos() {
    document.getElementById('nombre-cuenta').value = '';
    document.getElementById('contrasena').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('email').value = '';
    document.getElementById('fotoKebab').value = '';
    document.getElementById('nombre-calle').value = '';
    document.getElementById('numero-calle').value = '';
    document.getElementById('tipo-casa').value = '';
    document.getElementById('numero-piso').value = '';
    document.getElementById('letra-apartamento').value = '';
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
