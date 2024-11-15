<?php
// Cerrar sesión en el servidor
require_once './cargadores/Sesion.php';

// Cerrar la sesión
Sesion::cerrar();

// Enviar un encabezado para indicar que la sesión se cerró correctamente
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit();
?>
