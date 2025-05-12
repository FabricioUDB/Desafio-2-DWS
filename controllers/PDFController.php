<?php
require '../vendor/autoload.php';
require_once '../config/database.php';

use Dompdf\Dompdf;

session_start();
if (!isset($_SESSION['usuario'])) {
    die("Acceso no autorizado");
}

$db = new Database();
$conn = $db->getConnection();

// Obtener ID de usuario
$stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE username = :username");
$stmtUser->bindParam(':username', $_SESSION['usuario']);
$stmtUser->execute();
$usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
$usuario_id = $usuario ? $usuario['id'] : null;

// Obtener totales
$stmtEntradas = $conn->prepare("SELECT SUM(monto) as total FROM transacciones WHERE tipo = 'entrada' AND usuario_id = :usuario_id");
$stmtEntradas->bindParam(':usuario_id', $usuario_id);
$stmtEntradas->execute();
$totalEntradas = $stmtEntradas->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$stmtSalidas = $conn->prepare("SELECT SUM(monto) as total FROM transacciones WHERE tipo = 'salida' AND usuario_id = :usuario_id");
$stmtSalidas->bindParam(':usuario_id', $usuario_id);
$stmtSalidas->execute();
$totalSalidas = $stmtSalidas->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$balance = $totalEntradas - $totalSalidas;

// Generar HTML para PDF
$html = "
    <h2>Reporte Financiero</h2>
    <p><strong>Usuario:</strong> {$_SESSION['usuario']}</p>
    <p><strong>Total Entradas:</strong> $" . number_format($totalEntradas, 2) . "</p>
    <p><strong>Total Salidas:</strong> $" . number_format($totalSalidas, 2) . "</p>
    <p><strong>Balance Final:</strong> $" . number_format($balance, 2) . "</p>
";

// Crear instancia de Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Descargar PDF
$dompdf->stream("reporte_balance.pdf", ["Attachment" => true]);
exit();
