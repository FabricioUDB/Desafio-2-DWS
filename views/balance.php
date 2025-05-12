<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Obtener ID del usuario actual
$stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE username = :username");
$stmtUser->bindParam(':username', $_SESSION['usuario']);
$stmtUser->execute();
$usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
$usuario_id = $usuario ? $usuario['id'] : null;

$stmtEntradas = $conn->prepare("SELECT SUM(monto) as total FROM transacciones WHERE tipo = 'entrada' AND usuario_id = :usuario_id");
$stmtEntradas->bindParam(':usuario_id', $usuario_id);
$stmtEntradas->execute();
$totalEntradas = $stmtEntradas->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$stmtSalidas = $conn->prepare("SELECT SUM(monto) as total FROM transacciones WHERE tipo = 'salida' AND usuario_id = :usuario_id");
$stmtSalidas->bindParam(':usuario_id', $usuario_id);
$stmtSalidas->execute();
$totalSalidas = $stmtSalidas->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$balance = $totalEntradas - $totalSalidas;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen de Balance</title>
    <link rel="stylesheet" href="../public/css/balance.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Resumen financiero</h1>
            <a href="dashboard.php" class="btn-back">← Volver</a>
        </header>

        <div class="cards">
            <div class="card entrada">
                <h3>Total Entradas</h3>
                <p>$<?= number_format($totalEntradas, 2) ?></p>
            </div>
            <div class="card salida">
                <h3>Total Salidas</h3>
                <p>$<?= number_format($totalSalidas, 2) ?></p>
            </div>
            <div class="card balance">
                <h3>Balance Final</h3>
                <p>$<?= number_format($balance, 2) ?></p>
            </div>
        </div>

        <div class="chart-section">
            <canvas id="graficoBalance"></canvas>
        </div>

        <div class="acciones">
            <form action="../controllers/PDFController.php" method="post">
                <button type="submit" class="btn-pdf">📄 Exportar a PDF</button>
            </form>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('graficoBalance').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Entradas', 'Salidas'],
                datasets: [{
                    label: 'Distribución',
                    data: [<?= $totalEntradas ?>, <?= $totalSalidas ?>],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
