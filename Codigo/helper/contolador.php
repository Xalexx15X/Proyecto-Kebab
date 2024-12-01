<?php
session_start();

function mostrarHeader() {
    // Verificar si el usuario está en sesión
    if (isset($_SESSION['usuario'])) {
        $usuario = $_SESSION['usuario'];
        $tipo = $usuario['tipo'];

        if ($tipo === "Administrador") {
            // Header para Administrador
            echo '
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
                        <li class="nav-item active">
                            <a class="nav-link" href="?menu=inicio">Inicio</a>
                        </li>
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
                            <a class="nav-link" href="?menu=mantenimientoKebab">Kebab</a>
                        </li>
                        <li class="nav-item">º
                            <a class="nav-link" href="?menu=mantenimientoIngrediente">Ingredientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=registro-Pedido">Registro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=Mis-Pedidos">Mis Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=contacto">Contacto</a>
                        </li>
                    </ul>
                    <div class="nombre"><p>KEBAB AL PASO</p></div>
                    <div class="navbar-nav ml-auto d-flex align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <img src="../imagenes/cartera.png" alt="Monedero" style="width: 50px;">
                            <span>' . number_format($usuario['monedero'], 2) . '€</span>
                        </div>
                        <div class="nav-item ml-3">
                            <a href="?menu=carrito">
                                <img src="../imagenes/carrito.png" alt="Carrito" style="width: 50px;">
                            </a>
                        </div>
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
            ';
        } else {
            // Header para Cliente
            echo '
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">
                    <img src="../imagenes/logo Kebab.png" alt="Logo Kebab">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="?menu=inicio">Inicio</a>
                        </li>
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
                            <a class="nav-link" href="?menu=Mis-Pedidos">Mis Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="?menu=contacto">Contacto</a>
                        </li>
                    </ul>
                    <div class="nombre"><p>KEBAB AL PASO</p></div>
                    <div class="navbar-nav ml-auto d-flex align-items-center">
                        <div class="nav-item d-flex align-items-center">
                            <img src="../imagenes/cartera.png" alt="Monedero" style="width: 50px;">
                            <span>' . number_format($usuario['monedero'], 2) . '€</span>
                        </div>
                        <div class="nav-item ml-3">
                            <a href="?menu=carrito">
                                <img src="../imagenes/carrito.png" alt="Carrito" style="width: 50px;">
                            </a>
                        </div>
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
            ';
        }
    } else {
        // Header para Invitados
        echo '
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#">
                <img src="../imagenes/logo Kebab.png" alt="Logo Kebab">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="?menu=inicio">Inicio</a>
                    </li>
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
                        <a class="nav-link" href="?menu=contacto">Contacto</a>
                    </li>
                </ul>
                <div class="nombre"><p>KEBAB AL PASO</p></div>
                <div class="nav-item ml-3">
                    <a href="?menu=login" class="btn btn-light text-dark mr-2">Iniciar Sesión</a>
                    <a href="?menu=registro" class="btn btn-light text-dark">Registrarse</a>
                </div>
            </div>
        </nav>
        ';
    }
}

mostrarHeader();
?>

        
