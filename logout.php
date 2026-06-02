logout.php

<?php
// logout.php
session_start();
// Este archivo se encarga de cerrar la sesión del usuario
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    // Eliminar la cookie de sesión
    $params = session_get_cookie_params();
    // Establecer la cookie con una fecha de expiración en el pasado
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
// Destruir la sesión
session_destroy();
// Redirigir al login
header('Location: login.php');
exit();
