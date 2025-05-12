<?php
session_start();
require_once '../models/Transaccion.php';
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    if (!isset($_SESSION['usuario'])) {
        die("Acceso no autorizado.");
    }

    $tipo = $_POST['tipo'];
    $categoria = $_POST['categoria'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];

    $usuarioModel = new Usuario();
    $usuario_id = $usuarioModel->obtenerIdPorNombre($_SESSION['usuario']);

    if (!$usuario_id) {
        die("No se pudo identificar el usuario.");
    }

    // Procesar factura (imagen)
    $facturaPath = null;
    if (!empty($_FILES['factura']['name'])) {
        $targetDir = "../uploads/";
        $fileName = basename($_FILES["factura"]["name"]);
        $targetFilePath = $targetDir . time() . "_" . $fileName;

        if (move_uploaded_file($_FILES["factura"]["tmp_name"], $targetFilePath)) {
            $facturaPath = str_replace("../", "", $targetFilePath);
        } else {
            echo "Error al subir la factura.";
        }
    }

    $transaccionModel = new Transaccion();
    $guardado = $transaccionModel->registrar($tipo, $categoria, $monto, $fecha, $facturaPath, $usuario_id);

    if ($guardado) {
        header("Location: ../views/dashboard.php");
    } else {
        echo "❌ Error al guardar la transacción.";
    }
}
