Crear.php

<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include 'conexion.php'; //se establece la conexion a la base de datos

    // Se obtienen los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];

    // Hashear la contraseña antes de guardar
    $hash = password_hash($clave, PASSWORD_DEFAULT);

    // Usar sentencia preparada para evitar inyección SQL
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, email, clave) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $nombre, $email, $hash);
    $resultado = $stmt->execute();

    if ($resultado == TRUE) {
        // Redirige a la página principal después de agregar el usuario
        header('Location: index.php');
        exit();
    } else {
        echo '<div class="alert alert-danger mt-3">Error en el elemento: ' . $conexion->error . '</div>';
    }

    $conexion->close();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear usuario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .form-card {
            max-width: 600px;
            margin: 48px auto;
        }
    </style>
</head>

<body>

    <div class="container mt-3">
        <nav class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded">
            <a href="index.php" class="h4 text-decoration-none">CRUD Básico</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <div>
                    <span class="badge bg-success me-2"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="logout.php" class="btn btn-sm btn-outline-secondary">Cerrar sesión</a>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn btn-sm btn-primary">Iniciar sesión</a>
            <?php endif; ?>
        </nav>

        <div class="card mx-auto form-card shadow-sm">
            <div class="card-body">
                <h3 class="card-title mb-3">Crear cuenta</h3>
                <p class="text-muted small">Complete los datos para crear una nueva cuenta.</p>

                <?php if (!empty($conexion) && isset($resultado) && $resultado !== TRUE): ?>
                    <div class="alert alert-danger">Error: <?= htmlspecialchars($conexion->error) ?></div>
                <?php endif; ?>

                <form action="crear.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="clave" name="clave" required minlength="6">
                        <div class="form-text">Mínimo 6 caracteres.</div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Crear cuenta</button>
                        <a href="index.php" class="btn btn-outline-secondary">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>