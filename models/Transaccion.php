<?php
require_once __DIR__ . '/../config/database.php';

class Transaccion {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function registrar($tipo, $categoria, $monto, $fecha, $facturaPath, $usuario_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO transacciones (tipo, categoria, monto, fecha, factura_path, usuario_id)
            VALUES (:tipo, :categoria, :monto, :fecha, :factura_path, :usuario_id)
        ");

        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':factura_path', $facturaPath);
        $stmt->bindParam(':usuario_id', $usuario_id);

        return $stmt->execute();
    }

    public function obtenerPorTipo($tipo, $usuario_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM transacciones
            WHERE tipo = :tipo AND usuario_id = :usuario_id
            ORDER BY fecha DESC
        ");
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTotales($tipo, $usuario_id) {
        $stmt = $this->conn->prepare("
            SELECT SUM(monto) as total FROM transacciones
            WHERE tipo = :tipo AND usuario_id = :usuario_id
        ");
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function obtenerTodas($usuario_id) {
        $stmt = $this->conn->prepare("
            SELECT * FROM transacciones
            WHERE usuario_id = :usuario_id
            ORDER BY fecha DESC
        ");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
