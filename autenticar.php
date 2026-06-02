autenticar.php
===============================================
<?php
// autenticar.php
session_start();
// Si ya está logueado, redirigir
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

// Este archivo se encarga de autenticar al usuario
include 'conexion.php';

$email = $_POST['email'] ?? '';
$clave = $_POST['clave'] ?? '';

// Preparar consulta
$stmt = $conexion->prepare('SELECT id, nombre, email, clave FROM usuarios WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($clave, $user['clave'])) {
        // Credenciales válidas
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nombre'];
        header('Location: index.php');
        exit();
    }
}

// Si llegamos aquí, error de login
header('Location: login.php?error=' . urlencode('Email o contraseña incorrectos'));
exit();
