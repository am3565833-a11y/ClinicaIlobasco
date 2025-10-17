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
    // Traemos el hash y el salt en una sola consulta
    $sql = "SELECT usuario_id, nombre_usuario, password_hash, password_salt, rol_id 
            FROM clinic.usuarios 
            WHERE nombre_usuario = :u AND estado = 'Activo'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':u', $usuario);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $isValid = false;

    if ($user) {
        $stored = $user['password_hash'];
        $salt   = $user['password_salt'];

        // Si PDO devuelve recursos (LOB), convertirlos a string binario
        if (is_resource($stored)) $stored = stream_get_contents($stored);
        if (is_resource($salt)) $salt = stream_get_contents($salt);

        // Caso 1: PHP password_hash() (bcrypt/argon2)
        if (is_string($stored) && (strpos($stored, '$2y$') === 0 || strpos($stored, '$argon2') === 0)) {
            $isValid = password_verify($password, $stored);
        } else {
            // Caso 2: VARBINARY SHA2_512 - important: SQL Server convierte NVARCHAR -> VARBINARY en UTF-16LE
            // Convertimos la contraseña a UTF-16LE para que los bytes coincidan con HASHBYTES('SHA2_512', salt + CONVERT(VARBINARY, @password))
            $password_bytes = mb_convert_encoding($password, 'UTF-16LE');

            // $salt y $stored son cadenas binarias con contenido raw (no hex)
            // Calculamos SHA-512 en raw binary
            $computed = hash('sha512', $salt . $password_bytes, true);

            if (is_string($stored) && strlen($stored) === strlen($computed) && hash_equals($stored, $computed)) {
                $isValid = true;
            }
        }
    }

    if ($isValid) {
        session_regenerate_id(true);
        $_SESSION['usuario_id']    = $user['usuario_id'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['rol_id']        = $user['rol_id'] ?? null;

        header('Location: ../../index.php');
        exit;
    }

    $_SESSION['login_error'] = 'Usuario o contraseña incorrectos';
    header('Location: ../../views/auth/login.php');
    exit;

} catch (PDOException $e) {
    // En desarrollo puedes loguear el error; en producción evita mostrar detalles
    die("Error en la base de datos: " . $e->getMessage());
}
