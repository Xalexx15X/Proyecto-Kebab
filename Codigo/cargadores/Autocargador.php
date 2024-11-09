<?php
class Autocargador
{
    public static function autocargar()
    {
        spl_autoload_register([self::class, 'autocarga']);
    }

    public static function autocarga($className)
    {
        // Definir las rutas donde están las clases y repositorios
        $paths = [
            './repositorios/',
            './Clases/',
            './Vistas/',
            './Api/',
            './helper/',
        ];

        foreach ($paths as $path) {
            $file = $path . $className . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }

        // Si la clase no se encontró, puedes lanzar un error (opcional)
        throw new Exception("No se pudo cargar la clase: $className");
    }
}

Autocargador::autocargar();
