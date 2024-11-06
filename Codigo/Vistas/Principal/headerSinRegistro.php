<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/CssHeaderSinRegistro.css">
    <title>Header Sin Registro</title>
</head>
<header> 
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
            
            <!-- Iconos de Monedero y Carrito visibles para todos -->
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
                
                <!-- Usuario con Submenú (solo si el usuario ha iniciado sesión) -->
                    <div class="nav-item dropdown ml-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="../imagenes/usuario.png" alt="Usuario" style="width: 50px;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUser">
                            <a class="dropdown-item" href="?menu=configuracion">Configuración Personal</a>
                            <a class="dropdown-item" href="?menu=historial">Historial de Pedidos</a>
                            <a class="dropdown-item" href="?menu=cerrarSesion">Cerrar Sesión</a>
                        </div>
                    </div>
                    <!-- Botones de inicio de sesión y registro cuando no está logueado -->
                    <div class="nav-item ml-3">
                        <a href="?menu=login" class="btn btn-light text-dark mr-2">Iniciar Sesión</a>
                        <a href="?menu=registro" class="btn btn-light text-dark">Registrarse</a>
                    </div>
            </div>
        </div>
    </nav>
</header>
</html>
