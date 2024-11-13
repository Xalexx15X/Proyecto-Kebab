<?php
require_once './cargadores/Autocargador.php';

class Principal
{
    public static function main()
    {
        Autocargador::autocargar();
        require_once './helper/sesion.php';

        // Manejar rutas de la API
        $route = $_GET['route'] ?? null;
        switch ($route) {
            case 'alergenos':
                require './Api/ApiAlergenos.php';
                break;
            case 'ingredientes':
                require './Api/ApiIngredientes.php';
                break;
            default:
                self::mostrarPagina();
                break;
        }
    }

    private static function mostrarPagina()
    {
        $_GET['menu'] = $_GET['menu'] ?? "inicio"; 
        require_once './Vistas/Principal/layout.php';
    }
}

Principal::main();
