<?php
class SessionHelper {

    // Inicia la sesión si aún no está activa
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Guarda una variable de sesión
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // Obtiene una variable de sesión
    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    // Verifica si el usuario está logueado
    public static function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }

    // Elimina todas las variables y destruye la sesión
    public static function destroy() {
        // Limpia variables
        $_SESSION = [];

        // Elimina la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finalmente destruye la sesión
        session_destroy();
    }
}
?>
