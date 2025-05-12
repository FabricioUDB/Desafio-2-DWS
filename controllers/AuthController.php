<?php
session_start();
require_once '../models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // ⚠️ En producción usa password_hash

    $usuarioModel = new Usuario();
    $credencialesValidas = $usuarioModel->verificarCredenciales($username, $password);

    if ($credencialesValidas) {
        $_SESSION['usuario'] = $username;
        header("Location: ../views/dashboard.php");
    } else {
        echo "Credenciales inválidas.";
    }
}

