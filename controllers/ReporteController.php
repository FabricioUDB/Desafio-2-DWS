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

// Obtener ID del usuario actual
$stmtUser = $conn->prepare("SELECT id FROM usuarios WHERE username = :username");
$stmtUser->bindParam(':username', $_SESSION['usuario']);
$stmtUser->execute();
$usuario = $stmtUser->fetch(PDO::FETCH_ASSOC);
$usuario_id = $usuario ? $usuario['id'] : null;

// Obtener transacciones completas
$stmt = $conn->prepare("SELECT * FROM transacciones WHERE usuario_id = :usuario_id ORDER BY fecha DESC");
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar HTML para el PDF
$html = "<h2>Reporte Detallado de Transacciones</h2>";
$html .= "<p>Usuario: {$_SESSION['usuario']}</p>";
$html .= "<table border='1' cellpadding='5' cellspacing='0' width='100%'>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Categoría</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>";

foreach ($transacciones as $t) {
    $html .= "<tr>
                <td>{$t['tipo']}</td>
                <td>{$t['categoria']}</td>
                <td>$" . number_format($t['monto'], 2) . "</td>
                <td>{$t['fecha']}</td>
              </tr>";
}

$html .= "</tbody></table>";

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_detallado.pdf", ["Attachment" => true]);
exit();
