<?php
class Paciente {
    private $conn;

    public function __construct($pdo) {
        $this->conn = $pdo;
    }

    // Listar todos los pacientes activos
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM clinic.pacientes WHERE estado = 'Activo'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener paciente por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM clinic.pacientes WHERE paciente_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear nuevo paciente
    public function create($nombre, $apellido, $telefono, $email) {
        $stmt = $this->conn->prepare("INSERT INTO clinic.pacientes (nombre, apellido, telefono, email, estado) VALUES (:n, :a, :t, :e, 'Activo')");
        $stmt->bindParam(':n', $nombre);
        $stmt->bindParam(':a', $apellido);
        $stmt->bindParam(':t', $telefono);
        $stmt->bindParam(':e', $email);
        return $stmt->execute();
    }

    // Actualizar paciente
    public function update($id, $nombre, $apellido, $telefono, $email) {
        $stmt = $this->conn->prepare("UPDATE clinic.pacientes SET nombre = :n, apellido = :a, telefono = :t, email = :e WHERE paciente_id = :id");
        $stmt->bindParam(':n', $nombre);
        $stmt->bindParam(':a', $apellido);
        $stmt->bindParam(':t', $telefono);
        $stmt->bindParam(':e', $email);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar paciente (cambiar estado a Inactivo)
    public function delete($id) {
        $stmt = $this->conn->prepare("UPDATE clinic.pacientes SET estado = 'Inactivo' WHERE paciente_id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
