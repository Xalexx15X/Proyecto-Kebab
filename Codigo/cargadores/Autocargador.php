<?php
Class Autocargador
{

public static function autocargar()
{
    spl_autoload_register('self::autocarga');
}
public static function autocarga($name)
{
  //require_once './ruta
  require_once './repositorios/Conexion.php';
  //require_once './Vistas/inicio.php';

  //clases
  require_once './Clases/Alergenos.php';
  require_once './Clases/Ingredientes.php';
  require_once './Clases/Kebab.php';
  require_once './Clases/Linea_Pedido.php';
  require_once './Clases/Pedido.php';
  require_once './Clases/Usuario.php';

  //repositorios
  require_once './repositorios/RepoAlergenos.php';
  require_once './repositorios/RepoIngredientes.php';
  require_once './repositorios/RepoKebab.php';
  require_once './repositorios/RepoLinea_Pedido.php';
  require_once './repositorios/RepoPedido.php';
  require_once './repositorios/RepoUsuario.php';

}
}
Autocargador::autocargar();

