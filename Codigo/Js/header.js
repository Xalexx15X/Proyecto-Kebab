// cuando el dom este completamente cargado ejecuto la funcion principal
window.addEventListener('load', function () {
    const usuario = JSON.parse(localStorage.getItem('usuario')); // recupero el usuario desde el localstorage
    const header = document.getElementById('header'); // busco el header

    if (usuario) {
        if (usuario.tipo === "Administrador") {
            // Si es Administrador, mostrar el header de administrador
            header.innerHTML = `
                <nav class="navbar navbar-expand-lg navbar-light">
                    <!-- Logo -->
                    <a class="navbar-brand" href="#">
                        <img src="../imagenes/logo Kebab.png" alt="Logo Kebab">
                    </a>
                    
                    <!-- Botón para el menú responsive -->
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Contenido del Menú -->
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <ul class="navbar-nav mr-auto">
                            <!-- Inicio -->
                            <li class="nav-item active">
                                <a class="nav-link" href="?menu=inicio">Inicio</a>
                            </li>
                            
                            <!-- Carta con submenú desplegable -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarCarta" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Carta
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarCarta">
                                    <a class="dropdown-item" href="?menu=kebabCasa">Kebab de la Casa</a>
                                    <a class="dropdown-item" href="?menu=kebabGusto">Kebab al Gusto</a>
                                </div>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="?menu=mantenimientoKebab" id="navbarKebab">
                                Kebab
                                </a>
                            </li>

                            <!-- Ingredientes con botón -->
                            <li class="nav-item">
                                <a class="nav-link" href="?menu=mantenimientoIngrediente" id="navbarIngrediente">
                                Ingredientes
                                </a>
                            </li>

                            <!-- Registro -->
                            <li class="nav-item">
                                <a class="nav-link" href="?menu=registro-Pedido">Registro</a>
                            </li>

                            <!-- Registro -->
                            <li class="nav-item">
                                <a class="nav-link" href="?menu=Mis-Pedidos">Mis Pedidos</a>
                            </li>

                            <!-- Contacto -->
                            <li class="nav-item">
                                <a class="nav-link" href="?menu=contacto">Contacto</a>
                            </li>
                        </ul>

                        <div class="nombre"><p>KEBAB AL PASO</p></div>
                        
                        <!-- Iconos de Monedero, Carrito y Usuario -->
                        <div class="navbar-nav ml-auto d-flex align-items-center">
                            <!-- Monedero -->
                            <div class="nav-item d-flex align-items-center">
                                <img src="../imagenes/cartera.png" alt="Monedero" style="width: 50px;">
                                <span class="ml-1">0€</span>
                            </div>
                            
                            <!-- Carrito -->
                            <div class="nav-item ml-3">
                                <a href="?menu=carrito">
                                    <img src="../imagenes/carrito.png" alt="Carrito" style="width: 50px;">
                                </a>
                            </div>
                            
                            <!-- Usuario con Submenú -->
                            <div class="nav-item dropdown ml-3">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="../imagenes/usuario.png" alt="Usuario" style="width: 50px;">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUser">
                                    <a class="dropdown-item" href="?menu=configuracion">Configuración Personal</a>
                                    <a class="dropdown-item" href="?menu=inicio" id="cerrarSesionBtn">Cerrar Sesión</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            `;
            header.className = 'header-admin'; // Cambiar clase del header para admin
        } else {
            // Si es Cliente, mostrar el header de usuario registrado
            header.innerHTML = `
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="../imagenes/logo Kebab.png" alt="Logo Kebab">
                </a>
                
                <!-- Botón para el menú responsive -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Contenido del Menú -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- Inicio -->
                        <li class="nav-item active">
                            <a class="nav-link" href="?menu=inicio">Inicio</a>
                        </li>
                        
                        <!-- Carta con submenú desplegable -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarCarta" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Carta
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarCarta">
                                <a class="dropdown-item" href="?menu=kebabCasa">Kebab de la Casa</a>
                                <a class="dropdown-item" href="?menu=kebabGusto">Kebab al Gusto</a>
                            </div>
                        </li>

                        <!-- Registro -->
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=Mis-Pedidos">Mis Pedidos</a>
                        </li>

                        <!-- Contacto -->
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=contacto">Contacto</a>
                        </li>
                    </ul>

                    <div class="nombre"><p>KEBAB AL PASO</p></div>
                    
                    <!-- Iconos de Monedero, Carrito y Usuario -->
                    <div class="navbar-nav ml-auto d-flex align-items-center">
                        <!-- Monedero -->
                        <div class="nav-item d-flex align-items-center">
                            <img src="../imagenes/cartera.png" alt="Monedero" style="width: 50px;">
                            <span class="ml-1">0€</span>
                        </div>
                        
                        <!-- Carrito -->
                        <div class="nav-item ml-3">
                            <a href="?menu=carrito">
                                <img src="../imagenes/carrito.png" alt="Carrito" style="width: 50px;">
                            </a>
                        </div>
                        
                        <!-- Usuario con Submenú -->
                        <div class="nav-item dropdown ml-3">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="../imagenes/usuario.png" alt="Usuario" style="width: 50px;">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUser">
                                <a class="dropdown-item" href="?menu=configuracion">Configuración Personal</a>
                                <a class="dropdown-item" href="?menu=inicio" id="cerrarSesionBtn">Cerrar Sesión</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            `;
            header.className = 'header-cliente'; // Cambiar clase del header para cliente
        }
    } else {
        // Si no hay usuario, mostrar el header de no registrado
        header.innerHTML = `
            <nav class="navbar navbar-expand-lg navbar-light">
                <!-- Logo -->
                <a class="navbar-brand" href="#">
                    <img src="../imagenes/logo Kebab.png" alt="Logo Kebab">
                </a>
                
                <!-- Botón para el menú responsive -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Contenido del Menú -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <!-- Inicio -->
                        <li class="nav-item active">
                            <a class="nav-link" href="?menu=inicio">Inicio</a>
                        </li>
                        
                        <!-- Carta con submenú desplegable -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarCarta" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Carta
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarCarta">
                                <a class="dropdown-item" href="?menu=kebabCasa">Kebab de la Casa</a>
                                <a class="dropdown-item" href="?menu=kebabGusto">Kebab al Gusto</a>
                            </div>
                        </li>

                        <!-- Contacto -->
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=contacto">Contacto</a>
                        </li>
                    </ul>

                    <div class="nombre"><p>KEBAB AL PASO</p></div>
                        
                        <!-- Carrito -->
                        <div class="nav-item ml-3">
                            <a href="?menu=carrito">
                                <img src="../imagenes/carrito.png" alt="Carrito" style="width: 50px;">
                            </a>
                        </div>
                            <!-- Botones de inicio de sesión y registro cuando no está logueado -->
                            <div class="nav-item ml-3">
                                <a href="?menu=login" class="btn btn-light text-dark mr-2">Iniciar Sesión</a>
                                <a href="?menu=registro" class="btn btn-light text-dark">Registrarse</a>
                            </div>
                    </div>
                </div>
            </nav>
        `;
        header.className = 'header-invitado'; // Cambiar clase del header para invitado
    }
});


