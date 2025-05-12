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
$salidas = $transaccionModel->obtenerPorTipo('salida', $usuario_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Salidas</title>
    <link rel="stylesheet" href="../public/css/ver_transacciones.css">
</head>
<body>
    <div class="container">
        <h1>Listado de Salidas</h1>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                    <th>Factura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($salidas as $salida): ?>
                    <tr>
                        <td><?= htmlspecialchars($salida['categoria']) ?></td>
                        <td>$<?= number_format($salida['monto'], 2) ?></td>
                        <td><?= $salida['fecha'] ?></td>
                        <td>
                            <?php if ($salida['factura_path']): ?>
                                <a href="../<?= $salida['factura_path'] ?>" target="_blank">Ver factura</a>
                            <?php else: ?>
                                No disponible
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn-back">← Volver al Dashboard</a>
    </div>
</body>
</html>
