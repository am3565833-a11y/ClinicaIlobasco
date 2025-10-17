<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/app_config.php';
require_once __DIR__ . '/app/helpers/SessionHelper.php';

// Inicia la sesión de manera segura
SessionHelper::start();

// Verifica si el usuario está logueado
if (!SessionHelper::isLoggedIn()) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit();
}

// Incluye el encabezado y el contenido del dashboard
require_once __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/views/dashboard/home.php';
require_once __DIR__ . '/views/layouts/footer.php';
?>
