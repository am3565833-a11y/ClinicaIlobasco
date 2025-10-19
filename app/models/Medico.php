<?php
class Medico {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Listar todos los médicos activos
    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT m.medico_id, m.nombre, m.apellido, e.nombre AS especialidad, m.telefono, m.email, m.estado
            FROM clinic.medicos m
            LEFT JOIN clinic.especialidades e ON m.especialidad_id = e.especialidad_id
            WHERE m.estado = 'Activo'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener médico por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM clinic.medicos WHERE medico_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo médico
    public function create($nombre, $apellido, $telefono, $email, $especialidad_id, $jornada_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO clinic.medicos (nombre, apellido, telefono, email, especialidad_id, jornada_id, estado)
            VALUES (:n, :a, :t, :e, :esp, :j, 'Activo')
        ");
        $stmt->bindParam(':n', $nombre);
        $stmt->bindParam(':a', $apellido);
        $stmt->bindParam(':t', $telefono);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':esp', $especialidad_id);
        $stmt->bindParam(':j', $jornada_id);
        return $stmt->execute();
    }

    // Actualizar médico
    public function update($id, $nombre, $apellido, $telefono, $email, $especialidad_id, $jornada_id) {
        $stmt = $this->conn->prepare("
            UPDATE clinic.medicos
            SET nombre = :n, apellido = :a, telefono = :t, email = :e, especialidad_id = :esp, jornada_id = :j
            WHERE medico_id = :id
        ");
        $stmt->bindParam(':n', $nombre);
        $stmt->bindParam(':a', $apellido);
        $stmt->bindParam(':t', $telefono);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':esp', $especialidad_id);
        $stmt->bindParam(':j', $jornada_id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar médico (estado inactivo)
    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE clinic.medicos SET estado = 'Inactivo' WHERE medico_id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
