<?php

// iniciar sesión para verificar si el usuario está logueado
session_start();
// Verificar si el usuario está logueado
$logged = !empty($_SESSION['user_id']);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Básico</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .hero {
            max-width: 720px;
            margin: 48px auto;
        }

        .card-ghost {
            background: linear-gradient(180deg, #ffffffcc, #f1f3f5cc);
        }

        .table-wrap {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    
    <div class="hero">
        <div class="card card-ghost shadow-sm">
            <div class="card-body text-center">

                <h1 class="card-title mb-2">CRUD Básico</h1>
                <p class="text-muted mb-4">Gestión de usuarios con PHP y Bootstrap.</p>
                <!-- Mostrar opciones según el estado de autenticación -->
                <?php if (!$logged): ?>
                    <a href="login.php" class="btn btn-primary btn-lg">Iniciar sesión</a>
                <?php else: ?>

                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-success align-self-center">Conectado: <?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        <a href="crear.php" class="btn btn-outline-primary">+ Nuevo usuario</a>
                        <a href="logout.php" class="btn btn-outline-secondary">Cerrar sesión</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($logged): ?>
        <div class="container mb-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Lista de Usuarios</h5>
                            <div class="table-wrap">
                                <table class="table table-hover table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Email</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        include 'conexion.php';
                                        $sql = "SELECT id,nombre,email FROM usuarios ORDER BY id DESC";
                                        $result = $conexion->query($sql);
                                        if ($result && $result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<tr>';
                                                echo '<td>' . $row['id'] . '</td>';
                                                echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                                                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                                                echo '<td>';
                                                echo '<a href="editar.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning me-1">Editar</a>';
                                                echo '<a href="eliminar.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Seguro que deseas eliminar el registro?\')">Eliminar</a>';
                                                echo '</td>';
                                                echo '</tr>';
                                            }
                                        } else {
                                            echo '<tr><td colspan="4" class="text-center">No hay usuarios</td></tr>';
                                        }
                                        $conexion->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>