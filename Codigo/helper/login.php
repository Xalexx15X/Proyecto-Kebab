<?php
require_once 'sesion.php'; // Incluye la clase Sesion

$usuario = $_POST['usuario'] ?? null;
$contrasena = $_POST['contrasena'] ?? null;

if ($usuario === 'admin' && $contrasena === 'admin') { // Validación básica
    // Guardamos los datos en la sesión
    Sesion::escribir('usuario', [
        'nombre' => 'Admin',
        'tipo' => 'Administrador'
    ]);
    header('Location: index.php'); // Redirigimos a la página principal
    exit();
} else {
    echo "Usuario o contraseña incorrectos";
}
?>
