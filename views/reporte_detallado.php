<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../models/Usuario.php';
require_once '../models/Transaccion.php';

$usuarioModel = new Usuario();
$transaccionModel = new Transaccion();

$usuario_id = $usuarioModel->obtenerIdPorNombre($_SESSION['usuario']);
$transacciones = $transaccionModel->obtenerTodas($usuario_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Detallado</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../public/css/reporte_detallado.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Reporte Detallado de Transacciones</h1>
            <div class="acciones">
                <a href="dashboard.php" class="btn-back">← Volver</a>
                <form action="../controllers/ReporteController.php" method="post">
                    <button type="submit" class="btn-exportar">📄 Exportar PDF</button>
                </form>
            </div>
        </header>

        <table id="tablaTransacciones" class="display">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transacciones as $t): ?>
                    <tr>
                        <td><?= ucfirst($t['tipo']) ?></td>
                        <td><?= htmlspecialchars($t['categoria']) ?></td>
                        <td>$<?= number_format($t['monto'], 2) ?></td>
                        <td><?= $t['fecha'] ?></td>
                        <td>
                            <?php if ($t['factura_path']): ?>
                                <a href="../<?= $t['factura_path'] ?>" target="_blank">Ver factura</a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tablaTransacciones').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                }
            });
        });
    </script>
</body>
</html>
