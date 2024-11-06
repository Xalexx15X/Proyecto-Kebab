<?php
class Principal
{
    public static function main()
    {
        require_once './cargadores/Autocargador.php';
        require_once './helper/sesion.php';
        $_GET['menu'] = $_GET['menu'] ?? "inicio"; 
        require_once './Vistas/Principal/layout.php';
    }
}
Principal::main();
