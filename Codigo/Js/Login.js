window.addEventListener('load', function () {
    const btnLogin = document.getElementById('btnLogin');

    btnLogin.addEventListener('click', async function () {
        // Obtener los valores del formulario
        const usuario = document.getElementById('usuario').value;
        const contra = document.getElementById('contrasena').value;

        // Construir el cuerpo de la solicitud (en lugar de pasar como parámetros de la URL)
        const bodyData = {
            nombre: usuario,
            contrasena: contra
        };

        try {
            const response = await fetch('http://localhost/ProyectoKebab/codigo/index.php?route=usuarios', {
                method: 'POST',  // Cambiado de GET a POST
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(bodyData)  // Enviar los datos en el cuerpo
            });

            // Verificar el estado de la respuesta
            if (response.ok) {
                const data = await response.json();
                if (data) {
                    // Si el usuario es encontrado, almacenar los datos en localStorage
                    localStorage.setItem('usuario', JSON.stringify(data)); // Guardar los datos del usuario

                    // Redirigir al index
                    window.location.href = "index.php";  // Redirección al índice
                } else {
                    // Si no existe el usuario, mostrar error
                    alert("Credenciales incorrectas. Por favor, inténtalo de nuevo.");
                }
            } else {
                // Manejo de errores basado en la respuesta del servidor
                const errorData = await response.json();
                alert(`Error: ${errorData.error || 'Algo salió mal.'}`);
            }
        } catch (error) {
            // Manejo de errores de conexión
            alert('Hubo un problema al conectar con el servidor.');
            console.error('Error:', error);
        }
    });
});

