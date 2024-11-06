<?php
//METODO SI NO USAMOS HELPER
/*class Conexion {
    private static $con;

    public static function getConection():PDO
    {
        if(self::$con == null)
        {
            //Esto hay que consegirlo 
            self::$con = new PDO("mysql:host=localhost;dbname=prueba",'root','root');
        }
        return self::$con;
    }
}*/

//METODO SI USAMOS HELPER
class Conexion {
    private static $con;

    public static function getConection(): PDO {
        if (self::$con == null) {
            self::$con = new PDO("mysql:host=localhost;dbname=tiendakebab", 'root', 'root');
        }
        return self::$con;
    }
}


