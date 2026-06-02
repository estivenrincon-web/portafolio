<?php
session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'conexion.php';

// Obtener id desde GET o POST
$id = null;
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = (int) $_POST['id'];
}

if ($id) {
    // Preparar y ejecutar SELECT
    $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo '<div class="alert alert-danger">Usuario no encontrado</div>';
        exit();
    }
    $stmt->close();
} else {
    echo '<div class="alert alert-danger">ID no proporcionado</div>';
    exit();
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $id = (int) $_POST['id'];
    $stmt = $conexion->prepare('UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?');
    $stmt->bind_param('ssi', $nombre, $email, $id);
    if ($stmt->execute() === TRUE) {
        $stmt->close();
        header('Location: index.php');
        exit();
    } else {
        echo '<div class="alert alert-danger mt-3">Error: ' . $conexion->error . '</div>';
    }
    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
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
                <h3 class="card-title mb-3">Editar usuario</h3>
                <p class="text-muted small">Modifica los datos y guarda los cambios.</p>

                <form action="" method="POST" novalidate>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($row['nombre'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>