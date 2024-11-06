<?php
if (isset($_GET['menu'])) {
    if ($_GET['menu'] == "inicio") {
        require_once './Vistas/Mantenimiento/inicio.php';
    }
    if ($_GET['menu'] == "login") {
        require_once './Vistas/Login/autentifica.php';
    }
    if ($_GET['menu'] == "registro") {
        require_once './Vistas/Login/registrarse.php';
    }
    if ($_GET['menu'] == "crearIngredientes") {
        require_once './Vistas/Mantenimiento/crearIngrediente.php';
    }
    if ($_GET['menu'] == "modificarIngredientes") {
        require_once './Vistas/Mantenimiento/modificarIngrediente.php';
    }
    if ($_GET['menu'] == "mantenimientoKebab") {
        require_once './Vistas/Mantenimiento/mantenimientoKebab.php';
    }
    if ($_GET['menu'] == "kebabCasa") {
        require_once './Vistas/Mantenimiento/kebabDeLaCasa.php';
    }
    if ($_GET['menu'] == "crearKebab") {
        require_once './Vistas/Mantenimiento/crearKebab.php';
    }
    if ($_GET['menu'] == "modificarKebab") {
        require_once './Vistas/Mantenimiento/modificarKebab.php';
    }
    if ($_GET['menu'] == "mantenimientoIngrediente") {
        require_once './Vistas/Mantenimiento/mantenimientoIngredientes.php';
    }
    if ($_GET['menu'] == "crearIngrediente") {
        require_once './Vistas/Mantenimiento/crearIngrediente.php';
    }
    if ($_GET['menu'] == "modificarIngrediente") {
        require_once './Vistas/Mantenimiento/modificarIngrediente.php';
    }


    if ($_GET['menu'] == "contacto") {
        require_once './Vistas/Mantenimiento/contacto.php';
    }
    if ($_GET['menu'] == "carrito") {
        require_once './Vistas/Mantenimiento/carrito.php';
    }
    
}
?>
