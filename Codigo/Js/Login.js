window.addEventListener('load', function() {
    const btnLogin = document.getElementById('btnLogin');

    btnLogin.addEventListener('click', async function() {
        // Obtener los valores del formulario
        const usuario = document.getElementById('usuario').value;
        const contra = document.getElementById('contrasena').value;

        // Construir la URL con los parámetros de consulta
        const url = `http://localhost/ProyectoKebab/codigo/index.php?route=usuarios&nombre=${encodeURIComponent(usuario)}&contrasena=${encodeURIComponent(contra)}`;

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
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
            }
        } catch (error) {
            alert('Hubo un problema al conectar con el servidor.');
        }
    });
});
