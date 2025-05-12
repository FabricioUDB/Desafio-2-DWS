<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function verificarCredenciales($username, $password) {
        $stmt = $this->conn->prepare("
            SELECT * FROM usuarios
            WHERE username = :username AND password = :password
        ");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        return $stmt->rowCount() === 1;
    }

    public function obtenerIdPorNombre($username) {
        $stmt = $this->conn->prepare("
            SELECT id FROM usuarios
            WHERE username = :username
        ");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['id'] : null;
    }
}
