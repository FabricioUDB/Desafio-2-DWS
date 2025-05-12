<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Entrada</title>
    <link rel="stylesheet" href="../public/css/registrar_entrada.css">
</head>
<body>
    <div class="container">
        <h1>Registrar Nueva Entrada</h1>
        <form action="../controllers/TransaccionController.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="tipo" value="entrada">

            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" placeholder="Ej. Salario, Venta..." required>

            <label for="monto">Monto</label>
            <input type="number" id="monto" name="monto" placeholder="Ej. 100.00" step="0.01" required>

            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" required>

            <label for="factura">Factura (opcional)</label>
            <input type="file" id="factura" name="factura" accept="image/*">

            <button type="submit" name="registrar">Registrar Entrada</button>
        </form>
        <a href="dashboard.php" class="btn-back">← Volver al Dashboard</a>
    </div>
</body>
</html>
