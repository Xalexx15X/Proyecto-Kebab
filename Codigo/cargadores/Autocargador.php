<?php
class Autocargador
{
    public static function autocargar()
    {
        spl_autoload_register([self::class, 'autocarga']);
    }

    public static function autocarga($nombreClase)
    {
        // Definir las rutas donde están las clases y repositorios
        $Carpetas = [
            './repositorios/',
            './Clases/',
            './Vistas/',
            './Api/',
            './helper/',
        ];

        foreach ($Carpetas as $Carpetas) {
            $archivo = $Carpetas . $nombreClase . '.php';
            if (file_exists($archivo)) {
                require_once $archivo;
                return;
            }
        }

        // Si la clase no se encontró, puedes lanzar un error (opcional)
        throw new Exception("No se pudo cargar la clase: $nombreClase");
    }
}

Autocargador::autocargar();
