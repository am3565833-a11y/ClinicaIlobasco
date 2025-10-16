<?php
// login_action.php
require_once __DIR__ . '/../../config/database.php'; // debe definir $conn (PDO)
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/auth/login.php');
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$password = $_POST['password'] ?? '';

if ($usuario === '' || $password === '') {
    $_SESSION['login_error'] = 'Ingrese usuario y contraseña';
    header('Location: ../../views/auth/login.php');
    exit;
}

try {
    $sql = "SELECT usuario_id, nombre_usuario, password_hash, rol_id FROM clinic.usuarios WHERE nombre_usuario = :u AND estado = 'Activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':u', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // password_hash puede ser varbinary (SHA2_512) o password_hash() de PHP.
        // Intentamos primero password_verify (por compatibilidad), luego fallback SHA2_512 raw.
        $stored = $user['password_hash'];

        $isValid = false;

        // Caso 1: stored as PHP password_hash string
        if (is_string($stored) && (strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0)) {
            $isValid = password_verify($password, $stored);
        } else {
            // Caso 2: stored as binary varbinary from SQL HASHBYTES (SHA2_512)
            // En PDO puede venir como resource/stream; convertimos a string raw
            if (is_resource($stored)) {
                $raw = stream_get_contents($stored);
            } else {
                $raw = $stored;
            }
            // Si $raw está en binario, comparamos con hash('sha512', $salt + $password, true)
            // Nota: necesitas el salt almacenado; si usas password_salt en tu tabla, recupéralo
            // Aquí asumo que guardaste password_salt en la misma fila:
            $stmt2 = $conn->prepare("SELECT password_salt FROM clinic.usuarios WHERE usuario_id = :id");
            $stmt2->bindParam(':id', $user['usuario_id']);
            $stmt2->execute();
            $saltRow = $stmt2->fetch(PDO::FETCH_ASSOC);
            if ($saltRow && isset($saltRow['password_salt'])) {
                $salt = $saltRow['password_salt'];
                // si salt viene como resource convertir
                if (is_resource($salt)) $salt = stream_get_contents($salt);
                $computed = hash('sha512', $salt . $password, true); // raw binary
                // comparación segura
                if (strlen($raw) === strlen($computed) && hash_equals($raw, $computed)) {
                    $isValid = true;
                }
            }
        }

        if ($isValid) {
            // Regenerar id de sesión para evitar fijación
            session_regenerate_id(true);
            $_SESSION['usuario_id']   = $user['usuario_id'];
            $_SESSION['nombre_usuario']= $user['nombre_usuario'];
            $_SESSION['rol_id']       = $user['rol_id'] ?? null;
            header('Location: ../../index.php');
            exit;
        }
    }

    $_SESSION['login_error'] = 'Usuario o contraseña incorrectos';
    header('Location: ../../views/auth/login.php');
    exit;

} catch (PDOException $e) {
    // En desarrollo puedes mostrar el error, en producción logger
    die("Error en la base de datos: " . $e->getMessage());
}
