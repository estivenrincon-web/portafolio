<?php
session_start();
// Si ya está logueado, redirigir
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <nav class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded">
            <a href="index.php" class="h4 text-decoration-none">CRUD Básico</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <div>
                    <span class="badge bg-success me-2"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <a href="logout.php" class="btn btn-sm btn-outline-secondary">Cerrar sesión</a>
                </div>
            <?php else: ?>
                <a href="crear.php" class="btn btn-sm btn-primary">Crear cuenta</a>
            <?php endif; ?>
        </nav>

        <div class="card mx-auto" style="max-width:520px;">
            <div class="card-body">
                <h3 class="card-title mb-3">Iniciar sesión</h3>

                <?php if (!empty($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <form action="autenticar.php" method="POST" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="clave" name="clave" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                        <a href="crear.php" class="btn btn-outline-secondary">Crear cuenta</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>