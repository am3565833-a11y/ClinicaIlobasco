<?php
$server = "DESKTOP-248TJ3G\SQLEXPRESS";
$database = "ClinicaInternaSimple";
$user = "sa";
$password = "fran24";

try {
    $conn = new PDO("sqlsrv:Server=$server;Database=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conectado correctamente a SQL Server";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
