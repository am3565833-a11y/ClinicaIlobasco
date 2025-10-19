<?php
class Cita {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Obtener todas las citas activas
    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT c.cita_id, p.nombre AS paciente, m.nombre AS medico, 
                   e.nombre AS especialidad, c.fecha, c.hora, c.estado
            FROM clinic.citas c
            INNER JOIN clinic.pacientes p ON c.paciente_id = p.paciente_id
            INNER JOIN clinic.medicos m ON c.medico_id = m.medico_id
            LEFT JOIN clinic.especialidades e ON m.especialidad_id = e.especialidad_id
            WHERE c.estado != 'Cancelada'
            ORDER BY c.fecha DESC, c.hora DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener una cita por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM clinic.citas WHERE cita_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear una nueva cita
    public function create($paciente_id, $medico_id, $fecha, $hora, $motivo) {
        $stmt = $this->conn->prepare("
            INSERT INTO clinic.citas (paciente_id, medico_id, fecha, hora, motivo, estado)
            VALUES (:p, :m, :f, :h, :mot, 'Programada')
        ");
        $stmt->bindParam(':p', $paciente_id);
        $stmt->bindParam(':m', $medico_id);
        $stmt->bindParam(':f', $fecha);
        $stmt->bindParam(':h', $hora);
        $stmt->bindParam(':mot', $motivo);
        return $stmt->execute();
    }

    // Actualizar una cita
    public function update($id, $paciente_id, $medico_id, $fecha, $hora, $motivo, $estado) {
        $stmt = $this->conn->prepare("
            UPDATE clinic.citas
            SET paciente_id = :p, medico_id = :m, fecha = :f, hora = :h, motivo = :mot, estado = :e
            WHERE cita_id = :id
        ");
        $stmt->bindParam(':p', $paciente_id);
        $stmt->bindParam(':m', $medico_id);
        $stmt->bindParam(':f', $fecha);
        $stmt->bindParam(':h', $hora);
        $stmt->bindParam(':mot', $motivo);
        $stmt->bindParam(':e', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Cancelar una cita
    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE clinic.citas SET estado = 'Cancelada' WHERE cita_id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
