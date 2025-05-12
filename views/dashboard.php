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
    <title>Panel Principal - Finanzas</title>
    <link rel="stylesheet" href="../public/css/dashboard.css">
</head>
<body>
    <header>
        <h1>Bienvenido, <?= $_SESSION['usuario'] ?> 👋</h1>
        <a href="login.php" class="logout">Cerrar sesión</a>
    </header>

    <main class="main-panel">
        <section class="menu">
            <a href="registrar_entrada.php" class="card">📥 Registrar Entrada</a>
            <a href="registrar_salida.php" class="card">📤 Registrar Salida</a>
            <a href="ver_entradas.php" class="card">📄 Ver Entradas</a>
            <a href="ver_salidas.php" class="card">📄 Ver Salidas</a>
            <a href="balance.php" class="card">📊 Mostrar Balance</a>
            <a href="reporte_detallado.php" class="card">📋 Ver Reporte Detallado</a>
        </section>

        <section class="acciones">
            <form action="../controllers/PDFController.php" method="post">
                <button type="submit" class="btn exportar">📄 Exportar balance (resumen)</button>
            </form>
            <form action="../controllers/ReporteController.php" method="post">
                <button type="submit" class="btn exportar">📑 Exportar reporte completo</button>
            </form>
        </section>
    </main>
</body>
</html>
