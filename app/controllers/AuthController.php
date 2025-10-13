<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';
require_once __DIR__ . '/../helpers/SecurityHelper.php';

class AuthController {

    public static function login($conn) {
        SessionHelper::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = SecurityHelper::sanitize($_POST['usuario']);
            $password = SecurityHelper::sanitize($_POST['password']);

            $modelo = new Usuario($conn);
            $usuarioData = $modelo->verificarCredenciales($usuario, $password);

            if ($usuarioData) {
                SessionHelper::set('usuario_id', $usuarioData['usuario_id']);
                SessionHelper::set('nombre_usuario', $usuarioData['nombre_usuario']);
                SessionHelper::set('rol_id', $usuarioData['rol_id']);

                header("Location: ../../index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos";
                include __DIR__ . '/../../views/auth/login.php';
            }
        }
    }

    public static function logout() {
        SessionHelper::start();
        SessionHelper::destroy();
        header("Location: ../../views/auth/login.php");
        exit();
    }
}
?>
