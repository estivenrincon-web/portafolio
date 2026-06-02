<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'conexion.php';

// Si es POST, eliminar y redirigir
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id > 0) {
        $stmt = $conexion->prepare('DELETE FROM usuarios WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
    }
    $conexion->close();
    header('Location: index.php');
    exit();
}

// Si es GET, mostrar página de confirmación
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = (int) $_GET['id'];
$stmt = $conexion->prepare('SELECT id, nombre, email FROM usuarios WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows !== 1) {
    $stmt->close();
    $conexion->close();
    header('Location: index.php');
    exit();
}
$user = $result->fetch_assoc();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar usuario</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa
        }

        .confirm-card {
            max-width: 640px;
            margin: 48px auto
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

        <div class="confirm-card card mx-auto shadow-sm" style="max-width:640px; margin:48px auto; ">
            <div class="card-body bg-warning">
                <h4 class="card-title">Confirmar eliminación</h4>
                <p class="text-muted">¿Deseas eliminar este usuario?</p>
                <ul class="list-group mb-3">
                    <li class="list-group-item"><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?></li>
                    <li class="list-group-item"><strong>Nombre:</strong> <?= htmlspecialchars($user['nombre']) ?></li>
                    <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
                </ul>

                <form action="eliminar.php" method="POST">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>