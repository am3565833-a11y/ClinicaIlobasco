<?php
require_once __DIR__ . 'config/database.php';
require_once __DIR__ . 'app/helpers/SecurityHelper.php';

class Usuario {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Buscar usuario por nombre
    public function obtenerPorNombre($nombre_usuario) {
        $query = "SELECT * FROM clinic.usuarios WHERE nombre_usuario = :nombre_usuario AND estado = 'Activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Verificar credenciales
    public function verificarCredenciales($nombre_usuario, $password) {
        $usuario = $this->obtenerPorNombre($nombre_usuario);
        if ($usuario) {
            // reconstruye hash SHA2_512 con el salt (como en SQL Server)
            $salt = $usuario['password_salt'];
            $hashPHP = hash('sha512', $salt . $password, true);
            return ($hashPHP === $usuario['password_hash']) ? $usuario : false;
        }
        return false;
    }
}
?>
