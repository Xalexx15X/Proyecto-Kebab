window.addEventListener('load', function () {
    // obtengo el boton de login
    const btnLogin = document.getElementById('btnLogin');

    // agrego el evento al boton de login
    btnLogin.addEventListener('click', async function () {
        // obtengo los valores del formulario
        const usuario = document.getElementById('usuario').value; // obtengo el usuario
        const contra = document.getElementById('contrasena').value; // obtengo la contraseña
        const urlApiUsuario = 'http://localhost/ProyectoKebab/codigo/index.php?route=usuarios'; // url de la api para los usuarios

        // construyo el cuerpo de la solicitud 
        const bodyData = {
            nombre: usuario,
            contrasena: contra
        };

        try { // intento realizar la peticion ajax
            const response = await fetch(urlApiUsuario, { // hago la peticion ajax para obtener los datos
                method: 'POST',  // uso el metodo POST
                headers: { 
                    'Content-Type': 'application/json', // le digo que lo que voy a enviar en el body es json
                },
                body: JSON.stringify(bodyData)  // envio los datos en el cuerpo
            });

            // verifico el estado de la respuesta
            if (response.ok) { // si la respuesta indica que el servidor respondio correctamente
                const data = await response.json(); // proceso la respuesta como json
                if (data) { // si la respuesta es válida
                    
                    // si el usuario es encontrado, almaceno los datos en localStorage
                    localStorage.setItem('usuario', JSON.stringify(data));  // guardo los datos del usuario

                    // redirigo al index
                    window.location.href = "index.php"; 
                } else {
                    // si no existe el usuario, muestro un mensaje de error 
                    alert("Credenciales incorrectas. Por favor, inténtalo de nuevo.");
                } 
            } else { // si no es válido lanzo un error
                // manejo de errores basado en la respuesta del servidor
                const errorData = await response.json(); // proceso la respuesta como json
                alert(`Error: ${errorData.error || 'Algo salió mal.'}`); // muestro un mensaje de error
            }
        } catch (error) { // si no es válido lanzo un error
            // manejo de errores de conexión
            alert('Hubo un problema al conectar con el servidor.');
            console.error('Error:', error);
        }
    });
});
