<?php
require_once './cargadores/Autocargador.php';

class Principal
{
    public static function main()
    {
        Autocargador::autocargar();

        $route = $_GET['route'] ?? null;
        switch ($route) {
            case 'alergenos':
                require './Api/ApiAlergenos.php';
                break;
            case 'ingredientes':
                require './Api/ApiIngredientes.php';
                break;
            case 'kebabs':
                require './Api/ApiKebab.php';
                break;
            case 'usuarios':
                require './Api/ApiUsuario.php';
                break;
            case 'direccion':
                require './Api/ApiDireccion.php';
                break;
            case 'lineaPedido':
                require './Api/ApiLinea_Pedido.php';
                break;
            case 'pedido':
                require './Api/ApiPedido.php';
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
