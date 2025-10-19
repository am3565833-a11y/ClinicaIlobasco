<?php
class Factura {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Obtener todas las facturas
    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT f.factura_id, p.nombre AS paciente, f.fecha_emision, f.total, f.estado
            FROM clinic.facturas f
            INNER JOIN clinic.pacientes p ON f.paciente_id = p.paciente_id
            ORDER BY f.fecha_emision DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una factura por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("
            SELECT f.*, p.nombre AS paciente
            FROM clinic.facturas f
            INNER JOIN clinic.pacientes p ON f.paciente_id = p.paciente_id
            WHERE f.factura_id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva factura
    public function create($paciente_id, $detalle, $total) {
        $stmt = $this->conn->prepare("
            INSERT INTO clinic.facturas (paciente_id, detalle, total, fecha_emision, estado)
            VALUES (:p, :d, :t, GETDATE(), 'Emitida')
        ");
        $stmt->bindParam(':p', $paciente_id);
        $stmt->bindParam(':d', $detalle);
        $stmt->bindParam(':t', $total);
        return $stmt->execute();
    }

    // Cambiar estado de factura (Ej: Pagada o Anulada)
    public function updateEstado($id, $estado) {
        $stmt = $this->conn->prepare("UPDATE clinic.facturas SET estado = :e WHERE factura_id = :id");
        $stmt->bindParam(':e', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