document.body.addEventListener('click', function(event) { // cuando el usuario clickea en el boton cerrar sesion
    if (event.target && event.target.id === 'cerrarSesionBtn') { // verifico que el evento sea del tipo click
        // borro el localstorage
        localStorage.removeItem('usuario');
        localStorage.removeItem('carrito');

        // recargo la pagina y redirigo al index.php
        window.location.href = 'index.php';
    }
});

// cuando el usuario clickea en el boton cerrar sesion llamo a la api para destruir la sesion
document.body.addEventListener('click', function(event) {
    if (event.target && event.target.id === 'cerrarSesionBtn') { // verifico que el evento sea del tipo click
        fetch('http://localhost/ProyectoKebab/codigo/index.php?route=usuarios', { // llamo a la api para destruir la sesion
            method: 'POST', // le digo que voy a usar el post
            headers: { 'Content-Type': 'application/json' }, // le digo que lo que voy a enviar en el body es json
            body: JSON.stringify({ action: 'SESSION_DESTROY' }) // envio el action de destruir la sesion
        })
        .then(response => { // ahora segun lo que me responda el servidor proceso la respuesta como json
            if (!response.ok) { // si el servidor respondio con un error
                throw new Error(`HTTP error! Status: ${response.status}`); // lanzo un error
            }
            return response.json(); // proceso la respuesta como json
        })
        .then(data => {
            if (data.success) {
                console.log(data.mensaje); // Mensaje del servidor
                // Borrar el localStorage
                localStorage.removeItem('usuario'); 
                localStorage.removeItem('carrito'); 

                // Redirigir a la página principal
                window.location.href = 'index.php'; // Puede ser la página principal
            } else {
                console.error(data.error);
            }
        })
        .catch(error => {
            console.error('Sesion cerrada:');
            alert("Se a cerrado la sesion");  // Muestra el error
        });
    }
});


// cuando el dom este completamente cargado ejecuto la funcion principal
window.onload = function () {
    // recupero el usuario desde el localstorage
    const usuario = JSON.parse(localStorage.getItem("usuario"));

    // verifico si el usuario existe
    if (usuario) {
        console.log("Usuario cargado:", usuario); 

        // comptuebo si el usuario tiene la propiedad monedero
        if (usuario.monedero !== undefined) {
            // selecciono el span donde se mostrara el saldo del monedero
            const monederoSpan = document.querySelector('.nav-item.d-flex.align-items-center span');

            // verifico si se encontro el span
            if (monederoSpan) {
                console.log("Span encontrado:", monederoSpan);
                // actualizo el contenido del span con el saldo del monedero
                monederoSpan.textContent = `${usuario.monedero.toFixed(2)}€`;
            } else {
                console.error("No se encontró el span del monedero en el header.");
            }
        } else {
            console.error("No se encontró el monedero del usuario en localStorage.");
        }
    } 
};